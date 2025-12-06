<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 10px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 4px;
            text-align: left;
            font-size: 8px;
        }
        table td {
            border: 1px solid #ddd;
            padding: 3px;
            font-size: 8px;
        }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-item {
            margin: 5px 0;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN DATA INVENTARIS</h2>
        <p>PT. BUMI SULTAN GROUP</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 8%;">Kode</th>
                <th style="width: 12%;">Nama Barang</th>
                <th style="width: 8%;">Kategori</th>
                <th style="width: 8%;">Merk</th>
                <th style="width: 8%;">Tipe/Model</th>
                <th style="width: 5%;">Jml</th>
                <th style="width: 7%;">Kondisi</th>
                <th style="width: 7%;">Status</th>
                <th style="width: 9%;">Lokasi</th>
                <th style="width: 8%;">Tgl Perolehan</th>
                <th style="width: 10%;">Harga</th>
                <th style="width: 7%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventaris as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->kode_inventaris }}</td>
                <td>{{ $item->nama_barang }}</td>
                <td>{{ ucfirst($item->kategori) }}</td>
                <td>{{ $item->merk ?? '-' }}</td>
                <td>{{ $item->tipe_model ?? '-' }}</td>
                <td style="text-align: center;">{{ $item->jumlah }} {{ $item->satuan }}</td>
                <td>{{ ucfirst($item->kondisi) }}</td>
                <td>
                    <span class="badge badge-{{ $item->status == 'tersedia' ? 'success' : ($item->status == 'dipinjam' ? 'warning' : 'danger') }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>{{ $item->lokasi_penyimpanan ?? ($item->cabang->nama_cabang ?? '-') }}</td>
                <td>{{ $item->tanggal_perolehan ? \Carbon\Carbon::parse($item->tanggal_perolehan)->format('d/m/Y') : '-' }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->harga_perolehan, 0, ',', '.') }}</td>
                <td>{{ $item->keterangan ? Str::limit($item->keterangan, 30) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Inventaris:</strong> {{ $inventaris->count() }} item
        </div>
        <div class="summary-item">
            <strong>Total Nilai Perolehan:</strong> Rp {{ number_format($totalNilai, 0, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Status Tersedia:</strong> {{ $inventaris->where('status', 'tersedia')->count() }} item
        </div>
        <div class="summary-item">
            <strong>Status Dipinjam:</strong> {{ $inventaris->where('status', 'dipinjam')->count() }} item
        </div>
        <div class="summary-item">
            <strong>Status Rusak:</strong> {{ $inventaris->where('status', 'rusak')->count() }} item
        </div>
    </div>

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'System' }}</p>
        <p style="margin-top: 5px; font-size: 9px; color: #666;">
            Dokumen ini dicetak secara otomatis dari sistem
        </p>
    </div>
</body>
</html>
