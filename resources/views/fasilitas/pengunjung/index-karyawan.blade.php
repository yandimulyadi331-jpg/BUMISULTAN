@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            /* Neomorphic Colors */
            --bg-body-light: #e0e5ec;
            --bg-primary-light: #e0e5ec;
            --shadow-dark-light: #a3b1c6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2d3748;
            --text-secondary-light: #718096;
            --border-light: rgba(0, 0, 0, 0.08);

            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #171923;
            --shadow-light-dark: #3f4c63;
            --text-primary-dark: #f7fafc;
            --text-secondary-dark: #a0aec0;
            --border-dark: rgba(255, 255, 255, 0.08);
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

        .stats-row > *,
        .pengunjung-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }

        .stat-box:nth-child(1) { animation-delay: 0.1s; }
        .stat-box:nth-child(2) { animation-delay: 0.2s; }
        .stat-box:nth-child(3) { animation-delay: 0.3s; }
        .stat-box:nth-child(4) { animation-delay: 0.4s; }

        /* ========== HEADER ========== */
        #header-section {
            height: auto;
            padding: 25px 20px;
            position: relative;
            background: var(--bg-primary);
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 20px;
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
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            font-size: 24px;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        }

        .back-btn:active {
            transform: scale(0.95);
        }

        #header-title {
            text-align: center;
            color: #ffffff;
            margin-top: 10px;
        }

        #header-title h3 {
            font-weight: 800;
            margin: 0;
            font-size: 1.6rem;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
            letter-spacing: -0.5px;
        }

        #header-title p {
            margin: 8px 0 0 0;
            opacity: 0.95;
            font-size: 0.95rem;
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .stat-box {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 15px 10px;
            text-align: center;
            color: var(--text-primary);
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 7px 7px 15px var(--shadow-dark),
                       -7px -7px 15px var(--shadow-light);
        }

        .stat-box:active {
            transform: scale(0.98);
        }

        .stat-box h6 {
            margin: 0;
            font-size: 0.65rem;
            opacity: 0.9;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-box h3 {
            margin: 8px 0 0 0;
            font-weight: 900;
            font-size: 1.8rem;
            line-height: 1;
        }

        .btn-checkin {
            background: var(--bg-primary);
            color: var(--text-primary);
            border: 3px solid transparent;
            background-clip: padding-box;
            border-radius: 16px;
            padding: 16px 24px;
            width: 100%;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            position: relative;
        }

        .btn-checkin::before {
            content: '';
            position: absolute;
            top: -3px;
            left: -3px;
            right: -3px;
            bottom: -3px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border-radius: 16px;
            z-index: -1;
        }

        .btn-checkin:hover {
            transform: translateY(-2px);
            box-shadow: 7px 7px 15px var(--shadow-dark),
                       -7px -7px 15px var(--shadow-light);
        }

        .btn-checkin:hover::before {
            background: linear-gradient(135deg, #764ba2 0%, #f093fb 50%, #667eea 100%);
        }

        .btn-checkin:active {
            transform: scale(0.98);
        }

        .pengunjung-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .pengunjung-card:hover {
            box-shadow: 12px 12px 24px var(--shadow-dark),
                       -12px -12px 24px var(--shadow-light);
            transform: translateY(-3px);
        }

        .pengunjung-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .pengunjung-foto {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .pengunjung-info {
            flex: 1;
            margin-left: 12px;
        }

        .pengunjung-nama {
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            font-size: 1rem;
            letter-spacing: -0.3px;
        }

        .pengunjung-detail {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin: 4px 0;
            font-weight: 500;
        }

        .badge-status {
            padding: 6px 14px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        .badge-checkin {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .badge-checkout {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }

        .pengunjung-actions {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }

        .btn-action {
            flex: 1;
            padding: 10px 14px;
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-checkout-action {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #000;
            box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
        }

        .btn-checkout-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(255, 193, 7, 0.4);
        }

        .btn-detail {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(23, 162, 184, 0.3);
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(23, 162, 184, 0.4);
        }

        .btn-action:active {
            transform: scale(0.95);
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state ion-icon {
            font-size: 100px;
            opacity: 0.25;
            color: var(--text-secondary);
        }

        .empty-state h4 {
            font-weight: 700;
            font-size: 1.3rem;
            margin: 15px 0 10px 0;
            color: var(--text-primary);
        }

        .empty-state p {
            opacity: 0.8;
            font-size: 0.95rem;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        #content-section {
            padding: 20px;
            margin-bottom: 80px;
        }

        /* ========== GLASSMORPHISM TABLE ========== */
        .section-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 30px 0 15px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 0;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08),
                       inset 0 1px 0 rgba(255, 255, 255, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.3);
            margin-bottom: 20px;
        }

        body.dark-mode .table-container {
            background: rgba(45, 55, 72, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-glass {
            width: 100%;
            margin: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-glass thead th {
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            padding: 18px 15px;
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            text-align: left;
            white-space: nowrap;
        }

        body.dark-mode .table-glass thead th {
            background: rgba(45, 55, 72, 0.7);
            border-bottom: 2px solid rgba(255, 255, 255, 0.1);
        }

        .table-glass tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid rgba(0, 0, 0, 0.03);
        }

        body.dark-mode .table-glass tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .table-glass tbody tr:hover {
            background: rgba(255, 255, 255, 0.5);
        }

        body.dark-mode .table-glass tbody tr:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .table-glass tbody td {
            padding: 16px 15px;
            font-size: 0.85rem;
            color: var(--text-primary);
            font-weight: 500;
            vertical-align: middle;
        }

        /* Avatar in Table */
        .table-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 2px 2px 4px var(--shadow-dark),
                       -2px -2px 4px var(--shadow-light);
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .table-avatar:hover {
            transform: scale(1.15);
        }

        .table-avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--bg-primary);
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table-name-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .table-name-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .table-name {
            font-weight: 700;
            font-size: 0.9rem;
            color: var(--text-primary);
        }

        .table-company {
            font-size: 0.75rem;
            color: var(--text-secondary);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .table-time {
            font-size: 0.8rem;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .table-phone {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 4px;
        }

        /* Badge Styles */
        .badge-table {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            display: inline-block;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .badge-checkin {
            background: rgba(239, 68, 68, 0.2);
            color: #dc2626;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-checkout {
            background: rgba(66, 153, 225, 0.2);
            color: #2563eb;
            border: 1px solid rgba(66, 153, 225, 0.3);
        }

        /* Table Action Buttons */
        .table-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .btn-table {
            padding: 8px 14px;
            border-radius: 10px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-detail-table {
            background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
            color: white;
            box-shadow: 0 3px 10px rgba(23, 162, 184, 0.3);
        }

        .btn-detail-table:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(23, 162, 184, 0.4);
            color: white;
        }

        .btn-checkout-table {
            background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
            color: #000;
            box-shadow: 0 3px 10px rgba(255, 193, 7, 0.3);
        }

        .btn-checkout-table:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 14px rgba(255, 193, 7, 0.4);
        }

        .empty-state-table {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state-table ion-icon {
            font-size: 80px;
            opacity: 0.25;
        }

        .empty-state-table div {
            margin-top: 15px;
            font-size: 1rem;
            font-weight: 600;
        }

        /* ========== GLASSMORPHISM POPUP ========== */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }

        .popup-overlay.active {
            display: flex;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .popup-content {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border-radius: 25px;
            max-width: 600px;
            width: 90%;
            max-height: 85vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3),
                       inset 0 1px 0 rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: slideUp 0.4s ease;
            position: relative;
        }

        body.dark-mode .popup-content {
            background: rgba(45, 55, 72, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        @keyframes slideUp {
            from {
                transform: translateY(50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .popup-header {
            padding: 25px 30px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 25px 25px 0 0;
        }

        body.dark-mode .popup-header {
            background: rgba(45, 55, 72, 0.7);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .popup-header h3 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .popup-close {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            background: var(--bg-primary);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 4px 4px 10px var(--shadow-dark),
                       -4px -4px 10px var(--shadow-light);
        }

        .popup-close:hover {
            transform: rotate(90deg) scale(1.1);
        }

        .popup-close:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .popup-close ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        .popup-body {
            padding: 30px;
        }

        .detail-avatar-section {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(15px);
            border-radius: 20px;
            box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        body.dark-mode .detail-avatar-section {
            background: rgba(45, 55, 72, 0.4);
        }

        .detail-avatar {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 15px;
        }

        .detail-avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: var(--bg-primary);
            box-shadow: inset 5px 5px 10px var(--shadow-dark),
                       inset -5px -5px 10px var(--shadow-light);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .detail-name {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 8px;
        }

        .detail-company {
            font-size: 0.95rem;
            color: var(--text-secondary);
            font-weight: 600;
        }

        .detail-grid {
            display: grid;
            gap: 15px;
        }

        .detail-item {
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            padding: 18px 20px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }

        body.dark-mode .detail-item {
            background: rgba(45, 55, 72, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .detail-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        .detail-label {
            font-size: 0.75rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-secondary);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .detail-value {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            word-break: break-word;
        }

        .detail-status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .stats-row {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }

            .stat-box h3 {
                font-size: 1.5rem;
            }

            .table-glass {
                font-size: 0.8rem;
            }

            .table-glass thead th {
                padding: 12px 10px;
                font-size: 0.7rem;
            }

            .table-glass tbody td {
                padding: 12px 10px;
                font-size: 0.8rem;
            }

            .table-name {
                font-size: 0.85rem;
            }

            .table-company,
            .table-phone {
                font-size: 0.7rem;
            }

            .btn-table {
                padding: 6px 10px;
                font-size: 0.65rem;
            }
        }

        @media (max-width: 480px) {
            #header-section {
                padding: 20px 15px;
            }

            #header-title h3 {
                font-size: 1.3rem;
            }

            .stats-card {
                padding: 12px;
            }

            .stat-box {
                padding: 12px 8px;
            }

            .stat-box h6 {
                font-size: 0.6rem;
            }

            .stat-box h3 {
                font-size: 1.3rem;
            }

            .table-responsive {
                border-radius: 18px;
            }
        }
    </style>

    <div id="header-section">
        <div id="section-back">
            <a href="{{ route('fasilitas.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
        </div>
        <div id="header-title">
            <h3>Manajemen Pengunjung</h3>
            <p>Check-In & Check-Out Pengunjung</p>
        </div>
    </div>

    <div id="content-section">
        <!-- Statistics -->
        <div class="stats-card">
            <div class="stats-row">
                <div class="stat-box">
                    <h6>Total</h6>
                    <h3>{{ $pengunjung->count() }}</h3>
                </div>
                <div class="stat-box">
                    <h6>Check-In</h6>
                    <h3>{{ $pengunjung->where('status', 'checkin')->count() }}</h3>
                </div>
                <div class="stat-box">
                    <h6>Check-Out</h6>
                    <h3>{{ $pengunjung->where('status', 'checkout')->count() }}</h3>
                </div>
                <div class="stat-box">
                    <h6>Hari Ini</h6>
                    <h3>{{ $pengunjung->where('waktu_checkin', '>=', now()->startOfDay())->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Check-In Button -->
        <button class="btn btn-checkin" id="btnCheckinPengunjung">
            <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 50%; background: var(--bg-primary); padding: 2px; position: relative; margin-right: 0;">
                <span style="position: absolute; top: -2px; left: -2px; right: -2px; bottom: -2px; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); border-radius: 50%; z-index: -1;"></span>
                <ion-icon name="person-add" style="font-size: 20px;"></ion-icon>
            </span>
            <span style="margin-left: 8px;">Check-In Pengunjung Baru</span>
        </button>

        <!-- Histori Pengunjung Table -->
        <h4 class="section-title">
            <ion-icon name="time-outline" style="vertical-align: middle; margin-right: 8px;"></ion-icon>
            Histori Pengunjung
        </h4>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table-glass">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th>Keperluan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengunjung as $p)
                        <tr>
                            <!-- Nama Column -->
                            <td>
                                <div class="table-name-cell">
                                    @if($p->foto)
                                        <img src="{{ Storage::url($p->foto) }}" 
                                             class="table-avatar foto-preview" 
                                             data-foto="{{ Storage::url($p->foto) }}" 
                                             data-title="{{ $p->nama_lengkap }}"
                                             alt="{{ $p->nama_lengkap }}">
                                    @else
                                        <div class="table-avatar-placeholder">
                                            <ion-icon name="person" style="font-size: 22px; color: var(--color-primary);"></ion-icon>
                                        </div>
                                    @endif
                                    <div class="table-name-info">
                                        <div class="table-name">{{ $p->nama_lengkap }}</div>
                                        <div class="table-company">
                                            <ion-icon name="briefcase-outline" style="font-size: 13px;"></ion-icon>
                                            {{ $p->instansi ?? 'SAFD' }}
                                        </div>
                                        <div class="table-phone">
                                            <ion-icon name="call-outline" style="font-size: 12px; vertical-align: middle;"></ion-icon>
                                            {{ $p->no_telepon }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Check-In Column -->
                            <td>
                                <div class="table-time">
                                    <ion-icon name="log-in-outline" style="font-size: 16px;"></ion-icon>
                                    {{ $p->waktu_checkin->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 4px;">
                                    {{ $p->waktu_checkin->format('H:i') }}
                                </div>
                            </td>

                            <!-- Check-Out Column -->
                            <td>
                                @if($p->waktu_checkout)
                                <div class="table-time">
                                    <ion-icon name="log-out-outline" style="font-size: 16px;"></ion-icon>
                                    {{ $p->waktu_checkout->format('d/m/Y') }}
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 4px;">
                                    {{ $p->waktu_checkout->format('H:i') }}
                                </div>
                                @else
                                <span style="font-size: 0.8rem; color: var(--text-secondary);">-</span>
                                @endif
                            </td>

                            <!-- Keperluan Column -->
                            <td>
                                <div style="max-width: 200px;">
                                    <div style="font-size: 0.85rem; margin-bottom: 4px;">{{ $p->keperluan }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary);">
                                        <ion-icon name="people-outline" style="font-size: 13px; vertical-align: middle;"></ion-icon>
                                        Bertemu: {{ $p->bertemu_dengan ?? 'pak haji' }}
                                    </div>
                                </div>
                            </td>

                            <!-- Status Column -->
                            <td>
                                <span class="badge-table {{ $p->status == 'checkin' ? 'badge-checkin' : 'badge-checkout' }}">
                                    {{ $p->status == 'checkin' ? 'Check-In' : 'Check-Out' }}
                                </span>
                            </td>

                            <!-- Aksi Column -->
                            <td>
                                <div class="table-actions">
                                    @if($p->status == 'checkin')
                                    <button class="btn-table btn-checkout-table btn-checkout" 
                                        data-id="{{ $p->id }}" 
                                        data-nama="{{ $p->nama_lengkap }}">
                                        <ion-icon name="log-out-outline"></ion-icon>
                                        Checkout
                                    </button>
                                    @endif
                                    <button class="btn-table btn-detail-table btn-show-detail" 
                                        data-id="{{ $p->id }}"
                                        data-nama="{{ $p->nama_lengkap }}"
                                        data-instansi="{{ $p->instansi ?? 'SAFD' }}"
                                        data-telepon="{{ $p->no_telepon }}"
                                        data-foto="{{ $p->foto ? Storage::url($p->foto) : '' }}"
                                        data-keperluan="{{ $p->keperluan }}"
                                        data-bertemu="{{ $p->bertemu_dengan ?? 'pak haji' }}"
                                        data-checkin="{{ $p->waktu_checkin->format('d/m/Y H:i') }}"
                                        data-checkout="{{ $p->waktu_checkout ? $p->waktu_checkout->format('d/m/Y H:i') : '-' }}"
                                        data-status="{{ $p->status }}">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        Detail
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state-table">
                                    <ion-icon name="people-outline"></ion-icon>
                                    <div>Belum ada histori pengunjung</div>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Glassmorphism Detail Popup -->
    <div class="popup-overlay" id="detailPopup">
        <div class="popup-content">
            <div class="popup-header">
                <h3>
                    <ion-icon name="information-circle-outline"></ion-icon>
                    Detail Pengunjung
                </h3>
                <button class="popup-close" id="closePopup">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>
            <div class="popup-body">
                <!-- Avatar Section -->
                <div class="detail-avatar-section">
                    <div id="detailAvatarContainer"></div>
                    <div class="detail-name" id="detailNama"></div>
                    <div class="detail-company" id="detailInstansi"></div>
                </div>

                <!-- Detail Grid -->
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="call-outline"></ion-icon>
                            Nomor Telepon
                        </div>
                        <div class="detail-value" id="detailTelepon"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="log-in-outline"></ion-icon>
                            Waktu Check-In
                        </div>
                        <div class="detail-value" id="detailCheckin"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="log-out-outline"></ion-icon>
                            Waktu Check-Out
                        </div>
                        <div class="detail-value" id="detailCheckout"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="document-text-outline"></ion-icon>
                            Keperluan
                        </div>
                        <div class="detail-value" id="detailKeperluan"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="people-outline"></ion-icon>
                            Bertemu Dengan
                        </div>
                        <div class="detail-value" id="detailBertemu"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">
                            <ion-icon name="checkmark-circle-outline"></ion-icon>
                            Status
                        </div>
                        <div id="detailStatusContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Check-In -->
    <div class="modal fade" id="modalCheckin" tabindex="-1">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content" style="background: var(--bg-body);">
                <div class="modal-header" style="background: var(--bg-primary); border-bottom: 1px solid var(--border-color); box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);">
                    <h5 class="modal-title" style="color: var(--text-primary); font-weight: 800;">
                        <ion-icon name="person-add" style="vertical-align: middle; margin-right: 8px;"></ion-icon>
                        Check-In Pengunjung
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" style="background: var(--bg-primary); border-radius: 50%; width: 35px; height: 35px; box-shadow: 3px 3px 6px var(--shadow-dark), -3px -3px 6px var(--shadow-light);"></button>
                </div>
                <form action="{{ route('pengunjung.karyawan.checkin') }}" method="POST" enctype="multipart/form-data" id="formCheckin">
                    @csrf
                    <div class="modal-body" style="padding: 20px; background: var(--bg-body);">
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Instansi/Perusahaan</label>
                            <input type="text" name="instansi" class="form-control" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">No. Identitas (KTP/SIM)</label>
                            <input type="text" name="no_identitas" class="form-control" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">No. Telepon <span class="text-danger">*</span></label>
                            <input type="tel" name="no_telepon" class="form-control" required style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Email</label>
                            <input type="email" name="email" class="form-control" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Cabang</label>
                            <select name="kode_cabang" class="form-select" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                                <option value="">Pilih Cabang</option>
                                @foreach($cabang as $c)
                                    <option value="{{ $c->kode_cabang }}">{{ $c->nama_cabang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="2" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary); resize: none;"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Keperluan <span class="text-danger">*</span></label>
                            <input type="text" name="keperluan" class="form-control" required style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Bertemu Dengan</label>
                            <input type="text" name="bertemu_dengan" class="form-control" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Foto Pengunjung</label>
                            <input type="file" name="foto" class="form-control" accept="image/*" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary);">
                            <small class="text-muted">Format: JPG, JPEG, PNG (Max: 2MB)</small>
                        </div>
                        <div class="form-group mb-3">
                            <label style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Catatan</label>
                            <textarea name="catatan" class="form-control" rows="2" style="background: var(--bg-primary); border: none; border-radius: 12px; padding: 12px 16px; box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light); color: var(--text-primary); resize: none;"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer" style="background: var(--bg-primary); border-top: 1px solid var(--border-color); padding: 15px 20px; box-shadow: -5px -5px 10px var(--shadow-dark), 5px 5px 10px var(--shadow-light);">
                        <button type="button" class="btn" data-bs-dismiss="modal" style="background: var(--bg-primary); color: var(--text-primary); border: none; border-radius: 12px; padding: 12px 24px; font-weight: 700; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);">
                            <ion-icon name="close-circle-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon>
                            Batal
                        </button>
                        <button type="submit" class="btn" style="background: var(--bg-primary); color: var(--text-primary); border: 3px solid transparent; background-clip: padding-box; border-radius: 12px; padding: 12px 24px; font-weight: 700; box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light); position: relative;">
                            <span style="position: absolute; top: -3px; left: -3px; right: -3px; bottom: -3px; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); border-radius: 12px; z-index: -1;"></span>
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border-radius: 50%; background: var(--bg-primary); padding: 2px; position: relative; margin-right: 8px; vertical-align: middle;">
                                <span style="position: absolute; top: -2px; left: -2px; right: -2px; bottom: -2px; background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); border-radius: 50%; z-index: -1;"></span>
                                <ion-icon name="checkmark-circle-outline" style="font-size: 20px;"></ion-icon>
                            </span>
                            Check-In
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Preview Foto -->
    <div class="modal fade" id="modalFoto" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoTitle">Foto Pengunjung</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="fotoPreview" src="" class="img-fluid" alt="Foto" style="border-radius: 12px;">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Open Check-In Modal
        var btnCheckin = document.getElementById('btnCheckinPengunjung');
        if (btnCheckin) {
            btnCheckin.addEventListener('click', function(e) {
                e.preventDefault();
                var modal = new bootstrap.Modal(document.getElementById('modalCheckin'));
                modal.show();
            });
        }

        // Preview foto
        var fotoPreview = document.querySelectorAll('.foto-preview');
        fotoPreview.forEach(function(foto) {
            foto.addEventListener('click', function() {
                var fotoUrl = this.getAttribute('data-foto');
                var title = this.getAttribute('data-title');
                document.getElementById('fotoPreview').src = fotoUrl;
                document.getElementById('fotoTitle').textContent = 'Foto - ' + title;
                var modal = new bootstrap.Modal(document.getElementById('modalFoto'));
                modal.show();
            });
        });

        // Checkout confirmation
        var btnCheckout = document.querySelectorAll('.btn-checkout');
        btnCheckout.forEach(function(btn) {
            btn.addEventListener('click', function() {
                var id = this.getAttribute('data-id');
                var nama = this.getAttribute('data-nama');
                
                Swal.fire({
                    title: 'Konfirmasi Check-Out',
                    html: 'Apakah Anda yakin ingin check-out pengunjung:<br><strong>' + nama + '</strong>?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ffc107',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Check-Out',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create form and submit
                        var form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/fasilitas/pengunjung-karyawan/' + id + '/checkout';
                        
                        var token = document.createElement('input');
                        token.type = 'hidden';
                        token.name = '_token';
                        token.value = '{{ csrf_token() }}';
                        form.appendChild(token);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // Success notification
        @if(session('success'))
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: '{{ session("success") }}',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Error notification
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session("error") }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Validation errors
        @if($errors->any())
            var errorList = '<ul class="text-start mb-0">';
            @foreach($errors->all() as $error)
                errorList += '<li>{{ $error }}</li>';
            @endforeach
            errorList += '</ul>';
            
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: errorList,
                confirmButtonColor: '#3085d6'
            });
        @endif

        // ========== GLASSMORPHISM DETAIL POPUP ==========
        const detailPopup = document.getElementById('detailPopup');
        const closePopupBtn = document.getElementById('closePopup');
        const detailButtons = document.querySelectorAll('.btn-show-detail');

        // Show detail popup
        detailButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const nama = this.dataset.nama;
                const instansi = this.dataset.instansi;
                const telepon = this.dataset.telepon;
                const foto = this.dataset.foto;
                const keperluan = this.dataset.keperluan;
                const bertemu = this.dataset.bertemu;
                const checkin = this.dataset.checkin;
                const checkout = this.dataset.checkout;
                const status = this.dataset.status;

                // Set avatar
                const avatarContainer = document.getElementById('detailAvatarContainer');
                if (foto) {
                    avatarContainer.innerHTML = `<img src="${foto}" class="detail-avatar" alt="${nama}">`;
                } else {
                    avatarContainer.innerHTML = `
                        <div class="detail-avatar-placeholder">
                            <ion-icon name="person" style="font-size: 60px; color: var(--color-primary);"></ion-icon>
                        </div>`;
                }

                // Set data
                document.getElementById('detailNama').textContent = nama;
                document.getElementById('detailInstansi').innerHTML = `<ion-icon name="briefcase-outline" style="vertical-align: middle; margin-right: 5px;"></ion-icon>${instansi}`;
                document.getElementById('detailTelepon').textContent = telepon;
                document.getElementById('detailCheckin').textContent = checkin;
                document.getElementById('detailCheckout').textContent = checkout;
                document.getElementById('detailKeperluan').textContent = keperluan;
                document.getElementById('detailBertemu').textContent = bertemu;

                // Set status badge
                const statusContainer = document.getElementById('detailStatusContainer');
                const badgeClass = status === 'checkin' ? 'badge-checkin' : 'badge-checkout';
                const statusText = status === 'checkin' ? 'Check-In' : 'Check-Out';
                statusContainer.innerHTML = `<span class="badge-table detail-status-badge ${badgeClass}">${statusText}</span>`;

                // Show popup
                detailPopup.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        // Close popup
        function closeDetailPopup() {
            detailPopup.classList.remove('active');
            document.body.style.overflow = '';
        }

        closePopupBtn.addEventListener('click', closeDetailPopup);

        // Close on overlay click
        detailPopup.addEventListener('click', function(e) {
            if (e.target === detailPopup) {
                closeDetailPopup();
            }
        });

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && detailPopup.classList.contains('active')) {
                closeDetailPopup();
            }
        });
    });
</script>
@endpush
