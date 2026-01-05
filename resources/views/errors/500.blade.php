<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Server Error</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .error-container {
            background: white;
            border-radius: 20px;
            padding: 50px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            text-align: center;
        }
        .error-code {
            font-size: 120px;
            font-weight: bold;
            color: #667eea;
            margin: 0;
            line-height: 1;
        }
        .error-title {
            font-size: 32px;
            color: #333;
            margin: 20px 0;
        }
        .error-message {
            font-size: 16px;
            color: #666;
            margin: 20px 0;
            line-height: 1.6;
        }
        .error-icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .btn-back {
            display: inline-block;
            padding: 15px 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 30px;
            transition: transform 0.3s ease;
        }
        .btn-back:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        .technical-details {
            background: #f8f9fa;
            border-left: 4px solid #dc3545;
            padding: 15px;
            margin-top: 30px;
            text-align: left;
            border-radius: 8px;
            font-size: 13px;
            color: #495057;
            font-family: 'Courier New', monospace;
        }
        .suggestions {
            background: #e7f3ff;
            border-left: 4px solid #0084ff;
            padding: 15px;
            margin-top: 20px;
            text-align: left;
            border-radius: 8px;
        }
        .suggestions h4 {
            margin-top: 0;
            color: #0084ff;
        }
        .suggestions ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .suggestions li {
            margin: 8px 0;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">⚠️</div>
        <h1 class="error-code">500</h1>
        <h2 class="error-title">Internal Server Error</h2>
        <p class="error-message">
            {{ $message ?? 'Terjadi kesalahan pada server. Silakan coba lagi nanti atau hubungi administrator.' }}
        </p>
        
        <div class="suggestions">
            <h4>💡 Saran untuk laporan data besar:</h4>
            <ul>
                <li><strong>Pilih periode lebih pendek</strong> (misalnya 1-3 bulan)</li>
                <li><strong>Filter berdasarkan Cabang</strong> untuk mengurangi jumlah data</li>
                <li><strong>Filter berdasarkan Departemen</strong> untuk hasil lebih spesifik</li>
                <li><strong>Pilih karyawan tertentu</strong> untuk laporan individual</li>
                <li>Hubungi IT jika masih mengalami masalah</li>
            </ul>
        </div>
        
        @if(isset($technical_message) && $technical_message)
        <div class="technical-details">
            <strong>Technical Details (Debug Mode):</strong><br>
            {{ $technical_message }}
        </div>
        @endif
        
        <a href="javascript:history.back()" class="btn-back">← Kembali</a>
    </div>
</body>
</html>
