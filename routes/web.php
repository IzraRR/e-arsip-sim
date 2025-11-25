<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PimpinanController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\SuratKeluarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\ArsipController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\PengaturanController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    
    // ============================================
    // PROFILE ROUTES
    // ============================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // ============================================
    // DEFAULT DASHBOARD (Auto redirect based on role)
    // ============================================
    Route::get('/dashboard', function () {
        $role = auth()->user()->role;
        switch ($role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pimpinan':
                return redirect()->route('pimpinan.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            default:
                abort(403, 'Unauthorized access');
        }
    })->name('dashboard');

    // ============================================
    // SURAT MASUK ROUTES
    // ============================================
    Route::resource('surat-masuk', SuratMasukController::class);
    Route::get('surat-masuk/{suratMasuk}/download', [SuratMasukController::class, 'download'])
        ->name('surat-masuk.download');

    // ============================================
    // SURAT KELUAR ROUTES
    // ============================================
    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::get('surat-keluar/{suratKeluar}/download', [SuratKeluarController::class, 'download'])
        ->name('surat-keluar.download');
    Route::post('surat-keluar/{suratKeluar}/approve', [SuratKeluarController::class, 'approve'])
        ->name('surat-keluar.approve');
    Route::post('surat-keluar/{suratKeluar}/sent', [SuratKeluarController::class, 'sent'])
        ->name('surat-keluar.sent');

    // ============================================
    // NOTIFIKASI ROUTES
    // ============================================
    // Routes Notifikasi
    Route::get('/notifikasi', [NotifikasiController::class, 'index'])
        ->name('notifikasi.index');
    Route::post('/notifikasi/{id}/read', [NotifikasiController::class, 'markAsRead'])
        ->name('notifikasi.read');
    Route::post('/notifikasi/read-all', [NotifikasiController::class, 'markAllAsRead'])
        ->name('notifikasi.read-all');
    Route::get('/notifikasi/unread-count', [NotifikasiController::class, 'getUnreadCount'])
        ->name('notifikasi.unread-count');
    Route::delete('/notifikasi/{id}', [NotifikasiController::class, 'destroy'])
        ->name('notifikasi.destroy');
    Route::get('/notifikasi/latest', [NotifikasiController::class, 'getLatest'])
        ->name('notifikasi.latest');

    // Routes Disposisi (untuk semua role yang login)
    Route::resource('disposisi', DisposisiController::class);
    Route::post('disposisi/{disposisi}/update-status', [DisposisiController::class, 'updateStatus'])
        ->name('disposisi.update-status');
    Route::get('disposisi/{disposisi}/download', [DisposisiController::class, 'download'])
        ->name('disposisi.download');

    // Routes Arsip (untuk semua role yang login)
    Route::resource('arsip', ArsipController::class);
    Route::get('arsip/{arsip}/download', [ArsipController::class, 'download'])
        ->name('arsip.download');

    // ============================================
    // LAPORAN ROUTES (untuk semua role yang login)
    // ============================================
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [LaporanController::class, 'index'])->name('index');
        Route::get('/statistik', [LaporanController::class, 'statistik'])->name('statistik');
        
        // Export PDF
        Route::get('/export-surat-masuk-pdf', [LaporanController::class, 'exportSuratMasukPDF'])->name('export-surat-masuk-pdf');
        Route::get('/export-surat-keluar-pdf', [LaporanController::class, 'exportSuratKeluarPDF'])->name('export-surat-keluar-pdf');
        Route::get('/export-disposisi-pdf', [LaporanController::class, 'exportDisposisiPDF'])->name('export-disposisi-pdf');
        Route::get('/export-arsip-pdf', [LaporanController::class, 'exportArsipPDF'])->name('export-arsip-pdf');
        Route::get('/export-lengkap-pdf', [LaporanController::class, 'exportLaporanLengkapPDF'])->name('export-lengkap-pdf');
        
        // Export Excel
        Route::get('/export-surat-masuk-excel', [LaporanController::class, 'exportSuratMasukExcel'])->name('export-surat-masuk-excel');
        Route::get('/export-surat-keluar-excel', [LaporanController::class, 'exportSuratKeluarExcel'])->name('export-surat-keluar-excel');
        Route::get('/export-disposisi-excel', [LaporanController::class, 'exportDisposisiExcel'])->name('export-disposisi-excel');
        Route::get('/export-arsip-excel', [LaporanController::class, 'exportArsipExcel'])->name('export-arsip-excel');
    });

    // ============================================
    // ADMIN ROUTES (Only accessible by admin role)
    // ============================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::resource('kategori', KategoriController::class);
        Route::resource('users', UserController::class);
        Route::resource('log-aktivitas', LogAktivitasController::class)->only(['index', 'show', 'destroy']);
        Route::post('log-aktivitas/clear', [LogAktivitasController::class, 'clearOld'])->name('log-aktivitas.clear');
        Route::get('pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::put('pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');
    });

    // ============================================
    // PIMPINAN ROUTES (Only accessible by pimpinan role)
    // ============================================
    Route::middleware('role:pimpinan')->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('/dashboard', [PimpinanController::class, 'dashboard'])->name('dashboard');
    });

    // ============================================
    // STAFF ROUTES (Only accessible by staff role)
    // ============================================
    Route::middleware('role:staff')->prefix('staff')->name('staff.')->group(function () {
        Route::get('/dashboard', [StaffController::class, 'dashboard'])->name('dashboard');
    });

});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Login, Register, etc)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';
