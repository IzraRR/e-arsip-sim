@extends('layouts.app-custom')

@section('title', 'Dashboard Pimpinan')
@section('page-title', 'Dashboard Pimpinan')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card bg-gradient-primary text-white mb-4">
        <div class="card-body">
            <h4 class="mb-2">
                <i class="bi bi-person-badge"></i> Selamat Datang, {{ auth()->user()->name }}
            </h4>
            <p class="mb-0">{{ auth()->user()->unit_kerja }} - {{ auth()->user()->nip }}</p>
        </div>
    </div>

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
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Total Disposisi</h6>
                            <h2 class="mb-0">{{ $total_disposisi }}</h2>
                        </div>
                        <i class="bi bi-diagram-3 icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-clock"></i> Pending: {{ $disposisi_pending }}
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Disposisi Proses</h6>
                            <h2 class="mb-0">{{ $disposisi_proses }}</h2>
                        </div>
                        <i class="bi bi-arrow-repeat icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-hourglass-split"></i> Dalam proses
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row g-4">
        <!-- Disposisi Saya -->
        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3"></i> Disposisi Saya
                    </h5>
                    <a href="{{ route('disposisi.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Dari</th>
                                    <th>Surat</th>
                                    <th>Instruksi</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recent_disposisi as $disposisi)
                                <tr>
                                    <td>
                                        <strong>{{ $disposisi->dariUser->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $disposisi->tanggal_disposisi->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        {{ $disposisi->suratMasuk->nomor_agenda }}
                                        <br>
                                        <small class="text-muted">{{ Str::limit($disposisi->suratMasuk->perihal, 30) }}</small>
                                    </td>
                                    <td>{{ Str::limit($disposisi->instruksi, 40) }}</td>
                                    <td>
                                        @if($disposisi->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        @elseif($disposisi->status == 'proses')
                                            <span class="badge bg-info">Proses</span>
                                        @elseif($disposisi->status == 'dibaca')
                                            <span class="badge bg-primary">Dibaca</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">
                                        <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">Belum ada disposisi</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surat Masuk Terbaru -->
        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i> Surat Masuk Terbaru
                    </h5>
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
                                    <td>
                                        <strong>{{ $surat->nomor_agenda }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $surat->tanggal_terima->format('d/m/Y') }}</small>
                                    </td>
                                    <td>{{ Str::limit($surat->pengirim, 25) }}</td>
                                    <td>{{ Str::limit($surat->perihal, 40) }}</td>
                                    <td>
                                        @if($surat->status == 'pending')
                                            <span class="badge bg-warning text-dark">Pending</span>
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
    </div>

    <!-- Quick Actions -->
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card stat-card">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning"></i> Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-envelope fs-4 d-block mb-2"></i>
                                Lihat Surat Masuk
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-send fs-4 d-block mb-2"></i>
                                Lihat Surat Keluar
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('disposisi.index') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-diagram-3 fs-4 d-block mb-2"></i>
                                Disposisi Saya
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('laporan.index') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-file-earmark-text fs-4 d-block mb-2"></i>
                                Laporan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush