<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Code - {{ $santri->nama_lengkap }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            background: #ffffff;
            padding: 40px;
        }
        
        .qr-container {
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
            border: 3px solid #10b981;
            border-radius: 20px;
            padding: 40px;
            background: #ffffff;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, #10b981 0%, #34d399 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 36px;
            font-weight: bold;
            border: 4px solid #d1fae5;
        }
        
        .institution-name {
            font-size: 28px;
            font-weight: 700;
            color: #10b981;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        
        .institution-subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .divider {
            height: 3px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            margin: 20px 0;
        }
        
        .qr-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 15px;
        }
        
        .qr-code-wrapper {
            background: #f9fafb;
            padding: 30px;
            border-radius: 15px;
            margin: 20px 0;
            border: 2px dashed #10b981;
        }
        
        .qr-code-box {
            width: 300px;
            height: 300px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.1);
        }
        
        .santri-info {
            margin-top: 30px;
            background: #f0fdf4;
            padding: 25px;
            border-radius: 15px;
            border-left: 5px solid #10b981;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #d1fae5;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            font-weight: 600;
            color: #374151;
            font-size: 14px;
        }
        
        .info-value {
            font-weight: 700;
            color: #10b981;
            font-size: 14px;
        }
        
        .instruction {
            margin-top: 25px;
            padding: 20px;
            background: #fef3c7;
            border-radius: 10px;
            border-left: 5px solid #f59e0b;
        }
        
        .instruction-title {
            font-weight: 700;
            color: #92400e;
            font-size: 16px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .instruction-text {
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            font-size: 12px;
            color: #6b7280;
        }
        
        .footer-address {
            font-size: 11px;
            line-height: 1.5;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <!-- Header -->
        <div class="header">
            <div class="logo">SS</div>
            <div class="institution-name">SAUNG SANTRI</div>
            <div class="institution-subtitle">Pondok Pesantren Tahfidz Al-Qur'an</div>
        </div>
        
        <div class="divider"></div>
        
        <!-- QR Title -->
        <div class="qr-title">🔍 SCAN QR CODE</div>
        
        <!-- QR Code -->
        <div class="qr-code-wrapper">
            <div class="qr-code-box">
                <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::size(260)->generate($url)) }}" alt="QR Code Santri" style="width: 100%; height: 100%;">
            </div>
        </div>
        
        <!-- Santri Info -->
        <div class="santri-info">
            <div class="info-row">
                <span class="info-label">Nama Santri:</span>
                <span class="info-value">{{ strtoupper($santri->nama_lengkap) }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">NIS:</span>
                <span class="info-value">{{ $santri->nis }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">{{ $santri->status_santri == 'aktif' ? 'AKTIF' : strtoupper($santri->status_santri) }}</span>
            </div>
        </div>
        
        <!-- Instruction -->
        <div class="instruction">
            <div class="instruction-title">
                📱 Cara Menggunakan
            </div>
            <div class="instruction-text">
                Scan QR Code di atas menggunakan kamera smartphone atau aplikasi QR scanner untuk melihat <strong>DATA LENGKAP SANTRI</strong> termasuk informasi pribadi, keluarga, pendidikan, hafalan Al-Qur'an, dan data asrama.
            </div>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <strong>SAUNG SANTRI</strong> - Pondok Pesantren Tahfidz Al-Qur'an
            <div class="footer-address">
                Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol<br>
                Kabupaten Bogor, Jawa Barat 16830<br>
                📞 Telp: (0244) 123775 | 📧 Email: info@saungsantri.com
            </div>
        </div>
    </div>
</body>
</html>
