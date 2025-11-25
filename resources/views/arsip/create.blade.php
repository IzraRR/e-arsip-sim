@extends('layouts.app-custom')

@section('title', 'Upload Dokumen')
@section('page-title', 'Upload Dokumen Arsip')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-cloud-upload"></i> Form Upload Dokumen
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Nomor Dokumen -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nomor Dokumen <span class="text-danger">*</span></label>
                                <input type="text" name="nomor_dokumen" 
                                       class="form-control @error('nomor_dokumen') is-invalid @enderror" 
                                       value="{{ old('nomor_dokumen', $nomorDokumen) }}">
                                @error('nomor_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Nomor dokumen otomatis atau manual</small>
                            </div>

                            <!-- Tanggal Dokumen -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Dokumen <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_dokumen" 
                                       class="form-control @error('tanggal_dokumen') is-invalid @enderror" 
                                       value="{{ old('tanggal_dokumen', date('Y-m-d')) }}">
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
                                                {{ old('kategori_id') == $kategori->id ? 'selected' : '' }}>
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
                                       value="{{ old('judul') }}" 
                                       placeholder="Contoh: Surat Keputusan Tahun 2024">
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- File Upload -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Dokumen <span class="text-danger">*</span></label>
                                <input type="file" name="file_dokumen" 
                                       class="form-control @error('file_dokumen') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png"
                                       onchange="previewFileInfo(this)">
                                @error('file_dokumen')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted d-block">
                                    Format: PDF, DOC, DOCX, XLS, XLSX, JPG, PNG | Maksimal: 10MB
                                </small>
                                
                                <!-- File Preview Info -->
                                <div id="file-preview" class="mt-2" style="display: none;">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-file-earmark"></i> 
                                        <strong id="file-name"></strong> 
                                        (<span id="file-size"></span>)
                                    </div>
                                </div>
                            </div>

                            <!-- Tags -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Tags (Opsional)</label>
                                <input type="text" name="tags" 
                                       class="form-control @error('tags') is-invalid @enderror" 
                                       value="{{ old('tags') }}" 
                                       placeholder="Contoh: penting, urgent, 2024 (pisahkan dengan koma)">
                                @error('tags')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Gunakan koma untuk memisahkan tags</small>
                            </div>

                            <!-- Keterangan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Keterangan (Opsional)</label>
                                <textarea name="keterangan" rows="4" 
                                          class="form-control @error('keterangan') is-invalid @enderror" 
                                          placeholder="Deskripsi atau catatan tentang dokumen ini...">{{ old('keterangan') }}</textarea>
                                @error('keterangan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <i class="bi bi-info-circle"></i> 
                            <strong>Catatan:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Pastikan file dokumen sudah sesuai sebelum diupload</li>
                                <li>Dokumen yang sudah diupload akan tersimpan secara permanen</li>
                                <li>Gunakan tags untuk memudahkan pencarian</li>
                            </ul>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('arsip.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload Dokumen
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function previewFileInfo(input) {
    const preview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    const fileSize = document.getElementById('file-size');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
    }
}

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}
</script>
@endpush