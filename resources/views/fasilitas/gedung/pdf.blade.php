<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Fasilitas & Asset</title>
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
        
        .gedung-block {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .gedung-header {
            background: #0056b3;
            color: white;
            padding: 8px 10px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .ruangan-block {
            margin-bottom: 15px;
            padding-left: 15px;
        }
        
        .ruangan-header {
            background: #e7f3ff;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11px;
            color: #0056b3;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 10px;
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
        }
        
        table td {
            padding: 5px;
            border-bottom: 1px solid #dee2e6;
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
        
        .badge-primary {
            background: #007bff;
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
        
        .text-right {
            text-align: right;
        }
        
        .page-break {
            page-break-after: always;
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
    
    <div class="report-title">Laporan Fasilitas & Asset</div>
    <div class="report-date">Tanggal Cetak: {{ $tanggal }}</div>
    
    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-number">{{ $totalGedung }}</div>
                <div class="summary-label">Total Gedung</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalRuangan }}</div>
                <div class="summary-label">Total Ruangan</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalBarang }}</div>
                <div class="summary-label">Total Barang</div>
            </div>
            <div class="summary-item">
                <div class="summary-number">{{ $totalTransfer }}</div>
                <div class="summary-label">Total Transfer</div>
            </div>
        </div>
    </div>
    
    <!-- Data Gedung, Ruangan & Barang -->
    <div class="section-title">DATA GEDUNG, RUANGAN & BARANG</div>
    
    @foreach($gedung as $g)
        <div class="gedung-block">
            <div class="gedung-header">
                {{ $g->kode_gedung }} - {{ strtoupper($g->nama_gedung) }} 
                ({{ $g->cabang->nama_cabang ?? 'N/A' }}) - 
                {{ $g->jumlah_lantai }} Lantai
            </div>
            
            @if($g->ruangans->count() > 0)
                @foreach($g->ruangans as $r)
                    <div class="ruangan-block">
                        <div class="ruangan-header">
                            📍 {{ $r->kode_ruangan }} - {{ $r->nama_ruangan }} 
                            @if($r->lantai)
                                (Lantai {{ $r->lantai }})
                            @endif
                            - {{ $r->barangs->count() }} Barang
                        </div>
                        
                        @if($r->barangs->count() > 0)
                            <table>
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="12%">Kode</th>
                                        <th width="23%">Nama Barang</th>
                                        <th width="15%">Kategori</th>
                                        <th width="12%">Merk</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="10%">Kondisi</th>
                                        <th width="13%" class="text-right">Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($r->barangs as $index => $b)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $b->kode_barang }}</td>
                                            <td>{{ $b->nama_barang }}</td>
                                            <td>{{ $b->kategori ?? '-' }}</td>
                                            <td>{{ $b->merk ?? '-' }}</td>
                                            <td>{{ $b->jumlah }} {{ $b->satuan }}</td>
                                            <td>
                                                @if($b->kondisi == 'Baik')
                                                    <span class="badge badge-success">{{ $b->kondisi }}</span>
                                                @elseif($b->kondisi == 'Rusak Ringan')
                                                    <span class="badge badge-warning">{{ $b->kondisi }}</span>
                                                @else
                                                    <span class="badge badge-danger">{{ $b->kondisi }}</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                {{ $b->harga_perolehan ? 'Rp ' . number_format($b->harga_perolehan, 0, ',', '.') : '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="no-data">Tidak ada barang di ruangan ini</div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="no-data">Tidak ada ruangan di gedung ini</div>
            @endif
        </div>
    @endforeach
    
    <!-- Page Break -->
    <div class="page-break"></div>
    
    <!-- Riwayat Transfer -->
    <div class="section-title">RIWAYAT TRANSFER BARANG</div>
    
    @if($transfers->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="13%">Kode Transfer</th>
                    <th width="10%">Tanggal</th>
                    <th width="18%">Nama Barang</th>
                    <th width="18%">Dari</th>
                    <th width="18%">Ke</th>
                    <th width="10%">Jumlah</th>
                    <th width="8%">Petugas</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transfers as $index => $t)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $t->kode_transfer }}</td>
                        <td>{{ $t->tanggal_transfer->format('d/m/Y') }}</td>
                        <td>{{ $t->barang->nama_barang ?? 'N/A' }}</td>
                        <td>
                            <small>
                                <strong>{{ $t->ruanganAsal->gedung->nama_gedung ?? 'N/A' }}</strong><br>
                                {{ $t->ruanganAsal->nama_ruangan ?? 'N/A' }}
                            </small>
                        </td>
                        <td>
                            <small>
                                <strong>{{ $t->ruanganTujuan->gedung->nama_gedung ?? 'N/A' }}</strong><br>
                                {{ $t->ruanganTujuan->nama_ruangan ?? 'N/A' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge badge-primary">{{ $t->jumlah_transfer }}</span>
                        </td>
                        <td>{{ $t->petugas ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">Belum ada riwayat transfer barang</div>
    @endif
    
    <!-- Footer -->
    <div class="footer">
        <strong>BUMI SULTAN</strong> - Sistem Manajemen Fasilitas & Asset<br>
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>
</html>
