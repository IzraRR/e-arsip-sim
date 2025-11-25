@extends('layouts.app-custom')

@section('title', 'Edit Arsip')
@section('page-title', 'Edit Arsip Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Form Edit Arsip
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('arsip.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nomor Dokumen -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_dokumen" 
                                       class="form-control @error('nomor_dokumen') is-invalid @enderror" 
                                       value="{{ old('nomor_dokumen', $arsip->nomor_dokumen) }}">
                                @error('nomor_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Dokumen -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Dokumen <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_dokumen" 
                                       class="form-control @error('tanggal_dokumen') is-invalid @enderror" 
                                       value="{{ old('tanggal_dokumen', $arsip->tanggal_dokumen->format('Y-m-d')) }}">
                                @error('tanggal_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Kategori -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="kategori_id" 
                                        class="form-select @error('kategori_id') is-invalid @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($kategoriList as $kategori)
                                        <option value="{{ $kategori->id }}" 
                                                {{ old('kategori_id', $arsip->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kode }} - {{ $kategori->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Judul -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Judul Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="judul" 
                                       class="form-control @error('judul') is-invalid @enderror" 
                                       value="{{ old('judul', $arsip->judul) }}">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Dokumen</label>
                                <input type="file" name="file_dokumen" 
                                       class="form-control @error('file_dokumen') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png">
                                @error('file_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($arsip->file_dokumen)
                                    <small class="text-success d-block mt-1">
                                        <i class="bi bi-check-circle"></i> File saat ini: {{ $arsip->file_dokumen }}
                                    </small>
                                @endif
                                <small class="text-muted d-block">
                                    Kosongkan jika tidak ingin mengubah file
                                </small>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tags</label>
                                <input type="text" name="tags" 
                                       class="form-control @error('tags') is-invalid @enderror" 
                                       value="{{ old('tags', $arsip->tags) }}" 
                                       placeholder="Contoh: penting, urgent, 2024">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" rows="4" 
                                          class="form-control @error('keterangan') is-invalid @enderror" 
                                          placeholder="Deskripsi dokumen...">{{ old('keterangan', $arsip->keterangan) }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('arsip.show', $arsip->id) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
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