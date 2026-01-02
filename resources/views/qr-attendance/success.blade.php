<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi Berhasil</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #51cf66 0%, #37b24d 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .success-card {
            max-width: 500px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .success-icon {
            font-size: 80px;
            color: #51cf66;
            animation: checkmark 0.6s ease-in-out;
        }
        @keyframes checkmark {
            0% {
                transform: scale(0);
                opacity: 0;
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
        .success-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .success-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .info-box {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #666;
        }
        .info-value {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="success-card">
        <div class="success-icon">
            <i class="ti ti-circle-check"></i>
        </div>
        <div class="success-title">Absensi Berhasil!</div>
        <div class="success-message">
            Jazakallah khair atas kehadiran Anda
        </div>

        @if($attendance && $event)
        <div class="info-box">
            <div class="info-row">
                <span class="info-label">Event:</span>
                <span class="info-value">{{ $event->event_name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu Absen:</span>
                <span class="info-value">{{ $attendance->jam_in->format('H:i:s') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal:</span>
                <span class="info-value">{{ $attendance->tanggal->format('d F Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Metode:</span>
                <span class="info-value">
                    <span class="badge bg-success">QR Code</span>
                </span>
            </div>
        </div>
        @endif

        <div class="mt-4">
            <p class="text-muted">
                <i class="ti ti-check-circle"></i> Kehadiran Anda telah tercatat di sistem
            </p>
        </div>

        <div class="mt-4">
            <a href="{{ route('qr-attendance.logout') }}" class="btn btn-outline-primary">
                <i class="ti ti-logout"></i> Selesai
            </a>
        </div>

        <div class="mt-4">
            <small class="text-muted">Semoga berkah ilmunya 🤲</small>
        </div>
    </div>

    <script>
        // Auto redirect after 10 seconds
        setTimeout(function() {
            window.location.href = '{{ route('qr-attendance.logout') }}';
        }, 10000);
    </script>
</body>
</html>
