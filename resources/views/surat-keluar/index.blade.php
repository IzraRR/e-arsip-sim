@extends('layouts.app-custom')

@section('title', 'Surat Keluar')
@section('page-title', 'Surat Keluar')

@section('content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-send"></i> Data Surat Keluar
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('surat-keluar.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Tambah Surat Keluar
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('surat-keluar.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Terkirim</option>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor, tujuan, perihal..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-clockwise"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="13%">No. Surat</th>
                            <th width="10%">Tgl. Surat</th>
                            <th width="15%">Tujuan</th>
                            <th>Perihal</th>
                            <th width="12%">Penandatangan</th>
                            <th width="8%">Status</th>
                            <th width="8%">Prioritas</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratKeluar as $index => $surat)
                        <tr>
                            <td>{{ $suratKeluar->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $surat->nomor_surat }}</strong>
                            </td>
                            <td>{{ $surat->tanggal_surat->format('d/m/Y') }}</td>
                            <td>{{ $surat->tujuan }}</td>
                            <td>{{ Str::limit($surat->perihal, 50) }}</td>
                            <td>{{ $surat->penandatangan }}</td>
                            <td>
                                @if($surat->status == 'draft')
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-file-text"></i> Draft
                                    </span>
                                @elseif($surat->status == 'approved')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-send-check"></i> Terkirim
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($surat->prioritas == 'segera')
                                    <span class="badge bg-danger">Segera</span>
                                @elseif($surat->prioritas == 'penting')
                                    <span class="badge bg-warning text-dark">Penting</span>
                                @else
                                    <span class="badge bg-secondary">Biasa</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('surat-keluar.show', $surat->id) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('surat-keluar.edit', $surat->id) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmDelete({{ $surat->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $surat->id }}" 
                                      action="{{ route('surat-keluar.destroy', $surat->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada data surat keluar</p>
                                <a href="{{ route('surat-keluar.create') }}" class="btn btn-sm btn-success mt-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Surat Keluar
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $suratKeluar->firstItem() ?? 0 }} - {{ $suratKeluar->lastItem() ?? 0 }} 
                    dari {{ $suratKeluar->total() }} data
                </div>
                <div>
                    {{ $suratKeluar->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus surat keluar ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush