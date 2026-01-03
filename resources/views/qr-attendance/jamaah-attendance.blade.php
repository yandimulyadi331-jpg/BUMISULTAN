<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi {{ $jamaah->nama }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Leaflet CSS untuk Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Face-api.js untuk Face Recognition -->
    <script defer src="https://cdn.jsdelivr.net/npm/face-api.js@0.22.2/dist/face-api.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .attendance-container {
            max-width: 600px;
            margin: 0 auto;
        }
        
        /* Jamaah Profile Card */
        .profile-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        
        .profile-photo {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
            margin: 0 auto 15px;
            display: block;
        }
        
        .profile-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-size: 48px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }
        
        .profile-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        
        .profile-info {
            color: #666;
            font-size: 14px;
            margin: 3px 0;
        }
        
        .badge-kehadiran {
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            margin-top: 10px;
        }
        
        /* Event Info Card */
        .event-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .event-title {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .event-detail {
            color: #666;
            font-size: 14px;
            margin: 5px 0;
        }
        
        /* Validation Cards */
        .validation-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .validation-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .validation-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }
        
        .icon-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .icon-valid {
            background: #d4edda;
            color: #155724;
        }
        
        .icon-invalid {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Camera Preview */
        .camera-container {
            position: relative;
            width: 100%;
            max-width: 400px;
            margin: 0 auto 15px;
            border-radius: 15px;
            overflow: hidden;
            background: #000;
        }
        
        #video {
            width: 100%;
            height: auto;
            display: block;
        }
        
        #canvas {
            display: none;
        }
        
        #photoPreview {
            width: 100%;
            height: auto;
            display: none;
            border-radius: 15px;
        }
        
        .camera-overlay {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 200px;
            height: 200px;
            border: 3px solid #667eea;
            border-radius: 50%;
            pointer-events: none;
        }
        
        /* Buttons */
        .btn-custom {
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: all 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        
        .btn-primary-custom {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success-custom {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
        }
        
        .btn-success-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(40, 167, 69, 0.4);
        }
        
        .btn-warning-custom {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            color: white;
        }
        
        .btn-danger-custom {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
        }
        
        .btn-custom:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        /* Status Messages */
        .status-message {
            padding: 12px;
            border-radius: 10px;
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
        }
        
        .status-pending {
            background: #fff3cd;
            color: #856404;
        }
        
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        
        /* Submit Button */
        .btn-submit-attendance {
            padding: 18px;
            font-size: 18px;
            border-radius: 15px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
            transition: all 0.3s;
            margin-top: 20px;
        }
        
        .btn-submit-attendance:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
        }
        
        .btn-submit-attendance:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #6c757d;
        }
        
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .alert-sudah-absen {
            background: #d4edda;
            border: 2px solid #28a745;
            color: #155724;
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="attendance-container">
        <!-- Jamaah Profile -->
        <div class="profile-card">
            @if($jamaah->foto && file_exists(public_path('storage/yayasan_masar/' . $jamaah->foto)))
                <img src="{{ asset('storage/yayasan_masar/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="profile-photo" id="jamaahPhoto">
            @elseif($jamaah->foto && file_exists(public_path('storage/jamaah/' . $jamaah->foto)))
                <img src="{{ asset('storage/jamaah/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="profile-photo" id="jamaahPhoto">
            @else
                <div class="profile-placeholder" id="jamaahPhoto">
                    {{ strtoupper(substr($jamaah->nama, 0, 1)) }}
                </div>
            @endif
            
            <div class="profile-name">{{ $jamaah->nama }}</div>
            <div class="profile-info">No. Identitas: {{ $jamaah->no_identitas }}</div>
            @if($jamaah->no_hp)
            <div class="profile-info">No. HP: {{ $jamaah->no_hp }}</div>
            @endif
            <span class="badge-kehadiran">
                <i class="ti ti-check-circle"></i> {{ $jumlahKehadiran }}x Kehadiran
            </span>
        </div>

        <!-- Event Info -->
        <div class="event-card">
            <div class="event-title">
                <i class="ti ti-calendar-event"></i> {{ $event->event_name }}
            </div>
            <div class="event-detail">
                <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }}
            </div>
            <div class="event-detail">
                <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
            </div>
            <div class="event-detail">
                <i class="ti ti-map-pin"></i> {{ $event->venue_location }}
            </div>
        </div>

        @if($sudahAbsen)
        <div class="alert-sudah-absen">
            <i class="ti ti-check-circle" style="font-size: 48px; display: block; margin-bottom: 10px;"></i>
            Anda Sudah Melakukan Absensi<br>Untuk Event Ini Hari Ini
            <div style="margin-top: 15px;">
                <a href="{{ route('qr-attendance.jamaah-list', ['token' => $token]) }}" class="btn btn-success">
                    <i class="ti ti-arrow-left"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        @else
        
        <!-- Validation: Face Recognition -->
        <div class="validation-card">
            <div class="validation-title">
                <div class="validation-icon icon-pending" id="iconFace">
                    <i class="ti ti-camera"></i>
                </div>
                <span>Validasi Wajah</span>
            </div>
            
            <div class="camera-container" id="cameraContainer" style="display: none;">
                <video id="video" autoplay playsinline></video>
                <canvas id="canvas"></canvas>
                <div class="camera-overlay"></div>
            </div>
            
            <img id="photoPreview" alt="Photo Preview">
            
            <button type="button" class="btn-custom btn-primary-custom" id="btnStartCamera">
                <i class="ti ti-camera"></i> Aktifkan Kamera
            </button>
            <button type="button" class="btn-custom btn-success-custom" id="btnCapture" style="display: none;">
                <i class="ti ti-capture"></i> Ambil Foto
            </button>
            <button type="button" class="btn-custom btn-warning-custom" id="btnRetake" style="display: none;">
                <i class="ti ti-refresh"></i> Ambil Ulang
            </button>
            
            <div class="status-message status-pending" id="statusFace" style="display: none;">
                <i class="ti ti-info-circle"></i> Menunggu validasi wajah
            </div>
        </div>

        <!-- Validation: GPS Location -->
        <div class="validation-card">
            <div class="validation-title">
                <div class="validation-icon icon-pending" id="iconGPS">
                    <i class="ti ti-map-pin"></i>
                </div>
                <span>Validasi Lokasi</span>
            </div>
            
            <button type="button" class="btn-custom btn-primary-custom" id="btnGetLocation">
                <i class="ti ti-current-location"></i> Dapatkan Lokasi Saya
            </button>
            
            <div class="status-message status-pending" id="statusGPS" style="display: none;">
                <i class="ti ti-loader"></i> Menunggu validasi lokasi
            </div>
            
            <!-- MAP VISUALIZATION -->
            <div id="mapContainer" style="display: none; margin-top: 15px; height: 300px; border-radius: 10px; overflow: hidden; border: 2px solid #ddd;"></div>
            
            <div id="locationInfo" style="display: none; margin-top: 15px; font-size: 13px; color: #666;">
                <div><strong>Latitude:</strong> <span id="dispLat"></span></div>
                <div><strong>Longitude:</strong> <span id="dispLong"></span></div>
                <div><strong>Jarak dari venue:</strong> <span id="dispDistance"></span> meter</div>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="button" class="btn-submit-attendance" id="btnSubmitAttendance" disabled>
            <i class="ti ti-check"></i> Submit Absensi
        </button>

        <div style="text-align: center; margin-top: 20px;">
            <a href="{{ route('qr-attendance.jamaah-list', ['token' => $token]) }}" style="color: white; text-decoration: none;">
                <i class="ti ti-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
        @endif
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS untuk Map -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        const token = '{{ $token }}';
        const kodeYayasan = '{{ $jamaah->kode_yayasan }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const venueLatitude = {{ $event->venue_latitude }};
        const venueLongitude = {{ $event->venue_longitude }};
        const venueRadius = {{ $event->venue_radius_meter }};
        
        let stream = null;
        let fotoWajahBase64 = null;
        let latitude = null;
        let longitude = null;
        let isFaceValid = false;
        let isLocationValid = false;
        let map = null;
        let faceApiModelsLoaded = false;
        let deviceId = null;
        
        // Data foto jamaah dari database (jika ada)
        const jamaahPhotoElement = document.getElementById('jamaahPhoto');
        const hasJamaahPhoto = jamaahPhotoElement && jamaahPhotoElement.tagName === 'IMG';
        const jamaahPhotoSrc = hasJamaahPhoto ? jamaahPhotoElement.src : null;
        
        // ===== GENERATE DEVICE FINGERPRINT =====
        function generateDeviceFingerprint() {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            ctx.textBaseline = 'top';
            ctx.font = '14px Arial';
            ctx.textBaseline = 'alphabetic';
            ctx.fillStyle = '#f60';
            ctx.fillRect(125, 1, 62, 20);
            ctx.fillStyle = '#069';
            ctx.fillText('Device Fingerprint', 2, 15);
            ctx.fillStyle = 'rgba(102, 204, 0, 0.7)';
            ctx.fillText('Device Fingerprint', 4, 17);
            
            const canvasData = canvas.toDataURL();
            
            const fingerprint = {
                canvas: canvasData,
                userAgent: navigator.userAgent,
                language: navigator.language,
                platform: navigator.platform,
                cores: navigator.hardwareConcurrency || 0,
                screen: `${screen.width}x${screen.height}x${screen.colorDepth}`,
                timezone: Intl.DateTimeFormat().resolvedOptions().timeZone,
                timestamp: Date.now()
            };
            
            // Simple hash function
            const str = JSON.stringify(fingerprint);
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                const char = str.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash;
            }
            
            return 'dev_' + Math.abs(hash).toString(36);
        }
        
        $(document).ready(function() {
            checkSubmitButton();
            
            // Generate device fingerprint
            deviceId = generateDeviceFingerprint();
            console.log('Device ID:', deviceId);
            
            // ⭐ VALIDASI & LOGGING FOTO JAMAAH
            console.log('=== FACE RECOGNITION VALIDATION ===');
            console.log('Has Jamaah Photo:', hasJamaahPhoto);
            console.log('Jamaah Photo Src:', jamaahPhotoSrc);
            console.log('Photo Element:', jamaahPhotoElement);
            
            // Peringatan jika tidak ada foto
            if (!hasJamaahPhoto) {
                Swal.fire({
                    icon: 'error',
                    title: '⚠️ Foto Tidak Ditemukan',
                    html: '<strong>Anda belum memiliki foto di database!</strong><br><br>' +
                          'Absensi menggunakan <strong>Face Recognition</strong> yang membutuhkan foto referensi.<br><br>' +
                          'Silakan hubungi admin untuk mendaftarkan foto wajah Anda terlebih dahulu.<br><br>' +
                          '<small>Anda tidak akan bisa melakukan absensi tanpa foto referensi.</small>',
                    confirmButtonColor: '#dc3545',
                    allowOutsideClick: false,
                    confirmButtonText: 'Saya Mengerti'
                });
            }
            
            // Load Face-API models
            if (hasJamaahPhoto) {
                loadFaceApiModels();
            } else {
                // Disable semua tombol jika tidak ada foto
                $('#btnStartCamera').prop('disabled', true).text('⚠️ Tidak Ada Foto Referensi');
                $('#statusFace').html('<i class="ti ti-alert-circle"></i> Anda belum terdaftar di sistem Face Recognition').addClass('status-error').show();
            }
        });
        
        // ===== LOAD FACE-API MODELS =====
        async function loadFaceApiModels() {
            try {
                console.log('Loading Face-API models...');
                const MODEL_URL = 'https://cdn.jsdelivr.net/npm/@vladmandic/face-api/model/';
                
                await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
                
                faceApiModelsLoaded = true;
                console.log('Face-API models loaded successfully');
            } catch (error) {
                console.error('Failed to load Face-API models:', error);
                faceApiModelsLoaded = false;
            }
        }
        
        // ===== FACE RECOGNITION =====
        $('#btnStartCamera').on('click', function() {
            startCamera();
        });
        
        $('#btnCapture').on('click', function() {
            capturePhoto();
        });
        
        $('#btnRetake').on('click', function() {
            retakePhoto();
        });
        
        function startCamera() {
            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: 'user',
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                }
            })
            .then(function(mediaStream) {
                stream = mediaStream;
                const video = document.getElementById('video');
                video.srcObject = stream;
                
                $('#cameraContainer').show();
                $('#btnStartCamera').hide();
                $('#btnCapture').show();
                $('#statusFace').html('<i class="ti ti-info-circle"></i> Posisikan wajah Anda dalam lingkaran').addClass('status-pending').show();
            })
            .catch(function(error) {
                console.error('Camera error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengakses Kamera',
                    text: 'Pastikan Anda mengizinkan akses kamera pada browser',
                    confirmButtonColor: '#667eea'
                });
            });
        }
        
        function capturePhoto() {
            const video = document.getElementById('video');
            const canvas = document.getElementById('canvas');
            const context = canvas.getContext('2d');
            
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            fotoWajahBase64 = canvas.toDataURL('image/png');
            
            // Show preview
            $('#photoPreview').attr('src', fotoWajahBase64).show();
            $('#cameraContainer').hide();
            $('#btnCapture').hide();
            $('#btnRetake').show();
            
            // Stop camera
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
            
            // ⭐ WAJIB VERIFY FACE - TIDAK BOLEH BYPASS!
            if (!hasJamaahPhoto) {
                // TOLAK jika tidak ada foto di database
                Swal.fire({
                    icon: 'error',
                    title: 'Tidak Ada Foto Referensi',
                    text: 'Anda belum memiliki foto di database. Silakan hubungi admin untuk mendaftarkan foto wajah Anda.',
                    confirmButtonColor: '#dc3545'
                });
                isFaceValid = false;
                $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                $('#statusFace').html('<i class="ti ti-alert-circle"></i> Tidak ada foto referensi di database').removeClass('status-pending status-success').addClass('status-error').show();
                retakePhoto();
                return;
            }
            
            if (!faceApiModelsLoaded) {
                // TOLAK jika model face recognition gagal load
                Swal.fire({
                    icon: 'error',
                    title: 'Face Recognition Tidak Siap',
                    text: 'Model face recognition gagal dimuat. Silakan refresh halaman dan coba lagi.',
                    confirmButtonColor: '#dc3545'
                });
                isFaceValid = false;
                $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                $('#statusFace').html('<i class="ti ti-alert-circle"></i> Model face recognition gagal dimuat').removeClass('status-pending status-success').addClass('status-error').show();
                retakePhoto();
                return;
            }
            
            // Lakukan verifikasi wajah (WAJIB!)
            verifyFace();
        }
        
        // ===== FACE VERIFICATION WITH FACE-API.JS =====
        async function verifyFace() {
            try {
                console.log('=== STARTING FACE VERIFICATION ===');
                $('#statusFace').html('<i class="ti ti-loader"></i> Memverifikasi wajah...').addClass('status-pending').show();
                
                // Load images
                console.log('Loading captured image...');
                const capturedImage = await faceapi.fetchImage(fotoWajahBase64);
                console.log('Captured image loaded');
                
                console.log('Loading reference image from:', jamaahPhotoSrc);
                const referenceImage = await faceapi.fetchImage(jamaahPhotoSrc);
                console.log('Reference image loaded');
                
                // Detect faces with landmarks and descriptors
                console.log('Detecting face in captured image...');
                const capturedDetection = await faceapi
                    .detectSingleFace(capturedImage, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                console.log('Captured detection result:', capturedDetection ? 'FOUND' : 'NOT FOUND');
                
                console.log('Detecting face in reference image...');
                const referenceDetection = await faceapi
                    .detectSingleFace(referenceImage, new faceapi.TinyFaceDetectorOptions())
                    .withFaceLandmarks()
                    .withFaceDescriptor();
                console.log('Reference detection result:', referenceDetection ? 'FOUND' : 'NOT FOUND');
                
                if (!capturedDetection) {
                    console.error('❌ FAILED: No face detected in captured image');
                    Swal.fire({
                        icon: 'error',
                        title: 'Wajah Tidak Terdeteksi',
                        html: 'Tidak dapat mendeteksi wajah pada foto yang Anda ambil.<br><br>' +
                              '<strong>Tips:</strong><br>' +
                              '✓ Pastikan pencahayaan cukup<br>' +
                              '✓ Wajah menghadap kamera<br>' +
                              '✓ Tidak ada yang menghalangi wajah<br>' +
                              '✓ Jarak tidak terlalu jauh/dekat',
                        confirmButtonColor: '#dc3545'
                    });
                    isFaceValid = false;
                    $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                    $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                    $('#statusFace').html('<i class="ti ti-alert-circle"></i> Wajah tidak terdeteksi pada foto Anda').removeClass('status-pending status-success').addClass('status-error').show();
                    checkSubmitButton();
                    return;
                }
                
                if (!referenceDetection) {
                    // ⭐ TOLAK jika foto referensi tidak valid (tidak ada wajah terdeteksi)
                    console.error('❌ FAILED: No face detected in reference photo from database');
                    Swal.fire({
                        icon: 'error',
                        title: 'Foto Referensi Tidak Valid',
                        html: '<strong>Foto Anda di database tidak dapat dikenali!</strong><br><br>' +
                              'Tidak ada wajah yang terdeteksi pada foto referensi Anda.<br><br>' +
                              'Kemungkinan:<br>' +
                              '• Foto terlalu blur atau gelap<br>' +
                              '• Foto bukan foto wajah<br>' +
                              '• Wajah terlalu kecil<br><br>' +
                              'Silakan hubungi admin untuk memperbarui foto referensi Anda dengan foto yang lebih jelas.',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Saya Mengerti'
                    });
                    isFaceValid = false;
                    $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                    $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                    $('#statusFace').html('<i class="ti ti-alert-circle"></i> Foto referensi di database tidak valid').removeClass('status-pending status-success').addClass('status-error').show();
                    checkSubmitButton();
                    return;
                }
                
                // Calculate distance between face descriptors (lower = more similar)
                const distance = faceapi.euclideanDistance(capturedDetection.descriptor, referenceDetection.descriptor);
                const threshold = 0.6; // Standard threshold for face matching
                const similarity = Math.round((1 - distance) * 100);
                
                console.log('=== FACE MATCHING RESULT ===');
                console.log('Distance:', distance);
                console.log('Threshold:', threshold);
                console.log('Similarity:', similarity + '%');
                console.log('Match:', distance < threshold ? 'YES ✅' : 'NO ❌');
                
                if (distance < threshold) {
                    // ✅ Face match!
                    console.log('✅ SUCCESS: Face verified!');
                    isFaceValid = true;
                    $('#iconFace').removeClass('icon-pending').addClass('icon-valid');
                    $('#iconFace i').removeClass('ti-camera').addClass('ti-check');
                    $('#statusFace').html('<i class="ti ti-check"></i> ✅ Wajah terverifikasi! (Kecocokan: ' + similarity + '%)').removeClass('status-pending').addClass('status-success').show();
                    
                    Swal.fire({
                        icon: 'success',
                        title: '✅ Wajah Terverifikasi!',
                        html: '<strong>Wajah Anda cocok dengan database!</strong><br><br>' +
                              'Tingkat Kecocokan: <strong>' + similarity + '%</strong><br>' +
                              'Minimum Required: <strong>' + Math.round((1 - threshold) * 100) + '%</strong><br><br>' +
                              'Silakan lanjutkan untuk melengkapi validasi GPS.',
                        confirmButtonColor: '#28a745',
                        timer: 3000
                    });
                    
                    checkSubmitButton();
                } else {
                    // ❌ Face doesn't match
                    console.error('❌ FAILED: Face does not match!');
                    Swal.fire({
                        icon: 'error',
                        title: '❌ Wajah Tidak Cocok',
                        html: '<strong>Wajah Anda tidak cocok dengan foto di database!</strong><br><br>' +
                              'Tingkat Kecocokan: <strong>' + similarity + '%</strong><br>' +
                              'Minimum Required: <strong>' + Math.round((1 - threshold) * 100) + '%</strong><br><br>' +
                              '<strong>Kemungkinan Penyebab:</strong><br>' +
                              '• Anda bukan orang yang terdaftar<br>' +
                              '• Pencahayaan buruk saat scan<br>' +
                              '• Wajah tertutup (masker, kacamata hitam)<br>' +
                              '• Posisi wajah terlalu miring<br><br>' +
                              'Silakan ambil foto ulang dengan kondisi lebih baik, atau hubungi admin jika ini adalah kesalahan.',
                        confirmButtonColor: '#dc3545',
                        confirmButtonText: 'Coba Lagi'
                    });
                    isFaceValid = false;
                    $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                    $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                    $('#statusFace').html('<i class="ti ti-alert-circle"></i> ❌ Wajah tidak cocok (Kecocokan: ' + similarity + '%)').removeClass('status-pending status-success').addClass('status-error').show();
                    checkSubmitButton();
                }
                
            } catch (error) {
                console.error('Face verification error:', error);
                
                // ⭐ TOLAK jika verifikasi error - TIDAK BOLEH BYPASS!
                Swal.fire({
                    icon: 'error',
                    title: 'Verifikasi Wajah Gagal',
                    html: 'Terjadi kesalahan saat memverifikasi wajah.<br><br>' +
                          '<strong>Error:</strong> ' + error.message + '<br><br>' +
                          'Silakan ambil foto ulang dengan kondisi:<br>' +
                          '✓ Pencahayaan cukup<br>' +
                          '✓ Wajah menghadap kamera<br>' +
                          '✓ Tidak ada yang menghalangi wajah',
                    confirmButtonColor: '#dc3545'
                });
                
                // TOLAK absensi
                isFaceValid = false;
                $('#iconFace').removeClass('icon-pending icon-valid').addClass('icon-invalid');
                $('#iconFace i').removeClass('ti-camera ti-check').addClass('ti-alert-circle');
                $('#statusFace').html('<i class="ti ti-alert-circle"></i> Verifikasi wajah gagal').removeClass('status-pending status-success').addClass('status-error').show();
                
                // Auto retake
                retakePhoto();
            }
        }
        
        function retakePhoto() {
            isFaceValid = false;
            fotoWajahBase64 = null;
            $('#photoPreview').hide();
            $('#btnRetake').hide();
            $('#btnStartCamera').show();
            $('#iconFace').removeClass('icon-valid').addClass('icon-pending');
            $('#iconFace i').removeClass('ti-check').addClass('ti-camera');
            $('#statusFace').html('<i class="ti ti-info-circle"></i> Menunggu validasi wajah').removeClass('status-success').addClass('status-pending');
            checkSubmitButton();
        }
        
        // ===== GPS LOCATION =====
        $('#btnGetLocation').on('click', function() {
            getLocation();
        });
        
        function getLocation() {
            $('#statusGPS').html('<i class="ti ti-loader"></i> Mendapatkan lokasi Anda...').addClass('status-pending').show();
            $('#btnGetLocation').prop('disabled', true);
            
            if (!navigator.geolocation) {
                Swal.fire({
                    icon: 'error',
                    title: 'GPS Tidak Didukung',
                    text: 'Browser Anda tidak mendukung GPS',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    latitude = position.coords.latitude;
                    longitude = position.coords.longitude;
                    
                    // Calculate distance
                    const distance = calculateDistance(latitude, longitude, venueLatitude, venueLongitude);
                    
                    $('#dispLat').text(latitude.toFixed(6));
                    $('#dispLong').text(longitude.toFixed(6));
                    $('#dispDistance').text(Math.round(distance));
                    $('#locationInfo').show();
                    
                    // Display map with location
                    displayMap(latitude, longitude, distance);
                    
                    if (distance <= venueRadius) {
                        isLocationValid = true;
                        $('#iconGPS').removeClass('icon-pending').addClass('icon-valid');
                        $('#iconGPS i').removeClass('ti-map-pin').addClass('ti-check');
                        $('#statusGPS').html('<i class="ti ti-check"></i> Lokasi Anda valid - Dalam radius venue').removeClass('status-pending').addClass('status-success');
                    } else {
                        isLocationValid = false;
                        $('#iconGPS').removeClass('icon-pending').addClass('icon-invalid');
                        $('#iconGPS i').removeClass('ti-map-pin').addClass('ti-alert-circle');
                        $('#statusGPS').html('<i class="ti ti-alert-circle"></i> Anda di luar radius venue (' + Math.round(distance) + 'm). Maksimal: ' + venueRadius + 'm').removeClass('status-pending').addClass('status-error');
                    }
                    
                    $('#btnGetLocation').prop('disabled', false);
                    checkSubmitButton();
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    let message = 'Gagal mendapatkan lokasi';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            message = 'Akses lokasi ditolak. Mohon izinkan akses lokasi';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            message = 'Lokasi tidak tersedia';
                            break;
                        case error.TIMEOUT:
                            message = 'Request timeout';
                            break;
                    }
                    
                    $('#statusGPS').html('<i class="ti ti-alert-circle"></i> ' + message).removeClass('status-pending').addClass('status-error');
                    $('#btnGetLocation').prop('disabled', false);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mendapatkan Lokasi',
                        text: message,
                        confirmButtonColor: '#667eea'
                    });
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }
        
        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth radius in meters
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;
            
            const a = Math.sin(Δφ/2) * Math.sin(Δφ/2) +
                      Math.cos(φ1) * Math.cos(φ2) *
                      Math.sin(Δλ/2) * Math.sin(Δλ/2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
            
            return R * c; // Distance in meters
        }
        
        // ===== DISPLAY MAP WITH LEAFLET =====
        function displayMap(userLat, userLng, distance) {
            $('#mapContainer').show();
            
            // Destroy existing map if any
            if (map) {
                map.remove();
            }
            
            // Create map centered between venue and user location
            const centerLat = (venueLatitude + userLat) / 2;
            const centerLng = (venueLongitude + userLng) / 2;
            
            map = L.map('mapContainer').setView([centerLat, centerLng], 15);
            
            // Add OpenStreetMap tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(map);
            
            // Add venue marker (center point)
            const venueIcon = L.divIcon({
                className: 'venue-marker',
                html: '<div style="background: #667eea; width: 30px; height: 30px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
            
            L.marker([venueLatitude, venueLongitude], { icon: venueIcon })
                .bindPopup('<b>📍 Lokasi Event</b><br>Venue center')
                .addTo(map);
            
            // Add red circle radius
            const circle = L.circle([venueLatitude, venueLongitude], {
                color: '#dc3545',
                fillColor: '#dc3545',
                fillOpacity: 0.15,
                radius: venueRadius,
                weight: 2
            }).addTo(map);
            
            circle.bindPopup('<b>Radius Event</b><br>' + venueRadius + ' meter');
            
            // Add user location marker
            const userIcon = L.divIcon({
                className: 'user-marker',
                html: '<div style="background: ' + (distance <= venueRadius ? '#28a745' : '#ffc107') + '; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 10px rgba(0,0,0,0.3);"></div>',
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            
            L.marker([userLat, userLng], { icon: userIcon })
                .bindPopup('<b>📱 Lokasi Anda</b><br>Jarak: ' + Math.round(distance) + ' meter<br>' + 
                          (distance <= venueRadius ? '<span style="color: #28a745;">✓ Dalam radius</span>' : '<span style="color: #dc3545;">✗ Di luar radius</span>'))
                .addTo(map);
            
            // Fit map to show both points
            const bounds = L.latLngBounds(
                [venueLatitude, venueLongitude],
                [userLat, userLng]
            );
            map.fitBounds(bounds, { padding: [50, 50] });
            
            // Force map to re-render
            setTimeout(() => {
                map.invalidateSize();
            }, 100);
        }
        
        // ===== SUBMIT ATTENDANCE =====
        function checkSubmitButton() {
            if (isFaceValid && isLocationValid) {
                $('#btnSubmitAttendance').prop('disabled', false);
            } else {
                $('#btnSubmitAttendance').prop('disabled', true);
            }
        }
        
        $('#btnSubmitAttendance').on('click', function() {
            if (!isFaceValid || !isLocationValid) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Validasi Belum Lengkap',
                    text: 'Pastikan validasi wajah dan lokasi sudah selesai',
                    confirmButtonColor: '#667eea'
                });
                return;
            }
            
            const btnSubmit = $(this);
            const originalText = btnSubmit.html();
            btnSubmit.prop('disabled', true).html('<span class="loading-spinner"></span> Menyimpan absensi...');
            
            $.ajax({
                url: '/absensi-qr/submit-validation',
                method: 'POST',
                data: {
                    _token: csrfToken,
                    token: token,
                    kode_yayasan: kodeYayasan,
                    latitude: latitude,
                    longitude: longitude,
                    foto_wajah: fotoWajahBase64,
                    device_id: deviceId  // Kirim device fingerprint
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Absensi Berhasil!',
                            html: response.message + '<br><br><strong>Total Kehadiran: ' + response.jumlah_kehadiran + 'x</strong>',
                            confirmButtonColor: '#28a745',
                            confirmButtonText: 'OK'
                        }).then(function() {
                            window.location.href = response.redirect_url;
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message,
                            confirmButtonColor: '#dc3545'
                        });
                        btnSubmit.prop('disabled', false).html(originalText);
                    }
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonColor: '#dc3545'
                    });
                    btnSubmit.prop('disabled', false).html(originalText);
                }
            });
        });
    </script>
</body>
</html>
