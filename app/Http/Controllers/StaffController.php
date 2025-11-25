<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;

class StaffController extends Controller
{
    public function dashboard()
    {
        $userId = auth()->id();
        
        $data = [
            'my_surat_masuk' => SuratMasuk::where('user_id', $userId)->count(),
            'my_surat_keluar' => SuratKeluar::where('user_id', $userId)->count(),
            
            'my_disposisi' => Disposisi::where('kepada_user_id', $userId)->count(),
            'disposisi_pending' => Disposisi::where('kepada_user_id', $userId)
                ->where('status', 'pending')->count(),
                
            'recent_disposisi' => Disposisi::with(['suratMasuk', 'dariUser'])
                ->where('kepada_user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
                
            'recent_surat_masuk' => SuratMasuk::where('user_id', $userId)
                ->latest()
                ->take(5)
                ->get(),
        ];

        return view('staff.dashboard', $data);
    }
}