@extends('layouts.app-custom')

@section('title', 'Disposisi Surat')
@section('page-title', 'Disposisi Surat')

@section('content')
<div class="container-fluid">
    <!-- Header & Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3"></i> Data Disposisi
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('disposisi.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Buat Disposisi
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <form action="{{ route('disposisi.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="dibaca" {{ request('status') == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                        <option value="proses" {{ request('status') == 'proses' ? 'selected' : '' }}>Proses</option>
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
                    <input type="text" name="search" class="form-control" placeholder="Cari instruksi, nomor surat..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('disposisi.index') }}" class="btn btn-secondary">
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
                            <th width="12%">Tanggal</th>
                            <th width="15%">Dari</th>
                            <th width="15%">Kepada</th>
                            <th width="15%">Surat</th>
                            <th>Instruksi</th>
                            <th width="10%">Status</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($disposisi as $index => $item)
                        <tr class="{{ $item->kepada_user_id == auth()->id() && $item->status == 'pending' ? 'table-warning' : '' }}">
                            <td>{{ $disposisi->firstItem() + $index }}</td>
                            <td>
                                {{ $item->tanggal_disposisi->format('d/m/Y') }}
                                @if($item->batas_waktu)
                                    <br>
                                    <small class="text-danger">
                                        <i class="bi bi-clock"></i> {{ $item->batas_waktu->format('d/m/Y') }}
                                    </small>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->dariUser->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->dariUser->unit_kerja }}</small>
                            </td>
                            <td>
                                <strong>{{ $item->kepadaUser->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $item->kepadaUser->unit_kerja }}</small>
                            </td>
                            <td>
                                <strong>{{ $item->suratMasuk->nomor_agenda }}</strong>
                                <br>
                                <small class="text-muted">{{ Str::limit($item->suratMasuk->perihal, 30) }}</small>
                            </td>
                            <td>{{ Str::limit($item->instruksi, 50) }}</td>
                            <td>
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif($item->status == 'dibaca')
                                    <span class="badge bg-info">
                                        <i class="bi bi-eye"></i> Dibaca
                                    </span>
                                @elseif($item->status == 'proses')
                                    <span class="badge bg-primary">
                                        <i class="bi bi-arrow-repeat"></i> Proses
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('disposisi.show', $item->id) }}" 
                                       class="btn btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    
                                    @if($item->dari_user_id == auth()->id() || auth()->user()->role == 'admin')
                                        <a href="{{ route('disposisi.edit', $item->id) }}" 
                                           class="btn btn-warning" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" 
                                                onclick="confirmDelete({{ $item->id }})" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @endif
                                </div>

                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('disposisi.destroy', $item->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada data disposisi</p>
                                <a href="{{ route('disposisi.create') }}" class="btn btn-sm btn-primary mt-3">
                                    <i class="bi bi-plus-circle"></i> Buat Disposisi
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
                    Menampilkan {{ $disposisi->firstItem() ?? 0 }} - {{ $disposisi->lastItem() ?? 0 }} 
                    dari {{ $disposisi->total() }} data
                </div>
                <div>
                    {{ $disposisi->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus disposisi ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush