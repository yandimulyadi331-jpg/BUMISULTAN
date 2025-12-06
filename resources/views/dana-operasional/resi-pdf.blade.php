<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Resi Transaksi - {{ $realisasi->nomor_realisasi }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 11px;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 20px;
        }
        .header h2 {
            margin: 3px 0;
            color: #666;
            font-size: 13px;
        }
        .header p {
            margin: 3px 0;
            color: #999;
            font-size: 9px;
        }
        .info-box {
            border: 2px solid #333;
            padding: 10px;
            margin: 10px 0;
            background-color: #f9f9f9;
        }
        .info-row {
            margin: 5px 0;
            display: flex;
        }
        .info-label {
            width: 130px;
            font-weight: bold;
            color: #333;
            font-size: 10px;
        }
        .info-value {
            flex: 1;
            color: #666;
            font-size: 10px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            color: white;
        }
        .badge-primary { background-color: #007bff; }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
        .badge-info { background-color: #17a2b8; }
        .amount-box {
            text-align: center;
            padding: 12px;
            margin: 10px 0;
            border: 2px solid #28a745;
            background-color: #f0f9ff;
        }
        .amount-label {
            font-size: 11px;
            color: #666;
            margin-bottom: 5px;
        }
        .amount-value {
            font-size: 22px;
            font-weight: bold;
            color: #28a745;
        }
        .keterangan-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin: 10px 0;
            background-color: #fafafa;
            min-height: 50px;
            font-size: 10px;
        }
        .keterangan-box h3 {
            margin: 0 0 8px 0;
            font-size: 11px;
        }
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 8px;
        }
        .signature-box {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
        }
        .signature {
            text-align: center;
            width: 180px;
            font-size: 10px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }
        .foto-box {
            margin: 10px 0;
            text-align: center;
            border: 1px solid #ddd;
            padding: 8px;
        }
        .foto-box h3 {
            margin: 0 0 8px 0;
            font-size: 11px;
        }
        .foto-box img {
            max-width: 280px;
            max-height: 180px;
            border: 2px solid #333;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RESI TRANSAKSI</h1>
        <h2>Dana Operasional Harian</h2>
        <p>Dicetak: {{ $tanggal_cetak->format('d F Y H:i:s') }} WIB</p>
    </div>

    <div class="info-box">
        <div class="info-row">
            <div class="info-label">Nomor Transaksi</div>
            <div class="info-value">
                <span class="badge badge-primary">{{ $realisasi->nomor_realisasi }}</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal Transaksi</div>
            <div class="info-value">{{ $realisasi->tanggal_realisasi->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tipe Transaksi</div>
            <div class="info-value">
                <span class="badge badge-{{ $realisasi->tipe_transaksi == 'masuk' ? 'success' : 'danger' }}">
                    {{ strtoupper($realisasi->tipe_transaksi) }}
                </span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Kategori</div>
            <div class="info-value">
                <span class="badge badge-info">{{ $realisasi->kategori }}</span>
            </div>
        </div>
        <div class="info-row">
            <div class="info-label">Diinput Oleh</div>
            <div class="info-value">{{ $realisasi->creator ? $realisasi->creator->name : '-' }}</div>
        </div>
    </div>

    <div class="amount-box">
        <div class="amount-label">NOMINAL TRANSAKSI</div>
        <div class="amount-value" style="color: {{ $realisasi->tipe_transaksi == 'masuk' ? '#28a745' : '#dc3545' }}">
            Rp {{ number_format($realisasi->nominal, 2, ',', '.') }}
        </div>
    </div>

    <div class="keterangan-box">
        <h3>Keterangan:</h3>
        <div>{{ $realisasi->uraian }}</div>
    </div>

    @if($realisasi->foto_bukti)
    <div class="foto-box">
        <h3>Foto Bukti:</h3>
        <img src="{{ public_path('storage/' . $realisasi->foto_bukti) }}" alt="Foto Bukti">
    </div>
    @endif

    <div class="signature-box">
        <div class="signature">
            <div>Yang Mengajukan</div>
            <div class="signature-line">
                ({{ $realisasi->creator ? $realisasi->creator->name : '.....................' }})
            </div>
        </div>
        <div class="signature">
            <div>Mengetahui</div>
            <div class="signature-line">
                (...................)
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>Dokumen ini dicetak secara otomatis dari sistem</strong></p>
        <p>Dana Operasional Harian - {{ $realisasi->nomor_realisasi }}</p>
    </div>
</body>
</html>
