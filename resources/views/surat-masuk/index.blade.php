@extends('layouts.app-custom')

@section('title', 'Surat Masuk')
@section('page-title', 'Surat Masuk')

@section('content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i> Data Surat Masuk
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('surat-masuk.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Surat Masuk
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('surat-masuk.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disposisi" {{ request('status') == 'disposisi' ? 'selected' : '' }}>Disposisi</option>
                        <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari nomor, pengirim, perihal..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
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
                            <th width="12%">No. Agenda</th>
                            <th width="12%">No. Surat</th>
                            <th width="10%">Tgl. Terima</th>
                            <th width="15%">Pengirim</th>
                            <th>Perihal</th>
                            <th width="8%">Status</th>
                            <th width="8%">Prioritas</th>
                            <th width="12%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suratMasuk as $index => $surat)
                        <tr>
                            <td>{{ $suratMasuk->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $surat->nomor_agenda }}</strong>
                            </td>
                            <td>{{ $surat->nomor_surat }}</td>
                            <td>{{ $surat->tanggal_terima->format('d/m/Y') }}</td>
                            <td>{{ $surat->pengirim }}</td>
                            <td>{{ Str::limit($surat->perihal, 50) }}</td>
                            <td>
                                @if($surat->status == 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif($surat->status == 'disposisi')
                                    <span class="badge bg-info">
                                        <i class="bi bi-arrow-right-circle"></i> Disposisi
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Selesai
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
                                    <a href="{{ route('surat-masuk.show', $surat->id) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('surat-masuk.edit', $surat->id) }}" 
                                       class="btn btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" class="btn btn-danger" 
                                            onclick="confirmDelete({{ $surat->id }})" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>

                                <form id="delete-form-{{ $surat->id }}" 
                                      action="{{ route('surat-masuk.destroy', $surat->id) }}" 
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
                                <p class="text-muted mt-2 mb-0">Belum ada data surat masuk</p>
                                <a href="{{ route('surat-masuk.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-plus-circle"></i> Tambah Surat Masuk
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
                    Menampilkan {{ $suratMasuk->firstItem() ?? 0 }} - {{ $suratMasuk->lastItem() ?? 0 }} 
                    dari {{ $suratMasuk->total() }} data
                </div>
                <div>
                    {{ $suratMasuk->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus surat masuk ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush