<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman & Pengembalian Inventaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 5px 0;
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .header h2 {
            margin: 8px 0;
            font-size: 14px;
            font-weight: bold;
        }
        .header p {
            margin: 2px 0;
            font-size: 9px;
            line-height: 1.4;
        }
        .tanggal-cetak {
            text-align: right;
            margin: 10px 0;
            font-size: 9px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th {
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
            font-size: 9px;
        }
        table td {
            border: 1px solid #ddd;
            padding: 4px;
            font-size: 9px;
        }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
        }
        .summary-item {
            margin: 5px 0;
            font-size: 10px;
        }
        .signature-section {
            margin-top: 30px;
            page-break-inside: avoid;
        }
        .signature-row {
            display: table;
            width: 100%;
            margin-top: 20px;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
        }
        .signature-box img {
            max-width: 150px;
            max-height: 60px;
            margin: 10px 0;
        }
        .signature-name {
            margin-top: 5px;
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 150px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        .badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
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
        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-primary {
            background-color: #cce5ff;
            color: #004085;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <p>Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telepon: (021) 22966797 | Provinsi: Jawa Barat</p>
    </div>

    <h2 style="text-align: center; margin: 15px 0; font-size: 14px;">LAPORAN PEMINJAMAN & PENGEMBALIAN INVENTARIS</h2>
    
    <div class="tanggal-cetak">
        Tanggal Cetak: {{ now()->format('d F Y, H:i') }} WIB
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 4%;">No</th>
                <th style="width: 11%;">Tanggal</th>
                <th style="width: 12%;">Aktivitas</th>
                <th style="width: 18%;">Barang</th>
                <th style="width: 30%;">Deskripsi</th>
                <th style="width: 12%;">Status</th>
                <th style="width: 13%;">User</th>
            </tr>
        </thead>
        <tbody>
            @forelse($histories as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <span class="badge badge-{{ 
                        $item->jenis_aktivitas == 'tambah' ? 'success' : 
                        ($item->jenis_aktivitas == 'peminjaman' ? 'warning' : 
                        ($item->jenis_aktivitas == 'pengembalian' ? 'info' : 
                        ($item->jenis_aktivitas == 'update' ? 'primary' : 'danger')))
                    }}">
                        {{ $item->getJenisAktivitasLabel() }}
                    </span>
                </td>
                <td>
                    <strong>{{ $item->inventaris->nama_barang }}</strong><br>
                    <small>{{ $item->inventaris->kode_inventaris }}</small>
                </td>
                <td>{{ $item->deskripsi }}</td>
                <td>
                    @if($item->status_sebelum && $item->status_sesudah)
                        {{ ucfirst($item->status_sebelum) }} → {{ ucfirst($item->status_sesudah) }}
                    @elseif($item->status_sesudah)
                        {{ ucfirst($item->status_sesudah) }}
                    @else
                        -
                    @endif
                </td>
                <td>{{ $item->user->name ?? 'System' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <strong>Total Aktivitas:</strong> {{ $histories->count() }} transaksi (peminjaman & pengembalian)
        </div>
        <div class="summary-item">
            <strong>Total Peminjaman:</strong> {{ $histories->where('jenis_aktivitas', 'peminjaman')->count() }} transaksi
        </div>
        <div class="summary-item">
            <strong>Total Pengembalian:</strong> {{ $histories->where('jenis_aktivitas', 'pengembalian')->count() }} transaksi
        </div>
        <div class="summary-item">
            <strong>Selisih:</strong> {{ $histories->where('jenis_aktivitas', 'peminjaman')->count() - $histories->where('jenis_aktivitas', 'pengembalian')->count() }} 
            ({{ $histories->where('jenis_aktivitas', 'peminjaman')->count() > $histories->where('jenis_aktivitas', 'pengembalian')->count() ? 'Belum Dikembalikan' : 'Sudah Seimbang' }})
        </div>
    </div>

    <!-- Section Tanda Tangan untuk Peminjaman & Pengembalian -->
    @php
        $peminjamanItems = $histories->where('jenis_aktivitas', 'peminjaman');
        $pengembalianItems = $histories->where('jenis_aktivitas', 'pengembalian');
    @endphp

    @if($peminjamanItems->count() > 0 || $pengembalianItems->count() > 0)
    <div class="signature-section">
        <h3 style="font-size: 11px; margin: 15px 0 10px 0;">Tanda Tangan Peminjaman & Pengembalian</h3>
        
        @foreach($histories as $item)
            @if($item->jenis_aktivitas == 'peminjaman' && $item->peminjaman)
                <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; background-color: #fffef0;">
                    <p style="margin: 0 0 5px 0; font-weight: bold; font-size: 10px;">
                        Peminjaman: {{ $item->inventaris->nama_barang }} ({{ $item->inventaris->kode_inventaris }})
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 9px;">
                        Tanggal: {{ $item->created_at->format('d/m/Y H:i') }} | Kode: {{ $item->peminjaman->kode_peminjaman }}
                    </p>
                    <div class="signature-row">
                        <div class="signature-box">
                            <p style="margin-bottom: 5px; font-size: 9px;">Peminjam</p>
                            @if($item->peminjaman->ttd_peminjam)
                                <img src="{{ public_path('storage/' . $item->peminjaman->ttd_peminjam) }}" alt="TTD Peminjam">
                            @else
                                <div style="height: 60px;"></div>
                            @endif
                            <div class="signature-name">{{ $item->peminjaman->nama_peminjam }}</div>
                        </div>
                        <div class="signature-box">
                            <p style="margin-bottom: 5px; font-size: 9px;">Petugas</p>
                            @if($item->peminjaman->ttd_petugas)
                                <img src="{{ public_path('storage/' . $item->peminjaman->ttd_petugas) }}" alt="TTD Petugas">
                            @else
                                <div style="height: 60px;"></div>
                            @endif
                            <div class="signature-name">{{ $item->user->name ?? 'Petugas' }}</div>
                        </div>
                    </div>
                </div>
            @endif

            @if($item->jenis_aktivitas == 'pengembalian' && $item->pengembalian)
                <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd; background-color: #f0fff4;">
                    <p style="margin: 0 0 5px 0; font-weight: bold; font-size: 10px;">
                        Pengembalian: {{ $item->inventaris->nama_barang }} ({{ $item->inventaris->kode_inventaris }})
                    </p>
                    <p style="margin: 0 0 10px 0; font-size: 9px;">
                        Tanggal: {{ $item->created_at->format('d/m/Y H:i') }} | Kode: {{ $item->pengembalian->kode_pengembalian }}
                    </p>
                    <div class="signature-row">
                        <div class="signature-box">
                            <p style="margin-bottom: 5px; font-size: 9px;">Pengembalian</p>
                            @if($item->pengembalian->ttd_peminjam)
                                <img src="{{ public_path('storage/' . $item->pengembalian->ttd_peminjam) }}" alt="TTD Peminjam">
                            @else
                                <div style="height: 60px;"></div>
                            @endif
                            <div class="signature-name">{{ $item->pengembalian->peminjaman->nama_peminjam ?? '-' }}</div>
                        </div>
                        <div class="signature-box">
                            <p style="margin-bottom: 5px; font-size: 9px;">Petugas Penerima</p>
                            @if($item->pengembalian->ttd_petugas)
                                <img src="{{ public_path('storage/' . $item->pengembalian->ttd_petugas) }}" alt="TTD Petugas">
                            @else
                                <div style="height: 60px;"></div>
                            @endif
                            <div class="signature-name">{{ $item->user->name ?? 'Petugas' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
    @endif

    <div class="footer">
        <p>Dicetak oleh: {{ auth()->user()->name ?? 'System' }}</p>
        <p style="margin-top: 5px;">
            Dokumen ini dicetak secara otomatis dari sistem
        </p>
    </div>
</body>
</html>
