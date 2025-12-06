<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan Santri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        
        .header {
            border-bottom: 3px solid #2563EB;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .header-top {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .logo {
            display: table-cell;
            width: 30%;
            vertical-align: middle;
        }
        
        .logo h1 {
            color: #2563EB;
            font-size: 24pt;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .logo p {
            color: #666;
            font-size: 8pt;
            line-height: 1.4;
        }
        
        .header-info {
            display: table-cell;
            width: 70%;
            text-align: right;
            vertical-align: middle;
        }
        
        .statement-title {
            background-color: #2563EB;
            color: white;
            padding: 10px 15px;
            margin: 20px 0;
            font-size: 14pt;
            font-weight: bold;
        }
        
        .account-info {
            background-color: #F3F4F6;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #2563EB;
        }
        
        .account-info table {
            width: 100%;
        }
        
        .account-info td {
            padding: 5px 0;
        }
        
        .account-info td:first-child {
            width: 150px;
            font-weight: bold;
            color: #555;
        }
        
        .summary-box {
            background-color: #EFF6FF;
            border: 2px solid #2563EB;
            padding: 15px;
            margin: 20px 0;
        }
        
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
        }
        
        .summary-item .label {
            color: #666;
            font-size: 9pt;
            margin-bottom: 5px;
        }
        
        .summary-item .value {
            font-size: 14pt;
            font-weight: bold;
        }
        
        .summary-item.credit .value {
            color: #059669;
        }
        
        .summary-item.debit .value {
            color: #DC2626;
        }
        
        .summary-item.balance .value {
            color: #2563EB;
        }
        
        .transaction-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        
        .transaction-table thead {
            background-color: #F3F4F6;
        }
        
        .transaction-table th {
            padding: 12px 8px;
            text-align: left;
            font-size: 9pt;
            font-weight: bold;
            color: #374151;
            border-bottom: 2px solid #2563EB;
        }
        
        .transaction-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 9pt;
        }
        
        .transaction-table tbody tr:hover {
            background-color: #F9FAFB;
        }
        
        .amount-credit {
            color: #059669;
            font-weight: bold;
        }
        
        .amount-debit {
            color: #DC2626;
            font-weight: bold;
        }
        
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 8pt;
            font-weight: bold;
        }
        
        .badge-credit {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-debit {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        
        .badge-verified {
            background-color: #DBEAFE;
            color: #1E40AF;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #E5E7EB;
            text-align: center;
            font-size: 8pt;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        @page {
            margin: 25mm 15mm;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-top">
            <div class="logo">
                <h1>SAUNG SANTRI</h1>
                <p>Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol<br>
                Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830</p>
            </div>
            <div class="header-info">
                <p><strong>Tanggal Cetak:</strong> {{ now()->format('d/m/Y H:i') }}</p>
                <p><strong>Periode:</strong> 
                    @if(isset($filters['start_date']) && isset($filters['end_date']))
                        {{ \Carbon\Carbon::parse($filters['start_date'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($filters['end_date'])->format('d/m/Y') }}
                    @else
                        Semua Periode
                    @endif
                </p>
            </div>
        </div>
    </div>

    <!-- Statement Title -->
    <div class="statement-title">
        LAPORAN REKENING KEUANGAN SANTRI
    </div>

    <!-- Account Info -->
    @if($santri)
    <div class="account-info">
        <table>
            <tr>
                <td>Nama Santri</td>
                <td>: {{ $santri->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>NIK</td>
                <td>: {{ $santri->id }}</td>
            </tr>
            @if($saldo)
            <tr>
                <td>Saldo Awal</td>
                <td>: Rp {{ number_format($saldo->saldo_awal, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Saldo Akhir</td>
                <td>: <strong>Rp {{ number_format($saldo->saldo_akhir, 0, ',', '.') }}</strong></td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    <!-- Summary Box -->
    <div class="summary-box">
        <div class="summary-row">
            <div class="summary-item credit">
                <div class="label">TOTAL PEMASUKAN</div>
                <div class="value">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item debit">
                <div class="label">TOTAL PENGELUARAN</div>
                <div class="value">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item balance">
                <div class="label">SELISIH</div>
                <div class="value">Rp {{ number_format($totalPemasukan - $totalPengeluaran, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <table class="transaction-table">
        <thead>
            <tr>
                <th style="width: 8%;">Tanggal</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 25%;">Deskripsi</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 8%;">Jenis</th>
                <th style="width: 12%;" align="right">Jumlah</th>
                <th style="width: 12%;" align="right">Saldo</th>
                <th style="width: 8%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr>
                <td>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</td>
                <td>{{ $transaction->kode_transaksi }}</td>
                <td>{{ $transaction->deskripsi }}</td>
                <td>{{ $transaction->category->nama_kategori ?? '-' }}</td>
                <td>
                    @if($transaction->jenis == 'pemasukan')
                        <span class="badge badge-credit">KREDIT</span>
                    @else
                        <span class="badge badge-debit">DEBIT</span>
                    @endif
                </td>
                <td align="right">
                    <span class="{{ $transaction->jenis == 'pemasukan' ? 'amount-credit' : 'amount-debit' }}">
                        {{ $transaction->jenis == 'pemasukan' ? '+' : '-' }} 
                        Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                    </span>
                </td>
                <td align="right">
                    Rp {{ number_format($transaction->saldo_sesudah, 0, ',', '.') }}
                </td>
                <td>
                    @if($transaction->is_verified)
                        <span class="badge badge-verified">✓</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                    Tidak ada transaksi dalam periode ini
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
        <p><strong>BUMI SULTAN - Sistem Keuangan Santri</strong></p>
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem</p>
        <p style="margin-top: 10px;">
            <em>Disclaimer: Laporan ini merupakan rekap resmi transaksi keuangan santri. 
            Harap simpan sebagai bukti pencatatan.</em>
        </p>
    </div>
</body>
</html>
