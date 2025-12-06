<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Manajemen Pengunjung</title>
    <style>
        @page {
            margin: 20px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            padding: 15px 0;
            border-bottom: 3px solid #0056b3;
            margin-bottom: 20px;
        }
        
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 5px;
        }
        
        .company-info {
            font-size: 10px;
            color: #666;
            line-height: 1.5;
        }
        
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            color: #0056b3;
        }
        
        .report-date {
            text-align: center;
            font-size: 10px;
            color: #666;
            margin-bottom: 20px;
        }
        
        .summary-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
        }
        
        .summary-item {
            text-align: center;
            padding: 10px;
            background: white;
            border-radius: 3px;
        }
        
        .summary-number {
            font-size: 20px;
            font-weight: bold;
            color: #0056b3;
        }
        
        .summary-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0056b3;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 2px solid #0056b3;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 9px;
        }
        
        table thead {
            background: #343a40;
            color: white;
        }
        
        table th {
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #dee2e6;
        }
        
        table td {
            padding: 5px;
            border: 1px solid #dee2e6;
        }
        
        table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 8px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #333;
        }
        
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        
        .badge-secondary {
            background: #6c757d;
            color: white;
        }
        
        .no-data {
            text-align: center;
            padding: 20px;
            color: #999;
            font-style: italic;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <div class="header">
        <div class="company-name">BUMI SULTAN</div>
        <div class="company-info">
            Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830<br>
            Telepon: (021) 22966797 | Provinsi: Jawa Barat
        </div>
    </div>
    
    <div class="report-title">Laporan Manajemen Pengunjung</div>
    <div class="report-date">Tanggal Cetak: {{ $tanggal }}</div>
    
    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $totalPengunjung }}</div>
                <div class="summary-label">Total Pengunjung</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalCheckin }}</div>
                <div class="summary-label">Sedang Check-In</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalCheckout }}</div>
                <div class="summary-label">Sudah Check-Out</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalJadwal }}</div>
                <div class="summary-label">Jadwal Kunjungan</div>
            </div>
        </div>
    </div>
    
    <!-- Data Pengunjung -->
    <div class="section-title">DAFTAR PENGUNJUNG</div>
    
    @if($pengunjung->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="10%">Kode</th>
                    <th width="15%">Nama Lengkap</th>
                    <th width="12%">Instansi</th>
                    <th width="10%">No. Telepon</th>
                    <th width="15%">Keperluan</th>
                    <th width="12%">Check-In</th>
                    <th width="12%">Check-Out</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengunjung as $index => $p)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $p->kode_pengunjung }}</td>
                        <td>{{ $p->nama_lengkap }}</td>
                        <td>{{ $p->instansi ?? '-' }}</td>
                        <td>{{ $p->no_telepon }}</td>
                        <td>{{ $p->keperluan }}</td>
                        <td>{{ $p->waktu_checkin->format('d/m/Y H:i') }}</td>
                        <td>
                            @if($p->waktu_checkout)
                                {{ $p->waktu_checkout->format('d/m/Y H:i') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($p->status == 'checkin')
                                <span class="badge badge-success">Check-In</span>
                            @else
                                <span class="badge badge-secondary">Check-Out</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Tidak ada data pengunjung</div>
    @endif
    
    <!-- Page Break -->
    @if($jadwal->count() > 0)
        <div class="page-break"></div>
    @endif
    
    <!-- Data Jadwal Pengunjung -->
    @if($jadwal->count() > 0)
        <div class="section-title">JADWAL KUNJUNGAN</div>
        
        <table>
            <thead>
                <tr>
                    <th width="3%">No</th>
                    <th width="12%">Kode Jadwal</th>
                    <th width="18%">Nama Lengkap</th>
                    <th width="15%">Instansi</th>
                    <th width="12%">No. Telepon</th>
                    <th width="15%">Keperluan</th>
                    <th width="12%">Tanggal</th>
                    <th width="8%">Waktu</th>
                    <th width="8%">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwal as $index => $j)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $j->kode_jadwal }}</td>
                        <td>{{ $j->nama_lengkap }}</td>
                        <td>{{ $j->instansi ?? '-' }}</td>
                        <td>{{ $j->no_telepon }}</td>
                        <td>{{ $j->keperluan }}</td>
                        <td>{{ $j->tanggal_kunjungan->format('d/m/Y') }}</td>
                        <td>{{ date('H:i', strtotime($j->waktu_kunjungan)) }}</td>
                        <td class="text-center">
                            @if($j->status == 'terjadwal')
                                <span class="badge badge-info">Terjadwal</span>
                            @elseif($j->status == 'selesai')
                                <span class="badge badge-success">Selesai</span>
                            @else
                                <span class="badge badge-danger">Batal</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <strong>BUMI SULTAN</strong> - Sistem Manajemen Pengunjung<br>
        Laporan ini dicetak pada {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
