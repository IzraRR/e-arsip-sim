<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ===================================
        // TABEL 1: KATEGORI (dibuat pertama karena ada foreign key)
        // ===================================
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama_kategori', 100);
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index('kode');
        });

        // ===================================
        // TABEL 2: SURAT MASUK
        // ===================================
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_agenda', 50)->unique();
            $table->string('nomor_surat', 100);
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('pengirim', 200);
            $table->text('perihal');
            $table->string('file_surat')->nullable();
            $table->enum('status', ['pending', 'disposisi', 'selesai'])->default('pending');
            $table->enum('prioritas', ['biasa', 'penting', 'segera'])->default('biasa');
            $table->enum('sifat', ['biasa', 'rahasia', 'sangat_rahasia'])->default('biasa');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('nomor_agenda');
            $table->index('tanggal_terima');
            $table->index('status');
            $table->index('user_id');
        });

        // ===================================
        // TABEL 3: SURAT KELUAR
        // ===================================
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100)->unique();
            $table->date('tanggal_surat');
            $table->string('tujuan', 200);
            $table->text('perihal');
            $table->string('file_surat')->nullable();
            $table->string('penandatangan', 100);
            $table->enum('status', ['draft', 'approved', 'sent'])->default('draft');
            $table->enum('prioritas', ['biasa', 'penting', 'segera'])->default('biasa');
            $table->enum('sifat', ['biasa', 'rahasia', 'sangat_rahasia'])->default('biasa');
            $table->text('keterangan')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('nomor_surat');
            $table->index('tanggal_surat');
            $table->index('status');
            $table->index('user_id');
        });

        // ===================================
        // TABEL 4: DISPOSISI
        // ===================================
        Schema::create('disposisi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_masuk_id')->constrained('surat_masuk')->onDelete('cascade');
            $table->foreignId('dari_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kepada_user_id')->constrained('users')->onDelete('cascade');
            $table->text('instruksi');
            $table->date('tanggal_disposisi');
            $table->date('batas_waktu')->nullable();
            $table->enum('status', ['pending', 'dibaca', 'proses', 'selesai'])->default('pending');
            $table->text('catatan')->nullable();
            $table->string('file_lampiran')->nullable();
            $table->timestamps();
            
            $table->index('surat_masuk_id');
            $table->index('dari_user_id');
            $table->index('kepada_user_id');
            $table->index('status');
            $table->index('tanggal_disposisi');
        });

        // ===================================
        // TABEL 5: ARSIP (dibuat setelah kategori)
        // ===================================
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_dokumen', 100);
            $table->string('judul', 200);
            $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
            $table->date('tanggal_dokumen');
            $table->string('file_dokumen');
            $table->text('keterangan')->nullable();
            $table->string('tags')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index('nomor_dokumen');
            $table->index('kategori_id');
            $table->index('tanggal_dokumen');
            $table->index('user_id');
        });

        // ===================================
        // TABEL 6: LOG AKTIVITAS
        // ===================================
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aktivitas', 255);
            $table->string('modul', 50);
            $table->text('keterangan')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index('user_id');
            $table->index('modul');
            $table->index('created_at');
        });

        // ===================================
        // TABEL 7: PENGATURAN
        // ===================================
        Schema::create('pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('key_name', 100)->unique();
            $table->text('key_value')->nullable();
            $table->string('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index('key_name');
        });

        // ===================================
        // TABEL 8: NOTIFIKASI
        // ===================================
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->enum('tipe', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->boolean('is_read')->default(false);
            $table->string('url')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('is_read');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
        Schema::dropIfExists('pengaturan');
        Schema::dropIfExists('log_aktivitas');
        Schema::dropIfExists('arsip');
        Schema::dropIfExists('disposisi');
        Schema::dropIfExists('surat_keluar');
        Schema::dropIfExists('surat_masuk');
        Schema::dropIfExists('kategori');
    }
};