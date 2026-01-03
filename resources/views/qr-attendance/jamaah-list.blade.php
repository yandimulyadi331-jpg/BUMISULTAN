<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Daftar Jamaah - {{ $event->event_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
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
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .search-box {
            background: white;
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .search-box .input-group {
            position: relative;
        }
        .search-box .search-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #667eea;
            font-size: 20px;
            z-index: 10;
            pointer-events: none;
        }
        .search-box input {
            padding-left: 45px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            height: 50px;
        }
        .search-box input:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        .jamaah-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }
        .jamaah-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        .jamaah-card-header {
            display: flex;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 15px;
        }
        .jamaah-photo {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            object-fit: cover;
            border: 4px solid #667eea;
            flex-shrink: 0;
        }
        .jamaah-photo-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 42px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .jamaah-header-info {
            flex: 1;
            min-width: 0;
        }
        .jamaah-name {
            font-size: 22px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .jamaah-pin {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 8px;
        }
        .jamaah-body {
            border-top: 2px solid #f0f0f0;
            padding-top: 15px;
        }
        .jamaah-info-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 12px;
        }
        .jamaah-detail {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
            display: flex;
            align-items: flex-start;
        }
        .jamaah-detail i {
            margin-right: 8px;
            color: #667eea;
            font-size: 16px;
            margin-top: 2px;
        }
        .jamaah-detail strong {
            color: #333;
            margin-right: 5px;
        }
        .badge-kehadiran {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            display: inline-block;
            margin-top: 10px;
        }
        .no-photo {
            text-align: center;
            color: #999;
            padding: 40px 20px;
            background: white;
            border-radius: 15px;
            max-width: 800px;
            margin: 0 auto;
        }
        .chevron-icon {
            font-size: 28px;
            color: #667eea;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="header-card">
        <h3 class="mb-2"><i class="ti ti-calendar-event"></i> {{ $event->event_name }}</h3>
        <p class="text-muted mb-0">
            <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }} | 
            <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
        </p>
    </div>

    <div class="search-box">
        <div class="d-flex gap-2">
            <div class="input-group flex-grow-1">
                <i class="ti ti-search search-icon"></i>
                <input type="text" class="form-control" id="searchJamaah" placeholder="Cari nama, NIK, alamat, atau tempat lahir...">
            </div>
            <button class="btn btn-primary" id="btnSearch" style="border-radius: 10px; padding: 0 25px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; white-space: nowrap;">
                <i class="ti ti-search"></i> Cari
            </button>
        </div>
    </div>

    <div id="jamaahContainer">
        @forelse($jamaahList as $jamaah)
        <div class="jamaah-card" 
             data-nama="{{ strtolower($jamaah->nama) }}" 
             data-identitas="{{ $jamaah->no_identitas }}" 
             data-alamat="{{ strtolower($jamaah->alamat ?? '') }}"
             data-tempat-lahir="{{ strtolower($jamaah->tempat_lahir ?? '') }}"
             onclick="window.location.href='{{ route('qr-attendance.jamaah-attendance', ['token' => $token, 'kode_yayasan' => $jamaah->kode_yayasan]) }}'">
            
            <div class="jamaah-card-header">
                @if($jamaah->foto && (file_exists(public_path('storage/yayasan_masar/' . $jamaah->foto)) || file_exists(public_path('storage/jamaah/' . $jamaah->foto))))
                    @if(file_exists(public_path('storage/yayasan_masar/' . $jamaah->foto)))
                        <img src="{{ asset('storage/yayasan_masar/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="jamaah-photo">
                    @else
                        <img src="{{ asset('storage/jamaah/' . $jamaah->foto) }}" alt="{{ $jamaah->nama }}" class="jamaah-photo">
                    @endif
                @else
                    <div class="jamaah-photo-placeholder">
                        {{ strtoupper(substr($jamaah->nama, 0, 1)) }}
                    </div>
                @endif
                
                <div class="jamaah-header-info">
                    <div class="jamaah-name">{{ $jamaah->nama }}</div>
                    <div class="jamaah-pin">
                        <i class="ti ti-key"></i> PIN: {{ str_pad($jamaah->pin ?? '****', 4, '*') }}
                    </div>
                </div>
                
                <div class="d-flex align-items-center">
                    <i class="ti ti-chevron-right chevron-icon"></i>
                </div>
            </div>
            
            <div class="jamaah-body">
                <div class="jamaah-info-row">
                    <div class="jamaah-detail">
                        <i class="ti ti-id"></i>
                        <div><strong>No. Identitas:</strong> {{ $jamaah->no_identitas }}</div>
                    </div>
                    
                    <div class="jamaah-detail">
                        <i class="ti ti-map-pin"></i>
                        <div><strong>Alamat:</strong> {{ \Illuminate\Support\Str::limit($jamaah->alamat ?? '-', 50) }}</div>
                    </div>
                    
                    <div class="jamaah-detail">
                        <i class="ti ti-cake"></i>
                        <div>
                            <strong>TTL:</strong> 
                            {{ $jamaah->tempat_lahir ?? '-' }}, 
                            {{ $jamaah->tanggal_lahir ? \Carbon\Carbon::parse($jamaah->tanggal_lahir)->format('d M Y') : '-' }}
                            @if($jamaah->tanggal_lahir)
                                ({{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->age }} tahun)
                            @endif
                        </div>
                    </div>
                    
                    <div class="jamaah-detail">
                        <i class="ti ti-calendar-check"></i>
                        <div>
                            <strong>Tahun Masuk:</strong> 
                            {{ $jamaah->tanggal_masuk ? \Carbon\Carbon::parse($jamaah->tanggal_masuk)->format('Y') : '-' }}
                            @if($jamaah->tanggal_masuk)
                                ({{ \Carbon\Carbon::parse($jamaah->tanggal_masuk)->diffForHumans() }})
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($jamaah->jumlah_kehadiran > 0)
                <div>
                    <span class="badge-kehadiran">
                        <i class="ti ti-check-circle"></i> {{ $jamaah->jumlah_kehadiran }}x Hadir di Event
                    </span>
                </div>
                @endif
            </div>
        </div>
        @empty
        <div class="no-photo">
            <i class="ti ti-mood-sad" style="font-size: 64px; color: #ddd;"></i>
            <p class="mt-3"><strong>Tidak ada jamaah yang bisa melakukan absensi</strong></p>
            <p style="font-size: 14px; color: #666;">Hanya jamaah yang sudah memiliki foto yang dapat melakukan absensi.<br>Silakan upload foto jamaah terlebih dahulu.</p>
        </div>
        @endforelse
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function() {
            // Function to perform search
            function performSearch() {
                var searchValue = $('#searchJamaah').val().toLowerCase();
                
                $('.jamaah-card').each(function() {
                    var nama = $(this).data('nama') || '';
                    var identitas = $(this).data('identitas') || '';
                    var alamat = $(this).data('alamat') || '';
                    var tempatLahir = $(this).data('tempat-lahir') || '';
                    
                    // Search by nama, identitas, alamat, or tempat lahir
                    if (nama.indexOf(searchValue) > -1 || 
                        identitas.indexOf(searchValue) > -1 ||
                        alamat.indexOf(searchValue) > -1 ||
                        tempatLahir.indexOf(searchValue) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            
            // Enhanced search functionality with keyup
            $('#searchJamaah').on('keyup', function(e) {
                // Trigger search on keyup
                performSearch();
                
                // Also trigger on Enter key
                if (e.keyCode === 13) {
                    performSearch();
                }
            });
            
            // Click tombol Cari
            $('#btnSearch').on('click', function() {
                performSearch();
            });
            
            // Auto-focus search input on page load
            $('#searchJamaah').focus();
        });
    </script>
</body>
</html>
