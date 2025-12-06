@extends('layouts.mobile.app')
@section('content')
<style>
    /* ========== DARK MODE VARIABLES ========== */
    :root {
        --bg-primary: #e0e5ec;
        --bg-secondary: #e0e5ec;
        --shadow-dark: #a3b1c6;
        --shadow-light: #ffffff;
        --text-primary: #2d3748;
        --text-secondary: #718096;
        --text-tertiary: #a0aec0;
        --border-color: rgba(163, 177, 198, 0.1);
    }

    [data-theme="dark"] {
        --bg-primary: #1a202c;
        --bg-secondary: #2d3748;
        --shadow-dark: #0d1117;
        --shadow-light: #2d3748;
        --text-primary: #e2e8f0;
        --text-secondary: #cbd5e0;
        --text-tertiary: #a0aec0;
        --border-color: rgba(255, 255, 255, 0.1);
    }

    body {
        background: var(--bg-primary);
        min-height: 100vh;
        transition: background 0.3s ease;
    }
    
    /* Signature Pad - EXACT DARI ADMIN */
    .signature-pad {
        border: 2px solid #ddd;
        border-radius: 10px;
        background-color: white;
        cursor: crosshair;
        display: block;
        width: 100%;
        height: 200px;
        touch-action: none;
    }

    [data-theme="dark"] .signature-pad {
        background-color: #2d3748;
        border-color: #4a5568;
    }

    /* ========== HEADER ========== */
    #header-section {
        height: auto;
        padding: 25px 20px;
        position: relative;
        background: var(--bg-primary);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    #section-back {
        position: absolute;
        left: 15px;
        top: 15px;
    }

    .back-btn {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        background: var(--bg-primary);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
        font-size: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
        transition: all 0.3s ease;
    }

    .back-btn:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    #header-title {
        text-align: center;
        color: var(--text-primary);
        margin-top: 10px;
    }

    #header-title h3 {
        font-weight: 700;
        margin: 0;
        font-size: 1.6rem;
        color: var(--text-primary);
    }

    #header-title p {
        margin: 5px 0 0 0;
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* ========== DARK MODE TOGGLE ========== */
    .theme-toggle {
        position: fixed;
        right: 20px;
        top: 400px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--bg-primary);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .theme-toggle:hover {
        transform: scale(1.1);
    }

    .theme-toggle:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .theme-toggle ion-icon {
        font-size: 26px;
        color: var(--text-primary);
        transition: all 0.3s ease;
    }
    
    /* Section Subtitle */
    .section-subtitle {
        padding: 15px 20px 10px 20px;
        color: #2d3748;
        font-size: 1.05rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .section-subtitle ion-icon {
        font-size: 1.4rem;
        color: #667eea;
    }
    
    /* Horizontal Scroll Container - Story Style with Neomorphic Cards */
    .kendaraan-scroll-container {
        display: flex;
        overflow-x: auto;
        padding: 0 20px 25px 20px;
        gap: 20px;
        scroll-snap-type: x mandatory;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #667eea #e0e0e0;
    }
    
    /* Custom Scrollbar for WebKit Browsers */
    .kendaraan-scroll-container::-webkit-scrollbar {
        height: 8px;
        display: block;
    }
    
    .kendaraan-scroll-container::-webkit-scrollbar-track {
        background: #e0e0e0;
        border-radius: 10px;
        margin: 0 20px;
    }
    
    .kendaraan-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .kendaraan-scroll-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(90deg, #764ba2 0%, #667eea 100%);
    }
    
    .kendaraan-scroll-container::-webkit-scrollbar-thumb:active {
        background: #667eea;
    }
    
    /* Card Story Style - Updated Neomorphic Design */
    .kendaraan-story-card {
        flex: 0 0 320px;
        width: 320px;
        height: 480px;
        scroll-snap-align: center;
        border-radius: 25px;
        background: #e0e5ec;
        box-shadow: 10px 10px 20px #a3b1c6,
                   -10px -10px 20px #ffffff;
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
        position: relative;
        display: flex;
        flex-direction: column;
        padding: 18px;
        gap: 15px;
    }
    
    .kendaraan-story-card.selected {
        flex: 0 0 340px;
        width: 340px;
        height: 500px;
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.4),
                   inset 0 0 0 3px rgba(102, 126, 234, 0.3);
        transform: scale(1.05);
    }
    
    .kendaraan-story-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 50px rgba(102, 126, 234, 0.3);
    }
    
    /* Card Header */
    .card-header-neo {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
        z-index: 2;
    }
    
    .card-title-section-neo {
        flex: 1;
    }
    
    .card-title-neo {
        font-size: 1.15rem;
        font-weight: 900;
        color: #2d3748;
        margin: 0 0 4px 0;
        line-height: 1.2;
        text-transform: uppercase;
    }
    
    .card-code-neo {
        font-size: 0.75rem;
        color: #718096;
        font-family: 'Courier New', monospace;
        font-weight: 700;
        margin: 0;
    }
    
    .card-badge-neo {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: white;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .card-badge-neo.tersedia {
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
    }
    
    .card-badge-neo.keluar {
        background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%);
    }
    
    .card-badge-neo.dipinjam {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
    }
    
    .card-badge-neo.service {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
    }
    
    /* Photo Section */
    .card-photo-section-neo {
        width: 100%;
        height: 180px;
        border-radius: 18px;
        overflow: hidden;
        position: relative;
        box-shadow: inset 3px 3px 8px #a3b1c6,
                   inset -3px -3px 8px #ffffff;
    }
    
    .card-photo-neo {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .card-photo-placeholder-neo {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 70px;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Stats Section */
    .card-stats-section-neo {
        display: flex;
        flex-direction: column;
        gap: 10px;
        flex: 1;
    }
    
    .stat-item-neo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        background: #e0e5ec;
        border-radius: 15px;
        box-shadow: inset 2px 2px 5px #a3b1c6,
                   inset -2px -2px 5px #ffffff;
    }
    
    .stat-icon-wrapper-neo {
        width: 36px;
        height: 36px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 18px;
        flex-shrink: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-content-neo {
        display: flex;
        flex-direction: column;
        flex: 1;
        min-width: 0;
    }
    
    .stat-label-neo {
        font-size: 0.7rem;
        color: var(--text-secondary);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-value-neo {
        font-size: 0.9rem;
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.2;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    /* Card Footer */
    .card-footer-neo {
        margin-top: auto;
    }
    
    .btn-view-detail-neo {
        width: 100%;
        padding: 14px;
        border-radius: 18px;
        border: none;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        text-decoration: none;
        color: white;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff;
    }
    
    .btn-view-detail-neo:active {
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -4px -4px 8px rgba(255,255,255,0.1);
    }
    
    .btn-view-detail-neo ion-icon {
        font-size: 20px;
    }
    
    /* Remove old styles */
    .card-bg-image,
    .card-gradient-overlay,
    .card-story-content,
    .status-badge-top,
    .card-bottom-info,
    .card-title-story,
    .card-subtitle-story,
    .card-details-story {
        display: none !important;
    }
    
    /* Modern Card Style with Photo */
    .kendaraan-story-card {
        min-width: 280px;
        max-width: 280px;
        height: 420px;
        border-radius: 25px;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        scroll-snap-align: center;
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2), 0 8px 20px rgba(0, 0, 0, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .kendaraan-story-card:hover {
        transform: translateY(-10px) scale(1.03);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3), 0 12px 30px rgba(0, 0, 0, 0.2);
    }
    
    .kendaraan-story-card:active {
        transform: translateY(-5px) scale(1.01);
    }
    
    /* Selected Card Border */
    .kendaraan-story-card.selected {
        border: 4px solid #667eea;
        box-shadow: 0 20px 60px rgba(102, 126, 234, 0.4), 0 10px 25px rgba(102, 126, 234, 0.3);
    }
    
    /* Background Image */
    .card-bg-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        filter: brightness(1.1) contrast(1.2) saturate(1.3);
        transition: all 0.5s ease;
    }
    
    .kendaraan-story-card:hover .card-bg-image {
        transform: scale(1.08);
        filter: brightness(1.15) contrast(1.25) saturate(1.35);
    }
    
    /* Gradient Overlay */
    .card-gradient-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(180deg, 
            rgba(0, 0, 0, 0.1) 0%, 
            rgba(0, 0, 0, 0.3) 50%,
            rgba(0, 0, 0, 0.8) 100%);
    }
    
    /* Card Content */
    .card-story-content {
        position: relative;
        z-index: 2;
        padding: 20px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: white;
    }
    
    /* Status Badge */
    .status-badge-top {
        display: inline-block;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(15px);
        padding: 8px 18px;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 900;
        letter-spacing: 1px;
        border: 1.5px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        text-transform: uppercase;
        align-self: flex-start;
    }
    
    .status-badge-top.tersedia {
        background: rgba(34, 197, 94, 0.3);
        border-color: rgba(34, 197, 94, 0.6);
        color: #fff;
    }
    
    .status-badge-top.dipinjam {
        background: rgba(59, 130, 246, 0.3);
        border-color: rgba(59, 130, 246, 0.6);
        color: #fff;
    }
    
    .status-badge-top.keluar {
        background: rgba(14, 165, 233, 0.3);
        border-color: rgba(14, 165, 233, 0.6);
        color: #fff;
    }
    
    .status-badge-top.service {
        background: rgba(239, 68, 68, 0.3);
        border-color: rgba(239, 68, 68, 0.6);
        color: #fff;
    }
    
    /* Card Bottom Info */
    .card-bottom-info {
        text-align: center;
    }
    
    .card-title-story {
        font-size: 1.4rem;
        font-weight: 900;
        margin-bottom: 8px;
        line-height: 1.2;
        text-shadow: 0 3px 10px rgba(0, 0, 0, 0.8), 0 6px 20px rgba(0, 0, 0, 0.5);
        letter-spacing: -0.5px;
    }
    
    .card-subtitle-story {
        font-size: 0.85rem;
        opacity: 0.95;
        margin-bottom: 12px;
        font-weight: 600;
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
    }
    
    .card-details-story {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(10px);
        padding: 10px;
        border-radius: 15px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.75rem;
        line-height: 1.6;
    }
    
    .card-details-story strong {
        font-weight: 700;
        opacity: 0.9;
    }
    
    /* Navigation Arrows for Scroll */
    .scroll-nav-container {
        position: relative;
        padding: 0 15px;
    }
    
    .scroll-arrow {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        z-index: 1000;
        background: #e0e5ec;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff;
        user-select: none;
        border: none;
    }
    
    .scroll-arrow:hover {
        box-shadow: 4px 4px 8px #a3b1c6,
                   -4px -4px 8px #ffffff;
        transform: translateY(-50%) scale(1.05);
    }
    
    .scroll-arrow:active {
        box-shadow: inset 4px 4px 8px #a3b1c6,
                   inset -4px -4px 8px #ffffff;
        transform: translateY(-50%) scale(0.95);
    }
    
    .scroll-arrow ion-icon {
        font-size: 24px;
        color: #667eea;
        transition: color 0.3s ease;
        pointer-events: none;
    }
    
    .scroll-arrow:hover ion-icon {
        color: #764ba2;
    }
    
    .scroll-arrow-left {
        left: 5px;
    }
    
    .scroll-arrow-right {
        right: 5px;
    }
    
    /* Hide arrows on very small screens */
    @media (max-width: 400px) {
        .scroll-arrow {
            width: 35px;
            height: 35px;
        }
        .scroll-arrow ion-icon {
            font-size: 20px;
        }
    }
    
    /* Ilustrasi Kendaraan Berjalan - Inline di samping tombol */
    .vehicle-animation-inline {
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 3px 10px rgba(0,0,0,0.15);
        display: none;
    }
    
    .vehicle-animation-inline.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .vehicle-moving-inline {
        position: absolute;
        top: 50%;
        transform: translateY(-50%) scaleX(-1);
        left: -50px;
        font-size: 32px;
        animation: moveVehicleInline 3s linear infinite;
        filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));
    }
    
    @keyframes moveVehicleInline {
        0% {
            left: -50px;
        }
        100% {
            left: 100%;
        }
    }
    
    .road-line-inline {
        position: absolute;
        top: 50%;
        width: 60px;
        height: 2px;
        background: rgba(255,255,255,0.5);
        animation: moveLineInline 1s linear infinite;
    }
    
    @keyframes moveLineInline {
        0% {
            left: 100%;
        }
        100% {
            left: -60px;
        }
    }
    
    .vehicle-status-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 12px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        z-index: 1;
        text-align: center;
    }

    /* ========== TABS NEOMORPHIC ========== */
    .nav-tabs {
        border: none;
        background: #e0e5ec;
        padding: 8px;
        border-radius: 20px;
        box-shadow: inset 4px 4px 8px #a3b1c6,
                   inset -4px -4px 8px #ffffff;
        display: flex;
        gap: 8px;
        margin: 0 15px 20px 15px;
    }

    .nav-tabs .nav-item {
        flex: 1;
    }

    .nav-tabs .nav-link {
        border: none;
        border-radius: 15px;
        padding: 12px 16px;
        font-weight: 700;
        font-size: 0.85rem;
        color: #718096;
        background: transparent;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
    }

    .nav-tabs .nav-link i,
    .nav-tabs .nav-link ion-icon {
        font-size: 1.1rem;
    }

    .nav-tabs .nav-link:hover {
        color: #667eea;
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff;
    }

    /* Responsive tabs */
    @media (max-width: 768px) {
        .nav-tabs .nav-link {
            font-size: 0.75rem;
            padding: 10px 12px;
            gap: 4px;
        }

        .nav-tabs .nav-link i,
        .nav-tabs .nav-link ion-icon {
            font-size: 1rem;
        }
    }

    @media (max-width: 480px) {
        .nav-tabs {
            flex-direction: column;
        }

        .nav-tabs .nav-link {
            font-size: 0.85rem;
            padding: 12px 16px;
        }
    }

    /* ========== ACTION CARDS NEOMORPHIC ========== */
    .card.bg-primary,
    .card.bg-success,
    .card.bg-warning,
    .card.bg-danger,
    .card.bg-info {
        background: #e0e5ec !important;
        border: none;
        box-shadow: 8px 8px 16px #a3b1c6,
                   -8px -8px 16px #ffffff;
        transition: all 0.3s ease;
    }

    .card.bg-primary .card-body,
    .card.bg-success .card-body,
    .card.bg-warning .card-body,
    .card.bg-danger .card-body,
    .card.bg-info .card-body {
        color: #2d3748 !important;
    }

    .card.bg-primary ion-icon {
        color: #667eea !important;
        filter: drop-shadow(0 2px 4px rgba(102, 126, 234, 0.3));
    }

    .card.bg-success ion-icon {
        color: #10b981 !important;
        filter: drop-shadow(0 2px 4px rgba(16, 185, 129, 0.3));
    }

    .card.bg-warning ion-icon {
        color: #f59e0b !important;
        filter: drop-shadow(0 2px 4px rgba(245, 158, 11, 0.3));
    }

    .card.bg-danger ion-icon {
        color: #ef4444 !important;
        filter: drop-shadow(0 2px 4px rgba(239, 68, 68, 0.3));
    }

    .card.bg-info ion-icon {
        color: #3b82f6 !important;
        filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
    }

    .card.bg-primary h6,
    .card.bg-success h6,
    .card.bg-warning h6,
    .card.bg-danger h6,
    .card.bg-info h6 {
        color: #2d3748 !important;
        font-weight: 900;
    }

    .card.bg-primary small,
    .card.bg-success small,
    .card.bg-warning small,
    .card.bg-danger small,
    .card.bg-info small {
        color: #718096 !important;
    }

    .hover-shadow:hover {
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff !important;
        transform: translateY(-2px);
    }

    .hover-shadow:active {
        box-shadow: inset 4px 4px 8px #a3b1c6,
                   inset -4px -4px 8px #ffffff !important;
        transform: translateY(0);
    }

    /* Vehicle Animation Box */
    .vehicle-animation-inline {
        background: #e0e5ec !important;
        box-shadow: inset 4px 4px 8px #a3b1c6,
                   inset -4px -4px 8px #ffffff !important;
        border-radius: 18px !important;
    }

    .vehicle-animation-inline .vehicle-status-text {
        color: #667eea !important;
        font-weight: 900;
    }

    /* ========== GENERAL CARDS & TABLES ========== */
    .card {
        background: #e0e5ec;
        border: none;
        border-radius: 20px;
        box-shadow: 8px 8px 16px #a3b1c6,
                   -8px -8px 16px #ffffff;
    }

    .card-header {
        background: transparent;
        border: none;
        padding: 20px 20px 15px 20px;
    }

    .card-header h5 {
        color: #2d3748;
        font-weight: 900;
        font-size: 1.1rem;
        margin: 0;
    }

    .card-body {
        padding: 20px;
    }

    .table {
        color: #2d3748;
    }

    .table thead th {
        color: #718096;
        font-weight: 700;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #cbd5e0;
        padding: 12px 8px;
    }

    .table tbody td {
        padding: 12px 8px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: rgba(102, 126, 234, 0.05);
    }

    .badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.75rem;
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #10b981, #22c55e) !important;
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f59e0b, #fbbf24) !important;
    }

    .badge.bg-danger {
        background: linear-gradient(135deg, #ef4444, #f87171) !important;
    }

    .badge.bg-info {
        background: linear-gradient(135deg, #3b82f6, #60a5fa) !important;
    }

    .badge.bg-primary {
        background: linear-gradient(135deg, #667eea, #764ba2) !important;
    }

    /* ========== MODAL/POPUP NEOMORPHIC ========== */
    .modal-content {
        background: #e0e5ec !important;
        border: none !important;
        border-radius: 25px !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3) !important;
    }

    .modal-header {
        background: #e0e5ec !important;
        border: none !important;
        border-radius: 25px 25px 0 0 !important;
        padding: 20px 25px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .modal-header.bg-success,
    .modal-header.bg-primary,
    .modal-header.bg-warning,
    .modal-header.bg-danger,
    .modal-header.bg-info {
        background: transparent !important;
        position: relative;
        overflow: hidden;
    }

    .modal-header.bg-success::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
        opacity: 0.15;
        z-index: 0;
    }

    .modal-header.bg-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        opacity: 0.15;
        z-index: 0;
    }

    .modal-header.bg-warning::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        opacity: 0.15;
        z-index: 0;
    }

    .modal-header.bg-danger::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        opacity: 0.15;
        z-index: 0;
    }

    .modal-header.bg-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        opacity: 0.15;
        z-index: 0;
    }

    .modal-title {
        color: #2d3748 !important;
        font-weight: 900 !important;
        position: relative;
        z-index: 1;
    }

    .modal-header .text-white {
        color: #2d3748 !important;
    }

    .modal-header ion-icon {
        color: #667eea !important;
    }

    .modal-body {
        background: #e0e5ec !important;
        padding: 25px !important;
        color: #2d3748 !important;
    }

    .modal-footer {
        background: #e0e5ec !important;
        border: none !important;
        border-radius: 0 0 25px 25px !important;
        padding: 20px 25px !important;
    }

    .btn-close {
        background: transparent !important;
        opacity: 0.7 !important;
    }

    .btn-close:hover {
        opacity: 1 !important;
    }

    /* Form Controls in Modal */
    .modal .form-control,
    .modal .form-select,
    .modal textarea {
        background: #e0e5ec !important;
        border: 2px solid #cbd5e0 !important;
        border-radius: 15px !important;
        padding: 12px 15px !important;
        color: #2d3748 !important;
        box-shadow: inset 2px 2px 5px #a3b1c6,
                   inset -2px -2px 5px #ffffff !important;
    }

    .modal .form-control:focus,
    .modal .form-select:focus,
    .modal textarea:focus {
        border-color: #667eea !important;
        box-shadow: inset 2px 2px 5px #a3b1c6,
                   inset -2px -2px 5px #ffffff,
                   0 0 0 3px rgba(102, 126, 234, 0.1) !important;
        outline: none !important;
    }

    .modal .form-label {
        color: #2d3748 !important;
        font-weight: 700 !important;
        font-size: 0.9rem !important;
        margin-bottom: 8px !important;
    }

    /* Buttons in Modal */
    .modal .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 12px 24px !important;
        font-weight: 700 !important;
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff !important;
        color: white !important;
    }

    .modal .btn-primary:hover {
        box-shadow: 4px 4px 8px #a3b1c6,
                   -4px -4px 8px #ffffff !important;
        transform: translateY(-1px);
    }

    .modal .btn-primary:active {
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2),
                   inset -4px -4px 8px rgba(255,255,255,0.1) !important;
        transform: translateY(0);
    }

    .modal .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%) !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 12px 24px !important;
        font-weight: 700 !important;
        box-shadow: 6px 6px 12px #a3b1c6,
                   -6px -6px 12px #ffffff !important;
        color: white !important;
    }

    .modal .btn-success:hover {
        box-shadow: 4px 4px 8px #a3b1c6,
                   -4px -4px 8px #ffffff !important;
    }

    .modal .btn-success:active {
        box-shadow: inset 4px 4px 8px rgba(0,0,0,0.2) !important;
    }

    .modal .btn-secondary {
        background: #e0e5ec !important;
        border: 2px solid #cbd5e0 !important;
        border-radius: 15px !important;
        padding: 12px 24px !important;
        font-weight: 700 !important;
        color: #718096 !important;
        box-shadow: 4px 4px 8px #a3b1c6,
                   -4px -4px 8px #ffffff !important;
    }

    .modal .btn-secondary:hover {
        color: #2d3748 !important;
        border-color: #a0aec0 !important;
    }

    .modal .btn-secondary:active {
        box-shadow: inset 4px 4px 8px #a3b1c6,
                   inset -4px -4px 8px #ffffff !important;
    }

    /* Alert in Modal */
    .modal .alert {
        background: #e0e5ec !important;
        border: none !important;
        border-radius: 15px !important;
        padding: 15px 20px !important;
        box-shadow: inset 2px 2px 5px #a3b1c6,
                   inset -2px -2px 5px #ffffff !important;
    }

    .modal .alert-info {
        color: #2d3748 !important;
        position: relative;
        overflow: hidden;
    }

    .modal .alert-info::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
    }

    /* Signature Pad in Modal */
    .modal .signature-pad {
        border: 2px solid #cbd5e0 !important;
        border-radius: 15px !important;
        background: #ffffff !important;
        box-shadow: inset 2px 2px 5px #a3b1c6,
                   inset -2px -2px 5px #ffffff !important;
    }

    /* ========== VEHICLE GRID CARDS NEOMORPHIC ========== */
    /* Horizontal Scroll Container */
    .vehicle-scroll-container {
        overflow-x: auto;
        overflow-y: hidden;
        padding: 10px 5px 20px 5px;
        margin: 0 -5px;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #a3b1c6 #e0e5ec;
    }

    .vehicle-scroll-container::-webkit-scrollbar {
        height: 8px;
    }

    .vehicle-scroll-container::-webkit-scrollbar-track {
        background: var(--bg-primary);
        border-radius: 10px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .vehicle-scroll-container::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
    }

    .vehicle-scroll-wrapper {
        display: flex;
        gap: 20px;
        width: max-content;
    }

    .vehicle-scroll-item {
        width: 320px;
        flex-shrink: 0;
    }

    .vehicle-card-neo {
        background: var(--bg-primary);
        border-radius: 25px;
        padding: 0;
        overflow: hidden;
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
        position: relative;
    }

    .vehicle-card-neo:hover {
        transform: translateY(-5px);
        box-shadow: 12px 12px 24px var(--shadow-dark), -12px -12px 24px var(--shadow-light);
    }

    /* Animated Border for Active Card */
    .animated-border {
        position: absolute;
        top: -3px;
        left: -3px;
        right: -3px;
        bottom: -3px;
        border-radius: 28px;
        background: linear-gradient(
            45deg,
            #667eea 0%,
            #764ba2 25%,
            #f093fb 50%,
            #667eea 75%,
            #764ba2 100%
        );
        background-size: 300% 300%;
        animation: borderAnimation 3s ease infinite;
        z-index: -1;
        opacity: 0.8;
    }

    @keyframes borderAnimation {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    .vehicle-card-neo.active-neo {
        box-shadow: 0 0 0 3px var(--bg-primary),
                   8px 8px 16px var(--shadow-dark), 
                   -8px -8px 16px var(--shadow-light);
    }

    /* Header */
    .card-header-neo {
        background: var(--bg-primary);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid var(--border-color);
    }

    .header-content-neo {
        flex: 1;
    }

    .vehicle-name-neo {
        color: var(--text-primary);
        font-weight: 900;
        font-size: 1.1rem;
        margin: 0 0 5px 0;
        line-height: 1.3;
    }

    .vehicle-code-neo {
        color: var(--text-secondary);
        font-size: 0.85rem;
        margin: 0;
        font-weight: 600;
    }

    .status-badge-neo {
        padding: 6px 12px;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
    }

    .status-badge-neo.status-tersedia {
        background: linear-gradient(135deg, #10b981 0%, #22c55e 100%);
        color: white;
    }

    .status-badge-neo.status-keluar {
        background: linear-gradient(135deg, #f59e0b 0%, #fbbf24 100%);
        color: white;
    }

    .status-badge-neo.status-tidak-tersedia,
    .status-badge-neo.status-dipinjam,
    .status-badge-neo.status-service {
        background: linear-gradient(135deg, #ef4444 0%, #f87171 100%);
        color: white;
    }

    /* Photo Section */
    .photo-section-neo {
        background: var(--bg-primary);
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 180px;
        position: relative;
    }

    .vehicle-photo-neo {
        width: 100%;
        height: 180px;
        object-fit: cover;
        object-position: center;
        border-radius: 20px;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
        display: block;
    }

    .no-photo-neo {
        width: 100%;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-primary);
        border-radius: 20px;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .no-photo-neo ion-icon {
        font-size: 80px;
        color: var(--text-tertiary);
    }

    /* Stats Section */
    .stats-section-neo {
        background: var(--bg-primary);
        padding: 20px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stat-item-neo {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        background: var(--bg-primary);
        border-radius: 15px;
        box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);
    }

    .stat-item-neo ion-icon {
        font-size: 24px;
        color: #667eea;
        flex-shrink: 0;
    }

    .stat-content-neo {
        display: flex;
        flex-direction: column;
        gap: 2px;
        flex: 1;
    }

    .stat-label-neo {
        font-size: 0.75rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-value-neo {
        font-size: 0.9rem;
        color: var(--text-primary);
        font-weight: 800;
    }

    /* Footer */
    .card-footer-neo {
        background: var(--bg-primary);
        padding: 15px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid var(--border-color);
        margin-top: auto;
    }

    .view-detail-neo {
        color: #667eea;
        font-weight: 800;
        font-size: 0.9rem;
    }

    .card-footer-neo ion-icon {
        font-size: 20px;
        color: #667eea;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .vehicle-scroll-item {
            width: 280px;
        }
        
        .vehicle-name-neo {
            font-size: 1rem;
        }
        
        .photo-section-neo,
        .vehicle-photo-neo,
        .no-photo-neo {
            min-height: 150px;
            height: 150px;
        }
        
        .no-photo-neo ion-icon {
            font-size: 60px;
        }
    }

</style>

<!-- Header Section -->
<div id="header-section">
    <div id="section-back">
        <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>
    <div id="header-title">
        <h3>Manajemen Kendaraan</h3>
        <p>Pilih kendaraan untuk melihat detail & melakukan aksi</p>
    </div>
</div>

<!-- Content Section -->
<div style="padding: 0 15px 100px 15px;">

<!-- Grid Kendaraan -->
<div class="mb-4">
    <h5 style="color: var(--text-primary); font-weight: 800; margin-bottom: 15px; padding-left: 5px;">
        <ion-icon name="car-sport-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
        Daftar Kendaraan
        <small style="color: var(--text-secondary); font-size: 0.75rem; font-weight: 600; margin-left: 10px;">Geser untuk melihat lainnya</small>
    </h5>
    
    <!-- Horizontal Scrollable Container -->
    <div class="vehicle-scroll-container">
        <div class="vehicle-scroll-wrapper">
            @foreach($allKendaraan as $k)
            <div class="vehicle-scroll-item">
                <a href="{{ route('kendaraan.karyawan.index', ['k' => $k->id]) }}" class="text-decoration-none">
                    <div class="vehicle-card-neo {{ $kendaraan && $kendaraan->id == $k->id ? 'active-neo' : '' }}">
                        <!-- Animated Border -->
                        @if($kendaraan && $kendaraan->id == $k->id)
                        <div class="animated-border"></div>
                        @endif
                        
                        <!-- Header -->
                        <div class="card-header-neo">
                            <div class="header-content-neo">
                                <h6 class="vehicle-name-neo">{{ $k->nama_kendaraan }}</h6>
                                <p class="vehicle-code-neo">{{ $k->kode_kendaraan }}</p>
                            </div>
                            <div class="status-badge-neo status-{{ $k->status }}">
                                {{ strtoupper($k->status) }}
                            </div>
                        </div>
                        
                        <!-- Photo Section -->
                        <div class="photo-section-neo">
                            @if($k->foto)
                            <img src="{{ asset('storage/kendaraan/' . $k->foto) }}" alt="{{ $k->nama_kendaraan }}" class="vehicle-photo-neo" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'no-photo-neo\'><ion-icon name=\'car-sport-outline\'></ion-icon></div>';">
                            @else
                            <div class="no-photo-neo">
                                <ion-icon name="car-sport-outline"></ion-icon>
                            </div>
                            @endif
                        </div>
                        
                        <!-- Stats Section -->
                        <div class="stats-section-neo">
                            <div class="stat-item-neo">
                                <ion-icon name="card-outline"></ion-icon>
                                <div class="stat-content-neo">
                                    <span class="stat-label-neo">No Polisi</span>
                                    <span class="stat-value-neo">{{ $k->no_polisi }}</span>
                                </div>
                            </div>
                            <div class="stat-item-neo">
                                <ion-icon name="grid-outline"></ion-icon>
                                <div class="stat-content-neo">
                                    <span class="stat-label-neo">Jenis</span>
                                    <span class="stat-value-neo">{{ $k->jenis_kendaraan }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Footer -->
                        <div class="card-footer-neo">
                            <span class="view-detail-neo">Lihat Detail</span>
                            <ion-icon name="arrow-forward-outline"></ion-icon>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($kendaraan)
<!-- Detail Kendaraan yang Dipilih -->
<div class="mx-3 mb-3" style="border-radius: 20px; background: var(--bg-primary); padding: 18px; box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);">
    <div class="d-flex align-items-center gap-2">
        <div style="width: 40px; height: 40px; border-radius: 12px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <ion-icon name="information-circle" style="font-size: 24px; color: white;"></ion-icon>
        </div>
        <div style="color: var(--text-primary);">
            <strong style="font-size: 0.95rem;">Kendaraan Terpilih:</strong><br>
            <span style="font-size: 0.9rem;">{{ $kendaraan->nama_kendaraan }} ({{ $kendaraan->no_polisi }})</span><br>
            <small style="color: var(--text-secondary); font-size: 0.8rem;">Scroll ke bawah untuk melakukan aksi</small>
        </div>
    </div>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs mb-3" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'aktivitas' || !Request::get('tab') ? 'active' : '' }}" 
                data-toggle="tab" data-target="#aktivitas-tab">
            <i class="ti ti-car me-2"></i>Aktivitas Keluar/Masuk
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'peminjaman' ? 'active' : '' }}" 
                data-toggle="tab" data-target="#peminjaman-tab">
            <i class="ti ti-user-check me-2"></i>Peminjaman
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link {{ Request::get('tab') == 'service' ? 'active' : '' }}" 
                data-toggle="tab" data-target="#service-tab">
            <i class="ti ti-tool me-2"></i>Service
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content">
    <!-- Aktivitas Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'aktivitas' || !Request::get('tab') ? 'show active' : '' }}" id="aktivitas-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                    <div class="col-md-3 col-6">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalKeluarKendaraan">
                            <div class="card bg-primary text-white hover-shadow" style="min-height: 80px;">
                                <div class="card-body text-center py-2">
                                    <ion-icon name="arrow-forward-outline" style="font-size: 32px;"></ion-icon>
                                    <h6 class="mt-2 mb-0">Keluar</h6>
                                    <small style="font-size: 11px;">Tandai Keluar</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    @if($kendaraan->aktivitasAktif)
                    <div class="col-md-6 col-12 mb-2">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalKembaliKendaraan">
                            <div class="card bg-success text-white hover-shadow" style="height: 100%; min-height: 80px;">
                                <div class="card-body d-flex align-items-center justify-content-center gap-2" style="padding: 15px;">
                                    <ion-icon name="arrow-back-outline" style="font-size: 32px;"></ion-icon>
                                    <div class="text-start">
                                        <h6 class="mb-0">Tandai Kembali</h6>
                                        <small style="font-size: 11px;">Kendaraan Kembali</small>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    
                    <!-- Animasi Kendaraan Berjalan -->
                    <div class="col-md-6 col-12 mb-2">
                        <div class="vehicle-animation-inline {{ $kendaraan->status == 'keluar' ? 'active' : '' }}" id="vehicleAnimationInline">
                            <div class="vehicle-status-text">
                                <ion-icon name="car-sport" style="font-size: 16px; vertical-align: middle;"></ion-icon>
                                Kendaraan Berjalan
                            </div>
                            <div class="vehicle-moving-inline">🚗</div>
                            <div class="road-line-inline" style="left: 0%;"></div>
                            <div class="road-line-inline" style="left: 25%;"></div>
                            <div class="road-line-inline" style="left: 50%;"></div>
                            <div class="road-line-inline" style="left: 75%;"></div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            @if($kendaraan->aktivitas->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Pengemudi</th>
                                        <th>Tujuan</th>
                                        <th>Penumpang</th>
                                        <th>Waktu Keluar</th>
                                        <th>Waktu Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->aktivitas as $a)
                                    <tr>
                                        <td>{{ $a->kode_aktivitas }}</td>
                                        <td>{{ $a->driver }}</td>
                                        <td>{{ Str::limit($a->tujuan, 30) }}</td>
                                        <td>
                                            @if($a->penumpang)
                                                {{ Str::limit($a->penumpang, 30) }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($a->waktu_keluar)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if($a->waktu_kembali)
                                                {{ \Carbon\Carbon::parse($a->waktu_kembali)->format('d/m/Y H:i') }}
                                            @else
                                                <span class="badge bg-warning">Belum Kembali</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($a->status == 'keluar')
                                                <span class="badge bg-info">Keluar</span>
                                            @else
                                                <span class="badge bg-success">Kembali</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- End Aktivitas Tab -->

    <!-- Peminjaman Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'peminjaman' ? 'show active' : '' }}" id="peminjaman-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                    <div class="col-md-3">
                        <a href="#" class="text-decoration-none" data-toggle="modal" data-target="#modalPinjamKendaraan">
                            <div class="card bg-warning text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-hand-grab" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Pinjam</h5>
                                    <small>Pinjam Kendaraan</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                    
                    {{-- Tombol Kembalikan untuk Workflow Pinjam --}}
                    @if($kendaraan->prosesAktif && $kendaraan->prosesAktif->jenis_proses == 'pinjam')
                    <div class="col-md-3">
                        <button type="button" class="text-decoration-none border-0 w-100 p-0" 
                                style="background: none;" 
                                id="btnOpenReturnModal" 
                                data-kendaraan-id="{{ $kendaraan->id }}" 
                                data-proses-id="{{ $kendaraan->prosesAktif->id }}">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <ion-icon name="arrow-undo-outline" style="font-size: 48px;"></ion-icon>
                                    <h5 class="mt-3 mb-0">Kembalikan</h5>
                                    <small>Kendaraan Pinjaman</small>
                                </div>
                            </div>
                        </button>
                    </div>
                    
                    {{-- Animasi Kendaraan Berjalan saat Dipinjam --}}
                    <div class="col-md-6 mb-2">
                        <div class="vehicle-animation-inline active" id="vehicleAnimationPinjam">
                            <div class="vehicle-status-text">
                                <ion-icon name="hand-left" style="font-size: 16px; vertical-align: middle;"></ion-icon>
                                Kendaraan Sedang Dipinjam
                            </div>
                            <div class="vehicle-moving-inline">🚗</div>
                            <div class="road-line-inline" style="left: 0%;"></div>
                            <div class="road-line-inline" style="left: 25%;"></div>
                            <div class="road-line-inline" style="left: 50%;"></div>
                            <div class="road-line-inline" style="left: 75%;"></div>
                        </div>
                    </div>
                    @endif
                    
                    {{-- Tombol lama untuk backward compatibility --}}
                    @if($kendaraan->peminjamanAktif)
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.peminjaman.kembali', Crypt::encrypt($kendaraan->peminjamanAktif->id)) }}" class="text-decoration-none">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-arrow-back" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Kembalikan</h5>
                                    <small>Kendaraan Dikembalikan</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-3">
                        <a href="{{ route('kendaraan.peminjaman.tracking', Crypt::encrypt($kendaraan->peminjamanAktif->id)) }}" class="text-decoration-none" target="_blank">
                            <div class="card bg-info text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-map" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">GPS Tracking</h5>
                                    <small>Riwayat GPS</small>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Peminjaman -->
            @if($kendaraan->peminjaman->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 class="mb-0">
                            <ion-icon name="time-outline" style="vertical-align: middle;"></ion-icon>
                            Riwayat Peminjaman Kendaraan
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Peminjam</th>
                                        <th>Keperluan</th>
                                        <th>Waktu Pinjam</th>
                                        <th>Waktu Kembali</th>
                                        <th>KM</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->peminjaman as $index => $p)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><span class="badge bg-warning">{{ $p->kode_peminjaman }}</span></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($p->foto_identitas)
                                                    <img src="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}" 
                                                        alt="Foto" 
                                                        class="foto-popup me-2" 
                                                        style="width: 30px; height: 30px; object-fit: cover; border-radius: 50%; cursor: pointer;"
                                                        data-foto="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}"
                                                        data-title="Foto Identitas - {{ $p->nama_peminjam }}">
                                                @endif
                                                <div>
                                                    <strong>{{ $p->nama_peminjam }}</strong><br>
                                                    <small class="text-muted">{{ $p->no_hp_peminjam }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ Str::limit($p->keperluan, 30) }}</td>
                                        <td>
                                            <small>
                                                {{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('d/m/Y') }}<br>
                                                {{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('H:i') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($p->waktu_kembali)
                                                <small>
                                                    {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y') }}<br>
                                                    {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('H:i') }}
                                                </small>
                                            @else
                                                <span class="badge bg-warning">Belum Kembali</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small>
                                                {{ number_format($p->km_awal ?? 0) }} km
                                                @if($p->km_akhir)
                                                    <br>→ {{ number_format($p->km_akhir) }} km
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($p->status == 'dipinjam')
                                                <span class="badge bg-primary">Dipinjam</span>
                                            @else
                                                <span class="badge bg-success">Dikembalikan</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-bs-toggle="modal" 
                                                    data-target="#modalDetailPeminjaman{{ $p->id }}"
                                                    data-bs-target="#modalDetailPeminjaman{{ $p->id }}">
                                                <ion-icon name="eye-outline"></ion-icon>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- End Peminjaman Tab -->

    <!-- Service Tab -->
    <div class="tab-pane fade {{ Request::get('tab') == 'service' ? 'show active' : '' }}" id="service-tab">
        <div class="row">
            <!-- Action Cards -->
            <div class="col-md-12 mb-3">
                <div class="row g-3">
                    @if($kendaraan->status == 'tersedia')
                        <!-- Tombol Service dihilangkan, hanya admin yang bisa input sebelum service -->
                    @endif
                    
                    @if($kendaraan->serviceAktif)
                    <div class="col-md-3">
                        <button type="button" class="text-decoration-none border-0 p-0 w-100 btn-selesai-service-trigger" data-toggle="modal" data-target="#modalSelesaiService" style="background: none; cursor: pointer;">
                            <div class="card bg-success text-white hover-shadow">
                                <div class="card-body text-center py-4">
                                    <i class="ti ti-check" style="font-size: 48px;"></i>
                                    <h5 class="mt-3 mb-0">Selesai Service</h5>
                                    <small>Tandai Selesai</small>
                                </div>
                            </div>
                        </button>
                    </div>
                    @else
                    <!-- Debug: Service Aktif tidak ada -->
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <ion-icon name="information-circle-outline"></ion-icon>
                            Tidak ada service aktif saat ini. Tombol "Selesai Service" akan muncul jika ada service yang sedang berjalan.
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Service -->
            @if($kendaraan->services->count() > 0)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Service Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Jenis Service</th>
                                        <th>Bengkel</th>
                                        <th>Tanggal Masuk</th>
                                        <th>Tanggal Selesai</th>
                                        <th>Biaya</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kendaraan->services as $s)
                                    <tr>
                                        <td>{{ $s->kode_service }}</td>
                                        <td>{{ $s->jenis_service }}</td>
                                        <td>{{ $s->bengkel }}</td>
                                        <td>{{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($s->waktu_selesai)
                                                {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y') }}
                                            @else
                                                <span class="badge bg-warning">Dalam Service</span>
                                            @endif
                                        </td>
                                        <td>Rp {{ number_format($s->biaya_akhir ?? $s->estimasi_biaya ?? 0, 0, ',', '.') }}</td>
                                        <td>
                                            @if($s->status == 'proses')
                                                <span class="badge bg-warning">Dalam Service</span>
                                            @else
                                                <span class="badge bg-success">Selesai</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-info" 
                                                    data-toggle="modal" 
                                                    data-bs-toggle="modal" 
                                                    data-target="#modalDetailService{{ $s->id }}"
                                                    data-bs-target="#modalDetailService{{ $s->id }}" 
                                                    title="Lihat Detail">
                                                <i class="ti ti-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-12">
                <div class="alert alert-info">
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Belum ada riwayat service untuk kendaraan ini
                </div>
            </div>
            @endif
        </div>
    </div>
    <!-- End Service Tab -->
        
</div>
<!-- End tab-content -->
</div>
<!-- End Content Section -->

<!-- Modal Detail Service - Outside tabs -->
@foreach($kendaraan->services as $s)
<div class="modal fade" id="modalDetailService{{ $s->id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">Detail Service - {{ $s->kode_service }}</h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Jenis Service:</strong><br>
                        {{ $s->jenis_service }}
                    </div>
                    <div class="col-md-6">
                        <strong>Bengkel:</strong><br>
                        {{ $s->bengkel }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Waktu Masuk:</strong><br>
                        {{ \Carbon\Carbon::parse($s->waktu_service)->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Waktu Selesai:</strong><br>
                        @if($s->waktu_selesai)
                            {{ \Carbon\Carbon::parse($s->waktu_selesai)->format('d/m/Y H:i') }}
                        @else
                            <span class="badge bg-warning">Belum Selesai</span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>KM Service:</strong><br>
                        {{ number_format($s->km_service, 0, ',', '.') }} km
                    </div>
                    <div class="col-md-6">
                        <strong>KM Selesai:</strong><br>
                        {{ $s->km_selesai ? number_format($s->km_selesai, 0, ',', '.') . ' km' : '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Estimasi Biaya:</strong><br>
                        Rp {{ number_format($s->estimasi_biaya, 0, ',', '.') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Biaya Akhir:</strong><br>
                        {{ $s->biaya_akhir ? 'Rp ' . number_format($s->biaya_akhir, 0, ',', '.') : '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Deskripsi Kerusakan:</strong><br>
                        {{ $s->deskripsi_kerusakan }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Pekerjaan:</strong><br>
                        {{ $s->pekerjaan_selesai ?? $s->pekerjaan ?? '-' }}
                    </div>
                </div>
                @if($s->catatan_mekanik)
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Catatan Mekanik:</strong><br>
                        <div class="alert alert-info mb-0">{{ $s->catatan_mekanik }}</div>
                    </div>
                </div>
                @endif
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>PIC / Mekanik:</strong><br>
                        {{ $s->pic_selesai ?? $s->pic ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Kondisi Kendaraan:</strong><br>
                        {{ $s->kondisi_kendaraan ?? '-' }}
                    </div>
                </div>
                @if($s->foto_before || $s->foto_after)
                <div class="row">
                    @if($s->foto_before)
                    <div class="col-md-6">
                        <strong>Foto Sebelum:</strong><br>
                        <img src="{{ asset('storage/service/' . $s->foto_before) }}" class="img-fluid rounded mt-2" alt="Foto Before">
                    </div>
                    @endif
                    @if($s->foto_after)
                    <div class="col-md-6">
                        <strong>Foto Setelah:</strong><br>
                        <img src="{{ asset('storage/service/' . $s->foto_after) }}" class="img-fluid rounded mt-2" alt="Foto After">
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal Detail Peminjaman - Outside tabs so it can be accessed from anywhere -->
@foreach($kendaraan->peminjaman as $p)
<div class="modal fade" id="modalDetailPeminjaman{{ $p->id }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailPeminjamanLabel{{ $p->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white" id="modalDetailPeminjamanLabel{{ $p->id }}">
                    <ion-icon name="document-text-outline"></ion-icon>
                    Detail Peminjaman - {{ $p->kode_peminjaman }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Peminjam:</strong><br>
                        {{ $p->nama_peminjam }}
                    </div>
                    <div class="col-md-6">
                        <strong>No. HP:</strong><br>
                        {{ $p->no_hp_peminjam ?? '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Keperluan:</strong><br>
                        {{ $p->keperluan }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Waktu Pinjam:</strong><br>
                        {{ \Carbon\Carbon::parse($p->waktu_pinjam)->format('d/m/Y H:i') }}
                    </div>
                    <div class="col-md-6">
                        <strong>Rencana Kembali:</strong><br>
                        {{ $p->waktu_rencana_kembali ? \Carbon\Carbon::parse($p->waktu_rencana_kembali)->format('d/m/Y H:i') : '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Waktu Kembali Aktual:</strong><br>
                        @if($p->waktu_kembali)
                            {{ \Carbon\Carbon::parse($p->waktu_kembali)->format('d/m/Y H:i') }}
                        @else
                            <span class="badge bg-warning">Belum Kembali</span>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <strong>Status:</strong><br>
                        @if($p->status == 'dipinjam')
                            <span class="badge bg-primary">Dipinjam</span>
                        @else
                            <span class="badge bg-success">Dikembalikan</span>
                        @endif
                    </div>
                </div>
                <hr>
                <h6 class="fw-bold">Kondisi Kendaraan</h6>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>KM Awal:</strong><br>
                        {{ number_format($p->km_awal ?? 0) }} km
                    </div>
                    <div class="col-md-6">
                        <strong>KM Akhir:</strong><br>
                        {{ $p->km_akhir ? number_format($p->km_akhir) . ' km' : '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>BBM Pinjam:</strong><br>
                        {{ $p->status_bbm_pinjam ?? '-' }}
                    </div>
                    <div class="col-md-6">
                        <strong>BBM Kembali:</strong><br>
                        {{ $p->status_bbm_kembali ?? '-' }}
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <strong>Kondisi Kendaraan:</strong><br>
                        {{ $p->kondisi_kendaraan ?? '-' }}
                    </div>
                </div>
                @if($p->keterangan || $p->catatan_kembali)
                <hr>
                <h6 class="fw-bold">Catatan</h6>
                @if($p->keterangan)
                <div class="mb-2">
                    <strong>Keterangan Peminjaman:</strong><br>
                    <div class="alert alert-info">{{ $p->keterangan }}</div>
                </div>
                @endif
                @if($p->catatan_kembali)
                <div class="mb-2">
                    <strong>Catatan Pengembalian:</strong><br>
                    <div class="alert alert-success">{{ $p->catatan_kembali }}</div>
                </div>
                @endif
                @endif
                @if($p->foto_identitas || $p->foto_kembali)
                <hr>
                <h6 class="fw-bold">Dokumentasi</h6>
                <div class="row">
                    @if($p->foto_identitas)
                    <div class="col-md-6 mb-2">
                        <strong>Foto Identitas:</strong><br>
                        <img src="{{ asset('storage/peminjaman/identitas/' . $p->foto_identitas) }}" 
                            class="img-fluid rounded mt-2" alt="Foto Identitas">
                    </div>
                    @endif
                    @if($p->foto_kembali)
                    <div class="col-md-6 mb-2">
                        <strong>Foto Pengembalian:</strong><br>
                        <img src="{{ asset('storage/peminjaman/' . $p->foto_kembali) }}" 
                            class="img-fluid rounded mt-2" alt="Foto Kembali">
                    </div>
                    @endif
                </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endif
{{-- End @if($kendaraan) from line 1572 --}}

@push('myscript')
<style>
    .hover-shadow:hover {
        box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        transform: translateY(-2px);
        transition: all 0.3s ease;
    }
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .transition-all {
        transition: transform 0.3s ease;
    }
    .indicator-dot:hover {
        transform: scale(1.3);
    }
    .hover-bg-light:hover {
        background-color: #f9fafb;
    }
    
    /* Navigation Button Styles */
    #prevBtn, #nextBtn {
        transition: all 0.3s ease !important;
    }
    
    #prevBtn:hover:not(:disabled), #nextBtn:hover:not(:disabled) {
        transform: translateY(-50%) scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4) !important;
    }
    
    #prevBtn:active:not(:disabled), #nextBtn:active:not(:disabled) {
        transform: translateY(-50%) scale(0.95) !important;
    }
    
    #prevBtn:disabled, #nextBtn:disabled {
        opacity: 0.3 !important;
        cursor: not-allowed !important;
    }
    
    /* Swipe Container */
    #kendaraanCardsContainer {
        cursor: grab;
        touch-action: pan-y pinch-zoom;
        user-select: none;
        overflow: hidden;
        position: relative;
        width: 100%;
    }
    
    #kendaraanCardsContainer:active {
        cursor: grabbing;
    }
    
    /* Cards wrapper for horizontal scrolling */
    #kendaraanCards {
        display: flex;
        width: 100%;
        transition: transform 0.3s ease-out;
        will-change: transform;
    }
    
    .kendaraan-card {
        min-width: 100%;
        max-width: 100%;
        flex-shrink: 0;
        flex-grow: 0;
    }
</style>

<!-- Modal Popup Foto -->
<div class="modal fade" id="modalFotoPeminjaman" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoPeminjamanTitle">Foto</h5>
                <button type="button" class="btn-close" data-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalFotoPeminjamanImage" src="" alt="Foto" class="img-fluid" style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

<!-- Modal Pinjam Kendaraan -->
<div class="modal fade" id="modalPinjamKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="hand-left-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Pinjam Kendaraan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form id="formPinjamKendaraan" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <input type="hidden" name="kendaraan_id" id="kendaraan_id_pinjam" value="{{ $kendaraan->id }}">
                
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert alert-warning" style="border-radius: 15px; border-left: 4px solid #f59e0b;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>{{ $kendaraan->no_polisi }} - {{ $kendaraan->jenis_kendaraan }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="person-outline"></ion-icon> Nama Peminjam *
                        </label>
                        <input type="text" class="form-control" name="nama_peminjam" required 
                               value="{{ auth()->user()->name ?? '' }}"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Nama peminjam">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="call-outline"></ion-icon> No HP Peminjam *
                        </label>
                        <input type="text" class="form-control" name="no_hp" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="08xxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="mail-outline"></ion-icon> Email Peminjam
                        </label>
                        <input type="email" class="form-control" name="email_peminjam" 
                               value="{{ auth()->user()->email ?? '' }}"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="email@example.com">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="card-outline"></ion-icon> Foto KTP/Identitas *
                        </label>
                        <input type="file" class="form-control" name="foto_identitas" accept="image/*" required 
                               id="foto_identitas_pinjam"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto KTP, SIM, atau identitas lainnya (Max: 2MB, Format: JPG/PNG)</small>
                        <div class="mt-2" id="preview_container_pinjam" style="display: none;">
                            <img id="preview_identitas_pinjam" src="" alt="Preview" style="max-width: 100%; height: auto; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="navigate-outline"></ion-icon> Tujuan Penggunaan *
                        </label>
                        <input type="text" class="form-control" name="tujuan_penggunaan" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Contoh: Dinas ke Jakarta, Antar Jemput Santri, dll" maxlength="200">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Keperluan *
                        </label>
                        <textarea class="form-control" name="keperluan" rows="2" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Jelaskan keperluan peminjaman secara detail"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Pinjam *
                                </label>
                                <input type="date" class="form-control" name="tanggal_pinjam" required 
                                       value="{{ date('Y-m-d') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="time-outline"></ion-icon> Jam Pinjam *
                                </label>
                                <input type="time" class="form-control" name="jam_pinjam" required 
                                       value="{{ date('H:i') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Kembali *
                                </label>
                                <input type="date" class="form-control" name="tanggal_kembali" required 
                                       value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="time-outline"></ion-icon> Jam Kembali *
                                </label>
                                <input type="time" class="form-control" name="jam_rencana_kembali" required 
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="speedometer-outline"></ion-icon> KM Awal
                                </label>
                                <input type="number" class="form-control" name="km_awal" 
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                       placeholder="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="water-outline"></ion-icon> Status BBM
                                </label>
                                <select class="form-select" name="status_bbm_pinjam" 
                                        style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                    <option value="Penuh">Penuh</option>
                                    <option value="3/4">3/4</option>
                                    <option value="1/2">1/2</option>
                                    <option value="1/4">1/4</option>
                                    <option value="Kosong">Kosong</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="location-outline"></ion-icon> Lokasi GPS Saat Ini
                        </label>
                        <input type="text" class="form-control" id="lokasi_display_pinjam" readonly 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px; background: #f9fafb;"
                               placeholder="Mencari lokasi...">
                        <input type="hidden" name="latitude" id="latitude_pinjam">
                        <input type="hidden" name="longitude" id="longitude_pinjam">
                        <small class="text-muted d-block mt-1">GPS akan otomatis terdeteksi</small>
                    </div>

                    <!-- Signature Pad - EXACT DARI ADMIN -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="create-outline"></ion-icon> Tanda Tangan Digital Peminjam *
                        </label>
                        <div class="border rounded p-2 bg-white" style="background-color: white !important; position: relative;">
                            <canvas id="signature-pad-pinjam" class="signature-pad" style="position: relative; z-index: 1;"></canvas>
                        </div>
                        <input type="hidden" name="signature" id="signature">
                        <div class="mt-2">
                            <button type="button" class="btn btn-sm btn-warning" onclick="clearSignaturePinjam()">
                                <ion-icon name="refresh-outline"></ion-icon> Hapus Tanda Tangan
                            </button>
                            <button type="button" class="btn btn-sm btn-info" onclick="testCanvas()">
                                🧪 Test Canvas
                            </button>
                            <small class="text-muted ms-3">✍️ Tanda tangan di kotak putih di atas</small>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-warning text-white" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Kirim Permohonan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Keluar Kendaraan -->
<div class="modal fade" id="modalKeluarKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="car-sport-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kendaraan Keluar
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.aktivitas.prosesKeluar', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formKeluarKendaraan" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert alert-info" style="border-radius: 15px; border-left: 4px solid #667eea;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>{{ $kendaraan->no_polisi }} - {{ $kendaraan->jenis_kendaraan }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="person-outline"></ion-icon> Nama Pengemudi *
                        </label>
                        <input type="text" class="form-control" name="nama_pengemudi" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Masukkan nama pengemudi">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="call-outline"></ion-icon> No. HP Pengemudi
                        </label>
                        <input type="text" class="form-control" name="no_hp_pengemudi" 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="08xxxx">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="people-outline"></ion-icon> Penumpang
                        </label>
                        <input type="text" class="form-control" name="penumpang" 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                               placeholder="Nama penumpang (opsional)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="location-outline"></ion-icon> Tujuan *
                        </label>
                        <textarea class="form-control" name="tujuan" rows="2" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Tujuan perjalanan"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal *
                            </label>
                            <input type="date" class="form-control" name="tanggal_keluar" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam *
                            </label>
                            <input type="time" class="form-control" name="jam_keluar" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Awal
                            </label>
                            <input type="number" class="form-control" name="km_awal" 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                   placeholder="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="water-outline"></ion-icon> Status BBM
                            </label>
                            <select class="form-select" name="status_bbm_keluar" 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">Pilih</option>
                                <option value="Penuh">Penuh</option>
                                <option value="3/4">3/4</option>
                                <option value="1/2">1/2</option>
                                <option value="1/4">1/4</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_keluar" id="latitude_keluar">
                    <input type="hidden" name="longitude_keluar" id="longitude_keluar">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kembali Kendaraan -->
@if($kendaraan->aktivitasAktif)
<div class="modal fade" id="modalKembaliKendaraan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kendaraan Kembali
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('kendaraan.aktivitas.prosesKembali', Crypt::encrypt($kendaraan->aktivitasAktif->id)) }}" method="POST" id="formKembaliKendaraan" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Aktivitas -->
                    <div class="alert alert-success" style="border-radius: 15px; border-left: 4px solid #10b981;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small>Driver: {{ $kendaraan->aktivitasAktif->driver }}</small><br>
                                <small>Tujuan: {{ $kendaraan->aktivitasAktif->tujuan }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal *
                            </label>
                            <input type="date" class="form-control" name="tanggal_kembali" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam *
                            </label>
                            <input type="time" class="form-control" name="jam_kembali" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Akhir *
                            </label>
                            <input type="number" class="form-control" name="km_akhir" required 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                   placeholder="0">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="water-outline"></ion-icon> Status BBM
                            </label>
                            <select class="form-select" name="status_bbm_kembali" 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">Pilih</option>
                                <option value="Penuh">Penuh</option>
                                <option value="3/4">3/4</option>
                                <option value="1/2">1/2</option>
                                <option value="1/4">1/4</option>
                                <option value="Kosong">Kosong</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="3" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_kembali" id="latitude_kembali">
                    <input type="hidden" name="longitude_kembali" id="longitude_kembali">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-done-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Return Peminjaman Kendaraan - DI LUAR @endif -->
<div class="modal fade" id="modalReturnPinjam" tabindex="-1" role="dialog" aria-labelledby="modalReturnLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="arrow-undo-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Kembalikan Kendaraan Pinjaman
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 1; font-size: 1.5rem; padding: 0.5rem;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formReturnPinjam" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <input type="hidden" name="kendaraan_id" id="return_kendaraan_id">
                <input type="hidden" name="proses_id" id="return_proses_id">
                
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <div class="alert alert-info" style="border-radius: 15px; border-left: 4px solid #10b981;">
                        <div class="d-flex align-items-center">
                            <ion-icon name="information-circle-outline" style="font-size: 32px; margin-right: 10px;"></ion-icon>
                            <div>
                                <strong>Pengembalian Kendaraan</strong><br>
                                <small>Pastikan semua data pengembalian diisi dengan benar</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="calendar-outline"></ion-icon> Tanggal Kembali *
                                </label>
                                <input type="date" class="form-control" name="tanggal_return" required 
                                       value="{{ date('Y-m-d') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="time-outline"></ion-icon> Jam Kembali *
                                </label>
                                <input type="time" class="form-control" name="jam_return" required 
                                       value="{{ date('H:i') }}"
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="speedometer-outline"></ion-icon> KM Akhir *
                                </label>
                                <input type="number" class="form-control" name="km_akhir" required 
                                       style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                       placeholder="0" min="0">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="mb-3">
                                <label class="form-label fw-bold">
                                    <ion-icon name="water-outline"></ion-icon> Status BBM Akhir
                                </label>
                                <select class="form-select" name="bbm_akhir" 
                                        style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                    <option value="">Pilih</option>
                                    <option value="Penuh">Penuh</option>
                                    <option value="3/4">3/4</option>
                                    <option value="1/2">1/2</option>
                                    <option value="1/4">1/4</option>
                                    <option value="Kosong">Kosong</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="shield-checkmark-outline"></ion-icon> Kondisi Kendaraan *
                        </label>
                        <select class="form-select" name="kondisi_kendaraan" required 
                                style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                            <option value="">Pilih Kondisi</option>
                            <option value="Baik">Baik</option>
                            <option value="Cukup">Cukup</option>
                            <option value="Perlu Perbaikan">Perlu Perbaikan</option>
                            <option value="Rusak">Rusak</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="images-outline"></ion-icon> Foto Kondisi Kendaraan *
                        </label>
                        <input type="file" class="form-control" name="foto_kondisi[]" multiple accept="image/*" required 
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Wajib upload foto kondisi kendaraan saat dikembalikan (Max: 2MB per foto)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Catatan
                        </label>
                        <textarea class="form-control" name="catatan" rows="3" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Catatan tambahan pengembalian (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude" id="return_latitude">
                    <input type="hidden" name="longitude" id="return_longitude">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-done-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Kembalikan Kendaraan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Form Service -->
<div class="modal fade" id="modalFormService" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="construct-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Form Service Kendaraan
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('service.proses', Crypt::encrypt($kendaraan->id)) }}" method="POST" id="formService" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Kendaraan -->
                    <div class="alert" style="border-radius: 15px; border-left: 4px solid #ef4444; background: #fee2e2;">
                        <div class="d-flex align-items-center">
                            @if($kendaraan->foto && Storage::disk('public')->exists('kendaraan/' . $kendaraan->foto))
                                <img src="{{ asset('storage/kendaraan/' . $kendaraan->foto) }}" style="width: 60px; height: 60px; border-radius: 10px; object-fit: cover; margin-right: 15px;">
                            @else
                                <div style="width: 60px; height: 60px; border-radius: 10px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); display: flex; align-items: center; justify-content: center; margin-right: 15px;">
                                    <ion-icon name="car-sport" style="font-size: 2rem; color: white; opacity: 0.7;"></ion-icon>
                                </div>
                            @endif
                            <div>
                                <strong style="color: #991b1b; font-size: 1.1rem;">{{ $kendaraan->nama_kendaraan }}</strong><br>
                                <small style="color: #7f1d1d;"><strong>No. Polisi:</strong> {{ $kendaraan->no_polisi }}</small><br>
                                <small style="color: #7f1d1d;"><strong>KM Terakhir:</strong> {{ number_format($kendaraan->km_terakhir ?? 0) }} km</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal Service *
                            </label>
                            <input type="date" class="form-control" name="tanggal_service" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam Service *
                            </label>
                            <input type="time" class="form-control" name="jam_service" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="construct-outline"></ion-icon> Jenis Service *
                            </label>
                            <select class="form-select" name="jenis_service" required 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Service Rutin">Service Rutin</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Ganti Oli">Ganti Oli</option>
                                <option value="Ganti Ban">Ganti Ban</option>
                                <option value="Tune Up">Tune Up</option>
                                <option value="Body Repair">Body Repair</option>
                                <option value="Cuci">Cuci</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="business-outline"></ion-icon> Nama Bengkel
                            </label>
                            <input type="text" class="form-control" name="bengkel" 
                                   placeholder="Nama bengkel"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Saat Service
                            </label>
                            <input type="number" class="form-control" name="km_service" min="0"
                                   placeholder="KM saat service"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="cash-outline"></ion-icon> Estimasi Biaya (Rp)
                            </label>
                            <input type="number" class="form-control" name="estimasi_biaya" min="0" step="1000"
                                   placeholder="0"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Deskripsi Kerusakan / Keluhan *
                        </label>
                        <textarea class="form-control" name="deskripsi_kerusakan" rows="3" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Jelaskan kerusakan atau keluhan kendaraan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="build-outline"></ion-icon> Pekerjaan Yang Akan Dilakukan
                        </label>
                        <textarea class="form-control" name="pekerjaan" rows="3" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Daftar pekerjaan yang akan dilakukan"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Estimasi Selesai
                            </label>
                            <input type="date" class="form-control" name="estimasi_selesai" 
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="person-outline"></ion-icon> PIC / Mekanik
                            </label>
                            <input type="text" class="form-control" name="pic" 
                                   placeholder="Nama mekanik"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="image-outline"></ion-icon> Foto Kondisi Sebelum Service
                        </label>
                        <input type="file" class="form-control" name="foto_before" accept="image/*" id="foto_before_service"
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto kondisi kendaraan sebelum service (Max: 2MB)</small>
                        <div class="mt-2">
                            <img id="preview_foto_before_service" style="max-width: 200px; display: none; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Keterangan Tambahan
                        </label>
                        <textarea class="form-control" name="keterangan" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Keterangan tambahan (opsional)"></textarea>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_service" id="latitude_service">
                    <input type="hidden" name="longitude_service" id="longitude_service">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-circle-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Proses Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Form Selesai Service -->
@if($kendaraan->serviceAktif)
<div class="modal fade" id="modalSelesaiService" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered" style="margin: 1rem; max-width: calc(100% - 2rem);">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); max-height: 85vh; display: flex; flex-direction: column;">
            <div class="modal-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 20px 20px 0 0; flex-shrink: 0;">
                <h5 class="modal-title text-white fw-bold">
                    <ion-icon name="checkmark-circle-outline" style="font-size: 24px; vertical-align: middle;"></ion-icon>
                    Form Penyelesaian Service
                </h5>
                <button type="button" class="btn-close btn-close-white" data-dismiss="modal"></button>
            </div>
            <form action="{{ route('service.prosesSelesai', Crypt::encrypt($kendaraan->serviceAktif->id)) }}" method="POST" id="formSelesaiService" enctype="multipart/form-data" style="display: flex; flex-direction: column; flex: 1; overflow: hidden;">
                @csrf
                <div class="modal-body" style="padding: 20px; overflow-y: auto; flex: 1;">
                    <!-- Info Service Aktif -->
                    <div class="alert" style="border-radius: 15px; border-left: 4px solid #10b981; background: #d1fae5;">
                        <div class="d-flex align-items-start">
                            <ion-icon name="information-circle" style="font-size: 2.5rem; color: #059669; margin-right: 15px;"></ion-icon>
                            <div style="flex: 1;">
                                <strong style="color: #065f46; font-size: 1rem; display: block; margin-bottom: 8px;">Informasi Service</strong>
                                <div style="color: #047857; font-size: 0.9rem;">
                                    <div class="row">
                                        <div class="col-6">
                                            <small><strong>Kode:</strong> {{ $kendaraan->serviceAktif->kode_service }}</small><br>
                                            <small><strong>Jenis:</strong> {{ $kendaraan->serviceAktif->jenis_service }}</small><br>
                                            <small><strong>Bengkel:</strong> {{ $kendaraan->serviceAktif->bengkel ?? '-' }}</small>
                                        </div>
                                        <div class="col-6">
                                            <small><strong>Waktu Masuk:</strong> {{ \Carbon\Carbon::parse($kendaraan->serviceAktif->waktu_service)->format('d/m/Y H:i') }}</small><br>
                                            <small><strong>Estimasi Biaya:</strong> Rp {{ number_format($kendaraan->serviceAktif->estimasi_biaya ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="calendar-outline"></ion-icon> Tanggal Selesai *
                            </label>
                            <input type="date" class="form-control" name="tanggal_selesai" required 
                                   value="{{ date('Y-m-d') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="time-outline"></ion-icon> Jam Selesai *
                            </label>
                            <input type="time" class="form-control" name="jam_selesai" required 
                                   value="{{ date('H:i') }}"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="speedometer-outline"></ion-icon> KM Setelah Service
                            </label>
                            <input type="number" class="form-control" name="km_selesai" min="0"
                                   placeholder="KM setelah service"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="cash-outline"></ion-icon> Biaya Akhir (Rp)
                            </label>
                            <input type="number" class="form-control" name="biaya_akhir" min="0" step="1000"
                                   value="{{ $kendaraan->serviceAktif->estimasi_biaya ?? 0 }}"
                                   placeholder="0"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="document-text-outline"></ion-icon> Pekerjaan Yang Telah Dilakukan *
                        </label>
                        <textarea class="form-control" name="pekerjaan_selesai" rows="3" required 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Daftar pekerjaan yang telah diselesaikan">{{ $kendaraan->serviceAktif->pekerjaan ?? '' }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="chatbox-outline"></ion-icon> Catatan Mekanik
                        </label>
                        <textarea class="form-control" name="catatan_mekanik" rows="2" 
                                  style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;"
                                  placeholder="Catatan atau rekomendasi dari mekanik"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="shield-checkmark-outline"></ion-icon> Kondisi Setelah Service *
                            </label>
                            <select class="form-select" name="kondisi_kendaraan" required 
                                    style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                                <option value="">-- Pilih Kondisi --</option>
                                <option value="Sangat Baik">Sangat Baik</option>
                                <option value="Baik">Baik</option>
                                <option value="Cukup Baik">Cukup Baik</option>
                                <option value="Perlu Perhatian">Perlu Perhatian</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">
                                <ion-icon name="person-outline"></ion-icon> PIC / Mekanik
                            </label>
                            <input type="text" class="form-control" name="pic_selesai" 
                                   value="{{ $kendaraan->serviceAktif->pic ?? '' }}"
                                   placeholder="Nama mekanik"
                                   style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            <ion-icon name="image-outline"></ion-icon> Foto Kondisi Setelah Service *
                        </label>
                        <input type="file" class="form-control" name="foto_after" accept="image/*" id="foto_after_service" required
                               style="border-radius: 10px; border: 2px solid #e3e6f0; padding: 12px;">
                        <small class="text-muted d-block mt-1">Upload foto kondisi kendaraan setelah service (Max: 2MB)</small>
                        <div class="mt-2">
                            <img id="preview_foto_after_service" style="max-width: 200px; display: none; border-radius: 10px;" class="img-thumbnail">
                        </div>
                    </div>

                    <!-- Hidden GPS Fields -->
                    <input type="hidden" name="latitude_selesai" id="latitude_selesai">
                    <input type="hidden" name="longitude_selesai" id="longitude_selesai">
                </div>
                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 20px; flex-shrink: 0; background: white; border-radius: 0 0 20px 20px; box-shadow: 0 -2px 10px rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" 
                            style="border-radius: 10px; padding: 10px 20px; font-size: 14px;">
                        Batal
                    </button>
                    <button type="submit" class="btn btn-success" 
                            style="border-radius: 10px; padding: 10px 20px; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; font-size: 14px;">
                        <ion-icon name="checkmark-done-outline" style="font-size: 18px; vertical-align: middle;"></ion-icon>
                        Selesaikan Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
    // ========================================
    // DARK MODE FUNCTIONALITY
    // ========================================
    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('themeIcon');
        const htmlElement = document.documentElement;
        
        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        htmlElement.setAttribute('data-theme', savedTheme);
        updateIcon(savedTheme);
        
        // Toggle theme
        themeToggle.addEventListener('click', function() {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
            
            // Add animation
            themeToggle.style.transform = 'rotate(360deg)';
            setTimeout(() => {
                themeToggle.style.transform = 'rotate(0deg)';
            }, 300);
        });
        
        function updateIcon(theme) {
            if (theme === 'dark') {
                themeIcon.setAttribute('name', 'sunny-outline');
            } else {
                themeIcon.setAttribute('name', 'moon-outline');
            }
        }
    });

    // ========================================
    // SIMPLE JAVASCRIPT FOR KENDARAAN
    // ========================================
    $(document).ready(function() {
        console.log('✅ Kendaraan page loaded');
        
        // Debug: Check if button exists
        console.log('🔍 Tombol Selesai Service:', $('.btn-selesai-service-trigger').length);
        console.log('🔍 Modal Selesai Service:', $('#modalSelesaiService').length);
        
        // Manual click handler untuk tombol selesai service
        $('.btn-selesai-service-trigger').on('click', function(e) {
            e.preventDefault();
            console.log('🟢 Tombol Selesai Service diklik!');
            var modalEl = document.getElementById('modalSelesaiService');
            if (modalEl) {
                $(modalEl).fadeIn().addClass('show').css('display', 'block');
                $('body').addClass('modal-open');
                if ($('.modal-backdrop').length === 0) {
                    $('body').append('<div class="modal-backdrop fade show"></div>');
                }
                console.log('✅ Modal ditampilkan');
            } else {
                console.error('❌ Modal tidak ditemukan');
            }
        });
        
        // Close modal handler
        $('[data-dismiss="modal"]').on('click', function() {
            console.log('🔴 Close button clicked');
            $(this).closest('.modal').fadeOut().removeClass('show');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            
            var modal = document.getElementById('modalReturnPinjam');
            if (modal) {
                modal.style.display = 'none';
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                
                // Remove backdrop
                var backdrop = document.getElementById('returnModalBackdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                
                console.log('✅ Modal closed');
            }
        });
        
        // Scroll Navigation for Kendaraan Cards
        const scrollContainer = document.getElementById('kendaraanScrollContainer');
        const scrollLeftBtn = document.getElementById('scrollLeft');
        const scrollRightBtn = document.getElementById('scrollRight');
        
        console.log('Scroll elements:', {
            container: scrollContainer,
            leftBtn: scrollLeftBtn,
            rightBtn: scrollRightBtn
        });
        
        if (scrollContainer && scrollLeftBtn && scrollRightBtn) {
            console.log('✅ Scroll buttons found!');
            
            // Scroll left
            scrollLeftBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('⬅️ Left arrow clicked');
                scrollContainer.scrollBy({
                    left: -300,
                    behavior: 'smooth'
                });
            });
            
            // Scroll right
            scrollRightBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('➡️ Right arrow clicked');
                scrollContainer.scrollBy({
                    left: 300,
                    behavior: 'smooth'
                });
            });
            
            // Hide/show arrows based on scroll position
            function updateArrows() {
                const scrollPos = scrollContainer.scrollLeft;
                const maxScroll = scrollContainer.scrollWidth - scrollContainer.clientWidth;
                
                console.log('Scroll position:', scrollPos, '/', maxScroll);
                
                // Hide left arrow at start
                scrollLeftBtn.style.opacity = scrollPos <= 0 ? '0.3' : '1';
                scrollLeftBtn.style.pointerEvents = scrollPos <= 0 ? 'none' : 'auto';
                
                // Hide right arrow at end
                scrollRightBtn.style.opacity = scrollPos >= maxScroll - 5 ? '0.3' : '1';
                scrollRightBtn.style.pointerEvents = scrollPos >= maxScroll - 5 ? 'none' : 'auto';
            }
            
            scrollContainer.addEventListener('scroll', updateArrows);
            updateArrows(); // Initial check
            
            console.log('✅ Scroll handlers attached');
        } else {
            console.error('❌ Scroll elements not found!');
        }
        
        // Preview Foto Identitas untuk modal pinjam
        $('#foto_identitas_pinjam').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validasi ukuran file (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar!',
                        text: 'Ukuran file maksimal 2MB'
                    });
                    $(this).val('');
                    $('#preview_container_pinjam').hide();
                    return;
                }

                // Validasi tipe file
                if (!file.type.match('image.*')) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Tidak Valid!',
                        text: 'Hanya file gambar yang diperbolehkan'
                    });
                    $(this).val('');
                    $('#preview_container_pinjam').hide();
                    return;
                }

                // Preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_identitas_pinjam').attr('src', e.target.result);
                    $('#preview_container_pinjam').show();
                };
                reader.readAsDataURL(file);
            }
        });
        
        // Auto-detect GPS saat modal pinjam dibuka
        $('#modalPinjamKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    $('#latitude_pinjam').val(lat);
                    $('#longitude_pinjam').val(lng);
                    $('#lokasi_display_pinjam').val(lat.toFixed(4) + ', ' + lng.toFixed(4));
                    console.log('GPS detected:', lat, lng);
                }, function(error) {
                    $('#lokasi_display_pinjam').val('GPS tidak tersedia');
                    console.log('GPS error:', error);
                });
            }
        });
        
        // HAPUS KODE DUPLIKAT DI SINI - SUDAH ADA DI ATAS
```
        
        // Manual trigger modal untuk debugging
        $('a[data-toggle="modal"]').on('click', function(e) {
            e.preventDefault();
            var targetModal = $(this).data('target');
            console.log('Modal button clicked:', targetModal);
            
            // Coba manual trigger
            if (typeof bootstrap !== 'undefined') {
                var myModal = new bootstrap.Modal(document.querySelector(targetModal));
                myModal.show();
                console.log('Modal triggered via Bootstrap 5');
            } else {
                // Fallback ke jQuery (Bootstrap 4)
                $(targetModal).modal('show');
                console.log('Modal triggered via jQuery');
            }
        });
        // Update card position
        updateCardPosition();
        
        // Prev button - Event delegation
        $(document).on('click', '#prevBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== PREV CLICKED ===');
            console.log('Current index:', currentIndex);
            console.log('Total kendaraan:', totalKendaraan);
            
            if (currentIndex > 0) {
                currentIndex--;
                console.log('New index:', currentIndex);
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    console.log('Navigating to prev kendaraan...');
                    navigateToKendaraan();
                }, 300);
            } else {
                console.log('Already at first kendaraan');
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah di awal',
                    text: 'Ini adalah kendaraan pertama',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
        
        // Next button - Event delegation
        $(document).on('click', '#nextBtn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            console.log('=== NEXT CLICKED ===');
            console.log('Current index:', currentIndex);
            console.log('Total kendaraan:', totalKendaraan);
            
            if (currentIndex < totalKendaraan - 1) {
                currentIndex++;
                console.log('New index:', currentIndex);
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    console.log('Navigating to next kendaraan...');
                    navigateToKendaraan();
                }, 300);
            } else {
                console.log('Already at last kendaraan');
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah di akhir',
                    text: 'Ini adalah kendaraan terakhir',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
        
        // Indicator dots click - Event delegation
        $(document).on('click', '.indicator-dot', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var newIndex = $(this).data('index');
            console.log('=== DOT CLICKED ===');
            console.log('Current index:', currentIndex);
            console.log('New index:', newIndex);
            
            if (newIndex !== currentIndex) {
                currentIndex = newIndex;
                updateCardPosition();
                // Navigate after animation
                setTimeout(function() {
                    navigateToKendaraan();
                }, 300);
            } else {
                console.log('Same kendaraan, no action needed');
            }
        });
        
        // Touch swipe support
        let touchStartX = 0;
        let touchEndX = 0;
        let touchStartY = 0;
        let touchEndY = 0;
        
        $(document).on('touchstart', '#kendaraanCardsContainer', function(e) {
            touchStartX = e.originalEvent.changedTouches[0].screenX;
            touchStartY = e.originalEvent.changedTouches[0].screenY;
            console.log('Touch start X:', touchStartX, 'Y:', touchStartY);
        });
        
        $(document).on('touchend', '#kendaraanCardsContainer', function(e) {
            touchEndX = e.originalEvent.changedTouches[0].screenX;
            touchEndY = e.originalEvent.changedTouches[0].screenY;
            console.log('Touch end X:', touchEndX, 'Y:', touchEndY);
            handleSwipe();
        });
        
        function handleSwipe() {
            const deltaX = touchEndX - touchStartX;
            const deltaY = Math.abs(touchEndY - touchStartY);
            const minSwipeDistance = 50;
            
            console.log('=== SWIPE DETECTED ===');
            console.log('Delta X:', deltaX);
            console.log('Delta Y:', deltaY);
            
            // Only trigger if horizontal swipe is dominant (not vertical scroll)
            if (Math.abs(deltaX) > minSwipeDistance && Math.abs(deltaX) > deltaY) {
                if (deltaX < 0) {
                    // Swipe left (next)
                    console.log('Swipe LEFT detected - Going to NEXT');
                    if (currentIndex < totalKendaraan - 1) {
                        currentIndex++;
                        console.log('Moving to index:', currentIndex);
                        updateCardPosition();
                        setTimeout(function() {
                            navigateToKendaraan();
                        }, 300);
                    } else {
                        console.log('Already at last kendaraan');
                        alert('Sudah di kendaraan terakhir');
                    }
                } else {
                    // Swipe right (prev)
                    console.log('Swipe RIGHT detected - Going to PREV');
                    if (currentIndex > 0) {
                        currentIndex--;
                        console.log('Moving to index:', currentIndex);
                        updateCardPosition();
                        setTimeout(function() {
                            navigateToKendaraan();
                        }, 300);
                    } else {
                        console.log('Already at first kendaraan');
                        alert('Sudah di kendaraan pertama');
                    }
                }
            } else {
                console.log('Swipe not strong enough or vertical scroll detected');
            }
        }
        
        function updateCardPosition() {
            console.log('=== UPDATE CARD POSITION ===');
            console.log('Moving to index:', currentIndex);
            console.log('Total kendaraan:', totalKendaraan);
            
            // Calculate offset based on container width
            const container = document.getElementById('kendaraanCardsContainer');
            const containerWidth = container ? container.offsetWidth : 0;
            const offset = -currentIndex * 100;
            
            console.log('Container width:', containerWidth);
            console.log('Offset:', offset + '%');
            console.log('Transform will be:', `translateX(${offset}%)`);
            
            const $cards = $('#kendaraanCards');
            console.log('Cards element:', $cards);
            console.log('Cards current transform:', $cards.css('transform'));
            
            // Apply transform
            $cards.css({
                'transform': `translateX(${offset}%)`,
                'transition': 'transform 0.3s ease-out',
                '-webkit-transform': `translateX(${offset}%)`,
                '-moz-transform': `translateX(${offset}%)`,
                '-ms-transform': `translateX(${offset}%)`
            });
            
            // Force repaint
            void $cards[0].offsetHeight;
            
            console.log('Transform applied. New transform:', $cards.css('transform'));
            
            // Update active card border
            $('.kendaraan-card').removeClass('active').find('.card').css('border', 'none');
            $(`.kendaraan-card[data-index="${currentIndex}"]`).addClass('active')
                .find('.card').css('border', '3px solid #667eea');
            
            // Update indicators
            $('.indicator-dot').css('background', '#ddd');
            $(`.indicator-dot[data-index="${currentIndex}"]`).css('background', '#667eea');
            
            // Update prev/next button state with visual feedback
            if (currentIndex === 0) {
                $('#prevBtn').prop('disabled', true).css('opacity', '0.3');
            } else {
                $('#prevBtn').prop('disabled', false).css('opacity', '1');
            }
            
            if (currentIndex === totalKendaraan - 1) {
                $('#nextBtn').prop('disabled', true).css('opacity', '0.3');
            } else {
                $('#nextBtn').prop('disabled', false).css('opacity', '1');
            }
            
            console.log('Card position updated successfully');
            console.log('Prev button disabled:', currentIndex === 0);
            console.log('Next button disabled:', currentIndex === totalKendaraan - 1);
            
            // Update debug panel
            $('#debugIndex').text(currentIndex);
            $('#debugTotal').text(totalKendaraan - 1);
            $('#debugTransform').text(`${offset}%`);
        }
        
        function navigateToKendaraan() {
            const kendaraan = allKendaraanData[currentIndex];
            const kendaraanId = kendaraan.id;
            
            console.log('=== NAVIGATING TO KENDARAAN ===');
            console.log('Index:', currentIndex);
            console.log('Kendaraan ID:', kendaraanId);
            console.log('Kendaraan Name:', kendaraan.nama_kendaraan);
            
            // Show loading
            Swal.fire({
                title: 'Memuat ' + kendaraan.nama_kendaraan,
                text: 'Mohon tunggu...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Navigate to index with kendaraan parameter
            const targetUrl = `{{ route('kendaraan.karyawan.index') }}?k=${kendaraanId}`;
            console.log('Navigating to:', targetUrl);
            window.location.href = targetUrl;
        }
        
        // Popup Foto Peminjaman
        $(document).on('click', '.foto-popup', function() {
            var fotoUrl = $(this).data('foto');
            var title = $(this).data('title');
            
            $('#modalFotoPeminjamanTitle').text(title);
            $('#modalFotoPeminjamanImage').attr('src', fotoUrl);
            $('#modalFotoPeminjaman').modal('show');
        });
        
        // Auto-detect GPS location saat modal keluar dibuka
        $('#modalKeluarKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#latitude_keluar').val(position.coords.latitude);
                    $('#longitude_keluar').val(position.coords.longitude);
                }, function(error) {
                    console.log('GPS error:', error);
                });
            }
        });
        
        // Auto-detect GPS location saat modal kembali dibuka
        $('#modalKembaliKendaraan').on('shown.modal', function() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    $('#latitude_kembali').val(position.coords.latitude);
                    $('#longitude_kembali').val(position.coords.longitude);
                }, function(error) {
                    console.log('GPS error:', error);
                });
            }
        });
        
        // Handle form submission dengan AJAX
        $('#formKeluarKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan data keluar kendaraan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kendaraan berhasil ditandai keluar',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman untuk update status
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
        
        // Handle form kembali submission dengan AJAX
        $('#formKembaliKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang menyimpan data kembali kendaraan',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Kendaraan berhasil ditandai kembali',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        // Reload halaman untuk update status dan hilangkan animasi
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMsg
                    });
                }
            });
        });
    });

    // ==================== SIGNATURE PAD SCRIPT ====================
    // GLOBAL SCOPE - Tersedia untuk semua kondisi
</script>

<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
<script>
    // Global variables
    var canvas = null;
    var signaturePad = null;
    
    // Test function
    function testCanvas() {
        console.log('🧪 TESTING CANVAS...');
        console.log('Canvas element:', canvas);
        console.log('SignaturePad object:', signaturePad);
        
        if (!canvas) {
            alert('❌ Canvas tidak ditemukan!');
            return;
        }
        
        if (!signaturePad) {
            alert('❌ SignaturePad tidak diinisialisasi!');
            return;
        }
        
        // Test manual draw
        var ctx = canvas.getContext('2d');
        ctx.beginPath();
        ctx.moveTo(50, 50);
        ctx.lineTo(150, 150);
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 3;
        ctx.stroke();
        
        console.log('✅ Manual draw berhasil!');
        alert('✅ Canvas OK! Coba gambar dengan mouse/jari sekarang!');
    }
    
    // Clear function
    function clearSignaturePinjam() {
        if (signaturePad) {
            signaturePad.clear();
            console.log('🗑️ Signature dibersihkan');
        } else {
            console.log('⚠️ SignaturePad belum diinisialisasi');
            alert('⚠️ SignaturePad belum diinisialisasi. Tunggu modal dibuka dulu!');
        }
    }
    
    // jQuery Ready
    $(document).ready(function() {
        console.log('✅ jQuery Ready - SignaturePad Script');
        console.log('✅ SignaturePad Library:', typeof SignaturePad !== 'undefined' ? 'LOADED' : 'NOT LOADED');
        
        // Initialize signature pad ketika modal dibuka
        $('#modalPinjamKendaraan').on('shown.bs.modal', function() {
            console.log('🚀 Modal PINJAM dibuka');
            
            setTimeout(function() {
                canvas = document.getElementById('signature-pad-pinjam');
                
                if (!canvas) {
                    console.error('❌ Canvas tidak ditemukan!');
                    alert('❌ ERROR: Canvas element tidak ditemukan!');
                    return;
                }
                
                console.log('✅ Canvas ditemukan');
                console.log('📏 Canvas offsetWidth:', canvas.offsetWidth, 'offsetHeight:', canvas.offsetHeight);
                console.log('📍 Canvas position:', canvas.getBoundingClientRect());
                
                // Cek element di atas canvas
                var rect = canvas.getBoundingClientRect();
                var elementAtPoint = document.elementFromPoint(rect.left + rect.width/2, rect.top + rect.height/2);
                console.log('🎯 Element di tengah canvas:', elementAtPoint);
                console.log('🎯 Apakah element = canvas?', elementAtPoint === canvas);
                
                // Function to resize canvas
                function resizeCanvas() {
                    var ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    var ctx = canvas.getContext("2d");
                    ctx.scale(ratio, ratio);
                    
                    // Fill white background
                    ctx.fillStyle = 'white';
                    ctx.fillRect(0, 0, canvas.width, canvas.height);
                    
                    console.log('✅ Canvas di-resize:', canvas.width, 'x', canvas.height, 'ratio:', ratio);
                    
                    if (signaturePad) {
                        signaturePad.clear();
                    }
                }
                
                // Initialize SignaturePad
                try {
                    signaturePad = new SignaturePad(canvas, {
                        backgroundColor: 'rgb(255, 255, 255)',
                        penColor: 'rgb(0, 0, 0)',
                        minWidth: 1,
                        maxWidth: 3,
                        velocityFilterWeight: 0.7,
                        throttle: 0
                    });
                    
                    console.log('✅ SignaturePad berhasil dibuat');
                    console.log('✅ SignaturePad isEmpty:', signaturePad.isEmpty());
                    
                    // Event listeners
                    canvas.addEventListener('mousedown', function(e) {
                        console.log('🖱️ MOUSE DOWN at:', e.offsetX, e.offsetY);
                    });
                    
                    canvas.addEventListener('mousemove', function(e) {
                        if (e.buttons === 1) {
                            console.log('🖱️ MOUSE MOVE (dragging) at:', e.offsetX, e.offsetY);
                        }
                    });
                    
                    canvas.addEventListener('touchstart', function(e) {
                        console.log('👆 TOUCH START');
                        e.preventDefault();
                    }, { passive: false });
                    
                    // Resize
                    resizeCanvas();
                    window.addEventListener('resize', resizeCanvas);
                    
                    console.log('🎉 SignaturePad SIAP! Coba klik "Test Canvas" atau gambar langsung!');
                    
                } catch(error) {
                    console.error('❌ Error creating SignaturePad:', error);
                    alert('❌ ERROR: ' + error.message);
                }
                
            }, 500);
        });
        
        // Form submit handler
        $('#formPinjamKendaraan').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form submit pinjam kendaraan');
            
            // Validate signature
            if (!signaturePad || signaturePad.isEmpty()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian!',
                    text: 'Tanda tangan peminjam harus diisi!'
                });
                return false;
            }
            
            // Get signature data and save to hidden field
            var signatureData = signaturePad.toDataURL();
            $('#signature').val(signatureData);
            
            console.log('✅ Signature data saved to hidden field');
            console.log('✅ Submitting form via AJAX...');
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengirim permohonan peminjaman',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Prepare FormData
            var formData = new FormData(this);
            
            // Submit via AJAX
            $.ajax({
                url: '{{ route("kendaraan.karyawan.submit.pinjam") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Submit berhasil:', response);
                    $('#modalPinjamKendaraan').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Permohonan peminjaman berhasil dikirim',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('❌ Submit gagal:', xhr);
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Show validation errors
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMsg
                    });
                }
            });
        });
        
        // ==================== RETURN PEMINJAMAN ====================
        // Button click handler untuk buka modal
        $('#btnOpenReturnModal').on('click', function() {
            var kendaraanId = $(this).data('kendaraan-id');
            var prosesId = $(this).data('proses-id');
            
            console.log('🔓 Button clicked:', kendaraanId, prosesId);
            
            // Set values
            $('#return_kendaraan_id').val(kendaraanId);
            $('#return_proses_id').val(prosesId);
            
            // Show modal - pure JavaScript
            var modal = document.getElementById('modalReturnPinjam');
            if (modal) {
                modal.style.display = 'block';
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                
                // Add backdrop
                if (!document.getElementById('returnModalBackdrop')) {
                    var backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    backdrop.id = 'returnModalBackdrop';
                    document.body.appendChild(backdrop);
                }
                
                console.log('✅ Modal displayed');
            }
        });
        
        // Handle return form submit
        $('#formReturnPinjam').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form return peminjaman submit');
            
            // Get GPS location with timeout
            if (navigator.geolocation) {
                var gpsTimeout = setTimeout(function() {
                    console.warn('⚠️ GPS timeout, submitting without location');
                    submitReturnForm();
                }, 3000); // 3 detik timeout untuk GPS
                
                navigator.geolocation.getCurrentPosition(function(position) {
                    clearTimeout(gpsTimeout);
                    $('#return_latitude').val(position.coords.latitude);
                    $('#return_longitude').val(position.coords.longitude);
                    console.log('✅ GPS location obtained');
                    submitReturnForm();
                }, function(error) {
                    clearTimeout(gpsTimeout);
                    console.warn('⚠️ GPS error:', error);
                    submitReturnForm(); // Submit anyway
                });
            } else {
                console.warn('⚠️ GPS not available');
                submitReturnForm();
            }
        });
        
        function submitReturnForm() {
            var formData = new FormData($('#formReturnPinjam')[0]);
            
            console.log('✅ Submitting return via AJAX...');
            
            // Tutup modal dulu (Bootstrap 4 style)
            var modal = document.getElementById('modalReturnPinjam');
            modal.style.display = 'none';
            document.body.classList.remove('modal-open');
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Langsung tampilkan sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Kendaraan berhasil dikembalikan',
                showConfirmButton: false,
                timer: 1500
            }).then(function() {
                window.location.reload();
            });
            
            // Kirim di background
            $.ajax({
                url: '{{ route("kendaraan.karyawan.submit.return.pinjam") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Return berhasil di backend:', response);
                },
                error: function(xhr) {
                    console.error('❌ Return gagal di backend:', xhr);
                }
            });
        }
        
        // ==================== SERVICE KENDARAAN ====================
        // Preview foto before service
        $('#foto_before_service').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_before_service').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_before_service').hide();
            }
        });

        // Get GPS Location for Service when modal opens
        $('#modalFormService').on('shown.bs.modal', function() {
            console.log('📍 Getting GPS location for service...');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    $('#latitude_service').val(latitude);
                    $('#longitude_service').val(longitude);
                    
                    console.log('✅ GPS location obtained:', latitude, longitude);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Terdeteksi!',
                        text: 'GPS berhasil mendapatkan koordinat bengkel',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, function(error) {
                    console.warn('⚠️ GPS error:', error);
                });
            }
        });

        // Form Service Submit
        $('#formService').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form service submit');
            
            var jenisService = $('select[name="jenis_service"]').val();
            var deskripsiKerusakan = $('textarea[name="deskripsi_kerusakan"]').val();
            
            if (!jenisService) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Jenis service harus dipilih!'
                });
                return false;
            }
            
            if (!deskripsiKerusakan.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Deskripsi kerusakan harus diisi!'
                });
                return false;
            }
            
            // Show loading
            Swal.fire({
                title: 'Memproses...',
                text: 'Sedang mengirim data service',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Prepare FormData
            var formData = new FormData(this);
            
            // Submit via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Service berhasil:', response);
                    $('#modalFormService').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Kendaraan berhasil diproses untuk service',
                        showConfirmButton: false,
                        timer: 1500
                    }).then(function() {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    console.error('❌ Service gagal:', xhr);
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMsg
                    });
                }
            });
        });
        
        // ==================== SELESAI SERVICE ====================
        // Preview foto after service
        $('#foto_after_service').change(function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview_foto_after_service').attr('src', e.target.result).show();
                }
                reader.readAsDataURL(file);
            } else {
                $('#preview_foto_after_service').hide();
            }
        });

        // Get GPS Location for Selesai Service when modal opens
        $('#modalSelesaiService').on('shown.bs.modal', function() {
            console.log('📍 Getting GPS location for selesai service...');
            
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    
                    $('#latitude_selesai').val(latitude);
                    $('#longitude_selesai').val(longitude);
                    
                    console.log('✅ GPS location obtained:', latitude, longitude);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Lokasi Terdeteksi!',
                        text: 'GPS berhasil mendapatkan koordinat',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }, function(error) {
                    console.warn('⚠️ GPS error:', error);
                });
            }
        });

        // Form Selesai Service Submit
        $('#formSelesaiService').on('submit', function(e) {
            e.preventDefault();
            
            console.log('📝 Form selesai service submit');
            
            var kondisi = $('#formSelesaiService select[name="kondisi_kendaraan"]').val();
            var pekerjaan = $('#formSelesaiService textarea[name="pekerjaan_selesai"]').val();
            
            if (!kondisi) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Kondisi kendaraan harus dipilih!'
                });
                return false;
            }
            
            if (!pekerjaan.trim()) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Peringatan',
                    text: 'Pekerjaan yang telah dilakukan harus diisi!'
                });
                return false;
            }
            
            // Tutup modal langsung
            $('#modalSelesaiService').fadeOut().removeClass('show');
            $('.modal-backdrop').remove();
            $('body').removeClass('modal-open');
            
            // Prepare FormData
            var formData = new FormData(this);
            
            // Submit via AJAX
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('✅ Selesai service berhasil:', response);
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: response.message || 'Service berhasil diselesaikan',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Reload langsung tanpa then
                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    console.error('❌ Selesai service gagal:', xhr);
                    var errorMsg = 'Terjadi kesalahan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        errorMsg = Object.values(errors).flat().join('<br>');
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        html: errorMsg
                    });
                }
            });
        });
    });
</script>
@endpush

@endsection
