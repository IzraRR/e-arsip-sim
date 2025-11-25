<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Arsip;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_surat_masuk' => SuratMasuk::count(),
            'total_surat_keluar' => SuratKeluar::count(),
            'total_arsip' => Arsip::count(),
            'total_users' => User::where('status', 'aktif')->count(),
            
            'surat_masuk_pending' => SuratMasuk::where('status', 'pending')->count(),
            'surat_masuk_disposisi' => SuratMasuk::where('status', 'disposisi')->count(),
            'surat_masuk_selesai' => SuratMasuk::where('status', 'selesai')->count(),
            
            'surat_keluar_draft' => SuratKeluar::where('status', 'draft')->count(),
            'surat_keluar_approved' => SuratKeluar::where('status', 'approved')->count(),
            'surat_keluar_sent' => SuratKeluar::where('status', 'sent')->count(),
            
            'recent_surat_masuk' => SuratMasuk::with('user')->latest()->take(5)->get(),
            'recent_surat_keluar' => SuratKeluar::with('user')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $data);
    }
}