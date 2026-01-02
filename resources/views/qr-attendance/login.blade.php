<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Absensi - {{ $event->event_name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            max-width: 450px;
            width: 100%;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo-section i {
            font-size: 64px;
            color: #667eea;
        }
        .event-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .event-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .event-info {
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="logo-section">
            <i class="ti ti-qrcode"></i>
        </div>

        <div class="event-header">
            <div class="event-title">{{ $event->event_name }}</div>
            <div class="event-info">
                <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }}
                <br>
                <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
            </div>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible">
                <div class="d-flex">
                    <div><i class="ti ti-alert-circle"></i></div>
                    <div class="ms-2">{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('qr-attendance.login.process') }}" method="POST" id="loginForm">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="mb-3">
                <label class="form-label">Nomor HP/WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-phone"></i></span>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required 
                        value="{{ old('no_hp') }}" autofocus>
                </div>
                <small class="form-hint">Nomor HP yang terdaftar di sistem</small>
            </div>

            <div class="mb-3">
                <label class="form-label">PIN</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-key"></i></span>
                    <input type="password" name="pin" class="form-control" placeholder="Masukkan PIN Anda" required maxlength="10">
                </div>
                <small class="form-hint">PIN yang sama dengan mesin fingerprint</small>
            </div>

            <button type="submit" class="btn btn-primary w-100 btn-lg">
                <i class="ti ti-login me-1"></i> Login & Absen
            </button>
        </form>

        <hr class="my-4">

        <div class="text-center">
            <small class="text-muted">
                <i class="ti ti-info-circle"></i> Lupa PIN? Hubungi Admin
            </small>
        </div>

        <div class="mt-3 text-center">
            <small class="text-muted">
                <i class="ti ti-shield-check"></i> Sistem Terenkripsi & Aman
            </small>
        </div>
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/tabler.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Format nomor HP
            $('input[name="no_hp"]').on('input', function() {
                let value = $(this).val().replace(/\D/g, ''); // Hanya angka
                $(this).val(value);
            });

            // Validasi form
            $('#loginForm').submit(function() {
                let noHp = $('input[name="no_hp"]').val();
                let pin = $('input[name="pin"]').val();

                if (noHp.length < 10) {
                    alert('Nomor HP minimal 10 digit');
                    return false;
                }

                if (pin.length < 3) {
                    alert('PIN minimal 3 digit');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
