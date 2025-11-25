@extends('layouts.app-custom')

@section('title', 'Manajemen Kategori')
@section('page-title', 'Manajemen Kategori Dokumen')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-tags"></i> Data Kategori Dokumen
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-circle"></i> Tambah Kategori
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h3 class="text-primary">{{ $kategori->total() }}</h3>
                    <p class="text-muted mb-0">Total Kategori</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h3 class="text-success">{{ $kategori->where('arsip_count', '>', 0)->count() }}</h3>
                    <p class="text-muted mb-0">Kategori Terpakai</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h3 class="text-warning">{{ $kategori->where('arsip_count', 0)->count() }}</h3>
                    <p class="text-muted mb-0">Kategori Kosong</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h3 class="text-info">{{ $kategori->sum('arsip_count') }}</h3>
                    <p class="text-muted mb-0">Total Arsip</p>
                </div>
            </div>
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
                            <th width="10%">Kode</th>
                            <th width="25%">Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th width="10%">Jumlah Arsip</th>
                            <th width="15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kategori as $index => $item)
                        <tr>
                            <td>{{ $kategori->firstItem() + $index }}</td>
                            <td>
                                <span class="badge bg-primary fs-6">{{ $item->kode }}</span>
                            </td>
                            <td><strong>{{ $item->nama_kategori }}</strong></td>
                            <td>{{ $item->deskripsi ?? '-' }}</td>
                            <td>
                                @if($item->arsip_count > 0)
                                    <span class="badge bg-success">{{ $item->arsip_count }} Arsip</span>
                                @else
                                    <span class="badge bg-secondary">Kosong</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-warning" 
                                            onclick="editKategori({{ $item->id }}, '{{ $item->kode }}', '{{ $item->nama_kategori }}', '{{ $item->deskripsi }}')"
                                            title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    
                                    @if($item->arsip_count == 0)
                                        <button type="button" class="btn btn-danger" 
                                                onclick="confirmDelete({{ $item->id }})" 
                                                title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-secondary" 
                                                disabled 
                                                title="Tidak bisa dihapus (ada arsip)">
                                            <i class="bi bi-lock"></i>
                                        </button>
                                    @endif
                                </div>

                                <form id="delete-form-{{ $item->id }}" 
                                      action="{{ route('admin.kategori.destroy', $item->id) }}" 
                                      method="POST" style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2 mb-0">Belum ada kategori</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    Menampilkan {{ $kategori->firstItem() ?? 0 }} - {{ $kategori->lastItem() ?? 0 }} 
                    dari {{ $kategori->total() }} data
                </div>
                <div>
                    {{ $kategori->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="bi bi-plus-circle"></i> Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode" class="form-control" 
                               placeholder="Contoh: SK, ST, ND" 
                               maxlength="10" 
                               required 
                               style="text-transform: uppercase;">
                        <small class="text-muted">Maksimal 10 karakter, gunakan huruf kapital</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" class="form-control" 
                               placeholder="Contoh: Surat Keputusan" 
                               maxlength="100" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="3" 
                                  placeholder="Deskripsi kategori (opsional)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="bi bi-pencil-square"></i> Edit Kategori
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Kode Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="kode" id="edit_kode" class="form-control" 
                               maxlength="10" 
                               required 
                               style="text-transform: uppercase;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="nama_kategori" id="edit_nama_kategori" class="form-control" 
                               maxlength="100" 
                               required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-save"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function editKategori(id, kode, nama, deskripsi) {
    document.getElementById('editForm').action = '/admin/kategori/' + id;
    document.getElementById('edit_kode').value = kode;
    document.getElementById('edit_nama_kategori').value = nama;
    document.getElementById('edit_deskripsi').value = deskripsi || '';
    
    const modal = new bootstrap.Modal(document.getElementById('editModal'));
    modal.show();
}

function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus kategori ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}

// Auto show modal if validation error
@if($errors->any())
    @if(old('_method') == 'PUT')
        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    @else
        const createModal = new bootstrap.Modal(document.getElementById('createModal'));
        createModal.show();
    @endif
@endif
</script>
@endpush