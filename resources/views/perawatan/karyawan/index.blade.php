@extends('layouts.mobile.app')
@section('content')
<style>
    body {
        background: var(--bg-primary);
        min-height: 100vh;
    }
    
    .perawatan-container {
        background: transparent;
        min-height: 100vh;
        padding: 20px;
    }
    
    /* Progress Card */
    .streak-card {
        background: var(--bg-primary);
        border-radius: 25px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 12px 12px 24px var(--shadow-dark), 
                   -12px -12px 24px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.1);
        border: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .streak-card:hover {
        box-shadow: 15px 15px 30px var(--shadow-dark), 
                   -15px -15px 30px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    
    .streak-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    
    .progress-label {
        color: var(--text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
        font-weight: 700;
    }
    
    .progress-count {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        color: #fff;
        padding: 8px 18px;
        border-radius: 20px;
        font-weight: 800;
        font-size: 15px;
        box-shadow: 6px 6px 12px rgba(0,0,0,0.2),
                   -3px -3px 8px rgba(255,255,255,0.1),
                   inset 0 2px 4px rgba(255,255,255,0.2);
    }
    
    .streak-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .fire-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #ff6f00 0%, #ff8f00 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #fff;
        box-shadow: 8px 8px 16px var(--shadow-dark), 
                   -8px -8px 16px var(--shadow-light), 
                   inset 0 2px 4px rgba(255, 255, 255, 0.4),
                   0 0 20px rgba(255, 111, 0, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { 
            transform: scale(1); 
            box-shadow: 8px 8px 16px var(--shadow-dark), 
                       -8px -8px 16px var(--shadow-light), 
                       inset 0 2px 4px rgba(255, 255, 255, 0.4),
                       0 0 20px rgba(255, 111, 0, 0.3);
        }
        50% { 
            transform: scale(1.08); 
            box-shadow: 10px 10px 20px var(--shadow-dark), 
                       -10px -10px 20px var(--shadow-light), 
                       inset 0 3px 6px rgba(255, 255, 255, 0.5),
                       0 0 30px rgba(255, 111, 0, 0.5);
        }
    }
    
    .streak-text {
        color: var(--text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 5px;
        font-weight: 700;
    }
    
    .streak-count {
        color: var(--text-primary);
        font-size: 36px;
        font-weight: bold;
        text-shadow: 3px 3px 6px var(--shadow-dark), 
                    -2px -2px 4px var(--shadow-light);
        letter-spacing: -1px;
    }
    
    .streak-days {
        color: var(--text-secondary);
        font-size: 18px;
        margin-left: 8px;
    }
    
    .footprint-icon {
        font-size: 32px;
        color: var(--icon-color);
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: var(--bg-primary);
        box-shadow: 6px 6px 12px var(--shadow-dark), 
                   -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .footprint-icon:hover {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), 
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    /* Weekly Tracker */
    .weekly-tracker {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        gap: 8px;
    }
    
    .day-circle {
        text-align: center;
        flex: 1;
    }
    
    .circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 8px;
        border: none;
        background: var(--bg-primary);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 8px 8px 16px var(--shadow-dark), 
                   -8px -8px 16px var(--shadow-light);
        cursor: pointer;
    }
    
    .circle:hover {
        box-shadow: 6px 6px 12px var(--shadow-dark), 
                   -6px -6px 12px var(--shadow-light);
        transform: translateY(-1px);
    }
    
    .circle:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), 
                   inset -4px -4px 8px var(--shadow-light);
        transform: translateY(0);
    }
    
    .circle.completed {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        border: none;
        box-shadow: inset 5px 5px 10px rgba(0, 0, 0, 0.25), 
                   inset -3px -3px 8px rgba(255, 255, 255, 0.15), 
                   0 6px 16px rgba(39, 174, 96, 0.4),
                   0 0 0 1px rgba(39, 174, 96, 0.1);
        animation: completePop 0.5s ease-out;
    }
    
    @keyframes completePop {
        0% { transform: scale(0.8); opacity: 0; }
        50% { transform: scale(1.15); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    .circle.completed:hover {
        box-shadow: inset 5px 5px 10px rgba(0, 0, 0, 0.25), 
                   inset -3px -3px 8px rgba(255, 255, 255, 0.15), 
                   0 8px 20px rgba(39, 174, 96, 0.5),
                   0 0 0 1px rgba(39, 174, 96, 0.2);
    }
    
    .circle.completed i {
        color: #fff;
        font-size: 22px;
    }
    
    .circle.today {
        border: 3px solid #ffd54f;
        background: var(--bg-primary);
        box-shadow: 8px 8px 16px var(--shadow-dark), 
                   -8px -8px 16px var(--shadow-light), 
                   0 0 25px rgba(255, 213, 79, 0.5),
                   inset 0 0 20px rgba(255, 213, 79, 0.1);
        animation: todayPulse 2s ease-in-out infinite;
    }
    
    @keyframes todayPulse {
        0%, 100% { 
            box-shadow: 8px 8px 16px var(--shadow-dark), 
                       -8px -8px 16px var(--shadow-light), 
                       0 0 25px rgba(255, 213, 79, 0.5),
                       inset 0 0 20px rgba(255, 213, 79, 0.1);
            border-color: #ffd54f;
        }
        50% { 
            box-shadow: 10px 10px 20px var(--shadow-dark), 
                       -10px -10px 20px var(--shadow-light), 
                       0 0 40px rgba(255, 213, 79, 0.7),
                       inset 0 0 25px rgba(255, 213, 79, 0.2);
            border-color: #ffca28;
            transform: scale(1.05);
        }
    }
    
    .day-label {
        color: var(--text-secondary);
        font-size: 11px;
        font-weight: 600;
    }
    
    /* Stats Section */
    .stats-section {
        margin-bottom: 20px;
    }
    
    .stats-label {
        color: var(--text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 15px;
        font-weight: 700;
    }
    
    .stats-value {
        color: var(--text-primary);
        font-size: 48px;
        font-weight: 900;
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px var(--shadow-dark), 
                    -1px -1px 2px var(--shadow-light);
        letter-spacing: -2px;
        line-height: 1;
    }
    
    .stats-target {
        color: var(--text-secondary);\n        font-size: 20px;\n        font-weight: 600;\n    }\n    \n    .progress-message {\n        color: var(--badge-green);\n        font-size: 16px;\n        font-weight: 700;\n        display: flex;\n        align-items: center;\n        gap: 8px;\n        animation: bounceMessage 2s ease-in-out infinite;\n    }\n    \n    .progress-message i {\n        font-size: 22px;\n    }\n    \n    @keyframes bounceMessage {\n        0%, 100% { transform: translateY(0); }\n        50% { transform: translateY(-5px); }\n    }
    
    .stats-percentage {
        color: var(--badge-green);
        font-size: 36px;
        font-weight: 900;
        display: inline-block;
        animation: pulsePercentage 1.5s ease-in-out infinite;
    }
    
    @keyframes pulsePercentage {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .progress-bar-container {
        width: 100%;
        height: 20px;
        background: var(--bg-primary);
        border-radius: 25px;
        overflow: hidden;
        margin-top: 15px;
        margin-bottom: 15px;
        border: none;
        box-shadow: inset 6px 6px 12px var(--shadow-dark), 
                   inset -6px -6px 12px var(--shadow-light);
        position: relative;
    }
    
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--badge-green) 0%, #52c77a 50%, var(--badge-green) 100%);
        background-size: 200% 100%;
        border-radius: 20px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(39, 174, 96, 0.5);
        animation: shimmerProgress 2s linear infinite;
    }
    
    @keyframes shimmerProgress {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        animation: slideProgress 1.5s ease-in-out infinite;
    }
    
    @keyframes slideProgress {
        0% { left: -100%; }
        100% { left: 200%; }
    }
    
    /* Menu Cards */
    .menu-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .menu-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        text-decoration: none;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        min-height: 140px;
        box-shadow: 10px 10px 20px var(--shadow-dark), 
                   -10px -10px 20px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .menu-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    }
    
    .menu-card:hover {
        transform: translateY(-8px);
        box-shadow: 12px 12px 24px var(--shadow-dark), 
                   -12px -12px 24px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.15);
    }
    
    .menu-card:active {
        transform: translateY(-2px) scale(0.98);
        box-shadow: inset 6px 6px 12px var(--shadow-dark), 
                   inset -6px -6px 12px var(--shadow-light);
    }
    
    .menu-icon {
        font-size: 42px;
        margin-bottom: 12px;
        filter: drop-shadow(2px 2px 4px var(--shadow-dark)) 
                drop-shadow(-1px -1px 2px var(--shadow-light));\n        transition: all 0.3s ease;\n    }\n    \n    .menu-card:hover .menu-icon {\n        transform: scale(1.1) translateY(-2px);\n        filter: drop-shadow(3px 3px 6px var(--shadow-dark)) \n                drop-shadow(-2px -2px 3px var(--shadow-light));\n    }\n    \n    .menu-card:active .menu-icon {\n        transform: scale(0.95);\n        filter: drop-shadow(1px 1px 2px var(--shadow-dark));\n    }
    
    .menu-title {
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 4px;
        text-align: center;
    }
    
    .menu-subtitle {
        color: var(--text-secondary);
        font-size: 10px;
        text-align: center;
    }
    
    /* Activity Item */
    .activity-item {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        margin-bottom: 12px;
        border-left: 4px solid var(--badge-green);
        border-top: none;
        border-right: none;
        border-bottom: none;
        box-shadow: 8px 8px 16px var(--shadow-dark), 
                   -8px -8px 16px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .activity-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: linear-gradient(180deg, var(--badge-green), #52c77a);
        box-shadow: 0 0 10px rgba(39, 174, 96, 0.3);
    }
    
    .activity-item:hover {
        transform: translateX(8px);
        box-shadow: 10px 10px 20px var(--shadow-dark), 
                   -10px -10px 20px var(--shadow-light),
                   inset 0 1px 0 rgba(255, 255, 255, 0.08);
        border-left-color: #52c77a;
    }
    
    .activity-item:active {
        transform: translateX(4px) scale(0.98);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), 
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    .activity-title {
        color: var(--text-primary);
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .activity-time {
        color: var(--text-secondary);
        font-size: 11px;
    }
    
    .section-title {
        color: var(--text-primary);
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        text-transform: uppercase;
        letter-spacing: 1px;
        text-shadow: 1px 1px 2px var(--shadow-dark), 
                    -1px -1px 1px var(--shadow-light);
        padding-left: 12px;
        border-left: 4px solid var(--badge-green);
        position: relative;
    }\n    \n    .section-title::before {\n        content: '';\n        position: absolute;\n        left: -4px;\n        top: 0;\n        bottom: 0;\n        width: 4px;\n        background: linear-gradient(180deg, var(--badge-green), #52c77a);\n        box-shadow: 0 0 10px rgba(39, 174, 96, 0.3);\n    }
    
    /* Menu Card Color Variants */
    .menu-card.harian .menu-icon {
        color: #f9a825;
    }
    
    .menu-card.mingguan .menu-icon {
        color: #039be5;
    }
    
    .menu-card.bulanan .menu-icon {
        color: #8e24aa;
    }
    
    .menu-card.tahunan .menu-icon {
        color: #e64a19;
    }
    
    .menu-card.history .menu-icon {
        color: #43a047;
    }
    
    .activity-note {
        color: var(--text-secondary);
        font-size: 12px;
        margin-top: 5px;
        opacity: 0.8;
    }
</style>

<div class="perawatan-container">
    <!-- Streak Card -->
    <div class="streak-card">
        <div class="streak-header">
            <div class="streak-info">
                <div class="fire-icon">
                    <i class="ti ti-flame"></i>
                </div>
                <div>
                    <div class="streak-text">STREAK</div>
                    <div>
                        <span class="streak-count">{{ $completedHariIni }}</span>
                        <span class="streak-days">HARI INI</span>
                    </div>
                </div>
            </div>
            <div class="footprint-icon">
                <i class="ti ti-activity"></i>
            </div>
        </div>
        
        <!-- Weekly Tracker -->
        <div class="weekly-tracker">
            @php
                $days = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
                $dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
                $today = now()->dayOfWeek == 0 ? 7 : now()->dayOfWeek; // Convert Sunday from 0 to 7
            @endphp
            @foreach($days as $index => $day)
                <div class="day-circle">
                    <div class="circle {{ $index + 1 < $today ? 'completed' : '' }} {{ $index + 1 == $today ? 'today' : '' }}">
                        @if($index + 1 < $today)
                            <i class="ti ti-check"></i>
                        @endif
                    </div>
                    <div class="day-label">{{ $dayLabels[$index] }}</div>
                </div>
            @endforeach
        </div>
        
        <!-- Stats Section -->
        <div class="stats-section">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div class="stats-label">CHECKLIST</div>
                <div class="progress-count">{{ $completedMingguIni }}/{{ $checklistMingguIni }}</div>
            </div>
            
            <div class="progress-bar-container">
                @php
                    $percentage = $checklistMingguIni > 0 ? round(($completedMingguIni / $checklistMingguIni) * 100) : 0;
                @endphp
                <div class="progress-bar-fill" style="width: {{ $percentage }}%"></div>
            </div>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 15px;">
                <div class="stats-percentage">{{ $percentage }}%</div>
                <div class="progress-message">
                    @if($percentage >= 100)
                        <i class="ti ti-trophy"></i> <strong>SEMPURNA! 🎉</strong>
                    @elseif($percentage >= 75)
                        <i class="ti ti-star"></i> <strong>Luar Biasa! ⭐</strong>
                    @elseif($percentage >= 50)
                        <i class="ti ti-thumb-up"></i> <strong>Hebat! 👍</strong>
                    @elseif($percentage >= 25)
                        <i class="ti ti-rocket"></i> <strong>Semangat! 🚀</strong>
                    @else
                        <i class="ti ti-flame"></i> <strong>Ayo Mulai! 🔥</strong>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Menu Grid -->
    <div class="section-title">Pilih Checklist</div>
    <div class="menu-grid">
        <a href="{{ route('perawatan.karyawan.checklist', 'harian') }}" class="menu-card harian">
            <div class="menu-icon"><i class="ti ti-sun" style="font-size: 42px;"></i></div>
            <div class="menu-title">Harian</div>
            <div class="menu-subtitle">Tugas setiap hari</div>
        </a>

        <a href="{{ route('perawatan.karyawan.checklist', 'mingguan') }}" class="menu-card mingguan">
            <div class="menu-icon"><i class="ti ti-calendar-week" style="font-size: 42px;"></i></div>
            <div class="menu-title">Mingguan</div>
            <div class="menu-subtitle">Tugas setiap minggu</div>
        </a>

        <a href="{{ route('perawatan.karyawan.checklist', 'bulanan') }}" class="menu-card bulanan">
            <div class="menu-icon"><i class="ti ti-calendar-month" style="font-size: 42px;"></i></div>
            <div class="menu-title">Bulanan</div>
            <div class="menu-subtitle">Tugas setiap bulan</div>
        </a>

        <a href="{{ route('perawatan.karyawan.checklist', 'tahunan') }}" class="menu-card tahunan">
            <div class="menu-icon"><i class="ti ti-calendar-event" style="font-size: 42px;"></i></div>
            <div class="menu-title">Tahunan</div>
            <div class="menu-subtitle">Tugas setiap tahun</div>
        </a>
    </div>

    <!-- History Button -->
    <a href="{{ route('perawatan.karyawan.history') }}" class="menu-card history" style="display: block; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <div class="menu-icon" style="margin: 0;"><i class="ti ti-history" style="font-size: 42px;"></i></div>
                <div style="text-align: left;">
                    <div class="menu-title">History Aktivitas</div>
                    <div class="menu-subtitle">Lihat riwayat checklist</div>
                </div>
            </div>
            <i class="ti ti-chevron-right" style="color: var(--icon-color); font-size: 20px;"></i>
        </div>
    </a>

    <!-- Recent Activity -->
    @if($recentActivities->count() > 0)
    <div class="section-title">Aktivitas Terakhir</div>
    @foreach($recentActivities->take(5) as $activity)
    <div class="activity-item">
        <div class="activity-title">{{ $activity->masterPerawatan->nama_kegiatan }}</div>
        <div class="activity-time">
            <i class="ti ti-calendar"></i> {{ $activity->tanggal_eksekusi->format('d M Y') }}
            <i class="ti ti-clock ms-2"></i> {{ date('H:i', strtotime($activity->waktu_eksekusi)) }}
        </div>
        @if($activity->catatan)
        <div class="activity-note">{{ $activity->catatan }}</div>
        @endif
    </div>
    @endforeach
    @endif
</div>

<div style="height: 100px;"></div>

@endsection
