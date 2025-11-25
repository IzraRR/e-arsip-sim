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

class SuratMasukExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnWidths
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
                'nomor_agenda' => $item->nomor_agenda,
                'nomor_surat' => $item->nomor_surat,
                'tanggal_surat' => $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-',
                'tanggal_terima' => $item->tanggal_terima ? $item->tanggal_terima->format('d/m/Y') : '-',
                'pengirim' => $item->pengirim,
                'perihal' => $item->perihal,
                'prioritas' => strtoupper($item->prioritas ?? '-'),
                'sifat' => strtoupper($item->sifat ?? '-'),
                'status' => strtoupper($item->status ?? '-'),
                'keterangan' => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NO',
            'NO. AGENDA',
            'NO. SURAT',
            'TGL SURAT',
            'TGL TERIMA',
            'PENGIRIM',
            'PERIHAL',
            'PRIORITAS',
            'SIFAT',
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
                    'startColor' => ['rgb' => '4F46E5']
                ],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
            ],
        ];
    }

    public function title(): string
    {
        return 'Laporan Surat Masuk';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 20,
            'D' => 12,
            'E' => 12,
            'F' => 25,
            'G' => 35,
            'H' => 12,
            'I' => 12,
            'J' => 12,
            'K' => 25,
        ];
    }
}
