<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Santri - Saung Santri</title>
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
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .kop-header {
            display: table;
            width: 100%;
        }
        
        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: center;
        }
        
        .kop-logo img {
            width: 70px;
            height: 70px;
        }
        
        .kop-text {
            display: table-cell;
            vertical-align: middle;
            padding-left: 15px;
            text-align: center;
        }
        
        .kop-text h2 {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 3px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .kop-text h3 {
            font-size: 14px;
            font-weight: normal;
            color: #666;
            margin-bottom: 5px;
        }
        
        .kop-text p {
            font-size: 10px;
            color: #666;
            margin: 2px 0;
        }
        
        .judul-laporan {
            text-align: center;
            margin: 15px 0;
        }
        
        .judul-laporan h3 {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .info-filter {
            margin-bottom: 10px;
            font-size: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        table th {
            background-color: #667eea;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #667eea;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-warning {
            background-color: #ffc107;
            color: #333;
        }
        
        .badge-info {
            background-color: #17a2b8;
            color: white;
        }
        
        .badge-danger {
            background-color: #dc3545;
            color: white;
        }
        
        .footer {
            margin-top: 15px;
            font-size: 9px;
            color: #666;
        }
        
        .footer-left {
            float: left;
        }
        
        .footer-right {
            float: right;
        }
        
        .clearfix::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .progress-bar {
            background-color: #e9ecef;
            border-radius: 3px;
            height: 15px;
            position: relative;
            overflow: hidden;
        }
        
        .progress-fill {
            background-color: #28a745;
            height: 100%;
            text-align: center;
            line-height: 15px;
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-header">
            <div class="kop-logo">
                <!-- Icon/Logo placeholder -->
                <div style="width: 70px; height: 70px; border: 2px solid #667eea; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; color: #667eea; font-weight: bold;">
                    SS
                </div>
            </div>
            <div class="kop-text">
                <h2>SAUNG SANTRI</h2>
                <h3>Pondok Pesantren Tahfidz Al-Qur'an</h3>
                <p>Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
                <p>Kabupaten Bogor, Jawa Barat 16830</p>
                <p>Telp: (021) 1234-5678 | Email: info@saungsantri.com</p>
            </div>
        </div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul-laporan">
        <h3>LAPORAN DATA SANTRI</h3>
        <p style="font-size: 10px;">Per Tanggal: {{ $tanggal }}</p>
    </div>

    <!-- INFO FILTER -->
    @if($filter['status_santri'] || $filter['jenis_kelamin'] || $filter['tahun_masuk'] || $filter['search'])
    <div class="info-filter">
        <strong>Filter Aktif:</strong>
        @if($filter['status_santri'])
            Status: <strong>{{ ucfirst($filter['status_santri']) }}</strong> |
        @endif
        @if($filter['jenis_kelamin'])
            Jenis Kelamin: <strong>{{ $filter['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}</strong> |
        @endif
        @if($filter['tahun_masuk'])
            Tahun Masuk: <strong>{{ $filter['tahun_masuk'] }}</strong> |
        @endif
        @if($filter['search'])
            Pencarian: <strong>{{ $filter['search'] }}</strong>
        @endif
    </div>
    @endif

    <!-- SUMMARY -->
    <div style="margin-bottom: 10px; font-size: 10px;">
        <strong>Total Data: {{ $total }} Santri</strong>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th width="3%" class="text-center">No</th>
                <th width="8%">NIS</th>
                <th width="18%">Nama Lengkap</th>
                <th width="4%" class="text-center">JK</th>
                <th width="10%">Tempat Lahir</th>
                <th width="8%">Tgl Lahir</th>
                <th width="6%" class="text-center">Tahun Masuk</th>
                <th width="8%" class="text-center">Hafalan</th>
                <th width="10%">Asrama</th>
                <th width="8%" class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($santri as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->nis }}</td>
                <td>
                    <strong>{{ $item->nama_lengkap }}</strong>
                    @if($item->nama_panggilan)
                        <br><small style="color: #666;">({{ $item->nama_panggilan }})</small>
                    @endif
                </td>
                <td class="text-center">{{ $item->jenis_kelamin }}</td>
                <td>{{ $item->tempat_lahir }}</td>
                <td>{{ $item->tanggal_lahir ? $item->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                <td class="text-center">{{ $item->tahun_masuk }}</td>
                <td class="text-center">
                    <strong>{{ $item->jumlah_juz_hafalan }} Juz</strong>
                    <br>
                    <small style="color: #666;">({{ number_format($item->persentase_hafalan, 1) }}%)</small>
                </td>
                <td>
                    {{ $item->nama_asrama ?? '-' }}
                    @if($item->nomor_kamar)
                        <br><small>Kamar: {{ $item->nomor_kamar }}</small>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->status_santri == 'aktif')
                        <span class="badge badge-success">Aktif</span>
                    @elseif($item->status_santri == 'cuti')
                        <span class="badge badge-warning">Cuti</span>
                    @elseif($item->status_santri == 'alumni')
                        <span class="badge badge-info">Alumni</span>
                    @else
                        <span class="badge badge-danger">Keluar</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data santri</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- FOOTER -->
    <div class="footer clearfix">
        <div class="footer-left">
            Dicetak oleh: {{ auth()->user()->name ?? 'System' }}
        </div>
        <div class="footer-right">
            Tanggal Cetak: {{ now()->format('d/m/Y H:i:s') }}
        </div>
    </div>
</body>
</html>
