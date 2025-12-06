<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Khidmat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .summary-item {
            margin: 5px 0;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN BELANJA MASAK SANTRI</h2>
        <h3>SISTEM KHIDMAT</h3>
        <p>Periode: Semua Jadwal</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    @foreach($jadwal as $index => $item)
    <div class="jadwal-section">
        <h3>{{ $item->nama_kelompok }} - {{ $item->tanggal_jadwal->format('d F Y') }}</h3>
        
        <table>
            <tr>
                <th width="25%">Petugas Khidmat</th>
                <td>
                    @foreach($item->petugas as $petugas)
                        {{ $petugas->santri->nama_lengkap }}@if(!$loop->last), @endif
                    @endforeach
                </td>
            </tr>
            <tr>
                <th>Status Kebersihan</th>
                <td>{{ ucfirst($item->status_kebersihan) }}</td>
            </tr>
            <tr>
                <th>Saldo Awal</th>
                <td>Rp {{ number_format($item->saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Saldo Masuk</th>
                <td>Rp {{ number_format($item->saldo_masuk, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Belanja</th>
                <td>Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Saldo Akhir</th>
                <td><strong>Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        @if($item->belanja->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="30%">Nama Barang</th>
                    <th width="15%" class="text-center">Jumlah</th>
                    <th width="15%" class="text-right">Harga Satuan</th>
                    <th width="15%" class="text-right">Total</th>
                    <th width="20%">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($item->belanja as $i => $belanja)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $belanja->nama_barang }}</td>
                    <td class="text-center">{{ $belanja->jumlah }} {{ $belanja->satuan }}</td>
                    <td class="text-right">Rp {{ number_format($belanja->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($belanja->total_harga, 0, ',', '.') }}</td>
                    <td>{{ $belanja->keterangan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <table>
            <tr>
                <th width="70%">Saldo Awal</th>
                <td width="30%" class="text-right">Rp {{ number_format($item->saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <th>Total Belanja</th>
                <td class="text-right">Rp {{ number_format($item->total_belanja, 0, ',', '.') }}</td>
            </tr>
            <tr style="background-color: #e9ecef;">
                <th><strong>Saldo Akhir</strong></th>
                <td class="text-right"><strong>Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</strong></td>
            </tr>
        </table>

        @if($item->keterangan)
        <p><strong>Keterangan:</strong> {{ $item->keterangan }}</p>
        @endif
    </div>

    @if(!$loop->last)
    <div class="page-break"></div>
    @endif
    @endforeach

    <div class="summary">
        <h3>RINGKASAN KESELURUHAN</h3>
        <div class="summary-item">
            <strong>Saldo Awal (Jadwal Pertama):</strong> Rp {{ number_format($saldoAwalKeseluruhan, 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total Belanja Keseluruhan:</strong> Rp {{ number_format($totalSemuaBelanja, 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Saldo Akhir (Jadwal Terakhir):</strong> Rp {{ number_format($saldoAkhirKeseluruhan, 0, ',', '.') }}
        </div>
    </div>
</body>
</html>
