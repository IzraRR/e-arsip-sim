<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;
use Carbon\Carbon;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        /** USERS **/
        $users = [];
        $roles = ['admin', 'pimpinan', 'staff', 'staff', 'staff'];
        foreach ($roles as $index => $role) {
            $users[] = [
                'name' => $role === 'admin' ? 'Administrator' : $faker->name(),
                'email' => $role === 'admin'
                    ? 'admin@example.com'
                    : 'user' . ($index + 1) . '@example.com',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'role' => $role,
                'unit_kerja' => $faker->company(),
                'nip' => $role === 'admin' ? '198001011990011001' : $faker->numerify('1978##########'),
                'telepon' => $faker->phoneNumber(),
                'status' => 'aktif',
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('users')->insert($users);

        /** KATEGORI **/
        $kategoriList = [
            ['kode' => 'ADM', 'nama_kategori' => 'Administrasi', 'deskripsi' => 'Dokumen administrasi umum'],
            ['kode' => 'HKM', 'nama_kategori' => 'Hukum', 'deskripsi' => 'Surat keputusan dan regulasi'],
            ['kode' => 'KGM', 'nama_kategori' => 'Kepegawaian', 'deskripsi' => 'Surat kepegawaian'],
            ['kode' => 'KPN', 'nama_kategori' => 'Keuangan', 'deskripsi' => 'Dokumen keuangan'],
            ['kode' => 'LAP', 'nama_kategori' => 'Laporan', 'deskripsi' => 'Laporan kegiatan'],
        ];

        foreach ($kategoriList as $kategori) {
            DB::table('kategori')->insert([
                'kode' => $kategori['kode'],
                'nama_kategori' => $kategori['nama_kategori'],
                'deskripsi' => $kategori['deskripsi'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $kategoriIds = DB::table('kategori')->pluck('id')->toArray();
        $userIds = DB::table('users')->pluck('id')->toArray();

        /** SURAT MASUK **/
        $suratMasukData = [];
        for ($i = 1; $i <= 12; $i++) {
            $tanggalSurat = Carbon::now()->subDays(rand(10, 90));
            $suratMasukData[] = [
                'nomor_agenda' => 'AG-' . date('Ym') . str_pad($i, 4, '0', STR_PAD_LEFT),
                'nomor_surat' => 'SM-' . $faker->numerify('####/A/' . date('Y')),
                'tanggal_surat' => $tanggalSurat,
                'tanggal_terima' => $tanggalSurat->copy()->addDays(rand(0, 5)),
                'pengirim' => $faker->company(),
                'perihal' => $faker->sentence(6),
                'file_surat' => null,
                'status' => $faker->randomElement(['pending', 'disposisi', 'selesai']),
                'prioritas' => $faker->randomElement(['biasa', 'penting', 'segera']),
                'sifat' => $faker->randomElement(['biasa', 'rahasia']),
                'keterangan' => $faker->sentence(10),
                'user_id' => $faker->randomElement($userIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('surat_masuk')->insert($suratMasukData);
        $suratMasukIds = DB::table('surat_masuk')->pluck('id')->toArray();

        /** SURAT KELUAR **/
        $suratKeluarData = [];
        for ($i = 1; $i <= 10; $i++) {
            $tanggalSurat = Carbon::now()->subDays(rand(5, 60));
            $suratKeluarData[] = [
                'nomor_surat' => 'SK-' . $faker->numerify('####/B/' . date('Y')),
                'tanggal_surat' => $tanggalSurat,
                'tujuan' => $faker->company(),
                'perihal' => $faker->sentence(6),
                'file_surat' => null,
                'penandatangan' => $faker->name(),
                'status' => $faker->randomElement(['draft', 'approved', 'sent']),
                'prioritas' => $faker->randomElement(['biasa', 'penting', 'segera']),
                'sifat' => $faker->randomElement(['biasa', 'rahasia']),
                'keterangan' => $faker->sentence(),
                'user_id' => $faker->randomElement($userIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('surat_keluar')->insert($suratKeluarData);

        /** ARSIP **/
        $arsipData = [];
        for ($i = 1; $i <= 8; $i++) {
            $tanggalDokumen = Carbon::now()->subDays(rand(20, 180));
            $arsipData[] = [
                'nomor_dokumen' => 'DOC-' . $faker->numerify('####/ARS/' . date('Y')),
                'judul' => $faker->sentence(5),
                'kategori_id' => $faker->randomElement($kategoriIds),
                'tanggal_dokumen' => $tanggalDokumen,
                'file_dokumen' => null,
                'keterangan' => $faker->sentence(10),
                'tags' => implode(',', $faker->words(3)),
                'user_id' => $faker->randomElement($userIds),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('arsip')->insert($arsipData);

        /** DISPOSISI **/
        $disposisiData = [];
        for ($i = 1; $i <= 6; $i++) {
            $suratMasukId = $faker->randomElement($suratMasukIds);
            $tanggalDisposisi = Carbon::now()->subDays(rand(1, 30));
            $disposisiData[] = [
                'surat_masuk_id' => $suratMasukId,
                'dari_user_id' => $faker->randomElement($userIds),
                'kepada_user_id' => $faker->randomElement($userIds),
                'instruksi' => $faker->sentence(8),
                'tanggal_disposisi' => $tanggalDisposisi,
                'batas_waktu' => $tanggalDisposisi->copy()->addDays(rand(2, 7)),
                'status' => $faker->randomElement(['pending', 'dibaca', 'proses', 'selesai']),
                'catatan' => $faker->sentence(),
                'file_lampiran' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        DB::table('disposisi')->insert($disposisiData);
    }
}

