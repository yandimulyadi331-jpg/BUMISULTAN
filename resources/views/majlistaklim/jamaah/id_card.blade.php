<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $jamaah->nomor_jamaah }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #1a1a1a;
            min-height: 100vh;
            font-family: 'Arial', 'Helvetica', sans-serif;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .idcard-wrapper {
            position: relative;
            width: 638px;
            height: 1012px;
            margin: 0 auto;
            padding: 0;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* ID Card */
        .idcard-container {
            width: 100%;
            height: 100%;
            background: #ffffff;
            border-radius: 25px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            overflow: hidden;
            position: relative;
            animation: float 6s ease-in-out infinite;
        }
        
        /* Background Image */
        .id-card-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url('{{ asset('assets/template/img/logo/idcardback.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
            pointer-events: none;
        }
        
        .pattern-overlay-1 {
            display: none;
        }
        
        .pattern-overlay-2 {
            display: none;
        }
        
        .id-card {
            width: 100%;
            height: 100%;
            position: relative;
            color: #000000;
            overflow: hidden;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15px 40px 70px 40px;
        }
        
        /* Header Section - Logo and Title */
        .header-section {
            text-align: center;
            margin-bottom: 5px;
            z-index: 4;
        }
        
        .logo-container {
            width: 350px;
            height: 350px;
            margin: 0 auto -30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-container img {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }
        
        .organization-title {
            font-size: 1.7rem;
            font-weight: 900;
            color: #1a5f3a;
            margin-bottom: 0px;
            margin-top: 0px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-family: 'Arial Black', 'Helvetica', sans-serif;
        }
        
        /* Photo Section - Center with Gradient Border */
        .photo-section {
            margin: 3px 0 5px 0;
            z-index: 10;
        }
        
        .photo-container {
            width: 250px;
            height: 250px;
            border-radius: 50%;
            overflow: hidden;
            border: 6px solid transparent;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.4);
            position: relative;
            transition: all 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4px;
        }
        
        .photo-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            background: #f5f5f5;
        }
        
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            display: block;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: #f5f5f5;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #cccccc;
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            flex-direction: column;
        }
        
        /* Content Section - Name and Details */
        .content-section {
            text-align: center;
            z-index: 4;
            width: 100%;
        }
        
        .jamaah-name {
            font-size: 2.2rem;
            font-weight: 900;
            color: #000000;
            margin-bottom: 2px;
            letter-spacing: 1.5px;
            line-height: 1.2;
            text-transform: uppercase;
            font-family: 'Arial Black', 'Helvetica', sans-serif;
        }
        
        .jamaah-number {
            font-size: 1.15rem;
            font-weight: 700;
            color: #333333;
            margin-bottom: 7px;
            letter-spacing: 2px;
        }

        .divider-line {
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            margin: 0 auto 7px;
            border-radius: 2px;
        }
        
        /* Info Section - Vertical Layout */
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
        }
        
        .info-item {
            background: transparent;
            padding: 0;
            border-radius: 0;
            border-left: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
            text-align: center;
        }
        
        .info-value {
            font-size: 1.3rem;
            color: #000000;
            font-weight: 700;
            line-height: 1.4;
            letter-spacing: 0.3px;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: #666666;
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 0;
            text-transform: uppercase;
        }

        /* Bottom Bar - Gradient */
        .seniority-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 15px;
            z-index: 20;
            overflow: hidden;
            border-radius: 0 0 25px 25px;
        }
        
        .seniority-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            animation: shimmer 3s infinite linear;
        }
        
        @keyframes shimmer {
            0% { opacity: 0.8; }
            50% { opacity: 1; }
            100% { opacity: 0.8; }
        }
        
        .seniority-label {
            display: none;
        }
        
        /* Year Badge */
        .year-badge {
            position: absolute;
            bottom: 25px;
            left: 50%;
            transform: translateX(-50%);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            padding: 12px 40px;
            border-radius: 25px;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 1.5px;
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            z-index: 20;
        }

        .download-btn-wrapper {
            text-align: center;
            margin: 30px 0 20px 0;
            position: relative;
        }

        .download-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.6);
        }

        .download-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 15px rgba(102, 126, 234, 0.4);
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(102, 126, 234, 0.15);
            backdrop-filter: blur(10px);
            color: #667eea;
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(102, 126, 234, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.3);
        }

        @media (max-width: 1100px) {
            .idcard-wrapper {
                width: 95%;
                height: auto;
                aspect-ratio: 638/1012;
            }
            .jamaah-name {
                font-size: 2rem;
            }
            .info-value {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <button class="back-btn" onclick="window.history.back()">
        <i class="fas fa-arrow-left"></i> Kembali
    </button>
    
    <div class="idcard-wrapper">
        <div class="idcard-container" id="idcard-area">
            <!-- Background Pattern Layers -->
            <div class="id-card-pattern"></div>
            <div class="pattern-overlay-1"></div>
            <div class="pattern-overlay-2"></div>
            
            <div class="id-card">
                <!-- Header Section - Logo -->
                <div class="header-section">
                    <div class="logo-container">
                        <img src="{{ asset('assets/template/img/logo/logoyayasan.png') }}" alt="Logo" onerror="this.style.display='none'">
                    </div>
                    <div class="organization-title">MAJLIS TA'LIM AL-IKHLAS</div>
                </div>
                
                <!-- Photo Section - Center -->
                <div class="photo-section">
                    <div class="photo-container">
                        <div class="photo-inner">
                            @if($jamaah->foto)
                                @php
                                    // Gunakan path yang sama dengan halaman detail
                                    $fotoUrl = asset('storage/jamaah/' . ltrim($jamaah->foto, '/'));
                                @endphp
                                <img src="{{ $fotoUrl }}" alt="Foto {{ $jamaah->nama_jamaah }}" 
                                     crossorigin="anonymous"
                                     onerror="this.style.display='none'; this.parentElement.querySelector('.photo-placeholder').style.display='flex';">
                            @endif
                            <div class="photo-placeholder" style="{{ $jamaah->foto ? 'display:none;' : 'display:flex;' }}">
                                <span>FOTO</span>
                                <span>JAMAAH</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Content Section - Name and Info -->
                <div class="content-section">
                    <div class="jamaah-name">{{ Str::upper($jamaah->nama_jamaah) }}</div>
                    <div class="jamaah-number">{{ $jamaah->nomor_jamaah }}</div>
                    
                    <div class="divider-line"></div>
                    
                    <!-- Info Section -->
                    <div class="info-section">
                        <div class="info-item">
                            <div class="info-value">{{ $jamaah->nik }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-value">{{ $jamaah->tanggal_lahir ? \Carbon\Carbon::parse($jamaah->tanggal_lahir)->translatedFormat('d F Y') : 'BOGOR 24 FEBRUARI 2025' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-value">{{ Str::limit($jamaah->alamat, 100) }}</div>
                        </div>
                    </div>
                </div>
                
                @php
                    // Hitung tahun atau gunakan tahun masuk
                    $yearDisplay = $jamaah->tahun_masuk ?? date('Y');
                @endphp
                
                <!-- Year Badge -->
                <div class="year-badge">{{ $yearDisplay }}</div>
                
                <!-- Bottom Gradient Bar -->
                <div class="seniority-bar">
                    <div class="seniority-label"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="download-btn-wrapper">
        <button id="download-idcard" class="download-btn">
            <i class="fa-solid fa-download"></i> Download JPG
        </button>
    </div>

<script>
    // Download ID Card functionality
    document.addEventListener('DOMContentLoaded', function() {
        var btn = document.getElementById('download-idcard');
        if (btn) {
            btn.addEventListener('click', function() {
                var area = document.getElementById('idcard-area');
                if (!area) {
                    alert('ID Card tidak ditemukan!');
                    return;
                }
                if (typeof html2canvas === 'undefined') {
                    alert('Gagal memuat html2canvas. Pastikan koneksi internet Anda stabil.');
                    return;
                }
                html2canvas(area, {
                    backgroundColor: '#ffffff',
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    logging: true,
                    imageTimeout: 0,
                    removeContainer: true,
                    foreignObjectRendering: false,
                    scrollX: 0,
                    scrollY: -window.scrollY,
                    windowWidth: document.documentElement.scrollWidth,
                    windowHeight: document.documentElement.scrollHeight
                }).then(function(canvas) {
                    var link = document.createElement('a');
                    link.download = 'idcard-{{ $jamaah->nomor_jamaah }}.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                }).catch(function(e) {
                    console.error('Error:', e);
                    alert('Gagal membuat gambar: ' + e);
                });
            });
        }
    });
</script>
</body>
</html>
