<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan Tahunan {{ $tahun }} - BUMI SULTAN</title>
    <style>
        @page { margin: 20mm 15mm; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            line-height: 1.4;
        }

        .header {
            border-bottom: 4px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .company-name {
            font-size: 26pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 3px;
            margin-bottom: 8px;
        }

        .company-tagline {
            font-size: 9pt;
            color: #64748b;
            font-style: italic;
            margin-bottom: 10px;
        }

        .title {
            text-align: center;
            margin: 20px 0;
            padding: 15px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            border-radius: 5px;
        }

        .title h1 {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }

        .title-sub {
            font-size: 10pt;
            margin-top: 5px;
            opacity: 0.9;
        }

        .info-box {
            background: #f8fafc;
            border: 2px solid #cbd5e1;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .info-table {
            width: 100%;
        }

        .info-table td {
            padding: 5px;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
            width: 35%;
        }

        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table.summary th {
            background: #1e3a8a;
            color: white;
            padding: 12px;
            text-align: center;
            font-size: 10pt;
            border: 1px solid #0f172a;
        }

        table.summary td {
            padding: 10px;
            border: 1px solid #cbd5e1;
            text-align: center;
            font-size: 9pt;
        }

        table.summary tr:nth-child(even) {
            background: #f8fafc;
        }

        table.summary tr:hover {
            background: #e0f2fe;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .amount-in {
            color: #16a34a;
            font-weight: bold;
        }

        .amount-out {
            color: #dc2626;
            font-weight: bold;
        }

        .amount-neutral {
            color: #0284c7;
            font-weight: bold;
        }

        tfoot {
            background: #f1f5f9;
            font-weight: bold;
            font-size: 11pt;
        }

        tfoot td {
            padding: 15px 10px !important;
            border-top: 3px solid #1e3a8a !important;
        }

        .grand-total {
            background: #1e3a8a !important;
            color: white !important;
            font-size: 12pt;
            padding: 18px 10px !important;
        }

        .summary-box {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 100%);
            color: white;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }

        .summary-box h3 {
            margin: 0 0 15px 0;
            font-size: 14pt;
            text-align: center;
            border-bottom: 2px solid white;
            padding-bottom: 10px;
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-row;
        }

        .summary-item div {
            display: table-cell;
            padding: 8px 0;
            font-size: 11pt;
        }

        .summary-item .label {
            width: 60%;
        }

        .summary-item .value {
            text-align: right;
            font-weight: bold;
            font-size: 13pt;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #cbd5e1;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
            padding-right: 50px;
        }

        .sig-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .sig-line {
            margin-top: 70px;
            border-top: 2px solid #334155;
            padding-top: 8px;
            font-weight: bold;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80pt;
            color: rgba(30, 58, 138, 0.05);
            font-weight: bold;
            z-index: -1;
        }
    </style>
