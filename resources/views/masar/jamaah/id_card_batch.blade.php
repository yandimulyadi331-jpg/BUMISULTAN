<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $jamaah->nomor_jamaah }} - {{ $jamaah->nama_jamaah }}</title>
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
            flex-direction: column;
        }

        .status-message {
            background: #4CAF50;
            color: white;
            padding: 15px 30px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            box-shadow: 0 4px 12px rgba(76, 175, 80, 0.3);
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
            height: auto;
            margin: 0 auto 8px;
        }
        
        .logo-container img {
            width: 100%;
            height: auto;
            object-fit: contain;
        }
        
        .title-main {
            font-size: 30px;
            font-weight: 900;
            color: #1B4D3E;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin: 0;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .title-sub {
            font-size: 20px;
            color: #2E7D32;
            font-weight: 700;
            margin: 3px 0 0 0;
            letter-spacing: 1px;
        }
        
        /* Photo Section */
        .photo-section {
            width: 300px;
            height: 400px;
            border: 6px solid #1B4D3E;
            border-radius: 20px;
            overflow: hidden;
            margin: 10px auto 10px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);
            z-index: 4;
            position: relative;
        }
        
        .photo-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
        }
        
        .no-photo {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 80px;
            color: #1B4D3E;
        }
        
        /* Info Section */
        .info-section {
            width: 100%;
            z-index: 4;
            padding: 10px 20px;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        
        .info-label {
            font-weight: 800;
            color: #1B4D3E;
            min-width: 150px;
            font-size: 19px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .info-separator {
            margin: 0 10px;
            font-weight: 900;
            color: #1B4D3E;
            font-size: 19px;
        }
        
        .info-value {
            flex: 1;
            font-weight: 800;
            color: #000000;
            font-size: 19px;
        }
        
        /* QR Section */
        .qr-section {
            position: absolute;
            bottom: 40px;
            right: 40px;
            z-index: 4;
        }
        
        #qrcode {
            background: white;
            padding: 8px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        
        /* Download Button */
        .download-container {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
        }
        
        .download-btn {
            background: linear-gradient(135deg, #1B4D3E 0%, #2E7D32 100%);
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 50px;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(27, 77, 62, 0.4);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .download-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(27, 77, 62, 0.5);
        }
        
        .download-btn:active {
            transform: translateY(-1px);
        }
        
        .download-btn i {
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="status-message" id="statusMessage">
        <i class="fas fa-spinner fa-spin"></i> Memuat ID Card...
    </div>

    <div class="idcard-wrapper">
        <div class="idcard-container" id="idcard">
            <!-- Background Pattern -->
            <div class="id-card-pattern"></div>
            
            <div class="id-card">
                <!-- Header Section -->
                <div class="header-section">
                    <div class="logo-container">
                        <img src="{{ asset('assets/template/img/logo/bumisultan.png') }}" alt="Logo">
                    </div>
                    <h1 class="title-main">KARTU ANGGOTA</h1>
                    <h2 class="title-sub">MAJLIS TA'LIM AL-IKHLAS</h2>
                </div>
                
                <!-- Photo Section -->
                <div class="photo-section">
                    @if($jamaah->foto && file_exists(public_path('storage/jamaah_photos/' . $jamaah->foto)))
                        <img src="{{ asset('storage/jamaah_photos/' . $jamaah->foto) }}" alt="{{ $jamaah->nama_jamaah }}">
                    @else
                        <div class="no-photo">
                            <i class="fas fa-user"></i>
                        </div>
                    @endif
                </div>
                
                <!-- Info Section -->
                <div class="info-section">
                    <div class="info-row">
                        <span class="info-label">No. Anggota</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $jamaah->nomor_jamaah }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nama</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $jamaah->nama_jamaah }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">NIK</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $jamaah->nik ?? '-' }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Alamat</span>
                        <span class="info-separator">:</span>
                        <span class="info-value">{{ $jamaah->alamat ?? '-' }}</span>
                    </div>
                </div>
                
                <!-- QR Code Section -->
                <div class="qr-section">
                    <div id="qrcode"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="download-container">
        <button id="download-idcard" class="download-btn">
            <i class="fas fa-download"></i>
            <span>Klik Jika Tidak Auto-Download</span>
        </button>
    </div>

<script>
    // Generate QR Code
    document.addEventListener('DOMContentLoaded', function() {
        // Generate QR Code
        var qrData = {
            no: '{{ $jamaah->nomor_jamaah }}',
            nama: '{{ $jamaah->nama_jamaah }}',
            nik: '{{ $jamaah->nik ?? "-" }}'
        };
        
        new QRCode(document.getElementById("qrcode"), {
            text: JSON.stringify(qrData),
            width: 120,
            height: 120,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });

        // Wait for QR code to be generated
        setTimeout(function() {
            autoDownloadIDCard();
        }, 1500); // Give 1.5 seconds for QR code to fully render

        // Manual download button
        document.getElementById('download-idcard').addEventListener('click', function() {
            downloadIDCard();
        });
    });

    function updateStatus(message, icon = 'fa-spinner fa-spin', color = '#4CAF50') {
        var statusEl = document.getElementById('statusMessage');
        statusEl.innerHTML = '<i class="fas ' + icon + '"></i> ' + message;
        statusEl.style.background = color;
    }

    function autoDownloadIDCard() {
        updateStatus('Menggenerate gambar ID Card...', 'fa-cog fa-spin');
        
        html2canvas(document.getElementById('idcard'), {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            windowWidth: document.documentElement.scrollWidth,
            windowHeight: document.documentElement.scrollHeight
        }).then(function(canvas) {
            updateStatus('Download dimulai!', 'fa-check-circle', '#4CAF50');
            
            var link = document.createElement('a');
            link.download = '{{ sprintf("%03d_%s_%s", 0, $jamaah->nomor_jamaah, str_replace(" ", "_", $jamaah->nama_jamaah)) }}.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.95);
            link.click();
            
            setTimeout(function() {
                updateStatus('Download selesai! File JPG tersimpan.', 'fa-check-circle', '#2196F3');
            }, 500);
        }).catch(function(e) {
            console.error('Error:', e);
            updateStatus('Error! Klik tombol manual download.', 'fa-exclamation-triangle', '#f44336');
        });
    }

    function downloadIDCard() {
        updateStatus('Menggenerate gambar...', 'fa-cog fa-spin');
        
        html2canvas(document.getElementById('idcard'), {
            scale: 3,
            useCORS: true,
            allowTaint: true,
            backgroundColor: '#ffffff',
            logging: false,
            windowWidth: document.documentElement.scrollWidth,
            windowHeight: document.documentElement.scrollHeight
        }).then(function(canvas) {
            var link = document.createElement('a');
            link.download = '{{ sprintf("%03d_%s_%s", 0, $jamaah->nomor_jamaah, str_replace(" ", "_", $jamaah->nama_jamaah)) }}.jpg';
            link.href = canvas.toDataURL('image/jpeg', 0.95);
            link.click();
            
            updateStatus('Download selesai!', 'fa-check-circle', '#4CAF50');
        }).catch(function(e) {
            console.error('Error:', e);
            alert('Gagal membuat gambar: ' + e);
            updateStatus('Error! Coba lagi.', 'fa-exclamation-triangle', '#f44336');
        });
    }
</script>
</body>
</html>
