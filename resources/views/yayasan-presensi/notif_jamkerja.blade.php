@extends('layouts.mobile.app')
@section('content')
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .notification-container {
            background: white;
            border-radius: 15px;
            padding: 40px 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            animation: slideInUp 0.5s ease-out;
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .icon {
            font-size: 60px;
            margin-bottom: 20px;
        }

        .message {
            font-size: 20px;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .description {
            font-size: 14px;
            color: #666;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: center;
        }

        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>

    <div class="notification-container">
        <div class="icon">
            <i class="ti ti-info-circle" style="color: #667eea;"></i>
        </div>
        <div class="message">Jam Kerja Belum Dikonfigurasi</div>
        <div class="description">
            Silahkan hubungi administrator untuk mengkonfigurasi jam kerja Anda terlebih dahulu sebelum melakukan absensi.
        </div>
        <div class="button-group">
            <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
