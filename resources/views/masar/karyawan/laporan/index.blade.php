@extends('layouts.mobile.app')
@section('content')
<style>
    :root {
        --primary-color: #2F5D62;
        --bg-color: #e8f0f2;
        --shadow-light: #ffffff;
        --shadow-dark: #c5d3d5;
    }

    body {
        background-color: var(--bg-color);
        min-height: 100vh;
    }

    #app-body {
        background-color: var(--bg-color);
        min-height: 100vh;
        padding-bottom: 80px;
    }

    #header-section {
        padding: 20px;
        background-color: var(--bg-color);
        position: relative;
    }

    .logo-wrapper {
        text-align: center;
        padding: 15px 0;
    }

    .logo-title {
        display: block;
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .logo-subtitle {
        display: block;
        font-size: 0.95rem;
        color: #5a7c80;
        font-weight: 500;
    }

    .back-btn {
        position: absolute;
        left: 20px;
        top: 20px;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: var(--bg-color);
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--primary-color);
        font-size: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .back-btn:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
    }

    #content-section {
        padding: 0 20px 20px 20px;
    }

    .laporan-card {
        background: var(--bg-color);
        border-radius: 20px;
        padding: 25px;
        margin-bottom: 20px;
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
        text-align: center;
        transition: all 0.3s ease;
    }

    .laporan-card:active {
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
    }

    .icon-wrapper {
        width: 70px;
        height: 70px;
        margin: 0 auto 15px;
        border-radius: 50%;
        background: var(--bg-color);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-wrapper ion-icon {
        font-size: 35px;
        color: var(--primary-color);
    }

    .laporan-card h6 {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 8px;
        font-size: 1.1rem;
    }

    .laporan-card p {
        color: #5a7c80;
        font-size: 0.9rem;
        margin-bottom: 20px;
        line-height: 1.4;
    }

    .btn-laporan {
        padding: 12px 30px;
        background: linear-gradient(145deg, #34686d, #2a5256);
        color: white;
        text-decoration: none;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 5px 5px 10px var(--shadow-dark), -5px -5px 10px var(--shadow-light);
        transition: all 0.3s ease;
    }

    .btn-laporan:hover {
        background: linear-gradient(145deg, #2a5256, #34686d);
        transform: translateY(-2px);
        box-shadow: 6px 6px 12px var(--shadow-dark), -6px -6px 12px var(--shadow-light);
    }

    .btn-laporan:active {
        transform: translateY(0);
        box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
    }

    .btn-laporan ion-icon {
        font-size: 20px;
    }
</style>

<div id="app-body">
    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Laporan</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <div id="content-section">
        <div class="laporan-card">
            <div class="icon-wrapper">
                <ion-icon name="file-tray-stacked-outline"></ion-icon>
            </div>
            <h6>Laporan Stok Per Ukuran</h6>
            <p>Lihat detail stok hadiah per ukuran</p>
            <a href="{{ route('masar.karyawan.laporan.stokUkuran') }}" class="btn-laporan">
                <ion-icon name="eye-outline"></ion-icon> Lihat Laporan
            </a>
        </div>

        <div class="laporan-card">
            <div class="icon-wrapper">
                <ion-icon name="document-text-outline"></ion-icon>
            </div>
            <h6>Laporan Rekap Distribusi</h6>
            <p>Lihat rekap distribusi hadiah ke jamaah</p>
            <a href="{{ route('masar.karyawan.laporan.rekapDistribusi') }}" class="btn-laporan">
                <ion-icon name="eye-outline"></ion-icon> Lihat Laporan
            </a>
        </div>
    </div>
</div>
@endsection

