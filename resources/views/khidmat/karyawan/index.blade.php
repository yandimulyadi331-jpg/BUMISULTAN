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
        background: var(--bg-body);
        min-height: 100vh;
        padding-bottom: 80px;
        transition: all 0.3s ease;
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

    /* HEADER */
    #header-section {
        background: var(--bg-primary);
        padding: 25px 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        position: relative;
        margin: 15px;
        border-radius: 20px;
    }

    .header-content {
        position: relative;
    }

    .back-btn {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-primary);
        font-size: 28px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .back-btn:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .back-btn ion-icon {
        font-size: 24px;
    }

    .header-title {
        text-align: center;
        color: var(--text-primary);
        padding: 0 50px;
    }

    .header-title h3 {
        font-weight: 700;
        margin: 0;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .header-title h3 ion-icon {
        font-size: 24px;
    }

    .header-title p {
        margin: 5px 0 0 0;
        opacity: 0.8;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--text-secondary);
    }

    /* CONTENT */
    #content-section {
        padding: 0 15px;
    }

    .table-container {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 0;
        overflow: hidden;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.1);
    }

    .table-header {
        display: grid;
        grid-template-columns: 60px 90px 1fr 90px;
        background: var(--bg-primary);
        padding: 15px 10px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        border-bottom: 2px solid var(--border-color);
        letter-spacing: 0.5px;
        gap: 10px;
        box-shadow: inset 0 -2px 4px rgba(0, 0, 0, 0.05);
    }

    .table-row {
        display: grid;
        grid-template-columns: 60px 90px 1fr 90px;
        padding: 12px 10px;
        border-bottom: 1px solid var(--border-color);
        transition: all 0.3s ease;
        align-items: center;
        text-decoration: none;
        color: var(--text-primary);
        gap: 10px;
        position: relative;
    }

    .table-row::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, 
            transparent 0%, 
            rgba(255, 255, 255, 0.03) 50%, 
            transparent 100%);
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .table-row:hover::before {
        opacity: 1;
    }

    .table-row:last-child {
        border-bottom: none;
    }

    .table-row:active {
        background: var(--bg-body);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .col-tanggal {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 2px;
        padding: 8px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    .tanggal-number {
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    .tanggal-day {
        font-size: 0.6rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 700;
    }

    .tanggal-month {
        font-size: 0.55rem;
        color: var(--text-secondary);
        text-transform: uppercase;
    }

    .col-kelompok {
        font-size: 0.75rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .kelompok-name {
        font-size: 0.85rem;
        font-weight: 800;
    }

    .kelompok-petugas {
        font-size: 0.65rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .col-content {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .content-row {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        color: var(--text-secondary);
    }

    .content-row ion-icon {
        font-size: 0.9rem;
    }

    .col-badge {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: flex-end;
    }

    .badge-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 3px;
        font-size: 0.6rem;
        padding: 6px 12px;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        min-width: 70px;
        transition: all 0.3s ease;
    }

    .badge-selesai {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
        box-shadow: 0 3px 8px rgba(76, 175, 80, 0.4),
                   0 1px 3px rgba(76, 175, 80, 0.3),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .badge-proses {
        background: linear-gradient(135deg, #FF9800, #FFB74D);
        color: white;
        box-shadow: 0 3px 8px rgba(255, 152, 0, 0.4),
                   0 1px 3px rgba(255, 152, 0, 0.3),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .badge-bersih {
        background: linear-gradient(135deg, #2196F3, #42A5F5);
        color: white;
        box-shadow: 0 3px 8px rgba(33, 150, 243, 0.4),
                   0 1px 3px rgba(33, 150, 243, 0.3),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .badge-kotor {
        background: linear-gradient(135deg, #f44336, #EF5350);
        color: white;
        box-shadow: 0 3px 8px rgba(244, 67, 54, 0.4),
                   0 1px 3px rgba(244, 67, 54, 0.3),
                   inset 0 -2px 4px rgba(0, 0, 0, 0.1),
                   inset 0 1px 1px rgba(255, 255, 255, 0.3);
    }

    .badge-neutral {
        background: var(--bg-body);
        color: var(--text-primary);
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light),
                   inset 1px 1px 2px rgba(255, 255, 255, 0.1);
        font-weight: 800;
    }

    .badge-pill ion-icon {
        font-size: 0.85rem;
    }

    .empty-state {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 50px 20px;
        text-align: center;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .empty-state ion-icon {
        font-size: 80px;
        color: var(--text-secondary);
        opacity: 0.5;
        margin-bottom: 20px;
    }

    .empty-state h5 {
        color: var(--text-primary);
        font-weight: 700;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: var(--text-secondary);
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .table-row {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }

    .table-row:nth-child(2) { animation-delay: 0.05s; }
    .table-row:nth-child(3) { animation-delay: 0.1s; }
    .table-row:nth-child(4) { animation-delay: 0.15s; }
    .table-row:nth-child(5) { animation-delay: 0.2s; }
    .table-row:nth-child(6) { animation-delay: 0.25s; }
    .table-row:nth-child(7) { animation-delay: 0.3s; }
    .table-row:nth-child(8) { animation-delay: 0.35s; }
    .table-row:nth-child(9) { animation-delay: 0.4s; }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>
                <ion-icon name="restaurant-outline"></ion-icon>
                Jadwal Khidmat
            </h3>
            <p>Belanja Masak Santri - 7 Hari</p>
        </div>
    </div>
</div>

<div id="content-section">
    <div class="table-container">
        <!-- Table Header -->
        <div class="table-header">
            <div>Tanggal</div>
            <div>Kelompok</div>
            <div>Content</div>
            <div>Status</div>
        </div>

        <!-- Table Rows -->
        @forelse($jadwal as $item)
        @php
            $tanggal = \Carbon\Carbon::parse($item->tanggal_jadwal);
            $dayNames = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
            $monthNames = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];
        @endphp
        <a href="{{ route('khidmat.karyawan.show', $item->id) }}" class="table-row">
            <!-- Kolom Tanggal -->
            <div class="col-tanggal">
                <div class="tanggal-number">{{ $tanggal->format('d') }}</div>
                <div class="tanggal-day">{{ $dayNames[$tanggal->dayOfWeek] }}</div>
                <div class="tanggal-month">{{ $monthNames[$tanggal->month] }}</div>
            </div>

            <!-- Kolom Kelompok -->
            <div class="col-kelompok">
                <div class="kelompok-name">{{ str_replace('Kelompok ', '', $item->nama_kelompok) }}</div>
                <div class="kelompok-petugas">
                    <ion-icon name="people-outline"></ion-icon>
                    <span>{{ $item->petugas->count() }} Orang</span>
                </div>
            </div>

            <!-- Kolom Content -->
            <div class="col-content">
                <div class="content-row">
                    <ion-icon name="cash-outline"></ion-icon>
                    <span>Rp {{ number_format($item->total_belanja / 1000, 0) }}K</span>
                </div>
                @if($item->foto->count() > 0)
                <div class="content-row">
                    <ion-icon name="camera-outline"></ion-icon>
                    <span>{{ $item->foto->count() }} Foto</span>
                </div>
                @endif
                @if($item->belanja->count() > 1)
                <div class="content-row">
                    <ion-icon name="bag-outline"></ion-icon>
                    <span>{{ $item->belanja->count() }} Belanja</span>
                </div>
                @endif
            </div>

            <!-- Kolom Badge -->
            <div class="col-badge">
                <span class="badge-pill {{ $item->status_selesai ? 'badge-selesai' : 'badge-proses' }}">
                    <ion-icon name="{{ $item->status_selesai ? 'checkmark-circle' : 'time' }}-outline"></ion-icon>
                    {{ $item->status_selesai ? 'Selesai' : 'Proses' }}
                </span>
                <span class="badge-pill {{ $item->status_kebersihan == 'bersih' ? 'badge-bersih' : 'badge-kotor' }}">
                    <ion-icon name="{{ $item->status_kebersihan == 'bersih' ? 'checkmark-circle' : 'alert-circle' }}-outline"></ion-icon>
                    {{ $item->status_kebersihan == 'bersih' ? 'Bersih' : 'Kotor' }}
                </span>
            </div>
        </a>
        @empty
        <div style="padding: 20px;">
            <div class="empty-state">
                <ion-icon name="calendar-outline"></ion-icon>
                <h5>Belum Ada Jadwal</h5>
                <p>Belum ada jadwal khidmat untuk 7 hari ke depan</p>
            </div>
        </div>
        @endforelse
    </div>
</div>

@endsection
