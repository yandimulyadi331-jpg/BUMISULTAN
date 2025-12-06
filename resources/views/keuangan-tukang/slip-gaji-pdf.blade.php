<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Slip Gaji - {{ $tukang->kode_tukang }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            font-size: 11px;
            color: #666;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            font-weight: bold;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .nett-row {
            font-weight: bold;
            background-color: #e8f5e9;
            font-size: 14px;
        }
        .signature-section {
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            display: table;
            width: 100%;
        }
        .signature-col {
            display: table-cell;
            width: 48%;
            text-align: center;
            vertical-align: top;
        }
        .signature-col:first-child {
            margin-right: 4%;
        }
        .signature-label {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .signature-img {
            border: 2px solid #333;
            padding: 5px;
            margin: 10px auto;
            max-width: 200px;
            height: auto;
            display: block;
            background-color: #fff;
        }
        .signature-name {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 200px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-lunas {
            background-color: #4caf50;
            color: white;
        }
        .status-pending {
            background-color: #ff9800;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .potongan-detail {
            font-size: 11px;
            color: #666;
            margin-top: 3px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>BUMI SULTAN</h1>
        <p style="font-size: 10px; margin: 2px 0;">Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol</p>
        <p style="font-size: 10px; margin: 2px 0;">Kabupaten Bogor, Jawa Barat 16830</p>
        <p style="font-size: 12px; margin-top: 8px; font-weight: bold;">SLIP PEMBAYARAN GAJI TUKANG DAN KENEK</p>
        <p style="font-size: 11px;">Periode: {{ $periode }}</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">Kode Tukang</div>
            <div class="info-value">: {{ $tukang->kode_tukang }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div class="info-value">: {{ $tukang->nama_tukang }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Cetak</div>
            <div class="info-value">: {{ \Carbon\Carbon::now()->format('d M Y H:i') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th class="text-right" style="width: 150px;">Jumlah (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <!-- PENDAPATAN -->
            <tr>
                <td><strong>PENDAPATAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td>Upah Harian</td>
                <td class="text-right">Rp {{ number_format($upah_harian, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Upah Lembur</td>
                <td class="text-right">Rp {{ number_format($lembur, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL PENDAPATAN</td>
                <td class="text-right">Rp {{ number_format($upah_harian + $lembur, 0, ',', '.') }}</td>
            </tr>

            <!-- POTONGAN -->
            <tr>
                <td colspan="2" style="height: 15px;"></td>
            </tr>
            <tr>
                <td><strong>POTONGAN</strong></td>
                <td></td>
            </tr>
            <tr>
                <td>Potongan Lain-lain</td>
                <td class="text-right">Rp {{ number_format($potongan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Cicilan Pinjaman</td>
                <td class="text-right">Rp {{ number_format($cicilan, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td>TOTAL POTONGAN</td>
                <td class="text-right">Rp {{ number_format($potongan + $cicilan, 0, ',', '.') }}</td>
            </tr>

            <!-- NETT -->
            <tr>
                <td colspan="2" style="height: 15px;"></td>
            </tr>
            <tr class="nett-row">
                <td>GAJI BERSIH (NETT)</td>
                <td class="text-right">Rp {{ number_format($total_bersih, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-col">
                <div class="signature-label">Penerima,</div>
                <div class="signature-name">
                    <strong>{{ $tukang->nama_tukang }}</strong><br>
                    <small>{{ $tukang->kode_tukang }}</small>
                </div>
            </div>
            <div class="signature-col">
                <div class="signature-label">Petugas,</div>
                <div class="signature-name">
                    <strong>_____________________</strong><br>
                    <small>Divisi Keuangan</small>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dari sistem Manajemen Bumi Sultan</p>
        <p>Dicetak pada: {{ now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>
