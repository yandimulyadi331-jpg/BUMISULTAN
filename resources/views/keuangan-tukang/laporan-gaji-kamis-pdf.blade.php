<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran Gaji Kamis</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 3px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }
        .header p {
            margin: 3px 0;
            font-size: 11px;
            color: #666;
        }
        .periode-info {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 8px 5px;
            text-align: center;
            border: 1px solid #333;
            font-weight: bold;
            font-size: 10px;
        }
        table td {
            padding: 6px 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .text-left {
            text-align: left;
        }
        .total-row {
            font-weight: bold;
            background-color: #f0f0f0;
            font-size: 10px;
        }
        .summary-section {
            margin-top: 20px;
            page-break-inside: avoid;
        }
        .summary-box {
            display: table;
            width: 100%;
            border: 2px solid #333;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .summary-row {
            display: table-row;
        }
        .summary-label {
            display: table-cell;
            width: 200px;
            font-weight: bold;
            padding: 5px;
        }
        .summary-value {
            display: table-cell;
            text-align: right;
            padding: 5px;
            font-size: 12px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            white-space: nowrap;
        }
        .status-lunas {
            background-color: #4caf50;
            color: white;
        }
        .status-pending {
            background-color: #ff9800;
            color: white;
        }
        .status-dibatalkan {
            background-color: #f44336;
            color: white;
        }
        .signature-section {
            margin-top: 30px;
            text-align: right;
            page-break-inside: avoid;
        }
        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 50px;
        }
        .signature-name {
            border-top: 1px solid #333;
            padding-top: 5px;
            font-weight: bold;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .ttd-thumbnail {
            width: 75px;
            height: 35px;
            border: 1px solid #333;
            padding: 2px;
            background-color: #fff;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <p style="font-size: 10px; margin: 2px 0;">Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p style="font-size: 10px; margin: 2px 0;">Kabupaten Bogor, Jawa Barat 16830</p>
        <p style="font-size: 12px; margin-top: 8px; font-weight: bold;">LAPORAN PEMBAYARAN GAJI TUKANG DAN KENEK</p>
    </div>

    <div class="periode-info">
        Periode: {{ \Carbon\Carbon::parse($periode_mulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($periode_akhir)->format('d M Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 25px;">No</th>
                <th style="width: 70px;">Kode</th>
                <th style="width: 120px;">Nama Tukang</th>
                <th style="width: 55px;">Hadir</th>
                <th style="width: 60px;">Tarif/Hari</th>
                <th style="width: 75px;">Upah Harian</th>
                <th style="width: 75px;">Upah Lembur</th>
                <th style="width: 65px;">Cash</th>
                <th style="width: 70px;">Total Kotor</th>
                <th style="width: 70px;">Potongan</th>
                <th style="width: 75px;">Gaji Bersih</th>
                <th style="width: 70px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalUpahHarian = 0;
                $totalUpahLembur = 0;
                $totalCash = 0;
                $totalKotor = 0;
                $totalPotongan = 0;
                $totalGajiBersih = 0;
                $totalLunas = 0;
                $totalPending = 0;
            @endphp
            @foreach($pembayaranGaji as $index => $pembayaran)
                @php
                    $totalUpahHarian += $pembayaran->total_upah_harian;
                    $totalUpahLembur += $pembayaran->total_upah_lembur;
                    $totalCash += $pembayaran->lembur_cash_terbayar;
                    $totalKotor += $pembayaran->total_kotor;
                    $totalPotongan += $pembayaran->total_potongan;
                    $totalGajiBersih += $pembayaran->total_nett;
                    if($pembayaran->status == 'lunas') $totalLunas++;
                    if($pembayaran->status == 'pending') $totalPending++;
                    
                    // Hitung total kehadiran
                    $totalKehadiran = $pembayaran->jumlah_kehadiran + $pembayaran->jumlah_setengah;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $pembayaran->tukang->kode_tukang }}</td>
                    <td class="text-left">{{ $pembayaran->tukang->nama_tukang }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ $pembayaran->jumlah_kehadiran }}+{{ $pembayaran->jumlah_setengah }}</td>
                    <td class="text-right">{{ number_format($pembayaran->tukang->tarif_harian, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($pembayaran->total_upah_harian, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($pembayaran->total_upah_lembur, 0, ',', '.') }}</td>
                    <td class="text-right">({{ number_format($pembayaran->lembur_cash_terbayar, 0, ',', '.') }})</td>
                    <td class="text-right">{{ number_format($pembayaran->total_kotor, 0, ',', '.') }}</td>
                    <td class="text-right">{{ number_format($pembayaran->total_potongan, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>{{ number_format($pembayaran->total_nett, 0, ',', '.') }}</strong></td>
                    <td class="text-center" style="padding: 4px; vertical-align: middle;">
                        <span class="status-badge status-{{ $pembayaran->status }}">
                            {{ strtoupper(substr($pembayaran->status, 0, 3)) }}
                        </span>
                    </td>
                </tr>
                
                <!-- DETAIL POTONGAN ROW (jika ada) -->
                @if(count($pembayaran->rincian_potongan_detail) > 0)
                <tr style="background-color: #f9f9f9; font-size: 8px;">
                    <td colspan="4" class="text-right" style="padding: 3px; font-weight: bold; color: #666;">Potongan Detail:</td>
                    <td colspan="8" style="padding: 3px;">
                        @foreach($pembayaran->rincian_potongan_detail as $potongan)
                            <div style="margin: 2px 0;">
                                • {{ $potongan['jenis'] }}: Rp {{ number_format($potongan['jumlah'], 0, ',', '.') }}
                                @if($potongan['status'] == 'aktif')
                                    <span style="color: #666; font-size: 7px;">[Auto Potong: AKTIF]</span>
                                @endif
                            </div>
                        @endforeach
                    </td>
                </tr>
                @endif
            @endforeach
            
            <!-- TOTAL ROW -->
            <tr class="total-row">
                <td colspan="4" class="text-center">TOTAL</td>
                <td class="text-right">-</td>
                <td class="text-right">{{ number_format($totalUpahHarian, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalUpahLembur, 0, ',', '.') }}</td>
                <td class="text-right">({{ number_format($totalCash, 0, ',', '.') }})</td>
                <td class="text-right">{{ number_format($totalKotor, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($totalPotongan, 0, ',', '.') }}</td>
                <td class="text-right"><strong>{{ number_format($totalGajiBersih, 0, ',', '.') }}</strong></td>
                <td class="text-center">{{ $totalLunas }} L / {{ $totalPending }} P</td>
            </tr>
        </tbody>
    </table>

    <!-- LEGEND & NOTES -->
    <div style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px; font-size: 8px; color: #666; line-height: 1.5;">
        <strong>Keterangan:</strong>
        <ul style="margin: 5px 0; padding-left: 15px;">
            <li><strong>Hadir</strong> = Jumlah hari hadir + hari setengah (format: H+setengah)</li>
            <li><strong>Tarif/Hari</strong> = Tarif harian tukang (ditentukan per tukang)</li>
            <li><strong>Potongan</strong> = Cicilan pinjaman (jika auto_potong_pinjaman AKTIF) + denda/kerusakan (selalu ditampilkan)</li>
            <li><strong>Potongan Detail</strong> = Rincian lengkap per jenis potongan dengan status aktif/tidak</li>
            <li><strong>Total L/P</strong> = Total pembayaran Lunas / Pending</li>
        </ul>
    </div>

    <div class="summary-section">
        <div class="summary-box">
            <div class="summary-row">
                <div class="summary-label">Total Tukang:</div>
                <div class="summary-value">{{ count($pembayaranGaji) }} orang</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Total Pembayaran:</div>
                <div class="summary-value">Rp {{ number_format($totalGajiBersih, 0, ',', '.') }}</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Status Lunas:</div>
                <div class="summary-value">{{ $totalLunas }} orang (Rp {{ number_format($pembayaranGaji->where('status', 'lunas')->sum('total_nett'), 0, ',', '.') }})</div>
            </div>
            <div class="summary-row">
                <div class="summary-label">Status Pending:</div>
                <div class="summary-value">{{ $totalPending }} orang (Rp {{ number_format($pembayaranGaji->where('status', 'pending')->sum('total_nett'), 0, ',', '.') }})</div>
            </div>
        </div>
    </div>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-label">Mengetahui,<br>Divisi Keuangan</div>
            <div class="signature-name">
                _______________________<br>
                <small>{{ now()->format('d M Y') }}</small>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem Manajemen Bumi Sultan</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
