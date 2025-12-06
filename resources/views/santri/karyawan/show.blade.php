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
            --gradient-3: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
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

        /* ========== PROFILE CARD ========== */
        .profile-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 30px 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-photo-container {
            position: relative;
            width: 150px;
            height: 150px;
            margin: 0 auto 20px;
        }

        .profile-photo-ring {
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border-radius: 50%;
            background: var(--gradient-1);
            box-shadow: 4px 4px 12px rgba(0,0,0,0.2);
        }

        .profile-photo {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid var(--bg-primary);
            z-index: 2;
        }

        .profile-placeholder {
            position: relative;
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: var(--gradient-1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 60px;
            border: 5px solid var(--bg-primary);
            z-index: 2;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }

        .profile-nickname {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 8px;
            font-style: italic;
        }

        .profile-nis {
            color: var(--text-secondary);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .badge-status-large {
            padding: 8px 20px;
            border-radius: 15px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: white;
            display: inline-block;
        }

        /* ========== HAFALAN CARD ========== */
        .hafalan-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-top: 20px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .hafalan-title {
            font-size: 1rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 15px;
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .progress-neo {
            background: var(--bg-primary);
            border-radius: 15px;
            height: 30px;
            overflow: hidden;
            position: relative;
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
            margin-bottom: 15px;
        }

        .progress-neo-bar {
            background: var(--gradient-2);
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 0.85rem;
            transition: width 0.8s ease;
        }

        .hafalan-stats {
            display: flex;
            justify-content: space-around;
            margin-top: 15px;
        }

        .hafalan-stat-item {
            text-align: center;
        }

        .hafalan-stat-value {
            font-size: 2rem;
            font-weight: 900;
            color: var(--text-primary);
            line-height: 1;
        }

        .hafalan-stat-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 5px;
        }

        /* ========== TABS ========== */
        .nav-tabs-custom {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 10px;
            margin-bottom: 20px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            display: flex;
            overflow-x: auto;
            gap: 8px;
            -webkit-overflow-scrolling: touch;
        }

        .nav-tabs-custom::-webkit-scrollbar {
            display: none;
        }

        .tab-item {
            flex: 0 0 auto;
            padding: 12px 20px;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s;
            white-space: nowrap;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .tab-item.active {
            background: var(--bg-primary);
            color: var(--text-primary);
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        /* ========== INFO CARD ========== */
        .info-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 15px;
        }

        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            flex: 0 0 40%;
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            flex: 1;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .info-value a {
            color: var(--text-primary);
            text-decoration: none;
        }

        .info-value .badge {
            padding: 5px 12px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            color: white;
        }

        .bg-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .bg-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .bg-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .bg-secondary {
            background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        }

        .bg-pink {
            background: linear-gradient(135deg, #ec4899 0%, #db2777 100%);
        }

        .section-title {
            color: var(--text-primary);
            font-weight: 800;
            margin: 20px 0 15px 0;
            padding-left: 15px;
            border-left: 4px solid var(--text-primary);
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .tab-content-custom {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('santri.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Detail Santri</h3>
                <p>Informasi Lengkap Santri</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        {{-- Profile Card --}}
        <div class="profile-card">
            <div class="profile-photo-container">
                <div class="profile-photo-ring"></div>
                @if($santri->foto)
                    <img src="{{ asset('storage/santri/'.$santri->foto) }}" 
                        alt="{{ $santri->nama_lengkap }}" 
                        class="profile-photo">
                @else
                    <div class="profile-placeholder">
                        <i class="ti ti-user"></i>
                    </div>
                @endif
            </div>

            <div class="profile-name">{{ $santri->nama_lengkap }}</div>
            @if($santri->nama_panggilan)
                <div class="profile-nickname">"{{ $santri->nama_panggilan }}"</div>
            @endif
            <div class="profile-nis">{{ $santri->nis }}</div>

            @if($santri->status_santri == 'aktif')
                <span class="badge-status-large bg-success">AKTIF</span>
            @elseif($santri->status_santri == 'cuti')
                <span class="badge-status-large bg-warning">CUTI</span>
            @elseif($santri->status_santri == 'alumni')
                <span class="badge-status-large bg-info">ALUMNI</span>
            @else
                <span class="badge-status-large bg-danger">KELUAR</span>
            @endif

            {{-- Hafalan Card --}}
            <div class="hafalan-card">
                <div class="hafalan-title">Progress Hafalan</div>
                <div class="progress-neo">
                    <div class="progress-neo-bar" style="width: {{ $santri->persentase_hafalan }}%;">
                        {{ number_format($santri->persentase_hafalan, 1) }}%
                    </div>
                </div>
                <div class="hafalan-stats">
                    <div class="hafalan-stat-item">
                        <div class="hafalan-stat-value">{{ $santri->jumlah_juz_hafalan }}</div>
                        <div class="hafalan-stat-label">Juz</div>
                    </div>
                    <div class="hafalan-stat-item">
                        <div class="hafalan-stat-value">{{ $santri->jumlah_halaman_hafalan }}</div>
                        <div class="hafalan-stat-label">Halaman</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs Navigation --}}
        <div class="nav-tabs-custom">
            <a href="#" class="tab-item active" data-tab="pribadi">
                <i class="ti ti-user me-1"></i> Pribadi
            </a>
            <a href="#" class="tab-item" data-tab="keluarga">
                <i class="ti ti-users me-1"></i> Keluarga
            </a>
            <a href="#" class="tab-item" data-tab="pendidikan">
                <i class="ti ti-school me-1"></i> Pendidikan
            </a>
            <a href="#" class="tab-item" data-tab="hafalan">
                <i class="ti ti-book me-1"></i> Hafalan
            </a>
            <a href="#" class="tab-item" data-tab="asrama">
                <i class="ti ti-building me-1"></i> Asrama
            </a>
        </div>

        {{-- Tab Content --}}
        {{-- Data Pribadi --}}
        <div class="tab-content-custom" id="pribadi">
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">NIS</div>
                    <div class="info-value">{{ $santri->nis }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">NIK</div>
                    <div class="info-value">{{ $santri->nik ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">
                        <span class="badge {{ $santri->jenis_kelamin == 'L' ? 'bg-info' : 'bg-pink' }}">
                            <i class="ti ti-{{ $santri->jenis_kelamin == 'L' ? 'man' : 'woman' }}"></i>
                            {{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tempat Lahir</div>
                    <div class="info-value">{{ $santri->tempat_lahir }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Lahir</div>
                    <div class="info-value">{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d F Y') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Umur</div>
                    <div class="info-value">{{ $santri->umur ?? '-' }} tahun</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Alamat</div>
                    <div class="info-value">{{ $santri->alamat_lengkap }}</div>
                </div>
                @if($santri->kelurahan || $santri->kecamatan)
                <div class="info-row">
                    <div class="info-label">Kel/Kec</div>
                    <div class="info-value">{{ $santri->kelurahan }}, {{ $santri->kecamatan }}</div>
                </div>
                @endif
                @if($santri->kabupaten_kota || $santri->provinsi)
                <div class="info-row">
                    <div class="info-label">Kab/Provinsi</div>
                    <div class="info-value">{{ $santri->kabupaten_kota }}, {{ $santri->provinsi }}</div>
                </div>
                @endif
                @if($santri->kode_pos)
                <div class="info-row">
                    <div class="info-label">Kode Pos</div>
                    <div class="info-value">{{ $santri->kode_pos }}</div>
                </div>
                @endif
                <div class="info-row">
                    <div class="info-label">No. HP</div>
                    <div class="info-value">
                        @if($santri->no_hp)
                            <a href="tel:{{ $santri->no_hp }}" class="text-primary">{{ $santri->no_hp }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        @if($santri->email)
                            <a href="mailto:{{ $santri->email }}" class="text-primary">{{ $santri->email }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Keluarga --}}
        <div class="tab-content-custom" id="keluarga" style="display: none;">
            <h6 class="section-title">Data Orang Tua</h6>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Ayah</div>
                    <div class="info-value">{{ $santri->nama_ayah }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">{{ $santri->pekerjaan_ayah ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP Ayah</div>
                    <div class="info-value">
                        @if($santri->no_hp_ayah)
                            <a href="tel:{{ $santri->no_hp_ayah }}" class="text-primary">{{ $santri->no_hp_ayah }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>

            <div class="info-card mt-3">
                <div class="info-row">
                    <div class="info-label">Nama Ibu</div>
                    <div class="info-value">{{ $santri->nama_ibu }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Pekerjaan</div>
                    <div class="info-value">{{ $santri->pekerjaan_ibu ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP Ibu</div>
                    <div class="info-value">
                        @if($santri->no_hp_ibu)
                            <a href="tel:{{ $santri->no_hp_ibu }}" class="text-primary">{{ $santri->no_hp_ibu }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>

            @if($santri->nama_wali)
            <h6 class="section-title">Data Wali</h6>
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Wali</div>
                    <div class="info-value">{{ $santri->nama_wali }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Hubungan</div>
                    <div class="info-value">{{ $santri->hubungan_wali ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">No. HP Wali</div>
                    <div class="info-value">
                        @if($santri->no_hp_wali)
                            <a href="tel:{{ $santri->no_hp_wali }}" class="text-primary">{{ $santri->no_hp_wali }}</a>
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        {{-- Data Pendidikan --}}
        <div class="tab-content-custom" id="pendidikan" style="display: none;">
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Asal Sekolah</div>
                    <div class="info-value">{{ $santri->asal_sekolah ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tingkat Pendidikan</div>
                    <div class="info-value">{{ $santri->tingkat_pendidikan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tahun Masuk</div>
                    <div class="info-value">{{ $santri->tahun_masuk }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Masuk</div>
                    <div class="info-value">{{ $santri->tanggal_masuk ? $santri->tanggal_masuk->format('d F Y') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Lama Mondok</div>
                    <div class="info-value">{{ $santri->lama_mondok ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status Santri</div>
                    <div class="info-value">
                        @if($santri->status_santri == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @elseif($santri->status_santri == 'cuti')
                            <span class="badge bg-warning">Cuti</span>
                        @elseif($santri->status_santri == 'alumni')
                            <span class="badge bg-info">Alumni</span>
                        @else
                            <span class="badge bg-danger">Keluar</span>
                        @endif
                    </div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status Aktif</div>
                    <div class="info-value">
                        @if($santri->status_aktif == 'aktif')
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Non-Aktif</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Hafalan --}}
        <div class="tab-content-custom" id="hafalan" style="display: none;">
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Target Hafalan</div>
                    <div class="info-value">{{ $santri->target_hafalan ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Mulai Tahfidz</div>
                    <div class="info-value">{{ $santri->tanggal_mulai_tahfidz ? $santri->tanggal_mulai_tahfidz->format('d F Y') : '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Khatam Terakhir</div>
                    <div class="info-value">{{ $santri->tanggal_khatam_terakhir ? $santri->tanggal_khatam_terakhir->format('d F Y') : '-' }}</div>
                </div>
                @if($santri->catatan_hafalan)
                <div class="info-row">
                    <div class="info-label" style="align-self: flex-start;">Catatan</div>
                    <div class="info-value">{{ $santri->catatan_hafalan }}</div>
                </div>
                @endif
            </div>
        </div>

        {{-- Data Asrama --}}
        <div class="tab-content-custom" id="asrama" style="display: none;">
            <div class="info-card">
                <div class="info-row">
                    <div class="info-label">Nama Asrama</div>
                    <div class="info-value">{{ $santri->nama_asrama ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nomor Kamar</div>
                    <div class="info-value">{{ $santri->nomor_kamar ?? '-' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama Pembina</div>
                    <div class="info-value">{{ $santri->nama_pembina ?? '-' }}</div>
                </div>
                @if($santri->keterangan)
                <div class="info-row">
                    <div class="info-label" style="align-self: flex-start;">Keterangan</div>
                    <div class="info-value">{{ $santri->keterangan }}</div>
                </div>
                @endif
            </div>
        </div>

        <div style="height: 80px;"></div>
    </div>

    @push('myscript')
    <script>
        // Tab switching
        document.querySelectorAll('.tab-item').forEach(tab => {
            tab.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                document.querySelectorAll('.tab-item').forEach(t => t.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content-custom').forEach(content => {
                    content.style.display = 'none';
                });
                
                // Show selected tab content
                const tabName = this.getAttribute('data-tab');
                document.getElementById(tabName).style.display = 'block';
            });
        });
    </script>
    @endpush
@endsection
