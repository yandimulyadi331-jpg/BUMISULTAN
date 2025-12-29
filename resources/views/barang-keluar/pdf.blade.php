<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Barang Keluar</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        .header-info {
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #667eea;
            color: white;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 10px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .badge {
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-pending { background-color: #6c757d; color: white; }
        .badge-dikirim { background-color: #17a2b8; color: white; }
        .badge-proses { background-color: #ffc107; color: black; }
        .badge-selesai { background-color: #007bff; color: white; }
        .badge-diambil { background-color: #28a745; color: white; }
        .badge-batal { background-color: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
    </style>
</head>
<body>
    <h2>LAPORAN TRACKING BARANG KELUAR</h2>
    
    <div class="header-info">
        <p><strong>Tanggal Cetak:</strong> {{ date('d/m/Y H:i') }}</p>
        <p><strong>Total Data:</strong> {{ $barangKeluar->count() }} transaksi</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Pending:</strong> {{ $barangKeluar->where('status', 'pending')->count() }}
        </div>
        <div class="summary-item">
            <strong>Proses:</strong> {{ $barangKeluar->whereIn('status', ['dikirim', 'proses'])->count() }}
        </div>
        <div class="summary-item">
            <strong>Selesai:</strong> {{ $barangKeluar->where('status', 'selesai_vendor')->count() }}
        </div>
        <div class="summary-item">
            <strong>Diambil:</strong> {{ $barangKeluar->where('status', 'diambil')->count() }}
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Pemilik</th>
                <th>Vendor</th>
                <th>Status</th>
                <th>Estimasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangKeluar as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->kode_transaksi }}</td>
                <td>{{ $item->tanggal_keluar->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ ucfirst($item->jenis_barang) }}</strong><br>
                    {{ $item->nama_barang }} ({{ $item->jumlah }} {{ $item->satuan }})
                </td>
                <td>
                    {{ $item->pemilik_barang }}
                    @if($item->departemen)
                        <br><small>{{ $item->departemen }}</small>
                    @endif
                </td>
                <td>{{ $item->nama_vendor }}</td>
                <td>
                    <span class="badge badge-{{ $item->status }}">
                        {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                    </span>
                </td>
                <td>
                    @if($item->estimasi_kembali)
                        {{ $item->estimasi_kembali->format('d/m/Y') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px;">
                    Tidak ada data
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini dicetak secara otomatis oleh sistem BumisultanAPP</p>
        <p>{{ date('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
