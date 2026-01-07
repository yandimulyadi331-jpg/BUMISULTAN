<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Laporan Keuangan - BUMI SULTAN</title>
    <style>
        @page { margin: 20mm 15mm; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            line-height: 1.4;
        }

        .header {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 15px;
            margin-bottom: 20px;
            text-align: center;
        }

        .company-name {
            font-size: 24pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 2px;
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
            margin: 20px 0 15px 0;
            padding: 15px;
            background: #1e3a8a;
            color: white;
        }

        .title h1 {
            margin: 0;
            font-size: 18pt;
            font-weight: bold;
        }

        .title .subtitle {
            margin: 5px 0 0 0;
            font-size: 11pt;
            font-weight: normal;
        }

        .info-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }

        .info-label {
            display: table-cell;
            width: 140px;
            font-weight: bold;
            color: #475569;
        }

        .info-value {
            display: table-cell;
            color: #1e293b;
        }

        table.summary {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        table.summary thead {
            background: #1e3a8a;
            color: white;
        }

        table.summary th {
            padding: 12px 8px;
            text-align: left;
            font-weight: bold;
            font-size: 10pt;
            border: 1px solid #1e40af;
        }

        table.summary td {
            padding: 10px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: middle;
        }

        table.summary tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        table.summary tbody tr:hover {
            background: #eff6ff;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }

        .amount-in {
            color: #16a34a;
            font-weight: bold;
        }

        .amount-out {
            color: #dc2626;
            font-weight: bold;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 8px;
            font-size: 8pt;
            font-weight: bold;
        }

        .badge-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-danger {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .summary-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .summary-grid {
            display: table;
            width: 100%;
        }

        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            border-right: 1px solid rgba(255,255,255,0.3);
        }

        .summary-item:last-child {
            border-right: none;
        }

        .summary-label {
            font-size: 9pt;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .summary-value {
            font-size: 14pt;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #e2e8f0;
            font-size: 8pt;
            color: #64748b;
            text-align: center;
        }

        .grand-total {
            background: #f1f5f9;
            font-weight: bold;
            font-size: 11pt;
        }

        .highlight-row {
            background: #fffbeb !important;
            border-left: 4px solid #f59e0b;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">BUMI SULTAN</div>
        <div class="company-tagline">Laporan Keuangan Dana Operasional (Ringkasan)</div>
    </div>

    <!-- Title -->
    <div class="title">
        <h1>{{ $periode_label }}</h1>
        <div class="subtitle">Periode: {{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</div>
    </div>

    <!-- Info Box -->
    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Tanggal Cetak:</div>
            <div class="info-value">{{ $tanggal_cetak }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenis Laporan:</div>
            <div class="info-value">Ringkasan Akumulasi Per Bulan</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Bulan:</div>
            <div class="info-value">{{ $ringkasan_per_bulan->count() }} bulan</div>
        </div>
    </div>

    <!-- Summary Card -->
    <div class="summary-card">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Pemasukan</div>
                <div class="summary-value">Rp {{ number_format($grand_total_pemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Pengeluaran</div>
                <div class="summary-value">Rp {{ number_format($grand_total_pengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Selisih</div>
                <div class="summary-value">
                    Rp {{ number_format($grand_total_pemasukan - $grand_total_pengeluaran, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Table Ringkasan -->
    <table class="summary">
        <thead>
            <tr>
                <th style="width: 5%;" class="text-center">No</th>
                <th style="width: 20%;">Bulan</th>
                <th style="width: 10%;" class="text-center">Hari</th>
                <th style="width: 18%;" class="text-right">Saldo Awal</th>
                <th style="width: 18%;" class="text-right">Pemasukan</th>
                <th style="width: 18%;" class="text-right">Pengeluaran</th>
                <th style="width: 18%;" class="text-right">Saldo Akhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($ringkasan_per_bulan as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td><strong>{{ $item->bulan }}</strong></td>
                <td class="text-center">{{ $item->jumlah_hari }}</td>
                <td class="text-right">Rp {{ number_format($item->saldo_awal, 0, ',', '.') }}</td>
                <td class="text-right amount-in">Rp {{ number_format($item->total_pemasukan, 0, ',', '.') }}</td>
                <td class="text-right amount-out">Rp {{ number_format($item->total_pengeluaran, 0, ',', '.') }}</td>
                <td class="text-right">
                    <strong>Rp {{ number_format($item->saldo_akhir, 0, ',', '.') }}</strong>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 30px; color: #64748b;">
                    Tidak ada data ringkasan untuk periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot class="grand-total">
            <tr>
                <td colspan="4" class="text-right" style="padding: 12px;">
                    <strong>GRAND TOTAL:</strong>
                </td>
                <td class="text-right amount-in" style="padding: 12px;">
                    <strong>Rp {{ number_format($grand_total_pemasukan, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right amount-out" style="padding: 12px;">
                    <strong>Rp {{ number_format($grand_total_pengeluaran, 0, ',', '.') }}</strong>
                </td>
                <td class="text-right" style="padding: 12px;">
                    <strong>Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-right" style="padding: 12px; background: #e0f2fe;">
                    <strong>SELISIH (Pemasukan - Pengeluaran):</strong>
                </td>
                <td colspan="3" class="text-right" style="padding: 12px; background: #e0f2fe;">
                    <strong style="font-size: 12pt; color: {{ ($grand_total_pemasukan - $grand_total_pengeluaran) >= 0 ? '#16a34a' : '#dc2626' }};">
                        Rp {{ number_format($grand_total_pemasukan - $grand_total_pengeluaran, 0, ',', '.') }}
                        @if(($grand_total_pemasukan - $grand_total_pengeluaran) >= 0)
                            <span class="badge badge-success">SURPLUS</span>
                        @else
                            <span class="badge badge-danger">DEFISIT</span>
                        @endif
                    </strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- Catatan -->
    <div style="margin-top: 30px; padding: 15px; background: #fef3c7; border-left: 4px solid #f59e0b;">
        <strong style="color: #92400e;">📌 CATATAN:</strong>
        <ul style="margin: 10px 0 0 20px; color: #78350f;">
            <li>Laporan ini menampilkan <strong>ringkasan akumulasi per bulan</strong>, bukan detail transaksi harian</li>
            <li>Data diambil dari saldo harian yang sudah direkonsiliasi</li>
            <li>Untuk melihat detail transaksi, gunakan menu "Export PDF Detail"</li>
        </ul>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p><strong>BUMI SULTAN</strong> - Sistem Manajemen Keuangan Dana Operasional</p>
        <p>Dokumen ini digenerate otomatis oleh sistem | Dicetak pada: {{ $tanggal_cetak }}</p>
    </div>
</body>
</html>
