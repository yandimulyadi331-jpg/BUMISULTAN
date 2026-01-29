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
    
    /* Header */
    .page-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    
    .back-button {
        width: 45px;
        height: 45px;
        background: var(--bg-primary);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--icon-color);
        text-decoration: none;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .back-button:hover {
        transform: translateY(-2px);
    }
    
    .back-button:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    .header-info {
        flex: 1;
    }
    
    .header-title {
        color: var(--text-primary);
        font-size: 24px;
        font-weight: bold;
        margin: 0;
    }
    
    .header-subtitle {
        color: var(--text-secondary);
        font-size: 13px;
    }
    
    /* Progress Card */
    .progress-card {
        background: var(--bg-primary);
        border-radius: 25px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .progress-card:hover {
        box-shadow: 12px 12px 24px var(--shadow-dark),
                   -12px -12px 24px var(--shadow-light);
    }
    
    .progress-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
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
        box-shadow: 4px 4px 8px rgba(0,0,0,0.2),
                   -2px -2px 6px rgba(255,255,255,0.1);
    }
    
    .progress-bar-container {
        width: 100%;
        height: 20px;
        background: var(--bg-primary);
        border-radius: 25px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: inset 6px 6px 12px var(--shadow-dark),
                   inset -6px -6px 12px var(--shadow-light);
    }
    
    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #26a69a 0%, #4db6ac 50%, #26a69a 100%);
        background-size: 200% 100%;
        border-radius: 20px;
        transition: width 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        box-shadow: 0 0 15px rgba(38, 166, 154, 0.5);
        animation: shimmer 2s linear infinite;
    }
    
    .progress-bar-fill::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
        animation: slide 1.5s linear infinite;
    }
    
    @keyframes shimmer {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    @keyframes slide {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(100%); }
    }
    
    .progress-percentage {
        color: var(--badge-green);
        font-size: 36px;
        font-weight: 900;
        animation: pulse 1.5s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    .progress-message {
        color: var(--badge-green);
        font-size: 16px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        animation: bounce 2s ease-in-out infinite;
    }
    
    .progress-message i {
        font-size: 22px;
        animation: rotate 3s linear infinite;
    }
    
    @keyframes bounce {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    @keyframes rotate {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Timeline Stepper Horizontal */
    .timeline-stepper {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
        padding: 0 10px;
        position: relative;
    }
    
    .stepper-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        flex: 1;
        position: relative;
    }
    
    .stepper-circle {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: var(--bg-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 26px;
        color: var(--text-secondary);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 2;
        position: relative;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .stepper-item.active .stepper-circle {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        color: white;
        animation: popIn 0.5s ease-out, pulseGlow 2s ease-in-out infinite;
        box-shadow: 0 6px 25px rgba(39, 174, 96, 0.5),
                   inset 0 2px 4px rgba(255,255,255,0.3);
    }
    
    .stepper-item.active:not(.completed) .stepper-circle {
        animation: popIn 0.5s ease-out, pulseActive 1.5s ease-in-out infinite;
    }
    
    .stepper-item.active:not(.completed) .stepper-circle i {
        animation: rotateIcon 2s linear infinite;
    }
    
    .stepper-item.completed .stepper-circle {
        animation: checkmark 0.5s ease-out, pulseGlow 3s ease-in-out infinite;
    }
    
    @keyframes popIn {
        0% { transform: scale(0.5); opacity: 0; }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); opacity: 1; }
    }
    
    @keyframes checkmark {
        0% { transform: scale(0.5) rotate(-45deg); }
        50% { transform: scale(1.2) rotate(10deg); }
        100% { transform: scale(1) rotate(0deg); }
    }
    
    @keyframes pulseGlow {
        0%, 100% { 
            box-shadow: 0 4px 20px rgba(38, 166, 154, 0.6);
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 6px 30px rgba(38, 166, 154, 0.9);
            transform: scale(1.05);
        }
    }
    
    @keyframes pulseActive {
        0%, 100% { 
            box-shadow: 0 4px 25px rgba(255, 193, 7, 0.7);
            border-color: #ffd54f;
            transform: scale(1);
        }
        50% { 
            box-shadow: 0 6px 35px rgba(255, 193, 7, 1);
            border-color: #ffb300;
            transform: scale(1.1);
        }
    }
    
    @keyframes rotateIcon {
        0% { transform: rotate(0deg) scale(1); }
        25% { transform: rotate(10deg) scale(1.1); }
        50% { transform: rotate(-10deg) scale(1.1); }
        75% { transform: rotate(5deg) scale(1.05); }
        100% { transform: rotate(0deg) scale(1); }
    }
    
    .stepper-line {
        position: absolute;
        top: 25px;
        left: 50%;
        width: 100%;
        height: 4px;
        background: var(--shadow-dark);
        opacity: 0.2;
        z-index: 1;
        transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .stepper-line.filled {
        background: linear-gradient(90deg, #26a69a 0%, #4db6ac 100%);
        box-shadow: 0 0 15px rgba(38, 166, 154, 0.7);
        animation: fillLine 1.2s ease-out;
        position: relative;
    }
    
    .stepper-line.filled::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
        animation: movingLight 2s linear infinite;
    }
    
    @keyframes fillLine {
        from { 
            transform: scaleX(0); 
            transform-origin: left;
            opacity: 0;
        }
        to { 
            transform: scaleX(1); 
            transform-origin: left;
            opacity: 1;
        }
    }
    
    @keyframes movingLight {
        0% { transform: translateX(-100%); }
        100% { transform: translateX(200%); }
    }
    
    .stepper-item:last-child .stepper-line {
        display: none;
    }
    
    .stepper-label {
        margin-top: 10px;
        font-size: 11px;
        font-weight: 600;
        color: var(--text-secondary);
        opacity: 0.6;
        text-align: center;
        transition: all 0.3s;
    }
    
    .stepper-item.active .stepper-label {
        color: var(--badge-green);
        font-size: 12px;
        opacity: 1;
        animation: bounce 2s ease-in-out infinite;
    }
    
    .stepper-item.completed .stepper-label {
        color: var(--badge-green);
        opacity: 0.8;
    }
    
    /* Responsive Timeline */
    @media (max-width: 480px) {
        .stepper-circle {
            width: 40px;
            height: 40px;
            font-size: 18px;
        }
        
        .stepper-line {
            top: 20px;
        }
        
        .stepper-label {
            font-size: 9px;
        }
    }
    
    /* Progress Bar (tetap ada di bawah timeline) */
    .filter-container {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        margin-bottom: 20px;
        padding-bottom: 10px;
        scrollbar-width: none;
    }
    
    .filter-container::-webkit-scrollbar {
        display: none;
    }
    
    .filter-btn {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 12px 24px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        transition: all 0.3s;
        cursor: pointer;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .filter-btn.active {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        color: #fff;
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -2px -2px 6px rgba(255,255,255,0.1),
                   0 4px 15px rgba(39, 174, 96, 0.4);
    }
    
    /* Checklist Items */
    .checklist-item {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 18px;
        margin-bottom: 15px;
        transition: all 0.3s;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }
    
    .checklist-item.completed {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
        opacity: 0.8;
    }
    
    .checklist-item:hover {
        transform: translateY(-2px);
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light);
    }
    
    .checklist-item:active {
        transform: scale(0.98);
    }
    
    .checkbox-custom {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: var(--bg-primary);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        flex-shrink: 0;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .checkbox-custom.checked {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        box-shadow: inset 3px 3px 6px rgba(0,0,0,0.2),
                   inset -2px -2px 4px rgba(255,255,255,0.1),
                   0 4px 15px rgba(39, 174, 96, 0.5);
    }
    
    .checkbox-custom.checked i {
        color: #fff;
        font-size: 22px;
        font-weight: bold;
    }
    
    .checklist-content {
        flex: 1;
        margin-left: 15px;
    }
    
    .checklist-title {
        color: var(--text-primary);
        font-size: 15px;
        font-weight: 700;
        margin-bottom: 6px;
    }
    
    .checklist-item.completed .checklist-title {
        color: var(--text-secondary);
        text-decoration: line-through;
    }
    
    .checklist-desc {
        color: var(--text-secondary);
        font-size: 12px;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    
    .kategori-badge {
        display: inline-block;
        padding: 5px 14px;
        border-radius: 15px;
        font-size: 10px;
        font-weight: 700;
        background: var(--bg-primary);
        color: var(--badge-green);
        margin-right: 6px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }
    
    .time-badge {
        color: var(--badge-green);
        font-size: 11px;
        font-weight: 700;
    }
    
    .jam-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: #fff;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 700;
        margin-right: 8px;
        box-shadow: 4px 4px 8px rgba(0,0,0,0.2),
                   -2px -2px 6px rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }
    
    .jam-badge:hover {
        transform: translateY(-1px);
        box-shadow: 5px 5px 10px rgba(0,0,0,0.25),
                   -3px -3px 8px rgba(255,255,255,0.15);
    }
    
    .jam-badge i {
        font-size: 13px;
    }
    
    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: #fff;
        padding: 5px 12px;
        border-radius: 15px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 5px;
        box-shadow: 4px 4px 8px rgba(0,0,0,0.2),
                   -2px -2px 6px rgba(255,255,255,0.1);
        transition: all 0.3s ease;
    }
    
    .user-badge:hover {
        transform: translateY(-1px);
        box-shadow: 5px 5px 10px rgba(0,0,0,0.25),
                   -3px -3px 8px rgba(255,255,255,0.15);
    }
    
    .user-badge i {
        font-size: 13px;
    }
    
    .note-section {
        background: var(--bg-primary);
        border-radius: 12px;
        padding: 12px;
        margin-top: 10px;
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    .note-text {
        color: var(--text-secondary);
        font-size: 11px;
    }
    
    .foto-preview {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 15px;
        margin-top: 12px;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .btn-uncheck {
        background: var(--bg-primary);
        color: var(--badge-red);
        border: none;
        padding: 10px 18px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        margin-top: 12px;
        transition: all 0.3s;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }
    
    .btn-uncheck:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }
    
    .btn-uncheck:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }
    
    /* Modal */
    .modal-content {
        background: var(--bg-primary);
        border: none;
        border-radius: 20px;
        box-shadow: 10px 10px 30px var(--shadow-dark), -10px -10px 30px var(--shadow-light);
    }
    
    .modal-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .modal-title {
        color: var(--text-primary);
        font-weight: 600;
    }
    
    .btn-close {
        filter: none;
        opacity: 0.6;
    }
    
    .form-label {
        color: var(--text-secondary);
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 8px;
    }
    
    .form-control {
        background: var(--bg-primary);
        border: none;
        color: var(--text-primary);
        border-radius: 12px;
        padding: 12px;
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .form-control:focus {
        background: var(--bg-primary);
        border: none;
        color: var(--text-primary);
        box-shadow: inset 6px 6px 12px var(--shadow-dark), inset -6px -6px 12px var(--shadow-light);
        outline: none;
    }
    
    .form-control::placeholder {
        color: var(--text-secondary);
        opacity: 0.5;
    }
    
    .modal-footer {
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .btn-secondary {
        background: var(--bg-primary);
        border: none;
        color: var(--text-secondary);
        font-weight: 600;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        transform: translateY(-2px);
    }
    
    .btn-secondary:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        transform: translateY(0);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%);
        border: none;
        color: #fff;
        font-weight: 600;
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light), inset 0 1px 2px rgba(255, 255, 255, 0.3);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #2fa866 0%, var(--badge-green) 100%);
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light), inset 0 1px 2px rgba(255, 255, 255, 0.4);
    }
    
    .btn-primary:active {
        transform: translateY(0);
        box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2), inset -2px -2px 4px rgba(255, 255, 255, 0.1);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: var(--bg-primary);
        border-radius: 20px;
        box-shadow: inset 4px 4px 12px var(--shadow-dark), inset -4px -4px 12px var(--shadow-light);
        margin: 20px;
    }
    
    .empty-icon {
        font-size: 80px;
        color: var(--icon-color);
        opacity: 0.4;
        margin-bottom: 20px;
    }
    
    .empty-text {
        color: var(--text-secondary);
        font-size: 14px;
    }
    
    /* Alert Banner */
    .alert-banner {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        display: flex;
        gap: 15px;
        align-items: start;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        animation: slideIn 0.5s ease-out;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .alert-banner.alert-warning {
        border-left: 5px solid #ff9800;
    }
    
    .alert-banner.alert-danger {
        border-left: 5px solid #f44336;
    }
    
    .alert-banner.alert-info {
        border-left: 5px solid #2196F3;
    }
    
    .alert-icon {
        font-size: 32px;
        min-width: 40px;
    }
    
    .alert-content {
        flex: 1;
    }
    
    .alert-title {
        color: var(--text-primary);
        font-weight: 700;
        font-size: 16px;
        margin-bottom: 8px;
    }
    
    .alert-message {
        color: var(--text-secondary);
        font-size: 14px;
        line-height: 1.5;
    }
    
    .alert-note {
        margin-top: 10px;
        padding: 10px;
        background: rgba(0,0,0,0.05);
        border-radius: 10px;
        font-size: 13px;
        color: var(--text-secondary);
        display: flex;
        gap: 8px;
        align-items: start;
    }
    
    .alert-note i {
        margin-top: 2px;
    }
    
    /* Disabled State */
    .checklist-item.disabled {
        opacity: 0.4;
        pointer-events: none;
    }
    
    .checkbox-custom.disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
</style>

<div class="perawatan-container">
    <!-- Header -->
    <div class="page-header">
        <a href="{{ route('perawatan.karyawan.index') }}" class="back-button">
            <i class="ti ti-arrow-left"></i>
        </a>
        <div class="header-info">
            <h1 class="header-title">
                @if($tipe === 'harian') <i class="ti ti-sun"></i>
                @elseif($tipe === 'mingguan') <i class="ti ti-calendar-week"></i>
                @elseif($tipe === 'bulanan') <i class="ti ti-calendar-month"></i>
                @else <i class="ti ti-calendar-event"></i>
                @endif
                {{ ucfirst($tipe) }}
            </h1>
            <div class="header-subtitle">
                {{ now()->format('d F Y') }}
                @if($tipe === 'harian' && isset($jamKerja))
                    <br>
                    <span style="color: var(--badge-green); font-weight: 600;">
                        <i class="ti ti-clock"></i> Shift: {{ $jamKerja->nama_jam_kerja }} 
                        ({{ date('H:i', strtotime($jamKerja->jam_masuk)) }} - {{ date('H:i', strtotime($jamKerja->jam_pulang)) }})
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Banner Status Checklist -->
    @if($config)
        @if(!$config->is_enabled)
            <!-- NONAKTIF -->
            <div class="alert-banner alert-warning">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <div class="alert-title">Checklist {{ ucfirst($tipe) }} Sedang Dinonaktifkan</div>
                    <div class="alert-message">
                        Checklist tidak aktif untuk saat ini. Anda dapat absen pulang tanpa menyelesaikan checklist.
                    </div>
                    @if($config->keterangan)
                        <div class="alert-note">
                            <i class="ti ti-info-circle"></i>
                            <span>{{ $config->keterangan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @elseif($config->is_mandatory)
            <!-- AKTIF & WAJIB -->
            <div class="alert-banner alert-danger">
                <div class="alert-icon">⚠️</div>
                <div class="alert-content">
                    <div class="alert-title">Checklist {{ ucfirst($tipe) }} WAJIB Diselesaikan</div>
                    <div class="alert-message">
                        Anda HARUS menyelesaikan 100% checklist ini sebelum absen pulang.
                    </div>
                    @if($config->keterangan)
                        <div class="alert-note">
                            <i class="ti ti-info-circle"></i>
                            <span>{{ $config->keterangan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- AKTIF & OPSIONAL -->
            <div class="alert-banner alert-info">
                <div class="alert-icon">ℹ️</div>
                <div class="alert-content">
                    <div class="alert-title">Checklist {{ ucfirst($tipe) }} Opsional (Tidak Wajib)</div>
                    <div class="alert-message">
                        Checklist ini tidak wajib diselesaikan. Anda dapat absen pulang meskipun belum menyelesaikan.
                    </div>
                    @if($config->keterangan)
                        <div class="alert-note">
                            <i class="ti ti-info-circle"></i>
                            <span>{{ $config->keterangan }}</span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    <!-- Progress Card dengan Timeline Stepper -->
    <div class="progress-card">
        <div class="progress-header">
            <div class="progress-label">PROGRESS HARI INI</div>
            <div class="progress-count">{{ $completedChecklist }}/{{ $totalChecklist }}</div>
        </div>
        
        <!-- Timeline Stepper Horizontal -->
        <div class="timeline-stepper">
            <div class="stepper-item {{ $progress >= 20 ? 'active completed' : 'active' }}">
                <div class="stepper-circle">
                    @if($progress >= 20)
                        <i class="ti ti-check"></i>
                    @else
                        <i class="ti ti-flame"></i>
                    @endif
                </div>
                <div class="stepper-line {{ $progress >= 20 ? 'filled' : '' }}"></div>
                <div class="stepper-label">Mulai 🔥</div>
            </div>
            
            <div class="stepper-item {{ $progress >= 40 ? 'active completed' : ($progress > 20 ? 'active' : '') }}">
                <div class="stepper-circle">
                    @if($progress >= 40)
                        <i class="ti ti-check"></i>
                    @else
                        <i class="ti ti-rocket"></i>
                    @endif
                </div>
                <div class="stepper-line {{ $progress >= 40 ? 'filled' : '' }}"></div>
                <div class="stepper-label">Semangat 🚀</div>
            </div>
            
            <div class="stepper-item {{ $progress >= 60 ? 'active completed' : ($progress > 40 ? 'active' : '') }}">
                <div class="stepper-circle">
                    @if($progress >= 60)
                        <i class="ti ti-check"></i>
                    @else
                        <i class="ti ti-thumb-up"></i>
                    @endif
                </div>
                <div class="stepper-line {{ $progress >= 60 ? 'filled' : '' }}"></div>
                <div class="stepper-label">Hebat 👍</div>
            </div>
            
            <div class="stepper-item {{ $progress >= 80 ? 'active completed' : ($progress > 60 ? 'active' : '') }}">
                <div class="stepper-circle">
                    @if($progress >= 80)
                        <i class="ti ti-check"></i>
                    @else
                        <i class="ti ti-star"></i>
                    @endif
                </div>
                <div class="stepper-line {{ $progress >= 80 ? 'filled' : '' }}"></div>
                <div class="stepper-label">Hampir! ⭐</div>
            </div>
            
            <div class="stepper-item {{ $progress == 100 ? 'active completed' : ($progress > 80 ? 'active' : '') }}">
                <div class="stepper-circle">
                    @if($progress == 100)
                        <i class="ti ti-trophy"></i>
                    @else
                        <i class="ti ti-flag"></i>
                    @endif
                </div>
                <div class="stepper-label">Selesai! 🎉</div>
            </div>
        </div>
        
        <!-- Progress Bar dengan Animasi -->
        <div class="progress-bar-container">
            <div class="progress-bar-fill" style="width: {{ $progress }}%"></div>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div class="progress-percentage">{{ $progress }}%</div>
            <div class="progress-message">
                @if($progress >= 100)
                    <i class="ti ti-trophy"></i> <strong>SEMPURNA! 🎉</strong>
                @elseif($progress >= 75)
                    <i class="ti ti-star"></i> <strong>Luar Biasa! ⭐</strong>
                @elseif($progress >= 50)
                    <i class="ti ti-thumb-up"></i> <strong>Hebat! 👍</strong>
                @elseif($progress >= 25)
                    <i class="ti ti-rocket"></i> <strong>Semangat! 🚀</strong>
                @else
                    <i class="ti ti-flame"></i> <strong>Ayo Mulai! 🔥</strong>
                @endif
            </div>
        </div>
    </div>

    <!-- Filter Jam Kerja (Shift) -->
    @php
        // Get unique jam kerja dari checklist
        $jamKerjaOptions = [];
        if(isset($checklistsByRuangan) && !empty($checklistsByRuangan)) {
            foreach($checklistsByRuangan as $ruang) {
                foreach($ruang['items'] as $item) {
                    if($item->kode_jam_kerja) {
                        $jamKerjaOptions[$item->kode_jam_kerja] = $item->jamKerja?->nama_jam_kerja ?? 'Unknown';
                    }
                }
            }
        }
    @endphp
    
    @if(!empty($jamKerjaOptions))
    <div class="filter-container" style="margin-bottom: 15px;">
        <div style="color: var(--text-secondary); font-size: 11px; font-weight: 700; padding: 0 20px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">
            <i class="ti ti-clock"></i> Jam Kerja
        </div>
        <div style="display: flex; gap: 10px; overflow-x: auto; padding: 0 20px; padding-bottom: 10px; scrollbar-width: thin;">
            <button class="filter-btn jam-kerja-btn active" data-jam-kerja="all" style="flex-shrink: 0;">Semua</button>
            @foreach($jamKerjaOptions as $kode => $nama)
                <button class="filter-btn jam-kerja-btn" data-jam-kerja="{{ $kode }}" style="flex-shrink: 0;">
                    ⏰ {{ $nama }}
                </button>
            @endforeach
        </div>
    </div>
    @endif

    <!-- CARD VIEW: Ruangan Grid -->
    <div id="ruanganGridView" style="padding: 0 20px; margin-bottom: 25px;">
        <div style="color: var(--text-secondary); font-size: 11px; font-weight: 700; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px;">
            <i class="ti ti-layout-grid"></i> Pilih Ruangan
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px;">
            @forelse($checklistsByRuangan as $ruanganGroup)
                @php
                    $progressPercent = $ruanganGroup['total'] > 0 ? round(($ruanganGroup['completed'] / $ruanganGroup['total']) * 100) : 0;
                    $ruanganId = $ruanganGroup['ruangan_id'];
                @endphp
                <div class="ruangan-card" data-ruangan-id="{{ $ruanganId }}" style="cursor: pointer; background: var(--bg-primary); border-radius: 15px; padding: 20px; text-align: center; box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light); transition: all 0.3s ease; border: 2px solid transparent;">
                    <div style="font-size: 36px; margin-bottom: 10px;">
                        @if(stripos($ruanganGroup['ruangan_nama'], 'RTM') !== false)
                            👥
                        @elseif(stripos($ruanganGroup['ruangan_nama'], 'ICU') !== false)
                            🏥
                        @elseif(stripos($ruanganGroup['ruangan_nama'], 'IGD') !== false)
                            🚑
                        @elseif(stripos($ruanganGroup['ruangan_nama'], 'Kamar') !== false)
                            🛏️
                        @else
                            🏢
                        @endif
                    </div>
                    <h5 style="margin: 0 0 8px 0; color: var(--text-primary); font-weight: bold; font-size: 14px;">
                        {{ $ruanganGroup['ruangan_nama'] }}
                    </h5>
                    <div style="background: linear-gradient(135deg, var(--badge-green) 0%, #52c77a 100%); color: white; padding: 6px 12px; border-radius: 12px; font-size: 18px; font-weight: bold; margin-bottom: 10px;">
                        {{ $progressPercent }}%
                    </div>
                    <small style="color: var(--text-secondary); display: block;">
                        {{ $ruanganGroup['completed'] }}/{{ $ruanganGroup['total'] }} selesai
                    </small>
                    @if($progressPercent == 100)
                        <div style="margin-top: 8px; color: var(--badge-green); font-weight: bold;">
                            <i class="ti ti-check-circle"></i> SELESAI
                        </div>
                    @endif
                </div>
            @empty
                <div style="color: var(--text-secondary); padding: 20px; text-align: center;">
                    Tidak ada ruangan
                </div>
            @endforelse
        </div>
    </div>

    <!-- Toggle View Buttons -->
    <div style="display: flex; gap: 10px; padding: 0 20px; margin-bottom: 20px; justify-content: center;">
        <button id="toggleGridView" class="btn" style="background: var(--bg-primary); color: var(--badge-green); border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: bold; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light); font-size: 12px;">
            <i class="ti ti-layout-grid"></i> Grid View
        </button>
        <button id="toggleListView" class="btn" style="background: var(--bg-primary); color: var(--text-secondary); border: none; padding: 8px 16px; border-radius: 8px; cursor: pointer; font-weight: bold; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light); font-size: 12px;">
            <i class="ti ti-list"></i> List View
        </button>
    </div>

    <!-- Filter Kategori -->
    <div class="filter-container" id="filterCategoryContainer" style="display: none;">
        <div style="color: var(--text-secondary); font-size: 11px; font-weight: 700; padding: 0 20px; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 1px;">
            <i class="ti ti-filter"></i> Kategori
        </div>
        <div style="display: flex; gap: 10px; overflow-x: auto; padding: 0 20px; padding-bottom: 10px; scrollbar-width: thin;">
            <button class="filter-btn kategori-btn active" data-kategori="all" style="flex-shrink: 0;">Semua</button>
            <button class="filter-btn kategori-btn" data-kategori="kebersihan" style="flex-shrink: 0;"><i class="ti ti-wash"></i> Kebersihan</button>
            <button class="filter-btn kategori-btn" data-kategori="perawatan_rutin" style="flex-shrink: 0;"><i class="ti ti-tool"></i> Perawatan</button>
            <button class="filter-btn kategori-btn" data-kategori="pengecekan" style="flex-shrink: 0;"><i class="ti ti-search"></i> Pengecekan</button>
            <button class="filter-btn kategori-btn" data-kategori="lainnya" style="flex-shrink: 0;"><i class="ti ti-list"></i> Lainnya</button>
        </div>
    </div>

    <!-- DETAIL VIEW: Task List (Hidden by default) -->
    <div id="detailListView" style="display: none; padding: 0 20px; margin-bottom: 20px;">
        <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
            <button id="backToGridBtn" class="btn" style="background: var(--bg-primary); color: var(--text-secondary); border: none; width: 45px; height: 45px; border-radius: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center; box-shadow: 4px 4px 8px var(--shadow-dark), -4px -4px 8px var(--shadow-light);">
                <i class="ti ti-arrow-left"></i>
            </button>
            <div style="flex: 1;">
                <h3 id="selectedRuanganTitle" style="margin: 0; color: var(--text-primary); font-weight: bold;">Ruangan</h3>
                <small id="selectedRuanganDesc" style="color: var(--text-secondary);">0/0 selesai</small>
            </div>
        </div>
    </div>

    <!-- Checklist Items by Ruangan -->
    @if(isset($checklistsByRuangan) && !empty($checklistsByRuangan))
        @forelse($checklistsByRuangan as $ruanganGroup)
        <div class="ruangan-detail-section" data-ruangan-id="{{ $ruanganGroup['ruangan_id'] }}" style="display: none; margin-bottom: 25px; padding: 0 20px;">
            <!-- Ruangan Header -->
            <div style="background: var(--bg-primary); padding: 15px 20px; border-radius: 15px; margin-bottom: 15px; box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);">
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; color: var(--text-primary); font-weight: bold;">
                            <i class="ti ti-building"></i> {{ $ruanganGroup['ruangan_nama'] }}
                        </h4>
                        <small style="color: var(--text-secondary);">
                            {{ $ruanganGroup['completed'] }}/{{ $ruanganGroup['total'] }} selesai
                        </small>
                    </div>
                    <div style="text-align: right;">
                        <strong style="font-size: 20px; color: var(--badge-green);">
                            {{ $ruanganGroup['total'] > 0 ? round(($ruanganGroup['completed'] / $ruanganGroup['total']) * 100) : 0 }}%
                        </strong>
                    </div>
                </div>
            </div>

            <!-- Items untuk ruangan ini -->
            @forelse($ruanganGroup['items'] as $checklist)
            @php
                $isChecked = $checklist->logs->where('status', 'completed')->count() > 0;
                $log = $checklist->logs->first();
                $isDisabled = !$config || !$config->is_enabled;
            @endphp
            
            <div class="checklist-item {{ $isChecked ? 'completed' : '' }} {{ $isDisabled ? 'disabled' : '' }}" 
                 data-kategori="{{ $checklist->kategori }}"
                 data-jam-kerja="{{ $checklist->kode_jam_kerja ?? 'all' }}">
                <div style="display: flex; align-items: start;">
                    <div class="checkbox-custom {{ $isChecked ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }}" 
                         data-id="{{ $checklist->id }}"
                         data-checked="{{ $isChecked ? 'true' : 'false' }}"
                         data-disabled="{{ $isDisabled ? 'true' : 'false' }}">
                        @if($isChecked)
                            <i class="ti ti-check"></i>
                        @endif
                    </div>
                    
                    <div class="checklist-content">
                        <div class="checklist-title">
                            @if($tipe === 'harian' && $checklist->jam_mulai && $checklist->jam_selesai)
                                <span class="jam-badge">
                                    <i class="ti ti-clock"></i> 
                                    {{ date('H:i', strtotime($checklist->jam_mulai)) }} - {{ date('H:i', strtotime($checklist->jam_selesai)) }}
                                </span>
                            @endif
                            {{ $checklist->nama_kegiatan }}
                        </div>
                        
                        @if($checklist->deskripsi)
                        <div class="checklist-desc">{{ $checklist->deskripsi }}</div>
                        @endif
                        
                        @if($checklist->point_description)
                        <div class="checklist-desc" style="font-style: italic; color: #7c8db0; margin-top: 6px;">
                            <i class="ti ti-info-circle" style="margin-right: 4px;"></i>{{ $checklist->point_description }}
                        </div>
                        @endif
                        
                        <div style="margin-top: 8px; display: flex; gap: 10px; flex-wrap: wrap; align-items: center;">
                            @php
                                $kategoriBadge = [
                                    'kebersihan' => ['icon' => 'wash', 'text' => 'Kebersihan'],
                                    'perawatan_rutin' => ['icon' => 'tool', 'text' => 'Perawatan Rutin'],
                                    'pengecekan' => ['icon' => 'search', 'text' => 'Pengecekan'],
                                    'lainnya' => ['icon' => 'list', 'text' => 'Lainnya']
                                ];
                                $badge = $kategoriBadge[$checklist->kategori] ?? ['icon' => 'list', 'text' => 'Lainnya'];
                            @endphp
                            
                            <!-- Jam Kerja Badge (jika ada) -->
                            @if($checklist->kode_jam_kerja && $checklist->jamKerja)
                                <span class="jam-kerja-badge" style="background: linear-gradient(135deg, #2196f3 0%, #1976d2 100%); color: white; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                    <i class="ti ti-clock"></i> {{ $checklist->jamKerja->nama_jam_kerja }}
                                </span>
                            @else
                                <span class="jam-kerja-badge" style="background: rgba(156, 39, 176, 0.1); color: #6a1b9a; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; border: 1px solid #6a1b9a; display: flex; align-items: center; gap: 4px;">
                                    <i class="ti ti-users"></i> Semua Shift
                                </span>
                            @endif
                            
                            <span class="kategori-badge">
                                <i class="ti ti-{{ $badge['icon'] }}"></i> {{ $badge['text'] }}
                            </span>
                            @if($checklist->points)
                                @php
                                    $pointColor = $checklist->points <= 3 ? '#52c77a' : ($checklist->points <= 7 ? '#ffa500' : '#ff6b6b');
                                @endphp
                                <span class="badge" style="background: {{ $pointColor }}; color: white; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600;">
                                    ⭐ {{ $checklist->points }} pts
                                </span>
                            @endif
                            @if($isChecked && $log)
                                <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                    <i class="ti ti-user"></i> {{ $log->nama_karyawan ?? $log->user->name ?? 'Karyawan' }}
                                </span>
                                <span class="badge" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 6px 12px; border-radius: 15px; font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                                    <i class="ti ti-clock"></i> {{ \Carbon\Carbon::parse($log->waktu_eksekusi)->format('H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                @if($isChecked && $log)
                    @if($log->catatan)
                    <div class="note-section">
                        <div class="note-text">
                            <i class="ti ti-note"></i> {{ $log->catatan }}
                        </div>
                    </div>
                    @endif
                    
                    @if($log->foto_bukti)
                    <img src="{{ asset('storage/perawatan/' . $log->foto_bukti) }}" 
                         class="foto-preview" 
                         alt="Foto Bukti">
                    @endif
                    
                    <button class="btn btn-uncheck" 
                            data-id="{{ $checklist->id }}">
                        <i class="ti ti-x"></i> Batalkan Checklist
                    </button>
                @endif
            </div>
            @empty
                <div class="empty-state">
                    <div class="empty-icon"><i class="ti ti-clipboard-off"></i></div>
                    <div class="empty-text">Tidak ada checklist {{ $tipe }} untuk ruangan ini</div>
                </div>
            @endforelse
        </div>
        @endforelse
    @endif
</div>

<!-- Modal untuk Input Catatan dan Foto -->
<div class="modal fade" id="modalChecklist" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="ti ti-circle-check"></i> Selesaikan Checklist</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formChecklist" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="masterPerawatanId" name="master_perawatan_id">
                    <input type="hidden" name="periode_key" value="{{ $periodeKey }}">
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-note"></i> Catatan (Opsional)</label>
                        <textarea class="form-control" name="catatan" rows="3" 
                                  placeholder="Tambahkan catatan jika ada..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label"><i class="ti ti-camera"></i> Foto Bukti <span style="color: #f44336;">*</span></label>
                        <input type="file" class="form-control" name="foto_bukti" accept="image/*" capture="environment" required>
                        <small style="color: #666; font-size: 11px; margin-top: 5px; display: block;">
                            Format: JPG, PNG. Max: 10MB. <strong style="color: #f44336;">Wajib diisi!</strong>
                        </small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnSubmitChecklist">
                    <i class="ti ti-check"></i> Selesai
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Batalkan -->
<div class="modal fade" id="modalKonfirmasi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                <h5 class="modal-title" style="color: var(--text-primary); font-weight: 600;">
                    <i class="ti ti-alert-circle" style="color: #ff9800;"></i> Konfirmasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" style="padding: 25px;">
                <p style="color: var(--text-primary); font-size: 15px; margin: 0;">
                    Yakin ingin membatalkan checklist ini?
                </p>
            </div>
            <div class="modal-footer" style="border-top: 1px solid rgba(0, 0, 0, 0.05);">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnKonfirmasiUncheck" style="background: linear-gradient(135deg, #f44336 0%, #e53935 100%);">
                    <i class="ti ti-check"></i> Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<div style="height: 100px;"></div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentJamKerja = 'all';
    let currentKategori = 'all';
    
    // Filter Jam Kerja
    $('.jam-kerja-btn').on('click', function() {
        $('.jam-kerja-btn').removeClass('active');
        $(this).addClass('active');
        currentJamKerja = $(this).data('jam-kerja');
        applyFilters();
    });
    
    // Filter Kategori
    $('.kategori-btn').on('click', function() {
        $('.kategori-btn').removeClass('active');
        $(this).addClass('active');
        currentKategori = $(this).data('kategori');
        applyFilters();
    });
    
    // Apply both filters
    function applyFilters() {
        $('.checklist-item').each(function() {
            const itemJamKerja = $(this).data('jam-kerja');
            const itemKategori = $(this).data('kategori');
            
            let showJamKerja = currentJamKerja === 'all' || currentJamKerja === itemJamKerja;
            let showKategori = currentKategori === 'all' || currentKategori === itemKategori;
            
            if (showJamKerja && showKategori) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    }
    
    // Klik Checkbox - PERBAIKI MODAL
    $('.checkbox-custom').on('click', function() {
        // Cek apakah disabled
        const isDisabled = $(this).data('disabled') === 'true' || $(this).data('disabled') === true;
        if (isDisabled || $(this).hasClass('disabled')) {
            // Tampilkan alert dengan SweetAlert2 (jika ada) atau modal bootstrap
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Checklist Nonaktif',
                    text: 'Checklist sedang dinonaktifkan. Tidak dapat melakukan perubahan.',
                    confirmButtonColor: '#26a69a'
                });
            } else {
                alert('Checklist sedang dinonaktifkan. Tidak dapat melakukan perubahan.');
            }
            return false;
        }
        
        const isChecked = $(this).data('checked') === 'true' || $(this).data('checked') === true;
        const id = $(this).data('id');
        
        if (isChecked) {
            return;
        }
        
        $('#masterPerawatanId').val(id);
        $('#modalChecklist').modal('show');
    });
    
    // Submit Checklist - LEBIH CEPAT
    $('#btnSubmitChecklist').on('click', function() {
        const $btn = $(this);
        const form = $('#formChecklist')[0];
        
        // Validasi foto wajib
        const fotoInput = $('input[name="foto_bukti"]')[0];
        if (!fotoInput.files || fotoInput.files.length === 0) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Foto Wajib!',
                    text: 'Silakan upload foto bukti terlebih dahulu.',
                    confirmButtonColor: '#26a69a'
                });
            } else {
                alert('Foto bukti wajib diupload!');
            }
            return;
        }
        
        const formData = new FormData(form);
        
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Menyimpan...');
        
        $.ajax({
            url: '{{ route("perawatan.karyawan.execute") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    $('#modalChecklist').modal('hide');
                    location.reload();
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                
                // Tampilkan error dengan modal
                const modalError = `
                    <div class="modal fade" id="modalError" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                                    <h5 class="modal-title" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="ti ti-alert-triangle" style="color: #f44336;"></i> Error
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" style="padding: 25px;">
                                    <p style="color: var(--text-primary); margin: 0;">${errorMsg}</p>
                                </div>
                                <div class="modal-footer" style="border-top: 1px solid rgba(0, 0, 0, 0.05);">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modalError);
                $('#modalError').modal('show');
                $('#modalError').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
                
                $btn.prop('disabled', false).html('<i class="ti ti-check"></i> Selesai');
            }
        });
    });
    
    // Uncheck/Batalkan
    let uncheckId = null;
    
    $('.btn-uncheck').on('click', function(e) {
        e.preventDefault();
        console.log('Uncheck clicked');
        
        // Cek apakah parent checklist-item disabled
        if ($(this).closest('.checklist-item').hasClass('disabled')) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Checklist Nonaktif',
                    text: 'Checklist sedang dinonaktifkan. Tidak dapat melakukan perubahan.',
                    confirmButtonColor: '#26a69a'
                });
            } else {
                alert('Checklist sedang dinonaktifkan. Tidak dapat melakukan perubahan.');
            }
            return false;
        }
        
        uncheckId = $(this).data('id');
        $('#modalKonfirmasi').modal('show');
    });
    
    // Konfirmasi Uncheck
    $('#btnKonfirmasiUncheck').on('click', function() {
        if (!uncheckId) return;
        
        $('#modalKonfirmasi').modal('hide');
        
        $.ajax({
            url: '{{ route("perawatan.karyawan.uncheck") }}',
            type: 'POST',
            data: {
                master_perawatan_id: uncheckId,
                periode_key: '{{ $periodeKey }}'
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                console.log('Uncheck success:', response);
                if (response.success) {
                    location.reload();
                }
            },
            error: function(xhr) {
                console.log('Uncheck error:', xhr);
                
                // Tampilkan error dengan modal
                const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan!';
                const modalError = `
                    <div class="modal fade" id="modalError" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header" style="border-bottom: 1px solid rgba(0, 0, 0, 0.05);">
                                    <h5 class="modal-title" style="color: var(--text-primary); font-weight: 600;">
                                        <i class="ti ti-alert-triangle" style="color: #f44336;"></i> Error
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body" style="padding: 25px;">
                                    <p style="color: var(--text-primary); margin: 0;">${errorMsg}</p>
                                </div>
                                <div class="modal-footer" style="border-top: 1px solid rgba(0, 0, 0, 0.05);">
                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                $('body').append(modalError);
                $('#modalError').modal('show');
                $('#modalError').on('hidden.bs.modal', function() {
                    $(this).remove();
                });
            }
        });
    });

    // =====================
    // RUANGAN CARD NAVIGATION
    // =====================
    
    // Click on ruangan card to show details
    $('.ruangan-card').on('click', function() {
        const ruanganId = $(this).data('ruangan-id');
        const ruanganTitle = $(this).find('h5').text();
        const completed = $(this).find('small').text();
        
        // Hide grid view
        $('#ruanganGridView').fadeOut(300);
        $('#filterCategoryContainer').fadeIn(300);
        
        // Show detail view
        $('#detailListView').fadeIn(300);
        $('#selectedRuanganTitle').text(ruanganTitle);
        $('#selectedRuanganDesc').text(completed);
        
        // Show task list for selected ruangan
        $('.ruangan-detail-section').fadeOut(300);
        $(`.ruangan-detail-section[data-ruangan-id="${ruanganId}"]`).delay(300).fadeIn(300);
        
        // Scroll to top
        $('html, body').animate({ scrollTop: 0 }, 500);
    });
    
    // Back button to return to grid
    $('#backToGridBtn').on('click', function() {
        // Hide detail view
        $('#detailListView').fadeOut(300);
        $('#filterCategoryContainer').fadeOut(300);
        
        // Hide all task lists
        $('.ruangan-detail-section').fadeOut(300);
        
        // Show grid view
        $('#ruanganGridView').delay(300).fadeIn(300);
        
        // Scroll to top
        $('html, body').animate({ scrollTop: 0 }, 500);
    });
    
    // Toggle button handlers
    $('#toggleGridView').on('click', function() {
        $(this).css('color', 'var(--badge-green)');
        $('#toggleListView').css('color', 'var(--text-secondary)');
        
        $('#detailListView').fadeOut(300);
        $('#filterCategoryContainer').fadeOut(300);
        $('.ruangan-detail-section').fadeOut(300);
        $('#ruanganGridView').delay(300).fadeIn(300);
    });
    
    $('#toggleListView').on('click', function() {
        if ($('.ruangan-detail-section:visible').length > 0) {
            // Already in list view
            $(this).css('color', 'var(--badge-green)');
            $('#toggleGridView').css('color', 'var(--text-secondary)');
        } else {
            alert('Pilih ruangan terlebih dahulu');
        }
    });
    
    // Hover effect on ruangan card
    $('.ruangan-card').on('mouseenter', function() {
        $(this).css({
            'transform': 'translateY(-8px)',
            'border-color': 'var(--badge-green)',
            'box-shadow': '8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light), 0 0 20px rgba(76, 175, 80, 0.3)'
        });
    }).on('mouseleave', function() {
        $(this).css({
            'transform': 'translateY(0)',
            'border-color': 'transparent',
            'box-shadow': '6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light)'
        });
    });
});
</script>
@endpush
