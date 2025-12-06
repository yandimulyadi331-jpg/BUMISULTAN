<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Ijin Santri - Saung Santri</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #667eea;
        }

        .header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0;
            padding: 0;
            color: #667eea;
        }

        .header h2 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
            padding: 0;
            color: #764ba2;
        }

        .header p {
            font-size: 9pt;
            margin: 2px 0;
            padding: 0;
            color: #555;
        }

        .title-section {
            text-align: center;
            margin: 15px 0;
        }

        .title-section h3 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            color: #333;
            text-decoration: underline;
        }

        .info-section {
            margin: 10px 0 15px 0;
            font-size: 9pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        table thead {
            background-color: #667eea;
            color: white !important;
        }

        table thead th {
            padding: 10px 6px;
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            border: 1px solid #333;
            color: white !important;
            vertical-align: middle;
        }

        table tbody td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9pt;
            vertical-align: top;
        }

        table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tbody tr:hover {
            background-color: #f0f0f0;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 8pt;
            font-weight: bold;
            color: white;
            text-align: center;
        }

        .badge-warning {
            background-color: #f39c12;
        }

        .badge-info {
            background-color: #3498db;
        }

        .badge-primary {
            background-color: #9b59b6;
        }

        .badge-success {
            background-color: #27ae60;
        }

        .footer-info {
            margin-top: 20px;
            font-size: 8pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }

        .footer-info .summary {
            display: inline-block;
            margin-right: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: 8pt;
        }

        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }

        .signature-section {
            margin-top: 30px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .signature-space {
            height: 60px;
        }

        .signature-name {
            font-weight: bold;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 5px;
        }

        .page-number {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 8pt;
            color: #666;
        }
    </style>
</head>
<body>
    <!-- Header Kop Surat -->
    <div class="header">
        <h1>PONDOK PESANTREN</h1>
        <h2>SAUNG SANTRI</h2>
        <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p>Kabupaten Bogor, Jawa Barat 16830</p>
        <p>Telp: (021) 89534421 | Email: info@saungan tri.com</p>
    </div>

    <!-- Title -->
    <div class="title-section">
        <h3>LAPORAN DATA IJIN SANTRI</h3>
    </div>

    <!-- Info Section -->
    <div class="info-section">
        <table style="border: none; width: 100%; margin-bottom: 15px;">
            <tr style="border: none;">
                <td style="border: none; width: 150px;">Tanggal Cetak</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none;"><strong>{{ \Carbon\Carbon::now()->format('d F Y H:i') }} WIB</strong></td>
                <td style="border: none; width: 150px; text-align: right;">Total Data</td>
                <td style="border: none; width: 10px;">:</td>
                <td style="border: none; width: 100px;"><strong>{{ $ijinSantri->count() }} Ijin</strong></td>
            </tr>
        </table>
    </div>

    <!-- Tabel Data -->
    @if($ijinSantri->count() > 0)
        <table>
            <thead>
                <tr>
                    <th width="3%" class="text-center">No</th>
                    <th width="12%">No. Surat</th>
                    <th width="15%">Nama Santri</th>
                    <th width="7%">Tgl Ijin</th>
                    <th width="7%">Rencana</th>
                    <th width="7%">Kembali</th>
                    <th width="20%">Alasan</th>
                    <th width="10%">Status</th>
                    <th width="8%">Dibuat</th>
                    <th width="11%">Verifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($ijinSantri as $index => $ijin)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td><strong>{{ $ijin->nomor_surat }}</strong></td>
                        <td>
                            <strong>{{ $ijin->santri->nama_lengkap }}</strong><br>
                            <span class="small">NIS: {{ $ijin->santri->nis }}</span>
                        </td>
                        <td>{{ $ijin->tanggal_ijin->format('d/m/Y') }}</td>
                        <td>{{ $ijin->tanggal_kembali_rencana->format('d/m/Y') }}</td>
                        <td>
                            @if($ijin->tanggal_kembali_aktual)
                                {{ $ijin->tanggal_kembali_aktual->format('d/m/Y') }}
                            @else
                                <span class="small">-</span>
                            @endif
                        </td>
                        <td class="small">{{ Str::limit($ijin->alasan_ijin, 50) }}</td>
                        <td>
                            @if($ijin->status == 'pending')
                                <span class="badge badge-warning">Menunggu TTD</span>
                            @elseif($ijin->status == 'ttd_ustadz')
                                <span class="badge badge-info">TTD Ustadz</span>
                            @elseif($ijin->status == 'dipulangkan')
                                <span class="badge badge-primary">Pulang</span>
                            @else
                                <span class="badge badge-success">Kembali</span>
                            @endif
                        </td>
                        <td class="small">
                            {{ $ijin->creator->name }}<br>
                            {{ $ijin->created_at->format('d/m/Y') }}
                        </td>
                        <td class="small">
                            @if($ijin->ttd_ustadz_at)
                                ✓ TTD: {{ $ijin->ttd_ustadz_at->format('d/m/y') }}<br>
                            @endif
                            @if($ijin->verifikasi_pulang_at)
                                ✓ Pulang: {{ $ijin->verifikasi_pulang_at->format('d/m/y') }}<br>
                            @endif
                            @if($ijin->verifikasi_kembali_at)
                                ✓ Kembali: {{ $ijin->verifikasi_kembali_at->format('d/m/y') }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary Statistics -->
        <div class="footer-info">
            <strong>RINGKASAN DATA:</strong><br>
            <div class="summary">
                <span class="badge badge-warning">Pending</span>: 
                <strong>{{ $ijinSantri->where('status', 'pending')->count() }}</strong>
            </div>
            <div class="summary">
                <span class="badge badge-info">TTD Ustadz</span>: 
                <strong>{{ $ijinSantri->where('status', 'ttd_ustadz')->count() }}</strong>
            </div>
            <div class="summary">
                <span class="badge badge-primary">Pulang</span>: 
                <strong>{{ $ijinSantri->where('status', 'dipulangkan')->count() }}</strong>
            </div>
            <div class="summary">
                <span class="badge badge-success">Kembali</span>: 
                <strong>{{ $ijinSantri->where('status', 'kembali')->count() }}</strong>
            </div>
        </div>
    @else
        <div class="no-data">
            <p>Tidak ada data ijin santri yang tersedia</p>
        </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p>Jonggol, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p><strong>Penanggung Jawab</strong></p>
            <div class="signature-space"></div>
            <p class="signature-name">_____________________</p>
            <p class="small">Pengurus Pondok</p>
        </div>
    </div>

    <!-- Footer -->
    <div class="page-number">
        Halaman 1 dari 1 | Dicetak: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }} WIB
    </div>

    <!-- Catatan -->
    <div style="margin-top: 20px; font-size: 8pt; color: #666; font-style: italic;">
        <strong>Catatan:</strong> Dokumen ini dicetak secara otomatis dari Sistem Manajemen Saung Santri
    </div>
</body>
</html>
