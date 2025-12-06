@extends('layouts.mobile.app')
@section('content')
    <style>
        :root {
            --primary-color: #2F5D62;
            --bg-main: #e8f0f2;
            --shadow-light: #ffffff;
            --shadow-dark: #c5d3d5;
            --primary-gradient-start: #2F5D62;
            --primary-gradient-end: #1e3d42;
            
            /* Light Mode Colors */
            --bg-body-light: #ecf0f3;
            --bg-primary-light: #ecf0f3;
            --shadow-dark-light: #d1d9e6;
            --shadow-light-light: #ffffff;
            --text-primary-light: #2c3e50;
            --text-secondary-light: #6c7a89;
            --border-light: rgba(0, 0, 0, 0.05);

            /* Dark Mode Colors */
            --bg-body-dark: #1a202c;
            --bg-primary-dark: #2d3748;
            --shadow-dark-dark: #141923;
            --shadow-light-dark: #3a4555;
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
            --bg-main: var(--bg-body-dark);
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
            transition: background 0.3s ease;
        }

        /* Header Section */
        #header-section {
            background: var(--bg-primary);
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 4px 12px var(--shadow-dark),
                       0 -2px 8px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .logo-wrapper {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
            text-shadow: 2px 2px 4px var(--shadow-dark);
        }

        .logo-subtitle {
            font-size: 0.7rem;
            color: var(--text-secondary);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 2px;
        }

        .back-btn {
            background: var(--bg-body);
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .back-btn:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .back-btn ion-icon {
            font-size: 24px;
            color: var(--text-primary);
        }

        #content-section {
            padding: 20px;
            padding-bottom: 120px;
        }

        /* Search and Filter Container */
        .search-filter-container {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .search-wrapper {
            display: flex;
            gap: 10px;
            margin-bottom: 15px;
        }

        .search-box {
            position: relative;
            flex: 1;
        }

        .search-box input {
            width: 100%;
            padding: 12px 18px;
            border: none;
            border-radius: 12px;
            font-size: 0.9rem;
            background: var(--bg-body);
            color: var(--text-primary);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            transition: all 0.3s;
        }

        .search-box input:focus {
            outline: none;
        }

        .search-box input::placeholder {
            color: var(--text-secondary);
        }

        .search-btn {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-body);
            border: none;
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s;
            flex-shrink: 0;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .search-btn:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .filter-row {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .filter-icon {
            color: var(--text-primary);
            font-size: 20px;
            flex-shrink: 0;
        }

        .filter-select {
            flex: 1;
            padding: 10px 14px;
            border: none;
            border-radius: 12px;
            font-size: 0.85rem;
            background: var(--bg-body);
            color: var(--text-primary);
            cursor: pointer;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .filter-select:focus {
            outline: none;
        }

        /* Table Container */
        .table-container {
            background: var(--bg-primary);
            border-radius: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            overflow: hidden;
        }

        /* Table Header */
        .table-header {
            display: grid;
            grid-template-columns: 40px 1fr 180px 120px 100px 100px 50px;
            padding: 15px 20px;
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-header div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .header-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        /* Table Row */
        .table-row {
            display: grid;
            grid-template-columns: 40px 1fr 180px 120px 100px 100px 50px;
            padding: 15px 20px;
            border-bottom: 1px solid var(--border-color);
            transition: all 0.2s;
            align-items: center;
            background: var(--bg-primary);
        }

        .table-row:hover {
            background: var(--bg-body);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .table-row:last-child {
            border-bottom: none;
        }

        /* Checkbox */
        .row-checkbox {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary-color);
        }

        /* Customer Info */
        .customer-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            background: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            flex-shrink: 0;
            overflow: hidden;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -2px -2px 6px var(--shadow-light);
        }

        .customer-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .customer-details {
            flex: 1;
            min-width: 0;
        }

        .customer-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .customer-email {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin: 2px 0 0 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Location */
        .location-cell {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Orders */
        .orders-cell {
            color: var(--text-primary);
            font-weight: 500;
            font-size: 0.85rem;
        }

        /* Registered Date */
        .date-cell {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Status Badge */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .status-badge.active {
            background: linear-gradient(135deg, #27ae60 0%, #52c77a 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(39, 174, 96, 0.25);
        }

        .status-badge.inactive {
            background: linear-gradient(135deg, #e74c3c 0%, #ec7063 100%);
            color: white;
            box-shadow: 0 2px 6px rgba(231, 76, 60, 0.25);
        }

        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        /* Kehadiran Badge */
        .kehadiran-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
            min-width: 70px;
            text-align: center;
        }

        .kehadiran-badge.high {
            background: #d4edda;
            color: #155724;
            box-shadow: 0 2px 4px rgba(21, 87, 36, 0.15);
        }

        .kehadiran-badge.medium {
            background: #fff3cd;
            color: #856404;
            box-shadow: 0 2px 4px rgba(133, 100, 4, 0.15);
        }

        .kehadiran-badge.low {
            background: #f8d7da;
            color: #721c24;
            box-shadow: 0 2px 4px rgba(114, 28, 36, 0.15);
        }

        /* More Options */
        .more-options {
            color: var(--text-primary);
            font-size: 22px;
            cursor: pointer;
            padding: 5px;
            border-radius: 8px;
            transition: all 0.2s;
        }

        .more-options:hover {
            background: var(--bg-body);
            transform: scale(1.1);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        /* Pagination */
        .pagination-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background: var(--bg-primary);
            border-top: 1px solid var(--border-color);
            margin-top: 0;
        }

        .pagination-info {
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        .pagination-controls {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .page-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 8px;
            background: var(--bg-body);
            color: var(--text-primary);
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .page-btn:hover {
            transform: translateY(-2px);
        }

        .page-btn.active {
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            color: white;
            box-shadow: inset 2px 2px 4px rgba(0,0,0,0.2),
                       0 4px 8px rgba(47, 93, 98, 0.4);
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Loading State */
        .loading-skeleton {
            padding: 15px 20px;
            border-bottom: 1px solid rgba(47, 93, 98, 0.08);
        }

        .skeleton-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .skeleton-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(90deg, var(--shadow-dark) 25%, var(--shadow-light) 50%, var(--shadow-dark) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        .skeleton-line {
            height: 10px;
            background: linear-gradient(90deg, var(--shadow-dark) 25%, var(--shadow-light) 50%, var(--shadow-dark) 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
            border-radius: 4px;
            flex: 1;
        }

        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Hide Duplicate Elements - Cleanup */
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state ion-icon {
            font-size: 64px;
            margin-bottom: 15px;
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .filter-row {
                flex-wrap: wrap;
            }

            .filter-select {
                min-width: calc(50% - 15px);
            }

            .filter-icon {
                width: 100%;
                margin-bottom: 8px;
            }

            .table-header,
            .table-row {
                grid-template-columns: 30px 1fr 80px 40px;
            }

            .location-cell,
            .orders-cell,
            .date-cell {
                display: none;
            }

            .customer-email {
                display: none;
            }

            .pagination-controls {
                gap: 4px;
            }

            .page-btn {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
        }

        .empty-state ion-icon {
            font-size: 64px;
            opacity: 0.3;
            margin-bottom: 15px;
        }

        /* Modal ID Card */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.85);
            z-index: 9999;
            align-items: center;
            justify-content: center;
            padding: 20px;
            backdrop-filter: blur(12px) saturate(150%);
        }

        .modal-overlay.show {
            display: flex;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes fadeIn {
            from { 
                opacity: 0;
                backdrop-filter: blur(0);
            }
            to { 
                opacity: 1;
                backdrop-filter: blur(12px) saturate(150%);
            }
        }

        .id-card {
            background: var(--bg-body);
            border-radius: 28px;
            width: 100%;
            max-width: 440px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.6),
                       0 15px 45px rgba(0, 0, 0, 0.5),
                       inset 0 2px 1px rgba(255,255,255,0.1),
                       inset 0 -2px 1px rgba(0,0,0,0.1);
            animation: slideUp 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            position: relative;
            border: 2px solid var(--border-color);
            transform-style: preserve-3d;
        }

        .id-card::-webkit-scrollbar {
            width: 6px;
        }

        .id-card::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 10px;
        }

        .id-card::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));
            border-radius: 10px;
        }

        .id-card::-webkit-scrollbar-thumb:hover {
            background: var(--primary-gradient-start);
        }

        @keyframes slideUp {
            from { 
                transform: translateY(100px) scale(0.9) rotateX(10deg);
                opacity: 0;
            }
            to { 
                transform: translateY(0) scale(1) rotateX(0);
                opacity: 1;
            }
        }

        .id-card-header {
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 50%, #0f1f21 100%);
            padding: 0;
            position: relative;
            text-align: center;
            border-radius: 26px 26px 0 0;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3),
                       inset 0 1px 0 rgba(255,255,255,0.2),
                       inset 0 -2px 8px rgba(0,0,0,0.3);
            overflow: hidden;
            height: 280px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .id-card-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 50% 50%, rgba(255,255,255,0.15) 0%, transparent 70%);
            pointer-events: none;
        }

        .id-card-header::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 70%);
            border-radius: 50%;
        }

        .id-card-header-photo {
            display: none !important;
        }

        .id-card-header-placeholder {
            display: none !important;
        }

        .id-card-close {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            font-size: 26px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.5),
                       inset 0 1px 0 rgba(255,255,255,0.2);
        }

        .id-card-close:hover {
            background: rgba(239, 68, 68, 0.95);
            border-color: rgba(255, 255, 255, 0.5);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 6px 20px rgba(239, 68, 68, 0.7),
                       inset 0 1px 0 rgba(255,255,255,0.4);
        }

        .id-card-close:active {
            transform: rotate(90deg) scale(0.95);
            box-shadow: inset 0 3px 8px rgba(0,0,0,0.4);
        }

        .id-card-logo,
        .id-card-title {
            display: none;
        }

        .id-card-photo-container {
            position: relative;
            width: 180px;
            height: 180px;
            border-radius: 50%;
            border: 8px solid var(--bg-body);
            overflow: visible;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5),
                       0 10px 30px rgba(0, 0, 0, 0.4),
                       inset 0 2px 4px rgba(255,255,255,0.2),
                       0 0 0 3px rgba(47, 93, 98, 0.4);
            background: var(--bg-body);
            z-index: 10;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }

        .photo-expand-icon {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.5),
                       inset 0 1px 0 rgba(255,255,255,0.3);
            opacity: 0;
            transform: scale(0.8);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 2px solid white;
            z-index: 10;
        }

        .photo-expand-icon ion-icon {
            color: white;
            font-size: 24px;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        }

        .id-card-photo-container:hover .photo-expand-icon {
            opacity: 1;
            transform: scale(1);
        }

        .id-card-photo-container:hover {
            transform: scale(1.08);
            box-shadow: 0 25px 75px rgba(0, 0, 0, 0.6),
                       0 15px 40px rgba(0, 0, 0, 0.5),
                       0 0 0 4px rgba(47, 93, 98, 0.6);
        }

        .id-card-photo-container:active {
            transform: scale(1.05);
        }

        .id-card-photo,
        .id-card-photo-placeholder {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform 0.3s ease;
            border-radius: 50%;
            overflow: hidden;
        }

        .id-card-photo-container:hover .id-card-photo,
        .id-card-photo-container:hover .id-card-photo-placeholder {
            transform: scale(1.1);
            filter: brightness(1.1);
        }

        .id-card-photo-placeholder {
            width: 100%;
            height: 100%;
            display: none;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            color: white;
            font-size: 72px;
            font-weight: 900;
            text-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .id-card-photo-placeholder.show {
            display: flex;
        }

        .id-card-body {
            padding: 35px 28px 32px;
            background: var(--bg-body);
        }

        .id-card-name {
            text-align: center;
            font-size: 26px;
            font-weight: 900;
            color: var(--text-primary);
            margin-bottom: 8px;
            letter-spacing: -0.5px;
            text-shadow: 0 3px 8px rgba(0,0,0,0.15),
                        0 2px 4px rgba(0,0,0,0.1);
            line-height: 1.2;
        }

        .id-card-number {
            text-align: center;
            color: var(--text-secondary);
            font-size: 15px;
            margin-bottom: 28px;
            font-family: 'Courier New', monospace;
            letter-spacing: 2px;
            font-weight: 700;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .id-card-info {
            display: grid;
            gap: 12px;
        }

        .id-card-info-item {
            display: flex;
            align-items: start;
            gap: 14px;
            padding: 16px 14px;
            background: var(--bg-primary);
            border-radius: 14px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -3px -3px 8px var(--shadow-light),
                       inset 0 2px 4px rgba(0,0,0,0.1);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .id-card-info-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(47, 93, 98, 0.1), transparent);
            transition: left 0.5s ease;
        }

        .id-card-info-item:hover {
            background: var(--bg-body);
            transform: translateX(6px) translateZ(8px);
            box-shadow: 6px 6px 16px var(--shadow-dark),
                       -4px -4px 12px var(--shadow-light),
                       0 4px 16px rgba(0,0,0,0.15);
            border-color: rgba(47, 93, 98, 0.3);
        }

        .id-card-info-item:hover::before {
            left: 100%;
        }

        .id-card-info-icon {
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            border-radius: 12px;
            color: white;
            font-size: 22px;
            flex-shrink: 0;
            box-shadow: 0 6px 16px rgba(47, 93, 98, 0.4),
                       inset 0 1px 0 rgba(255,255,255,0.3),
                       inset 0 -1px 0 rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .id-card-info-content {
            flex: 1;
        }

        .id-card-info-label {
            font-size: 11px;
            color: var(--text-secondary);
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
        }

        .id-card-info-value {
            font-size: 15px;
            color: var(--text-primary);
            font-weight: 700;
            line-height: 1.4;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .id-card-badges {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .id-card-badge {
            padding: 8px 18px;
            border-radius: 24px;
            font-size: 13px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 6px 16px rgba(0,0,0,0.2),
                       inset 0 1px 0 rgba(255,255,255,0.3),
                       inset 0 -1px 0 rgba(0,0,0,0.1);
            border: 2px solid rgba(255,255,255,0.2);
            text-shadow: 0 2px 4px rgba(0,0,0,0.2);
            letter-spacing: 0.5px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .id-card-badge ion-icon {
            font-size: 16px;
        }

        .id-card-badge:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
        }

        .id-card-badge.active {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
        }

        .id-card-badge.inactive {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: white;
        }

        .id-card-badge.umroh {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
        }

        .id-card-badge.no-umroh {
            background: linear-gradient(135deg, #9ca3af 0%, #6b7280 100%);
            color: white;
        }

        .id-card-footer {
            padding: 24px 28px;
            background: var(--bg-primary);
            display: flex;
            gap: 12px;
            border-radius: 0 0 26px 26px;
            box-shadow: inset 0 4px 12px rgba(0,0,0,0.08);
        }

        .id-card-btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 15px;
            letter-spacing: 0.5px;
        }

        .id-card-btn ion-icon {
            font-size: 20px;
        }

        .id-card-btn-primary {
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            color: white;
            box-shadow: 0 6px 20px rgba(47, 93, 98, 0.4),
                       inset 0 1px 0 rgba(255,255,255,0.3),
                       inset 0 -1px 0 rgba(0,0,0,0.2);
            border: 1px solid rgba(255,255,255,0.1);
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .id-card-btn-primary:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 10px 30px rgba(47, 93, 98, 0.5),
                       inset 0 1px 0 rgba(255,255,255,0.4);
        }

        .id-card-btn-primary:active {
            transform: translateY(0) scale(0.98);
            box-shadow: inset 0 4px 12px rgba(0,0,0,0.4);
        }

        .id-card-btn-secondary {
            background: var(--bg-body);
            color: var(--text-secondary);
            border: 2px solid var(--border-color);
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .id-card-btn-secondary:hover {
            background: var(--bg-primary);
            border-color: rgba(47, 93, 98, 0.3);
            transform: translateY(-2px) scale(1.02);
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
        }

        .id-card-btn-secondary:active {
            transform: translateY(0) scale(0.98);
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        /* Fullscreen Photo Modal */
        .fullscreen-photo-container {
            position: relative;
            width: 100%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 20px;
            animation: zoomIn 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes zoomIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .fullscreen-photo-container img {
            max-width: 95%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 16px;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.6),
                       0 10px 40px rgba(0, 0, 0, 0.5),
                       inset 0 1px 0 rgba(255,255,255,0.1);
            border: 3px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s ease;
        }

        .fullscreen-photo-container img:hover {
            transform: scale(1.02);
        }

        .fullscreen-initials {
            width: 450px;
            height: 450px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2F5D62 0%, #1e3d42 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 140px;
            font-weight: 900;
            color: white;
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.6),
                       inset 0 4px 8px rgba(255,255,255,0.2),
                       inset 0 -4px 8px rgba(0,0,0,0.3);
            text-transform: uppercase;
            text-shadow: 0 6px 16px rgba(0,0,0,0.5);
            border: 4px solid rgba(255, 255, 255, 0.2);
        }

        .close-fullscreen {
            position: absolute;
            top: 24px;
            right: 24px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 14px;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 10;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4),
                       inset 0 1px 0 rgba(255,255,255,0.5);
        }

        .close-fullscreen:hover {
            background: rgba(239, 68, 68, 0.95);
            border-color: rgba(255, 255, 255, 0.5);
            transform: rotate(90deg) scale(1.1);
            box-shadow: 0 12px 32px rgba(239, 68, 68, 0.6);
        }

        .close-fullscreen:active {
            transform: rotate(90deg) scale(0.95);
            box-shadow: inset 0 4px 12px rgba(0,0,0,0.4);
        }

        .close-fullscreen ion-icon {
            font-size: 32px;
            color: #1e293b;
            transition: color 0.3s ease;
        }

        .close-fullscreen:hover ion-icon {
            color: white;
        }

        .photo-caption {
            margin-top: 24px;
            padding: 16px 28px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            color: #1e293b;
            font-size: 18px;
            font-weight: 800;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4),
                       inset 0 1px 0 rgba(255,255,255,0.6);
            border: 2px solid rgba(255, 255, 255, 0.3);
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Dark mode support for photo caption */
        body.dark-mode .photo-caption {
            background: rgba(45, 55, 72, 0.95);
            color: #f7fafc;
            border-color: rgba(255, 255, 255, 0.1);
            text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }

        body.dark-mode .close-fullscreen {
            background: rgba(45, 55, 72, 0.95);
        }

        body.dark-mode .close-fullscreen ion-icon {
            color: #f7fafc;
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Data Jamaah</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <div id="content-section">
        <!-- Search and Filter -->
        <div class="search-filter-container">
            <!-- Search Box with Button -->
            <div class="search-wrapper">
                <div class="search-box">
                    <input type="text" id="searchJamaah" placeholder="Cari nama atau nomor jamaah...">
                </div>
                <button class="search-btn" id="btnSearch">
                    <ion-icon name="search-outline"></ion-icon>
                </button>
            </div>

            <!-- Filter Row -->
            <div class="filter-row">
                <ion-icon name="filter-outline" class="filter-icon"></ion-icon>
                <select class="filter-select" id="filterTahun">
                    <option value="">Semua Tahun Masuk</option>
                    @for($i = date('Y'); $i >= 2015; $i--)
                        <option value="{{ $i }}">Tahun {{ $i }}</option>
                    @endfor
                </select>
                <select class="filter-select" id="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="aktif">Aktif</option>
                    <option value="non_aktif">Non Aktif</option>
                </select>
                <select class="filter-select" id="filterUmroh">
                    <option value="">Status Umroh</option>
                    <option value="1">Sudah Umroh</option>
                    <option value="0">Belum Umroh</option>
                </select>
            </div>
        </div>

        <!-- Table Container -->
        <div class="table-container">
            <!-- Table Header -->
            <div class="table-header">
                <div>
                    <input type="checkbox" class="header-checkbox" id="selectAll">
                </div>
                <div>Nama Jamaah</div>
                <div>Alamat</div>
                <div>Jumlah Kehadiran</div>
                <div>Tahun Masuk</div>
                <div>Status</div>
                <div></div>
            </div>

            <!-- Table Body -->
            <div id="jamaahList">
                <!-- Loading skeleton -->
                <div class="loading-skeleton">
                    <div class="skeleton-row">
                        <div class="skeleton-avatar"></div>
                        <div class="skeleton-text"></div>
                    </div>
                </div>
                <div class="loading-skeleton">
                    <div class="skeleton-row">
                        <div class="skeleton-avatar"></div>
                        <div class="skeleton-text"></div>
                    </div>
                </div>
                <div class="loading-skeleton">
                    <div class="skeleton-row">
                        <div class="skeleton-avatar"></div>
                        <div class="skeleton-text"></div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    Showing <span id="showingStart">0</span> to <span id="showingEnd">0</span> of <span id="totalItems">0</span> items
                </div>
                <div class="pagination-controls">
                    <button class="page-btn" id="prevBtn" disabled>
                        <ion-icon name="chevron-back-outline"></ion-icon>
                    </button>
                    <button class="page-btn active" data-page="1">1</button>
                    <button class="page-btn" data-page="2">2</button>
                    <button class="page-btn" data-page="3">3</button>
                    <span style="color: #94a3b8;">...</span>
                    <button class="page-btn" id="lastPageBtn">10</button>
                    <button class="page-btn" id="nextBtn">
                        <ion-icon name="chevron-forward-outline"></ion-icon>
                    </button>
                </div>
            </div>
        </div>

        <div style="height: 30px;"></div>
    </div>

    <!-- Modal ID Card -->
    <div class="modal-overlay" id="modalIdCard">
        <div class="id-card">
            <div class="id-card-header">
                <img src="" alt="Foto Jamaah" class="id-card-header-photo" id="modalHeaderPhoto">
                <div class="id-card-header-placeholder" id="modalHeaderPlaceholder">YM</div>
                <button class="id-card-close" onclick="closeModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
                <div class="id-card-logo">Masar Saung Ar-Rohmah</div>
                <div class="id-card-title">ID CARD JAMAAH</div>
                <div class="id-card-photo-container" onclick="openFullscreenPhoto()" title="Klik untuk memperbesar">
                    <img src="" alt="Foto Jamaah" class="id-card-photo" id="modalPhoto">
                    <div class="id-card-photo-placeholder" id="modalPhotoPlaceholder">YM</div>
                    <div class="photo-expand-icon">
                        <ion-icon name="expand-outline"></ion-icon>
                    </div>
                </div>
            </div>
            <div class="id-card-body">
                <div class="id-card-name" id="modalName">-</div>
                <div class="id-card-number" id="modalNumber">-</div>
                
                <div class="id-card-info">
                    <div class="id-card-info-item">
                        <div class="id-card-info-icon">
                            <ion-icon name="card-outline"></ion-icon>
                        </div>
                        <div class="id-card-info-content">
                            <div class="id-card-info-label">NIK</div>
                            <div class="id-card-info-value" id="modalNik">-</div>
                        </div>
                    </div>
                    <div class="id-card-info-item">
                        <div class="id-card-info-icon">
                            <ion-icon name="call-outline"></ion-icon>
                        </div>
                        <div class="id-card-info-content">
                            <div class="id-card-info-label">No. Telepon</div>
                            <div class="id-card-info-value" id="modalPhone">-</div>
                        </div>
                    </div>
                    <div class="id-card-info-item">
                        <div class="id-card-info-icon">
                            <ion-icon name="location-outline"></ion-icon>
                        </div>
                        <div class="id-card-info-content">
                            <div class="id-card-info-label">Alamat</div>
                            <div class="id-card-info-value" id="modalAddress">-</div>
                        </div>
                    </div>
                    <div class="id-card-info-item">
                        <div class="id-card-info-icon">
                            <ion-icon name="calendar-outline"></ion-icon>
                        </div>
                        <div class="id-card-info-content">
                            <div class="id-card-info-label">Tahun Masuk</div>
                            <div class="id-card-info-value" id="modalYear">-</div>
                        </div>
                    </div>
                    <div class="id-card-info-item">
                        <div class="id-card-info-icon">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                        <div class="id-card-info-content">
                            <div class="id-card-info-label">Kehadiran</div>
                            <div class="id-card-info-value" id="modalKehadiran">0 kali</div>
                        </div>
                    </div>
                </div>

                <div class="id-card-badges">
                    <span class="id-card-badge active" id="modalStatusBadge">
                        <ion-icon name="checkmark-circle"></ion-icon>
                        Active
                    </span>
                    <span class="id-card-badge umroh" id="modalUmrohBadge">
                        <ion-icon name="airplane"></ion-icon>
                        Sudah Umroh
                    </span>
                </div>
            </div>
            <div class="id-card-footer">
                <button class="id-card-btn id-card-btn-primary" onclick="closeModal()" style="width: 100%;">
                    <ion-icon name="close-circle-outline"></ion-icon>
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Fullscreen Photo -->
    <div id="modalFullscreenPhoto" class="modal-overlay" style="display: none;">
        <div class="fullscreen-photo-container">
            <button class="close-fullscreen" onclick="closeFullscreenPhoto()">
                <ion-icon name="close"></ion-icon>
            </button>
            <img id="fullscreenPhotoImg" src="" alt="Foto Jamaah" />
            <div id="fullscreenInitialsPlaceholder" class="fullscreen-initials" style="display: none;"></div>
            <div class="photo-caption" id="fullscreenPhotoCaption"></div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    let jamaahData = [];
    let currentPage = 1;
    let itemsPerPage = 10;

    // Load data
    function loadJamaah() {
        $.ajax({
            url: '{{ route("masar.karyawan.jamaah.index") }}',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                jamaahData = response.data;
                renderJamaah(jamaahData);
                updatePagination();
            },
            error: function() {
                $('#jamaahList').html(`
                    <div class="empty-state">
                        <ion-icon name="alert-circle-outline"></ion-icon>
                        <p>Gagal memuat data jamaah</p>
                    </div>
                `);
            }
        });
    }

    // Get initials from name
    function getInitials(name) {
        return name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
    }

    // Get truncated location
    function truncateLocation(address) {
        if (!address) return '-';
        const parts = address.split(',');
        return parts.length > 1 ? parts[parts.length - 1].trim() : address.substring(0, 20);
    }

    // Render jamaah table
    function renderJamaah(data) {
        if (data.length === 0) {
            $('#jamaahList').html(`
                <div class="empty-state">
                    <ion-icon name="people-outline"></ion-icon>
                    <p>Tidak ada data jamaah</p>
                </div>
            `);
            $('#totalItems').text('0');
            $('#showingStart').text('0');
            $('#showingEnd').text('0');
            return;
        }

        const startIndex = (currentPage - 1) * itemsPerPage;
        const endIndex = Math.min(startIndex + itemsPerPage, data.length);
        const pageData = data.slice(startIndex, endIndex);

        let html = '';
        pageData.forEach(function(jamaah) {
            const initials = getInitials(jamaah.nama_jamaah);
            const location = truncateLocation(jamaah.alamat);
            const statusClass = jamaah.status_aktif == 'aktif' ? 'active' : 'inactive';
            const statusText = jamaah.status_aktif == 'aktif' ? 'Active' : 'Inactive';
            const kehadiran = jamaah.jumlah_kehadiran || 0;
            
            // Determine kehadiran badge class based on count
            let kehadiranBadgeClass = 'low'; // default
            if (kehadiran >= 25) {
                kehadiranBadgeClass = 'high'; // Hijau
            } else if (kehadiran >= 10) {
                kehadiranBadgeClass = 'medium'; // Kuning
            }
            // else kehadiranBadgeClass remains 'low' (Merah)
            
            // Check if photo exists
            let avatarHtml = '';
            console.log('Jamaah foto_jamaah:', jamaah.foto_jamaah); // Debug
            if (jamaah.foto_jamaah && jamaah.foto_jamaah !== '' && jamaah.foto_jamaah !== null && jamaah.foto_jamaah !== 'null') {
                // Build photo URL - always use /storage/jamaah/ path
                let photoUrl = '/storage/jamaah/' + jamaah.foto_jamaah;
                console.log('Photo URL:', photoUrl); // Debug
                avatarHtml = `<img src="${photoUrl}" alt="${jamaah.nama_jamaah}" style="width: 100%; height: 100%; object-fit: cover;" onerror="console.error('Failed to load:', this.src); this.style.display='none'; this.parentElement.innerHTML='${initials}';">`;
            } else {
                console.log('No photo, showing initials'); // Debug
                avatarHtml = initials;
            }
            
            html += `
                <div class="table-row" data-id="${jamaah.encrypted_id}">
                    <div>
                        <input type="checkbox" class="row-checkbox" value="${jamaah.encrypted_id}">
                    </div>
                    <div class="customer-info">
                        <div class="customer-avatar">${avatarHtml}</div>
                        <div class="customer-details">
                            <p class="customer-name">${jamaah.nama_jamaah}</p>
                            <p class="customer-email">${jamaah.nomor_jamaah}</p>
                        </div>
                    </div>
                    <div class="location-cell">${location}</div>
                    <div class="orders-cell"><span class="kehadiran-badge ${kehadiranBadgeClass}">${kehadiran} kali</span></div>
                    <div class="date-cell">${jamaah.tahun_masuk}</div>
                    <div>
                        <span class="status-badge ${statusClass}">
                            <span class="status-dot"></span>
                            ${statusText}
                        </span>
                    </div>
                    <div>
                        <ion-icon name="eye-outline" class="more-options" data-jamaah='${JSON.stringify(jamaah)}'></ion-icon>
                    </div>
                </div>
            `;
        });

        $('#jamaahList').html(html);
        $('#totalItems').text(data.length);
        $('#showingStart').text(startIndex + 1);
        $('#showingEnd').text(endIndex);

        // Add click handler for more options to show modal
        $('.more-options').on('click', function(e) {
            e.stopPropagation();
            const jamaahDataStr = $(this).attr('data-jamaah');
            const jamaah = JSON.parse(jamaahDataStr);
            showIdCardModal(jamaah);
        });

        // Add click handler for rows - show modal
        $('.table-row').on('click', function(e) {
            // Jangan trigger jika yang diklik adalah checkbox atau avatar
            if (!$(e.target).is('input[type="checkbox"]') && !$(e.target).closest('.customer-avatar').length) {
                const jamaahDataStr = $(this).find('.more-options').attr('data-jamaah');
                const jamaah = JSON.parse(jamaahDataStr);
                showIdCardModal(jamaah);
            }
        });
        
        // Add click handler for avatar - show fullscreen photo or initials
        $('.customer-avatar').on('click', function(e) {
            e.stopPropagation();
            const jamaahDataStr = $(this).closest('.table-row').find('.more-options').attr('data-jamaah');
            const jamaah = JSON.parse(jamaahDataStr);
            
            // Check if has photo
            if (jamaah.foto_jamaah && jamaah.foto_jamaah !== '' && jamaah.foto_jamaah !== null && jamaah.foto_jamaah !== 'null') {
                const photoUrl = '/storage/jamaah/' + jamaah.foto_jamaah;
                showFullscreenPhoto(photoUrl, jamaah.nama_jamaah);
            } else {
                // Show fullscreen with initials
                showFullscreenInitials(jamaah.nama_jamaah);
            }
        });
    }

    // Show ID Card Modal
    function showIdCardModal(jamaah) {
        console.log('Jamaah data:', jamaah); // Debug
        
        // Set name and number
        $('#modalName').text(jamaah.nama_jamaah);
        $('#modalNumber').text(jamaah.nomor_jamaah);
        
        // Set photo or initials
        const initials = getInitials(jamaah.nama_jamaah);
        
        // Reset display for avatar
        $('#modalPhoto').hide().off('load error');
        $('#modalPhotoPlaceholder').removeClass('show').text(initials);
        
        console.log('Modal - Jamaah foto_jamaah:', jamaah.foto_jamaah); // Debug
        
        if (jamaah.foto_jamaah && jamaah.foto_jamaah !== '' && jamaah.foto_jamaah !== null && jamaah.foto_jamaah !== 'null') {
            // Build photo URL - always use /storage/jamaah/ path  
            let photoUrl = '/storage/jamaah/' + jamaah.foto_jamaah;
            
            console.log('Modal - Photo URL:', photoUrl); // Debug
            
            // Try to load image for avatar
            $('#modalPhoto')
                .attr('src', photoUrl)
                .on('load', function() {
                    console.log('Photo loaded successfully');
                    $(this).show();
                    $('#modalPhotoPlaceholder').removeClass('show');
                })
                .on('error', function() {
                    console.log('Photo failed to load, showing initials');
                    $(this).hide();
                    $('#modalPhotoPlaceholder').addClass('show');
                });
        } else {
            console.log('No photo available, showing initials');
            $('#modalPhotoPlaceholder').addClass('show');
        }
        
        // Set info
        $('#modalNik').text(jamaah.nik || '-');
        $('#modalPhone').html(jamaah.no_hp ? `<a href="tel:${jamaah.no_hp}" style="color: inherit;">${jamaah.no_hp}</a>` : '-');
        $('#modalAddress').text(jamaah.alamat || '-');
        $('#modalYear').text(jamaah.tahun_masuk || '-');
        $('#modalKehadiran').text((jamaah.jumlah_kehadiran || 0) + ' kali');
        
        // Set status badge
        if (jamaah.status_aktif == 'aktif') {
            $('#modalStatusBadge').removeClass('inactive').addClass('active')
                .html('<ion-icon name="checkmark-circle"></ion-icon> Active');
        } else {
            $('#modalStatusBadge').removeClass('active').addClass('inactive')
                .html('<ion-icon name="close-circle"></ion-icon> Inactive');
        }
        
        // Set umroh badge
        if (jamaah.status_umroh == 1) {
            $('#modalUmrohBadge').removeClass('no-umroh').addClass('umroh')
                .html('<ion-icon name="airplane"></ion-icon> Sudah Umroh').show();
        } else {
            $('#modalUmrohBadge').removeClass('umroh').addClass('no-umroh')
                .html('<ion-icon name="close-circle-outline"></ion-icon> Belum Umroh').show();
        }
        
        // Show modal
        $('#modalIdCard').addClass('show');
        $('body').css('overflow', 'hidden');
    }

    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(jamaahData.length / itemsPerPage);
        
        // Update prev/next buttons
        $('#prevBtn').prop('disabled', currentPage === 1);
        $('#nextBtn').prop('disabled', currentPage === totalPages);
        
        // Highlight active page
        $('.page-btn[data-page]').removeClass('active');
        $(`.page-btn[data-page="${currentPage}"]`).addClass('active');
        
        $('#lastPageBtn').text(totalPages).data('page', totalPages);
    }

    // Pagination controls
    $('#prevBtn').on('click', function() {
        if (currentPage > 1) {
            currentPage--;
            renderJamaah(jamaahData);
            updatePagination();
        }
    });

    $('#nextBtn').on('click', function() {
        const totalPages = Math.ceil(jamaahData.length / itemsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            renderJamaah(jamaahData);
            updatePagination();
        }
    });

    $(document).on('click', '.page-btn[data-page]', function() {
        currentPage = parseInt($(this).data('page'));
        renderJamaah(jamaahData);
        updatePagination();
    });

    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.row-checkbox').prop('checked', $(this).is(':checked'));
    });

    // Search function
    function performSearch() {
        let search = $('#searchJamaah').val().toLowerCase();
        let filtered = jamaahData.filter(function(jamaah) {
            return jamaah.nama_jamaah.toLowerCase().includes(search) || 
                   jamaah.nomor_jamaah.toLowerCase().includes(search);
        });
        currentPage = 1;
        renderJamaah(filtered);
        updatePagination();
    }

    // Search button click
    $('#btnSearch').on('click', function() {
        performSearch();
    });

    // Search on Enter key
    $('#searchJamaah').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            performSearch();
        }
    });

    // Real-time search on input (optional)
    $('#searchJamaah').on('input', function() {
        performSearch();
    });

    // Filter functionality
    $('#filterTahun, #filterStatus, #filterUmroh').on('change', function() {
        let tahun = $('#filterTahun').val();
        let status = $('#filterStatus').val();
        let umroh = $('#filterUmroh').val();

        let filtered = jamaahData.filter(function(jamaah) {
            let matchTahun = !tahun || jamaah.tahun_masuk == tahun;
            let matchStatus = !status || jamaah.status_aktif == status;
            let matchUmroh = umroh === '' || jamaah.status_umroh == umroh;
            return matchTahun && matchStatus && matchUmroh;
        });

        currentPage = 1;
        renderJamaah(filtered);
        updatePagination();
    });

    // Initial load
    loadJamaah();
});

