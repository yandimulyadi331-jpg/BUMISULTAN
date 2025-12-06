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
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 5px;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    }

    .logo-subtitle {
        display: block;
        font-size: 0.9rem;
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

    .hadiah-card {
        background: var(--bg-color);
        border-radius: 20px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 8px 8px 16px var(--shadow-dark), -8px -8px 16px var(--shadow-light);
    }

    .hadiah-title {
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #d0dfe1;
        font-size: 1.05rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .hadiah-title ion-icon {
        font-size: 22px;
    }

    .ukuran-item {
        display: flex;
        justify-content: space-between;
        padding: 12px 15px;
        background: var(--bg-color);
        border-radius: 12px;
        margin-bottom: 10px;
        box-shadow: inset 3px 3px 6px var(--shadow-dark), inset -3px -3px 6px var(--shadow-light);
    }

    .ukuran-label {
        font-weight: 600;
        color: #5a7c80;
        font-size: 0.95rem;
    }

    .ukuran-value {
        color: var(--primary-color);
        font-weight: 700;
        font-size: 0.95rem;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 20px;
        border-radius: 50%;
        background: var(--bg-color);
        box-shadow: inset 4px 4px 8px var(--shadow-dark), inset -4px -4px 8px var(--shadow-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .empty-icon ion-icon {
        font-size: 40px;
        color: #5a7c80;
        opacity: 0.5;
    }

    .empty-state p {
        color: #5a7c80;
        font-size: 0.95rem;
    }
</style>

<div id="app-body">
    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Laporan Stok Per Ukuran</span>
            <span class="logo-subtitle">MASAR</span>
        </div>
        <a href="{{ route('masar.karyawan.laporan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <div id="content-section">
        @forelse($hadiahList as $hadiah)
            <div class="hadiah-card">
                <div class="hadiah-title">
                    <ion-icon name="gift-outline"></ion-icon>
                    {{ $hadiah->nama_hadiah }}
                </div>
                
                @if($hadiah->stok_ukuran)
                    @foreach($hadiah->stok_ukuran as $ukuran => $jumlah)
                        <div class="ukuran-item">
                            <span class="ukuran-label">Ukuran {{ $ukuran }}</span>
                            <span class="ukuran-value">{{ $jumlah }} pcs</span>
                        </div>
                    @endforeach
                @else
                    <p style="text-align: center; color: #5a7c80; font-style: italic;">Tidak ada detail ukuran</p>
                @endif
            </div>
        @empty
            <div class="empty-state">
                <div class="empty-icon">
                    <ion-icon name="document-outline"></ion-icon>
                </div>
                <p>Belum ada data hadiah dengan ukuran</p>
            </div>
        @endforelse
    </div>
</div>
@endsection

