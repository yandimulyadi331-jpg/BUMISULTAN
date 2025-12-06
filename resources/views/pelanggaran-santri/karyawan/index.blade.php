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
        --accent-color: #17a2b8;
        --accent-hover: #138496;
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
        padding-bottom: 80px;
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

    /* ========== SEARCH & FILTER CARD ========== */
    .search-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        margin-bottom: 20px;
    }

    .form-label {
        color: var(--text-secondary);
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 10px;
        display: block;
    }

    .form-control-neo,
    .form-select-neo {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 12px 18px;
        font-size: 0.95rem;
        color: var(--text-primary);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control-neo:focus,
    .form-select-neo:focus {
        outline: none;
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .form-control-neo::placeholder {
        color: var(--text-secondary);
        opacity: 0.6;
    }

    .btn-neo {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 700;
        font-size: 0.9rem;
        cursor: pointer;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
        color: var(--text-primary);
        display: inline-flex;
        align-items: center;
        gap: 8px;
        width: 100%;
        justify-content: center;
    }

    .btn-neo:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .btn-neo ion-icon {
        font-size: 18px;
    }

    /* ========== TABLE CONTAINER ========== */
    .table-container {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        overflow: hidden;
    }

    .table-neo {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 12px;
    }

    .table-neo thead th {
        color: var(--text-secondary);
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        padding: 12px;
        text-align: left;
        border-bottom: 2px solid var(--border-color);
    }

    .table-neo tbody tr {
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .table-neo tbody tr:hover {
        transform: translateY(-2px);
        box-shadow: 5px 5px 12px var(--shadow-dark),
                   -5px -5px 12px var(--shadow-light);
    }

    .table-neo tbody td {
        padding: 15px 12px;
        color: var(--text-primary);
        font-size: 0.9rem;
        vertical-align: middle;
    }

    .table-neo tbody tr td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .table-neo tbody tr td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    /* ========== SANTRI CARD (Mobile Style) ========== */
    .santri-card {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 15px;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .santri-card:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .santri-header {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 2px solid var(--border-color);
    }

    .avatar-container {
        flex-shrink: 0;
        margin-right: 15px;
    }

    .santri-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        cursor: pointer;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .santri-avatar:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .avatar-initial {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: var(--gradient-1);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.3rem;
        cursor: pointer;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .avatar-initial:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .santri-info {
        flex: 1;
    }

    .santri-info h5 {
        margin: 0 0 5px 0;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .santri-info small {
        color: var(--text-secondary);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .santri-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 15px;
    }

    .stat-item-neo {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        text-align: center;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .stat-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-bottom: 8px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .stat-value {
        font-size: 1.3rem;
        font-weight: 800;
        color: var(--text-primary);
    }

    .badge-status {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
    }

    .badge-aman {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
        box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
    }

    .badge-peringatan {
        background: linear-gradient(135deg, #ffc107, #ff9800);
        color: white;
        box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
    }

    .badge-bahaya {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
    }

    .action-buttons {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
    }

    .btn-action {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 12px;
        font-size: 0.85rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        cursor: pointer;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
        color: var(--text-primary);
    }

    .btn-action:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .btn-action ion-icon {
        font-size: 18px;
    }

    /* ========== MODAL STYLES ========== */
    .modal-photo {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.95);
        animation: fadeIn 0.3s;
        backdrop-filter: blur(10px);
    }

    .photo-container {
        position: relative;
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .photo-container img {
        max-width: 90%;
        max-height: 90vh;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    .close-photo {
        position: absolute;
        top: 20px;
        right: 30px;
        color: white;
        font-size: 40px;
        font-weight: bold;
        cursor: pointer;
        z-index: 10000;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .close-photo:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: rotate(90deg);
    }

    .modal-fade-bg {
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
    }

    .modal-dialog-bottom {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        margin: 0;
        max-width: 500px;
        width: 90%;
    }

    .modal-tambah {
        background: var(--bg-body);
        border-radius: 20px;
        max-height: 70vh;
        height: auto;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    body.dark-mode .modal-tambah {
        background: var(--bg-primary-dark);
    }

    .modal-header-custom {
        background: var(--gradient-1);
        color: white;
        padding: 16px 20px;
        border-radius: 20px 20px 0 0;
        position: sticky;
        top: 0;
        z-index: 10;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-shrink: 0;
    }

    .modal-header-custom h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
    }

    .btn-close-white {
        filter: brightness(0) invert(1);
        opacity: 1;
    }

    .modal-body-scrollable {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        padding: 16px 20px;
        max-height: calc(70vh - 120px);
    }

    .modal-body-scrollable::-webkit-scrollbar {
        width: 5px;
    }

    .modal-body-scrollable::-webkit-scrollbar-track {
        background: transparent;
    }

    .modal-body-scrollable::-webkit-scrollbar-thumb {
        background: var(--text-secondary);
        border-radius: 10px;
        opacity: 0.5;
    }

    .modal-body-scrollable::-webkit-scrollbar-thumb:hover {
        background: var(--text-primary);
    }

    .modal-footer-sticky {
        flex-shrink: 0;
        position: sticky;
        bottom: 0;
        background: var(--bg-body);
        z-index: 9;
        border-top: 1px solid var(--border-color);
        padding: 15px 20px;
        border-radius: 0 0 20px 20px;
    }

    body.dark-mode .modal-footer-sticky {
        background: var(--bg-primary-dark);
    }

    .form-control-custom {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 10px 15px;
        font-size: 0.9rem;
        color: var(--text-primary);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-control-custom:focus {
        outline: none;
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .form-control-custom[readonly] {
        opacity: 0.7;
    }

    .form-label {
        color: var(--text-secondary);
        font-weight: 700;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
        display: block;
    }

    .btn-submit {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 12px;
        color: var(--text-primary);
        font-weight: 700;
        width: 100%;
        font-size: 0.9rem;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-submit:active {
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .loading-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 9998;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(5px);
    }

    .loading-spinner {
        width: 60px;
        height: 60px;
        border: 6px solid rgba(255, 255, 255, 0.2);
        border-top: 6px solid #17a2b8;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

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

    @keyframes modalSlideUp {
        from {
            opacity: 0;
            transform: translate(-50%, -40%) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .modal.show .modal-dialog-bottom {
        animation: modalSlideUp 0.3s ease-out forwards;
    }

    .santri-card {
        animation: fadeInUp 0.5s ease-out forwards;
    }

    .santri-card:nth-child(1) { animation-delay: 0.05s; }
    .santri-card:nth-child(2) { animation-delay: 0.1s; }
    .santri-card:nth-child(3) { animation-delay: 0.15s; }
    .santri-card:nth-child(4) { animation-delay: 0.2s; }
    .santri-card:nth-child(5) { animation-delay: 0.25s; }

    .empty-state {
        background: var(--bg-primary);
        border-radius: 20px;
        padding: 50px 20px;
        text-align: center;
        box-shadow: 5px 5px 10px var(--shadow-dark),
                   -5px -5px 10px var(--shadow-light);
        margin: 20px 0;
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

    .pagination {
        margin-top: 25px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    .pagination .page-link {
        background: var(--bg-primary);
        border: none;
        border-radius: 10px;
        padding: 10px 15px;
        color: var(--text-primary);
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .pagination .page-link:active,
    .pagination .page-item.active .page-link {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .alert-info-neo {
        background: var(--bg-primary);
        border: none;
        border-radius: 12px;
        padding: 12px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    #previewContainer {
        text-align: center;
        padding: 12px;
        background: var(--bg-primary);
        border-radius: 12px;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    #previewImg {
        border-radius: 12px;
        max-width: 100%;
        height: auto;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    #previewImg {
        border-radius: 12px;
        box-shadow: 3px 3px 6px var(--shadow-dark),
                   -3px -3px 6px var(--shadow-light);
    }

    /* ========== RESPONSIVE ========== */
    @media (max-width: 576px) {
        .modal-dialog-bottom {
            max-width: 95%;
            width: 95%;
        }

        .modal-tambah {
            max-height: 80vh;
        }

        .modal-body-scrollable {
            max-height: calc(80vh - 120px);
            padding: 14px 16px;
        }

        .form-control-custom {
            padding: 9px 14px;
            font-size: 0.85rem;
        }

        .modal-header-custom h5 {
            font-size: 0.95rem;
        }
    }

    /* ========== TOAST NOTIFICATION ========== */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .toast-notification {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 16px 20px;
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3),
                   5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        display: flex;
        align-items: center;
        gap: 12px;
        animation: slideInRight 0.3s ease-out;
        position: relative;
        overflow: hidden;
    }

    .toast-notification::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
    }

    .toast-notification.success::before {
        background: linear-gradient(135deg, #28a745, #20c997);
    }

    .toast-notification.error::before {
        background: linear-gradient(135deg, #dc3545, #c82333);
    }

    .toast-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }

    .toast-notification.success .toast-icon {
        background: linear-gradient(135deg, #28a745, #20c997);
        color: white;
    }

    .toast-notification.error .toast-icon {
        background: linear-gradient(135deg, #dc3545, #c82333);
        color: white;
    }

    .toast-content {
        flex: 1;
    }

    .toast-title {
        font-weight: 700;
        font-size: 0.95rem;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .toast-message {
        font-size: 0.85rem;
        color: var(--text-secondary);
        line-height: 1.4;
    }

    .toast-close {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--bg-primary);
        box-shadow: 2px 2px 5px var(--shadow-dark),
                   -2px -2px 5px var(--shadow-light);
        flex-shrink: 0;
    }

    .toast-close:active {
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .toast-close ion-icon {
        font-size: 18px;
        color: var(--text-secondary);
    }

    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    .toast-notification.hiding {
        animation: slideOutRight 0.3s ease-out forwards;
    }

    @media (max-width: 576px) {
        .toast-notification {
            min-width: 280px;
            max-width: calc(100vw - 40px);
        }

        .toast-container {
            right: 10px;
            left: 10px;
        }
    }
</style>

<!-- Toast Container -->
<div class="toast-container" id="toastContainer"></div>

<!-- HEADER -->
<div id="header-section">
    <div class="header-content">
        <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Pelanggaran Santri</h3>
            <p>Data & Input Pelanggaran</p>
        </div>
    </div>
</div>

<!-- CONTENT -->
<div id="content-section">
    <!-- Search & Filter Card -->
    <div class="search-card">
        <form method="GET" id="filterForm">
            <div class="mb-3">
                <label class="form-label">Cari Santri</label>
                <input type="text" name="search" class="form-control-neo" 
                       placeholder="Ketik nama santri atau NIK..." value="{{ request('search') }}">
            </div>
            <div class="row g-3">
                <div class="col-7">
                    <label class="form-label">Status</label>
                    <select name="status_santri" class="form-select-neo">
                        <option value="">Semua Status</option>
                        <option value="aktif" {{ request('status_santri')=='aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status_santri')=='nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>
                <div class="col-5">
                    <label class="form-label" style="opacity: 0;">Filter</label>
                    <button type="submit" class="btn-neo">
                        <ion-icon name="search-outline"></ion-icon>
                        <span>Filter</span>
                    </button>
                </div>
            </div>
        </form>
    </div>


    <!-- List Santri -->
    <div id="santriList">
        @forelse($santriList as $santri)
        <div class="santri-card" id="santri-card-{{ $santri->id }}">
            <div class="santri-header">
                <div class="avatar-container">
                    @if($santri->foto && file_exists(public_path('storage/santri/' . $santri->foto)))
                        <img src="{{ asset('storage/santri/' . $santri->foto) }}" 
                             alt="{{ $santri->nama_lengkap }}" 
                             class="santri-avatar"
                             onclick="showPhoto('{{ asset('storage/santri/' . $santri->foto) }}', '{{ $santri->nama_lengkap }}', '{{ $santri->nik }}')">
                    @else
                        <div class="avatar-initial" 
                             onclick="showPhoto('', '{{ $santri->nama_lengkap }}', '{{ $santri->nik }}')">
                            {{ strtoupper(substr($santri->nama_lengkap, 0, 2)) }}
                        </div>
                    @endif
                </div>
                <div class="santri-info">
                    <h5>{{ $santri->nama_lengkap }}</h5>
                    <small>
                        <ion-icon name="card-outline"></ion-icon> 
                        {{ $santri->nik ?? 'No NIK' }}
                    </small>
                </div>
            </div>

            <div class="santri-stats">
                <div class="stat-item-neo">
                    <div class="stat-label">Total Pelanggaran</div>
                    <div class="stat-value" id="total-{{ $santri->id }}">{{ $santri->total_pelanggaran }}x</div>
                </div>
                <div class="stat-item-neo">
                    <div class="stat-label">Total Point</div>
                    <div class="stat-value" id="point-{{ $santri->id }}">{{ $santri->total_point }}</div>
                </div>
            </div>

            <div class="mb-3">
                <div class="badge-status 
                    @if($santri->total_point < 5) badge-aman
                    @elseif($santri->total_point < 8) badge-peringatan
                    @else badge-bahaya
                    @endif" id="status-{{ $santri->id }}">
                    {{ $santri->status_info['status'] }}
                </div>
            </div>

            <div class="action-buttons">
                <button class="btn-action" 
                        onclick="openTambahModal({{ $santri->id }}, '{{ $santri->nama_lengkap }}', '{{ $santri->nik }}')">
                    <ion-icon name="add-circle-outline"></ion-icon> Tambah
                </button>
                <a href="{{ route('pelanggaran-santri.karyawan.show', $santri->id) }}" 
                   class="btn-action">
                    <ion-icon name="eye-outline"></ion-icon> Detail
                </a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <ion-icon name="clipboard-outline"></ion-icon>
            <h5>Tidak Ada Data</h5>
            <p>Belum ada data santri yang ditemukan</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($santriList->hasPages())
    <div class="pagination">
        {{ $santriList->links() }}
    </div>
    @endif
</div>


<!-- Modal Photo -->
<div class="modal-photo" id="modalPhoto" onclick="closePhoto()">
    <span class="close-photo" onclick="closePhoto()">&times;</span>
    <div class="photo-container">
        <div style="text-align: center;">
            <img id="photoImage" src="" alt="Foto Santri">
            <div style="color: white; margin-top: 20px;">
                <h4 id="photoNama" style="margin-bottom: 5px;"></h4>
                <p id="photoNik" style="opacity: 0.8;"></p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Pelanggaran -->
<div class="modal fade" id="modalTambah" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-bottom">
        <div class="modal-content modal-tambah">
            <div class="modal-header-custom">
                <h5><ion-icon name="add-circle-outline"></ion-icon> Tambah Pelanggaran</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formTambah" enctype="multipart/form-data">
                @csrf
                <div class="modal-body-scrollable">
                    <input type="hidden" name="user_id" id="user_id">
                    <input type="hidden" name="nama_santri" id="nama_santri">
                    <input type="hidden" name="nik_santri" id="nik_santri">

                    <div class="mb-3">
                        <label class="form-label">Santri</label>
                        <input type="text" class="form-control-custom" id="display_nama_santri" readonly>
                    </div>

                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_pelanggaran" class="form-control-custom" 
                                   value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Point <span class="text-danger">*</span></label>
                            <input type="number" name="point" class="form-control-custom" 
                                   value="1" min="1" max="10" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Foto Pelanggaran</label>
                        <input type="file" name="foto" class="form-control-custom" 
                               accept="image/*" onchange="previewImage(this)">
                        <small class="text-muted d-block mt-2" style="font-size: 0.8rem;">Max 5MB (JPG, PNG)</small>
                    </div>

                    <div class="mb-3" id="previewContainer" style="display: none;">
                        <img id="previewImg" src="" class="img-thumbnail" style="max-height: 200px; border-radius: 12px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea name="keterangan" class="form-control-custom" rows="5" 
                                  placeholder="Jelaskan detail pelanggaran..." required></textarea>
                    </div>

                    <div class="alert-info-neo">
                        <strong>ℹ️ Info:</strong> Point 1-4: Ringan | 5-7: Sedang | 8-10: Berat
                    </div>
                </div>
                <div class="modal-footer-sticky">
                    <button type="submit" class="btn-submit">
                        <ion-icon name="save-outline"></ion-icon> Simpan Pelanggaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

@endsection

@push('myscript')
<script>
// Toast Notification Function
function showToast(type, title, message) {
    const container = document.getElementById('toastContainer');
    
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    
    const iconName = type === 'success' ? 'checkmark-circle' : 
                     type === 'error' ? 'close-circle' : 'alert-circle';
    
    toast.innerHTML = `
        <div class="toast-icon">
            <ion-icon name="${iconName}"></ion-icon>
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        <div class="toast-close" onclick="closeToast(this)">
            <ion-icon name="close-outline"></ion-icon>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        closeToast(toast.querySelector('.toast-close'));
    }, 5000);
}

function closeToast(btn) {
    const toast = btn.closest('.toast-notification');
    toast.classList.add('hiding');
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Show photo modal
function showPhoto(src, nama, nik) {
    if (src) {
        document.getElementById('photoImage').src = src;
        document.getElementById('photoImage').style.display = 'block';
    } else {
        document.getElementById('photoImage').style.display = 'none';
    }
    document.getElementById('photoNama').textContent = nama;
    document.getElementById('photoNik').textContent = nik || 'No NIK';
    document.getElementById('modalPhoto').style.display = 'flex';
}

// Close photo modal
function closePhoto() {
    document.getElementById('modalPhoto').style.display = 'none';
}

// ESC key to close
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closePhoto();
    }
});

// Open tambah modal
function openTambahModal(userId, namaSantri, nikSantri) {
    document.getElementById('user_id').value = userId;
    document.getElementById('nama_santri').value = namaSantri;
    document.getElementById('nik_santri').value = nikSantri;
    document.getElementById('display_nama_santri').value = namaSantri + ' (' + (nikSantri || 'No NIK') + ')';
    
    // Reset form
    document.getElementById('formTambah').reset();
    document.getElementById('user_id').value = userId;
    document.getElementById('nama_santri').value = namaSantri;
    document.getElementById('nik_santri').value = nikSantri;
    document.getElementById('display_nama_santri').value = namaSantri + ' (' + (nikSantri || 'No NIK') + ')';
    document.getElementById('previewContainer').style.display = 'none';
    
    // Set tanggal hari ini
    var today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tanggal_pelanggaran"]').value = today;
    
    // Show modal
    var modalElement = document.getElementById('modalTambah');
    var modal = new bootstrap.Modal(modalElement);
    modal.show();
}

// Preview image
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('previewContainer').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('previewContainer').style.display = 'none';
    }
}

// Submit form via AJAX
document.getElementById('formTambah').addEventListener('submit', function(e) {
    e.preventDefault();
    
    console.log('Form submitted');
    
    var formData = new FormData(this);
    var loadingOverlay = document.getElementById('loadingOverlay');
    
    // Debug: Check form data
    console.log('User ID:', formData.get('user_id'));
    console.log('Tanggal:', formData.get('tanggal_pelanggaran'));
    console.log('Point:', formData.get('point'));
    console.log('Keterangan:', formData.get('keterangan'));
    
    // Show loading
    loadingOverlay.style.display = 'flex';
    
    fetch('{{ route("pelanggaran-santri.karyawan.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => {
                throw err;
            });
        }
        return response.json();
    })
    .then(data => {
        loadingOverlay.style.display = 'none';
        console.log('Response data:', data);
        
        if (data.success) {
            // Update UI tanpa refresh
            var userId = document.getElementById('user_id').value;
            document.getElementById('total-' + userId).textContent = data.data.total_pelanggaran + 'x';
            document.getElementById('point-' + userId).textContent = data.data.total_point;
            
            // Update badge status
            var statusElement = document.getElementById('status-' + userId);
            statusElement.textContent = data.data.status_info.status;
            statusElement.className = 'badge-status';
            
            if (data.data.total_point < 5) {
                statusElement.classList.add('badge-aman');
            } else if (data.data.total_point < 8) {
                statusElement.classList.add('badge-peringatan');
            } else {
                statusElement.classList.add('badge-bahaya');
            }
            
            // Close modal manually
            var modalElement = document.getElementById('modalTambah');
            modalElement.classList.remove('show');
            modalElement.style.display = 'none';
            modalElement.setAttribute('aria-hidden', 'true');
            modalElement.removeAttribute('aria-modal');
            modalElement.removeAttribute('role');
            
            // Remove backdrop
            var backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            
            // Remove modal-open class from body
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
            
            // Show success message with toast
            showToast('success', 'Berhasil!', data.message);
            
            // Reset form
            document.getElementById('formTambah').reset();
            document.getElementById('previewContainer').style.display = 'none';
        } else {
            showToast('error', 'Gagal!', data.message || 'Terjadi kesalahan');
        }
    })
    .catch(error => {
        loadingOverlay.style.display = 'none';
        console.error('Error:', error);
        
        if (error.errors) {
            // Validation errors
            let errorMsg = '';
            Object.keys(error.errors).forEach(key => {
                errorMsg += error.errors[key][0] + '<br>';
            });
            showToast('error', 'Validasi Gagal', errorMsg);
        } else if (error.message) {
            showToast('error', 'Error', error.message);
        } else {
            showToast('error', 'Error', 'Terjadi kesalahan saat menyimpan data');
        }
    });
});
</script>
@endpush
