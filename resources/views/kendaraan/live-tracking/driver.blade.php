<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Tracking - {{ $aktivitas->kendaraan->nama_kendaraan }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .tracking-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 30px;
            max-width: 500px;
            margin: 0 auto;
        }
        .status-indicator {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }
        .status-active { background: #10b981; }
        .status-sending { background: #f59e0b; }
        .status-stopped { background: #ef4444; }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .info-item {
            background: #f8fafc;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 10px;
        }
        #stopBtn {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="tracking-card">
        <div class="text-center mb-4">
            <i class="ti ti-car" style="font-size: 60px; color: #667eea;"></i>
            <h3 class="mt-3">{{ $aktivitas->kendaraan->nama_kendaraan }}</h3>
            <p class="text-muted mb-0">{{ $aktivitas->kendaraan->no_polisi }}</p>
        </div>

        <div class="info-item">
            <small class="text-muted">Pengemudi</small>
            <div><strong>{{ $aktivitas->nama_pengemudi }}</strong></div>
        </div>

        <div class="info-item">
            <small class="text-muted">Tujuan</small>
            <div><strong>{{ $aktivitas->tujuan }}</strong></div>
        </div>

        <div class="info-item">
            <small class="text-muted">Waktu Keluar</small>
            <div><strong>{{ \Carbon\Carbon::parse($aktivitas->waktu_keluar)->format('d/m/Y H:i') }}</strong></div>
        </div>

        <div class="alert alert-info mt-4">
            <div class="d-flex align-items-center">
                <span class="status-indicator status-active me-2" id="statusIndicator"></span>
                <div>
                    <strong id="statusText">Memulai tracking...</strong>
                    <div><small id="statusDetail">GPS sedang diinisialisasi</small></div>
                </div>
            </div>
        </div>

        <div class="row text-center mt-4 mb-4">
            <div class="col-6">
                <div class="info-item">
                    <small class="text-muted">Kecepatan</small>
                    <h4 class="mb-0" id="speedDisplay">0</h4>
                    <small class="text-muted">km/h</small>
                </div>
            </div>
            <div class="col-6">
                <div class="info-item">
                    <small class="text-muted">Update Terakhir</small>
                    <h4 class="mb-0" id="lastUpdateDisplay">--:--</h4>
                    <small class="text-muted">detik lalu</small>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-danger" id="stopBtn" onclick="confirmStop()">
            <i class="ti ti-square-rounded-x me-2"></i>Stop Tracking
        </button>

        <div class="text-center mt-3">
            <small class="text-muted">Data akan dikirim setiap 30 detik</small>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let trackingInterval;
        let lastUpdateTime = Date.now();
        let lastPosition = null;
        let updateCounter = 0;

        // Start tracking otomatis saat page load
        window.onload = function() {
            startTracking();
            setInterval(updateLastUpdateTime, 1000); // Update display setiap detik
        };

        function startTracking() {
            if (!navigator.geolocation) {
                showError('GPS tidak didukung di browser ini');
                return;
            }

            updateStatus('active', 'Tracking Aktif', 'Mengirim lokasi setiap 30 detik');

            // Kirim GPS pertama kali immediately
            sendGPS();

            // Lalu kirim setiap 30 detik
            trackingInterval = setInterval(sendGPS, 30000);
        }

        function sendGPS() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const gpsData = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        speed: position.coords.speed ? (position.coords.speed * 3.6) : 0, // m/s to km/h
                        accuracy: position.coords.accuracy,
                        _token: document.querySelector('meta[name="csrf-token"]').content
                    };

                    // Update display
                    document.getElementById('speedDisplay').textContent = Math.round(gpsData.speed);
                    lastUpdateTime = Date.now();
                    updateCounter++;

                    // Ubah status indicator saat sending
                    updateStatus('sending', 'Mengirim...', `Update ke-${updateCounter}`);

                    // Kirim ke server
                    fetch("{{ route('api.gps.store', Crypt::encrypt($aktivitas->id)) }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': gpsData._token
                        },
                        body: JSON.stringify(gpsData)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateStatus('active', 'Tracking Aktif', `Berhasil dikirim (${updateCounter}x)`);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        updateStatus('stopped', 'Error', 'Gagal mengirim data');
                    });
                },
                function(error) {
                    showError('GPS error: ' + error.message);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 0
                }
            );
        }

        function updateStatus(type, text, detail) {
            const indicator = document.getElementById('statusIndicator');
            indicator.className = 'status-indicator status-' + type;
            document.getElementById('statusText').textContent = text;
            document.getElementById('statusDetail').textContent = detail;
        }

        function updateLastUpdateTime() {
            const seconds = Math.floor((Date.now() - lastUpdateTime) / 1000);
            document.getElementById('lastUpdateDisplay').textContent = seconds;
        }

        function confirmStop() {
            Swal.fire({
                title: 'Stop Tracking?',
                text: 'Apakah Anda yakin ingin menghentikan tracking?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Stop',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    stopTracking();
                }
            });
        }

        function stopTracking() {
            clearInterval(trackingInterval);
            updateStatus('stopped', 'Tracking Dihentikan', 'Anda dapat menutup halaman ini');
            document.getElementById('stopBtn').disabled = true;
            
            Swal.fire({
                icon: 'success',
                title: 'Tracking Dihentikan',
                text: 'Terima kasih! Anda dapat menutup halaman ini.',
                confirmButtonText: 'OK'
            });
        }

        function showError(message) {
            updateStatus('stopped', 'Error', message);
            Swal.fire({
                icon: 'error',
                title: 'GPS Error',
                text: message
            });
        }

        // Prevent accidental close
        window.onbeforeunload = function() {
            if (trackingInterval) {
                return "Tracking masih aktif. Yakin ingin keluar?";
            }
        };
    </script>
</body>
</html>
