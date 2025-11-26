@extends('layouts.app-custom')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">
                        <i class="bi bi-people"></i> Data User
                    </h5>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Cari</label>
                    <input type="text" name="search" class="form-control" 
                           value="{{ request('search') }}" placeholder="Nama, Email, NIP...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="pimpinan" {{ request('role') == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                        <option value="staff" {{ request('role') == 'staff' ? 'selected' : '' }}>Staff</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i> Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th width="5%">NO</th>
                            <th>NAMA</th>
                            <th>EMAIL</th>
                            <th>NIP</th>
                            <th>ROLE</th>
                            <th>UNIT KERJA</th>
                            <th>STATUS</th>
                            <th width="15%" class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                        <tr>
                            <td>{{ $users->firstItem() + $index }}</td>
                            <td>
                                <strong>{{ $user->name }}</strong>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->nip ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'pimpinan' ? 'warning' : 'info') }}">
                                    {{ strtoupper($user->role) }}
                                </span>
                            </td>
                            <td>{{ $user->unit_kerja ?? '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $user->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ strtoupper($user->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="Detail">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Edit">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" 
                                          class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-2">Tidak ada data user</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


