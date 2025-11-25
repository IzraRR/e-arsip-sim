@extends('layouts.app-custom')

@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-bell"></i> Notifikasi Saya
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <form action="{{ route('notifikasi.read-all') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-all"></i> Tandai Semua Sudah Dibaca
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifikasi List -->
    <div class="card">
        <div class="card-body p-0">
            @forelse($notifikasi as $item)
                <div class="notif-item border-bottom {{ $item->is_read ? 'bg-white' : 'bg-light' }} p-3 hover-bg-light">
                    <div class="d-flex">
                        <!-- Icon -->
                        <div class="flex-shrink-0 me-3">
                            @if($item->tipe == 'info')
                                <div class="bg-info rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-info-circle text-white fs-4"></i>
                                </div>
                            @elseif($item->tipe == 'success')
                                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-check-circle text-white fs-4"></i>
                                </div>
                            @elseif($item->tipe == 'warning')
                                <div class="bg-warning rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-exclamation-triangle text-white fs-4"></i>
                                </div>
                            @else
                                <div class="bg-danger rounded-circle d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-exclamation-circle text-white fs-4"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1 {{ $item->is_read ? '' : 'fw-bold' }}">
                                        {{ $item->judul }}
                                        @if(!$item->is_read)
                                            <span class="badge bg-primary">Baru</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 text-muted">{{ $item->pesan }}</p>
                                    <small class="text-muted">
                                        <i class="bi bi-clock"></i> 
                                        {{ \Carbon\Carbon::parse($item->created_at)->diffForHumans() }}
                                    </small>
                                </div>
                                
                                <!-- Actions -->
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @if($item->url)
                                            <li>
                                                <form action="{{ route('notifikasi.read', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-box-arrow-up-right"></i> Buka
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        @if(!$item->is_read)
                                            <li>
                                                <form action="{{ route('notifikasi.read', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="bi bi-check"></i> Tandai Sudah Dibaca
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li>
                                            <form action="{{ route('notifikasi.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" 
                                                        onclick="return confirm('Hapus notifikasi ini?')">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="bi bi-bell-slash" style="font-size: 5rem; color: #ccc;"></i>
                    <h5 class="mt-3 text-muted">Tidak ada notifikasi</h5>
                    <p class="text-muted">Notifikasi akan muncul di sini ketika ada aktivitas baru</p>
                </div>
            @endforelse
        </div>

        @if($notifikasi->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $notifikasi->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
.notif-item {
    transition: all 0.3s;
}

.hover-bg-light:hover {
    background-color: #f8f9fa !important;
}
</style>
@endpush