</head>
<body>
    <div class="watermark">BUMI SULTAN</div>

    <!-- Header -->
    <div class="header">
        <div class="company-name">BUMI SULTAN</div>
        <div class="company-tagline">Laporan Keuangan Tahunan - Financial Annual Report</div>
        <div style="font-size: 8pt; color: #64748b; margin-top: 5px;">
            Jl. Raya Jonggol No.37, Jonggol, Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        <h1>LAPORAN KEUANGAN TAHUNAN</h1>
        <div class="title-sub">Tahun {{ $tahun }} - Summary per Bulan</div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="info-label">Periode Laporan:</td>
                <td><strong>{{ $periode_label }}</strong></td>
                <td class="info-label">Tanggal Cetak:</td>
                <td>{{ $tanggal_cetak }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Transaksi:</td>
                <td><strong>{{ number_format($total_transaksi, 0, ',', '.') }}</strong> transaksi</td>
                <td class="info-label">Nomor Dokumen:</td>
                <td><strong>BS/FIN/{{ $tahun }}/ANNUAL</strong></td>
            </tr>
        </table>
    </div>

    <!-- Summary Table -->
    <table class="summary">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Bulan</th>
                <th style="width: 12%;">Transaksi</th>
                <th style="width: 18%;">Pemasukan (Rp)</th>
                <th style="width: 18%;">Pengeluaran (Rp)</th>
                <th style="width: 15%;">Selisih (Rp)</th>
                <th style="width: 18%;">Saldo Akhir (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summary_per_bulan as $index => $bulan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: bold; padding-left: 15px;">
                    {{ strtoupper($bulan['nama_bulan']) }} {{ $tahun }}
                </td>
                <td>{{ number_format($bulan['jumlah_transaksi'], 0, ',', '.') }}</td>
                <td class="text-right amount-in">
                    {{ number_format($bulan['pemasukan'], 0, ',', '.') }}
                </td>
                <td class="text-right amount-out">
                    {{ number_format($bulan['pengeluaran'], 0, ',', '.') }}
                </td>
                <td class="text-right {{ $bulan['selisih'] >= 0 ? 'amount-in' : 'amount-out' }}">
                    {{ $bulan['selisih'] >= 0 ? '+' : '' }}{{ number_format($bulan['selisih'], 0, ',', '.') }}
                </td>
                <td class="text-right amount-neutral">
                    {{ number_format($bulan['saldo_akhir'], 0, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right"><strong>TOTAL TAHUN {{ $tahun }}:</strong></td>
                <td><strong>{{ number_format($total_transaksi, 0, ',', '.') }}</strong></td>
                <td class="text-right amount-in">
                    <strong>{{ number_format($total_pemasukan, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right amount-out">
                    <strong>{{ number_format($total_pengeluaran, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right {{ ($total_pemasukan - $total_pengeluaran) >= 0 ? 'amount-in' : 'amount-out' }}">
                    <strong>{{ ($total_pemasukan - $total_pengeluaran) >= 0 ? '+' : '' }}{{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right amount-neutral">
                    <strong>{{ number_format($saldo_akhir_tahun, 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr class="grand-total">
                <td colspan="6" class="text-right" style="font-size: 13pt;">
                    <strong>SALDO AKHIR TAHUN {{ $tahun }}:</strong>
                </td>
                <td class="text-right" style="font-size: 15pt;">
                    <strong>Rp {{ number_format($saldo_akhir_tahun, 0, ',', '.') }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Summary Box -->
    <div class="summary-box">
        <h3>RINGKASAN KEUANGAN TAHUNAN {{ $tahun }}</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <div class="label">Saldo Awal Tahun:</div>
                <div class="value">Rp {{ number_format($saldo_awal_tahun, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pemasukan Tahun {{ $tahun }}:</div>
                <div class="value" style="color: #4ade80;">+ Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="label">Total Pengeluaran Tahun {{ $tahun }}:</div>
                <div class="value" style="color: #f87171;">- Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item" style="border-top: 2px solid white; margin-top: 10px; padding-top: 15px;">
                <div class="label" style="font-size: 13pt;">Selisih (Net Income):</div>
                <div class="value" style="font-size: 15pt; color: {{ ($total_pemasukan - $total_pengeluaran) >= 0 ? '#4ade80' : '#f87171' }};">
                    {{ ($total_pemasukan - $total_pengeluaran) >= 0 ? '+' : '' }} Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}
                </div>
            </div>
            <div class="summary-item" style="border-top: 3px solid white; margin-top: 15px; padding-top: 15px;">
                <div class="label" style="font-size: 14pt;">Saldo Akhir Tahun {{ $tahun }}:</div>
                <div class="value" style="font-size: 16pt; color: #60a5fa;">
                    Rp {{ number_format($saldo_akhir_tahun, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div style="background: #dbeafe; border: 1px solid #3b82f6; padding: 12px; margin: 20px 0; font-size: 9pt; border-radius: 5px;">
        <strong>📊 Catatan:</strong> Laporan ini merupakan ringkasan keuangan per bulan untuk tahun {{ $tahun }}. 
        Untuk melihat detail transaksi per tanggal, silakan export laporan bulanan atau gunakan filter custom range.
    </div>

    <!-- Signature -->
    <div class="signature">
        <div class="sig-box">
            <p style="margin: 0; color: #64748b;">Jonggol, {{ date('d F Y') }}</p>
            <p style="margin: 5px 0 0 0; font-weight: bold;">Mengetahui,</p>
            <div class="sig-line">
                Bagian Keuangan
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <strong>BUMI SULTAN</strong> - Laporan Keuangan Tahunan {{ $tahun }}<br>
        Dicetak: {{ $tanggal_cetak }} WIB | Total {{ $total_transaksi }} Transaksi<br>
        © {{ date('Y') }} BUMI SULTAN. Confidential Document.
    </div>
</body>
</html>
