@extends('layouts.app-custom')

@section('title', 'Arsip Dokumen')
@section('page-title', 'Arsip Dokumen')

@section('content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-folder"></i> Arsip Dokumen Digital
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('arsip.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Upload Dokumen
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('arsip.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-select">
                        <option value="">Semua Kategori</option>
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                {{ $kat->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Dari</label>
                    <input type="date" name="tanggal_dari" class="form-control" value="{{ request('tanggal_dari') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Tanggal Sampai</label>
                    <input type="date" name="tanggal_sampai" class="form-control" value="{{ request('tanggal_sampai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Pencarian</label>
                    <input type="text" name="search" class="form-control" 
                           placeholder="Cari judul, nomor, tags..." 
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('arsip.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Grid Arsip -->
    <div class="row g-4">
        @forelse($arsip as $item)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card h-100 shadow-sm hover-card">
                <div class="card-body">
                    <!-- Icon File Berdasarkan Ekstensi -->
                    @php
                        $extension = pathinfo($item->file_dokumen, PATHINFO_EXTENSION);
                        $iconColor = match(strtolower($extension)) {
                            'pdf' => 'text-danger',
                            'doc', 'docx' => 'text-primary',
                            'xls', 'xlsx' => 'text-success',
                            'jpg', 'jpeg', 'png' => 'text-warning',
                            default => 'text-secondary'
                        };
                        $iconClass = match(strtolower($extension)) {
                            'pdf' => 'bi-file-earmark-pdf',
                            'doc', 'docx' => 'bi-file-earmark-word',
                            'xls', 'xlsx' => 'bi-file-earmark-excel',
                            'jpg', 'jpeg', 'png' => 'bi-file-earmark-image',
                            default => 'bi-file-earmark'
                        };
                    @endphp
                    
                    <div class="text-center mb-3">
                        <i class="bi {{ $iconClass }} {{ $iconColor }}" style="font-size: 4rem;"></i>
                    </div>

                    <!-- Kategori Badge -->
                    <span class="badge bg-primary mb-2">{{ $item->kategori->nama_kategori }}</span>

                    <!-- Judul -->
                    <h6 class="card-title mb-2">
                        <a href="{{ route('arsip.show', $item->id) }}" class="text-decoration-none text-dark">
                            {{ Str::limit($item->judul, 50) }}
                        </a>
                    </h6>

                    <!-- Nomor Dokumen -->
                    <p class="text-muted small mb-2">
                        <i class="bi bi-hash"></i> {{ $item->nomor_dokumen }}
                    </p>

                    <!-- Tanggal -->
                    <p class="text-muted small mb-2">
                        <i class="bi bi-calendar"></i> {{ $item->tanggal_dokumen->format('d M Y') }}
                    </p>

                    <!-- Tags -->
                    @if($item->tags)
                        <div class="mb-3">
                            @foreach(explode(',', $item->tags) as $tag)
                                <span class="badge bg-light text-dark">{{ trim($tag) }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Actions -->
                    <div class="d-flex gap-2 mt-3">
                        <a href="{{ route('arsip.show', $item->id) }}" 
                           class="btn btn-sm btn-info flex-fill" title="Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('arsip.download', $item->id) }}" 
                           class="btn btn-sm btn-success flex-fill" title="Download">
                            <i class="bi bi-download"></i>
                        </a>
                        <a href="{{ route('arsip.edit', $item->id) }}" 
                           class="btn btn-sm btn-warning flex-fill" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="bi bi-inbox" style="font-size: 5rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Belum ada arsip dokumen</h5>
                    <p class="text-muted">Mulai upload dokumen untuk menyimpan arsip digital</p>
                    <a href="{{ route('arsip.create') }}" class="btn btn-primary mt-2">
                        <i class="bi bi-plus-circle"></i> Upload Dokumen Pertama
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($arsip->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $arsip->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.hover-card {
    transition: transform 0.3s, box-shadow 0.3s;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 20px rgba(0,0,0,0.15) !important;
}
</style>
@endpush