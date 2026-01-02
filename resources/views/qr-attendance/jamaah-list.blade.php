<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Jamaah - {{ $event->event_name }}</title>
    <link rel="stylesheet" href="{{ asset('assets/css/tabler.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/tabler-icons.min.css') }}">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .header-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        .search-box {
            margin-bottom: 20px;
        }
        .jamaah-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .jamaah-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .jamaah-photo {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
            flex-shrink: 0;
        }
        .jamaah-photo-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .jamaah-info {
            flex: 1;
            min-width: 0;
        }
        .jamaah-name {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .jamaah-detail {
            font-size: 13px;
            color: #666;
            margin: 3px 0;
        }
        .badge-kehadiran {
            background: #28a745;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .no-photo {
            text-align: center;
            color: #999;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header-card">
        <h3 class="mb-2">{{ $event->event_name }}</h3>
        <p class="text-muted mb-0">
            <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }} | 
            <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
        </p>
    </div>

    <div class="header-card search-box">
        <div class="input-group">
            <span class="input-group-text">
                <i class="ti ti-search"></i>
            </span>
            <input type="text" class="form-control" id="searchJamaah" placeholder="Cari nama atau NIK jamaah...">
        </div>
    </div>

    <div id="jamaahContainer">
        @forelse($jamaahList as $jamaah)
        <div class="jamaah-card" onclick="window.location.href='{{ route('qr-attendance.confirm', ['token' => $token, 'kode_yayasan' => $jamaah->kode_yayasan]) }}'">
            @if($jamaah->foto && file_exists(public_path('storage/uploads/karyawan/' . $jamaah->foto)))
                <img src="{{ asset('storage/uploads/karyawan/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="jamaah-photo">
            @else
                <div class="jamaah-photo-placeholder">
                    {{ strtoupper(substr($jamaah->nama, 0, 1)) }}
                </div>
            @endif
            
            <div class="jamaah-info">
                <div class="jamaah-name">{{ $jamaah->nama }}</div>
                <div class="jamaah-detail"><strong>No. Identitas:</strong> {{ $jamaah->no_identitas }}</div>
                <div class="jamaah-detail">
                    <strong>Status:</strong> 
                    @if($jamaah->status == 'K') Kontrak
                    @elseif($jamaah->status == 'T') Tetap
                    @elseif($jamaah->status == 'O') Outsourcing
                    @else {{ $jamaah->status }}
                    @endif
                </div>
                <div class="jamaah-detail"><strong>Tgl Masuk:</strong> {{ \Carbon\Carbon::parse($jamaah->tanggal_masuk)->format('d M Y') }}</div>
                @if($jamaah->jumlah_kehadiran > 0)
                <div class="mt-2">
                    <span class="badge-kehadiran">
                        <i class="ti ti-check"></i> {{ $jamaah->jumlah_kehadiran }}x Hadir
                    </span>
                </div>
                @endif
            </div>
            
            <div>
                <i class="ti ti-chevron-right" style="font-size: 24px; color: #667eea;"></i>
            </div>
        </div>
        @empty
        <div class="no-photo">
            <i class="ti ti-mood-sad" style="font-size: 64px;"></i>
            <p>Tidak ada data jamaah</p>
        </div>
        @endforelse
    </div>

    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Search functionality
            $('#searchJamaah').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('.jamaah-card').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });
        });
    </script>
</body>
</html>
