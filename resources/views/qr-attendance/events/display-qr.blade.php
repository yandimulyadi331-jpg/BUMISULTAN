<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR Code - {{ $event->event_name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .qr-container {
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            text-align: center;
            max-width: 600px;
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .event-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        .event-info {
            font-size: 18px;
            color: #666;
            margin-bottom: 30px;
        }
        .qr-box {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 20px;
            margin: 30px 0;
            display: inline-block;
        }
        #qrImage {
            max-width: 400px;
            height: auto;
            transition: all 0.3s ease;
        }
        .countdown {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
            margin-top: 20px;
        }
        .countdown.warning {
            color: #ff6b6b;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .status-badge {
            display: inline-block;
            padding: 10px 20px;
            border-radius: 50px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 20px;
        }
        .badge-success { background: #51cf66; color: white; }
        .badge-warning { background: #ffd93d; color: #333; }
        .badge-danger { background: #ff6b6b; color: white; }
        .loading {
            text-align: center;
            padding: 50px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #e9ecef;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
        }
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="qr-container">
        <div class="event-title">{{ $event->event_name }}</div>
        <div class="event-info">
            <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }}
            <br>
            <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
            <br>
            <i class="ti ti-map-pin"></i> {{ $event->venue_name ?? 'Venue' }}
        </div>

        <div id="qrDisplay">
            @if($activeQR)
                <div class="qr-box">
                    <img src="data:image/svg+xml;base64,{{ base64_encode(\SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(400)->generate(route('qr-attendance.scan', ['token' => $activeQR->qr_token]))) }}" 
                        id="qrImage" alt="QR Code">
                </div>
                <div class="status-badge badge-success">
                    <i class="ti ti-check"></i> QR Code Aktif - Scan untuk Absensi
                </div>
                <div class="alert alert-info mt-3">
                    <i class="ti ti-info-circle"></i> QR Code ini permanent dan bisa ditempel di dinding. 
                    Absensi hanya bisa dilakukan pada hari dan jam event.
                </div>
            @else
                <div class="loading">
                    <div class="spinner"></div>
                    <p class="mt-3">Generate QR Code...</p>
                </div>
            @endif
        </div>

        <div class="stats" id="stats">
            <div class="stat-item">
                <div class="stat-value" id="totalAttendance">0</div>
                <div class="stat-label">Total Kehadiran</div>
            </div>
            <div class="stat-item">
                <div class="stat-value" id="recentScans">0</div>
                <div class="stat-label">Scan Terakhir</div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        let refreshInterval;

        function generateQR() {
            $.ajax({
                url: '{{ route("qr-attendance.events.generate-qr", $event->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        $('#qrDisplay').html(`
                            <div class="qr-box">
                                <img src="${response.data.qr_image}" id="qrImage" alt="QR Code" style="max-width: 400px;">
                            </div>
                            <div class="status-badge badge-success">
                                <i class="ti ti-check"></i> QR Code Aktif - Scan untuk Absensi
                            </div>
                            <div class="alert alert-info mt-3">
                                <i class="ti ti-info-circle"></i> QR Code ini permanent dan bisa ditempel di dinding. 
                                Absensi hanya bisa dilakukan pada hari dan jam event.
                            </div>
                        `);
                    }
                },
                error: function() {
                    $('#qrDisplay').html(`
                        <div class="status-badge badge-danger">
                            <i class="ti ti-alert-circle"></i> Gagal Generate QR Code
                        </div>
                    `);
                }
            });
        }

        function updateStats() {
            $.ajax({
                url: '{{ route("qr-attendance.events.statistics", $event->id) }}',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        $('#totalAttendance').text(response.data.total_attendance);
                        $('#recentScans').text(response.data.successful_scans);
                    }
                }
            });
        }

        $(document).ready(function() {
            @if(!$activeQR)
                generateQR();
            @endif

            // Refresh stats every 5 seconds
            updateStats();
            refreshInterval = setInterval(updateStats, 5000);
        });
    </script>
</body>
</html>
