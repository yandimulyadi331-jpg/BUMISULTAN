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
        background: var(--gradient-1);
        padding: 20px 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        position: relative;
        margin-bottom: 20px;
    }

    .header-content {
        position: relative;
    }

    .back-btn {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        color: white;
        font-size: 28px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
    }

    .back-btn:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: translateY(-50%) scale(1.05);
    }

    .back-btn ion-icon {
        font-size: 24px;
    }

    .header-title {
        text-align: center;
        color: white;
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
        opacity: 0.95;
        font-size: 0.8rem;
        font-weight: 500;
    }

    /* CONTENT */
    #content-section {
        padding: 0 15px;
    }

    .calendar-container {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }
    .calendar-container {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 15px;
        margin-bottom: 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
        margin-bottom: 15px;
    }

    .day-header {
        text-align: center;
        padding: 12px 5px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .day-number {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-primary);
        margin-bottom: 2px;
    }

    .day-name {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .day-date {
        font-size: 0.65rem;
        color: var(--text-secondary);
        margin-top: 2px;
        text-transform: uppercase;
    }

    .jadwal-item {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 10px;
        margin-bottom: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        border-left: 4px solid transparent;
    }

    .jadwal-item:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .jadwal-item.selesai {
        border-left-color: #4CAF50;
    }

    .jadwal-item.proses {
        border-left-color: #FF9800;
    }

    .jadwal-item.bersih {
        border-left-color: #2196F3;
    }

    .jadwal-item.kotor {
        border-left-color: #f44336;
    }

    .jadwal-name {
        font-size: 0.85rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .jadwal-name ion-icon {
        font-size: 1rem;
    }

    .jadwal-time {
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .jadwal-time ion-icon {
        font-size: 0.9rem;
    }

    .jadwal-status {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 0.65rem;
        padding: 4px 10px;
        border-radius: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-selesai {
        background: linear-gradient(135deg, #4CAF50, #66BB6A);
        color: white;
        box-shadow: 0 2px 6px rgba(76, 175, 80, 0.3);
    }

    .status-proses {
        background: linear-gradient(135deg, #FF9800, #FFB74D);
        color: white;
        box-shadow: 0 2px 6px rgba(255, 152, 0, 0.3);
    }

    .status-bersih {
        background: linear-gradient(135deg, #2196F3, #42A5F5);
        color: white;
        box-shadow: 0 2px 6px rgba(33, 150, 243, 0.3);
    }

    .status-kotor {
        background: linear-gradient(135deg, #f44336, #EF5350);
        color: white;
        box-shadow: 0 2px 6px rgba(244, 67, 54, 0.3);
    }

    .saldo-info {
        font-size: 0.7rem;
        color: var(--text-primary);
        margin-top: 5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .saldo-info ion-icon {
        font-size: 0.9rem;
        color: #4CAF50;
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

    .jadwal-item {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .day-header:nth-child(1) { animation: fadeInUp 0.3s ease-out 0.05s forwards; opacity: 0; }
    .day-header:nth-child(2) { animation: fadeInUp 0.3s ease-out 0.1s forwards; opacity: 0; }
    .day-header:nth-child(3) { animation: fadeInUp 0.3s ease-out 0.15s forwards; opacity: 0; }
    .day-header:nth-child(4) { animation: fadeInUp 0.3s ease-out 0.2s forwards; opacity: 0; }
    .day-header:nth-child(5) { animation: fadeInUp 0.3s ease-out 0.25s forwards; opacity: 0; }
    .day-header:nth-child(6) { animation: fadeInUp 0.3s ease-out 0.3s forwards; opacity: 0; }
    .day-header:nth-child(7) { animation: fadeInUp 0.3s ease-out 0.35s forwards; opacity: 0; }
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
    <div class="calendar-container">
        <div class="calendar-header">
            @foreach($jadwal as $index => $item)
            @php
                $tanggal = \Carbon\Carbon::parse($item->tanggal_jadwal);
                $dayNames = ['MIN', 'SEN', 'SEL', 'RAB', 'KAM', 'JUM', 'SAB'];
                $monthNames = ['', 'JAN', 'FEB', 'MAR', 'APR', 'MEI', 'JUN', 'JUL', 'AGS', 'SEP', 'OKT', 'NOV', 'DES'];
            @endphp
            <div class="day-header">
                <div class="day-number">{{ $tanggal->format('d') }}</div>
                <div class="day-name">{{ $dayNames[$tanggal->dayOfWeek] }}</div>
                <div class="day-date">{{ $monthNames[$tanggal->month] }}</div>
            </div>
            @endforeach
        </div>

        <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 8px;">
            @forelse($jadwal as $item)
            <div>
                <a href="{{ route('khidmat.karyawan.show', $item->id) }}" style="text-decoration: none;">
                    <div class="jadwal-item {{ $item->status_selesai ? 'selesai' : 'proses' }} {{ $item->status_kebersihan == 'bersih' ? 'bersih' : 'kotor' }}">
                        <div class="jadwal-name">
                            <ion-icon name="people-outline"></ion-icon>
                            <span>{{ str_replace('Kelompok ', '', $item->nama_kelompok) }}</span>
                        </div>
                        
                        <div class="jadwal-time">
                            <ion-icon name="person-outline"></ion-icon>
                            <span>{{ $item->petugas->count() }} Petugas</span>
                        </div>
                        
                        <div style="margin: 5px 0;">
                            <span class="jadwal-status {{ $item->status_selesai ? 'status-selesai' : 'status-proses' }}">
                                <ion-icon name="{{ $item->status_selesai ? 'checkmark-circle' : 'time' }}-outline"></ion-icon>
                                {{ $item->status_selesai ? 'Selesai' : 'Proses' }}
                            </span>
                        </div>

                        <div style="margin: 5px 0;">
                            <span class="jadwal-status {{ $item->status_kebersihan == 'bersih' ? 'status-bersih' : 'status-kotor' }}">
                                <ion-icon name="{{ $item->status_kebersihan == 'bersih' ? 'checkmark-circle' : 'alert-circle' }}-outline"></ion-icon>
                                {{ $item->status_kebersihan == 'bersih' ? 'Bersih' : 'Kotor' }}
                            </span>
                        </div>

                        <div class="saldo-info">
                            <ion-icon name="cash-outline"></ion-icon>
                            <span>Rp {{ number_format($item->total_belanja / 1000, 0) }}K</span>
                        </div>

                        @if($item->foto->count() > 0)
                        <div style="font-size: 0.65rem; color: var(--text-secondary); margin-top: 5px; display: flex; align-items: center; gap: 4px;">
                            <ion-icon name="camera-outline" style="font-size: 0.8rem;"></ion-icon>
                            <span>{{ $item->foto->count() }} Foto</span>
                        </div>
                        @endif
                    </div>
                </a>

                @if($item->belanja->count() > 1)
                <a href="{{ route('khidmat.karyawan.show', $item->id) }}" style="text-decoration: none;">
                    <div class="jadwal-item" style="margin-top: 8px; border-left-color: #ffc107;">
                        <div class="jadwal-time" style="justify-content: center; font-weight: 700;">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            <span>{{ $item->belanja->count() - 1 }} item lagi</span>
                        </div>
                    </div>
                </a>
                @endif
            </div>
            @empty
            <div style="grid-column: 1 / -1;">
                <div class="empty-state">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <h5>Belum Ada Jadwal</h5>
                    <p>Belum ada jadwal khidmat untuk 7 hari ke depan</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection
