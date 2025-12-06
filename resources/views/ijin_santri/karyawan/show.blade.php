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
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        body {
            --bg-body: var(--bg-body-light);
            --bg-primary: var(--bg-primary-light);
            --shadow-dark: var(--shadow-dark-light);
            --shadow-light: var(--shadow-light-light);
            --text-primary: var(--text-primary-light);
            --text-secondary: var(--text-secondary-light);
            --border-color: var(--border-light);
        }

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
            color: var(--text-primary);
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
            padding-bottom: 100px;
        }

        /* ========== TIMELINE CARD ========== */
        .timeline-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 30px 20px;
            margin-bottom: 25px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .timeline-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gradient-1);
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.4);
        }

        .timeline-title {
            color: var(--text-primary);
            font-weight: 800;
            font-size: 1rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .timeline-title i {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.4rem;
        }

        .timeline-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            text-align: center;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .timeline-icon-wrapper {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .timeline-icon-wrapper.active {
            background: var(--gradient-1);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.5),
                       inset 0 -2px 8px rgba(0, 0, 0, 0.2);
            transform: translateY(-3px);
        }

        .timeline-icon-wrapper.inactive {
            background: var(--bg-primary);
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light),
                       inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .timeline-icon {
            font-size: 1.8em;
            transition: all 0.3s;
        }

        .timeline-icon-wrapper.active .timeline-icon {
            color: white;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .timeline-icon-wrapper.inactive .timeline-icon {
            color: var(--text-secondary);
            opacity: 0.4;
        }

        .timeline-label {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .timeline-date {
            font-size: 0.8rem;
            color: var(--text-primary);
            font-weight: 800;
            background: var(--bg-primary);
            padding: 5px 10px;
            border-radius: 8px;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        /* ========== INFO CARDS ========== */
        .info-card {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            position: relative;
        }

        .card-header-custom {
            margin-bottom: 20px;
            padding-bottom: 18px;
            border-bottom: 2px solid var(--border-color);
        }

        .card-title-custom {
            font-size: 1.05rem;
            font-weight: 800;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 0;
        }

        .card-title-custom i {
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.5rem;
            filter: drop-shadow(0 2px 3px rgba(102, 126, 234, 0.3));
        }

        .info-table {
            width: 100%;
        }

        .info-row {
            display: grid;
            grid-template-columns: 40% 1fr;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
            gap: 15px;
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-label {
            color: var(--text-secondary);
            font-size: 0.85rem;
            font-weight: 700;
        }

        .info-value {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 800;
        }

        /* ========== STATUS BADGE ========== */
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .status-badge.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .status-badge.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
        }

        .status-badge.danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        /* ========== DETAIL SECTION ========== */
        .detail-item {
            margin-bottom: 20px;
        }

        .detail-item:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            color: var(--text-secondary);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .detail-value {
            color: var(--text-primary);
            font-size: 0.95rem;
            font-weight: 800;
        }

        .detail-text {
            color: var(--text-primary);
            font-size: 0.9rem;
            font-weight: 600;
            line-height: 1.6;
        }

        /* ========== HISTORY ITEM ========== */
        .history-item {
            display: flex;
            gap: 15px;
            margin-bottom: 25px;
        }

        .history-item:last-child {
            margin-bottom: 0;
        }

        .history-icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 5px 5px 12px var(--shadow-dark),
                       -5px -5px 12px var(--shadow-light);
            position: relative;
        }

        .history-icon-wrapper::after {
            content: '';
            position: absolute;
            inset: 2px;
            border-radius: 13px;
            opacity: 0.5;
        }

        .history-icon-wrapper.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            box-shadow: 0 6px 15px rgba(245, 158, 11, 0.4),
                       inset 0 -2px 6px rgba(0, 0, 0, 0.2);
        }

        .history-icon-wrapper.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4),
                       inset 0 -2px 6px rgba(0, 0, 0, 0.2);
        }

        .history-icon-wrapper.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4),
                       inset 0 -2px 6px rgba(0, 0, 0, 0.2);
        }

        .history-icon-wrapper i {
            color: white;
            font-size: 1.3rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }

        .history-content {
            flex: 1;
            background: var(--bg-primary);
            padding: 12px 15px;
            border-radius: 12px;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .history-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 5px;
        }

        .history-time {
            font-size: 0.75rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        /* ========== IMAGE ========== */
        .image-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }

        .image-preview {
            width: 100%;
            border-radius: 15px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            margin-top: 15px;
        }

        /* ========== BUTTON ========== */
        .btn-download {
            background: var(--gradient-2);
            color: white;
            border: none;
            border-radius: 18px;
            padding: 20px;
            font-size: 1rem;
            font-weight: 800;
            width: 100%;
            box-shadow: 0 8px 20px rgba(245, 87, 108, 0.4),
                       inset 0 -3px 8px rgba(0, 0, 0, 0.2);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-download::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-download:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-download:active {
            transform: scale(0.97);
            box-shadow: 0 4px 12px rgba(245, 87, 108, 0.4),
                       inset 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .btn-download i {
            font-size: 1.3rem;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
        }
    </style>

    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('ijin-santri.karyawan.index') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Detail Ijin Santri</h3>
                <p>Informasi Lengkap Ijin</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        {{-- Status Timeline --}}
        <div class="timeline-card">
            <div class="timeline-title">
                <i class="ti ti-timeline"></i> Status Proses Ijin
            </div>
            
            <div class="timeline-steps">
                <div class="timeline-step">
                    <div class="timeline-icon-wrapper {{ in_array($ijinSantri->status, ['pending', 'ttd_ustadz', 'dipulangkan', 'kembali']) ? 'active' : 'inactive' }}">
                        <i class="ti ti-file-plus timeline-icon"></i>
                    </div>
                    <div class="timeline-label">Dibuat</div>
                    <div class="timeline-date">{{ $ijinSantri->created_at->format('d/m/y') }}</div>
                </div>
                
                <div class="timeline-step">
                    <div class="timeline-icon-wrapper {{ in_array($ijinSantri->status, ['ttd_ustadz', 'dipulangkan', 'kembali']) ? 'active' : 'inactive' }}">
                        <i class="ti ti-writing-sign timeline-icon"></i>
                    </div>
                    <div class="timeline-label">TTD Ustadz</div>
                    <div class="timeline-date">
                        @if($ijinSantri->ttd_ustadz_at)
                            {{ $ijinSantri->ttd_ustadz_at->format('d/m/y') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                
                <div class="timeline-step">
                    <div class="timeline-icon-wrapper {{ in_array($ijinSantri->status, ['dipulangkan', 'kembali']) ? 'active' : 'inactive' }}">
                        <i class="ti ti-plane-departure timeline-icon"></i>
                    </div>
                    <div class="timeline-label">Pulang</div>
                    <div class="timeline-date">
                        @if($ijinSantri->verifikasi_pulang_at)
                            {{ $ijinSantri->verifikasi_pulang_at->format('d/m/y') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
                
                <div class="timeline-step">
                    <div class="timeline-icon-wrapper {{ $ijinSantri->status == 'kembali' ? 'active' : 'inactive' }}">
                        <i class="ti ti-plane-arrival timeline-icon"></i>
                    </div>
                    <div class="timeline-label">Kembali</div>
                    <div class="timeline-date">
                        @if($ijinSantri->verifikasi_kembali_at)
                            {{ $ijinSantri->verifikasi_kembali_at->format('d/m/y') }}
                        @else
                            -
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Info Surat --}}
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="ti ti-file-text"></i> Informasi Surat
                </div>
            </div>
            <div class="info-table">
                <div class="info-row">
                    <div class="info-label">Nomor Surat</div>
                    <div class="info-value">{{ $ijinSantri->nomor_surat }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Status</div>
                    <div class="info-value">{!! $ijinSantri->status_label !!}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Dibuat Oleh</div>
                    <div class="info-value">{{ $ijinSantri->creator->name }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tanggal Dibuat</div>
                    <div class="info-value">{{ $ijinSantri->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        {{-- Data Santri --}}
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="ti ti-user"></i> Data Santri
                </div>
            </div>
            <div class="info-table">
                <div class="info-row">
                    <div class="info-label">NIS</div>
                    <div class="info-value">{{ $ijinSantri->santri->nis }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Nama</div>
                    <div class="info-value">{{ $ijinSantri->santri->nama_lengkap }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Jenis Kelamin</div>
                    <div class="info-value">{{ $ijinSantri->santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Telepon</div>
                    <div class="info-value">{{ $ijinSantri->santri->no_hp_santri ?? '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Detail Ijin --}}
        <div class="info-card">
            <div class="card-header-custom">
                <div class="card-title-custom">
                    <i class="ti ti-calendar"></i> Detail Ijin
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Tanggal Ijin</div>
                <div class="detail-value">{{ $ijinSantri->tanggal_ijin->format('d/m/Y') }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Rencana Kembali</div>
                <div class="detail-value">{{ $ijinSantri->tanggal_kembali_rencana->format('d/m/Y') }}</div>
            </div>

            @if($ijinSantri->tanggal_kembali_aktual)
                <div class="detail-item">
                    <div class="detail-label">Tanggal Kembali Aktual</div>
                    <div class="detail-value" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        {{ $ijinSantri->tanggal_kembali_aktual->format('d/m/Y') }}
                    </div>
                </div>
            @endif

            <div class="detail-item">
                <div class="detail-label">Alasan Ijin</div>
                <div class="detail-text">{{ $ijinSantri->alasan_ijin }}</div>
            </div>

            @if($ijinSantri->catatan)
                <div class="detail-item">
                    <div class="detail-label">Catatan</div>
                    <div class="detail-text">{{ $ijinSantri->catatan }}</div>
                </div>
            @endif
        </div>
        {{-- Riwayat Verifikasi --}}
        @if($ijinSantri->status != 'pending')
            <div class="info-card">
                <div class="card-header-custom">
                    <div class="card-title-custom">
                        <i class="ti ti-history"></i> Riwayat Verifikasi
                    </div>
                </div>

                @if($ijinSantri->ttd_ustadz_at)
                    <div class="history-item">
                        <div class="history-icon-wrapper warning">
                            <i class="ti ti-check"></i>
                        </div>
                        <div class="history-content">
                            <div class="history-title">Verifikasi TTD Ustadz</div>
                            <div class="history-time">
                                {{ $ijinSantri->ttd_ustadz_at->format('d/m/Y H:i') }}
                                @if($ijinSantri->ttdUstadzBy)
                                    - oleh {{ $ijinSantri->ttdUstadzBy->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($ijinSantri->verifikasi_pulang_at)
                    <div class="history-item">
                        <div class="history-icon-wrapper primary">
                            <i class="ti ti-plane-departure"></i>
                        </div>
                        <div class="history-content">
                            <div class="history-title">Verifikasi Kepulangan</div>
                            <div class="history-time">
                                {{ $ijinSantri->verifikasi_pulang_at->format('d/m/Y H:i') }}
                                @if($ijinSantri->verifikasiPulangBy)
                                    - oleh {{ $ijinSantri->verifikasiPulangBy->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($ijinSantri->verifikasi_kembali_at)
                    <div class="history-item">
                        <div class="history-icon-wrapper success">
                            <i class="ti ti-plane-arrival"></i>
                        </div>
                        <div class="history-content">
                            <div class="history-title">Verifikasi Kembali</div>
                            <div class="history-time">
                                {{ $ijinSantri->verifikasi_kembali_at->format('d/m/Y H:i') }}
                                @if($ijinSantri->verifikasiKembaliBy)
                                    - oleh {{ $ijinSantri->verifikasiKembaliBy->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        {{-- Foto Surat TTD Ortu --}}
        @if($ijinSantri->foto_surat_ttd_ortu)
            <div class="image-card">
                <div class="card-header-custom">
                    <div class="card-title-custom">
                        <i class="ti ti-photo"></i> Foto Surat TTD Orang Tua
                    </div>
                </div>
                <img src="{{ Storage::url('ijin_santri/' . $ijinSantri->foto_surat_ttd_ortu) }}" 
                     class="image-preview" alt="Surat TTD Ortu">
            </div>
        @endif
    </div>
@endsection
