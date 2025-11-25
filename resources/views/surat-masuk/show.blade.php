@extends('layouts.app-custom')

@section('title', 'Detail Surat Masuk')
@section('page-title', 'Detail Surat Masuk')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Action Buttons -->
            <div class="mb-3">
                <a href="{{ route('surat-masuk.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <a href="{{ route('surat-masuk.edit', $suratMasuk->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                @if($suratMasuk->file_surat)
                    <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Download File
                    </a>
                @endif
                <a href="{{ route('disposisi.create', ['surat_masuk_id' => $suratMasuk->id]) }}" class="btn btn-primary">
                    <i class="bi bi-arrow-right-circle"></i> Disposisi
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $suratMasuk->id }})">
                    <i class="bi bi-trash"></i> Hapus
                </button>
            </div>

            <!-- Detail Surat -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Informasi Surat Masuk
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Agenda</label>
                            <h5>{{ $suratMasuk->nomor_agenda }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Surat</label>
                            <h5>{{ $suratMasuk->nomor_surat }}</h5>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Surat</label>
                            <p class="mb-0">{{ $suratMasuk->tanggal_surat->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Terima</label>
                            <p class="mb-0">{{ $suratMasuk->tanggal_terima->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Pengirim</label>
                            <h6>{{ $suratMasuk->pengirim }}</h6>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Perihal</label>
                            <p>{{ $suratMasuk->perihal }}</p>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($suratMasuk->status == 'pending')
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                @elseif($suratMasuk->status == 'disposisi')
                                    <span class="badge bg-info fs-6">
                                        <i class="bi bi-arrow-right-circle"></i> Disposisi
                                    </span>
                                @else
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Prioritas</label>
                            <div>
                                @if($suratMasuk->prioritas == 'segera')
                                    <span class="badge bg-danger fs-6">Segera</span>
                                @elseif($suratMasuk->prioritas == 'penting')
                                    <span class="badge bg-warning text-dark fs-6">Penting</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Biasa</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="text-muted small">Sifat</label>
                            <div>
                                @if($suratMasuk->sifat == 'sangat_rahasia')
                                    <span class="badge bg-dark fs-6">Sangat Rahasia</span>
                                @elseif($suratMasuk->sifat == 'rahasia')
                                    <span class="badge bg-danger fs-6">Rahasia</span>
                                @else
                                    <span class="badge bg-secondary fs-6">Biasa</span>
                                @endif
                            </div>
                        </div>
                        @if($suratMasuk->keterangan)
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Keterangan</label>
                            <p>{{ $suratMasuk->keterangan }}</p>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Diinput Oleh</label>
                            <p class="mb-0">{{ $suratMasuk->user->name }} ({{ $suratMasuk->user->unit_kerja }})</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Waktu Input</label>
                            <p class="mb-0">{{ $suratMasuk->created_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- File Preview -->
                     @if($suratMasuk->file_surat)
                        @php
                            $filePath = public_path('uploads/surat_masuk/' . $suratMasuk->file_surat);
                            $fileExists = file_exists($filePath);
                            $fileSize = $fileExists ? number_format(filesize($filePath) / 1024, 2) : '0';
                            
                            // Deteksi ekstensi file untuk icon yang sesuai
                            $extension = pathinfo($suratMasuk->file_surat, PATHINFO_EXTENSION);
                            $iconClass = match(strtolower($extension)) {
                                'pdf' => 'bi-file-earmark-pdf',
                                'doc', 'docx' => 'bi-file-earmark-word',
                                'xls', 'xlsx' => 'bi-file-earmark-excel',
                                'jpg', 'jpeg', 'png', 'gif' => 'bi-file-earmark-image',
                                'zip', 'rar' => 'bi-file-earmark-zip',
                                default => 'bi-file-earmark'
                            };
                        @endphp
                        
                        <div class="p-3 rounded border d-flex align-items-center" style="background-color: #d1e7dd; border-color: #badbcc; color: #0f5132;">
                            <i class="bi {{ $fileExists ? $iconClass : 'bi-exclamation-triangle' }} fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>File Lampiran:</strong> {{ $suratMasuk->file_surat }}
                                <br>
                                @if($fileExists)
                                    <small>Ukuran: {{ $fileSize }} KB | Tipe: {{ strtoupper($extension) }}</small>
                                @else
                                    <small class="text-danger">⚠️ File tidak ditemukan di server</small>
                                @endif
                            </div>
                            @if($fileExists)
                                <a href="{{ route('surat-masuk.download', $suratMasuk->id) }}" class="btn btn-success">
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
                    
            <!-- Riwayat Disposisi -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3"></i> Riwayat Disposisi
                    </h5>
                </div>
                <div class="card-body">
                    @forelse($suratMasuk->disposisi as $disposisi)
                    <div class="border-start border-primary border-4 ps-3 pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <div>
                                <strong>{{ $disposisi->dariUser->name }}</strong>
                                <i class="bi bi-arrow-right mx-2"></i>
                                <strong class="text-primary">{{ $disposisi->kepadaUser->name }}</strong>
                            </div>
                            <span class="badge bg-{{ $disposisi->status == 'selesai' ? 'success' : 'warning' }}">
                                {{ ucfirst($disposisi->status) }}
                            </span>
                        </div>
                        <small class="text-muted">{{ $disposisi->tanggal_disposisi->format('d F Y') }}</small>
                        <p class="mt-2 mb-0">{{ $disposisi->instruksi }}</p>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada disposisi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Delete Hidden -->
<form id="delete-form-{{ $suratMasuk->id }}" 
      action="{{ route('surat-masuk.destroy', $suratMasuk->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus surat masuk ini?\n\nData disposisi yang terkait juga akan terhapus!')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush