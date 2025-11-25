@extends('layouts.app-custom')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')

@section('content')
<div class="container-fluid">
    
    <!-- Filter Section -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <h5 class="card-title mb-3">
                <i class="bi bi-funnel"></i> Filter Periode Laporan
            </h5>
            <form id="filterForm" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" 
                           value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Akhir</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" 
                           value="{{ date('Y-m-t') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary d-block w-100">
                        <i class="bi bi-search"></i> Tampilkan Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-5" style="display: none;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2">Memuat data laporan...</p>
    </div>

    <!-- Statistik Dashboard -->
    <div id="statistikSection">
        <div class="row g-4 mb-4">
            <!-- Surat Masuk Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-white-50 mb-2">Surat Masuk</h6>
                                <h2 class="mb-0" id="stat-surat-masuk-total">0</h2>
                            </div>
                            <i class="bi bi-envelope icon"></i>
                        </div>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>🔴 Prioritas Tinggi:</span>
                                <strong id="stat-surat-masuk-tinggi">0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>🟡 Prioritas Sedang:</span>
                                <strong id="stat-surat-masuk-sedang">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>🟢 Prioritas Rendah:</span>
                                <strong id="stat-surat-masuk-rendah">0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Surat Keluar Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-white-50 mb-2">Surat Keluar</h6>
                                <h2 class="mb-0" id="stat-surat-keluar-total">0</h2>
                            </div>
                            <i class="bi bi-send icon"></i>
                        </div>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>📝 Draft:</span>
                                <strong id="stat-surat-keluar-draft">0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>✅ Approved:</span>
                                <strong id="stat-surat-keluar-approved">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>📤 Terkirim:</span>
                                <strong id="stat-surat-keluar-sent">0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Disposisi Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-warning text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-white-50 mb-2">Disposisi</h6>
                                <h2 class="mb-0" id="stat-disposisi-total">0</h2>
                            </div>
                            <i class="bi bi-diagram-3 icon"></i>
                        </div>
                        <div class="small">
                            <div class="d-flex justify-content-between mb-1">
                                <span>⏳ Pending:</span>
                                <strong id="stat-disposisi-pending">0</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>🔄 Proses:</span>
                                <strong id="stat-disposisi-proses">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>✔️ Selesai:</span>
                                <strong id="stat-disposisi-selesai">0</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Arsip Card -->
            <div class="col-xl-3 col-md-6">
                <div class="card stat-card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="text-white-50 mb-2">Total Arsip</h6>
                                <h2 class="mb-0" id="stat-arsip-total">0</h2>
                            </div>
                            <i class="bi bi-folder icon"></i>
                        </div>
                        <div class="small" id="stat-arsip-kategori">
                            <div class="text-center py-2">
                                <em>Memuat kategori...</em>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Export Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-download"></i> Export Laporan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Surat Masuk -->
                            <div class="col-lg-3 col-md-6">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-3"><i class="bi bi-envelope text-primary"></i> Surat Masuk</h6>
                                    <div class="d-grid gap-2">
                                        <button onclick="exportPDF('surat-masuk')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-pdf"></i> Export PDF
                                        </button>
                                        <button onclick="exportExcel('surat-masuk')" class="btn btn-success btn-sm">
                                            <i class="bi bi-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Surat Keluar -->
                            <div class="col-lg-3 col-md-6">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-3"><i class="bi bi-send text-success"></i> Surat Keluar</h6>
                                    <div class="d-grid gap-2">
                                        <button onclick="exportPDF('surat-keluar')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-pdf"></i> Export PDF
                                        </button>
                                        <button onclick="exportExcel('surat-keluar')" class="btn btn-success btn-sm">
                                            <i class="bi bi-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Disposisi -->
                            <div class="col-lg-3 col-md-6">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-3"><i class="bi bi-diagram-3 text-warning"></i> Disposisi</h6>
                                    <div class="d-grid gap-2">
                                        <button onclick="exportPDF('disposisi')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-pdf"></i> Export PDF
                                        </button>
                                        <button onclick="exportExcel('disposisi')" class="btn btn-success btn-sm">
                                            <i class="bi bi-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Arsip -->
                            <div class="col-lg-3 col-md-6">
                                <div class="p-3 border rounded h-100">
                                    <h6 class="mb-3"><i class="bi bi-folder text-info"></i> Arsip</h6>
                                    <div class="d-grid gap-2">
                                        <button onclick="exportPDF('arsip')" class="btn btn-danger btn-sm">
                                            <i class="bi bi-file-pdf"></i> Export PDF
                                        </button>
                                        <button onclick="exportExcel('arsip')" class="btn btn-success btn-sm">
                                            <i class="bi bi-file-excel"></i> Export Excel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Laporan Lengkap -->
                        <div class="mt-3 p-3 bg-light border rounded">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <h6 class="mb-2">
                                        <i class="bi bi-file-earmark-text text-primary"></i> 
                                        Laporan Lengkap (Semua Data)
                                    </h6>
                                    <small class="text-muted">
                                        Gabungan seluruh data Surat Masuk, Surat Keluar, Disposisi, dan Arsip dalam satu file PDF
                                    </small>
                                </div>
                                <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                    <button onclick="exportPDF('lengkap')" class="btn btn-primary">
                                        <i class="bi bi-file-pdf"></i> Export Laporan Lengkap
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let currentStartDate = '{{ date('Y-m-01') }}';
    let currentEndDate = '{{ date('Y-m-t') }}';

    // Load statistik saat halaman dimuat
    document.addEventListener('DOMContentLoaded', function() {
        loadStatistik();
    });

    // Handle form submit
    document.getElementById('filterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        currentStartDate = document.getElementById('start_date').value;
        currentEndDate = document.getElementById('end_date').value;
        loadStatistik();
    });

    // Load statistik data
    function loadStatistik() {
        document.getElementById('loadingState').style.display = 'block';
        document.getElementById('statistikSection').style.display = 'none';

        fetch(`{{ route('laporan.statistik') }}?start_date=${currentStartDate}&end_date=${currentEndDate}`)
            .then(response => response.json())
            .then(data => {
                // Update Surat Masuk
                document.getElementById('stat-surat-masuk-total').textContent = data.surat_masuk.total;
                document.getElementById('stat-surat-masuk-tinggi').textContent = data.surat_masuk.prioritas_tinggi;
                document.getElementById('stat-surat-masuk-sedang').textContent = data.surat_masuk.prioritas_sedang;
                document.getElementById('stat-surat-masuk-rendah').textContent = data.surat_masuk.prioritas_rendah;

                // Update Surat Keluar
                document.getElementById('stat-surat-keluar-total').textContent = data.surat_keluar.total;
                document.getElementById('stat-surat-keluar-draft').textContent = data.surat_keluar.draft;
                document.getElementById('stat-surat-keluar-approved').textContent = data.surat_keluar.approved;
                document.getElementById('stat-surat-keluar-sent').textContent = data.surat_keluar.sent;

                // Update Disposisi
                document.getElementById('stat-disposisi-total').textContent = data.disposisi.total;
                document.getElementById('stat-disposisi-pending').textContent = data.disposisi.pending;
                document.getElementById('stat-disposisi-proses').textContent = data.disposisi.proses;
                document.getElementById('stat-disposisi-selesai').textContent = data.disposisi.selesai;

                // Update Arsip
                document.getElementById('stat-arsip-total').textContent = data.arsip.total;
                
                // Update arsip by kategori
                let arsipKategoriHtml = '';
                if (data.arsip.by_kategori && data.arsip.by_kategori.length > 0) {
                    data.arsip.by_kategori.forEach(item => {
                        arsipKategoriHtml += `
                            <div class="d-flex justify-content-between mb-1">
                                <span>📁 ${item.kategori?.nama_kategori || 'Tanpa Kategori'}:</span>
                                <strong>${item.total}</strong>
                            </div>
                        `;
                    });
                } else {
                    arsipKategoriHtml = '<div class="text-center"><em>Tidak ada data</em></div>';
                }
                document.getElementById('stat-arsip-kategori').innerHTML = arsipKategoriHtml;

                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('statistikSection').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat statistik. Silakan coba lagi.');
                document.getElementById('loadingState').style.display = 'none';
                document.getElementById('statistikSection').style.display = 'block';
            });
    }

    // Export PDF
    function exportPDF(type) {
        const routes = {
            'surat-masuk': '{{ route("laporan.export-surat-masuk-pdf") }}',
            'surat-keluar': '{{ route("laporan.export-surat-keluar-pdf") }}',
            'disposisi': '{{ route("laporan.export-disposisi-pdf") }}',
            'arsip': '{{ route("laporan.export-arsip-pdf") }}',
            'lengkap': '{{ route("laporan.export-lengkap-pdf") }}'
        };

        const url = `${routes[type]}?start_date=${currentStartDate}&end_date=${currentEndDate}`;
        window.open(url, '_blank');
    }

    // Export Excel
    function exportExcel(type) {
        const routes = {
            'surat-masuk': '{{ route("laporan.export-surat-masuk-excel") }}',
            'surat-keluar': '{{ route("laporan.export-surat-keluar-excel") }}',
            'disposisi': '{{ route("laporan.export-disposisi-excel") }}',
            'arsip': '{{ route("laporan.export-arsip-excel") }}'
        };

        const url = `${routes[type]}?start_date=${currentStartDate}&end_date=${currentEndDate}`;
        window.open(url, '_blank');
    }
</script>
@endpush
@endsection
