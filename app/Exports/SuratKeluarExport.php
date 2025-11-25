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

class SuratKeluarExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
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
                'nomor_surat' => $item->nomor_surat,
                'tanggal_surat' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                'tujuan' => $item->tujuan,
                'perihal' => $item->perihal,
                'lampiran' => $item->lampiran ?? '-',
                'status' => strtoupper($item->status ?? '-'),
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. SURAT',
            'TGL SURAT',
            'TUJUAN',
            'PERIHAL',
            'LAMPIRAN',
            'STATUS',
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
                    'startColor' => ['rgb' => '10B981']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Surat Keluar';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 12,
            'D' => 25,
            'E' => 35,
            'F' => 15,
            'G' => 12,
            'H' => 25,
        ];
    }
}
