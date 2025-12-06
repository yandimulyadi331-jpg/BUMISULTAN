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
            --gradient-2: linear-gradient(135deg, #2563eb 0%, #3b82f6 100%);
            --gradient-3: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
            --gradient-4: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
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

        html {
            scroll-behavior: smooth;
        }

        body {
            background: var(--bg-body);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .santri-card {
            animation: fadeInUp 0.5s ease-out forwards;
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

        /* ========== FILTER SECTION ========== */
        .search-box {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .filter-input {
            background: var(--bg-primary);
            border: none;
            padding: 12px 18px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
            width: 100%;
        }

        .filter-input::placeholder {
            color: var(--text-secondary);
        }

        .filter-input:focus {
            outline: none;
        }

        .filter-select {
            background: var(--bg-primary);
            border: none;
            padding: 10px 12px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 600;
            width: 100%;
        }

        .filter-select:focus {
            outline: none;
        }

        .btn-filter, .btn-reset {
            background: var(--bg-primary);
            border: none;
            padding: 10px 20px;
            border-radius: 12px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            display: block;
            text-decoration: none;
            width: 100%;
        }

        .btn-filter:active, .btn-reset:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        /* Grid Container for Cards */
        .santri-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .santri-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .santri-grid {
                grid-template-columns: 1fr;
            }
        }

        /* SANTRI CARD - Neomorphic Style */
        .santri-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 18px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            position: relative;
            text-align: center;
            min-height: 250px;
            display: flex;
            flex-direction: column;
        }

        .santri-card:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        /* Photo Container with Ring Effect */
        .santri-photo-container {
            position: relative;
            width: 70px;
            height: 70px;
            margin: 0 auto 12px;
        }

        .santri-photo-ring {
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            border-radius: 50%;
            background: var(--gradient-1);
            box-shadow: 4px 4px 8px rgba(0,0,0,0.2);
        }

        .santri-photo {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--bg-primary);
            box-shadow: inset 2px 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .santri-photo-placeholder {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 30px;
            border: 3px solid var(--bg-primary);
            z-index: 2;
        }

        /* Santri Info */
        .santri-name {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 5px;
            letter-spacing: -0.3px;
            line-height: 1.2;
        }

        .santri-nis {
            color: var(--text-secondary);
            font-size: 0.75rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        /* Badge Container */
        .santri-badges {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            margin-bottom: 10px;
            flex-wrap: wrap;
        }

        .badge-custom {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-transform: uppercase;
            color: white;
        }

        .badge-aktif {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .badge-cuti {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .badge-alumni {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .badge-keluar {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .badge-gender {
            background: var(--bg-primary);
            color: var(--text-primary);
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        /* Santri Details */
        .santri-details {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin: 10px 0;
            padding: 10px 0;
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
        }

        .detail-item {
            text-align: center;
        }

        .detail-label {
            font-size: 0.6rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .detail-value {
            font-size: 0.85rem;
            font-weight: 800;
            color: var(--text-primary);
        }

        /* Progress Bar - Neomorphic Style */
        .hafalan-progress {
            margin: 10px 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            color: var(--text-secondary);
            margin-bottom: 6px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .hafalan-bar {
            background: var(--bg-primary);
            border-radius: 15px;
            height: 8px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .hafalan-fill {
            background: var(--gradient-1);
            height: 100%;
            border-radius: 15px;
            transition: width 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        /* Status Ijin Badge */
        .ijin-badge, .di-pesantren-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 0.65rem;
            font-weight: 700;
            margin: 8px 0;
            letter-spacing: 0.3px;
        }

        .ijin-badge {
            background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
            color: white;
        }

        .di-pesantren-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        /* Button View - Neomorphic Style */
        .btn-view {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: none;
            border-radius: 15px;
            padding: 10px 20px;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: auto;
            transition: all 0.3s ease;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .btn-view:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            transform: scale(0.98);
        }

        /* No Data State */
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
            font-size: 1.2rem;
            display: block;
            margin-bottom: 10px;
            color: var(--text-primary);
        }

        /* Pagination */
        .pagination-wrapper {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            text-align: center;
        }

        .pagination-wrapper .pagination {
            justify-content: center;
        }

        .page-link {
            background: var(--bg-primary);
            border: none;
            color: var(--text-primary);
            font-weight: 700;
            margin: 0 5px;
            border-radius: 10px;
            padding: 8px 15px;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .page-link:active {
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .page-item.active .page-link {
            background: var(--gradient-1);
            color: white;
            box-shadow: 4px 4px 8px rgba(0,0,0,0.2);
        }

        .pagination-wrapper small {
            color: var(--text-secondary);
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Data Santri</h3>
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

        {{-- Search & Filter --}}
        <div class="search-box">
            <form action="{{ route('santri.karyawan.index') }}" method="GET">
                <div class="row g-2 mb-2">
                    <div class="col-12">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="🔍 Cari NIS/Nama/NIK..." 
                            value="{{ Request('search') }}">
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-2">
                        <select name="status_santri" class="form-select filter-select">
                            <option value="">Status</option>
                            <option value="aktif" {{ Request('status_santri') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="cuti" {{ Request('status_santri') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="alumni" {{ Request('status_santri') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                            <option value="keluar" {{ Request('status_santri') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="jenis_kelamin" class="form-select filter-select">
                            <option value="">Gender</option>
                            <option value="L" {{ Request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ Request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-2">
                        <select name="tahun_masuk" class="form-select filter-select">
                            <option value="">Tahun</option>
                            @foreach($tahunMasukList as $tahun)
                                <option value="{{ $tahun }}" {{ Request('tahun_masuk') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn-filter w-100">
                            Cari
                        </button>
                    </div>
                    <div class="col-2">
                        <a href="{{ route('santri.karyawan.index') }}" class="btn-reset w-100">
                            <i class="ti ti-refresh me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- Data Santri --}}
        <div class="santri-grid">
        @forelse($santri as $item)
            <div>
                <div class="santri-card">
                    {{-- Photo with Ring Effect --}}
                    <div class="santri-photo-container">
                        <div class="santri-photo-ring"></div>
                        @if($item->foto)
                            <img src="{{ asset('storage/santri/'.$item->foto) }}" 
                                alt="{{ $item->nama_lengkap }}" 
                                class="santri-photo">
                        @else
                            <div class="santri-photo-placeholder">
                                <i class="ti ti-user"></i>
                            </div>
                        @endif
                    </div>

                    {{-- Santri Name & NIS --}}
                    <div class="santri-name">{{ $item->nama_lengkap }}</div>
                    <div class="santri-nis">{{ $item->nis }}</div>

                    {{-- Badges --}}
                    <div class="santri-badges">
                        <span class="badge-custom 
                            @if($item->status_santri == 'aktif') badge-aktif
                            @elseif($item->status_santri == 'cuti') badge-cuti
                            @elseif($item->status_santri == 'alumni') badge-alumni
                            @else badge-keluar
                            @endif">
                            {{ ucfirst($item->status_santri) }}
                        </span>
                        <span class="badge-custom badge-gender">
                            <i class="ti ti-{{ $item->jenis_kelamin == 'L' ? 'man' : 'woman' }}"></i>
                            {{ $item->jenis_kelamin == 'L' ? 'L' : 'P' }}
                        </span>
                    </div>

                    {{-- Details --}}
                    <div class="santri-details">
                        <div class="detail-item">
                            <div class="detail-label">Tahun</div>
                            <div class="detail-value">{{ $item->tahun_masuk }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-label">Hafalan</div>
                            <div class="detail-value">{{ $item->jumlah_juz_hafalan }} Juz</div>
                        </div>
                    </div>

                    {{-- Progress Hafalan --}}
                    <div class="hafalan-progress">
                        <div class="progress-label">
                            <span>Progress Hafalan</span>
                            <span>{{ number_format($item->persentase_hafalan, 0) }}%</span>
                        </div>
                        <div class="hafalan-bar">
                            <div class="hafalan-fill" style="width: {{ $item->persentase_hafalan }}%"></div>
                        </div>
                    </div>

                    {{-- Status Ijin --}}
                    @php
                        $ijinAktif = null;
                        if (method_exists($item, 'ijinSantri') && $item->relationLoaded('ijinSantri')) {
                            $ijinAktif = $item->ijinSantri->first();
                        }
                    @endphp
                    @if($ijinAktif)
                        <div class="ijin-badge">
                            <i class="ti ti-home-off"></i> Pulang s/d {{ $ijinAktif->tanggal_kembali_rencana->format('d/m/Y') }}
                        </div>
                    @else
                        <div class="di-pesantren-badge">
                            <i class="ti ti-home-check"></i> Di Pesantren
                        </div>
                    @endif

                    {{-- Button View --}}
                    <div>
                        <a href="{{ route('santri.karyawan.show', $item->id) }}" class="btn-view">
                            <i class="ti ti-eye"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="no-data">
                <div class="no-data-icon">
                    <i class="ti ti-database-off"></i>
                </div>
                <p><strong>Belum ada data santri</strong></p>
                <small>Tidak ada data yang sesuai dengan filter Anda</small>
            </div>
        @endforelse
        </div>

        {{-- Pagination --}}
        @if($santri->hasPages())
            <div class="pagination-wrapper">
                <div class="mb-2">
                    <small class="text-muted">
                        Menampilkan {{ $santri->firstItem() ?? 0 }} - {{ $santri->lastItem() ?? 0 }} dari {{ $santri->total() }} data
                    </small>
                </div>
                {{ $santri->links() }}
            </div>
        @endif

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