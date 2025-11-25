@extends('layouts.app-custom')

@section('title', 'Detail Arsip')
@section('page-title', 'Detail Arsip Dokumen')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Action Buttons -->
            <div class="mb-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('arsip.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('arsip.edit', $arsip->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <a href="{{ route('arsip.download', $arsip->id) }}" class="btn btn-success">
                    <i class="bi bi-download"></i> Download File
                </a>
                @if(auth()->user()->role == 'admin')
                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $arsip->id }})">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                @endif
            </div>

            <div class="row">
                <!-- Detail Dokumen -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark-text"></i> Informasi Dokumen
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Nomor Dokumen</label>
                                    <h5>{{ $arsip->nomor_dokumen }}</h5>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Tanggal Dokumen</label>
                                    <h5>{{ $arsip->tanggal_dokumen->format('d F Y') }}</h5>
                                </div>
                                
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Judul Dokumen</label>
                                    <h4 class="text-primary">{{ $arsip->judul }}</h4>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Kategori</label>
                                    <div>
                                        <span class="badge bg-primary fs-6">
                                            {{ $arsip->kategori->kode }} - {{ $arsip->kategori->nama_kategori }}
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Diupload Oleh</label>
                                    <p class="mb-0">
                                        <strong>{{ $arsip->user->name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $arsip->user->unit_kerja }}</small>
                                    </p>
                                </div>
                                
                                @if($arsip->tags)
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Tags</label>
                                    <div>
                                        @foreach(explode(',', $arsip->tags) as $tag)
                                            <span class="badge bg-light text-dark me-1">
                                                <i class="bi bi-tag"></i> {{ trim($tag) }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
                                @if($arsip->keterangan)
                                <div class="col-md-12 mb-3">
                                    <label class="text-muted small">Keterangan</label>
                                    <div class="alert alert-light border">
                                        {{ $arsip->keterangan }}
                                    </div>
                                </div>
                                @endif
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Tanggal Upload</label>
                                    <p class="mb-0">{{ $arsip->created_at->format('d F Y H:i') }}</p>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted small">Terakhir Update</label>
                                    <p class="mb-0">{{ $arsip->updated_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Preview Card -->
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-file-earmark"></i> File Dokumen
                            </h5>
                        </div>
                        <div class="card-body text-center">
                            @php
                                $filePath = public_path('uploads/arsip/' . $arsip->file_dokumen);
                                $fileExists = file_exists($filePath);
                                $fileSize = $fileExists ? number_format(filesize($filePath) / 1024, 2) : '0';
                                
                                // Deteksi ekstensi file
                                $extension = pathinfo($arsip->file_dokumen, PATHINFO_EXTENSION);
                                $iconColor = match(strtolower($extension)) {
                                    'pdf' => 'text-danger',
                                    'doc', 'docx' => 'text-primary',
                                    'xls', 'xlsx' => 'text-success',
                                    'jpg', 'jpeg', 'png' => 'text-warning',
                                    default => 'text-secondary'
                                };
                                $iconClass = match(strtolower($extension)) {
                                    'pdf' => 'bi-file-earmark-pdf',
                                    'doc', 'docx' => 'bi-file-earmark-word',
                                    'xls', 'xlsx' => 'bi-file-earmark-excel',
                                    'jpg', 'jpeg', 'png' => 'bi-file-earmark-image',
                                    default => 'bi-file-earmark'
                                };
                            @endphp
                            
                            @if($fileExists)
                                <i class="bi {{ $iconClass }} {{ $iconColor }}" style="font-size: 6rem;"></i>
                                
                                <h6 class="mt-3 mb-2">{{ $arsip->file_dokumen }}</h6>
                                
                                <div class="mb-3">
                                    <span class="badge bg-secondary">{{ strtoupper($extension) }}</span>
                                    <span class="badge bg-info">{{ $fileSize }} KB</span>
                                </div>
                                
                                <a href="{{ route('arsip.download', $arsip->id) }}" 
                                   class="btn btn-success w-100">
                                    <i class="bi bi-download"></i> Download File
                                </a>
                                
                                <!-- Image Preview -->
                                @if(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <div class="mt-3">
                                        <img src="{{ asset('uploads/arsip/' . $arsip->file_dokumen) }}" 
                                             alt="Preview" 
                                             class="img-fluid rounded"
                                             style="max-height: 300px;">
                                    </div>
                                @endif
                            @else
                                <i class="bi bi-exclamation-triangle text-danger" style="font-size: 6rem;"></i>
                                <h6 class="mt-3 text-danger">File Tidak Ditemukan</h6>
                                <p class="text-muted small">File mungkin sudah dihapus atau dipindahkan</p>
                            @endif
                        </div>
                    </div>

                    <!-- QR Code Card (Optional) -->
                    <div class="card mt-3">
                        <div class="card-header bg-info text-white">
                            <h6 class="mb-0">
                                <i class="bi bi-qr-code"></i> Quick Access
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('arsip.show', $arsip->id)) }}" 
                                 alt="QR Code" 
                                 class="img-fluid">
                            <small class="text-muted d-block mt-2">Scan untuk akses cepat</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="delete-form-{{ $arsip->id }}" 
      action="{{ route('arsip.destroy', $arsip->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus arsip ini?\n\nFile dokumen juga akan terhapus permanen!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush