<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Disposisi;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Halaman utama laporan dengan filter
     */
    public function index()
    {
        if (auth()->user()->role == 'staff') {
        abort(403, 'ANDA TIDAK MEMILIKI AKSES KE HALAMAN INI.');
    }
        return view('laporan.index');
    }

    /**
     * Generate statistik untuk dashboard laporan
     */
    public function statistik(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $stats = [
            // Statistik Surat Masuk
            'surat_masuk' => [
                'total' => SuratMasuk::whereBetween('tanggal_terima', [$startDate, $endDate])->count(),
                'prioritas_tinggi' => SuratMasuk::whereBetween('tanggal_terima', [$startDate, $endDate])
                    ->where('prioritas', 'tinggi')->count(),
                'prioritas_sedang' => SuratMasuk::whereBetween('tanggal_terima', [$startDate, $endDate])
                    ->where('prioritas', 'sedang')->count(),
                'prioritas_rendah' => SuratMasuk::whereBetween('tanggal_terima', [$startDate, $endDate])
                    ->where('prioritas', 'rendah')->count(),
                'by_month' => SuratMasuk::selectRaw('MONTH(tanggal_terima) as bulan, COUNT(*) as total')
                    ->whereBetween('tanggal_terima', [$startDate, $endDate])
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get(),
            ],

            // Statistik Surat Keluar
            'surat_keluar' => [
                'total' => SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])->count(),
                'draft' => SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])
                    ->where('status', 'draft')->count(),
                'approved' => SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])
                    ->where('status', 'approved')->count(),
                'sent' => SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])
                    ->where('status', 'sent')->count(),
                'by_month' => SuratKeluar::selectRaw('MONTH(tanggal_surat) as bulan, COUNT(*) as total')
                    ->whereBetween('tanggal_surat', [$startDate, $endDate])
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get(),
            ],

            // Statistik Disposisi
            'disposisi' => [
                'total' => Disposisi::whereBetween('created_at', [$startDate, $endDate])->count(),
                'pending' => Disposisi::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'pending')->count(),
                'proses' => Disposisi::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'proses')->count(),
                'selesai' => Disposisi::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'selesai')->count(),
            ],

            // Statistik Arsip
            'arsip' => [
                'total' => Arsip::whereBetween('tanggal_dokumen', [$startDate, $endDate])->count(),
                'by_kategori' => Arsip::select('kategori_id', DB::raw('COUNT(*) as total'))
                    ->with('kategori')
                    ->whereBetween('tanggal_dokumen', [$startDate, $endDate])
                    ->groupBy('kategori_id')
                    ->get(),
            ],

            // Info periode
            'periode' => [
                'start' => $startDate,
                'end' => $endDate,
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Export Laporan Surat Masuk ke PDF
     */
    public function exportSuratMasukPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = SuratMasuk::with('user')
            ->whereBetween('tanggal_terima', [$startDate, $endDate])
            ->orderBy('tanggal_terima', 'desc')
            ->get();

        $stats = [
            'total' => $data->count(),
            'prioritas_tinggi' => $data->where('prioritas', 'tinggi')->count(),
            'prioritas_sedang' => $data->where('prioritas', 'sedang')->count(),
            'prioritas_rendah' => $data->where('prioritas', 'rendah')->count(),
        ];

        $pdf = Pdf::loadView('laporan.pdf.surat-masuk', [
            'data' => $data,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-surat-masuk-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Surat Keluar ke PDF
     */
    public function exportSuratKeluarPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = SuratKeluar::with('user')
            ->whereBetween('tanggal_surat', [$startDate, $endDate])
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        $stats = [
            'total' => $data->count(),
            'draft' => $data->where('status', 'draft')->count(),
            'approved' => $data->where('status', 'approved')->count(),
            'sent' => $data->where('status', 'sent')->count(),
        ];

        $pdf = Pdf::loadView('laporan.pdf.surat-keluar', [
            'data' => $data,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-surat-keluar-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Disposisi ke PDF
     */
    public function exportDisposisiPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = Disposisi::with(['suratMasuk', 'penerima'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        $stats = [
            'total' => $data->count(),
            'pending' => $data->where('status', 'pending')->count(),
            'proses' => $data->where('status', 'proses')->count(),
            'selesai' => $data->where('status', 'selesai')->count(),
        ];

        $pdf = Pdf::loadView('laporan.pdf.disposisi', [
            'data' => $data,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-disposisi-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Arsip ke PDF
     */
    public function exportArsipPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = Arsip::with(['kategori', 'user'])
            ->whereBetween('tanggal_dokumen', [$startDate, $endDate])
            ->orderBy('tanggal_dokumen', 'desc')
            ->get();

        $stats = [
            'total' => $data->count(),
            'by_kategori' => $data->groupBy('kategori.nama_kategori')
                ->map(fn($items) => $items->count())
                ->toArray(),
        ];

        $pdf = Pdf::loadView('laporan.pdf.arsip', [
            'data' => $data,
            'stats' => $stats,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-arsip-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Export Laporan Surat Masuk ke Excel
     */
    public function exportSuratMasukExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = SuratMasuk::with('user')
            ->whereBetween('tanggal_terima', [$startDate, $endDate])
            ->orderBy('tanggal_terima', 'desc')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SuratMasukExport($data, $startDate, $endDate),
            'laporan-surat-masuk-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Laporan Surat Keluar ke Excel
     */
    public function exportSuratKeluarExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = SuratKeluar::with('user')
            ->whereBetween('tanggal_surat', [$startDate, $endDate])
            ->orderBy('tanggal_surat', 'desc')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\SuratKeluarExport($data, $startDate, $endDate),
            'laporan-surat-keluar-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Laporan Disposisi ke Excel
     */
    public function exportDisposisiExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = Disposisi::with(['suratMasuk', 'kepadaUser', 'dariUser'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\DisposisiExport($data, $startDate, $endDate),
            'laporan-disposisi-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Export Laporan Arsip ke Excel
     */
    public function exportArsipExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $data = Arsip::with(['kategori', 'user'])
            ->whereBetween('tanggal_dokumen', [$startDate, $endDate])
            ->orderBy('tanggal_dokumen', 'desc')
            ->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\ArsipExport($data, $startDate, $endDate),
            'laporan-arsip-' . date('Y-m-d') . '.xlsx'
        );
    }

    /**
     * Laporan Lengkap - Kombinasi semua data
     */
    public function exportLaporanLengkapPDF(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth());
        $endDate = $request->input('end_date', now()->endOfMonth());

        $suratMasuk = SuratMasuk::whereBetween('tanggal_terima', [$startDate, $endDate])->get();
        $suratKeluar = SuratKeluar::whereBetween('tanggal_surat', [$startDate, $endDate])->get();
        $disposisi = Disposisi::with(['suratMasuk', 'kepadaUser', 'dariUser'])
            ->whereBetween('created_at', [$startDate, $endDate])->get();
        $arsip = Arsip::whereBetween('tanggal_dokumen', [$startDate, $endDate])->get();

        $pdf = Pdf::loadView('laporan.pdf.lengkap', [
            'suratMasuk' => $suratMasuk,
            'suratKeluar' => $suratKeluar,
            'disposisi' => $disposisi,
            'arsip' => $arsip,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laporan-lengkap-' . date('Y-m-d') . '.pdf');
    }
}
