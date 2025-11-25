@extends('layouts.app-custom')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person"></i> Informasi User</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td><strong>Nama</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>NIP</strong></td>
                            <td>{{ $user->nip ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'pimpinan' ? 'warning' : 'info') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                <span class="badge bg-{{ $user->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ strtoupper($user->status) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Unit Kerja</strong></td>
                            <td>{{ $user->unit_kerja ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Telepon</strong></td>
                            <td>{{ $user->telepon ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Terdaftar</strong></td>
                            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit User
                        </a>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Statistik -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h3 class="text-primary">{{ $user->suratMasuk->count() }}</h3>
                            <p class="text-muted mb-0">Surat Masuk</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <h3 class="text-success">{{ $user->suratKeluar->count() }}</h3>
                            <p class="text-muted mb-0">Surat Keluar</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-info">
                        <div class="card-body text-center">
                            <h3 class="text-info">{{ $user->arsip->count() }}</h3>
                            <p class="text-muted mb-0">Arsip</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Aktivitas Terakhir</h5>
                </div>
                <div class="card-body">
                    @if($recentActivities->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Aktivitas</th>
                                    <th>Modul</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentActivities as $activity)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($activity->created_at)->format('d/m/Y H:i') }}</td>
                                    <td>{{ $activity->aktivitas }}</td>
                                    <td><span class="badge bg-secondary">{{ $activity->modul }}</span></td>
                                    <td>{{ Str::limit($activity->keterangan, 50) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <p class="text-muted text-center py-3">Belum ada aktivitas</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

