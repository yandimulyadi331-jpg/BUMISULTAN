<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi - {{ $event->event_name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .form-card {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .header-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .jamaah-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .gps-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .gps-status {
            font-size: 16px;
            margin-top: 10px;
        }
        .camera-preview {
            width: 100%;
            max-width: 300px;
            height: auto;
            border-radius: 10px;
            display: block;
            margin: 20px auto;
        }
        #video {
            width: 100%;
            border-radius: 10px;
        }
        .btn-capture {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="form-card">
        <div class="header-section">
            <div class="jamaah-name">Assalamu'alaikum</div>
            <div class="mt-2 text-muted">Silakan isi data untuk absensi</div>
        </div>

        <div class="alert alert-info">
            <div class="d-flex">
                <div><i class="ti ti-info-circle"></i></div>
                <div class="ms-2">
                    <strong>{{ $event->event_name }}</strong><br>
                    <small>{{ $event->event_date->format('d F Y') }} | {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}</small>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('qr-attendance.submit') }}" method="POST" id="formAbsensi" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <!-- Data Jamaah -->
            <div class="mb-3">
                <label class="form-label">Nomor HP <span class="text-danger">*</span></label>
                <input type="text" name="no_hp" class="form-control" placeholder="08xxxx" required autofocus>
            </div>

            <div class="mb-3">
                <label class="form-label">PIN <span class="text-danger">*</span></label>
                <input type="password" name="pin" class="form-control" placeholder="PIN Anda" required>
                <small class="text-muted">PIN yang sama dengan mesin fingerprint</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama (Opsional)</label>
                <input type="text" name="nama" class="form-control" placeholder="Nama lengkap">
                <small class="text-muted">Isi jika Anda jamaah baru / belum terdaftar</small>
            </div>

            <!-- GPS Section -->
            <div class="gps-box">
                <h5><i class="ti ti-map-pin"></i> Validasi Lokasi</h5>
                <div class="gps-status" id="gpsStatus">
                    <div class="spinner-border spinner-border-sm" role="status"></div>
                    <span class="ms-2">Mengambil lokasi GPS...</span>
                </div>
                <div id="gpsInfo" class="mt-3" style="display: none;">
                    <small class="text-muted">
                        Lat: <span id="dispLat"></span> | Long: <span id="dispLong"></span><br>
                        Jarak dari venue: <span id="distance" class="fw-bold"></span>
                    </small>
                </div>
            </div>

            <!-- Camera Section (Optional) -->
            <div class="mb-3">
                <label class="form-label">Foto Selfie (Opsional)</label>
                <div class="text-center">
                    <video id="video" autoplay style="display: none;"></video>
                    <canvas id="canvas" style="display: none;"></canvas>
                    <img id="photo" class="camera-preview" style="display: none;">
                    
                    <button type="button" class="btn btn-info" id="btnStartCamera">
                        <i class="ti ti-camera"></i> Aktifkan Kamera
                    </button>
                    <button type="button" class="btn btn-success" id="btnCapture" style="display: none;">
                        <i class="ti ti-capture"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-warning" id="btnRetake" style="display: none;">
                        <i class="ti ti-refresh"></i> Ulangi
                    </button>
                </div>
                <input type="hidden" name="photo_selfie" id="photoData">
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg" id="btnSubmit" disabled>
                <i class="ti ti-check"></i> Submit Absensi
            </button>
        </form>
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/tabler.min.js') }}"></script>
    <script>
        let video = document.getElementById('video');
        let canvas = document.getElementById('canvas');
        let photo = document.getElementById('photo');
        let stream = null;

        // Get GPS Location
        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition, showError, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } else {
                $('#gpsStatus').html('<span class="text-danger"><i class="ti ti-alert-circle"></i> Browser tidak mendukung GPS</span>');
            }
        }

        function showPosition(position) {
            let lat = position.coords.latitude;
            let lon = position.coords.longitude;
            
            $('#latitude').val(lat);
            $('#longitude').val(lon);
            $('#dispLat').text(lat.toFixed(6));
            $('#dispLong').text(lon.toFixed(6));

            // Calculate distance (simple estimation)
            let venueLat = {{ $event->venue_latitude }};
            let venueLon = {{ $event->venue_longitude }};
            let distance = calculateDistance(lat, lon, venueLat, venueLon);

            $('#distance').text(distance.toFixed(0) + ' meter');
            $('#gpsInfo').show();

            if (distance <= {{ $event->venue_radius_meter }}) {
                $('#gpsStatus').html('<span class="text-success"><i class="ti ti-check"></i> Lokasi Valid - Dalam Area</span>');
                $('#btnSubmit').prop('disabled', false);
            } else {
                $('#gpsStatus').html('<span class="text-danger"><i class="ti ti-alert-circle"></i> Anda di Luar Area Venue (Jarak: ' + distance.toFixed(0) + 'm)</span>');
                $('#btnSubmit').prop('disabled', true);
            }
        }

        function showError(error) {
            let msg = '';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    msg = 'GPS diblokir. Mohon izinkan akses lokasi di browser';
                    break;
                case error.POSITION_UNAVAILABLE:
                    msg = 'Informasi lokasi tidak tersedia';
                    break;
                case error.TIMEOUT:
                    msg = 'Request GPS timeout. Coba lagi';
                    break;
                default:
                    msg = 'Error GPS tidak diketahui';
            }
            $('#gpsStatus').html('<span class="text-danger"><i class="ti ti-alert-circle"></i> ' + msg + '</span>');
        }

        function calculateDistance(lat1, lon1, lat2, lon2) {
            const R = 6371e3; // Earth radius in meters
            const φ1 = lat1 * Math.PI / 180;
            const φ2 = lat2 * Math.PI / 180;
            const Δφ = (lat2 - lat1) * Math.PI / 180;
            const Δλ = (lon2 - lon1) * Math.PI / 180;

            const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +
                      Math.cos(φ1) * Math.cos(φ2) *
                      Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
            const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));

            return R * c;
        }

        // Camera Functions
        $('#btnStartCamera').click(function() {
            navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
                .then(function(s) {
                    stream = s;
                    video.srcObject = stream;
                    $('#video').show();
                    $('#btnStartCamera').hide();
                    $('#btnCapture').show();
                })
                .catch(function(err) {
                    alert('Tidak dapat mengakses kamera: ' + err.message);
                });
        });

        $('#btnCapture').click(function() {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            canvas.getContext('2d').drawImage(video, 0, 0);
            
            let dataURL = canvas.toDataURL('image/jpeg');
            photo.src = dataURL;
            $('#photo').show();
            $('#photoData').val(dataURL);
            
            $('#video').hide();
            $('#btnCapture').hide();
            $('#btnRetake').show();
            
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });

        $('#btnRetake').click(function() {
            $('#photo').hide();
            $('#photoData').val('');
            $('#btnStartCamera').show();
            $('#btnRetake').hide();
        });

        $(document).ready(function() {
            getLocation();

            // Auto refresh GPS every 30 seconds
            setInterval(getLocation, 30000);
        });
    </script>
</body>
</html>
