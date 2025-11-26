@extends('layouts.app-custom')

@section('title', 'Dashboard Staff')
@section('page-title', 'Dashboard Staff')

@section('content')
<div class="container-fluid">
    <!-- Welcome Card -->
    <div class="card bg-gradient-info text-white mb-4">
        <div class="card-body">
            <h4 class="mb-2">
                <i class="bi bi-person-circle"></i> Selamat Datang, {{ auth()->user()->name }}
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
                            <h6 class="text-white-50 mb-2">Surat Masuk Saya</h6>
                            <h2 class="mb-0">{{ $my_surat_masuk }}</h2>
                        </div>
                        <i class="bi bi-envelope icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-file-earmark"></i> Yang saya input
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Surat Keluar Saya</h6>
                            <h2 class="mb-0">{{ $my_surat_keluar }}</h2>
                        </div>
                        <i class="bi bi-send icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-file-earmark"></i> Yang saya buat
                    </small>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Disposisi Saya</h6>
                            <h2 class="mb-0">{{ $my_disposisi }}</h2>
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
                            <h6 class="text-white-50 mb-2">Tugas Hari Ini</h6>
                            <h2 class="mb-0">{{ $disposisi_pending }}</h2>
                        </div>
                        <i class="bi bi-list-check icon"></i>
                    </div>
                    <small class="mt-2 d-block">
                        <i class="bi bi-hourglass-split"></i> Perlu dikerjakan
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
                        <i class="bi bi-diagram-3"></i> Disposisi Terbaru
                    </h5>
                    <a href="{{ route('disposisi.index') }}" class="btn btn-sm btn-warning">Lihat Semua</a>
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

        <!-- Surat Masuk yang Saya Input -->
        <div class="col-lg-6">
            <div class="card stat-card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i> Surat Masuk Saya
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
                            <a href="{{ route('surat-masuk.create') }}" class="btn btn-outline-primary w-100 py-3">
                                <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                                Input Surat Masuk
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('surat-keluar.create') }}" class="btn btn-outline-success w-100 py-3">
                                <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                                Buat Surat Keluar
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('disposisi.index') }}" class="btn btn-outline-warning w-100 py-3">
                                <i class="bi bi-diagram-3 fs-4 d-block mb-2"></i>
                                Lihat Disposisi
                            </a>
                        </div>
                        <div class="col-md-3">
                            <a href="{{ route('arsip.index') }}" class="btn btn-outline-info w-100 py-3">
                                <i class="bi bi-folder fs-4 d-block mb-2"></i>
                                Arsip Dokumen
                            </a>
                        </div>
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

@push('styles')
<style>
.bg-gradient-info {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endpush