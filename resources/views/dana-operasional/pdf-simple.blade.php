<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan - BUMI SULTAN</title>
    <style>
        @page { margin: 15mm 10mm; }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 8pt;
            color: #2c3e50;
            line-height: 1.3;
        }

        .header {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 15px;
            text-align: center;
        }

        .company-name {
            font-size: 22pt;
            font-weight: bold;
            color: #1e3a8a;
            letter-spacing: 2px;
            margin-bottom: 5px;
        }

        .company-tagline {
            font-size: 8pt;
            color: #64748b;
            font-style: italic;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 7pt;
            color: #64748b;
        }

        .title {
            text-align: center;
            margin: 15px 0 12px 0;
            padding: 12px;
            background: #1e3a8a;
            color: white;
        }

        .title h1 {
            font-size: 14pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
        }

        .title-sub {
            font-size: 8pt;
            margin-top: 3px;
        }

        .info-box {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 7pt;
        }

        .info-table {
            width: 100%;
            border: none;
        }

        .info-table td {
            padding: 3px 5px;
            border: none;
        }

        .info-label {
            font-weight: bold;
            color: #475569;
        }

        .alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 8px 10px;
            margin-bottom: 15px;
            font-size: 7pt;
            color: #78350f;
        }

        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 7pt;
        }

        table.data thead {
            background: #1e3a8a;
            color: white;
        }

        table.data th {
            padding: 8px 4px;
            text-align: left;
            font-weight: bold;
            font-size: 7pt;
            border: 1px solid #1e40af;
        }

        table.data td {
            padding: 6px 4px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }

        table.data tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .amount-in {
            color: #16a34a;
            font-weight: bold;
        }

        .amount-out {
            color: #dc2626;
            font-weight: bold;
        }

        .badge {
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 6pt;
            font-weight: bold;
        }

        .badge-in {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-out {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        .summary {
            background: #f8fafc;
            border: 2px solid #1e3a8a;
            padding: 12px;
            margin-top: 15px;
        }

        .summary-title {
            font-size: 10pt;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 5px;
        }

        .summary-table {
            width: 100%;
            font-size: 8pt;
        }

        .summary-table td {
            padding: 6px 0;
            border: none;
        }

        .summary-label {
            font-weight: bold;
            color: #475569;
        }

        .summary-value {
            text-align: right;
            font-weight: bold;
        }

        .final {
            background: #1e3a8a;
            color: white;
            padding: 12px;
            margin: 10px -12px -12px -12px;
            font-size: 10pt;
            font-weight: bold;
        }

        .signature {
            margin-top: 25px;
            text-align: right;
        }

        .sig-box {
            display: inline-block;
            text-align: center;
            min-width: 150px;
        }

        .sig-line {
            margin-top: 50px;
            border-top: 1px solid #334155;
            padding-top: 5px;
            font-weight: bold;
            font-size: 7pt;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #cbd5e1;
            font-size: 6pt;
            color: #64748b;
        }

        .currency { font-family: 'Courier New', monospace; font-weight: bold; }
        .kode { font-family: 'Courier New', monospace; font-weight: bold; color: #1e40af; }
        .timestamp { color: #64748b; font-size: 6pt; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">BUMI SULTAN</div>
        <div class="company-tagline">Excellence in Financial Management & Transparency</div>
        <div class="company-address">
            <strong>Alamat:</strong> Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <!-- Title -->
    <div class="title">
        <h1>LAPORAN KEUANGAN</h1>
        <div class="title-sub">Detailed Financial Statement Report - All Transactions</div>
    </div>

    <!-- Info -->
    <div class="info-box">
        <table class="info-table">
            <tr>
                <td class="info-label" style="width: 18%;">Periode Laporan:</td>
                <td style="width: 32%;"><strong>{{ $tanggal_dari }}</strong> s/d <strong>{{ $tanggal_sampai }}</strong></td>
                <td class="info-label" style="width: 15%;">Tanggal Cetak:</td>
                <td style="width: 35%;">{{ $tanggal_cetak }}</td>
            </tr>
            <tr>
                <td class="info-label">Total Transaksi:</td>
                <td><strong>{{ $total_transaksi }}</strong> transaksi</td>
                <td class="info-label">Nomor Dokumen:</td>
                <td><strong>BS/FIN/{{ date('Y/m/') }}{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Alert -->
    <div class="alert">
        <strong>📢 Laporan Transparansi:</strong> Dokumen ini menampilkan SEMUA transaksi keuangan secara detail dan transparan untuk memastikan akuntabilitas penuh.
    </div>

    <!-- Transactions -->
    @if($transaksi_detail->count() > 0)
    <table class="data">
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 12%;">Kode Transaksi</th>
                <th style="width: 11%;">Tanggal & Jam</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 35%;">Keterangan Lengkap</th>
                <th style="width: 13%;" class="text-right">CR (Credit)</th>
                <th style="width: 13%;" class="text-right">DB (Debit)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi_detail as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="kode">{{ $item->nomor_realisasi ?? 'N/A' }}</td>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($item->tanggal_realisasi)->format('d/m/Y') }}</strong><br>
                    <span class="timestamp">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</span>
                </td>
                <td><strong style="color: #1e3a8a;">{{ strtoupper($item->kategori ?? 'Umum') }}</strong></td>
                <td>
                    {{ $item->uraian ?? $item->keterangan ?? '-' }}
                    @if($item->keterangan && $item->uraian != $item->keterangan)
                    <br><span style="color: #64748b; font-style: italic;">Catatan: {{ $item->keterangan }}</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pemasukan' || $item->tipe_transaksi == 'masuk')
                        <span class="amount-in currency">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @else
                        <span style="color: #cbd5e1;">-</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pengeluaran' || $item->tipe_transaksi == 'keluar')
                        <span class="amount-out currency">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @else
                        <span style="color: #cbd5e1;">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background: #f1f5f9; font-weight: bold;">
            <tr>
                <td colspan="5" class="text-right" style="padding: 8px; font-size: 8pt;">
                    <strong>SUBTOTAL:</strong>
                </td>
                <td class="text-right amount-in" style="padding: 8px;">
                    <span class="currency">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</span>
                </td>
                <td class="text-right amount-out" style="padding: 8px;">
                    <span class="currency">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</span>
                </td>
            </tr>
        </tfoot>
    </table>
    @else
    <div style="padding: 30px; text-align: center; background: #f8fafc; border: 2px solid #e2e8f0; margin: 20px 0;">
        <p style="color: #64748b; font-size: 10pt;"><strong>Tidak ada transaksi pada periode ini</strong></p>
    </div>
    @endif

    <!-- Summary -->
    <div class="summary">
        <div class="summary-title">RINGKASAN KEUANGAN</div>
        
        <table class="summary-table">
            <tr>
                <td class="summary-label">Saldo Awal Periode:</td>
                <td class="summary-value currency">Rp {{ number_format($saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Pemasukan (Credit/Dana Masuk):</td>
                <td class="summary-value amount-in currency">+ Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td class="summary-label">Total Pengeluaran (Debit/Dana Keluar):</td>
                <td class="summary-value amount-out currency">- Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</td>
            </tr>
            <tr style="border-top: 2px solid #cbd5e1;">
                <td class="summary-label" style="padding-top: 8px; font-size: 9pt;">Selisih (Pemasukan - Pengeluaran):</td>
                <td class="summary-value" style="padding-top: 8px; font-size: 9pt;">
                    <span class="currency" style="color: {{ ($total_pemasukan - $total_pengeluaran) >= 0 ? '#16a34a' : '#dc2626' }};">
                        Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}
                    </span>
                </td>
            </tr>
        </table>
        
        <div class="final">
            <table style="width: 100%;">
                <tr>
                    <td style="color: white;">SALDO AKHIR PERIODE (FINAL BALANCE):</td>
                    <td class="text-right currency" style="font-size: 12pt; color: white;">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Disclaimer -->
    <div style="background: #dbeafe; border: 1px solid #3b82f6; padding: 10px; margin-top: 15px; font-size: 7pt;">
        <strong>Pernyataan Transparansi:</strong> Dokumen ini adalah laporan keuangan resmi BUMI SULTAN yang menampilkan SEMUA transaksi secara detail dan transparan. Setiap transaksi telah diverifikasi dan tercatat dengan kode unik untuk audit trail.
    </div>

    <!-- Signature -->
    <div class="signature">
        <div class="sig-box">
            <p style="margin-bottom: 8px; font-size: 7pt;">Jonggol, {{ date('d F Y') }}</p>
            <p style="font-size: 7pt; color: #64748b; margin-bottom: 5px;">Mengetahui,</p>
            <div class="sig-line">
                <div style="font-size: 8pt;">(__________________)</div>
                <div style="font-size: 7pt; color: #64748b; margin-top: 3px;">Finance Manager</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <table style="width: 100%;">
            <tr>
                <td><strong>BUMI SULTAN</strong> - Laporan Keuangan Detail</td>
                <td class="text-right">Halaman 1 | Total {{ $total_transaksi }} Transaksi</td>
            </tr>
            <tr>
                <td>Dicetak: {{ $tanggal_cetak }} WIB</td>
                <td class="text-right">© {{ date('Y') }} BUMI SULTAN. Confidential</td>
            </tr>
        </table>
    </div>
</body>
</html>
