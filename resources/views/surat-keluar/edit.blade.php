@extends('layouts.app-custom')

@section('title', 'Edit Surat Keluar')
@section('page-title', 'Edit Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Form Edit Surat Keluar
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('surat-keluar.update', $suratKeluar->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <!-- Nomor Surat -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Surat <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_surat" 
                                       class="form-control @error('nomor_surat') is-invalid @enderror" 
                                       value="{{ old('nomor_surat', $suratKeluar->nomor_surat) }}">
                                @error('nomor_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Surat -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Surat <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_surat" 
                                       class="form-control @error('tanggal_surat') is-invalid @enderror" 
                                       value="{{ old('tanggal_surat', $suratKeluar->tanggal_surat->format('Y-m-d')) }}">
                                @error('tanggal_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tujuan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tujuan <span class="text-danger">*</span></label>
                                <input type="text" name="tujuan" 
                                       class="form-control @error('tujuan') is-invalid @enderror" 
                                       value="{{ old('tujuan', $suratKeluar->tujuan) }}" 
                                       placeholder="Contoh: Kepala Dinas Pendidikan Kota Jakarta">
                                @error('tujuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Perihal -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Perihal <span class="text-danger">*</span></label>
                                <textarea name="perihal" rows="3" 
                                          class="form-control @error('perihal') is-invalid @enderror" 
                                          placeholder="Masukkan perihal surat...">{{ old('perihal', $suratKeluar->perihal) }}</textarea>
                                @error('perihal')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Penandatangan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Penandatangan <span class="text-danger">*</span></label>
                                <input type="text" name="penandatangan" 
                                       class="form-control @error('penandatangan') is-invalid @enderror" 
                                       value="{{ old('penandatangan', $suratKeluar->penandatangan) }}" 
                                       placeholder="Contoh: Kepala Tata Usaha">
                                @error('penandatangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Prioritas -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror">
                                    <option value="biasa" {{ old('prioritas', $suratKeluar->prioritas) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="penting" {{ old('prioritas', $suratKeluar->prioritas) == 'penting' ? 'selected' : '' }}>Penting</option>
                                    <option value="segera" {{ old('prioritas', $suratKeluar->prioritas) == 'segera' ? 'selected' : '' }}>Segera</option>
                                </select>
                                @error('prioritas')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Sifat -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Sifat <span class="text-danger">*</span></label>
                                <select name="sifat" class="form-select @error('sifat') is-invalid @enderror">
                                    <option value="biasa" {{ old('sifat', $suratKeluar->sifat) == 'biasa' ? 'selected' : '' }}>Biasa</option>
                                    <option value="rahasia" {{ old('sifat', $suratKeluar->sifat) == 'rahasia' ? 'selected' : '' }}>Rahasia</option>
                                    <option value="sangat_rahasia" {{ old('sifat', $suratKeluar->sifat) == 'sangat_rahasia' ? 'selected' : '' }}>Sangat Rahasia</option>
                                </select>
                                @error('sifat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-4 mb-3">
                                <label class="form-label">File Surat</label>
                                <input type="file" name="file_surat" 
                                       class="form-control @error('file_surat') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('file_surat')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($suratKeluar->file_surat)
                                    <small class="text-success d-block mt-1">
                                        <i class="bi bi-check-circle"></i> File: {{ $suratKeluar->file_surat }}
                                    </small>
                                @endif
                                <small class="text-muted d-block">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan (Opsional)</label>
                                <textarea name="keterangan" rows="2" 
                                          class="form-control" 
                                          placeholder="Keterangan tambahan...">{{ old('keterangan', $suratKeluar->keterangan) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
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