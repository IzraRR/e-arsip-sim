<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Surat Masuk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            font-size: 20px;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .stats {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            background-color: #F3F4F6;
            padding: 10px;
            border-radius: 5px;
        }
        .stat-item {
            display: table-cell;
            text-align: center;
            padding: 10px;
        }
        .stat-item strong {
            display: block;
            font-size: 24px;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        .stat-item span {
            color: #666;
            font-size: 11px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4F46E5;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
        }
        td {
            padding: 6px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 10px;
        }
        tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-tinggi { background-color: #FEE2E2; color: #991B1B; }
        .badge-sedang { background-color: #FEF3C7; color: #92400E; }
        .badge-rendah { background-color: #DBEAFE; color: #1E40AF; }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📨 LAPORAN SURAT MASUK</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        <p>Dicetak pada: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <strong>{{ $stats['total'] }}</strong>
            <span>Total Surat</span>
        </div>
        <div class="stat-item">
            <strong>{{ $stats['prioritas_tinggi'] }}</strong>
            <span>Prioritas Tinggi</span>
        </div>
        <div class="stat-item">
            <strong>{{ $stats['prioritas_sedang'] }}</strong>
            <span>Prioritas Sedang</span>
        </div>
        <div class="stat-item">
            <strong>{{ $stats['prioritas_rendah'] }}</strong>
            <span>Prioritas Rendah</span>
        </div>
    </div>

    <h3 style="margin-top: 20px; margin-bottom: 10px; color: #4F46E5; font-size: 14px; font-weight: bold;">
        TABEL DATA SURAT MASUK
    </h3>

    <table>
        <thead>
            <tr>
                <th width="3%">NO</th>
                <th width="10%">NO. AGENDA</th>
                <th width="12%">NO. SURAT</th>
                <th width="8%">TGL SURAT</th>
                <th width="8%">TGL TERIMA</th>
                <th width="15%">PENGIRIM</th>
                <th width="24%">PERIHAL</th>
                <th width="8%">PRIORITAS</th>
                <th width="7%">SIFAT</th>
                <th width="5%">STATUS</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->nomor_agenda }}</td>
                <td>{{ $item->nomor_surat }}</td>
                <td>{{ $item->tanggal_surat ? $item->tanggal_surat->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->tanggal_terima ? $item->tanggal_terima->format('d/m/Y') : '-' }}</td>
                <td>{{ $item->pengirim }}</td>
                <td>{{ $item->perihal }}</td>
                <td>
                    <span class="badge badge-{{ $item->prioritas }}">
                        {{ strtoupper($item->prioritas) }}
                    </span>
                </td>
                <td style="text-align: center;">{{ strtoupper($item->sifat) }}</td>
                <td style="text-align: center;">{{ strtoupper($item->status) }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada data untuk periode yang dipilih
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Sistem E-Arsip | Generated by {{ auth()->user()->name }}</p>
    </div>
</body>
</html>
