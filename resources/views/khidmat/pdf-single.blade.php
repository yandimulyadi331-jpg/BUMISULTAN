<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Khidmat - {{ $jadwal->nama_kelompok }}</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN KEUANGAN BELANJA MASAK SANTRI</h2>
        <h3>{{ $jadwal->nama_kelompok }}</h3>
        <p>Tanggal: {{ $jadwal->tanggal_jadwal->format('d F Y') }}</p>
        <p>Dicetak: {{ date('d F Y H:i') }}</p>
    </div>

    <table>
        <tr>
            <th width="25%">Petugas Khidmat</th>
            <td>
                @foreach($jadwal->petugas as $petugas)
                    {{ $petugas->santri->nama_lengkap }}@if(!$loop->last), @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>Status Kebersihan</th>
            <td>{{ ucfirst($jadwal->status_kebersihan) }}</td>
        </tr>
        <tr>
            <th>Saldo Awal</th>
            <td>Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Saldo Masuk</th>
            <td>Rp {{ number_format($jadwal->saldo_masuk, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Belanja</th>
            <td>Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Saldo Akhir</th>
            <td><strong>Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    @if($jadwal->belanja->count() > 0)
    <h3>Rincian Belanja</h3>
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
            @foreach($jadwal->belanja as $index => $belanja)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
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

    <h3>Ringkasan Keuangan</h3>
    <table>
        <tr>
            <th width="70%">Saldo Awal</th>
            <td width="30%" class="text-right">Rp {{ number_format($jadwal->saldo_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <th>Total Belanja</th>
            <td class="text-right">Rp {{ number_format($jadwal->total_belanja, 0, ',', '.') }}</td>
        </tr>
        <tr style="background-color: #e9ecef;">
            <th><strong>Saldo Akhir</strong></th>
            <td class="text-right"><strong>Rp {{ number_format($jadwal->saldo_akhir, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    @if($jadwal->keterangan)
    <p><strong>Keterangan:</strong> {{ $jadwal->keterangan }}</p>
    @endif
</body>
</html>
