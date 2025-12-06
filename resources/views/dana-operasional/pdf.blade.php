<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - BUMI SULTAN</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            margin: 15mm 10mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 8pt;
            color: #2c3e50;
            line-height: 1.3;
            background: #ffffff;
        }

        /* Header Section */
        .header-container {
            border-bottom: 3px solid #1e3a8a;
            padding-bottom: 12px;
            margin-bottom: 15px;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            padding: 15px 10px;
        }

        .company-name {
            font-size: 22pt;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 2px;
            text-align: center;
            margin-bottom: 3px;
            text-transform: uppercase;
        }

        .company-tagline {
            font-size: 8pt;
            color: #64748b;
            font-style: italic;
            text-align: center;
            margin-bottom: 8px;
        }

        .company-address {
            font-size: 7pt;
            color: #64748b;
            line-height: 1.4;
            text-align: center;
        }

        /* Document Title */
        .document-title {
            text-align: center;
            margin: 15px 0 12px 0;
            padding: 12px;
            background: #1e3a8a;
            color: white;
        }

        .document-title h1 {
            font-size: 14pt;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .document-subtitle {
            font-size: 8pt;
            opacity: 0.95;
        }

        /* Statement Info */
        .statement-info {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 10px;
            margin-bottom: 15px;
            font-size: 7pt;
        }

        .info-grid {
            display: table;
            width: 100%;
        }

        .info-row {
            display: table-row;
        }

        .info-label {
            display: table-cell;
            font-weight: 600;
            color: #475569;
            padding: 3px 10px 3px 0;
            width: 20%;
        }

        .info-value {
            display: table-cell;
            color: #1e293b;
            padding: 3px 0;
        }

        /* Transaction Table - Full Detail */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 7pt;
        }

        .transaction-table thead {
            background: #1e3a8a;
            color: white;
        }

        .transaction-table th {
            padding: 8px 4px;
            text-align: left;
            font-weight: 600;
            font-size: 7pt;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            border: 1px solid #1e40af;
        }

        .transaction-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .transaction-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .transaction-table td {
            padding: 6px 4px;
            font-size: 7pt;
            color: #334155;
            border-left: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
            vertical-align: top;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .amount-credit {
            color: #16a34a;
            font-weight: 700;
        }

        .amount-debit {
            color: #dc2626;
            font-weight: 700;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 8px;
            font-size: 6pt;
            font-weight: 600;
            text-transform: uppercase;
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

        /* Summary Box */
        .summary-box {
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            border: 2px solid #1e3a8a;
            padding: 12px;
            margin-top: 15px;
            page-break-inside: avoid;
        }

        .summary-title {
            font-size: 10pt;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 5px;
        }

        .summary-grid {
            display: table;
            width: 100%;
            font-size: 8pt;
        }

        .summary-row {
            display: table-row;
        }

        .summary-label {
            display: table-cell;
            font-weight: 600;
            color: #475569;
            padding: 6px 10px 6px 0;
        }

        .summary-value {
            display: table-cell;
            font-weight: 700;
            text-align: right;
            padding: 6px 0;
        }

        .final-balance {
            background: #1e3a8a;
            color: white;
            margin: 10px -12px -12px -12px;
            padding: 12px;
            font-size: 10pt;
            font-weight: 700;
        }

        .final-balance .summary-label,
        .final-balance .summary-value {
            color: white;
            padding: 0;
        }

        /* Footer */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #cbd5e1;
            font-size: 6pt;
            color: #64748b;
        }

        .footer-grid {
            display: table;
            width: 100%;
        }

        .footer-row {
            display: table-row;
        }

        .footer-cell {
            display: table-cell;
            padding: 3px 0;
        }

        .footer-right {
            text-align: right;
        }

        .signature-section {
            margin-top: 25px;
            text-align: right;
            page-break-inside: avoid;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 150px;
        }

        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #334155;
            padding-top: 5px;
            font-weight: 600;
            color: #1e293b;
            font-size: 7pt;
        }

        .currency {
            font-family: 'Courier New', Courier, monospace;
            font-weight: 600;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 120pt;
            color: rgba(30, 58, 138, 0.02);
            font-weight: 700;
            z-index: -1;
        }

        .kode-transaksi {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #1e40af;
            font-size: 7pt;
        }

        .timestamp {
            color: #64748b;
            font-size: 6pt;
        }

        /* Prevent page breaks inside table rows */
        tr {
            page-break-inside: avoid;
        }

        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">BUMI SULTAN</div>

    <!-- Header -->
    <div class="header-container no-break">
        <div class="company-name">BUMI SULTAN</div>
        <div class="company-tagline">Excellence in Financial Management & Transparency</div>
        <div class="company-address">
            <strong>Alamat:</strong> Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830 | 
            <strong>Telp:</strong> +62 xxx-xxxx-xxxx | <strong>Email:</strong> finance@bumisultan.co.id
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title no-break">
        <h1>LAPORAN KEUANGAN</h1>
        <div class="document-subtitle">Detailed Financial Statement Report - All Transactions</div>
    </div>

    <!-- Statement Information -->
    <div class="statement-info no-break">
        <div class="info-grid">
            <div class="info-row">
                <span class="info-label">Periode Laporan:</span>
                <span class="info-value"><strong>{{ $tanggal_dari }}</strong> s/d <strong>{{ $tanggal_sampai }}</strong></span>
                <span class="info-label" style="width: 15%;">Tanggal Cetak:</span>
                <span class="info-value">{{ $tanggal_cetak }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Total Transaksi:</span>
                <span class="info-value"><strong>{{ $total_transaksi }}</strong> transaksi</span>
                <span class="info-label" style="width: 15%;">Nomor Dokumen:</span>
                <span class="info-value"><strong>BS/FIN/{{ date('Y/m/') }}{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</strong></span>
            </div>
        </div>
    </div>

    <!-- Alert Transparansi -->
    <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 8px 10px; margin-bottom: 15px; font-size: 7pt; color: #78350f;" class="no-break">
        <strong>📢 Laporan Transparansi:</strong> Dokumen ini menampilkan SEMUA transaksi keuangan secara detail dan transparan untuk memastikan akuntabilitas penuh. Setiap transaksi mencakup kode unik, tanggal & waktu, kategori, keterangan lengkap, dan jumlah yang akurat.
    </div>

    <!-- Transaction Table - SEMUA Detail -->
    @if($transaksi_detail->count() > 0)
    <table class="transaction-table">
        <thead>
            <tr>
                <th style="width: 3%;" class="text-center">No</th>
                <th style="width: 10%;">Kode Transaksi</th>
                <th style="width: 10%;">Tanggal & Jam</th>
                <th style="width: 10%;">Kategori</th>
                <th style="width: 27%;">Keterangan Lengkap</th>
                <th style="width: 7%;" class="text-center">Tipe</th>
                <th style="width: 13%;" class="text-right">Pemasukan</th>
                <th style="width: 13%;" class="text-right">Pengeluaran</th>
                <th style="width: 10%;">User/PIC</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi_detail as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="kode-transaksi">{{ $item->nomor_realisasi ?? 'N/A' }}</td>
                <td>
                    <strong>{{ \Carbon\Carbon::parse($item->tanggal_realisasi)->format('d/m/Y') }}</strong><br>
                    <span class="timestamp">{{ \Carbon\Carbon::parse($item->created_at)->format('H:i:s') }}</span>
                </td>
                <td>
                    <strong style="color: #1e3a8a;">{{ strtoupper($item->kategori ?? 'Umum') }}</strong>
                </td>
                <td>
                    {{ $item->uraian ?? $item->keterangan ?? '-' }}
                    @if($item->keterangan && $item->uraian != $item->keterangan)
                    <br><span style="color: #64748b; font-style: italic;">Catatan: {{ $item->keterangan }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->tipe_transaksi == 'pemasukan' || $item->tipe_transaksi == 'masuk')
                        <span class="badge badge-in">MASUK</span>
                    @else
                        <span class="badge badge-out">KELUAR</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pemasukan' || $item->tipe_transaksi == 'masuk')
                        <span class="amount-credit currency">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @else
                        <span style="color: #cbd5e1;">-</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pengeluaran' || $item->tipe_transaksi == 'keluar')
                        <span class="amount-debit currency">Rp {{ number_format($item->nominal, 0, ',', '.') }}</span>
                    @else
                        <span style="color: #cbd5e1;">-</span>
                    @endif
                </td>
                <td style="font-size: 6pt;">
                    {{ $item->creator->name ?? 'System' }}<br>
                    <span class="timestamp">ID: {{ $item->created_by ?? '-' }}</span>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot style="background: #f1f5f9; font-weight: 700;">
            <tr>
                <td colspan="6" class="text-right" style="padding: 8px; font-size: 8pt;">
                    <strong>SUBTOTAL:</strong>
                </td>
                <td class="text-right amount-credit" style="padding: 8px;">
                    <span class="currency">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</span>
                </td>
                <td class="text-right amount-debit" style="padding: 8px;">
                    <span class="currency">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</span>
                </td>
                <td></td>
            </tr>
        </tfoot>
    </table>
    @else
    <div style="padding: 30px; text-align: center; background: #f8fafc; border: 2px solid #e2e8f0; border-radius: 4px; margin: 20px 0;">
        <p style="color: #64748b; font-size: 10pt;"><strong>ℹ️ Tidak ada transaksi pada periode ini</strong></p>
        <p style="color: #94a3b8; font-size: 8pt; margin-top: 5px;">Silakan pilih periode lain atau tambahkan transaksi baru.</p>
    </div>
    @endif

    <!-- Summary Box -->
    <div class="summary-box no-break">
        <div class="summary-title">📊 RINGKASAN KEUANGAN</div>
        
        <div class="summary-grid">
            <div class="summary-row">
                <span class="summary-label">Saldo Awal Periode:</span>
                <span class="summary-value currency">Rp {{ number_format($saldo_awal, 0, ',', '.') }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Total Pemasukan (Credit/Dana Masuk):</span>
                <span class="summary-value amount-credit currency">+ Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</span>
            </div>
            
            <div class="summary-row">
                <span class="summary-label">Total Pengeluaran (Debit/Dana Keluar):</span>
                <span class="summary-value amount-debit currency">- Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</span>
            </div>
            
            <div class="summary-row" style="border-top: 2px solid #cbd5e1; padding-top: 8px; margin-top: 8px;">
                <span class="summary-label" style="font-size: 9pt;">Selisih (Pemasukan - Pengeluaran):</span>
                <span class="summary-value" style="font-size: 9pt;">
                    <span class="currency" style="color: {{ ($total_pemasukan - $total_pengeluaran) >= 0 ? '#16a34a' : '#dc2626' }};">
                        Rp {{ number_format($total_pemasukan - $total_pengeluaran, 0, ',', '.') }}
                    </span>
                </span>
            </div>
        </div>
        
        <div class="final-balance">
            <div class="summary-grid">
                <div class="summary-row">
                    <span class="summary-label">💰 SALDO AKHIR PERIODE (FINAL BALANCE):</span>
                    <span class="summary-value currency" style="font-size: 12pt;">Rp {{ number_format($saldo_akhir, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Disclaimer Transparansi -->
    <div style="background: #dbeafe; border: 1px solid #3b82f6; border-radius: 4px; padding: 10px; margin-top: 15px; font-size: 7pt;" class="no-break">
        <strong>🔒 Pernyataan Transparansi & Akuntabilitas:</strong><br>
        Dokumen ini adalah laporan keuangan resmi BUMI SULTAN yang menampilkan SEMUA transaksi secara detail dan transparan. Setiap transaksi telah diverifikasi dan tercatat dengan kode unik untuk audit trail. Laporan ini dibagikan kepada seluruh stakeholder untuk memastikan tidak ada kecurigaan dan menjaga kepercayaan. Segala pertanyaan atau klarifikasi dapat disampaikan kepada tim finance.
    </div>

    <!-- Signature Section -->
    <div class="signature-section no-break">
        <div class="signature-box">
            <p style="margin-bottom: 8px; font-size: 7pt; color: #475569;">Jonggol, {{ date('d F Y') }}</p>
            <p style="font-size: 7pt; color: #64748b; margin-bottom: 5px;">Mengetahui,</p>
            <div class="signature-line">
                <div style="font-size: 8pt;">(__________________)</div>
                <div style="font-size: 7pt; color: #64748b; margin-top: 3px;">Finance Manager</div>
                <div style="font-size: 6pt; color: #94a3b8; margin-top: 2px;">BUMI SULTAN</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer no-break">
        <div class="footer-grid">
            <div class="footer-row">
                <div class="footer-cell"><strong>BUMI SULTAN</strong> - Laporan Keuangan Detail</div>
                <div class="footer-cell footer-right">Halaman 1 dari 1 | Total {{ $total_transaksi }} Transaksi</div>
            </div>
            <div class="footer-row">
                <div class="footer-cell">Dicetak otomatis pada: {{ $tanggal_cetak }} WIB</div>
                <div class="footer-cell footer-right">© {{ date('Y') }} BUMI SULTAN. Confidential - For Internal Use Only</div>
            </div>
            <div class="footer-row">
                <div class="footer-cell" style="padding-top: 5px; color: #94a3b8;">
                    Dokumen ini sah tanpa tanda tangan basah | Verifikasi: {{ md5($tanggal_dari . $tanggal_sampai . date('Ymd')) }}
                </div>
                <div class="footer-cell footer-right" style="padding-top: 5px; color: #94a3b8;">
                    Untuk pertanyaan: finance@bumisultan.co.id
                </div>
            </div>
        </div>
    </div>
</body>
</html>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #2c3e50;
            line-height: 1.4;
            background: #ffffff;
        }

        /* Header Section - Bank Style */
        .header-container {
            border-bottom: 4px solid: #1e3a8a;
            padding-bottom: 20px;
            margin-bottom: 30px;
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            padding: 25px;
        }

        .company-logo {
            text-align: center;
            margin-bottom: 15px;
        }

        .company-name {
            font-size: 28pt;
            font-weight: 700;
            color: #1e3a8a;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .company-tagline {
            font-size: 9pt;
            color: #64748b;
            font-style: italic;
            margin-bottom: 10px;
        }

        .company-address {
            font-size: 8pt;
            color: #64748b;
            line-height: 1.6;
            text-align: center;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Document Title */
        .document-title {
            text-align: center;
            margin: 30px 0 20px 0;
            padding: 15px;
            background: #1e3a8a;
            color: white;
        }

        .document-title h1 {
            font-size: 16pt;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .document-subtitle {
            font-size: 9pt;
            margin-top: 5px;
            opacity: 0.9;
        }

        /* Statement Info Box */
        .statement-info {
            background: #f1f5f9;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .info-row {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
        }

        .info-label {
            font-weight: 600;
            color: #475569;
        }

        .info-value {
            color: #1e293b;
        }

        /* Transaction Table */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
        }

        .transaction-table thead {
            background: #1e3a8a;
            color: white;
        }

        .transaction-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #1e40af;
        }

        .transaction-table tbody tr {
            border-bottom: 1px solid #e2e8f0;
        }

        .transaction-table td {
            padding: 10px 8px;
            font-size: 9pt;
            color: #334155;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .amount-credit {
            color: #16a34a;
            font-weight: 600;
        }

        .amount-debit {
            color: #dc2626;
            font-weight: 600;
        }

        /* Summary Box */
        .summary-box {
            background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
            border: 2px solid #1e3a8a;
            border-radius: 6px;
            padding: 20px;
            margin-top: 30px;
        }

        .summary-title {
            font-size: 12pt;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .summary-label {
            font-weight: 600;
            color: #475569;
        }

        .summary-value {
            font-weight: 700;
            font-size: 11pt;
        }

        .final-balance {
            background: #1e3a8a;
            color: white;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 4px 4px;
            font-size: 12pt;
            font-weight: 700;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #cbd5e1;
            font-size: 8pt;
            color: #64748b;
        }

        .signature-section {
            margin-top: 40px;
            text-align: right;
        }

        .signature-box {
            display: inline-block;
            text-align: center;
            min-width: 200px;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #334155;
            padding-top: 8px;
            font-weight: 600;
            color: #1e293b;
        }

        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
        }

        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72pt;
            color: rgba(30, 58, 138, 0.03);
            font-weight: 700;
            z-index: -1;
        }
    </style>
</head>
<body>
    <!-- Watermark -->
    <div class="watermark">BUMI SULTAN</div>

    <!-- Header -->
    <div class="header-container">
        <div class="company-logo">
            <div class="company-name">BUMI SULTAN</div>
            <div class="company-tagline">Excellence in Financial Management</div>
        </div>
        <div class="company-address">
            <strong>Alamat:</strong> Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol,<br>
            Kabupaten Bogor, Jawa Barat 16830
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">
        <h1>Laporan Dana Operasional Harian</h1>
        <div class="document-subtitle">Daily Operational Fund Statement</div>
    </div>

    <!-- Statement Information -->
    <div class="statement-info">
        <div class="info-row">
            <span class="info-label">Periode Laporan:</span>
            <span class="info-value">{{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Tanggal Cetak:</span>
            <span class="info-value">{{ $tanggal_cetak }}</span>
        </div>

