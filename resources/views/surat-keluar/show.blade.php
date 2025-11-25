@extends('layouts.app-custom')

@section('title', 'Detail Surat Keluar')
@section('page-title', 'Detail Surat Keluar')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Action Buttons -->
            <div class="mb-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('surat-keluar.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                
                @if($suratKeluar->status == 'draft')
                    <a href="{{ route('surat-keluar.edit', $suratKeluar->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                @endif
                
                @if($suratKeluar->file_surat)
                    <a href="{{ route('surat-keluar.download', $suratKeluar->id) }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Download File
                    </a>
                @endif
                
                <!-- Tombol Approve (hanya untuk Pimpinan dan jika status draft) -->
                @if(auth()->user()->role == 'pimpinan' && $suratKeluar->status == 'draft')
                    <form action="{{ route('surat-keluar.approve', $suratKeluar->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary" onclick="return confirm('Approve surat ini?')">
                            <i class="bi bi-check-circle"></i> Approve
                        </button>
                    </form>
                @endif
                
                <!-- Tombol Kirim (hanya jika sudah approved) -->
                @if($suratKeluar->status == 'approved')
                    <form action="{{ route('surat-keluar.sent', $suratKeluar->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Tandai surat ini sebagai terkirim?')">
                            <i class="bi bi-send-check"></i> Tandai Terkirim
                        </button>
                    </form>
                @endif
                
                <!-- Tombol Hapus (hanya untuk Admin atau pembuat surat) -->
                @if(auth()->user()->role == 'admin' || auth()->id() == $suratKeluar->user_id)
                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $suratKeluar->id }})">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                @endif
            </div>

            <!-- Detail Surat -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Informasi Surat Keluar
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Surat</label>
                            <h5>{{ $suratKeluar->nomor_surat }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Surat</label>
                            <h5>{{ $suratKeluar->tanggal_surat->format('d F Y') }}</h5>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Tujuan</label>
                            <h6>{{ $suratKeluar->tujuan }}</h6>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Perihal</label>
                            <p>{{ $suratKeluar->perihal }}</p>
                        </div>
                        
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Penandatangan</label>
                            <p class="mb-0"><strong>{{ $suratKeluar->penandatangan }}</strong></p>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($suratKeluar->status == 'draft')
                                    <span class="badge bg-secondary fs-6">
                                        <i class="bi bi-file-text"></i> Draft
                                    </span>
                                    <small class="d-block text-muted mt-1">Menunggu approval</small>
                                @elseif($suratKeluar->status == 'approved')
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-check-circle"></i> Approved
                                    </span>
                                    <small class="d-block text-success mt-1">Siap dikirim</small>
                                @else
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-send-check"></i> Terkirim
                                    </span>
                                    <small class="d-block text-success mt-1">Surat sudah terkirim</small>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Prioritas</label>
                            <div>
                                @if($suratKeluar->prioritas == 'segera')
                                    <span class="badge bg-danger fs-6">Segera</span>
                                @elseif($suratKeluar->prioritas == 'penting')
                                    <span class="badge bg-warning text-dark fs-6">Penting</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Biasa</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Sifat</label>
                            <div>
                                @if($suratKeluar->sifat == 'sangat_rahasia')
                                    <span class="badge bg-dark fs-6">Sangat Rahasia</span>
                                @elseif($suratKeluar->sifat == 'rahasia')
                                    <span class="badge bg-danger fs-6">Rahasia</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Biasa</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($suratKeluar->keterangan)
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Keterangan</label>
                            <p>{{ $suratKeluar->keterangan }}</p>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dibuat Oleh</label>
                            <p class="mb-0">{{ $suratKeluar->user->name }} ({{ $suratKeluar->user->unit_kerja }})</p>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Waktu Dibuat</label>
                            <p class="mb-0">{{ $suratKeluar->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- File Preview -->
                    @if($suratKeluar->file_surat)
                        @php
                            $filePath = public_path('uploads/surat_keluar/' . $suratKeluar->file_surat);
                            $fileExists = file_exists($filePath);
                            $fileSize = $fileExists ? number_format(filesize($filePath) / 1024, 2) : '0';
                            
                            // Deteksi ekstensi file
                            $extension = pathinfo($suratKeluar->file_surat, PATHINFO_EXTENSION);
                            $iconClass = match(strtolower($extension)) {
                                'pdf' => 'bi-file-earmark-pdf',
                                'doc', 'docx' => 'bi-file-earmark-word',
                                'xls', 'xlsx' => 'bi-file-earmark-excel',
                                'jpg', 'jpeg', 'png' => 'bi-file-earmark-image',
                                default => 'bi-file-earmark'
                            };
                        @endphp
                        
                        <div class="ap-3 rounded border d-flex align-items-center" style="background-color: #d1e7dd; border-color: #badbcc; color: #0f5132;">
                            <i class="bi {{ $fileExists ? $iconClass : 'bi-exclamation-triangle' }} fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>File Lampiran:</strong> {{ $suratKeluar->file_surat }}
                                <br>
                                @if($fileExists)
                                    <small>Ukuran: {{ $fileSize }} KB | Tipe: {{ strtoupper($extension) }}</small>
                                @else
                                    <small class="text-danger">⚠️ File tidak ditemukan di server</small>
                                @endif
                            </div>
                            @if($fileExists)
                                <a href="{{ route('surat-keluar.download', $suratKeluar->id) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @else
                                <button class="btn btn-secondary" disabled>
                                    <i class="bi bi-x-circle"></i> File Hilang
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="col-md-4 mb-3">
                            <i class="bi bi-info-circle"></i> Tidak ada file lampiran
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timeline Status -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history"></i> Timeline Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <!-- Draft -->
                        <div class="timeline-item">
                            <div class="timeline-marker bg-secondary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Draft</h6>
                                <small class="text-muted">{{ $suratKeluar->created_at->format('d F Y H:i') }}</small>
                                <p class="mb-0 mt-1">Surat dibuat oleh {{ $suratKeluar->user->name }}</p>
                            </div>
                        </div>

                        <!-- Approved -->
                        @if($suratKeluar->status == 'approved' || $suratKeluar->status == 'sent')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Approved</h6>
                                <small class="text-muted">{{ $suratKeluar->updated_at->format('d F Y H:i') }}</small>
                                <p class="mb-0 mt-1">Surat disetujui</p>
                            </div>
                        </div>
                        @endif

                        <!-- Sent -->
                        @if($suratKeluar->status == 'sent')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="mb-1">Terkirim</h6>
                                <small class="text-muted">{{ $suratKeluar->updated_at->format('d F Y H:i') }}</small>
                                <p class="mb-0 mt-1">Surat telah dikirim</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="delete-form-{{ $suratKeluar->id }}" 
      action="{{ route('surat-keluar.destroy', $suratKeluar->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    padding-bottom: 2rem;
}

.timeline-item:last-child {
    padding-bottom: 0;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.5rem;
    bottom: -1.5rem;
    width: 2px;
    background: #dee2e6;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: absolute;
    left: -1.875rem;
    top: 0;
    width: 1rem;
    height: 1rem;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #dee2e6;
}

.timeline-content {
    padding-left: 1rem;
}
</style>
@endpush

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus surat keluar ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush