@extends('layouts.app-custom')

@section('title', 'Edit Disposisi')
@section('page-title', 'Edit Disposisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="bi bi-pencil-square"></i> Form Edit Disposisi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('disposisi.update', $disposisi->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Info Surat (Read Only) -->
                        <div class="alert alert-info">
                            <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Surat yang Didisposisi</h6>
                            <p class="mb-0">
                                <strong>No. Agenda:</strong> {{ $disposisi->suratMasuk->nomor_agenda }}<br>
                                <strong>Perihal:</strong> {{ $disposisi->suratMasuk->perihal }}
                            </p>
                        </div>

                        <div class="row">
                            <!-- Tanggal Disposisi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Disposisi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_disposisi" 
                                       class="form-control @error('tanggal_disposisi') is-invalid @enderror" 
                                       value="{{ old('tanggal_disposisi', $disposisi->tanggal_disposisi->format('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_disposisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Batas Waktu -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Batas Waktu</label>
                                <input type="date" name="batas_waktu" 
                                       class="form-control @error('batas_waktu') is-invalid @enderror" 
                                       value="{{ old('batas_waktu', $disposisi->batas_waktu?->format('Y-m-d')) }}">
                                @error('batas_waktu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Instruksi -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Instruksi Disposisi <span class="text-danger">*</span></label>
                                <textarea name="instruksi" rows="5" 
                                          class="form-control @error('instruksi') is-invalid @enderror" 
                                          required>{{ old('instruksi', $disposisi->instruksi) }}</textarea>
                                @error('instruksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Lampiran -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Lampiran</label>
                                <input type="file" name="file_lampiran" 
                                       class="form-control @error('file_lampiran') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('file_lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($disposisi->file_lampiran)
                                    <small class="text-success d-block mt-1">
                                        <i class="bi bi-check-circle"></i> File: {{ $disposisi->file_lampiran }}
                                    </small>
                                @endif
                                <small class="text-muted d-block">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                            </div>

                            <!-- Catatan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan</label>
                                <textarea name="catatan" rows="2" 
                                          class="form-control">{{ old('catatan', $disposisi->catatan) }}</textarea>
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('disposisi.show', $disposisi->id) }}" class="btn btn-secondary">
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