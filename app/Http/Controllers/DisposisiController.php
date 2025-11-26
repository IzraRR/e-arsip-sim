<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DisposisiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Disposisi::with(['suratMasuk', 'dariUser', 'kepadaUser']);

        // Filter berdasarkan role
        $user = Auth::user();
        if ($user->role == 'staff') {
            // Staff hanya lihat disposisi yang ditujukan ke dia
            $query->where('kepada_user_id', $user->id);
        } elseif ($user->role == 'pimpinan') {
            // Pimpinan lihat disposisi dari dia dan ke dia
            $query->where(function($q) use ($user) {
                $q->where('kepada_user_id', $user->id)
                  ->orWhere('dari_user_id', $user->id);
            });
        }
        // Admin lihat semua

        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggal_disposisi', '>=', $request->tanggal_dari);
        }
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggal_disposisi', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('instruksi', 'like', "%{$search}%")
                  ->orWhereHas('suratMasuk', function($sq) use ($search) {
                      $sq->where('nomor_agenda', 'like', "%{$search}%")
                        ->orWhere('perihal', 'like', "%{$search}%");
                  });
            });
        }

        $disposisi = $query->latest()->paginate(10);

        return view('disposisi.index', compact('disposisi'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Ambil surat masuk dari parameter atau semua
        $suratMasukId = $request->get('surat_masuk_id');
        
        if ($suratMasukId) {
            $suratMasuk = SuratMasuk::findOrFail($suratMasukId);
        } else {
            $suratMasuk = null;
        }

        // Ambil semua surat masuk yang bisa didisposisi (status pending atau disposisi)
        $suratMasukList = SuratMasuk::whereIn('status', ['pending', 'disposisi'])
            ->orderBy('tanggal_terima', 'desc')
            ->get();

        // Ambil user yang bisa menerima disposisi (kecuali diri sendiri)
        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('disposisi.create', compact('suratMasuk', 'suratMasukList', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'surat_masuk_id' => 'required|exists:surat_masuk,id',
            'kepada_user_id' => 'required|exists:users,id',
            'instruksi' => 'required',
            'tanggal_disposisi' => 'required|date',
            'batas_waktu' => 'nullable|date|after_or_equal:tanggal_disposisi',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'surat_masuk_id.required' => 'Pilih surat masuk yang akan didisposisi',
            'kepada_user_id.required' => 'Pilih penerima disposisi',
            'instruksi.required' => 'Instruksi harus diisi',
            'tanggal_disposisi.required' => 'Tanggal disposisi harus diisi',
            'batas_waktu.after_or_equal' => 'Batas waktu tidak boleh sebelum tanggal disposisi',
        ]);

        $data = $request->all();
        $data['dari_user_id'] = Auth::id();
        $data['status'] = 'pending';

        // Upload file lampiran jika ada
        if ($request->hasFile('file_lampiran')) {
            try {
            $file = $request->file('file_lampiran');
                
                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
            $file->move(public_path('uploads/disposisi'), $filename);
            $data['file_lampiran'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        $disposisi = Disposisi::create($data);

        // Update status surat masuk menjadi "disposisi"
        $suratMasuk = SuratMasuk::find($request->surat_masuk_id);
        $suratMasuk->update(['status' => 'disposisi']);

        // Buat notifikasi untuk penerima disposisi
        DB::table('notifikasi')->insert([
            'user_id' => $request->kepada_user_id,
            'judul' => 'Disposisi Baru',
            'pesan' => 'Anda mendapat disposisi surat ' . $suratMasuk->nomor_agenda . ' dari ' . Auth::user()->name,
            'tipe' => 'info',
            'url' => route('disposisi.show', $disposisi->id),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Buat Disposisi',
            'modul' => 'Disposisi',
            'keterangan' => 'Mendisposisi surat ' . $suratMasuk->nomor_agenda . ' kepada ' . User::find($request->kepada_user_id)->name,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('disposisi.index')
            ->with('success', 'Disposisi berhasil dibuat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Disposisi $disposisi)
    {
        $disposisi->load(['suratMasuk', 'dariUser', 'kepadaUser']);

        // Update status menjadi "dibaca" jika penerima yang membuka
        if ($disposisi->kepada_user_id == Auth::id() && $disposisi->status == 'pending') {
            $disposisi->update(['status' => 'dibaca']);
        }

        return view('disposisi.show', compact('disposisi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Disposisi $disposisi)
    {
        // Hanya pembuat disposisi yang bisa edit
        if ($disposisi->dari_user_id != Auth::id() && Auth::user()->role != 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk mengedit disposisi ini.');
        }

        $users = User::where('id', '!=', Auth::id())
            ->where('status', 'aktif')
            ->orderBy('name')
            ->get();

        return view('disposisi.edit', compact('disposisi', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Disposisi $disposisi)
    {
        $request->validate([
            'instruksi' => 'required',
            'tanggal_disposisi' => 'required|date',
            'batas_waktu' => 'nullable|date|after_or_equal:tanggal_disposisi',
            'file_lampiran' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ]);

        $data = $request->only(['instruksi', 'tanggal_disposisi', 'batas_waktu', 'catatan']);

        // Upload file lampiran baru jika ada
        if ($request->hasFile('file_lampiran')) {
            try {
                // Hapus file lama jika ada
            if ($disposisi->file_lampiran && file_exists(public_path('uploads/disposisi/' . $disposisi->file_lampiran))) {
                unlink(public_path('uploads/disposisi/' . $disposisi->file_lampiran));
            }

            $file = $request->file('file_lampiran');
                
                // Sanitize filename
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $cleanName = preg_replace('/[^A-Za-z0-9\-_\.]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
                $filename = time() . '_' . $cleanName . '.' . $extension;
                
            $file->move(public_path('uploads/disposisi'), $filename);
            $data['file_lampiran'] = $filename;
                
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return redirect()->back()
                    ->with('error', 'Gagal upload file. Silakan coba lagi.')
                    ->withInput();
            }
        }

        $disposisi->update($data);

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Disposisi',
            'modul' => 'Disposisi',
            'keterangan' => 'Mengupdate disposisi ID: ' . $disposisi->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('disposisi.show', $disposisi->id)
            ->with('success', 'Disposisi berhasil diupdate!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Disposisi $disposisi)
    {
        // Hanya admin atau pembuat disposisi yang bisa hapus
        if ($disposisi->dari_user_id != Auth::id() && Auth::user()->role != 'admin') {
            abort(403, 'Anda tidak memiliki akses untuk menghapus disposisi ini.');
        }

        // Hapus file lampiran jika ada
        if ($disposisi->file_lampiran && file_exists(public_path('uploads/disposisi/' . $disposisi->file_lampiran))) {
            unlink(public_path('uploads/disposisi/' . $disposisi->file_lampiran));
        }

        $disposisi->delete();

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Hapus Disposisi',
            'modul' => 'Disposisi',
            'keterangan' => 'Menghapus disposisi ID: ' . $disposisi->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->route('disposisi.index')
            ->with('success', 'Disposisi berhasil dihapus!');
    }

    /**
     * Update status disposisi
     */
    public function updateStatus(Request $request, Disposisi $disposisi)
    {
        // Hanya penerima disposisi yang bisa update status
        if ($disposisi->kepada_user_id != Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah status disposisi ini.');
        }

        $request->validate([
            'status' => 'required|in:dibaca,proses,selesai',
            'catatan' => 'nullable|string',
        ]);

        $disposisi->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        // Jika status selesai, update status surat masuk
        if ($request->status == 'selesai') {
            $suratMasuk = $disposisi->suratMasuk;
            $suratMasuk->update(['status' => 'selesai']);
        }

        // Log aktivitas
        DB::table('log_aktivitas')->insert([
            'user_id' => Auth::id(),
            'aktivitas' => 'Update Status Disposisi',
            'modul' => 'Disposisi',
            'keterangan' => 'Mengubah status disposisi ID: ' . $disposisi->id . ' menjadi ' . $request->status,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Status disposisi berhasil diupdate!');
    }

    /**
     * Download file lampiran
     */
    public function download(Disposisi $disposisi)
    {
        if (!$disposisi->file_lampiran) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        $filepath = public_path('uploads/disposisi/' . $disposisi->file_lampiran);
        
        if (!file_exists($filepath)) {
            return redirect()->back()->with('error', 'File tidak ditemukan!');
        }

        return response()->download($filepath);
    }
}