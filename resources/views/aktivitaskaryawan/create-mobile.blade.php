@extends('layouts.mobile.app')
@section('content')
    <style>
        /* Global fix untuk memastikan scroll berfungsi */
        html, body {
            height: auto !important;
            overflow-x: hidden !important;
            overflow-y: auto !important;
            -webkit-overflow-scrolling: touch;
        }

        /* Reset untuk container utama */
        .appContainer {
            height: auto !important;
            min-height: 100vh;
            overflow: visible !important;
        }

        /* Tambahan agar kamera portrait dan rounded di semua device */
        .webcam-capture {
            width: 100%;
            height: 360px;
            margin: auto 20px;
            padding: 0;
            border-radius: 24px;
            overflow: hidden;
            background: #222;
            position: relative;
            box-shadow: 0 4px 24px rgba(44, 62, 80, 0.10);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .webcam-capture video,
        .webcam-capture canvas {
            width: 100% !important;
            height: 100% !important;
            object-fit: cover;
            border-radius: 24px !important;
            display: block;
        }

        canvas {
            position: absolute;
            border-radius: 0;
            box-shadow: none;
        }

        #facedetection {
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            height: 100%;
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
        }

        /* Perbaikan untuk posisi content-section */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 60px;
            min-height: 100vh;
            padding-bottom: 120px !important;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        }

        /* Pastikan body dapat scroll */
        body {
            overflow-y: auto !important;
            height: auto !important;
        }

        /* Fix untuk container utama */
        .aktivitas-content-modern {
            background: transparent;
            border-radius: 18px;
            padding: 18px 5px 24px 5px;
            margin: 10px 0;
            min-height: auto;
        }
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 60px !important;
            padding: 0 !important;
            position: relative;
            z-index: 1;
            overflow: hidden;
        }

        /* Style untuk tombol scan - overlay di depan kamera */
        .scan-buttons {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 1000;
            width: 100%;
            padding: 0 20px;
        }

        .scan-button {
            height: 45px !important;
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            width: 42%;
            border: none;
            font-weight: 600;
            font-size: 14px;
        }

        .scan-button.btn-success {
            background: #28a745 !important;
            color: white !important;
        }

        .scan-button.btn-warning {
            background: #ffc107 !important;
            color: #212529 !important;
        }

        .scan-button ion-icon {
            margin-right: 5px;
            font-size: 18px;
        }

        /* Style untuk image preview overlay */
        .image-preview-overlay {
            position: absolute;
            top: 50px;
            right: 10px;
            z-index: 500;
            width: 100px;
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            border: 3px solid #fff;
        }

        .image-preview-overlay img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Style untuk jam digital */
        .jam-digital-malasngoding {
            background-color: rgba(39, 39, 39, 0.7);
            position: absolute;
            top: 65px;
            right: 15px;
            z-index: 20;
            width: 150px;
            border-radius: 10px;
            padding: 5px;
            backdrop-filter: blur(5px);
        }

        .jam-digital-malasngoding p {
            color: #fff;
            font-size: 16px;
            text-align: left;
            margin-top: 0;
            margin-bottom: 0;
        }

        /* Modern Aktivitas Content Wrapper */
        .aktivitas-content-modern {
            background: transparent;
            border-radius: 18px;
            padding: 18px 5px 24px 5px;
            margin: 10px 0;
        }

        .aktivitas-content-modern,
        .aktivitas-content-modern * {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Absolute Tanggal & Jam */
        .abs-tanggal-modern {
            position: absolute;
            top: 12px;
            left: 30px;
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 600;
            color: #222;
            z-index: 10;
            backdrop-filter: blur(4px);
        }

        .abs-jam-modern {
            position: absolute;
            top: 12px;
            right: 30px;
            background: rgba(255, 255, 255, 0.75);
            box-shadow: 0 2px 8px rgba(44, 62, 80, 0.10);
            border-radius: 10px;
            padding: 4px 8px;
            font-size: 14px;
            font-weight: 600;
            color: #222;
            z-index: 10;
            letter-spacing: 1px;
            backdrop-filter: blur(4px);
        }



        /* Error Messages */
        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Form section styling untuk scroll yang smooth */
        .form-section {
            background: #fff;
            margin: 20px 10px;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .feedback-input {
            width: 100%;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            padding: 15px;
            font-size: 16px;
            margin-bottom: 15px;
            resize: vertical;
            min-height: 120px;
            font-family: 'Poppins', sans-serif;
        }

        .feedback-input:focus {
            border-color: #4CAF50;
            outline: none;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
        }

        /* Tombol simpan yang lebih prominent */
        .btn-simpan-aktivitas {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border: none;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: 600;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.3);
            transition: all 0.3s ease;
        }

        .btn-simpan-aktivitas:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(76, 175, 80, 0.4);
        }

        /* Pastikan scroll area cukup di mobile */
        @media (max-width: 768px) {
            #content-section {
                padding-bottom: 150px !important;
                margin-bottom: 50px;
            }

            .scan-buttons {
                position: absolute;
                bottom: -80px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 15;
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
                justify-content: center;
                width: 100%;
                padding: 0 20px;
            }

            .scan-button {
                flex: 1;
                max-width: 150px;
                min-width: 130px;
                white-space: nowrap;
                font-size: 13px;
                padding: 10px 8px;
            }
        }
    </style>

    <!-- Import Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('aktivitaskaryawan.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Tambah Aktivitas</div>
            <div class="right"></div>
        </div>
    </div>

    <div id="content-section">
        <div class="aktivitas-content-modern">
            <!-- Camera Section -->
            <div class="camera-section" style="position:relative; margin-bottom: 100px;">
                <div class="row" style="margin-top: 0;">
                    <div class="col" id="facedetection" style="position:relative;">
                        <!-- Absolute Tanggal & Jam -->
                        <div class="abs-tanggal-modern">{{ DateToIndo(date('Y-m-d')) }}</div>
                        <div class="abs-jam-modern"><span id="jam"></span></div>

                        <!-- Image Preview - Overlay di atas kamera -->
                        <div id="imagePreview" class="image-preview-overlay" style="display: none;">
                            <img id="previewImg" src="" alt="Preview">
                        </div>

                        <div class="webcam-capture"></div>

                        <!-- Scan Buttons - Overlay di depan kamera -->
                        <div class="scan-buttons">
                            <button type="button" class="btn btn-success scan-button" id="btnScan">
                                <ion-icon name="camera-outline"></ion-icon>
                                Ambil Foto
                            </button>
                            <button type="button" class="btn btn-warning scan-button" id="btnSwitch">
                                <ion-icon name="camera-reverse-outline"></ion-icon>
                                Ganti Kamera
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Section dengan styling yang lebih baik -->
        <div class="form-section">
            <form action="{{ route('aktivitaskaryawan.store') }}" method="POST" enctype="multipart/form-data" id="formAktivitas">
                @csrf

                <!-- Hidden NIK field for karyawan -->
                @if (auth()->user()->hasRole('karyawan'))
                    <input type="hidden" name="nik" value="{{ $karyawan->nik }}">
                @endif

                <!-- Hidden foto field -->
                <input type="hidden" name="foto" id="fotoData" value="">

                <!-- Hidden lokasi field - akan diisi otomatis dari geolocation -->
                <input type="hidden" name="lokasi" id="lokasiData" value="">

                <textarea placeholder="Deskripsikan aktivitas yang dilakukan..." class="feedback-input" name="aktivitas">{{ old('aktivitas') }}</textarea>
                @error('aktivitas')
                    <div class="error-message">{{ $message }}</div>
                @enderror

                <button type="submit" class="btn btn-primary w-100 btn-simpan-aktivitas" id="btnSimpan">
                    <i class="ti ti-send me-1"></i>Simpan Aktivitas
                </button>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            let stream = null;
            let currentFacingMode = 'user'; // 'user' untuk front camera, 'environment' untuk back camera
            let capturedImage = null;
            let currentLocation = null;

            // Update jam digital
            function updateJam() {
                const now = new Date();
                const jam = now.toLocaleTimeString('id-ID', {
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                $('#jam').text(jam);
            }

            // Update jam setiap detik
            setInterval(updateJam, 1000);
            updateJam();

            // Pastikan halaman dapat scroll dengan baik
            function ensureScrollable() {
                // Reset body dan html untuk memungkinkan scroll
                $('html, body').css({
                    'height': 'auto',
                    'overflow-y': 'auto',
                    'overflow-x': 'hidden',
                    '-webkit-overflow-scrolling': 'touch'
                });

                // Reset container jika ada
                $('.appContainer').css({
                    'height': 'auto',
                    'min-height': '100vh',
                    'overflow': 'visible'
                });

                // Check jika ada viewport yang terpotong
                setTimeout(() => {
                    const contentHeight = document.body.scrollHeight;
                    const viewportHeight = window.innerHeight;
                    
                    if (contentHeight > viewportHeight) {
                        // Tambahkan indikator scroll jika konten lebih panjang dari viewport
                        console.log('Konten dapat di-scroll');
                    }
                }, 1000);
            }

            // Jalankan fungsi scroll saat halaman dimuat
            ensureScrollable();

            // Scroll ke form jika user mengklik textarea
            $('textarea[name="aktivitas"]').on('focus', function() {
                setTimeout(() => {
                    $('html, body').animate({
                        scrollTop: $('.form-section').offset().top - 20
                    }, 300);
                }, 100);
            });

            // Smooth scroll ke tombol simpan jika diperlukan
            $('#btnSimpan').on('click', function(e) {
                // Pastikan form terlihat sebelum submit
                const formPosition = $('.form-section').offset().top;
                const currentScroll = $(window).scrollTop();
                const windowHeight = $(window).height();
                
                if (formPosition > currentScroll + windowHeight - 100) {
                    e.preventDefault();
                    $('html, body').animate({
                        scrollTop: formPosition - 50
                    }, 300, function() {
                        // Submit form setelah scroll selesai
                        $('#formAktivitas').submit();
                    });
                }
            });

            // Start camera
            function startCamera() {
                navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: currentFacingMode,
                        width: {
                            ideal: 640
                        },
                        height: {
                            ideal: 480
                        }
                    }
                }).then(function(mediaStream) {
                    stream = mediaStream;
                    const video = document.createElement('video');
                    video.srcObject = stream;
                    video.autoplay = true;
                    video.playsInline = true;
                    video.style.width = '100%';
                    video.style.height = '100%';
                    video.style.objectFit = 'cover';
                    video.style.borderRadius = '24px';

                    $('.webcam-capture').html(video);
                }).catch(function(err) {
                    console.error('Error accessing camera:', err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Tidak dapat mengakses kamera. Pastikan izin kamera sudah diberikan.',
                        confirmButtonText: 'OK'
                    });
                });
            }

            // Switch camera
            function switchCamera() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }

                currentFacingMode = currentFacingMode === 'user' ? 'environment' : 'user';
                startCamera();
            }

            // Capture photo
            function capturePhoto() {
                const video = $('.webcam-capture video')[0];
                if (!video) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Kamera belum siap. Tunggu sebentar.',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                const canvas = document.createElement('canvas');
                const context = canvas.getContext('2d');

                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;

                context.drawImage(video, 0, 0, canvas.width, canvas.height);

                capturedImage = canvas.toDataURL('image/jpeg', 0.8);

                // Show preview
                $('#previewImg').attr('src', capturedImage);
                $('#imagePreview').show();

                // Set hidden input
                $('#fotoData').val(capturedImage);

                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Foto berhasil diambil.',
                    confirmButtonText: 'OK'
                });
            }

            // Event listeners
            $('#btnScan').click(capturePhoto);
            $('#btnSwitch').click(switchCamera);

            // Get current location
            function getCurrentLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            currentLocation = position.coords.latitude + "," + position.coords.longitude;
                            $('#lokasiData').val(currentLocation);
                            console.log('Location obtained:', currentLocation);
                        },
                        function(error) {
                            console.error('Error getting location:', error);
                            Swal.fire({
                                icon: 'warning',
                                title: 'Peringatan',
                                text: 'Tidak dapat mendapatkan lokasi. Aktivitas akan disimpan tanpa lokasi.',
                                confirmButtonText: 'OK'
                            });
                        }, {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 60000
                        }
                    );
                } else {
                    console.log('Geolocation is not supported by this browser.');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Browser tidak mendukung geolocation. Aktivitas akan disimpan tanpa lokasi.',
                        confirmButtonText: 'OK'
                    });
                }
            }

            // Start camera on page load
            startCamera();

            // Get location on page load
            getCurrentLocation();

            // Form validation
            $('#formAktivitas').on('submit', function(e) {
                var aktivitas = $('textarea[name="aktivitas"]').val().trim();

                if (aktivitas === '') {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan isi deskripsi aktivitas terlebih dahulu',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                if (!capturedImage) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan!',
                        text: 'Silakan ambil foto terlebih dahulu',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }
            });

            // Auto-resize textarea
            $('textarea[name="aktivitas"]').on('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            // Cleanup on page unload
            $(window).on('beforeunload', function() {
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
            });
        });
    </script>
@endpush
