<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ArsipExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
{
    protected $data;
    protected $startDate;
    protected $endDate;

    public function __construct($data, $startDate, $endDate)
    {
        $this->data = $data;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return $this->data->map(function ($item, $index) {
            return [
                'no' => $index + 1,
                'kode_arsip' => $item->kode_arsip ?? '-',
                'nomor_dokumen' => $item->nomor_dokumen ?? '-',
                'judul' => $item->judul,
                'kategori' => $item->kategori->nama_kategori ?? '-',
                'tanggal_arsip' => $item->tanggal_arsip ? $item->tanggal_arsip->format('d/m/Y') : '-',
                'lokasi_fisik' => $item->lokasi_fisik ?? '-',
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'KODE ARSIP',
            'NO. DOKUMEN',
            'JUDUL',
            'KATEGORI',
            'TGL ARSIP',
            'LOKASI FISIK',
            'KETERANGAN',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '8B5CF6']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Arsip';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 35,
            'E' => 20,
            'F' => 15,
            'G' => 20,
            'H' => 25,
        ];
    }
}
