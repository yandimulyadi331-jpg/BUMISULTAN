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
            background: linear-gradient(135deg, #0f8a5f 0%, #0d6b4a 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .idcard-wrapper {
            position: relative;
            width: 600px;
            height: 950px;
            margin: 0 auto;
            padding: 0;
            perspective: 1000px;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }
        
        /* ID Card Horizontal Style - Like Reference Image */
        .idcard-container {
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, #0d6b4a 0%, #116b4f 30%, #0e5d42 70%, #0a4a35 100%);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4),
                       0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
            position: relative;
            animation: float 6s ease-in-out infinite;
        }
        
        /* Background Pattern */
        .id-card-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,.02) 10px, rgba(255,255,255,.02) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(255,255,255,.02) 10px, rgba(255,255,255,.02) 20px);
            opacity: 0.5;
            z-index: 1;
        }
        
        .id-card {
            width: 100%;
            height: 100%;
            position: relative;
            color: #ffffff;
            overflow: hidden;
            z-index: 2;
        }
        
        /* Header Top */
        .header-top {
            position: relative;
            padding: 30px 30px 20px 30px;
            text-align: center;
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, transparent 100%);
        }
        
        .header-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px auto;
            opacity: 0.98;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.5));
        }
        
        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        
        .organization-name {
            font-size: 2.2rem;
            font-weight: 800;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 5px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
            line-height: 1.2;
        }
        
        /* Photo Section - Circular Large */
        .photo-container {
            position: relative;
            width: 320px;
            height: 320px;
            margin: 30px auto;
            border-radius: 50%;
            overflow: hidden;
            border: 8px solid #ffffff;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.5);
            background: rgba(255, 255, 255, 0.1);
        }
        
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #2d6b52 0%, #1a4d3e 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: rgba(255, 255, 255, 0.5);
            text-align: center;
            font-weight: bold;
        }
        
        /* Umroh Badge - Top Right */
        .umroh-ribbon {
            position: absolute;
            top: 240px;
            right: 40px;
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #ffffff;
            padding: 10px 25px;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.5);
            z-index: 10;
            letter-spacing: 1px;
            border-radius: 50px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }
        
        .umroh-ribbon::before {
            content: '⭐';
            margin-right: 6px;
            font-size: 1.1rem;
        }
        
        /* Name Section */
        .name-section {
            padding: 0 30px;
            margin-top: 20px;
            text-align: center;
        }
        
        .jamaah-name {
            font-size: 2.5rem;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.6);
            margin-bottom: 15px;
            line-height: 1.2;
            letter-spacing: 1px;
        }
        
        /* Info Section - Two Column Grid */
        .info-section {
            padding: 30px 40px 20px 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 10px;
        }
        
        .info-item {
            background: rgba(255, 255, 255, 0.08);
            padding: 15px;
            border-radius: 12px;
            border-left: 4px solid #2ecc71;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        
        .info-item.full-width {
            grid-column: 1 / -1;
        }
        
        .info-label {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-label i {
            color: #2ecc71;
            font-size: 1rem;
        }
        
        .info-value {
            font-size: 1.1rem;
            color: #ffffff;
            font-weight: 600;
            line-height: 1.4;
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }
        
        /* Footer with ID Badge */
        .footer-section {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 25px 40px;
            background: linear-gradient(180deg, transparent 0%, rgba(0, 0, 0, 0.4) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .id-badge-bottom {
            background: linear-gradient(135deg, #d4af37 0%, #f4c542 100%);
            color: #1a4d3e;
            padding: 15px 50px;
            border-radius: 50px;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: 2px;
            box-shadow: 0 8px 24px rgba(212, 175, 55, 0.5);
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .download-btn-wrapper {
            text-align: center;
            margin: 30px 0 20px 0;
            position: relative;
        }

        .download-btn {
            background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 600;
            box-shadow: 0 6px 20px rgba(46, 204, 113, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(46, 204, 113, 0.5);
        }

        .download-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 15px rgba(46, 204, 113, 0.4);
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            padding: 12px 20px;
            font-size: 0.95rem;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
        }

        @media (max-width: 400px) {
            .idcard-wrapper {
                width: 95%;
                height: auto;
                aspect-ratio: 340/540;
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
            <!-- Background Pattern -->
            <div class="id-card-pattern"></div>
            
            <div class="id-card">
                <!-- Header Top -->
                <div class="header-top">
                    @php
                        $logoPath = public_path('logo/logo_yayasan.png');
                        if(!file_exists($logoPath)) {
                            $logoPath = public_path('assets/img/logo.png');
                        }
                        if(file_exists($logoPath)) {
                            $logoData = base64_encode(file_get_contents($logoPath));
                            $logoExt = pathinfo($logoPath, PATHINFO_EXTENSION);
                            $logoBase64 = 'data:image/' . $logoExt . ';base64,' . $logoData;
                        }
                    @endphp
                    
                    @if(isset($logoBase64))
                    <img src="{{ $logoBase64 }}" class="header-logo" alt="Logo">
                    @endif
                    
                    <div class="organization-name">MAJLIS TA'LIM<br>AL-IKHLAS</div>
                    <div class="organization-subtitle">Kartu Identitas Jamaah</div>
                </div>
                
                <!-- Umroh Badge -->
                @if($jamaah->status_umroh)
                <div class="umroh-ribbon">
                    Umroh
                </div>
                @endif
                
                <!-- Badges on Right Side -->
                <div class="badges-container">
                    <div class="badge-icon">
                        <i class="fas fa-mosque"></i>
                    </div>
                    <div class="badge-icon">
                        <i class="fas fa-book-quran"></i>
                    </div>
                    @if($jamaah->jumlah_kehadiran > 20)
                    <div class="badge-icon">
                        <i class="fas fa-star"></i>
                    </div>
            <div class="id-card">
                <!-- Background Pattern -->
                <div class="id-card-pattern"></div>
                
                <!-- Header Top -->
                <div class="header-top">
                    <div class="header-logo">
                        @php
                            $logoPath = public_path('logo/logo_yayasan.png');
                            if(!file_exists($logoPath)) {
                                $logoPath = public_path('assets/img/logo.png');
                            }
                            if(file_exists($logoPath)) {
                                $imageData = base64_encode(file_get_contents($logoPath));
                                $ftype = pathinfo($logoPath, PATHINFO_EXTENSION);
                                $logoBase64 = 'data:image/' . $ftype . ';base64,' . $imageData;
                            } else {
                                $logoBase64 = null;
                            }
                        @endphp
                        @if($logoBase64)
                            <img src="{{ $logoBase64 }}" alt="Logo">
                        @else
                            <i class="fas fa-mosque" style="font-size: 3rem; color: #ffffff;"></i>
                        @endif
                    </div>
                    <div class="organization-name">MAJLIS TA'LIM AL-IKHLAS</div>
                </div>
                
                <!-- Umroh Badge -->
                @if($jamaah->status_umroh)
                <div class="umroh-ribbon">
                    UMROH
                </div>
                @endif
                
                <!-- Photo Section -->
                <div class="photo-container">
                    @php
                        $fotoPath = null;
                        $fotoBase64 = null;
                        
                        if($jamaah->foto) {
                            $paths = [
                                storage_path('app/public/' . $jamaah->foto),
                                public_path('storage/' . $jamaah->foto),
                                storage_path('app/' . $jamaah->foto),
                            ];
                            
                            foreach($paths as $path) {
                                if(file_exists($path)) {
                                    $fotoPath = $path;
                                    $imageData = base64_encode(file_get_contents($path));
                                    $ftype = pathinfo($path, PATHINFO_EXTENSION);
                                    $fotoBase64 = 'data:image/' . $ftype . ';base64,' . $imageData;
                                    break;
                                }
                            }
                        }
                    @endphp
                    
                    @if($fotoBase64)
                        <img src="{{ $fotoBase64 }}" alt="Foto Jamaah">
                    @else
                        <div class="photo-placeholder">
                            FOTO<br>JAMAAH
                        </div>
                    @endif
                </div>
                
                <!-- Name Section -->
                <div class="name-section">
                    <div class="jamaah-name">{{ Str::upper($jamaah->nama_jamaah) }}</div>
                </div>
                
                <!-- Info Section -->
                <div class="info-section">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-id-card"></i> NIK
                        </div>
                        <div class="info-value">{{ $jamaah->nik }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i> Tahun Masuk
                        </div>
                        <div class="info-value">{{ $jamaah->tahun_masuk }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-check"></i> Tanggal Lahir
                        </div>
                        <div class="info-value">{{ $jamaah->tanggal_lahir ? \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d F Y') : '-' }}</div>
                    </div>
                    
                    <div class="info-item full-width">
                        <div class="info-label">
                            <i class="fas fa-map-marker-alt"></i> Alamat
                        </div>
                        <div class="info-value">{{ $jamaah->alamat }}</div>
                    </div>
                </div>
                
                <!-- Footer with ID Badge -->
                <div class="footer-section">
                    <div class="id-badge-bottom">JAMAAH - {{ $jamaah->nomor_jamaah }}</div>
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
                    backgroundColor: null,
                    scale: 3,
                    useCORS: true,
                    allowTaint: true,
                    logging: false
                }).then(function(canvas) {
                    var link = document.createElement('a');
                    link.download = 'idcard-{{ $jamaah->nomor_jamaah }}.jpg';
                    link.href = canvas.toDataURL('image/jpeg', 0.95);
                    link.click();
                }).catch(function(e) {
                    alert('Gagal membuat gambar: ' + e);
                });
            });
        }
    });
</script>
</body>
</html>
