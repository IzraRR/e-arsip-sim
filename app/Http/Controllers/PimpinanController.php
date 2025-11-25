<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;

class PimpinanController extends Controller
{
    public function dashboard()
    {
        $data = [
            'total_surat_masuk' => SuratMasuk::count(),
            'total_surat_keluar' => SuratKeluar::count(),
            'total_disposisi' => Disposisi::where('kepada_user_id', auth()->id())->count(),
            
            'disposisi_pending' => Disposisi::where('kepada_user_id', auth()->id())
                ->where('status', 'pending')->count(),
            'disposisi_proses' => Disposisi::where('kepada_user_id', auth()->id())
                ->where('status', 'proses')->count(),
            
            'recent_disposisi' => Disposisi::with(['suratMasuk', 'dariUser'])
                ->where('kepada_user_id', auth()->id())
                ->latest()
                ->take(5)
                ->get(),
                
            'recent_surat_masuk' => SuratMasuk::latest()->take(5)->get(),
        ];

        return view('pimpinan.dashboard', $data);
    }
}