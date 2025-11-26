<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ArsipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Arsip::with(['kategori', 'user']);

        // Filter berdasarkan kategori
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_dokumen', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_dokumen', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_dokumen', 'like', "%{$search}%")
                  ->orWhere('judul', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhere('tags', 'like', "%{$search}%");
            });
        }

        $arsip = $query->latest()->paginate(12);
        $kategoriList = Kategori::orderBy('nama_kategori')->get();

        return view('arsip.index', compact('arsip', 'kategoriList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        $nomorDokumen = $this->generateNomorDokumen();
        
        return view('arsip.create', compact('kategoriList', 'nomorDokumen'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_dokumen' => 'required|max:100',
            'judul' => 'required|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal_dokumen' => 'required|date',
            'file_dokumen' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'tags' => 'nullable|max:255',
        ], [
            'nomor_dokumen.required' => 'Nomor dokumen harus diisi',
            'judul.required' => 'Judul dokumen harus diisi',
            'kategori_id.required' => 'Kategori harus dipilih',
            'tanggal_dokumen.required' => 'Tanggal dokumen harus diisi',
            'file_dokumen.required' => 'File dokumen harus diupload',
            'file_dokumen.mimes' => 'File harus berformat: pdf, doc, docx, xls, xlsx, jpg, jpeg, png',
            'file_dokumen.max' => 'Ukuran file maksimal 10MB',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();

        // Upload file
        if ($request->hasFile('file_dokumen')) {
            try {
            $file = $request->file('file_dokumen');
                
                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
            $file->move(public_path('uploads/arsip'), $filename);
            $data['file_dokumen'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        Arsip::create($data);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Arsip',
            'modul' => 'Arsip',
            'keterangan' => 'Menambah arsip: ' . $request->judul,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Arsip $arsip)
    {
        $arsip->load(['kategori', 'user']);
        
        return view('arsip.show', compact('arsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arsip $arsip)
    {
        $kategoriList = Kategori::orderBy('nama_kategori')->get();
        
        return view('arsip.edit', compact('arsip', 'kategoriList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arsip $arsip)
    {
        $request->validate([
            'nomor_dokumen' => 'required|max:100',
            'judul' => 'required|max:200',
            'kategori_id' => 'required|exists:kategori,id',
            'tanggal_dokumen' => 'required|date',
            'file_dokumen' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
            'tags' => 'nullable|max:255',
        ]);

        $data = $request->all();

        // Upload file baru jika ada
        if ($request->hasFile('file_dokumen')) {
            try {
                // Hapus file lama jika ada
            if ($arsip->file_dokumen && file_exists(public_path('uploads/arsip/' . $arsip->file_dokumen))) {
                unlink(public_path('uploads/arsip/' . $arsip->file_dokumen));
            }

            $file = $request->file('file_dokumen');
                
                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
            $file->move(public_path('uploads/arsip'), $filename);
            $data['file_dokumen'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        $arsip->update($data);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Arsip',
            'modul' => 'Arsip',
            'keterangan' => 'Mengupdate arsip: ' . $arsip->judul,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Arsip $arsip)
    {
        // Hapus file
        if ($arsip->file_dokumen && file_exists(public_path('uploads/arsip/' . $arsip->file_dokumen))) {
            unlink(public_path('uploads/arsip/' . $arsip->file_dokumen));
        }

        $judul = $arsip->judul;
        $arsip->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Arsip',
            'modul' => 'Arsip',
            'keterangan' => 'Menghapus arsip: ' . $judul,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('arsip.index')
            ->with('success', 'Arsip berhasil dihapus!');
    }

    /**
     * Download file arsip
     */
    public function download(Arsip $arsip)
    {
        if (!$arsip->file_dokumen) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        $filepath = public_path('uploads/arsip/' . $arsip->file_dokumen);
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filepath);
    }

    /**
     * Generate nomor dokumen otomatis
     */
    private function generateNomorDokumen()
    {
        $tahun = date('Y');
        $bulan = date('m');
        
        $lastArsip = Arsip::whereYear('tanggal_dokumen', $tahun)
            ->whereMonth('tanggal_dokumen', $bulan)
            ->orderBy('nomor_dokumen', 'desc')
            ->first();

        if ($lastArsip) {
            // Ambil 4 digit terakhir dan tambah 1
            preg_match('/\d+/', $lastArsip->nomor_dokumen, $matches);
            $lastNumber = isset($matches[0]) ? (int)end($matches) : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'ARS-' . $tahun . $bulan . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}