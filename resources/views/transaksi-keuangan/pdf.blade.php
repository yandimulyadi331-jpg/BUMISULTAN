<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Keuangan - BUMI SULTAN</title>
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
            border-bottom: 4px solid #1e3a8a;
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

        .company-contact {
            font-size: 8pt;
            color: #64748b;
            text-align: center;
            margin-top: 8px;
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
            padding: 5px 15px 5px 0;
            width: 150px;
        }

        .info-value {
            display: table-cell;
            color: #1e293b;
            padding: 5px 0;
        }

        /* Transaction Table - Bank Statement Style */
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            background: white;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
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

        .transaction-table tbody tr:hover {
            background: #f8fafc;
        }

        .transaction-table tbody tr:last-child {
            border-bottom: 2px solid #cbd5e1;
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

        /* Amount Styling */
        .amount-credit {
            color: #16a34a;
            font-weight: 600;
        }

        .amount-debit {
            color: #dc2626;
            font-weight: 600;
        }

        .amount-neutral {
            color: #64748b;
        }

        /* Summary Box - Bank Style */
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

        .summary-row:last-child {
            border-bottom: none;
            background: #1e3a8a;
            color: white;
            margin: 10px -20px -20px -20px;
            padding: 15px 20px;
            border-radius: 0 0 4px 4px;
            font-size: 12pt;
            font-weight: 700;
        }

        .summary-label {
            font-weight: 600;
            color: #475569;
        }

        .summary-row:last-child .summary-label {
            color: white;
        }

        .summary-value {
            font-weight: 700;
            font-size: 11pt;
        }

        .summary-row:last-child .summary-value {
            color: white;
            font-size: 13pt;
        }

        /* Badge Styling */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-credit {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #86efac;
        }

        .badge-debit {
            background: #fee2e2;
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Footer */
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #cbd5e1;
            font-size: 8pt;
            color: #64748b;
        }

        .footer-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .disclaimer {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 12px;
            margin-top: 20px;
            font-size: 8pt;
            color: #78350f;
            line-height: 1.5;
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

        .signature-title {
            font-size: 8pt;
            color: #64748b;
            margin-top: 4px;
        }

        /* Page Break */
        .page-break {
            page-break-after: always;
        }

        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 72pt;
            color: rgba(30, 58, 138, 0.03);
            font-weight: 700;
            z-index: -1;
            pointer-events: none;
        }

        /* Number Formatting */
        .currency {
            font-family: 'Courier New', monospace;
            font-weight: 600;
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
        <div class="company-contact">
            <strong>Contact:</strong> info@bumisultan.co.id | <strong>Phone:</strong> +62 xxx-xxxx-xxxx
        </div>
    </div>

    <!-- Document Title -->
    <div class="document-title">
        <h1>Laporan Transaksi Keuangan</h1>
        <div class="document-subtitle">Financial Transaction Statement</div>
    </div>

    <!-- Statement Information -->
    <div class="statement-info">
        <div class="info-row">
            <div class="info-label">Periode Laporan:</div>
            <div class="info-value">{{ $tanggal_dari }} s/d {{ $tanggal_sampai }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Cetak:</div>
            <div class="info-value">{{ $tanggal_cetak }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Total Transaksi:</div>
            <div class="info-value">{{ $transaksi->count() }} transaksi</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nomor Dokumen:</div>
            <div class="info-value">BS/FIN/{{ date('Y/m/') }}{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</div>
        </div>
    </div>

    <!-- Transaction Table -->
    @if($transaksi->count() > 0)
    <table class="transaction-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 20%;">Tukang</th>
                <th style="width: 25%;">Keterangan</th>
                <th style="width: 10%;" class="text-center">Tipe</th>
                <th style="width: 15%;" class="text-right">Pemasukan</th>
                <th style="width: 15%;" class="text-right">Pengeluaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                <td>
                    <strong>{{ $item->tukang->nama_tukang ?? '-' }}</strong><br>
                    <span style="font-size: 8pt; color: #64748b;">{{ $item->tukang->kode_tukang ?? '-' }}</span>
                </td>
                <td>
                    {{ $item->keterangan ?? '-' }}<br>
                    @if($item->user)
                    <span style="font-size: 8pt; color: #64748b;">oleh: {{ $item->user->name }}</span>
                    @endif
                </td>
                <td class="text-center">
                    @if($item->tipe_transaksi == 'pemasukan')
                        <span class="badge badge-credit">Credit</span>
                    @else
                        <span class="badge badge-debit">Debit</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pemasukan')
                        <span class="amount-credit currency">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    @else
                        <span class="amount-neutral">-</span>
                    @endif
                </td>
                <td class="text-right">
                    @if($item->tipe_transaksi == 'pengeluaran')
                        <span class="amount-debit currency">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</span>
                    @else
                        <span class="amount-neutral">-</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="padding: 40px; text-align: center; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;">
        <p style="color: #64748b; font-size: 11pt;">Tidak ada transaksi pada periode ini</p>
    </div>
    @endif

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-title">Ringkasan Keuangan</div>
        
        <div class="summary-row">
            <div class="summary-label">Total Pemasukan (Credit):</div>
            <div class="summary-value amount-credit currency">Rp {{ number_format($total_pemasukan, 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-row">
            <div class="summary-label">Total Pengeluaran (Debit):</div>
            <div class="summary-value amount-debit currency">Rp {{ number_format($total_pengeluaran, 0, ',', '.') }}</div>
        </div>
        
        <div class="summary-row">
            <div class="summary-label">Saldo Akhir (Net Balance):</div>
            <div class="summary-value currency">
                Rp {{ number_format($saldo, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div class="disclaimer">
        <strong>⚠️ Penting:</strong> Dokumen ini adalah laporan resmi transaksi keuangan BUMI SULTAN. 
        Semua informasi yang tercantum dalam dokumen ini bersifat rahasia dan hanya untuk keperluan internal. 
        Harap simpan dokumen ini dengan aman dan jangan disebarkan tanpa izin.
    </div>

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-box">
            <p style="margin-bottom: 10px; font-size: 9pt; color: #475569;">Jonggol, {{ date('d F Y') }}</p>
            <div class="signature-line">
                <div>(__________________)</div>
                <div class="signature-title">Finance Manager</div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-row">
            <div>
                <strong>BUMI SULTAN</strong> - Financial Management System
            </div>
            <div>
                Halaman 1 dari 1
            </div>
        </div>
        <div class="footer-row">
            <div>
                Dokumen ini dicetak secara otomatis pada {{ $tanggal_cetak }}
            </div>
            <div>
                © {{ date('Y') }} BUMI SULTAN. All Rights Reserved.
            </div>
        </div>
    </div>
</body>
</html>
