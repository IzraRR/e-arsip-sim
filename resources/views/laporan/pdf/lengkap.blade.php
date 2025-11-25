<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Lengkap</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 5px 0;
            color: #4F46E5;
            font-size: 22px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .section-title {
            background: linear-gradient(to right, #4F46E5, #8B5CF6);
            color: white;
            padding: 10px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background-color: #F9FAFB;
            padding: 15px;
            border-radius: 5px;
        }
        .summary-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
            border-right: 2px solid #E5E7EB;
        }
        .summary-item:last-child {
            border-right: none;
        }
        .summary-item strong {
            display: block;
            font-size: 28px;
            margin-bottom: 5px;
        }
        .summary-item span {
            color: #666;
            font-size: 11px;
        }
        .color-blue { color: #4F46E5; }
        .color-green { color: #10B981; }
        .color-yellow { color: #F59E0B; }
        .color-purple { color: #8B5CF6; }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #374151;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .page-break {
            page-break-after: always;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 2px solid #E5E7EB;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN LENGKAP E-ARSIP</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <!-- Summary Statistics -->
    <div class="summary-grid">
        <div class="summary-item">
            <strong class="color-blue">{{ $suratMasuk->count() }}</strong>
            <span>Surat Masuk</span>
        </div>
        <div class="summary-item">
            <strong class="color-green">{{ $suratKeluar->count() }}</strong>
            <span>Surat Keluar</span>
        </div>
        <div class="summary-item">
            <strong class="color-yellow">{{ $disposisi->count() }}</strong>
            <span>Disposisi</span>
        </div>
        <div class="summary-item">
            <strong class="color-purple">{{ $arsip->count() }}</strong>
            <span>Arsip</span>
        </div>
    </div>

    <!-- Section Surat Masuk -->
    <div class="section">
        <h2 style="color: #4F46E5;">LAPORAN SURAT MASUK</h2>
        <table>
            <thead>
                <tr>
                    <th width="3%">NO</th>
                    <th width="10%">NO. AGENDA</th>
                    <th width="13%">NO. SURAT</th>
                    <th width="8%">TGL TERIMA</th>
                    <th width="17%">PENGIRIM</th>
                    <th width="30%">PERIHAL</th>
                    <th width="9%">PRIORITAS</th>
                    <th width="10%">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratMasuk as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_agenda }}</td>
                    <td>{{ $item->nomor_surat }}</td>
                    <td>{{ $item->tanggal_terima ? $item->tanggal_terima->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->pengirim }}</td>
                    <td>{{ Str::limit($item->perihal, 40) }}</td>
                    <td>{{ strtoupper($item->prioritas) }}</td>
                    <td>{{ strtoupper($item->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 15px;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Section Surat Keluar -->
    <div class="section">
        <h2 style="color: #4F46E5;">LAPORAN SURAT KELUAR</h2>
        <table>
            <thead>
                <tr>
                    <th width="3%">NO</th>
                    <th width="15%">NO. SURAT</th>
                    <th width="10%">TGL SURAT</th>
                    <th width="22%">TUJUAN</th>
                    <th width="35%">PERIHAL</th>
                    <th width="15%">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suratKeluar as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_surat }}</td>
                    <td>{{ $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->tujuan }}</td>
                    <td>{{ Str::limit($item->perihal, 50) }}</td>
                    <td>{{ strtoupper($item->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Section Disposisi -->
    <div class="section">
        <h2 style="color: #4F46E5;">LAPORAN DISPOSISI</h2>
        <table>
            <thead>
                <tr>
                    <th width="3%">NO</th>
                    <th width="12%">NO. SURAT</th>
                    <th width="10%">TGL DISPOSISI</th>
                    <th width="12%">DARI</th>
                    <th width="13%">KEPADA</th>
                    <th width="30%">INSTRUKSI</th>
                    <th width="20%">STATUS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($disposisi as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->suratMasuk->nomor_surat ?? '-' }}</td>
                    <td>{{ $item->tanggal_disposisi ? $item->tanggal_disposisi->format('d/m/Y') : '-' }}</td>
                    <td>{{ $item->dariUser->name ?? '-' }}</td>
                    <td>{{ $item->kepadaUser->name ?? '-' }}</td>
                    <td>{{ Str::limit($item->instruksi, 40) }}</td>
                    <td>{{ strtoupper($item->status) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Section Arsip -->
    <div class="section">
        <h2 style="color: #4F46E5;">LAPORAN ARSIP</h2>
        <table>
            <thead>
                <tr>
                    <th width="3%">NO</th>
                    <th width="15%">NO. DOKUMEN</th>
                    <th width="35%">JUDUL</th>
                    <th width="18%">KATEGORI</th>
                    <th width="17%">TGL DOKUMEN</th>
                    <th width="12%">KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @forelse($arsip as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->nomor_dokumen }}</td>
                    <td>{{ Str::limit($item->judul, 45) }}</td>
                    <td>{{ $item->kategori->nama_kategori ?? '-' }}</td>
                    <td>{{ $item->tanggal_dokumen ? $item->tanggal_dokumen->format('d/m/Y') : '-' }}</td>
                    <td>{{ Str::limit($item->keterangan ?? '-', 25) }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 15px;">Tidak ada data</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p><strong>Sistem E-Arsip</strong></p>
        <p>Generated by {{ auth()->user()->name }} | {{ auth()->user()->role }}</p>
        <p>{{ date('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>
