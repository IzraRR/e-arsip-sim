@extends('layouts.app-custom')

@section('title', 'Detail Disposisi')
@section('page-title', 'Detail Disposisi')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <!-- Action Buttons -->
            <div class="mb-3 d-flex gap-2 flex-wrap">
                <a href="{{ route('disposisi.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                
                @if($disposisi->dari_user_id == auth()->id() || auth()->user()->role == 'admin')
                    <a href="{{ route('disposisi.edit', $disposisi->id) }}" class="btn btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $disposisi->id }})">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                @endif
                
                @if($disposisi->file_lampiran)
                    <a href="{{ route('disposisi.download', $disposisi->id) }}" class="btn btn-success">
                        <i class="bi bi-download"></i> Download Lampiran
                    </a>
                @endif

                <!-- Tombol Update Status (untuk penerima disposisi) -->
                @if($disposisi->kepada_user_id == auth()->id() && $disposisi->status != 'selesai')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusModal">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </button>
                @endif
            </div>

            <!-- Detail Surat yang Didisposisi -->
            <div class="card mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-envelope"></i> Surat yang Didisposisi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Agenda</label>
                            <h6>{{ $disposisi->suratMasuk->nomor_agenda }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nomor Surat</label>
                            <h6>{{ $disposisi->suratMasuk->nomor_surat }}</h6>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Surat</label>
                            <p class="mb-0">{{ $disposisi->suratMasuk->tanggal_surat->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Terima</label>
                            <p class="mb-0">{{ $disposisi->suratMasuk->tanggal_terima->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Pengirim</label>
                            <p class="mb-0">{{ $disposisi->suratMasuk->pengirim }}</p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Perihal</label>
                            <p>{{ $disposisi->suratMasuk->perihal }}</p>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('surat-masuk.show', $disposisi->suratMasuk->id) }}" 
                               class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye"></i> Lihat Detail Surat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Disposisi -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-diagram-3"></i> Informasi Disposisi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dari</label>
                            <h6>{{ $disposisi->dariUser->name }}</h6>
                            <small class="text-muted">{{ $disposisi->dariUser->unit_kerja }} - {{ ucfirst($disposisi->dariUser->role) }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Kepada</label>
                            <h6>{{ $disposisi->kepadaUser->name }}</h6>
                            <small class="text-muted">{{ $disposisi->kepadaUser->unit_kerja }} - {{ ucfirst($disposisi->kepadaUser->role) }}</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Tanggal Disposisi</label>
                            <p class="mb-0">{{ $disposisi->tanggal_disposisi->format('d F Y') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Batas Waktu</label>
                            <p class="mb-0">
                                @if($disposisi->batas_waktu)
                                    <span class="badge bg-danger">
                                        <i class="bi bi-clock"></i> {{ $disposisi->batas_waktu->format('d F Y') }}
                                    </span>
                                @else
                                    <span class="text-muted">Tidak ada batas waktu</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Status</label>
                            <div>
                                @if($disposisi->status == 'pending')
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-clock"></i> Pending
                                    </span>
                                    <small class="text-muted ms-2">Belum dibaca</small>
                                @elseif($disposisi->status == 'dibaca')
                                    <span class="badge bg-info fs-6">
                                        <i class="bi bi-eye"></i> Dibaca
                                    </span>
                                    <small class="text-muted ms-2">Sudah dibaca, belum diproses</small>
                                @elseif($disposisi->status == 'proses')
                                    <span class="badge bg-primary fs-6">
                                        <i class="bi bi-arrow-repeat"></i> Dalam Proses
                                    </span>
                                    <small class="text-muted ms-2">Sedang dikerjakan</small>
                                @else
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle"></i> Selesai
                                    </span>
                                    <small class="text-success ms-2">Sudah diselesaikan</small>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Instruksi</label>
                            <div class="p-3 border rounded bg-light" style="white-space: pre-line;">
                                {{ $disposisi->instruksi ?? '-' }}
                            </div>
                        </div>

                        @if($disposisi->catatan)
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Catatan</label>
                            <div class="p-3 rounded border" style="white-space: pre-line; background-color: #fff3cd; border-color: #ffecb5; color: #664d03;">
                                <i class="bi bi-chat-left-text"></i> {{ $disposisi->catatan }}
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Dibuat Pada</label>
                            <p class="mb-0">{{ $disposisi->created_at->format('d F Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Terakhir Update</label>
                            <p class="mb-0">{{ $disposisi->updated_at->format('d F Y H:i') }}</p>
                        </div>
                    </div>

                    <!-- File Lampiran -->
                    @if($disposisi->file_lampiran)
                        @php
                            $filePath = public_path('uploads/disposisi/' . $disposisi->file_lampiran);
                            $fileExists = file_exists($filePath);
                            $fileSize = $fileExists ? number_format(filesize($filePath) / 1024, 2) : '0';
                        @endphp
                        
                        <div class="alert {{ $fileExists ? 'alert-success' : 'alert-warning' }} d-flex align-items-center mt-3" role="alert">
                            <i class="bi {{ $fileExists ? 'bi-file-earmark-pdf' : 'bi-exclamation-triangle' }} fs-3 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>File Lampiran:</strong> {{ $disposisi->file_lampiran }}
                                <br>
                                @if($fileExists)
                                    <small>Ukuran: {{ $fileSize }} KB</small>
                                @else
                                    <small class="text-danger">⚠️ File tidak ditemukan</small>
                                @endif
                            </div>
                            @if($fileExists)
                                <a href="{{ route('disposisi.download', $disposisi->id) }}" class="btn btn-success">
                                    <i class="bi bi-download"></i> Download
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Update Status -->
@if($disposisi->kepada_user_id == auth()->id())
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status Disposisi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('disposisi.update-status', $disposisi->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="dibaca" {{ $disposisi->status == 'dibaca' ? 'selected' : '' }}>Dibaca</option>
                            <option value="proses" {{ $disposisi->status == 'proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="selesai">Selesai</option>
                        </select>
                        <small class="text-muted">Pilih "Selesai" jika sudah menyelesaikan disposisi</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control" rows="3" placeholder="Tambahkan catatan (opsional)">{{ $disposisi->catatan }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Form Delete Hidden -->
<form id="delete-form-{{ $disposisi->id }}" 
      action="{{ route('disposisi.destroy', $disposisi->id) }}" 
      method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus disposisi ini?')) {
        document.getElementById('delete-form-' + id).submit();
    }
}
</script>
@endpush