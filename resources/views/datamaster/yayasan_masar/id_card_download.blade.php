<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $yayasan_masar->kode_yayasan }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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
            flex-direction: column;
        }

        .idcard-wrapper {
            position: relative;
            width: 1050px;
            height: 595px;
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
            background: #000000;
            border-radius: 0;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8);
            overflow: hidden;
            position: relative;
            animation: float 6s ease-in-out infinite;
        }
        
        /* Background with Green-Black Gradient for Yayasan Masar */
        .id-card-pattern {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                radial-gradient(ellipse at 20% 30%, rgba(0, 100, 50, 0.4) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 70%, rgba(0, 150, 80, 0.3) 0%, transparent 50%),
                linear-gradient(135deg, 
                    #000000 0%,
                    #001a0d 20%,
                    #003320 35%,
                    #006644 45%,
                    #004d33 55%,
                    #003320 70%,
                    #001a0d 85%,
                    #000000 100%
                );
            z-index: 1;
            pointer-events: none;
        }
        
        .pattern-overlay-1 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: 
                /* Hexagonal Islamic Pattern */
                repeating-conic-gradient(from 30deg at 50% 50%, 
                    rgba(0, 255, 136, 0.15) 0deg, 
                    transparent 60deg,
                    rgba(255, 255, 255, 0.12) 120deg,
                    transparent 180deg
                ),
                /* Mandala Circle Pattern */
                repeating-radial-gradient(circle at 20% 30%, 
                    transparent 0px, 
                    rgba(0, 255, 136, 0.10) 30px, 
                    rgba(255, 255, 255, 0.06) 40px,
                    transparent 50px,
                    rgba(0, 255, 136, 0.08) 80px,
                    transparent 100px
                ),
                repeating-radial-gradient(circle at 80% 70%, 
                    transparent 0px, 
                    rgba(0, 255, 136, 0.12) 25px, 
                    rgba(255, 255, 255, 0.08) 35px,
                    transparent 45px,
                    rgba(0, 255, 136, 0.10) 70px,
                    transparent 90px
                ),
                /* Diagonal Weave Pattern */
                repeating-linear-gradient(45deg, 
                    transparent, 
                    transparent 8px, 
                    rgba(255, 255, 255, 0.08) 8px, 
                    rgba(255, 255, 255, 0.08) 9px,
                    transparent 9px,
                    transparent 16px,
                    rgba(0, 255, 136, 0.10) 16px,
                    rgba(0, 255, 136, 0.10) 17px
                ),
                repeating-linear-gradient(-45deg, 
                    transparent, 
                    transparent 12px, 
                    rgba(0, 255, 136, 0.12) 12px, 
                    rgba(0, 255, 136, 0.12) 13px,
                    transparent 13px,
                    transparent 24px
                ),
                /* Fine Dotted Grid */
                radial-gradient(circle at center, 
                    rgba(0, 255, 136, 0.15) 0.5px, 
                    transparent 0.5px
                );
            background-size: 
                250px 250px, 
                180px 180px, 
                200px 200px, 
                80px 80px, 
                100px 100px,
                20px 20px;
            background-position: 
                0 0, 
                40px 40px, 
                80px 80px, 
                0 0, 
                0 0,
                0 0;
            opacity: 1;
            z-index: 2;
            mix-blend-mode: soft-light;
            pointer-events: none;
        }
        
        .pattern-overlay-2 {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: 
                /* Premium Glass Effect Top */
                radial-gradient(ellipse at 30% 20%, rgba(0, 255, 136, 0.20) 0%, transparent 50%),
                radial-gradient(ellipse at 70% 80%, rgba(0, 255, 136, 0.18) 0%, transparent 50%),
                /* Subtle Shine Effects */
                radial-gradient(circle at 30% 40%, rgba(255, 255, 255, 0.22) 0%, transparent 30%),
                radial-gradient(circle at 70% 60%, rgba(0, 255, 136, 0.25) 0%, transparent 40%),
                /* Islamic Star Pattern Overlay */
                repeating-conic-gradient(from 0deg at 50% 50%,
                    transparent 0deg,
                    rgba(0, 255, 136, 0.12) 15deg,
                    transparent 30deg,
                    rgba(255, 255, 255, 0.08) 45deg,
                    transparent 60deg
                );
            background-size: 100% 100%, 100% 100%, 400px 400px, 350px 350px, 150px 150px;
            background-position: 0 0, 0 0, 0 0, 100px 100px, 0 0;
            z-index: 3;
            mix-blend-mode: screen;
            animation: shimmerPattern 15s ease-in-out infinite;
            pointer-events: none;
        }
        
        @keyframes shimmerPattern {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }
        
        .id-card {
            width: 100%;
            height: 100%;
            position: relative;
            color: #ffffff;
            overflow: hidden;
            z-index: 2;
        }
        
        /* Left Content Section */
        .content-section {
            position: absolute;
            top: 0;
            left: 0;
            width: 650px;
            height: 100%;
            padding: 70px 90px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            z-index: 4;
        }
        
        .name {
            font-size: 4rem;
            font-weight: 900;
            color: #ffffff;
            text-shadow: 3px 3px 8px rgba(0, 0, 0, 0.9),
                        0 0 20px rgba(255, 255, 255, 0.1);
            margin-bottom: 10px;
            letter-spacing: 3px;
            line-height: 1;
            text-transform: uppercase;
            font-family: 'Arial Black', 'Helvetica', sans-serif;
        }
        
        .identity-number {
            font-size: 1.4rem;
            font-weight: 400;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 35px;
            letter-spacing: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.8);
        }

        .divider-line {
            width: 180px;
            height: 3px;
            background: linear-gradient(90deg, #00ff88 0%, #00cc6a 50%, #009944 100%);
            margin-bottom: 45px;
            box-shadow: 0 2px 10px rgba(0, 255, 136, 0.4);
        }
        
        /* Info Section - Simple Clean Layout */
        .info-section {
            display: flex;
            flex-direction: column;
            gap: 22px;
        }
        
        .info-item {
            background: transparent;
            padding: 0;
            border-radius: 0;
            border-left: 0;
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        
        .info-value {
            font-size: 1.6rem;
            color: #ffffff;
            font-weight: 600;
            line-height: 1.4;
            letter-spacing: 0.5px;
            text-shadow: 2px 2px 6px rgba(0, 0, 0, 0.8);
        }
        
        .info-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            letter-spacing: 1px;
            margin-bottom: 0;
            text-transform: uppercase;
        }
        
        /* Photo Section - Right Side with Green Border */
        .photo-section {
            position: absolute;
            top: 50%;
            right: 110px;
            transform: translateY(-50%);
            z-index: 10;
        }
        
        .photo-container {
            width: 320px;
            height: 320px;
            border-radius: 50%;
            overflow: hidden;
            border: 7px solid #00ff88;
            box-shadow: 0 0 40px rgba(0, 255, 136, 0.7),
                       0 0 80px rgba(0, 255, 136, 0.4),
                       0 0 120px rgba(0, 255, 136, 0.2),
                       inset 0 0 20px rgba(0, 255, 136, 0.1),
                       0 10px 40px rgba(0, 0, 0, 0.9);
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            position: relative;
            transition: all 0.3s ease;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .photo-container::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(0, 255, 136, 0.1) 0%, transparent 70%);
            z-index: -1;
        }
        
        .photo-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            filter: brightness(1.05) contrast(1.1);
            display: block;
        }
        
        .photo-placeholder {
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            color: rgba(255, 255, 255, 0.25);
            text-align: center;
            font-weight: bold;
            letter-spacing: 2px;
            flex-direction: column;
        }

        /* Senioritas Bar - Bottom of Card - WARNA HIJAU UNTUK YAYASAN MASAR */
        .seniority-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 12px;
            z-index: 20;
            overflow: hidden;
        }
        
        .seniority-bar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 100%;
            background: linear-gradient(90deg, #00ff88 0%, #00cc6a 25%, #00ff88 50%, #009944 75%, #00ff88 100%);
            box-shadow: 0 -2px 15px rgba(0, 255, 136, 0.7),
                       inset 0 2px 8px rgba(255, 255, 255, 0.4),
                       0 0 20px rgba(0, 255, 136, 0.5);
            animation: shimmer 3s infinite linear;
        }
        
        @keyframes shimmer {
            0% { background-position: -100% 0; }
            100% { background-position: 200% 0; }
        }
        
        .seniority-label {
            position: absolute;
            top: -35px;
            right: 20px;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: #00ff88;
            border: 1px solid rgba(0, 255, 136, 0.6);
            box-shadow: 0 2px 10px rgba(0, 255, 136, 0.3);
            z-index: 20;
        }

        .download-btn-wrapper {
            text-align: center;
            margin: 30px 0 20px 0;
            position: relative;
        }

        .download-btn {
            background: linear-gradient(135deg, #00ff88 0%, #00cc6a 100%);
            color: #000000;
            border: none;
            border-radius: 12px;
            padding: 14px 32px;
            font-size: 1rem;
            font-weight: 700;
            box-shadow: 0 6px 20px rgba(0, 255, 136, 0.4);
            transition: all 0.3s ease;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .download-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 255, 136, 0.6);
        }

        .download-btn:active {
            transform: translateY(0);
            box-shadow: 0 3px 15px rgba(0, 255, 136, 0.4);
        }

        /* Badge Section - Tahun Masuk dan PIN */
        .badges-section {
            position: absolute;
            bottom: 30px;
            left: 60px;
            display: flex;
            gap: 20px;
            z-index: 15;
        }

        .badge-item {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border: 1.5px solid rgba(0, 255, 136, 0.5);
            border-radius: 12px;
            padding: 12px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            min-width: 100px;
        }

        .badge-label {
            font-size: 0.7rem;
            color: rgba(0, 255, 136, 0.8);
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .badge-value {
            font-size: 1.4rem;
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 2px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.8);
        }

        @media (max-width: 1100px) {
            .idcard-wrapper {
                width: 95%;
                height: auto;
                aspect-ratio: 1050/595;
            }
            .name {
                font-size: 2.8rem;
            }
            .info-value {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <div class="idcard-wrapper">
        <div class="idcard-container" id="idcard-area">
            <!-- Background Pattern Layers -->
            <div class="id-card-pattern"></div>
            <div class="pattern-overlay-1"></div>
            <div class="pattern-overlay-2"></div>
            
            <div class="id-card">
                <!-- Left Content Section -->
                <div class="content-section">
                    <div class="name">{{ Str::upper($yayasan_masar->nama) }}</div>
                    <div class="identity-number">{{ $yayasan_masar->kode_yayasan }}</div>
                    
                    <div class="divider-line"></div>
                    
                    <!-- Info Section -->
                    <div class="info-section">
                        <div class="info-item">
                            <div class="info-value">{{ $yayasan_masar->no_identitas }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-value">{{ $yayasan_masar->tanggal_lahir ? \Carbon\Carbon::parse($yayasan_masar->tanggal_lahir)->format('d F Y') : '-' }}</div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-value">{{ Str::limit($yayasan_masar->alamat, 80) }}</div>
                        </div>
                        
                        @if(!empty($yayasan_masar->nama_jabatan))
                        <div class="info-item">
                            <div class="info-value">{{ $yayasan_masar->nama_jabatan }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Photo Section - Right Side -->
                <div class="photo-section">
                    <div class="photo-container">
                        @if(!empty($yayasan_masar->foto) && Storage::disk('public')->exists('yayasan_masar/' . $yayasan_masar->foto))
                            @php
                                $fotoUrl = asset('storage/yayasan_masar/' . ltrim($yayasan_masar->foto, '/'));
                            @endphp
                            <img src="{{ $fotoUrl }}" alt="Foto {{ $yayasan_masar->nama }}" 
                                 crossorigin="anonymous"
                                 onerror="this.style.display='none'; this.parentElement.querySelector('.photo-placeholder').style.display='flex';">
                        @endif
                        <div class="photo-placeholder" style="{{ (!empty($yayasan_masar->foto) && Storage::disk('public')->exists('yayasan_masar/' . $yayasan_masar->foto)) ? 'display:none;' : 'display:flex;' }}">
                            <span>FOTO</span>
                            <span>YAYASAN</span>
                        </div>
                    </div>
                </div>
                
                @php
                    // Hitung lama bergabung
                    $currentYear = date('Y');
                    $yearJoined = (int)$yayasan_masar->tanggal_masuk ? (new \Carbon\Carbon($yayasan_masar->tanggal_masuk))->year : $currentYear;
                    $yearsOfMembership = $currentYear - $yearJoined;
                @endphp

                <!-- Badge Section - Tahun Masuk dan PIN -->
                <div class="badges-section">
                    <div class="badge-item">
                        <div class="badge-label">Tahun Masuk</div>
                        <div class="badge-value">{{ $yearJoined }}</div>
                    </div>
                    @if(!empty($yayasan_masar->pin))
                    <div class="badge-item">
                        <div class="badge-label">PIN</div>
                        <div class="badge-value">{{ $yayasan_masar->pin }}</div>
                    </div>
                    @endif
                </div>
                
                <!-- Seniority Bar - HIJAU untuk YAYASAN MASAR -->
                <div class="seniority-bar">
                    <div class="seniority-label">YAYASAN MASAR ({{ $yearsOfMembership }} Tahun)</div>
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
                    backgroundColor: '#000000',
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
                    link.download = 'idcard-{{ $yayasan_masar->kode_yayasan }}.jpg';
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
