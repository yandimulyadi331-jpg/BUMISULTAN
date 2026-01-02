<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a6f 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .error-card {
            max-width: 500px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .error-icon {
            font-size: 80px;
            color: #ff6b6b;
        }
        .error-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 20px 0;
        }
        .error-message {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i class="ti {{ $icon ?? 'ti-alert-circle' }}"></i>
        </div>
        <div class="error-title">{{ $title }}</div>
        <div class="error-message">{{ $message }}</div>

        @if(isset($event))
        <div class="alert alert-light">
            <strong>{{ $event->event_name }}</strong><br>
            <small>{{ $event->event_date->format('d F Y') }}</small>
        </div>
        @endif

        <a href="{{ url('/') }}" class="btn btn-primary">
            <i class="ti ti-home"></i> Kembali
        </a>
    </div>
</body>
</html>
