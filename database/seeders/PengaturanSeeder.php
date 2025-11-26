<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pengaturan = [
            [
                'key_name' => 'nama_aplikasi',
                'key_value' => 'E-Arsip',
                'deskripsi' => 'Nama aplikasi sistem arsip elektronik',
            ],
            [
                'key_name' => 'nama_instansi',
                'key_value' => '',
                'deskripsi' => 'Nama instansi atau organisasi',
            ],
            [
                'key_name' => 'alamat_instansi',
                'key_value' => '',
                'deskripsi' => 'Alamat lengkap instansi',
            ],
            [
                'key_name' => 'telepon',
                'key_value' => '',
                'deskripsi' => 'Nomor telepon instansi',
            ],
            [
                'key_name' => 'email',
                'key_value' => '',
                'deskripsi' => 'Email kontak instansi',
            ],
            [
                'key_name' => 'website',
                'key_value' => '',
                'deskripsi' => 'Website instansi',
            ],
            [
                'key_name' => 'max_upload_size',
                'key_value' => '10',
                'deskripsi' => 'Maksimal ukuran file upload dalam MB',
            ],
            [
                'key_name' => 'allowed_file_types',
                'key_value' => 'pdf,doc,docx,jpg,jpeg,png',
                'deskripsi' => 'Format file yang diizinkan untuk upload',
            ],
            [
                'key_name' => 'auto_backup_days',
                'key_value' => '7',
                'deskripsi' => 'Interval hari untuk backup otomatis',
            ],
            [
                'key_name' => 'log_retention_days',
                'key_value' => '90',
                'deskripsi' => 'Lama penyimpanan log aktivitas dalam hari',
            ],
            [
                'key_name' => 'notifikasi_email',
                'key_value' => '1',
                'deskripsi' => 'Aktifkan notifikasi email (1=aktif, 0=nonaktif)',
            ],
            [
                'key_name' => 'notifikasi_sistem',
                'key_value' => '1',
                'deskripsi' => 'Aktifkan notifikasi sistem (1=aktif, 0=nonaktif)',
            ],
        ];

        foreach ($pengaturan as $setting) {
            DB::table('pengaturan')->updateOrInsert(
                ['key_name' => $setting['key_name']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}


