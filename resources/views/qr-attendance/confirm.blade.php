<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Konfirmasi Absensi - {{ $jamaah->nama }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 10px;
        }
        .confirm-card {
            background: white;
            border-radius: 20px;
            padding: 20px;
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
        }
        .jamaah-photo-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
            margin: 0 auto 15px;
        }
        .jamaah-photo-placeholder-large {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: bold;
            margin: 0 auto 15px;
        }
        .jamaah-name-large {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 13px;
        }
        .info-value {
            color: #333;
            font-size: 13px;
        }
        #map {
            height: 250px;
            width: 100%;
            border-radius: 15px;
            margin: 15px 0;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        .btn-hadir {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            border-radius: 10px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        .btn-hadir:active {
            transform: scale(0.95);
        }
        .btn-hadir.loading {
            pointer-events: none;
        }
        .btn-back {
            margin-top: 10px;
            font-size: 14px;
        }
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.7);
            z-index: 9999;
            align-items: center;
            justify-content: center;
        }
        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            max-width: 300px;
        }
        .spinner {
            width: 60px;
            height: 60px;
            border: 5px solid #f3f3f3;
            border-top: 5px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .pulse {
            animation: pulse 1.5s ease-in-out infinite;
        }
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        /* Toast Notification */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 10000;
        }
        .toast {
            background: white;
            padding: 15px 20px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: slideIn 0.3s ease-out;
            min-width: 250px;
        }
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        .toast.success {
            border-left: 4px solid #28a745;
        }
        .toast.error {
            border-left: 4px solid #dc3545;
        }
        .toast.warning {
            border-left: 4px solid #ffc107;
        }
        .toast-icon {
            font-size: 24px;
        }
        .toast-message {
            flex: 1;
            font-size: 14px;
        }
        .map-legend {
            background: white;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 10px;
            font-size: 12px;
            text-align: left;
        }
        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 5px 0;
        }
        .legend-circle {
            width: 12px;
            height: 12px;
            border-radius: 50%;
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

            <!-- Map Legend -->
            <div class="map-legend">
                <strong>Peta Lokasi:</strong>
                <div class="legend-item">
                    <div class="legend-circle" style="background: #667eea;"></div>
                    <span>Posisi Anda</span>
                </div>
                <div class="legend-item">
                    <div class="legend-circle" style="background: #dc3545; opacity: 0.3;"></div>
                    <span>Area Valid (Radius {{ $event->venue_radius_meter }}m)</span>
                </div>
            </div>

            <!-- Map Container -->
            <div id="map"></div>

            <div class="alert alert-info mt-2" style="font-size: 13px;">
                <i class="ti ti-info-circle"></i> <strong>{{ $event->event_name }}</strong><br>
                <small>{{ $event->event_date->format('d F Y') }} | {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}</small>
            </div>

            <button type="button" class="btn btn-success btn-hadir pulse" id="btnHadir">
                <i class="ti ti-check"></i> HADIR
            </button>

            <a href="{{ route('qr-attendance.jamaah-list', ['token' => $token]) }}" class="btn btn-link btn-back" id="btnKembali">
                <i class="ti ti-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer"></div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <p style="margin: 0; font-weight: bold;">Memproses Absensi...</p>
            <small style="color: #666;">Mohon tunggu sebentar</small>
        </div>
    </div>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Load jQuery from CDN as fallback -->
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script>
        // Toast Notification Function
        function showToast(message, type = 'info') {
            const toastContainer = document.getElementById('toastContainer');
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            
            let icon = 'ti-info-circle';
            if (type === 'success') icon = 'ti-check-circle';
            if (type === 'error') icon = 'ti-alert-circle';
            if (type === 'warning') icon = 'ti-alert-triangle';
            
            toast.innerHTML = `
                <i class="ti ${icon} toast-icon"></i>
                <div class="toast-message">${message}</div>
            `;
            
            toastContainer.appendChild(toast);
            
            // Haptic feedback (vibrate)
            if (navigator.vibrate) {
                navigator.vibrate(type === 'error' ? [100, 50, 100] : 100);
            }
            
            setTimeout(() => {
                toast.style.animation = 'slideIn 0.3s ease-out reverse';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Map Configuration
        let map, userMarker, geofenceCircle;
        const venueLatitude = {{ $event->venue_latitude }};
        const venueLongitude = {{ $event->venue_longitude }};
        const radiusMeters = {{ $event->venue_radius_meter }};

        // Initialize Map
        function initMap(userLat, userLng) {
            // Create map centered on user or venue
            map = L.map('map').setView([userLat || venueLatitude, userLng || venueLongitude], 16);

            // Add tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add geofence circle (red)
            geofenceCircle = L.circle([venueLatitude, venueLongitude], {
                color: '#dc3545',
                fillColor: '#dc3545',
                fillOpacity: 0.2,
                radius: radiusMeters
            }).addTo(map);

            // Add user marker if position available
            if (userLat && userLng) {
                userMarker = L.marker([userLat, userLng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background: #667eea; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3);"></div>',
                        iconSize: [20, 20]
                    })
                }).addTo(map);

                // Fit bounds to show both user and circle
                const bounds = L.latLngBounds([
                    [userLat, userLng],
                    [venueLatitude, venueLongitude]
                ]);
                map.fitBounds(bounds.pad(0.3));
            }
        }

        // Update user position on map
        function updateUserPosition(lat, lng) {
            if (userMarker) {
                userMarker.setLatLng([lat, lng]);
            } else {
                userMarker = L.marker([lat, lng], {
                    icon: L.divIcon({
                        className: 'custom-marker',
                        html: '<div style="background: #667eea; width: 20px; height: 20px; border-radius: 50%; border: 3px solid white; box-shadow: 0 2px 5px rgba(0,0,0,0.3); animation: pulse 1.5s infinite;"></div>',
                        iconSize: [20, 20]
                    })
                }).addTo(map);
            }
            map.setView([lat, lng], 16);
        }

        // Initialize map on load with venue position
        initMap(null, null);

        // Check if jQuery loaded, if not use vanilla JS
        if (typeof jQuery === 'undefined') {
            console.warn('jQuery not loaded, using vanilla JS');
            
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('btnHadir').addEventListener('click', function() {
                    console.log('Button HADIR clicked (Vanilla JS)');
                    handleAbsensi();
                });
            });
        } else {
            // jQuery loaded, use jQuery
            $(document).ready(function() {
                $('#btnHadir').click(function() {
                    console.log('Button HADIR clicked (jQuery)');
                    handleAbsensi();
                });
            });
        }

        function handleAbsensi() {
            // Vibrate haptic feedback
            if (navigator.vibrate) {
                navigator.vibrate(200);
            }
            
            // Request GPS location
            if (navigator.geolocation) {
                document.getElementById('loadingOverlay').style.display = 'flex';
                document.getElementById('btnHadir').classList.remove('pulse');
                document.getElementById('btnHadir').classList.add('loading');

                showToast('Mengambil lokasi GPS...', 'info');

                navigator.geolocation.getCurrentPosition(
                    function(position) {
                        console.log('GPS berhasil:', position.coords);
                        const userLat = position.coords.latitude;
                        const userLng = position.coords.longitude;
                        
                        // Update map with user position
                        updateUserPosition(userLat, userLng);
                        
                        showToast('Lokasi berhasil didapat', 'success');
                        
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
                                latitude: userLat,
                                longitude: userLng
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log('Absensi berhasil:', data);
                            
                            // Vibrate success
                            if (navigator.vibrate) {
                                navigator.vibrate([100, 50, 100, 50, 100]);
                            }
                            
                            // Sembunyikan tombol kembali setelah berhasil
                            document.getElementById('btnKembali').style.display = 'none';
                            
                            showToast('✓ Absensi Berhasil!', 'success');
                            
                            setTimeout(() => {
                                if (data.redirect_url) {
                                    window.location.href = data.redirect_url;
                                } else {
                                    window.location.href = '{{ route('qr-attendance.success') }}?kode_yayasan={{ $jamaah->kode_yayasan }}&event_id={{ $event->id }}';
                                }
                            }, 1500);
                        })
                        .catch(error => {
                            console.error('Error fetch:', error);
                            document.getElementById('loadingOverlay').style.display = 'none';
                            document.getElementById('btnHadir').classList.remove('loading');
                            document.getElementById('btnHadir').classList.add('pulse');
                            
                            // Vibrate error
                            if (navigator.vibrate) {
                                navigator.vibrate([100, 50, 100]);
                            }
                            
                            showToast('Terjadi kesalahan. Silakan coba lagi.', 'error');
                        });
                    },
                    function(error) {
                        console.error('GPS Error:', error);
                        document.getElementById('loadingOverlay').style.display = 'none';
                        document.getElementById('btnHadir').classList.remove('loading');
                        document.getElementById('btnHadir').classList.add('pulse');
                        
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
                        
                        // Vibrate error
                        if (navigator.vibrate) {
                            navigator.vibrate([100, 50, 100, 50, 100]);
                        }
                        
                        showToast(errorMessage, 'error');
                    },
                    {
                        enableHighAccuracy: true,
                        timeout: 10000,
                        maximumAge: 0
                    }
                );
            } else {
                showToast('Browser Anda tidak mendukung GPS', 'error');
            }
        }
    </script>
</body>
</html>

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
                                    
                                    // Sembunyikan tombol kembali setelah berhasil (Vanilla JS)
                                    var btnKembali = document.getElementById('btnKembali');
                                    if (btnKembali) {
                                        btnKembali.style.display = 'none';
                                    }
                                    
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
                                        
                                        // Sembunyikan tombol kembali setelah berhasil (jQuery)
                                        $('#btnKembali').hide();
                                        
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
