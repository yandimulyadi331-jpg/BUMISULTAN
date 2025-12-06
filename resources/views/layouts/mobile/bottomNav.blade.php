<!-- App Bottom Menu - Clean Card Style with Rainbow Border on Active -->
<style>
    /* Rainbow Gradient Variables */
    :root {
        /* Rainbow Gradient Colors - Teal to Orange to Pink */
        --gradient-start: #14b8a6;
        --gradient-mid1: #06b6d4;
        --gradient-mid2: #f59e0b;
        --gradient-mid3: #f97316;
        --gradient-end: #ec4899;
    }

    /* Main Navbar - Clean Background */
    .appBottomMenu {
        background: var(--bg-primary);
        border-top: 1px solid var(--border-color);
        box-shadow: 0 -8px 24px rgba(0, 0, 0, 0.08);
        padding: 10px 16px 14px;
        border-radius: 0;
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .appBottomMenu .item {
        transition: all 0.3s ease;
        color: var(--text-secondary);
        position: relative;
        padding: 4px 8px;
        flex: 1;
        text-decoration: none;
    }

    /* Clean Card Style - Default State */
    .appBottomMenu .item .col {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        min-height: 64px;
        border-radius: 18px;
        transition: all 0.3s ease;
        position: relative;
        padding: 10px 14px;
        /* Clean Neumorphism Shadow */
        background: var(--bg-primary);
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
    }

    /* Rainbow Border - Hidden by Default */
    .appBottomMenu .item .col::before {
        content: '';
        position: absolute;
        inset: -2px;
        border-radius: 18px;
        padding: 2px;
        background: linear-gradient(135deg, 
            var(--gradient-start) 0%,
            var(--gradient-mid1) 25%,
            var(--gradient-mid2) 50%,
            var(--gradient-mid3) 75%,
            var(--gradient-end) 100%);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: all 0.3s ease;
    }

    /* Active State - Show Rainbow Border Only */
    .appBottomMenu .item.active .col::before {
        opacity: 1;
    }

    /* Active State - Clean Card with Inset Shadow */
    .appBottomMenu .item.active .col {
        background: var(--bg-primary);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
        transform: translateY(-2px);
    }

    /* Icon Styling - Clean */
    .appBottomMenu .item ion-icon {
        font-size: 28px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 2;
        color: var(--text-secondary);
    }

    /* Active Icon - Rainbow Gradient */
    .appBottomMenu .item.active ion-icon {
        background: linear-gradient(135deg, 
            var(--gradient-start) 0%,
            var(--gradient-mid1) 25%,
            var(--gradient-mid2) 50%,
            var(--gradient-mid3) 75%,
            var(--gradient-end) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        transform: scale(1.1);
    }

    /* Text Label */
    .appBottomMenu .item strong {
        font-size: 10px;
        font-weight: 600;
        margin-top: 2px;
        transition: all 0.3s ease;
        letter-spacing: 0.3px;
        position: relative;
        z-index: 2;
        color: var(--text-secondary);
    }

    .appBottomMenu .item.active strong {
        background: linear-gradient(135deg, 
            var(--gradient-start) 0%,
            var(--gradient-mid1) 25%,
            var(--gradient-mid2) 50%,
            var(--gradient-mid3) 75%,
            var(--gradient-end) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        font-weight: 700;
    }

    /* Center Button - Rainbow Gradient with Neumorphism */
    .appBottomMenu .action-button {
        background: linear-gradient(135deg, 
            var(--gradient-start) 0%,
            var(--gradient-mid1) 25%,
            var(--gradient-mid2) 50%,
            var(--gradient-mid3) 75%,
            var(--gradient-end) 100%);
        box-shadow: 
            8px 8px 16px var(--shadow-dark),
            -8px -8px 16px var(--shadow-light),
            0 8px 20px rgba(20, 184, 166, 0.2);
        width: 70px;
        height: 70px;
        border-radius: 20px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: -26px;
        position: relative;
    }

    .appBottomMenu .action-button:active {
        transform: translateY(-22px) scale(0.95);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .appBottomMenu .action-button ion-icon {
        color: #ffffff;
        font-size: 36px;
        position: relative;
        z-index: 2;
    }

    /* Hover effect for non-active items */
    .appBottomMenu .item:not(.active):hover .col {
        transform: translateY(-2px);
    }

    .appBottomMenu .item:not(.active):hover .col::before {
        opacity: 0.4;
    }

    .appBottomMenu .item:not(.active):hover ion-icon {
        color: var(--text-primary);
        transform: scale(1.05);
    }
</style>

<div class="appBottomMenu">
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="home-outline"></ion-icon>
            <strong>Home</strong>
        </div>
    </a>
    <a href="{{ route('presensi.histori') }}" class="item {{ request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="document-text-outline" role="img" class="md hydrated" aria-label="document text outline"></ion-icon>
            <strong>Histori</strong>
        </div>
    </a>

    <a href="/presensi/create" class="item">
        <div class="col">
            <div class="action-button large">
                <ion-icon name="finger-print-outline"></ion-icon>
            </div>
        </div>
    </a>
    <a href="{{ route('pengajuanizin.index') }}" class="item {{ request()->is('pengajuanizin') || request()->is('lembur*') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="calendar-outline"></ion-icon>
            <strong>{{ request()->is('lembur*') ? 'Lembur' : 'Pengajuan Izin' }}</strong>
        </div>
    </a>
    <a href="{{ route('users.editpassword', Crypt::encrypt(Auth::user()->id)) }}"
        class="item {{ request()->is('/users/:id/editpassword') ? 'active' : '' }}">
        <div class="col">
            <ion-icon name="settings-outline"></ion-icon>
            <strong>Setting</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->
