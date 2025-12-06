<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Peralatan BS - Bumi Sultan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
        }
        
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .kop-surat h1 {
            font-size: 18px;
            font-weight: bold;
            margin: 5px 0;
            color: #000;
        }
        
        .kop-surat h2 {
            font-size: 24px;
            font-weight: bold;
            margin: 5px 0;
            color: #F97316;
        }
        
        .kop-surat p {
            font-size: 9px;
            margin: 2px 0;
        }
        
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0;
            text-transform: uppercase;
        }
        
        .info {
            margin-bottom: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #F97316;
            color: white;
            padding: 8px 4px;
            text-align: left;
            font-size: 9px;
            border: 1px solid #000;
        }
        
        table td {
            padding: 6px 4px;
            border: 1px solid #000;
            font-size: 9px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #FFA500;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature-container {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin: 60px auto 0;
        }
    </style>
</head>
<body>
    <!-- Kop Surat -->
    <div class="kop-surat">
        <h1>PT BUMI SULTAN</h1>
        <h2>BUMI SULTAN</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telepon: (021) 22966797 | Email: info@bumisultan.com | Website: www.bumisultan.com</p>
    </div>
    
    <!-- Judul Laporan -->
    <div class="title">
        DATA PERALATAN BS (BUMI SULTAN)
    </div>
    
    <!-- Info Laporan -->
    <div class="info">
        <table style="border: none;">
            <tr>
                <td style="border: none; width: 150px;">Tanggal Cetak</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none;">{{ date('d F Y, H:i:s') }}</td>
            </tr>
            <tr>
                <td style="border: none;">Total Data</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ $peralatan->count() }} item peralatan</td>
            </tr>
            <tr>
                <td style="border: none;">Total Stok Tersedia</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ $peralatan->sum('stok_tersedia') }} unit</td>
            </tr>
            <tr>
                <td style="border: none;">Total Stok Dipinjam</td>
                <td style="border: none;">:</td>
                <td style="border: none;">{{ $peralatan->sum('stok_dipinjam') }} unit</td>
            </tr>
        </table>
    </div>
    
    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%">Kode</th>
                <th width="18%">Nama Peralatan</th>
                <th width="10%">Kategori</th>
                <th width="7%" class="text-center">Stok Tersedia</th>
                <th width="7%" class="text-center">Dipinjam</th>
                <th width="7%" class="text-center">Rusak</th>
                <th width="10%">Lokasi</th>
                <th width="8%">Kondisi</th>
                <th width="10%" class="text-right">Harga Satuan</th>
                <th width="12%">Supplier</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peralatan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->kode_peralatan }}</td>
                <td><strong>{{ $item->nama_peralatan }}</strong></td>
                <td>{{ $item->kategori }}</td>
                <td class="text-center">{{ $item->stok_tersedia }} {{ $item->satuan }}</td>
                <td class="text-center">{{ $item->stok_dipinjam }} {{ $item->satuan }}</td>
                <td class="text-center">{{ $item->stok_rusak }} {{ $item->satuan }}</td>
                <td>{{ $item->lokasi_penyimpanan ?? '-' }}</td>
                <td class="text-center">
                    @if($item->kondisi == 'baik')
                        <span class="badge badge-success">Baik</span>
                    @elseif($item->kondisi == 'rusak ringan')
                        <span class="badge badge-warning">Rusak Ringan</span>
                    @else
                        <span class="badge badge-danger">Rusak Berat</span>
                    @endif
                </td>
                <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                <td>{{ $item->supplier ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="11" class="text-center">Tidak ada data peralatan</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Summary -->
    <div style="margin-top: 20px; padding: 10px; background-color: #f0f0f0; border: 1px solid #ccc;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 25%;"><strong>Total Item:</strong> {{ $peralatan->count() }}</td>
                <td style="border: none; width: 25%;"><strong>Stok Tersedia:</strong> {{ $peralatan->sum('stok_tersedia') }}</td>
                <td style="border: none; width: 25%;"><strong>Stok Dipinjam:</strong> {{ $peralatan->sum('stok_dipinjam') }}</td>
                <td style="border: none; width: 25%;"><strong>Stok Rusak:</strong> {{ $peralatan->sum('stok_rusak') }}</td>
            </tr>
        </table>
    </div>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
    
    <!-- Tanda Tangan -->
    <div style="margin-top: 40px; text-align: center;">
        <p>Divisi Pemeliharaan dan Logistik,</p>
        <div style="border-top: 1px solid #000; width: 200px; margin: 60px auto 0;"></div>
        <p style="margin-top: 5px;">(............................)</p>
    </div>
</body>
</html>
