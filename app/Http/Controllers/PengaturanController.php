<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengaturanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaturan = DB::table('pengaturan')
            ->orderBy('key_name')
            ->get()
            ->keyBy('key_name');

        return view('admin.pengaturan.index', compact('pengaturan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->settings as $key => $value) {
                DB::table('pengaturan')
                    ->updateOrInsert(
                        ['key_name' => $key],
                        [
                            'key_value' => $value,
                            'updated_at' => now(),
                        ]
                    );
            }

            // Log aktivitas
            DB::table('log_aktivitas')->insert([
                'user_id' => auth()->id(),
                'aktivitas' => 'Update Pengaturan',
                'modul' => 'Pengaturan',
                'keterangan' => 'Mengupdate pengaturan sistem',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('admin.pengaturan.index')
                ->with('success', 'Pengaturan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating settings: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menyimpan pengaturan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Get setting value
     */
    public static function get($key, $default = null)
    {
        $setting = DB::table('pengaturan')
            ->where('key_name', $key)
            ->first();

        return $setting ? $setting->key_value : $default;
    }
}

