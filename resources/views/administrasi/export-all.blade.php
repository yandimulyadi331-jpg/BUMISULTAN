<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Administrasi Perusahaan</title>
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
            color: #333;
        }
        
        .kop-surat {
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .kop-header {
            display: table;
            width: 100%;
        }
        
        .kop-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        
        .kop-logo img {
            width: 70px;
            height: 70px;
        }
        
        .kop-content {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
            padding: 0 10px;
        }
        
        .kop-content h2 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 2px;
            color: #1a1a1a;
        }
        
        .kop-content h3 {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
        
        .kop-content p {
            font-size: 9px;
            margin: 2px 0;
            color: #555;
        }
        
        .report-title {
            text-align: center;
            margin: 20px 0;
        }
        
        .report-title h1 {
            font-size: 16px;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        
        .report-info {
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        table th {
            background-color: #2c3e50;
            color: white;
            padding: 8px 5px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
            border: 1px solid #1a252f;
        }
        
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        
        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        table tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            display: inline-block;
        }
        
        .badge-primary { background-color: #3498db; color: white; }
        .badge-success { background-color: #27ae60; color: white; }
        .badge-info { background-color: #16a085; color: white; }
        .badge-warning { background-color: #f39c12; color: white; }
        .badge-danger { background-color: #e74c3c; color: white; }
        .badge-secondary { background-color: #95a5a6; color: white; }
        
        .tindak-lanjut {
            margin-top: 5px;
            padding: 5px;
            background-color: #ecf0f1;
            border-radius: 3px;
            font-size: 8px;
        }
        
        .tindak-lanjut-item {
            margin-bottom: 3px;
            padding: 3px;
            background-color: white;
            border-left: 3px solid #3498db;
            padding-left: 5px;
        }
        
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #888;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .summary-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .summary-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
        }
        
        .summary-value {
            display: table-cell;
            width: 60%;
        }
    </style>
</head>
<body>
    <!-- KOP SURAT -->
    <div class="kop-surat">
        <div class="kop-header">
            <div class="kop-content" style="width: 100%;">
                <h2>BUMI SULTAN</h2>
                <h3>MANAJEMEN ADMINISTRASI PERUSAHAAN</h3>
                <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
                <p>Kabupaten Bogor, Jawa Barat 16830</p>
                <p>Telepon: (021) 22966797 | Provinsi: Jawa Barat</p>
            </div>
        </div>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="report-title">
        <h1>LAPORAN DATA ADMINISTRASI PERUSAHAAN</h1>
    </div>

    <!-- INFO LAPORAN -->
    <div class="report-info">
        <div class="summary-row">
            <div class="summary-label">Tanggal Cetak:</div>
            <div class="summary-value">{{ $tanggal_cetak }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Total Data:</div>
            <div class="summary-value">{{ $administrasi->count() }} Administrasi</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Dicetak Oleh:</div>
            <div class="summary-value">{{ Auth::user()->name ?? 'System' }}</div>
        </div>
    </div>

    <!-- RINGKASAN STATISTIK -->
    <div class="summary-section">
        <strong>Ringkasan Status:</strong>
        <div style="margin-top: 5px;">
            @php
                $statusCounts = $administrasi->groupBy('status')->map->count();
                $prioritasCounts = $administrasi->groupBy('prioritas')->map->count();
            @endphp
            Pending: {{ $statusCounts->get('pending', 0) }} | 
            Proses: {{ $statusCounts->get('proses', 0) }} | 
            Selesai: {{ $statusCounts->get('selesai', 0) }} | 
            Ditolak: {{ $statusCounts->get('ditolak', 0) }}
            <br>
            <strong>Prioritas:</strong>
            Rendah: {{ $prioritasCounts->get('rendah', 0) }} | 
            Sedang: {{ $prioritasCounts->get('sedang', 0) }} | 
            Tinggi: {{ $prioritasCounts->get('tinggi', 0) }} | 
            Urgent: {{ $prioritasCounts->get('urgent', 0) }}
        </div>
    </div>

    <!-- TABEL DATA -->
    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 9%;">Kode</th>
                <th style="width: 10%;">Jenis</th>
                <th style="width: 10%;">Nomor Surat</th>
                <th style="width: 20%;">Perihal</th>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 12%;">Pengirim/Penerima</th>
                <th style="width: 7%;">Status</th>
                <th style="width: 7%;">Prioritas</th>
                <th style="width: 14%;">Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
            @forelse($administrasi as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td><strong>{{ $item->kode_administrasi }}</strong></td>
                <td>
                    <span class="badge badge-primary">
                        {{ $item->getJenisAdministrasiLabel() }}
                    </span>
                </td>
                <td>{{ $item->nomor_surat ?? '-' }}</td>
                <td>{{ Str::limit($item->perihal, 50) }}</td>
                <td>{{ $item->tanggal_surat ? \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') : '-' }}</td>
                <td>
                    @if($item->isMasuk())
                        <strong>Dari:</strong> {{ Str::limit($item->pengirim, 30) }}
                    @else
                        <strong>Kepada:</strong> {{ Str::limit($item->penerima, 30) }}
                    @endif
                </td>
                <td>
                    @php
                        $statusClass = '';
                        switch($item->status) {
                            case 'pending': $statusClass = 'badge-warning'; break;
                            case 'proses': $statusClass = 'badge-info'; break;
                            case 'selesai': $statusClass = 'badge-success'; break;
                            case 'ditolak': $statusClass = 'badge-danger'; break;
                            default: $statusClass = 'badge-secondary';
                        }
                    @endphp
                    <span class="badge {{ $statusClass }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </td>
                <td>
                    @php
                        $prioritasClass = '';
                        switch($item->prioritas) {
                            case 'rendah': $prioritasClass = 'badge-secondary'; break;
                            case 'sedang': $prioritasClass = 'badge-info'; break;
                            case 'tinggi': $prioritasClass = 'badge-warning'; break;
                            case 'urgent': $prioritasClass = 'badge-danger'; break;
                        }
                    @endphp
                    <span class="badge {{ $prioritasClass }}">
                        {{ ucfirst($item->prioritas) }}
                    </span>
                </td>
                <td>
                    @if($item->tindakLanjut->count() > 0)
                        <div class="tindak-lanjut">
                            <strong>{{ $item->tindakLanjut->count() }} Tindak Lanjut:</strong>
                            @foreach($item->tindakLanjut as $tl)
                                <div class="tindak-lanjut-item">
                                    <strong>{{ $tl->kode_tindak_lanjut }}</strong><br>
                                    {{ $tl->getJenisTindakLanjutLabel() }}<br>
                                    <small>{{ \Carbon\Carbon::parse($tl->tanggal_tindak_lanjut)->format('d/m/Y') }}</small>
                                    @if($tl->pic)
                                        <br><small>PIC: {{ $tl->pic->name }}</small>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <span class="badge badge-secondary">Belum Ada</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 20px;">
                    Tidak ada data administrasi
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- CATATAN KAKI -->
    <div style="margin-top: 30px; font-size: 8px; color: #666;">
        <strong>Keterangan:</strong><br>
        - Laporan ini dicetak secara otomatis dari Sistem Manajemen Administrasi Bumi Sultan<br>
        - Data yang ditampilkan adalah data aktif pada saat pencetakan<br>
        - Untuk informasi lebih lanjut hubungi bagian administrasi perusahaan
    </div>

    <!-- TANDA TANGAN -->
    <div style="margin-top: 50px;">
        <table style="border: none; width: 100%;">
            <tr>
                <td style="border: none; width: 50%;"></td>
                <td style="border: none; width: 50%; text-align: center;">
                    <div>Jonggol, {{ now()->format('d F Y') }}</div>
                    <div style="margin-top: 5px; font-weight: bold;">Penanggung Jawab</div>
                    <div style="margin-top: 70px; font-weight: bold; border-top: 1px solid #000; display: inline-block; padding-top: 5px; min-width: 200px;">
                        (&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>Bumi Sultan - Jl. Raya Jonggol No.37, Jonggol, Bogor | Telepon: (021) 22966797</p>
        <p>Dokumen ini dicetak otomatis dan sah tanpa tanda tangan</p>
    </div>
</body>
</html>
