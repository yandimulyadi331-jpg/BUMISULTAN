<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>History Unit {{ $unit->kode_unit }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #333;
        }
        .header h2 {
            margin: 5px 0;
            color: #333;
        }
        .header p {
            margin: 3px 0;
            color: #666;
        }
        .info-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 3px 5px;
        }
        .info-box td:first-child {
            width: 120px;
            font-weight: bold;
            color: #555;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background: #4CAF50;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
            border: 1px solid #333;
        }
        table.data-table td {
            padding: 6px 8px;
            border: 1px solid #ddd;
            font-size: 9pt;
            vertical-align: top;
        }
        table.data-table tr:nth-child(even) {
            background: #f9f9f9;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
        }
        .badge-warning {
            background: #ff9800;
            color: white;
        }
        .badge-success {
            background: #4CAF50;
            color: white;
        }
        .badge-danger {
            background: #f44336;
            color: white;
        }
        .detail-box {
            background: #fff8e1;
            padding: 5px 8px;
            margin: 3px 0;
            border-left: 3px solid #ff9800;
            font-size: 8pt;
        }
        .detail-box.success {
            background: #e8f5e9;
            border-left-color: #4CAF50;
        }
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN HISTORY UNIT INVENTARIS</h2>
        <p>{{ $unit->inventaris->nama_barang }}</p>
        <p><strong>Kode Unit: {{ $unit->kode_unit }}</strong></p>
        <p>Periode: {{ $periodText }}</p>
        <p>Dicetak: {{ now()->format('d F Y H:i') }}</p>
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td>Inventaris</td>
                <td>: {{ $unit->inventaris->nama_barang }}</td>
                <td>Kondisi</td>
                <td>: {{ ucfirst($unit->kondisi) }}</td>
            </tr>
            <tr>
                <td>Kode Unit</td>
                <td>: {{ $unit->kode_unit }}</td>
                <td>Status</td>
                <td>: {{ ucfirst($unit->status) }}</td>
            </tr>
            <tr>
                <td>Nomor Seri</td>
                <td>: {{ $unit->nomor_seri_unit ?? '-' }}</td>
                <td>Lokasi</td>
                <td>: {{ $unit->lokasi_saat_ini ?? '-' }}</td>
            </tr>
        </table>
    </div>

    @if($histories->count() > 0)
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 12%;">Tanggal</th>
                    <th style="width: 10%;">Jenis</th>
                    <th style="width: 48%;">Detail Aktivitas</th>
                    <th style="width: 15%;">Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($histories as $index => $history)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($history->jenis_aktivitas === 'pinjam')
                            <span class="badge badge-warning">Peminjaman</span>
                        @else
                            <span class="badge badge-success">Pengembalian</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $history->keterangan }}</strong>
                        
                        @if($history->jenis_aktivitas === 'pinjam' && $history->peminjaman)
                            <div class="detail-box">
                                <strong>Peminjam:</strong> {{ $history->peminjaman->nama_peminjam ?? '-' }}<br>
                                <strong>Tgl Pinjam:</strong> {{ $history->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($history->peminjaman->tanggal_pinjam)->format('d M Y') : '-' }}<br>
                                <strong>Rencana Kembali:</strong> {{ $history->peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($history->peminjaman->tanggal_kembali_rencana)->format('d M Y') : '-' }}<br>
                                <strong>Keperluan:</strong> {{ $history->peminjaman->keperluan ?? '-' }}
                            </div>
                        @endif
                        
                        @if($history->jenis_aktivitas === 'kembali' && $history->pengembalian)
                            <div class="detail-box success">
                                <strong>Tgl Kembali:</strong> {{ $history->pengembalian->tanggal_pengembalian ? \Carbon\Carbon::parse($history->pengembalian->tanggal_pengembalian)->format('d M Y') : '-' }}<br>
                                <strong>Kondisi:</strong> {{ ucfirst($history->pengembalian->kondisi_barang ?? 'baik') }}
                                @if(isset($history->pengembalian->denda) && $history->pengembalian->denda > 0)
                                    <br><strong>Denda:</strong> Rp {{ number_format($history->pengembalian->denda, 0, ',', '.') }}
                                @endif
                                @if(isset($history->pengembalian->catatan_pengembalian) && $history->pengembalian->catatan_pengembalian)
                                    <br><strong>Catatan:</strong> {{ $history->pengembalian->catatan_pengembalian }}
                                @endif
                            </div>
                        @endif
                    </td>
                    <td>{{ $history->user->name ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 15px; font-size: 9pt;">
            <strong>Total Aktivitas:</strong> {{ $histories->count() }} record
            <br>
            <strong>Peminjaman:</strong> {{ $histories->where('jenis_aktivitas', 'pinjam')->count() }} kali
            <br>
            <strong>Pengembalian:</strong> {{ $histories->where('jenis_aktivitas', 'kembali')->count() }} kali
        </div>
    @else
        <div class="no-data">
            Tidak ada data history untuk periode yang dipilih
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dicetak otomatis dari sistem PT. Bumi Sultan Group</p>
        <p>{{ config('app.url') }}</p>
    </div>
</body>
</html>
