<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Peminjaman Peralatan BS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
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
            font-size: 10px;
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
            padding: 8px 5px;
            text-align: left;
            font-size: 10px;
            border: 1px solid #000;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #000;
            font-size: 10px;
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
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        
        .badge-warning {
            background-color: #FFA500;
            color: white;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        
        .signature {
            margin-top: 60px;
            text-align: right;
            margin-right: 50px;
        }
        
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            margin-left: auto;
            margin-top: 60px;
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
        LAPORAN PEMINJAMAN PERALATAN
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
                <td style="border: none;">{{ $peminjaman->count() }} peminjaman</td>
            </tr>
        </table>
    </div>
    
    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="12%">Nomor Peminjaman</th>
                <th width="18%">Peralatan</th>
                <th width="15%">Peminjam</th>
                <th width="8%" class="text-center">Jumlah</th>
                <th width="10%">Tgl Pinjam</th>
                <th width="10%">Tgl Kembali</th>
                <th width="15%">Keperluan</th>
                <th width="8%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($peminjaman as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nomor_peminjaman }}</td>
                <td>
                    <strong>{{ $item->peralatan->nama_peralatan }}</strong><br>
                    <small>{{ $item->peralatan->kode_peralatan }}</small>
                </td>
                <td>{{ $item->nama_peminjam }}</td>
                <td class="text-center">{{ $item->jumlah_dipinjam }} {{ $item->peralatan->satuan }}</td>
                <td>{{ $item->tanggal_pinjam->format('d/m/Y') }}</td>
                <td>
                    @if($item->tanggal_kembali_aktual)
                        {{ $item->tanggal_kembali_aktual->format('d/m/Y') }}
                    @else
                        {{ $item->tanggal_kembali_rencana->format('d/m/Y') }}
                    @endif
                </td>
                <td>{{ $item->keperluan }}</td>
                <td class="text-center">
                    @if($item->status == 'dipinjam')
                        @if($item->isTerlambat())
                            <span class="badge badge-danger">Terlambat</span>
                        @else
                            <span class="badge badge-warning">Dipinjam</span>
                        @endif
                    @else
                        <span class="badge badge-success">Dikembalikan</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center">Tidak ada data peminjaman</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <!-- Footer -->
    <div class="footer">
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }}</p>
    </div>
    
    <!-- Tanda Tangan -->
    <div class="signature">
        <p>Mengetahui,</p>
        <p style="margin-top: 5px; font-weight: bold;">Manager Operasional</p>
        <div class="signature-line"></div>
        <p style="margin-top: 5px;">(............................)</p>
    </div>
</body>
</html>
