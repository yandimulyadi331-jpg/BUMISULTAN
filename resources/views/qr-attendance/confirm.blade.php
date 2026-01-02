<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Absensi - {{ $jamaah->nama }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .confirm-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .jamaah-photo-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
            margin: 0 auto 20px;
        }
        .jamaah-photo-placeholder-large {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 64px;
            font-weight: bold;
            margin: 0 auto 20px;
        }
        .jamaah-name-large {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
        }
        .info-value {
            color: #333;
        }
        .btn-hadir {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 20px;
            border-radius: 10px;
        }
        .btn-back {
            margin-top: 10px;
        }
        .loading {
            display: none;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="confirm-card">
        <div id="confirmForm">
            @if($jamaah->foto && file_exists(storage_path('app/public/yayasan_masar/' . $jamaah->foto)))
                <img src="{{ asset('storage/yayasan_masar/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="jamaah-photo-large">
            @else
                <div class="jamaah-photo-placeholder-large">
                    {{ strtoupper(substr($jamaah->nama, 0, 1)) }}
                </div>
            @endif

            <div class="jamaah-name-large">{{ $jamaah->nama }}</div>
            
            <div class="card mt-3">
                <div class="card-body p-0">
                    <div class="info-row">
                        <span class="info-label">No. Identitas</span>
                        <span class="info-value">{{ $jamaah->no_identitas }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Nomor HP</span>
                        <span class="info-value">{{ $jamaah->no_hp }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">PIN</span>
                        <span class="info-value">{{ str_repeat('*', strlen($jamaah->pin)) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($jamaah->status == 'K') Kontrak
                            @elseif($jamaah->status == 'T') Tetap
                            @elseif($jamaah->status == 'O') Outsourcing
                            @else {{ $jamaah->status }}
                            @endif
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Tanggal Masuk</span>
                        <span class="info-value">{{ \Carbon\Carbon::parse($jamaah->tanggal_masuk)->format('d F Y') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Departemen</span>
                        <span class="info-value">{{ $jamaah->departemen->nama_dept ?? '-' }}</span>
                    </div>
                    <div class="info-row" style="border-bottom: none;">
                        <span class="info-label">Jumlah Kehadiran</span>
                        <span class="info-value">
                            <span class="badge bg-success">{{ $jumlahKehadiran }}x</span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3">
                <i class="ti ti-info-circle"></i> <strong>{{ $event->event_name }}</strong><br>
                <small>{{ $event->event_date->format('d F Y') }} | {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}</small>
            </div>

            <button type="button" class="btn btn-success btn-hadir" id="btnHadir">
                <i class="ti ti-check"></i> HADIR
            </button>

            <a href="{{ route('qr-attendance.jamaah-list', ['token' => $token]) }}" class="btn btn-link btn-back">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>

        <div class="loading" id="loadingDiv">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-3">Sedang memproses absensi...</p>
        </div>
    </div>

    <!-- Load jQuery from CDN as fallback -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // Check if jQuery loaded, if not use vanilla JS
        if (typeof jQuery === 'undefined') {
            console.warn('jQuery not loaded, using vanilla JS');
            
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('btnHadir').addEventListener('click', function() {
                    console.log('Button HADIR clicked (Vanilla JS)');
                    
                    // Request GPS location
                    if (navigator.geolocation) {
                        document.getElementById('confirmForm').style.display = 'none';
                        document.getElementById('loadingDiv').style.display = 'block';

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                console.log('GPS berhasil:', position.coords);
                                
                                // Submit absensi with fetch
                                fetch('{{ route('qr-attendance.submit-simple') }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        token: '{{ $token }}',
                                        kode_yayasan: '{{ $jamaah->kode_yayasan }}',
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log('Absensi berhasil:', data);
                                    if (data.redirect_url) {
                                        window.location.href = data.redirect_url;
                                    } else {
                                        window.location.href = '{{ route('qr-attendance.success') }}?kode_yayasan={{ $jamaah->kode_yayasan }}&event_id={{ $event->id }}';
                                    }
                                })
                                .catch(error => {
                                    console.error('Error fetch:', error);
                                    document.getElementById('loadingDiv').style.display = 'none';
                                    document.getElementById('confirmForm').style.display = 'block';
                                    alert('Terjadi kesalahan. Silakan coba lagi.');
                                });
                            },
                            function(error) {
                                console.error('GPS Error:', error);
                                document.getElementById('loadingDiv').style.display = 'none';
                                document.getElementById('confirmForm').style.display = 'block';
                                
                                var errorMessage = 'Gagal mendapatkan lokasi GPS. ';
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMessage += 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMessage += 'Informasi lokasi tidak tersedia.';
                                        break;
                                    case error.TIMEOUT:
                                        errorMessage += 'Waktu permintaan lokasi habis.';
                                        break;
                                    default:
                                        errorMessage += 'Error tidak diketahui.';
                                }
                                alert(errorMessage);
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        alert('Browser Anda tidak mendukung GPS');
                    }
                });
            });
        } else {
            // jQuery loaded, use jQuery
            $(document).ready(function() {
                $('#btnHadir').click(function() {
                    console.log('Button HADIR clicked (jQuery)');
                    
                    // Request GPS location
                    if (navigator.geolocation) {
                        $('#confirmForm').hide();
                        $('#loadingDiv').show();

                        navigator.geolocation.getCurrentPosition(
                            function(position) {
                                console.log('GPS berhasil:', position.coords);
                                
                                // Submit absensi
                                $.ajax({
                                    url: '{{ route('qr-attendance.submit-simple') }}',
                                    type: 'POST',
                                    data: {
                                        _token: '{{ csrf_token() }}',
                                        token: '{{ $token }}',
                                        kode_yayasan: '{{ $jamaah->kode_yayasan }}',
                                        latitude: position.coords.latitude,
                                        longitude: position.coords.longitude
                                    },
                                    success: function(response) {
                                        console.log('Absensi berhasil:', response);
                                        if (response.redirect_url) {
                                            window.location.href = response.redirect_url;
                                        } else {
                                            window.location.href = '{{ route('qr-attendance.success') }}?kode_yayasan={{ $jamaah->kode_yayasan }}&event_id={{ $event->id }}';
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('Error AJAX:', xhr.responseText);
                                        $('#loadingDiv').hide();
                                        $('#confirmForm').show();
                                        
                                        var message = 'Terjadi kesalahan. Silakan coba lagi.';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            message = xhr.responseJSON.message;
                                        }
                                        alert(message);
                                    }
                                });
                            },
                            function(error) {
                                console.error('GPS Error:', error);
                                $('#loadingDiv').hide();
                                $('#confirmForm').show();
                                
                                var errorMessage = 'Gagal mendapatkan lokasi GPS. ';
                                switch(error.code) {
                                    case error.PERMISSION_DENIED:
                                        errorMessage += 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.';
                                        break;
                                    case error.POSITION_UNAVAILABLE:
                                        errorMessage += 'Informasi lokasi tidak tersedia.';
                                        break;
                                    case error.TIMEOUT:
                                        errorMessage += 'Waktu permintaan lokasi habis.';
                                        break;
                                    default:
                                        errorMessage += 'Error tidak diketahui.';
                                }
                                alert(errorMessage);
                            },
                            {
                                enableHighAccuracy: true,
                                timeout: 10000,
                                maximumAge: 0
                            }
                        );
                    } else {
                        alert('Browser Anda tidak mendukung GPS');
                    }
                });
            });
        }
    </script>
</body>
</html>
