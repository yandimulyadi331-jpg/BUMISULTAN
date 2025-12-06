@extends('layouts.mobile.app')
@section('content')
<style>
    :root {
        --primary-color: #2F5D62;
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
        transition: background 0.3s ease, color 0.3s ease;
        overflow-y: auto;
        padding-bottom: 100px;
    }

    #app-body {
        min-height: 100vh;
        overflow-y: auto;
        padding-bottom: 120px;
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
        padding-bottom: 100px;
        overflow-y: visible;
    }

    .card-distribusi {
        background: var(--bg-primary);
        border-radius: 25px;
        padding: 25px;
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   0 4px 20px rgba(0,0,0,0.1);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .card-distribusi::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(47, 93, 98, 0.1), transparent);
        transition: left 0.7s ease;
    }

    .card-distribusi:hover {
        box-shadow: 10px 10px 20px var(--shadow-dark),
                   -10px -10px 20px var(--shadow-light),
                   0 8px 30px rgba(0,0,0,0.15);
        transform: translateY(-2px);
    }

    .card-distribusi:hover::before {
        left: 100%;
    }

    .form-label {
        font-size: 0.85rem;
        margin-bottom: 8px;
        color: var(--text-primary);
        font-weight: 600;
        text-shadow: 1px 1px 2px var(--shadow-dark),
                    -1px -1px 1px var(--shadow-light);
        letter-spacing: 0.3px;
    }

    .form-control, .form-select {
        border-radius: 12px;
        border: none;
        padding: 12px 16px;
        font-size: 0.9rem;
        background: var(--bg-body);
        color: var(--text-primary);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light),
                   0 1px 0 rgba(255,255,255,0.1);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .form-control:hover, .form-select:hover {
        box-shadow: inset 5px 5px 10px var(--shadow-dark),
                   inset -5px -5px 10px var(--shadow-light),
                   0 2px 4px rgba(0,0,0,0.1);
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        box-shadow: inset 6px 6px 12px var(--shadow-dark),
                   inset -6px -6px 12px var(--shadow-light),
                   0 0 0 3px rgba(47, 93, 98, 0.1),
                   0 4px 12px rgba(47, 93, 98, 0.2);
        transform: translateY(-1px);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
    }

    .btn {
        border-radius: 12px;
        padding: 12px 24px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: none;
        position: relative;
        overflow: hidden;
    }

    .btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn:hover::before {
        left: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
        color: white;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light),
                   0 4px 12px rgba(47, 93, 98, 0.3),
                   inset 0 1px 0 rgba(255,255,255,0.2);
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .btn-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   0 8px 20px rgba(47, 93, 98, 0.4),
                   inset 0 1px 0 rgba(255,255,255,0.3);
    }

    .btn-primary:active {
        transform: translateY(0) scale(0.98);
        box-shadow: inset 3px 3px 6px rgba(0,0,0,0.3),
                   inset -3px -3px 6px rgba(255,255,255,0.1),
                   0 2px 8px rgba(47, 93, 98, 0.2);
    }

    .btn-secondary {
        background: var(--bg-body);
        color: var(--text-primary);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        border: 2px solid var(--border-color);
        text-shadow: 1px 1px 2px var(--shadow-dark);
    }

    .btn-secondary:hover {
        background: var(--bg-primary);
        transform: translateY(-2px) scale(1.02);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
        border-color: rgba(47, 93, 98, 0.3);
    }

    .btn-secondary:active {
        transform: translateY(0) scale(0.98);
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light);
    }

    .text-danger {
        color: #e74c3c;
    }

    .text-muted {
        color: var(--text-secondary);
        font-size: 0.8rem;
    }

    .text-success {
        color: #27ae60;
    }

    ion-icon {
        font-size: 20px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    /* Section Styling */
    .section-header {
        padding: 0;
        margin-bottom: 20px;
        border: none;
        background: none;
    }

    .section-header h6 {
        margin: 0;
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 8px;
        text-shadow: 1px 1px 2px var(--shadow-dark);
    }

    .section-header h6::before {
        content: '';
        width: 4px;
        height: 20px;
        background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));
        border-radius: 2px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    /* Mode Toggle Buttons */
    .mode-selector {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
        margin-bottom: 25px;
    }

    .mode-btn {
        padding: 15px 20px;
        border: none;
        background: var(--bg-body);
        cursor: pointer;
        transition: all 0.3s;
        text-align: center;
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    .mode-btn ion-icon {
        font-size: 20px;
    }

    .mode-btn.active {
        background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));
        color: white;
        box-shadow: inset 3px 3px 6px rgba(0,0,0,0.2),
                   inset -3px -3px 6px rgba(255,255,255,0.1),
                   0 4px 12px rgba(47, 93, 98, 0.3);
    }

    .mode-btn:active {
        transform: scale(0.98);
    }

    .mode-content {
        display: none;
        animation: fadeIn 0.3s;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .mode-content.active {
        display: block;
    }

    /* Row Layout */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        margin-bottom: 0;
    }

    .form-row.single {
        grid-template-columns: 1fr;
    }

    .form-row.triple {
        grid-template-columns: 1fr 1fr 1fr;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
    }

    /* Input Card Style - seperti gambar */
    .input-card {
        background: var(--bg-body);
        border-radius: 16px;
        padding: 16px;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light),
                   inset 0 1px 0 rgba(255,255,255,0.1);
        margin-bottom: 16px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .input-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(47, 93, 98, 0.05), transparent);
        transition: left 0.6s ease;
    }

    .input-card:hover {
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light),
                   0 4px 16px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }

    .input-card:hover::before {
        left: 100%;
    }

    /* Input Card Header */
    .input-card-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
    }

    .input-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: linear-gradient(135deg, rgba(47, 93, 98, 0.1), rgba(47, 93, 98, 0.05));
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: inset 2px 2px 4px var(--shadow-dark),
                   inset -2px -2px 4px var(--shadow-light);
    }

    .input-card-icon ion-icon {
        font-size: 24px;
        color: var(--text-primary);
    }

    .input-card-title {
        flex: 1;
    }

    .input-card-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
        margin: 0;
        line-height: 1;
    }

    .input-card-value {
        font-size: 1.1rem;
        color: var(--text-primary);
        font-weight: 700;
        margin: 4px 0 0 0;
        line-height: 1;
        text-shadow: 1px 1px 2px var(--shadow-dark);
    }

    /* Input di dalam card */
    .input-card .form-control,
    .input-card .form-select {
        background: transparent;
        border: none;
        box-shadow: none;
        padding: 8px 0;
        font-size: 1.05rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .input-card .form-control:focus,
    .input-card .form-select:focus {
        box-shadow: none;
        transform: none;
        outline: none;
    }

    .input-card .form-control::placeholder {
        color: var(--text-secondary);
        font-weight: 400;
        font-size: 0.95rem;
    }

    /* Row layout untuk input cards */
    .input-card-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }

    .input-card-row.cols-2 {
        grid-template-columns: repeat(2, 1fr);
    }

    .input-card-row.cols-3 {
        grid-template-columns: repeat(3, 1fr);
    }

    @media (max-width: 768px) {
        .input-card-row,
        .input-card-row.cols-2,
        .input-card-row.cols-3 {
            grid-template-columns: 1fr;
        }
    }

    /* Dark mode enhancements for input cards */
    body.dark-mode .input-card {
        box-shadow: 6px 6px 16px var(--shadow-dark),
                   -6px -6px 16px var(--shadow-light),
                   0 4px 16px rgba(0,0,0,0.3),
                   inset 0 1px 0 rgba(255,255,255,0.05);
    }

    body.dark-mode .input-card:hover {
        box-shadow: 8px 8px 20px var(--shadow-dark),
                   -8px -8px 20px var(--shadow-light),
                   0 6px 20px rgba(0,0,0,0.4),
                   inset 0 1px 0 rgba(255,255,255,0.08);
    }

    body.dark-mode .input-card-icon {
        background: linear-gradient(135deg, rgba(47, 93, 98, 0.2), rgba(47, 93, 98, 0.1));
        box-shadow: inset 2px 2px 6px var(--shadow-dark),
                   inset -2px -2px 6px var(--shadow-light),
                   0 0 20px rgba(47, 93, 98, 0.2);
    }

    /* Input date styling */
    input[type="date"].form-control {
        font-weight: 600;
        color: var(--text-primary);
    }

    input[type="date"]::-webkit-calendar-picker-indicator {
        filter: var(--text-primary);
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s;
    }

    input[type="date"]::-webkit-calendar-picker-indicator:hover {
        opacity: 1;
    }

    /* Select option styling */
    .form-select option {
        background: var(--bg-body);
        color: var(--text-primary);
        padding: 10px;
    }

    /* Textarea in card enhancement */
    .input-card textarea.form-control {
        min-height: 60px;
        line-height: 1.6;
        font-size: 0.95rem;
    }

    /* Override table borders - HAPUS SEMUA GARIS */
    .table-bordered,
    .table-bordered th,
    .table-bordered td,
    table,
    thead,
    tbody,
    tr,
    th,
    td {
        border: none !important;
    }

    thead {
        border-bottom: none !important;
    }

    /* Info Badge */
    .info-badge {
        background: linear-gradient(135deg, #f39c12 0%, #f8c471 100%);
        color: white;
        font-size: 0.65rem;
        padding: 3px 8px;
        border-radius: 8px;
        font-weight: 600;
        margin-left: 5px;
    }

    /* Option Buttons */
    .option-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .option-btn {
        padding: 10px 18px;
        border: none;
        background: var(--bg-body);
        border-radius: 12px;
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 500;
        color: var(--text-secondary);
        transition: all 0.2s;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
    }

    .option-btn:hover {
        transform: translateY(-2px);
        color: var(--text-primary);
    }

    .option-btn.active {
        background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));
        color: white;
        box-shadow: inset 2px 2px 4px rgba(0,0,0,0.2),
                   0 4px 8px rgba(47, 93, 98, 0.3);
    }

    /* Table Styling */
    .table-responsive {
        background: var(--bg-primary);
        border-radius: 15px;
        padding: 15px;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light),
                   0 4px 12px rgba(0,0,0,0.1);
        margin: 20px 0;
        transition: all 0.3s ease;
    }

    .table-responsive:hover {
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light),
                   0 6px 16px rgba(0,0,0,0.15);
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    thead {
        background: transparent !important;
    }

    th {
        padding: 12px;
        text-align: left;
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.85rem;
        border: none;
        text-shadow: 1px 1px 2px var(--shadow-dark);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    tbody tr {
        transition: all 0.2s ease;
        background: var(--bg-body);
        border-radius: 12px;
        box-shadow: 4px 4px 8px var(--shadow-dark),
                   -4px -4px 8px var(--shadow-light);
    }

    tbody tr:hover {
        background: var(--bg-primary);
        transform: translateX(4px);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    td {
        padding: 12px;
        color: var(--text-secondary);
        font-size: 0.85rem;
        border: none !important;
    }

    td:first-child {
        border-radius: 12px 0 0 12px;
    }

    td:last-child {
        border-radius: 0 12px 12px 0;
    }

    /* Button Tambah Hadiah - Small Size */
    .btn-sm {\n        padding: 8px 16px;\n        font-size: 0.8rem;\n        border-radius: 10px;\n        font-weight: 700;\n        letter-spacing: 0.3px;\n        text-shadow: 0 1px 2px rgba(0,0,0,0.2);\n    }\n\n    .btn-sm.btn-primary {\n        background: linear-gradient(135deg, #10b981 0%, #059669 100%);\n        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.3),\n                   inset 0 1px 0 rgba(255,255,255,0.3),\n                   0 0 20px rgba(16, 185, 129, 0.2);\n    }\n\n    .btn-sm.btn-primary:hover {\n        transform: translateY(-2px) scale(1.05);\n        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4),\n                   inset 0 1px 0 rgba(255,255,255,0.4),\n                   0 0 30px rgba(16, 185, 129, 0.3);\n    }\n\n    /* Button Danger (Remove Row) */\n    .btn-danger {\n        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);\n        color: white;\n        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3),\n                   inset 0 1px 0 rgba(255,255,255,0.3);\n        text-shadow: 0 1px 2px rgba(0,0,0,0.3);\n        transition: all 0.3s ease;\n    }\n\n    .btn-danger:hover {\n        transform: translateY(-2px) scale(1.1);\n        box-shadow: 0 6px 12px rgba(239, 68, 68, 0.5),\n                   inset 0 1px 0 rgba(255,255,255,0.4),\n                   0 0 20px rgba(239, 68, 68, 0.4);\n    }\n\n    .btn-danger:active {\n        transform: translateY(0) scale(0.95);\n        box-shadow: inset 0 3px 6px rgba(0,0,0,0.4);\n    }\n\n    .btn-danger:disabled {\n        opacity: 0.4;\n        cursor: not-allowed;\n        transform: none;\n    }

    /* Bootstrap-like utilities dengan dark mode support */\n    .d-flex {\n        display: flex !important;\n    }\n\n    .justify-content-between {\n        justify-content: space-between !important;\n    }\n\n    .align-items-center {\n        align-items: center !important;\n    }\n\n    .mb-0 {\n        margin-bottom: 0 !important;\n    }\n\n    .mb-3 {\n        margin-bottom: 20px !important;\n    }\n\n    .fw-bold {\n        font-weight: 700 !important;\n    }\n\n    /* Text center for table */\n    .text-center {\n        text-align: center !important;\n    }\n\n    /* Animations */\n    @keyframes slideIn {\n        from {\n            opacity: 0;\n            transform: translateY(20px);\n        }\n        to {\n            opacity: 1;\n            transform: translateY(0);\n        }\n    }\n\n    @keyframes pulse {\n        0%, 100% {\n            transform: scale(1);\n        }\n        50% {\n            transform: scale(1.05);\n        }\n    }\n\n    .card-distribusi {\n        animation: slideIn 0.5s ease-out;\n    }\n\n    /* Ion-icon styling */\n    ion-icon {\n        font-size: 20px;\n        vertical-align: middle;\n        transition: transform 0.3s ease;\n    }\n\n    .btn:hover ion-icon {\n        transform: scale(1.2) rotate(10deg);\n    }\n\n    .btn-danger:hover ion-icon {\n        transform: scale(1.3) rotate(-10deg);\n    }\n\n    /* Select focus ring */\n    .form-select:focus {\n        outline: none;\n        border: 2px solid rgba(47, 93, 98, 0.3);\n    }\n\n    /* Table bordered */\n    .table-bordered {\n        border: 1px solid var(--border-color);\n        border-radius: 12px;\n        overflow: hidden;\n    }\n\n    .table-bordered th,\n    .table-bordered td {\n        border: 1px solid var(--border-color);\n    }\n\n    /* Form select small */\n    .form-select-sm, .form-control-sm {\n        padding: 8px 12px;\n        font-size: 0.85rem;\n        border-radius: 10px;\n    }\n\n    /* Row number styling */\n    .row-number {\n        font-weight: 700;\n        color: var(--text-primary);\n        background: var(--bg-body);\n        text-shadow: 1px 1px 2px var(--shadow-dark);\n    }\n\n    /* Stok info */\n    .stok-info {\n        font-size: 0.8rem;\n        font-weight: 600;\n        padding: 4px 8px;\n        border-radius: 8px;\n        background: var(--bg-body);\n        display: inline-block;\n        box-shadow: inset 2px 2px 4px var(--shadow-dark),\n                   inset -2px -2px 4px var(--shadow-light);\n    }\n\n    /* Textarea styling */\n    textarea.form-control {\n        resize: vertical;\n        min-height: 80px;\n        line-height: 1.6;\n    }\n\n    textarea.form-control::-webkit-scrollbar {\n        width: 8px;\n    }\n\n    textarea.form-control::-webkit-scrollbar-track {\n        background: var(--bg-body);\n        border-radius: 10px;\n    }\n\n    textarea.form-control::-webkit-scrollbar-thumb {\n        background: linear-gradient(135deg, var(--primary-gradient-start), var(--primary-gradient-end));\n        border-radius: 10px;\n        box-shadow: inset 0 1px 0 rgba(255,255,255,0.3);\n    }\n\n    textarea.form-control::-webkit-scrollbar-thumb:hover {\n        background: linear-gradient(135deg, var(--primary-gradient-end), var(--primary-gradient-start));\n    }\n\n    /* Required asterisk */\n    .text-danger {\n        color: #ef4444;\n        font-weight: 700;\n        text-shadow: 0 1px 2px rgba(0,0,0,0.2);\n    }\n\n    /* Section header enhancement */\n    .section-header {\n        padding: 0;\n        margin-bottom: 20px;\n        border: none;\n        background: none;\n        animation: slideIn 0.4s ease-out;\n    }\n\n    /* Dark mode specific enhancements */\n    body.dark-mode .card-distribusi {\n        box-shadow: 8px 8px 20px var(--shadow-dark),\n                   -8px -8px 20px var(--shadow-light),\n                   0 4px 20px rgba(0,0,0,0.3),\n                   inset 0 1px 0 rgba(255,255,255,0.05);\n    }\n\n    body.dark-mode .btn-primary {\n        box-shadow: 6px 6px 12px var(--shadow-dark),\n                   -6px -6px 12px var(--shadow-light),\n                   0 4px 12px rgba(47, 93, 98, 0.4),\n                   0 0 30px rgba(47, 93, 98, 0.2);\n    }\n\n    body.dark-mode .form-control:focus,\n    body.dark-mode .form-select:focus {\n        box-shadow: inset 6px 6px 12px var(--shadow-dark),\n                   inset -6px -6px 12px var(--shadow-light),\n                   0 0 0 3px rgba(47, 93, 98, 0.2),\n                   0 0 20px rgba(47, 93, 98, 0.3);\n    }\n\n    /* Pulse animation for required fields */\n    @keyframes pulseGlow {\n        0%, 100% {\n            box-shadow: 0 0 5px rgba(239, 68, 68, 0.3);\n        }\n        50% {\n            box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);\n        }\n    }\n\n    input:invalid:focus,\n    select:invalid:focus {\n        animation: pulseGlow 2s infinite;\n    }

    /* Badge */
    .badge {
        padding: 5px 12px;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2),
                   inset 0 1px 0 rgba(255,255,255,0.3);
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    .badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.3);
    }

    .badge.bg-success {
        background: linear-gradient(135deg, #27ae60 0%, #52c77a 100%);
        color: white;
        box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3),
                   inset 0 1px 0 rgba(255,255,255,0.3);
    }

    .badge.bg-warning {
        background: linear-gradient(135deg, #f39c12 0%, #f8c471 100%);
        color: white;
        box-shadow: 0 4px 8px rgba(243, 156, 18, 0.3),
                   inset 0 1px 0 rgba(255,255,255,0.3);
    }

    /* Helper Text */
    .helper-text {
        font-size: 0.75rem;
        color: var(--text-secondary);
        margin-top: 5px;
        font-style: italic;
    }

    /* Info Badge */
    .info-badge {
        display: inline-block;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
        margin-left: 8px;
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.3),
                   inset 0 1px 0 rgba(255,255,255,0.3);
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
        transition: all 0.3s ease;
    }

    .info-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(59, 130, 246, 0.4);
    }

    /* Dark mode support for info badge */
    body.dark-mode .info-badge {
        box-shadow: 0 4px 8px rgba(59, 130, 246, 0.4),
                   inset 0 1px 0 rgba(255,255,255,0.2),
                   0 0 20px rgba(59, 130, 246, 0.2);
    }

    /* Divider Line - HIDDEN */
    .divider-line {
        display: none;
        height: 0;
        margin: 0;
        opacity: 0;
        visibility: hidden;
    }

    .divider-line::after {
        display: none;
    }

    /* Custom Select Arrow */
    .form-select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%238e44ad' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
    }

    /* Button Group */
    .btn-group-footer {
        display: grid;
        grid-template-columns: 1fr 2fr;
        gap: 12px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 2px solid var(--border-color);
        position: relative;
    }

    .btn-group-footer::before {
        content: '';
        position: absolute;
        top: -2px;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(to right, transparent, var(--border-color), transparent);
    }

    .btn-secondary {
        background: white;
        border: 2px solid #dee2e6;
        color: #6c757d;
    }

    .btn-secondary:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(142, 68, 173, 0.3);
    }

    /* FINAL OVERRIDE - HAPUS SEMUA BORDER TABLE */
    table, thead, tbody, tr, th, td,
    .table, .table-bordered, 
    .table thead th, .table tbody td,
    .table-bordered thead th,
    .table-bordered tbody td {
        border: none !important;
        border-top: none !important;
        border-bottom: none !important;
        border-left: none !important;
        border-right: none !important;
    }
