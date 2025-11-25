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

class DisposisiExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
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
                'nomor_surat' => $item->suratMasuk->nomor_surat ?? '-',
                'tanggal_disposisi' => $item->created_at ? $item->created_at->format('d/m/Y H:i') : '-',
                'penerima' => $item->penerima->name ?? '-',
                'isi_disposisi' => $item->isi_disposisi ?? '-',
                'catatan' => $item->catatan ?? '-',
                'status' => strtoupper($item->status ?? '-'),
                'batas_waktu' => $item->batas_waktu ? $item->batas_waktu->format('d/m/Y') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. SURAT',
            'TGL DISPOSISI',
            'PENERIMA',
            'ISI DISPOSISI',
            'CATATAN',
            'STATUS',
            'BATAS WAKTU',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'F59E0B']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Disposisi';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 18,
            'D' => 20,
            'E' => 35,
            'F' => 25,
            'G' => 12,
            'H' => 15,
        ];
    }
}
