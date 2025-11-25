@extends('layouts.app-custom')

@section('title', 'Buat Disposisi')
@section('page-title', 'Buat Disposisi Surat')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="bi bi-plus-circle"></i> Form Buat Disposisi
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('disposisi.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Pilih Surat Masuk -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Surat Masuk <span class="text-danger">*</span></label>
                                <select name="surat_masuk_id" id="surat_masuk_id" 
                                        class="form-select @error('surat_masuk_id') is-invalid @enderror" 
                                        required onchange="showSuratDetail()">
                                    <option value="">-- Pilih Surat Masuk --</option>
                                    @foreach($suratMasukList as $surat)
                                        <option value="{{ $surat->id }}" 
                                                {{ (old('surat_masuk_id', $suratMasuk?->id) == $surat->id) ? 'selected' : '' }}
                                                data-nomor="{{ $surat->nomor_agenda }}"
                                                data-pengirim="{{ $surat->pengirim }}"
                                                data-perihal="{{ $surat->perihal }}"
                                                data-tanggal="{{ $surat->tanggal_terima->format('d/m/Y') }}">
                                            {{ $surat->nomor_agenda }} - {{ Str::limit($surat->perihal, 60) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('surat_masuk_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Detail Surat (Auto Show) -->
                            <div class="col-md-12 mb-3" id="surat-detail" style="display: none;">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="bi bi-info-circle"></i> Detail Surat</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr>
                                            <td width="150"><strong>No. Agenda</strong></td>
                                            <td id="detail-nomor">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tanggal Terima</strong></td>
                                            <td id="detail-tanggal">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Pengirim</strong></td>
                                            <td id="detail-pengirim">-</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Perihal</strong></td>
                                            <td id="detail-perihal">-</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            <!-- Disposisi Kepada -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Disposisi Kepada <span class="text-danger">*</span></label>
                                <select name="kepada_user_id" 
                                        class="form-select @error('kepada_user_id') is-invalid @enderror" 
                                        required>
                                    <option value="">-- Pilih Penerima Disposisi --</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('kepada_user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }} - {{ $user->unit_kerja }} ({{ ucfirst($user->role) }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('kepada_user_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Tanggal Disposisi -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Disposisi <span class="text-danger">*</span></label>
                                <input type="date" name="tanggal_disposisi" 
                                       class="form-control @error('tanggal_disposisi') is-invalid @enderror" 
                                       value="{{ old('tanggal_disposisi', date('Y-m-d')) }}" 
                                       required>
                                @error('tanggal_disposisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Batas Waktu -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Batas Waktu (Opsional)</label>
                                <input type="date" name="batas_waktu" 
                                       class="form-control @error('batas_waktu') is-invalid @enderror" 
                                       value="{{ old('batas_waktu') }}">
                                @error('batas_waktu')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Kosongkan jika tidak ada deadline</small>
                            </div>

                            <!-- Instruksi -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Instruksi Disposisi <span class="text-danger">*</span></label>
                                <textarea name="instruksi" rows="5" 
                                          class="form-control @error('instruksi') is-invalid @enderror" 
                                          placeholder="Masukkan instruksi disposisi..." 
                                          required>{{ old('instruksi') }}</textarea>
                                @error('instruksi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Contoh: Segera tindak lanjuti, Untuk diproses, Mohon koordinasi, dll.</small>
                            </div>

                            <!-- File Lampiran -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">File Lampiran Tambahan (Opsional)</label>
                                <input type="file" name="file_lampiran" 
                                       class="form-control @error('file_lampiran') is-invalid @enderror"
                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                @error('file_lampiran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</small>
                            </div>

                            <!-- Catatan -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Catatan (Opsional)</label>
                                <textarea name="catatan" rows="2" 
                                          class="form-control" 
                                          placeholder="Catatan tambahan...">{{ old('catatan') }}</textarea>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <i class="bi bi-exclamation-triangle"></i> 
                            <strong>Catatan:</strong> Penerima disposisi akan mendapat notifikasi otomatis.
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('disposisi.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Kirim Disposisi
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
// Show detail surat when selected
function showSuratDetail() {
    const select = document.getElementById('surat_masuk_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('surat-detail').style.display = 'block';
        document.getElementById('detail-nomor').textContent = selectedOption.dataset.nomor;
        document.getElementById('detail-tanggal').textContent = selectedOption.dataset.tanggal;
        document.getElementById('detail-pengirim').textContent = selectedOption.dataset.pengirim;
        document.getElementById('detail-perihal').textContent = selectedOption.dataset.perihal;
    } else {
        document.getElementById('surat-detail').style.display = 'none';
    }
}

// Auto show detail on page load if surat already selected
window.addEventListener('DOMContentLoaded', function() {
    showSuratDetail();
});
</script>
@endpush