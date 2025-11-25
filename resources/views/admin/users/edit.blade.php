@extends('layouts.app-custom')

@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil"></i> Form Edit User
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->id === auth()->id())
                    <div class="p-3 border rounded bg-warning text-dark mb-4">
                        <i class="bi bi-exclamation-triangle"></i> 
                        <strong>Peringatan!</strong> Anda sedang mengedit akun sendiri. 
                        Jika Anda mengubah role dari Admin ke role lain, Anda akan kehilangan akses ke halaman admin 
                        dan perlu meminta admin lain untuk mengubah kembali role Anda.
                    </div>
                    @endif

                    <form action="{{ route('admin.users.update', $user) }}" method="POST" 
                          @if($user->id === auth()->id() && $user->role == 'admin') 
                          onsubmit="return confirm('Apakah Anda yakin ingin mengubah role akun sendiri? Anda mungkin kehilangan akses admin!');"
                          @endif>
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" 
                                       value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Password Baru (Kosongkan jika tidak diubah)</label>
                                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Role <span class="text-danger">*</span></label>
                                <select name="role" class="form-select @error('role') is-invalid @enderror" required
                                        @if($user->id === auth()->id() && $user->role == 'admin') 
                                        onchange="if(this.value !== 'admin') { alert('PERINGATAN: Mengubah role dari Admin akan membuat Anda kehilangan akses ke halaman admin!'); }"
                                        @endif>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="pimpinan" {{ old('role', $user->role) == 'pimpinan' ? 'selected' : '' }}>Pimpinan</option>
                                    <option value="staff" {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                                @if($user->id === auth()->id() && $user->role == 'admin')
                                <small class="text-danger">
                                    <i class="bi bi-exclamation-triangle"></i> 
                                    Hati-hati! Mengubah role akan mempengaruhi akses Anda.
                                </small>
                                @endif
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Status <span class="text-danger">*</span></label>
                                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                                    <option value="aktif" {{ old('status', $user->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $user->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">NIP</label>
                                <input type="text" name="nip" class="form-control @error('nip') is-invalid @enderror" 
                                       value="{{ old('nip', $user->nip) }}">
                                @error('nip')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Unit Kerja</label>
                                <input type="text" name="unit_kerja" class="form-control" 
                                       value="{{ old('unit_kerja', $user->unit_kerja) }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control" 
                                       value="{{ old('telepon', $user->telepon) }}">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

