<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sudah Absen</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #ffd93d 0%, #f9ca24 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 500px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .icon {
            font-size: 80px;
            color: #ffd93d;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">
            <i class="ti ti-check-circle"></i>
        </div>
        <h2 class="mt-3">Anda Sudah Absen</h2>
        <p class="text-muted">Absensi Anda untuk event ini sudah tercatat sebelumnya.</p>
        
        <div class="alert alert-info mt-3">
            <strong>{{ $event->event_name }}</strong><br>
            <small>Waktu Absen: {{ $attendance->jam_in->format('H:i:s') }}</small>
        </div>

        <a href="{{ route('qr-attendance.logout') }}" class="btn btn-primary mt-3">
            <i class="ti ti-logout"></i> Selesai
        </a>
    </div>
</body>
</html>
