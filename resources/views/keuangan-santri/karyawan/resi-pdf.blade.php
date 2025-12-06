<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi Keuangan Santri - {{ $santri->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid #667eea;
            border-radius: 10px;
            overflow: hidden;
        }
        
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        .resi-number {
            background: white;
            color: #667eea;
            padding: 8px 20px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
        }
        
        .content {
            padding: 30px;
        }
        
        .section {
            margin-bottom: 25px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #667eea;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e9ecef;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .info-table tr {
            border-bottom: 1px solid #f1f3f5;
        }
        
        .info-table tr:last-child {
            border-bottom: none;
        }
        
        .info-table td {
            padding: 10px 0;
        }
        
        .info-table td:first-child {
            width: 40%;
            color: #6c757d;
            font-weight: 600;
        }
        
        .info-table td:last-child {
            width: 60%;
            color: #2c3e50;
            font-weight: bold;
        }
        
        .amount-grid {
            display: table;
            width: 100%;
            margin: 20px 0;
        }
        
        .amount-row {
            display: table-row;
        }
        
        .amount-cell {
            display: table-cell;
            width: 50%;
            padding: 15px;
            text-align: center;
            background: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        
        .amount-label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .amount-value {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        
        .amount-value.success {
            color: #2b8a3e;
        }
        
        .amount-value.danger {
            color: #c92a2a;
        }
        
        .saldo-box {
            background: #f8f9fa;
            border: 2px solid #667eea;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        
        .saldo-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-weight: bold;
        }
        
        .saldo-value {
            font-size: 28px;
            font-weight: bold;
            color: #667eea;
        }
        
        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        
        .badge-primary {
            background: #e7f5ff;
            color: #1971c2;
        }
        
        .badge-success {
            background: #d3f9d8;
            color: #2b8a3e;
        }
        
        .badge-warning {
            background: #fff3bf;
            color: #f08c00;
        }
        
        .badge-secondary {
            background: #f1f3f5;
            color: #495057;
        }
        
        .footer {
            background: #f8f9fa;
            padding: 20px 30px;
            border-top: 2px dashed #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
        }
        
        .footer-info {
            margin-top: 10px;
            font-style: italic;
        }
        
        .qr-placeholder {
            width: 80px;
            height: 80px;
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            margin: 15px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
            font-size: 10px;
        }
        
        .timestamp {
            text-align: right;
            font-size: 10px;
            color: #6c757d;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- Header --}}
        <div class="header">
            <h1>RESI KEUANGAN SANTRI</h1>
            <p>Bumi Sultan Super App</p>
            <div class="resi-number">
                {{ $santri->nis }} - {{ date('YmdHis') }}
            </div>
        </div>
        
        {{-- Content --}}
        <div class="content">
            {{-- Informasi Santri --}}
            <div class="section">
                <div class="section-title">Informasi Santri</div>
                <table class="info-table">
                    <tr>
                        <td>NIS</td>
                        <td>{{ $santri->nis }}</td>
                    </tr>
                    <tr>
                        <td>Nama Lengkap</td>
                        <td>{{ $santri->nama_lengkap }}</td>
                    </tr>
                    <tr>
                        <td>Status Santri</td>
                        <td>
                            @if($santri->status_santri == 'aktif')
                                <span class="badge badge-primary">AKTIF</span>
                            @elseif($santri->status_santri == 'cuti')
                                <span class="badge badge-warning">CUTI</span>
                            @elseif($santri->status_santri == 'alumni')
                                <span class="badge badge-success">ALUMNI</span>
                            @else
                                <span class="badge badge-secondary">KELUAR</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Tanggal Cetak</td>
                        <td>{{ date('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
            
            {{-- Ringkasan Keuangan --}}
            <div class="section">
                <div class="section-title">Ringkasan Keuangan</div>
                
                <div class="amount-grid">
                    <div class="amount-row">
                        <div class="amount-cell">
                            <div class="amount-label">Saldo Awal</div>
                            <div class="amount-value">
                                Rp {{ number_format($saldo->saldo_awal ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="amount-cell">
                            <div class="amount-label">Total Setoran</div>
                            <div class="amount-value success">
                                Rp {{ number_format($saldo->total_pemasukan ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    <div class="amount-row">
                        <div class="amount-cell">
                            <div class="amount-label">Total Pengeluaran</div>
                            <div class="amount-value danger">
                                Rp {{ number_format($saldo->total_pengeluaran ?? 0, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="amount-cell">
                            <div class="amount-label">Transaksi Terakhir</div>
                            <div class="amount-value" style="font-size: 12px;">
                                @if($saldo && $saldo->last_transaction_date)
                                    {{ \Carbon\Carbon::parse($saldo->last_transaction_date)->format('d/m/Y H:i') }}
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Saldo Akhir --}}
                <div class="saldo-box">
                    <div class="saldo-label">Sisa Saldo Saat Ini</div>
                    <div class="saldo-value">
                        Rp {{ number_format($saldo->saldo_akhir ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            
            {{-- Catatan --}}
            <div class="section">
                <div class="section-title">Catatan</div>
                <table class="info-table">
                    <tr>
                        <td>Status Verifikasi</td>
                        <td>
                            @if($saldo && $saldo->saldo_akhir > 0)
                                <span class="badge badge-success">TERVERIFIKASI</span>
                            @else
                                <span class="badge badge-secondary">BELUM ADA TRANSAKSI</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Dicetak Oleh</td>
                        <td>{{ Auth::user()->name ?? 'Karyawan' }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        {{-- Footer --}}
        <div class="footer">
            <strong>Bumi Sultan Super App - Sistem Manajemen Pesantren</strong>
            <div class="footer-info">
                Dokumen ini digenerate secara otomatis dan sah tanpa tanda tangan<br>
                Dicetak pada: {{ date('d F Y, H:i:s') }} WIB
            </div>
            <div class="timestamp">
                Resi ID: {{ $santri->nis }}-{{ date('YmdHis') }}
            </div>
        </div>
    </div>
</body>
</html>
