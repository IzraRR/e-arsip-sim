@extends('layouts.app-custom')

@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard Admin')

@section('content')
<div class="container-fluid">
    <!-- Statistik Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Surat Masuk</h6>
                            <h2 class="mb-0">{{ $total_surat_masuk }}</h2>
                        </div>
                        <i class="bi bi-envelope icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-clock"></i> Pending: {{ $surat_masuk_pending }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Surat Keluar</h6>
                            <h2 class="mb-0">{{ $total_surat_keluar }}</h2>
                        </div>
                        <i class="bi bi-send icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-file-text"></i> Draft: {{ $surat_keluar_draft }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Arsip</h6>
                            <h2 class="mb-0">{{ $total_arsip }}</h2>
                        </div>
                        <i class="bi bi-folder icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-files"></i> Dokumen tersimpan
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total User Aktif</h6>
                            <h2 class="mb-0">{{ $total_users }}</h2>
                        </div>
                        <i class="bi bi-people icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-person-check"></i> User terdaftar
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Surat Masuk</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSuratMasuk" height="200"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Status Surat Keluar</h5>
                </div>
                <div class="card-body">
                    <canvas id="chartSuratKeluar" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Surat Masuk Terbaru</h5>
                    <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Agenda</th>
                                    <th>Pengirim</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_surat_masuk as $surat)
                                <tr>
                                    <td>{{ $surat->nomor_agenda }}</td>
                                    <td>{{ $surat->pengirim }}</td>
                                    <td>{{ Str::limit($surat->perihal, 40) }}</td>
                                    <td>
                                        @if($surat->status == 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($surat->status == 'disposisi')
                                            <span class="badge bg-info">Disposisi</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Belum ada surat masuk</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Surat Keluar Terbaru</h5>
                    <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-success">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>No. Surat</th>
                                    <th>Tujuan</th>
                                    <th>Perihal</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_surat_keluar as $surat)
                                <tr>
                                    <td>{{ $surat->nomor_surat }}</td>
                                    <td>{{ $surat->tujuan }}</td>
                                    <td>{{ Str::limit($surat->perihal, 40) }}</td>
                                    <td>
                                        @if($surat->status == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($surat->status == 'approved')
                                            <span class="badge bg-primary">Approved</span>
                                        @else
                                            <span class="badge bg-success">Terkirim</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Belum ada surat keluar</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Developer Credit -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 bg-light">
                <div class="card-body text-center py-3">
                    <small class="text-muted">
                        <i class="bi bi-code-slash"></i> 
                        <strong>Developed by:</strong> 
                        Izra Rafif Rabbani | Adniel Rama Ezaputra | Muhammad Rizky
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Surat Masuk
    const ctxMasuk = document.getElementById('chartSuratMasuk');
    new Chart(ctxMasuk, {
        type: 'doughnut',
        data: {
            labels: ['Pending', 'Disposisi', 'Selesai'],
            datasets: [{
                data: [{{ $surat_masuk_pending }}, {{ $surat_masuk_disposisi }}, {{ $surat_masuk_selesai }}],
                backgroundColor: ['#ffc107', '#17a2b8', '#28a745']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Chart Surat Keluar
    const ctxKeluar = document.getElementById('chartSuratKeluar');
    new Chart(ctxKeluar, {
        type: 'doughnut',
        data: {
            labels: ['Draft', 'Approved', 'Terkirim'],
            datasets: [{
                data: [{{ $surat_keluar_draft }}, {{ $surat_keluar_approved }}, {{ $surat_keluar_sent }}],
                backgroundColor: ['#6c757d', '#007bff', '#28a745']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endpush