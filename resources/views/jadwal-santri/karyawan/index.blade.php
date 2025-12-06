@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Minimal Elegant Colors */
            --bg-body-light: #ecf0f3;
            --bg-primary-light: #ecf0f3;
            --shadow-dark-light: #d1d9e6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2c3e50;
            --text-secondary-light: #6c7a89;
            --border-light: rgba(0, 0, 0, 0.05);

            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #141923;
            --shadow-light-dark: #3a4555;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);

            /* Accent Colors */
            --gradient-1: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            --gradient-2: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-3: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        }

        /* Apply light mode by default */
        body {
            --bg-body: var(--bg-body-light);
            --bg-primary: var(--bg-primary-light);
            --shadow-dark: var(--shadow-dark-light);
            --shadow-light: var(--shadow-light-light);
            --text-primary: var(--text-primary-light);
            --text-secondary: var(--text-secondary-light);
            --border-color: var(--border-light);
        }

        /* Dark mode */
        body.dark-mode {
            --bg-body: var(--bg-body-dark);
            --bg-primary: var(--bg-primary-dark);
            --shadow-dark: var(--shadow-dark-dark);
            --shadow-light: var(--shadow-light-dark);
            --text-primary: var(--text-primary-dark);
            --text-secondary: var(--text-secondary-dark);
            --border-color: var(--border-dark);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-body);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* ========== HEADER ========== */
        #header-section {
            background: var(--bg-primary);
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
            width: 100%;
        }

        .back-btn {
            background: var(--bg-primary);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            flex-shrink: 0;
        }

        .back-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .back-btn ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .header-title {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header-title h3 {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1.2;
            letter-spacing: -0.5px;
            margin: 0;
        }

        .header-title p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            font-weight: 600;
            margin-top: 5px;
            letter-spacing: 0.3px;
        }

        /* ========== CONTENT ========== */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        /* ========== INFO BOX ========== */
        .info-box {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .info-box i {
            color: var(--text-primary);
            font-size: 1.5rem;
        }

        .info-box small {
            color: var(--text-primary);
            font-weight: 600;
        }

        .info-box strong {
            color: var(--text-primary);
        }

        /* ========== JADWAL CARD ========== */
        .jadwal-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .jadwal-card:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .jadwal-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 15px;
        }

        .jadwal-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 5px;
            letter-spacing: -0.3px;
        }

        .jadwal-desc {
            font-size: 0.8rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .badge-custom {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .badge-aktif {
            background: var(--gradient-2);
            color: white;
        }

        .badge-nonaktif {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
            color: white;
        }

        .badge-tipe {
            background: var(--gradient-1);
            color: white;
        }

        .jadwal-info {
            margin: 15px 0;
        }

        .info-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border-color);
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-item i {
            width: 35px;
            color: var(--text-secondary);
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .info-item .label {
            font-weight: 700;
            min-width: 100px;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .info-item .value {
            color: var(--text-primary);
            font-weight: 700;
        }

        .btn-view {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: none;
            border-radius: 15px;
            padding: 12px 20px;
            font-size: 0.85rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            width: 100%;
            justify-content: center;
        }

        .btn-view:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        .berlangsung-badge {
            background: var(--gradient-3);
            color: white;
            padding: 10px 15px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        .no-data {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .no-data-icon {
            font-size: 80px;
            opacity: 0.5;
            margin-bottom: 20px;
        }

        .no-data strong {
            color: var(--text-primary);
            font-size: 1.2rem;
            display: block;
            margin-bottom: 10px;
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Jadwal Santri</h3>
                <p>Saung Santri - Bumi Sultan</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ti ti-check me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- Info Box --}}
        <div class="info-box">
            <div class="d-flex align-items-center">
                <i class="ti ti-info-circle me-2" style="font-size: 1.5rem;"></i>
                <div>
                    <small><strong>Info:</strong> Anda dapat melihat jadwal dan rekap absensi santri.</small>
                </div>
            </div>
        </div>

        {{-- List Jadwal --}}
        @forelse($jadwalList as $jadwal)
            <div class="jadwal-card">
                <div class="jadwal-header">
                    <div style="flex: 1;">
                        <div class="jadwal-title">{{ $jadwal->nama_jadwal }}</div>
                        @if($jadwal->deskripsi)
                            <div class="jadwal-desc">{{ Str::limit($jadwal->deskripsi, 60) }}</div>
                        @endif
                    </div>
                    <div>
                        @if($jadwal->status == 'aktif')
                            <span class="badge-custom badge-aktif">Aktif</span>
                        @else
                            <span class="badge-custom badge-nonaktif">Nonaktif</span>
                        @endif
                    </div>
                </div>

                <div class="mt-2">
                    <span class="badge-custom badge-tipe">{{ ucfirst($jadwal->tipe_jadwal) }}</span>
                </div>

                <div class="jadwal-info">
                    <div class="info-item">
                        <i class="ti ti-calendar"></i>
                        <span class="label">Jadwal:</span>
                        <span class="value">
                            @if($jadwal->tipe_jadwal == 'harian')
                                Setiap Hari
                            @elseif($jadwal->tipe_jadwal == 'mingguan')
                                {{ $jadwal->hari }}
                            @else
                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d M Y') }}
                            @endif
                        </span>
                    </div>

                    <div class="info-item">
                        <i class="ti ti-clock"></i>
                        <span class="label">Waktu:</span>
                        <span class="value">
                            {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - 
                            {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}
                        </span>
                    </div>

                    @if($jadwal->tempat)
                    <div class="info-item">
                        <i class="ti ti-map-pin"></i>
                        <span class="label">Tempat:</span>
                        <span class="value">{{ $jadwal->tempat }}</span>
                    </div>
                    @endif

                    @if($jadwal->pembimbing)
                    <div class="info-item">
                        <i class="ti ti-user"></i>
                        <span class="label">Pembimbing:</span>
                        <span class="value">{{ $jadwal->pembimbing }}</span>
                    </div>
                    @endif
                </div>

                @if($jadwal->is_berlangsung && $jadwal->status == 'aktif')
                    <div class="berlangsung-badge">
                        <i class="ti ti-live-photo"></i> Sedang Berlangsung
                    </div>
                @endif

                <div class="mt-3">
                    <a href="{{ route('jadwal-santri.karyawan.show', $jadwal->id) }}" class="btn-view">
                        <i class="ti ti-eye"></i> Lihat Detail & Absensi
                    </a>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="ti ti-calendar-off"></i>
                </div>
                <p><strong>Belum ada jadwal santri</strong></p>
                <small>Belum ada data jadwal yang tersedia</small>
            </div>
        @endforelse

        <div style="height: 80px;"></div>
    </div>

    @push('myscript')
    <script>
        // Auto hide alert setelah 3 detik
        @if(session('success'))
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 3000);
        @endif
    </script>
    @endpush
@endsection
