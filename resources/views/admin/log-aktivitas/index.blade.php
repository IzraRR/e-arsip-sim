@extends('layouts.app-custom')

@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
<div class="container-fluid">
    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.log-aktivitas.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Aktivitas, modul, keterangan...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Modul</label>
                    <select name="modul" class="form-select">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('modul') == $module ? 'selected' : '' }}>
                            {{ $module }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">User</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua User</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
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
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
            <div class="mt-3">
                <a href="{{ route('admin.log-aktivitas.index') }}" class="btn btn-sm btn-secondary">
                    <i class="bi bi-arrow-clockwise"></i> Reset
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-list-ul"></i> Daftar Log Aktivitas</h5>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#clearModal">
                <i class="bi bi-trash"></i> Hapus Log Lama
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th width="12%">WAKTU</th>
                            <th>USER</th>
                            <th>AKTIVITAS</th>
                            <th>MODUL</th>
                            <th>KETERANGAN</th>
                            <th width="10%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $index => $log)
                        <tr>
                            <td>{{ $logs->firstItem() + $index }}</td>
                            <td>
                                <small>{{ \Carbon\Carbon::parse($log->created_at)->format('d/m/Y') }}</small><br>
                                <small class="text-muted">{{ \Carbon\Carbon::parse($log->created_at)->format('H:i:s') }}</small>
                            </td>
                            <td>
                                @if($log->user_name)
                                <strong>{{ $log->user_name }}</strong><br>
                                <small class="text-muted">{{ $log->user_email }}</small>
                                @else
                                <span class="text-muted">System</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $log->modul == 'Authentication' ? 'primary' : ($log->modul == 'Manajemen User' ? 'danger' : 'info') }}">
                                    {{ $log->aktivitas }}
                                </span>
                            </td>
                            <td><span class="badge bg-secondary">{{ $log->modul }}</span></td>
                            <td>{{ Str::limit($log->keterangan, 60) }}</td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.log-aktivitas.show', $log->id) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form action="{{ route('admin.log-aktivitas.destroy', $log->id) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Yakin ingin menghapus log ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Tidak ada log aktivitas</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Clear Old Logs -->
<div class="modal fade" id="clearModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.log-aktivitas.clear') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Log Lama</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Masukkan jumlah hari untuk menghapus log yang lebih lama dari hari tersebut.</p>
                    <div class="mb-3">
                        <label class="form-label">Hapus log lebih dari (hari)</label>
                        <input type="number" name="days" class="form-control" value="30" min="1" max="365" required>
                        <small class="text-muted">Contoh: 30 = hapus log yang lebih dari 30 hari</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