</style>

<div id="app-body">
    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Distribusi Hadiah</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <div id="content-section">
        <div class="card-distribusi">
            <form action="{{ route('masar.karyawan.distribusi.store') }}" method="POST" id="formDistribusi">
                @csrf
                
                <!-- Mode Selector - Tab Style -->
                <div class="section-header">
                    <h6>TIPE PENERIMA</h6>
                </div>
                <div class="mode-selector">
                    <button type="button" class="mode-btn active" data-mode="jamaah">
                        <ion-icon name="people"></ion-icon>
                        Jamaah Terdaftar
                    </button>
                    <button type="button" class="mode-btn" data-mode="non-jamaah">
                        <ion-icon name="person"></ion-icon>
                        Penerima Umum
                    </button>
                </div>
                <input type="hidden" name="tipe_penerima" id="tipe_penerima" value="jamaah">

                <!-- Mode: Jamaah Terdaftar -->
                <div class="mode-content active" id="content-jamaah">
                    <!-- Pilih Jamaah Card -->
                    <div class="input-card">
                        <div class="input-card-header">
                            <div class="input-card-icon">
                                <ion-icon name="people-outline"></ion-icon>
                            </div>
                            <div class="input-card-title">
                                <p class="input-card-label">Pilih Jamaah <span class="text-danger">*</span></p>
                            </div>
                        </div>
                        <select name="jamaah_id" id="jamaah_id" class="form-select">
                            <option value="">-- Pilih Jamaah Terdaftar --</option>
                            @foreach($jamaahList ?? [] as $jamaah)
                                <option value="{{ $jamaah->id }}">
                                    {{ $jamaah->nomor_jamaah }} - {{ $jamaah->nama_jamaah }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                </div>

                <!-- Mode: Non-Jamaah -->
                <div class="mode-content" id="content-non-jamaah">
                    <!-- Nama Penerima Card -->
                    <div class="input-card">
                        <div class="input-card-header">
                            <div class="input-card-icon">
                                <ion-icon name="person-outline"></ion-icon>
                            </div>
                            <div class="input-card-title">
                                <p class="input-card-label">Nama Penerima <span class="text-danger">*</span></p>
                            </div>
                        </div>
                        <input type="text" name="penerima_nama" id="penerima_nama" class="form-control" 
                               placeholder="Masukkan nama lengkap penerima">
                    </div>
                    
                    <!-- Row untuk No HP dan Alamat -->
                    <div class="input-card-row cols-2">
                        <div class="input-card">
                            <div class="input-card-header">
                                <div class="input-card-icon">
                                    <ion-icon name="call-outline"></ion-icon>
                                </div>
                                <div class="input-card-title">
                                    <p class="input-card-label">No. HP/WhatsApp</p>
                                </div>
                            </div>
                            <input type="text" name="penerima_hp" id="penerima_hp" class="form-control" 
                                   placeholder="08xxxxxxxxxx">
                        </div>
                        <div class="input-card">
                            <div class="input-card-header">
                                <div class="input-card-icon">
                                    <ion-icon name="location-outline"></ion-icon>
                                </div>
                                <div class="input-card-title">
                                    <p class="input-card-label">Alamat Singkat</p>
                                </div>
                            </div>
                            <input type="text" name="penerima_alamat" id="penerima_alamat" class="form-control" 
                                   placeholder="Contoh: Jakarta Selatan">
                        </div>
                    </div>
                </div>

                <!-- Divider -->
                <div class="divider-line"></div>

                <!-- Detail Hadiah Section -->
                <div class="section-header">
                    <h6>DETAIL HADIAH</h6>
                </div>

                <!-- Table Hadiah (Multiple) -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <label class="form-label fw-bold mb-0">Daftar Hadiah yang Didistribusikan</label>
                        <button type="button" class="btn btn-sm btn-primary" id="btnTambahHadiah">
                            <ion-icon name="add-circle"></ion-icon> Tambah Hadiah
                        </button>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tableHadiah" style="font-size: 13px;">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th width="35%">Hadiah</th>
                                    <th width="20%">Ukuran</th>
                                    <th width="15%">Jumlah</th>
                                    <th width="15%">Stok</th>
                                    <th width="10%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="hadiahRows">
                                <!-- Row pertama (default) -->
                                <tr class="hadiah-row">
                                    <td class="text-center row-number">1</td>
                                    <td>
                                        <select name="hadiah_id[]" class="form-select form-select-sm hadiah-select" required>
                                            <option value="">-- Pilih Hadiah --</option>
                                            @foreach($hadiahList as $hadiah)
                                                <option value="{{ $hadiah->id }}" 
                                                        data-stok="{{ $hadiah->stok_tersedia }}"
                                                        data-ukuran="{{ $hadiah->ukuran ?? '' }}"
                                                        data-stok-ukuran="{{ json_encode($hadiah->stok_ukuran ?? []) }}"
                                                        data-nama="{{ $hadiah->nama_hadiah }}">
                                                    {{ $hadiah->kode_hadiah }} - {{ $hadiah->nama_hadiah }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="ukuran[]" class="form-select form-select-sm ukuran-select">
                                            <option value="">-- Pilih Ukuran --</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" 
                                               value="1" min="1" required>
                                    </td>
                                    <td>
                                        <small class="text-muted stok-info">-</small>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-danger btn-remove-row" disabled>
                                            <ion-icon name="trash"></ion-icon>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Divider -->
                <div class="divider-line"></div>

                <!-- Informasi Distribusi Section -->
                <div class="section-header">
                    <h6>INFORMASI DISTRIBUSI</h6>
                </div>

                <!-- Row untuk Tanggal dan Metode -->
                <div class="input-card-row cols-2">
                    <div class="input-card">
                        <div class="input-card-header">
                            <div class="input-card-icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </div>
                            <div class="input-card-title">
                                <p class="input-card-label">Tanggal Distribusi <span class="text-danger">*</span></p>
                            </div>
                        </div>
                        <input type="date" name="tanggal_distribusi" class="form-control" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="input-card">
                        <div class="input-card-header">
                            <div class="input-card-icon">
                                <ion-icon name="grid-outline"></ion-icon>
                            </div>
                            <div class="input-card-title">
                                <p class="input-card-label">Metode Distribusi</p>
                            </div>
                        </div>
                        <select name="metode_distribusi" class="form-select">
                            <option value="langsung">Langsung</option>
                            <option value="undian">Undian</option>
                            <option value="prestasi">Prestasi</option>
                            <option value="kehadiran">Kehadiran</option>
                        </select>
                    </div>
                </div>

                <!-- Nama Penerima Final Card -->
                <div class="input-card">
                    <div class="input-card-header">
                        <div class="input-card-icon">
                            <ion-icon name="person-circle-outline"></ion-icon>
                        </div>
                        <div class="input-card-title">
                            <p class="input-card-label">Nama Penerima (Final) <span class="text-danger">*</span></p>
                        </div>
                    </div>
                    <input type="text" name="penerima" id="penerima" class="form-control" 
                           placeholder="Nama yang akan tercatat sebagai penerima" required>
                </div>

                <!-- Petugas Distribusi Card -->
                <div class="input-card">
                    <div class="input-card-header">
                        <div class="input-card-icon">
                            <ion-icon name="shield-checkmark-outline"></ion-icon>
                        </div>
                        <div class="input-card-title">
                            <p class="input-card-label">Petugas Distribusi</p>
                        </div>
                    </div>
                    <input type="text" name="petugas_distribusi" class="form-control" 
                           value="{{ auth()->user()->name ?? '' }}" 
                           placeholder="Nama petugas yang melakukan distribusi">
                </div>

                <!-- Catatan Card -->
                <div class="input-card">
                    <div class="input-card-header">
                        <div class="input-card-icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </div>
                        <div class="input-card-title">
                            <p class="input-card-label">Catatan / Keterangan</p>
                        </div>
                    </div>
                    <textarea name="keterangan" class="form-control" rows="3" 
                              placeholder="Tambahkan catatan atau keterangan tambahan (opsional)"></textarea>
                </div>

                <!-- Buttons -->
                <div class="btn-group-footer">
                    <a href="{{ route('masar.karyawan.index') }}" class="btn btn-secondary">
                        <ion-icon name="arrow-back"></ion-icon> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <ion-icon name="checkmark-circle"></ion-icon> Simpan Distribusi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Hadiah options untuk clone
        let hadiahOptions = `
            <option value="">-- Pilih Hadiah --</option>
            @foreach($hadiahList as $hadiah)
                <option value="{{ $hadiah->id }}" 
                        data-stok="{{ $hadiah->stok_tersedia }}"
                        data-ukuran="{{ $hadiah->ukuran ?? '' }}"
                        data-stok-ukuran="{{ json_encode($hadiah->stok_ukuran ?? []) }}"
                        data-nama="{{ $hadiah->nama_hadiah }}">
                    {{ $hadiah->kode_hadiah }} - {{ $hadiah->nama_hadiah }}
                </option>
            @endforeach
        `;

        // Mode Toggle Handler
        $('.mode-btn').click(function() {
            let mode = $(this).data('mode');
            
            // Update button active state
            $('.mode-btn').removeClass('active');
            $(this).addClass('active');
            
            // Update hidden input
            if (mode === 'jamaah') {
                $('#tipe_penerima').val('jamaah');
            } else {
                $('#tipe_penerima').val('non-jamaah');
            }
            
            // Toggle content
            $('.mode-content').removeClass('active');
            $('#content-' + mode).addClass('active');
            
            // Clear and toggle required fields
            if (mode === 'jamaah') {
                $('#jamaah_id').prop('required', true);
                $('#penerima_nama').prop('required', false).val('');
                $('#penerima_hp').val('');
                $('#penerima_alamat').val('');
                $('#penerima').val('');
            } else {
                $('#jamaah_id').prop('required', false).val('');
                $('#penerima_nama').prop('required', true);
                $('#penerima').val('');
            }
        });

        // Auto-fill penerima when jamaah selected
        $('#jamaah_id').change(function() {
            let selectedText = $(this).find('option:selected').text();
            if ($(this).val()) {
                // Extract name from "NOMOR - NAMA" format
                let parts = selectedText.split(' - ');
                let nama = parts.length > 1 ? parts[1] : selectedText;
                $('#penerima').val(nama.trim());
            } else {
                $('#penerima').val('');
            }
        });

        // Auto-fill penerima when non-jamaah name entered
        $('#penerima_nama').on('input', function() {
            $('#penerima').val($(this).val());
        });

        // Tambah Baris Hadiah
        $('#btnTambahHadiah').click(function() {
            let rowCount = $('#hadiahRows tr').length + 1;
            let newRow = `
                <tr class="hadiah-row">
                    <td class="text-center row-number">${rowCount}</td>
                    <td>
                        <select name="hadiah_id[]" class="form-select form-select-sm hadiah-select" required>
                            ${hadiahOptions}
                        </select>
                    </td>
                    <td>
                        <select name="ukuran[]" class="form-select form-select-sm ukuran-select">
                            <option value="">-- Pilih Ukuran --</option>
                        </select>
                    </td>
                    <td>
                        <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" 
                               value="1" min="1" required>
                    </td>
                    <td>
                        <small class="text-muted stok-info">-</small>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-danger btn-remove-row">
                            <ion-icon name="trash"></ion-icon>
                        </button>
                    </td>
                </tr>
            `;
            $('#hadiahRows').append(newRow);
            updateRowNumbers();
            updateRemoveButtons();
        });

        // Hapus Baris
        $(document).on('click', '.btn-remove-row', function() {
            $(this).closest('tr').remove();
            updateRowNumbers();
            updateRemoveButtons();
        });

        // Update nomor urut
        function updateRowNumbers() {
            $('#hadiahRows tr').each(function(index) {
                $(this).find('.row-number').text(index + 1);
            });
        }

        // Update tombol hapus (disable jika hanya 1 baris)
        function updateRemoveButtons() {
            let rowCount = $('#hadiahRows tr').length;
            if (rowCount === 1) {
                $('.btn-remove-row').prop('disabled', true);
            } else {
                $('.btn-remove-row').prop('disabled', false);
            }
        }

        // Handle hadiah selection (untuk setiap row)
        $(document).on('change', '.hadiah-select', function() {
            let $row = $(this).closest('tr');
            let $ukuranSelect = $row.find('.ukuran-select');
            let $stokInfo = $row.find('.stok-info');
            let $jumlahInput = $row.find('.jumlah-input');
            
            let selectedOption = $(this).find('option:selected');
            let stok = selectedOption.data('stok');
            let ukuran = selectedOption.data('ukuran');
            let stokUkuran = selectedOption.data('stok-ukuran');
            
            // Clear ukuran dropdown
            $ukuranSelect.html('<option value="">-- Pilih Ukuran --</option>');
            
            if ($(this).val()) {
                // Update stok info
                $jumlahInput.attr('max', stok);
                $stokInfo.html('<span class="text-success">Stok: ' + stok + '</span>');
                
                // Populate ukuran dropdown
                if (stokUkuran && typeof stokUkuran === 'object' && Object.keys(stokUkuran).length > 0) {
                    // Ada stok per ukuran
                    $.each(stokUkuran, function(ukuranKey, jumlah) {
                        if (jumlah > 0) {
                            $ukuranSelect.append(
                                '<option value="' + ukuranKey + '">' + 
                                ukuranKey + ' (Stok: ' + jumlah + ')' +
                                '</option>'
                            );
                        }
                    });
                } else if (ukuran && ukuran !== '') {
                    // Single ukuran
                    $ukuranSelect.append('<option value="' + ukuran + '">' + ukuran + '</option>');
                    $ukuranSelect.val(ukuran);
                } else {
                    // Tidak ada ukuran
                    $ukuranSelect.append('<option value="">Tidak ada</option>');
                }
            } else {
                $jumlahInput.removeAttr('max');
                $stokInfo.html('-');
            }
        });

        // Form validation
        $('#formDistribusi').submit(function(e) {
            e.preventDefault();
            
            let tipePenerima = $('#tipe_penerima').val();
            
            // Validate tipe penerima
            if (tipePenerima === 'jamaah') {
                let jamaahId = $('#jamaah_id').val();
                if (!jamaahId) {
                    Swal.fire('Perhatian!', 'Silakan pilih jamaah terlebih dahulu', 'warning');
                    return false;
                }
            } else {
                let penerimaNama = $('#penerima_nama').val();
                if (!penerimaNama || penerimaNama.trim() === '') {
                    Swal.fire('Perhatian!', 'Silakan isi nama penerima', 'warning');
                    $('#penerima_nama').focus();
                    return false;
                }
            }

            // Validate penerima final
            if (!$('#penerima').val() || $('#penerima').val().trim() === '') {
                Swal.fire('Perhatian!', 'Nama penerima harus diisi', 'warning');
                $('#penerima').focus();
                return false;
            }
            
            // Validate hadiah
            let isValid = true;
            $('.hadiah-select').each(function() {
                if (!$(this).val()) {
                    isValid = false;
                    $(this).addClass('is-invalid');
                } else {
                    $(this).removeClass('is-invalid');
                }
            });

            if (!isValid) {
                Swal.fire('Perhatian!', 'Pastikan semua hadiah sudah dipilih', 'warning');
                return false;
            }

            // Validate stok
            let stokValid = true;
            $('.hadiah-row').each(function() {
                let jumlah = parseInt($(this).find('.jumlah-input').val());
                let stok = parseInt($(this).find('.hadiah-select option:selected').data('stok'));
                
                if (jumlah > stok) {
                    stokValid = false;
                    Swal.fire('Error!', 'Jumlah hadiah "' + $(this).find('.hadiah-select option:selected').data('nama') + '" melebihi stok (' + stok + ')', 'error');
                    return false;
                }
            });

            if (!stokValid) {
                return false;
            }

            // Show loading and submit
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Sedang memproses distribusi',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            this.submit();
        });
    });
</script>
@endpush

