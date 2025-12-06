@extends('layouts.mobile.app')
@section('content')
<style>
    :root {
        --bg-body: #dff9fb;
        --bg-nav: #ffffff;
        --color-nav: #667eea;
        --color-nav-active: #764ba2;
        --bg-indicator: #667eea;
        --color-nav-hover: #7c3aed;
    }

    body {
        background: linear-gradient(180deg, #e3f4f9 0%, #f8fbfc 100%);
    }

    #header-section {
        height: auto;
        padding: 25px 20px;
        position: relative;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 0 0 35px 35px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    #section-back {
        position: absolute;
        left: 15px;
        top: 15px;
    }

    .back-btn {
        color: #ffffff;
        font-size: 32px;
        text-decoration: none;
        transition: transform 0.3s ease;
    }

    #header-title {
        text-align: center;
        color: #ffffff;
        margin-top: 10px;
    }

    #header-title h3 {
        font-weight: 900;
        margin: 0;
        font-size: 1.8rem;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
        letter-spacing: -0.5px;
    }

    #header-title p {
        margin: 8px 0 0 0;
        opacity: 0.95;
        font-size: 0.95rem;
        font-weight: 500;
    }

    #content-section {
        padding: 15px 0 100px 0;
        overflow-x: hidden;
    }

    .section-subtitle {
        padding: 0 20px 15px 20px;
        color: #555;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Horizontal Scroll Container */
    .kendaraan-scroll-container {
        display: flex;
        overflow-x: auto;
        padding: 0 20px 25px 20px;
        gap: 25px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
    }

    .kendaraan-scroll-container::-webkit-scrollbar {
        display: none;
    }

    /* Modern Pricing Card Style */
    .pricing-card {
        min-width: 320px;
        max-width: 320px;
        height: 420px;
        border-radius: 30px;
        position: relative;
        overflow: hidden;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        scroll-snap-align: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25), 0 8px 20px rgba(0, 0, 0, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3), 0 10px 25px rgba(0, 0, 0, 0.18);
    }

    /* Enhanced Background with Photo */
    .card-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0.85;
        filter: brightness(1.15) contrast(1.3) saturate(1.5) sharpen(1.2);
        transition: all 0.5s ease;
    }

    .pricing-card:hover .card-background {
        transform: scale(1.08);
        opacity: 0.95;
        filter: brightness(1.2) contrast(1.35) saturate(1.55) sharpen(1.3);
    }

    /* Gradient Overlay */
    .card-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, 
            rgba(102, 126, 234, 0.45) 0%, 
            rgba(118, 75, 162, 0.35) 100%);
    }

    /* Card Content */
    .card-content {
        position: relative;
        z-index: 2;
        padding: 35px 28px;
        height: 100%;
        display: flex;
        flex-direction: column;
        color: white;
    }

    /* Premium Badge */
    .card-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.35);
        backdrop-filter: blur(20px) saturate(180%);
        padding: 10px 24px;
        border-radius: 30px;
        font-size: 0.72rem;
        font-weight: 900;
        letter-spacing: 2px;
        margin-bottom: 22px;
        border: 2px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        text-transform: uppercase;
        align-self: flex-start;
    }

    /* Title & Subtitle */
    .card-title {
        font-size: 1.65rem;
        font-weight: 900;
        margin-bottom: 8px;
        line-height: 1.25;
        text-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
        letter-spacing: -0.5px;
    }

    .card-subtitle {
        font-size: 0.9rem;
        opacity: 0.95;
        margin-bottom: 25px;
        font-weight: 600;
        text-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    }

    /* Card Stats */
    .card-stats {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: auto;
    }

    .stat-item {
        background: rgba(255, 255, 255, 0.22);
        backdrop-filter: blur(15px) saturate(160%);
        padding: 12px 14px;
        border-radius: 16px;
        border: 1.5px solid rgba(255, 255, 255, 0.35);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .stat-label {
        font-size: 0.7rem;
        opacity: 0.9;
        margin-bottom: 6px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .stat-value {
        font-size: 1.1rem;
        font-weight: 900;
        text-shadow: 0 3px 8px rgba(0, 0, 0, 0.25);
    }

    /* Card Footer with Action Icons */
    .card-footer {
        margin-top: auto;
        padding-top: 20px;
        display: flex;
        gap: 10px;
        justify-content: center;
    }

    /* Icon Buttons */
    .btn-card-action {
        width: 56px;
        height: 56px;
        padding: 0;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.28) 0%, rgba(255, 255, 255, 0.18) 100%);
        backdrop-filter: blur(20px) saturate(180%);
        border: 2px solid rgba(255, 255, 255, 0.4);
        border-radius: 18px;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        cursor: pointer;
        position: relative;
    }

    .btn-card-action:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.92) 100%);
        transform: translateY(-3px) scale(1.08);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
        border-color: rgba(255, 255, 255, 0.7);
    }

    .btn-card-action ion-icon {
        font-size: 1.6rem;
        transition: transform 0.4s ease;
    }

    .btn-card-action:hover ion-icon {
        transform: scale(1.2) rotate(5deg);
    }

    /* Button Colors */
    .btn-detail:hover { color: #667eea; }
    .btn-keluar:hover { color: #10b981; }
    .btn-masuk:hover { color: #f59e0b; }
    .btn-pinjam:hover { color: #06b6d4; }
    .btn-service:hover { color: #8b5cf6; }

    /* Disabled State */
    .btn-card-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-card-action:disabled:hover {
        transform: none;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    /* Action Label */
    .action-label {
        position: absolute;
        bottom: -28px;
        left: 50%;
        transform: translateX(-50%);
        background: rgba(0, 0, 0, 0.85);
        color: white;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 0.7rem;
        font-weight: 700;
        white-space: nowrap;
        opacity: 0;
        pointer-events: none;
        transition: all 0.3s ease;
        z-index: 10;
    }

    .btn-card-action:hover .action-label {
        opacity: 1;
        bottom: -32px;
    }

    /* Modal Styles */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        z-index: 9999;
        animation: fadeIn 0.3s ease;
    }

    .modal-overlay.active {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: white;
        border-radius: 25px;
        width: 100%;
        max-width: 480px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.4);
        animation: slideUp 0.4s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 25px;
        border-radius: 25px 25px 0 0;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h3 {
        font-weight: 900;
        font-size: 1.3rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-close {
        background: rgba(255, 255, 255, 0.2);
        border: 2px solid rgba(255, 255, 255, 0.4);
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .modal-close:hover {
        background: rgba(220, 38, 38, 0.9);
        border-color: #dc2626;
        transform: rotate(90deg);
    }

    .modal-body {
        padding: 25px;
    }

    .modal-info {
        background: #f0f3ff;
        padding: 15px;
        border-radius: 15px;
        margin-bottom: 20px;
        border-left: 4px solid #667eea;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
        font-weight: 700;
        color: #374151;
        margin-bottom: 8px;
    }

    .form-control, .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #d1d5db;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        font-family: 'Poppins', sans-serif;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .btn-submit {
        width: 100%;
        padding: 15px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 15px;
        font-weight: 900;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 28px rgba(102, 126, 234, 0.4);
    }

    .text-danger {
        color: #dc2626;
    }
</style>

<div id="header-section">
    <div id="section-back">
        <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title">
        <h3>Manajemen Kendaraan</h3>
        <p>Kelola Keluar/Masuk, Peminjaman & Service</p>
    </div>
</div>

<div id="content-section">
    @if(Session::get('success'))
        <div class="alert alert-success alert-dismissible" style="margin: 0 20px 20px 20px;">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(Session::get('error'))
        <div class="alert alert-danger alert-dismissible" style="margin: 0 20px 20px 20px;">
            {{ Session::get('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="section-subtitle">
        <div>
            <ion-icon name="car-sport"></ion-icon>
            Daftar Kendaraan ({{ $kendaraan->total() }})
        </div>
        <a href="{{ route('kendaraan.karyawan.riwayatPeminjaman') }}" style="color: #667eea; text-decoration: none; font-size: 0.85rem;">
            <ion-icon name="time-outline"></ion-icon> Riwayat Saya
        </a>
    </div>

    @if($kendaraan->count() > 0)
        <div class="kendaraan-scroll-container">
            @foreach($kendaraan as $k)
                @php
                    $statusColors = [
                        'Tersedia' => 'success',
                        'Digunakan' => 'warning',
                        'Service' => 'info',
                        'Rusak' => 'danger'
                    ];
                    $statusColor = $statusColors[$k->status_kendaraan] ?? 'secondary';
                    
                    $jenisIcons = [
                        'Mobil' => 'car-sport',
                        'Motor' => 'bicycle',
                        'Truk' => 'bus',
                        'Bus' => 'bus',
                        'Lainnya' => 'car'
                    ];
                    $jenisIcon = $jenisIcons[$k->jenis_kendaraan] ?? 'car';
                @endphp
                
                <div class="pricing-card">
                    @if($k->foto && Storage::disk('public')->exists('kendaraan/' . $k->foto))
                        <div class="card-background" style="background-image: url('{{ asset('storage/kendaraan/' . $k->foto) }}');"></div>
                    @else
                        <div class="card-background" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                    @endif
                    
                    <div class="card-overlay"></div>
                    
                    <div class="card-content">
                        <div class="card-badge">
                            {{ $k->status_kendaraan }}
                        </div>
                        
                        <h2 class="card-title">{{ $k->nama_kendaraan }}</h2>
                        <p class="card-subtitle">
                            <ion-icon name="{{ $jenisIcon }}"></ion-icon>
                            {{ $k->no_polisi }} • {{ $k->jenis_kendaraan }}
                        </p>
                        
                        <div class="card-stats">
                            <div class="stat-item">
                                <div class="stat-label">
                                    <ion-icon name="speedometer-outline"></ion-icon>
                                    Odometer
                                </div>
                                <div class="stat-value">{{ number_format($k->km_terakhir ?? 0) }} KM</div>
                            </div>
                            
                            <div class="stat-item">
                                <div class="stat-label">
                                    <ion-icon name="people-outline"></ion-icon>
                                    Kapasitas
                                </div>
                                <div class="stat-value">{{ $k->kapasitas_penumpang ?? '-' }} Org</div>
                            </div>
                        </div>
                        
                        <div class="card-footer">
                            <!-- Detail Button - Always Available -->
                            <button class="btn-card-action btn-detail" onclick="openDetailModal('{{ Crypt::encrypt($k->id) }}')" title="Lihat Detail">
                                <ion-icon name="eye-outline"></ion-icon>
                                <div class="action-label">Detail</div>
                            </button>
                            
                            <!-- Keluar Button - ALWAYS ACTIVE FOR NOW -->
                            <button class="btn-card-action btn-keluar" 
                                    onclick="openKeluarModal('{{ Crypt::encrypt($k->id) }}', '{{ addslashes($k->nama_kendaraan) }}', '{{ $k->no_polisi }}', {{ $k->km_terakhir ?? 0 }})" 
                                    title="Kendaraan Keluar">
                                <ion-icon name="exit-outline"></ion-icon>
                                <div class="action-label">Keluar</div>
                            </button>
                            
                            <!-- Masuk Button - ALWAYS ACTIVE FOR NOW -->
                            <button class="btn-card-action btn-masuk" 
                                    onclick="openMasukModal('{{ Crypt::encrypt($k->id) }}', '{{ addslashes($k->nama_kendaraan) }}', '{{ $k->no_polisi }}', {{ $k->km_terakhir ?? 0 }})" 
                                    title="Kendaraan Masuk">
                                <ion-icon name="enter-outline"></ion-icon>
                                <div class="action-label">Masuk</div>
                            </button>
                            
                            <!-- Pinjam Button - ALWAYS ACTIVE FOR NOW -->
                            <button class="btn-card-action btn-pinjam" 
                                    onclick="openPeminjamanModal('{{ Crypt::encrypt($k->id) }}', '{{ addslashes($k->nama_kendaraan) }}', '{{ $k->no_polisi }}', {{ $k->kapasitas_penumpang ?? 0 }})" 
                                    title="Ajukan Peminjaman">
                                <ion-icon name="calendar-outline"></ion-icon>
                                <div class="action-label">Pinjam</div>
                            </button>
                            
                            <!-- Service Button - Always Available -->
                            <button class="btn-card-action btn-service" onclick="openServiceModal('{{ Crypt::encrypt($k->id) }}', '{{ addslashes($k->nama_kendaraan) }}', '{{ $k->no_polisi }}', {{ $k->km_terakhir ?? 0 }})" title="Lapor Service">
                                <ion-icon name="build-outline"></ion-icon>
                                <div class="action-label">Service</div>
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if($kendaraan->hasPages())
            <div style="padding: 0 20px;">
                {{ $kendaraan->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    @else
        <div style="text-align: center; padding: 60px 20px;">
            <ion-icon name="car-sport-outline" style="font-size: 5rem; color: #cbd5e0;"></ion-icon>
            <h4 style="margin-top: 20px; color: #4a5568;">Tidak Ada Kendaraan</h4>
            <p style="color: #718096;">Belum ada data kendaraan tersedia</p>
        </div>
    @endif
</div>

{{-- Modal Keluar --}}
<div class="modal-overlay" id="modalKeluar">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <ion-icon name="exit-outline"></ion-icon>
                Kendaraan Keluar
            </h3>
            <button class="modal-close" onclick="closeModal('modalKeluar')">
                <ion-icon name="close" style="font-size: 1.3rem; color: white;"></ion-icon>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <div><strong id="keluar-nama"></strong></div>
                <div><small id="keluar-nopol"></small></div>
            </div>

            <form id="formKeluar" method="POST">
                @csrf
                <input type="hidden" id="keluar-id" name="kendaraan_id">
                <input type="hidden" name="tipe" value="Keluar">
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon>
                        Pengemudi <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="pengemudi" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="time-outline"></ion-icon>
                        Waktu Keluar <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" name="waktu" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="people-outline"></ion-icon>
                        Jumlah Penumpang <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="jumlah_penumpang" id="keluar-jumlah-penumpang" class="form-control" min="1" required>
                    <small style="color: #718096; font-size: 0.8rem;">Isi sesuai jumlah penumpang saat keluar</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        Kondisi <span class="text-danger">*</span>
                    </label>
                    <select name="kondisi" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Perlu Service">Perlu Service</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="location-outline"></ion-icon>
                        Tujuan
                    </label>
                    <input type="text" name="tujuan" class="form-control" placeholder="Contoh: Jakarta">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Keperluan
                    </label>
                    <textarea name="keperluan" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
                    Simpan Keluar
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Masuk --}}
<div class="modal-overlay" id="modalMasuk">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <ion-icon name="enter-outline"></ion-icon>
                Kendaraan Masuk
            </h3>
            <button class="modal-close" onclick="closeModal('modalMasuk')">
                <ion-icon name="close" style="font-size: 1.3rem; color: white;"></ion-icon>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <div><strong id="masuk-nama"></strong></div>
                <div><small id="masuk-nopol"></small></div>
            </div>

            <form id="formMasuk" method="POST">
                @csrf
                <input type="hidden" id="masuk-id" name="kendaraan_id">
                <input type="hidden" name="tipe" value="Masuk">
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon>
                        Pengemudi <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="pengemudi" class="form-control" value="{{ auth()->user()->name }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="time-outline"></ion-icon>
                        Waktu Masuk <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" name="waktu" class="form-control" value="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="speedometer-outline"></ion-icon>
                        KM Masuk <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="km" id="masuk-km" class="form-control" min="0" required>
                    <small style="color: #718096; font-size: 0.8rem;">KM terakhir: <span id="masuk-km-last"></span> km</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                        Kondisi <span class="text-danger">*</span>
                    </label>
                    <select name="kondisi" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Baik">Baik</option>
                        <option value="Cukup">Cukup</option>
                        <option value="Perlu Service">Perlu Service</option>
                        <option value="Rusak">Rusak</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
                    Simpan Masuk
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Peminjaman --}}
<div class="modal-overlay" id="modalPeminjaman">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <ion-icon name="calendar-outline"></ion-icon>
                Pengajuan Peminjaman
            </h3>
            <button class="modal-close" onclick="closeModal('modalPeminjaman')">
                <ion-icon name="close" style="font-size: 1.3rem; color: white;"></ion-icon>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <div><strong id="pinjam-nama"></strong></div>
                <div><small id="pinjam-nopol"></small></div>
                <div><small>Kapasitas: <span id="pinjam-kapasitas"></span> orang</small></div>
            </div>

            <form id="formPeminjaman" method="POST">
                @csrf
                <input type="hidden" id="pinjam-id" name="kendaraan_id">
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon>
                        Nama Peminjam <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="nama_peminjam" class="form-control" value="{{ auth()->user()->name }}" readonly>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="call-outline"></ion-icon>
                        No. HP <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Tanggal Pinjam <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" name="tanggal_pinjam" class="form-control" min="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Tanggal Kembali <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" name="tanggal_kembali" class="form-control" min="{{ date('Y-m-d\TH:i') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="location-outline"></ion-icon>
                        Tujuan Penggunaan <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="tujuan_penggunaan" class="form-control" placeholder="Contoh: Jakarta" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Keperluan <span class="text-danger">*</span>
                    </label>
                    <select name="keperluan" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Dinas">Dinas</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Kegiatan">Kegiatan</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="people-outline"></ion-icon>
                        Jumlah Penumpang
                    </label>
                    <input type="number" name="jumlah_penumpang" class="form-control" min="1" placeholder="1">
                    <small style="color: #718096; font-size: 0.8rem;">Maksimal: <span id="pinjam-max"></span> orang</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="chatbox-outline"></ion-icon>
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" rows="3" placeholder="Keterangan tambahan..."></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <ion-icon name="send-outline" style="font-size: 1.3rem;"></ion-icon>
                    Ajukan Peminjaman
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Modal Service --}}
<div class="modal-overlay" id="modalService">
    <div class="modal-content">
        <div class="modal-header">
            <h3>
                <ion-icon name="build-outline"></ion-icon>
                Laporan Service
            </h3>
            <button class="modal-close" onclick="closeModal('modalService')">
                <ion-icon name="close" style="font-size: 1.3rem; color: white;"></ion-icon>
            </button>
        </div>
        <div class="modal-body">
            <div class="modal-info">
                <div><strong id="service-nama"></strong></div>
                <div><small id="service-nopol"></small></div>
                <div><small>KM: <span id="service-km"></span> km</small></div>
            </div>

            <form id="formService" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="service-id" name="kendaraan_id">
                
                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="calendar-outline"></ion-icon>
                        Tanggal Service <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="tanggal_service" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="construct-outline"></ion-icon>
                        Jenis Service <span class="text-danger">*</span>
                    </label>
                    <select name="jenis_service" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Service Rutin">Service Rutin</option>
                        <option value="Ganti Oli">Ganti Oli</option>
                        <option value="Perbaikan">Perbaikan</option>
                        <option value="Tune Up">Tune Up</option>
                        <option value="Ganti Ban">Ganti Ban</option>
                        <option value="Ganti Aki">Ganti Aki</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="business-outline"></ion-icon>
                        Bengkel <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="bengkel" class="form-control" placeholder="Nama bengkel" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="document-text-outline"></ion-icon>
                        Deskripsi Pekerjaan <span class="text-danger">*</span>
                    </label>
                    <textarea name="deskripsi_pekerjaan" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="speedometer-outline"></ion-icon>
                        KM Service <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="km_service" id="service-km-input" class="form-control" min="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="cash-outline"></ion-icon>
                        Biaya <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="biaya" class="form-control" min="0" step="0.01" placeholder="0" required>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="person-outline"></ion-icon>
                        Mekanik
                    </label>
                    <input type="text" name="mekanik" class="form-control" placeholder="Nama mekanik">
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="settings-outline"></ion-icon>
                        Sparepart Diganti
                    </label>
                    <textarea name="sparepart_diganti" class="form-control" rows="2" placeholder="Contoh: Filter oli, Busi"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="image-outline"></ion-icon>
                        Foto Bukti
                    </label>
                    <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                    <small style="color: #718096; font-size: 0.8rem;">Format: JPG, PNG (Max: 5MB)</small>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        <ion-icon name="chatbox-outline"></ion-icon>
                        Keterangan
                    </label>
                    <textarea name="keterangan" class="form-control" rows="2"></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
                    Simpan Laporan
                </button>
            </form>
        </div>
    </div>
</div>

@push('myscript')
<script>
// Alert for disabled actions
function alertNotAvailable(action, currentStatus) {
    let message = '';
    
    if (action === 'Keluar') {
        message = 'Kendaraan harus berstatus "Tersedia" untuk bisa keluar. Status saat ini: ' + currentStatus;
    } else if (action === 'Masuk') {
        message = 'Kendaraan harus berstatus "Digunakan" untuk bisa masuk. Status saat ini: ' + currentStatus;
    } else if (action === 'Pinjam') {
        message = 'Kendaraan harus berstatus "Tersedia" untuk bisa dipinjam. Status saat ini: ' + currentStatus;
    }
    
    Swal.fire({
        icon: 'info',
        title: 'Aksi Tidak Tersedia',
        text: message,
        confirmButtonColor: '#667eea'
    });
}

function openDetailModal(encryptedId) {
    window.location.href = '{{ url("/kendaraan-karyawan") }}/' + encryptedId + '/detail';
}

function openKeluarModal(encryptedId, nama, nopol, kmTerakhir) {
    document.getElementById('keluar-nama').textContent = nama;
    document.getElementById('keluar-nopol').textContent = nopol;
    document.getElementById('keluar-km-last').textContent = kmTerakhir.toLocaleString();
    document.getElementById('keluar-km').min = kmTerakhir;
    document.getElementById('keluar-km').value = kmTerakhir;
    
    const form = document.getElementById('formKeluar');
    form.action = '{{ url("/kendaraan-karyawan") }}/' + encryptedId + '/keluar-masuk';
    
    document.getElementById('modalKeluar').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openMasukModal(encryptedId, nama, nopol, kmTerakhir) {
    document.getElementById('masuk-nama').textContent = nama;
    document.getElementById('masuk-nopol').textContent = nopol;
    document.getElementById('masuk-km-last').textContent = kmTerakhir.toLocaleString();
    document.getElementById('masuk-km').min = kmTerakhir;
    document.getElementById('masuk-km').value = kmTerakhir;
    
    const form = document.getElementById('formMasuk');
    form.action = '{{ url("/kendaraan-karyawan") }}/' + encryptedId + '/keluar-masuk';
    
    document.getElementById('modalMasuk').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openPeminjamanModal(encryptedId, nama, nopol, kapasitas) {
    document.getElementById('pinjam-nama').textContent = nama;
    document.getElementById('pinjam-nopol').textContent = nopol;
    document.getElementById('pinjam-kapasitas').textContent = kapasitas || '-';
    document.getElementById('pinjam-max').textContent = kapasitas || '-';
    
    const form = document.getElementById('formPeminjaman');
    form.action = '{{ url("/kendaraan-karyawan") }}/' + encryptedId + '/peminjaman';
    
    // Set default dates
    const now = new Date();
    const tomorrow = new Date(now.getTime() + 24 * 60 * 60 * 1000);
    
    const formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    };
    
    document.querySelector('input[name="tanggal_pinjam"]').value = formatDate(now);
    document.querySelector('input[name="tanggal_kembali"]').value = formatDate(tomorrow);
    
    document.getElementById('modalPeminjaman').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function openServiceModal(encryptedId, nama, nopol, kmTerakhir) {
    document.getElementById('service-nama').textContent = nama;
    document.getElementById('service-nopol').textContent = nopol;
    document.getElementById('service-km').textContent = kmTerakhir.toLocaleString();
    document.getElementById('service-km-input').value = kmTerakhir;
    document.getElementById('service-km-input').min = kmTerakhir;
    
    const form = document.getElementById('formService');
    form.action = '{{ url("/kendaraan-karyawan") }}/' + encryptedId + '/service';
    
    document.getElementById('modalService').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
    document.body.style.overflow = '';
}

// Close modal when clicking overlay
document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Form validation for Keluar
document.getElementById('formKeluar').addEventListener('submit', function(e) {
    const km = parseInt(document.getElementById('keluar-km').value);
    const kmMin = parseInt(document.getElementById('keluar-km').min);
    
    if (km < kmMin) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'KM keluar tidak boleh kurang dari KM terakhir (' + kmMin.toLocaleString() + ' km)',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
});

// Form validation for Masuk
document.getElementById('formMasuk').addEventListener('submit', function(e) {
    const km = parseInt(document.getElementById('masuk-km').value);
    const kmMin = parseInt(document.getElementById('masuk-km').min);
    
    if (km < kmMin) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'KM masuk tidak boleh kurang dari KM terakhir (' + kmMin.toLocaleString() + ' km)',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
});

// Form validation for Peminjaman
document.getElementById('formPeminjaman').addEventListener('submit', function(e) {
    const tanggalPinjam = new Date(document.querySelector('input[name="tanggal_pinjam"]').value);
    const tanggalKembali = new Date(document.querySelector('input[name="tanggal_kembali"]').value);
    
    if (tanggalKembali <= tanggalPinjam) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Tanggal kembali harus lebih besar dari tanggal pinjam',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
    
    const jumlahPenumpang = parseInt(document.querySelector('input[name="jumlah_penumpang"]').value) || 0;
    const maxKapasitas = parseInt(document.getElementById('pinjam-max').textContent) || 0;
    
    if (jumlahPenumpang > maxKapasitas && maxKapasitas > 0) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'Jumlah penumpang melebihi kapasitas maksimal (' + maxKapasitas + ' orang)',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
});

// Form validation for Service
document.getElementById('formService').addEventListener('submit', function(e) {
    const km = parseInt(document.getElementById('service-km-input').value);
    const kmMin = parseInt(document.getElementById('service-km-input').min);
    
    if (km < kmMin) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Peringatan',
            text: 'KM service tidak boleh kurang dari KM terakhir (' + kmMin.toLocaleString() + ' km)',
            confirmButtonColor: '#667eea'
        });
        return false;
    }
    
    const fileInput = document.querySelector('input[name="foto_bukti"]');
    if (fileInput.files.length > 0) {
        const fileSize = fileInput.files[0].size / 1024 / 1024; // in MB
        if (fileSize > 5) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Ukuran foto tidak boleh lebih dari 5MB',
                confirmButtonColor: '#667eea'
            });
            return false;
        }
    }
});

// Close modal on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const activeModals = document.querySelectorAll('.modal-overlay.active');
        activeModals.forEach(modal => {
            closeModal(modal.id);
        });
    }
});
</script>
@endpush
@endsection
