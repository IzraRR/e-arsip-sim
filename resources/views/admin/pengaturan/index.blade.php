@extends('layouts.app-custom')

@section('title', 'Pengaturan')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-gear"></i> Pengaturan Sistem
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pengaturan.update') }}" method="POST">
                        @csrf

                        <!-- Informasi Aplikasi -->
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-info-circle"></i> Informasi Aplikasi
                        </h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Aplikasi</label>
                                <input type="text" name="settings[nama_aplikasi]" class="form-control" 
                                       value="{{ $pengaturan['nama_aplikasi']->key_value ?? 'E-Arsip' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nama Instansi</label>
                                <input type="text" name="settings[nama_instansi]" class="form-control" 
                                       value="{{ $pengaturan['nama_instansi']->key_value ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Alamat Instansi</label>
                                <textarea name="settings[alamat_instansi]" class="form-control" rows="3">{{ $pengaturan['alamat_instansi']->key_value ?? '' }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="settings[telepon]" class="form-control" 
                                       value="{{ $pengaturan['telepon']->key_value ?? '' }}">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" name="settings[email]" class="form-control" 
                                       value="{{ $pengaturan['email']->key_value ?? '' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="url" name="settings[website]" class="form-control" 
                                       value="{{ $pengaturan['website']->key_value ?? '' }}">
                            </div>
                        </div>

                        <!-- Pengaturan Umum -->
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-sliders"></i> Pengaturan Umum
                        </h6>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Maksimal Ukuran File Upload (MB)</label>
                                <input type="number" name="settings[max_upload_size]" class="form-control" 
                                       value="{{ $pengaturan['max_upload_size']->key_value ?? '10' }}" min="1" max="100">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Format File yang Diizinkan</label>
                                <input type="text" name="settings[allowed_file_types]" class="form-control" 
                                       value="{{ $pengaturan['allowed_file_types']->key_value ?? 'pdf,doc,docx,jpg,jpeg,png' }}" 
                                       placeholder="pdf,doc,docx,jpg,jpeg,png">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Auto Backup (Hari)</label>
                                <input type="number" name="settings[auto_backup_days]" class="form-control" 
                                       value="{{ $pengaturan['auto_backup_days']->key_value ?? '7' }}" min="1" max="30">
                                <small class="text-muted">Interval hari untuk backup otomatis</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Retensi Log (Hari)</label>
                                <input type="number" name="settings[log_retention_days]" class="form-control" 
                                       value="{{ $pengaturan['log_retention_days']->key_value ?? '90' }}" min="1" max="365">
                                <small class="text-muted">Lama penyimpanan log aktivitas</small>
                            </div>
                        </div>

                        <!-- Notifikasi -->
                        <h6 class="border-bottom pb-2 mb-3">
                            <i class="bi bi-bell"></i> Pengaturan Notifikasi
                        </h6>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="settings[notifikasi_email]" 
                                           value="1" {{ ($pengaturan['notifikasi_email']->key_value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label">Aktifkan Notifikasi Email</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="settings[notifikasi_sistem]" 
                                           value="1" {{ ($pengaturan['notifikasi_sistem']->key_value ?? '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label">Aktifkan Notifikasi Sistem</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

