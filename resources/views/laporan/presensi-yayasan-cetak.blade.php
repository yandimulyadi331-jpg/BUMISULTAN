<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi Yayasan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
        }
        .info {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
        }
        .info-item {
            flex: 1;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table th {
            background-color: #2F5D62;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #000;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ccc;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        table tr:hover {
            background-color: #f0f0f0;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            padding-top: 20px;
            border-top: 1px solid #000;
        }
        .summary {
            margin-top: 15px;
            display: flex;
            justify-content: space-around;
        }
        .summary-item {
            text-align: center;
        }
        .summary-item h4 {
            font-size: 11px;
            margin: 5px 0;
            color: #666;
        }
        .summary-item p {
            font-size: 16px;
            font-weight: bold;
            color: #2F5D62;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PRESENSI YAYASAN</h2>
        <p>{{ config('app.name') }}</p>
    </div>

    <div class="info">
        <div class="info-item">
            <p><strong>Periode:</strong> {{ $from }} s/d {{ $to }}</p>
        </div>
        <div class="info-item">
            <p><strong>Cabang:</strong> {{ $cabang }}</p>
        </div>
        <div class="info-item">
            <p><strong>Tanggal Cetak:</strong> {{ DateToIndo(date('Y-m-d H:i:s')) }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">Kode Yayasan</th>
                <th style="width: 20%;">Nama</th>
                <th style="width: 13%;">Tanggal</th>
                <th style="width: 12%;">Jam Masuk</th>
                <th style="width: 12%;">Jam Pulang</th>
                <th style="width: 16%;">Jam Kerja</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $key => $item)
                <tr>
                    <td style="text-align: center;">{{ $key + 1 }}</td>
                    <td>{{ $item->kode_yayasan }}</td>
                    <td>{{ $item->yayasan->nama ?? '-' }}</td>
                    <td>{{ DateToIndo($item->tanggal) }}</td>
                    <td>{{ $item->jam_in ?? '-' }}</td>
                    <td>{{ $item->jam_out ?? '-' }}</td>
                    <td>{{ $item->jamKerja->nama_jam_kerja ?? '-' }}</td>
                    <td>
                        @switch($item->status)
                            @case('H')
                                Hadir
                                @break
                            @case('I')
                                Izin
                                @break
                            @case('S')
                                Sakit
                                @break
                            @case('A')
                                Alfa
                                @break
                            @case('C')
                                Cuti
                                @break
                            @default
                                {{ $item->status }}
                        @endswitch
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 20px;">Tidak ada data presensi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <h4>Total Rekam</h4>
            <p>{{ $data->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>Hadir</h4>
            <p>{{ $data->where('status', 'H')->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>Izin</h4>
            <p>{{ $data->where('status', 'I')->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>Sakit</h4>
            <p>{{ $data->where('status', 'S')->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>Alfa</h4>
            <p>{{ $data->where('status', 'A')->count() }}</p>
        </div>
        <div class="summary-item">
            <h4>Cuti</h4>
            <p>{{ $data->where('status', 'C')->count() }}</p>
        </div>
    </div>

    <div class="footer">
        <p>Dicetak oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }}</strong></p>
        <p>Tanggal Cetak: <strong>{{ DateToIndo(date('Y-m-d H:i:s')) }}</strong></p>
    </div>
</body>
</html>