// Close Modal Function (global)
function closeModal() {
    $('#modalIdCard').removeClass('show');
    $('body').css('overflow', 'auto');
}

// Open fullscreen photo from modal
function openFullscreenPhoto() {
    const photoSrc = $('#modalPhoto').attr('src');
    const name = $('#modalName').text();
    const hasPhoto = $('#modalPhoto').is(':visible') && photoSrc && photoSrc !== '';
    
    if (hasPhoto) {
        showFullscreenPhoto(photoSrc, name);
    } else {
        showFullscreenInitials(name);
    }
}

// Show fullscreen photo
function showFullscreenPhoto(photoUrl, caption) {
    if (photoUrl && photoUrl !== '' && photoUrl !== 'null') {
        $('#fullscreenPhotoImg').attr('src', photoUrl).show();
        $('#fullscreenInitialsPlaceholder').hide();
        $('#fullscreenPhotoCaption').text(caption);
        $('#modalFullscreenPhoto').fadeIn(300);
        $('body').css('overflow', 'hidden');
    }
}

// Show fullscreen initials for jamaah without photo
function showFullscreenInitials(name) {
    const initials = name.split(' ').map(word => word[0]).join('').substring(0, 2).toUpperCase();
    $('#fullscreenPhotoImg').hide();
    $('#fullscreenInitialsPlaceholder').text(initials).show();
    $('#fullscreenPhotoCaption').text(name);
    $('#modalFullscreenPhoto').fadeIn(300);
    $('body').css('overflow', 'hidden');
}

// Close fullscreen photo
function closeFullscreenPhoto() {
    $('#modalFullscreenPhoto').fadeOut(300);
    $('body').css('overflow', 'auto');
}

// Close modal when clicking overlay
$(document).on('click', '#modalIdCard', function(e) {
    if (e.target.id === 'modalIdCard') {
        closeModal();
    }
});

// Close fullscreen on background click
$(document).on('click', '#modalFullscreenPhoto', function(e) {
    if (e.target.id === 'modalFullscreenPhoto') {
        closeFullscreenPhoto();
    }
});

// Close modal with ESC key
$(document).on('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection

