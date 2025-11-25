<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $query = SuratKeluar::with('user');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_surat', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_surat', '<=', $request->tanggal_sampai);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nomor_surat', 'like', "%{$search}%")
                  ->orWhere('tujuan', 'like', "%{$search}%")
                  ->orWhere('perihal', 'like', "%{$search}%")
                  ->orWhere('penandatangan', 'like', "%{$search}%");
            });
        }

        $suratKeluar = $query->latest()->paginate(10);

        return view('surat-keluar.index', compact('suratKeluar'));
    }

    public function create()
    {
        $nomorSurat = $this->generateNomorSurat();
        return view('surat-keluar.create', compact('nomorSurat'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_surat' => 'required|unique:surat_keluar,nomor_surat|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|max:200',
            'perihal' => 'required',
            'penandatangan' => 'required|max:100',
            'prioritas' => 'required|in:biasa,penting,segera',
            'sifat' => 'required|in:biasa,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::id();
        $data['status'] = 'draft';

        if ($request->hasFile('file_surat')) {
            $file = $request->file('file_surat');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/surat_keluar'), $filename);
            $data['file_surat'] = $filename;
        }

        SuratKeluar::create($data);

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Tambah Surat Keluar',
            'modul' => 'Surat Keluar',
            'keterangan' => 'Menambah surat keluar nomor: ' . $request->nomor_surat,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil ditambahkan!');
    }

    public function show(SuratKeluar $suratKeluar)
    {
        $suratKeluar->load('user');
        return view('surat-keluar.show', compact('suratKeluar'));
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        return view('surat-keluar.edit', compact('suratKeluar'));
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $request->validate([
            'nomor_surat' => 'required|unique:surat_keluar,nomor_surat,' . $suratKeluar->id . '|max:100',
            'tanggal_surat' => 'required|date',
            'tujuan' => 'required|max:200',
            'perihal' => 'required',
            'penandatangan' => 'required|max:100',
            'prioritas' => 'required|in:biasa,penting,segera',
            'sifat' => 'required|in:biasa,rahasia,sangat_rahasia',
            'file_surat' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->except(['file_surat']);

        if ($request->hasFile('file_surat')) {
            try {
                if ($suratKeluar->file_surat && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat))) {
                    unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
                }

                $file = $request->file('file_surat');
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
                $file->move(public_path('uploads/surat_keluar'), $filename);
                $data['file_surat'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        $suratKeluar->update($data);

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Surat Keluar',
            'modul' => 'Surat Keluar',
            'keterangan' => 'Mengupdate surat keluar nomor: ' . $suratKeluar->nomor_surat,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil diupdate!');
    }

    public function destroy(Request $request, SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->file_surat && file_exists(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat))) {
            unlink(public_path('uploads/surat_keluar/' . $suratKeluar->file_surat));
        }

        $nomorSurat = $suratKeluar->nomor_surat;
        $suratKeluar->delete();

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Surat Keluar',
            'modul' => 'Surat Keluar',
            'keterangan' => 'Menghapus surat keluar nomor: ' . $nomorSurat,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('surat-keluar.index')
            ->with('success', 'Surat keluar berhasil dihapus!');
    }

    public function download(SuratKeluar $suratKeluar)
    {
        if (!$suratKeluar->file_surat) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        $filepath = public_path('uploads/surat_keluar/' . $suratKeluar->file_surat);
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filepath);
    }

    public function approve(Request $request, SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status != 'draft') {
            return redirect()->back()->with('error', 'Surat sudah diapprove sebelumnya!');
        }

        $suratKeluar->update(['status' => 'approved']);

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Approve Surat Keluar',
            'modul' => 'Surat Keluar',
            'keterangan' => 'Approve surat keluar nomor: ' . $suratKeluar->nomor_surat,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Surat berhasil diapprove!');
    }

    public function sent(Request $request, SuratKeluar $suratKeluar)
    {
        if ($suratKeluar->status == 'draft') {
            return redirect()->back()->with('error', 'Surat harus diapprove dulu!');
        }

        $suratKeluar->update(['status' => 'sent']);

        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Kirim Surat Keluar',
            'modul' => 'Surat Keluar',
            'keterangan' => 'Menandai surat keluar nomor: ' . $suratKeluar->nomor_surat . ' sebagai terkirim',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Surat ditandai sebagai terkirim!');
    }

    private function generateNomorSurat()
    {
        $tahun = date('Y');
        
        $lastSurat = SuratKeluar::whereYear('tanggal_surat', $tahun)
            ->orderBy('nomor_surat', 'desc')
            ->first();

        if ($lastSurat) {
            preg_match('/\d+/', $lastSurat->nomor_surat, $matches);
            $lastNumber = isset($matches[0]) ? (int)$matches[0] : 0;
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return str_pad($newNumber, 3, '0', STR_PAD_LEFT) . '/SK-TU/' . $tahun;
    }
}