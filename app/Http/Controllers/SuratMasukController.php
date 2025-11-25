<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuratMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SuratMasuk::with('user');

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_terima', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_terima', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_agenda', 'like', "%{$search}%")
                  ->orWhere('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('pengirim', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%");
            });
        }

        $suratMasuk = $query->latest()->paginate(10);

        return view('surat-masuk.index', compact('suratMasuk'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Generate nomor agenda otomatis
        $nomorAgenda = $this->generateNomorAgenda();
        
        return view('surat-masuk.create', compact('nomorAgenda'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nomor_agenda' => 'required|unique:surat_masuk,nomor_agenda',
            'nomor_surat' => 'required|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'pengirim' => 'required|max:200',
            'perihal' => 'required',
            'prioritas' => 'required|in:biasa,penting,segera',
            'sifat' => 'required|in:biasa,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'nomor_agenda.required' => 'Nomor agenda harus diisi',
            'nomor_agenda.unique' => 'Nomor agenda sudah digunakan',
            'nomor_surat.required' => 'Nomor surat harus diisi',
            'tanggal_surat.required' => 'Tanggal surat harus diisi',
            'tanggal_terima.required' => 'Tanggal terima harus diisi',
            'pengirim.required' => 'Pengirim harus diisi',
            'perihal.required' => 'Perihal harus diisi',
            'file_surat.mimes' => 'File harus berformat: pdf, doc, docx, jpg, jpeg, png',
            'file_surat.max' => 'Ukuran file maksimal 5MB',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'pending';

        // Upload file jika ada
        if ($request->hasFile('file_surat')) {
            try {
                $file = $request->file('file_surat');
                
                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
                $file->move(public_path('uploads/surat_masuk'), $filename);
                $data['file_surat'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        SuratMasuk::create($data);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Surat Masuk',
            'modul' => 'Surat Masuk',
            'keterangan' => 'Menambah surat masuk nomor: ' . $request->nomor_agenda,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratMasuk $suratMasuk)
    {
        $suratMasuk->load(['user', 'disposisi.kepadaUser', 'disposisi.dariUser']);
        
        return view('surat-masuk.show', compact('suratMasuk'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratMasuk $suratMasuk)
    {
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $request->validate([
            'nomor_agenda' => 'required|unique:surat_masuk,nomor_agenda,' . $suratMasuk->id,
            'nomor_surat' => 'required|max:100',
            'tanggal_surat' => 'required|date',
            'tanggal_terima' => 'required|date',
            'pengirim' => 'required|max:200',
            'perihal' => 'required',
            'prioritas' => 'required|in:biasa,penting,segera',
            'sifat' => 'required|in:biasa,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        // Ambil data dari request
        $data = $request->except(['file_surat']);

        // Upload file baru jika ada
        if ($request->hasFile('file_surat')) {
            try {
                // Hapus file lama jika ada
                if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
                    unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
                }

                $file = $request->file('file_surat');
                
                // Sanitize filename - hapus karakter aneh
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
                // Move file
                $file->move(public_path('uploads/surat_masuk'), $filename);
                $data['file_surat'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        // Update data
        $suratMasuk->update($data);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Surat Masuk',
            'modul' => 'Surat Masuk',
            'keterangan' => 'Mengupdate surat masuk nomor: ' . $suratMasuk->nomor_agenda,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, SuratMasuk $suratMasuk)
    {
        // Hapus file jika ada
        if ($suratMasuk->file_surat && file_exists(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat))) {
            unlink(public_path('uploads/surat_masuk/' . $suratMasuk->file_surat));
        }

        $nomorAgenda = $suratMasuk->nomor_agenda;
        $suratMasuk->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Surat Masuk',
            'modul' => 'Surat Masuk',
            'keterangan' => 'Menghapus surat masuk nomor: ' . $nomorAgenda,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-masuk.index')
            ->with('success', 'Surat masuk berhasil dihapus!');
    }

    /**
     * Download file surat
     */
    public function download(SuratMasuk $suratMasuk)
    {
        if (!$suratMasuk->file_surat) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        $filepath = public_path('uploads/surat_masuk/' . $suratMasuk->file_surat);
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filepath);
    }

    /**
     * Generate nomor agenda otomatis
     */
    private function generateNomorAgenda()
    {
        $tahun = date('Y');
        $bulan = date('m');
        
        $lastSurat = SuratMasuk::whereYear('tanggal_terima', $tahun)
            ->whereMonth('tanggal_terima', $bulan)
            ->orderBy('nomor_agenda', 'desc')
            ->first();

        if ($lastSurat) {
            // Ambil 4 digit terakhir dan tambah 1
            $lastNumber = (int) substr($lastSurat->nomor_agenda, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return 'AG-' . $tahun . $bulan . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}