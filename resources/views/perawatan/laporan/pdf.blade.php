<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Perawatan Gedung - {{ $laporan->periode_key }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #0066cc;
        }
        
        .header h1 {
            font-size: 18px;
            color: #0066cc;
            margin-bottom: 5px;
        }
        
        .header h2 {
            font-size: 14px;
            color: #666;
            font-weight: normal;
        }
        
        .info-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        
        .info-table td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        
        .info-table td:first-child {
            width: 30%;
            background-color: #f5f5f5;
            font-weight: bold;
        }
        
        .summary-box {
            background-color: #e8f4f8;
            border: 2px solid #0066cc;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary-box h3 {
            color: #0066cc;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .checklist-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        .checklist-table thead {
            background-color: #0066cc;
            color: white;
        }
        
        .checklist-table th,
        .checklist-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        .checklist-table th {
            font-weight: bold;
            font-size: 11px;
        }
        
        .checklist-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .checklist-table tbody tr:hover {
            background-color: #f0f0f0;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-align: center;
        }
        
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        
        .badge-blue {
            background-color: #0066cc;
            color: white;
        }
        
        .badge-green {
            background-color: #28a745;
            color: white;
        }
        
        .badge-yellow {
            background-color: #ffc107;
            color: #333;
        }
        
        .badge-gray {
            background-color: #6c757d;
            color: white;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        
        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
            padding: 10px;
        }
        
        .signature-box .name {
            margin-top: 60px;
            font-weight: bold;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 150px;
        }
        
        .category-section {
            margin-bottom: 15px;
        }
        
        .category-title {
            background-color: #0066cc;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 5px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PERAWATAN GEDUNG</h1>
        <h2>{{ strtoupper($laporan->tipe_laporan) }} - {{ $laporan->periode_key }}</h2>
    </div>

    <table class="info-table">
        <tr>
            <td>Periode Laporan</td>
            <td><strong>{{ $laporan->periode_key }}</strong></td>
        </tr>
        <tr>
            <td>Tipe Periode</td>
            <td>{{ ucfirst($laporan->tipe_laporan) }}</td>
        </tr>
        <tr>
            <td>Tanggal Laporan</td>
            <td>{{ $laporan->tanggal_laporan->format('d F Y') }}</td>
        </tr>
        <tr>
            <td>Periode Pelaksanaan</td>
            <td>{{ $statusPeriode->periode_start->format('d M Y') }} s/d {{ $statusPeriode->periode_end->format('d M Y') }}</td>
        </tr>
        <tr>
            <td>Dibuat Oleh</td>
            <td>{{ $laporan->pembuatLaporan->name ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Total Checklist</td>
            <td>
                <strong>{{ $laporan->total_completed }}/{{ $laporan->total_checklist }}</strong>
                @if($laporan->total_completed == $laporan->total_checklist)
                    <span class="text-success">✓ LENGKAP</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="summary-box">
        <h3>📊 Ringkasan Pelaksanaan</h3>
        {!! nl2br(e($laporan->ringkasan)) !!}
    </div>

    @php
        $logsByKategori = $logs->groupBy('masterPerawatan.kategori');
        $kategoriColors = [
            'kebersihan' => 'blue',
            'perawatan_rutin' => 'green',
            'pengecekan' => 'yellow',
            'lainnya' => 'gray'
        ];
        $kategoriIcons = [
            'kebersihan' => '🧹',
            'perawatan_rutin' => '🔧',
            'pengecekan' => '✅',
            'lainnya' => '📋'
        ];
    @endphp

    @foreach($logsByKategori as $kategori => $logItems)
    <div class="category-section">
        <div class="category-title">
            {{ $kategoriIcons[$kategori] ?? '📋' }} {{ strtoupper(str_replace('_', ' ', $kategori)) }} ({{ $logItems->count() }} Kegiatan)
        </div>
        
        <table class="checklist-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="35%">Kegiatan</th>
                    <th width="15%">Tanggal</th>
                    <th width="10%">Waktu</th>
                    <th width="15%">Petugas</th>
                    <th width="10%">Status</th>
                    <th width="10%">Foto</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logItems as $index => $log)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $log->masterPerawatan->nama_kegiatan }}</strong>
                        @if($log->masterPerawatan->deskripsi)
                            <br><small style="color: #666;">{{ $log->masterPerawatan->deskripsi }}</small>
                        @endif
                        @if($log->catatan)
                            <br><small style="color: #0066cc;"><em>Catatan: {{ $log->catatan }}</em></small>
                        @endif
                    </td>
                    <td>{{ $log->tanggal_eksekusi->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($log->waktu_eksekusi)->format('H:i') }}</td>
                    <td>{{ $log->user->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        <span class="badge badge-success">✓ Selesai</span>
                    </td>
                    <td class="text-center">
                        @if($log->foto_bukti)
                            <span class="badge badge-blue">📷 Ada</span>
                        @else
                            <span style="color: #999;">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endforeach

    <div class="signature-section">
        <div class="signature-box">
            <div>Mengetahui,</div>
            <div><strong>Kepala Bagian</strong></div>
            <div class="name">(...........................)</div>
        </div>
        <div class="signature-box">
            <div>{{ $laporan->tanggal_laporan->format('d F Y') }}</div>
            <div><strong>Penanggung Jawab</strong></div>
            <div class="name">{{ $laporan->pembuatLaporan->name ?? '(...........................)' }}</div>
        </div>
    </div>

    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh Sistem Manajemen Perawatan Gedung</p>
        <p>Dicetak pada: {{ $tanggal_cetak }}</p>
        <p><em>Dokumen ini sah tanpa tanda tangan basah</em></p>
    </div>
</body>
</html>
