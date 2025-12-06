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
            color: var(--icon-color);
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

        /* ========== DATE CARD ========== */
        .date-card {
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
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            display: block;
        }

        .form-control {
            background: var(--bg-primary);
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
            color: var(--text-primary);
            font-weight: 700;
            font-size: 0.9rem;
            width: 100%;
        }

        .form-control:focus {
            outline: none;
        }

        /* ========== TABLE CONTAINER ========== */
        .list-container {
            background: var(--bg-primary);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
            margin-bottom: 20px;
        }

        .list-header {
            background: var(--gradient-1);
            color: white;
            padding: 18px 20px;
            display: grid;
            grid-template-columns: 50px 1fr auto;
            gap: 15px;
            align-items: center;
            font-weight: 700;
        }

        .header-col {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .header-col.status {
            text-align: center;
        }

        /* ========== SANTRI ITEM ========== */
        .santri-item {
            border-bottom: 1px solid var(--border-color);
            padding: 0;
            background: var(--bg-primary);
            transition: all 0.3s;
        }

        .santri-item:last-child {
            border-bottom: none;
        }

        .santri-header {
            display: grid;
            grid-template-columns: 50px 1fr auto;
            gap: 15px;
            align-items: center;
            padding: 15px 20px;
        }

        .santri-number {
            background: var(--bg-primary);
            border-radius: 10px;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: var(--text-primary);
            font-size: 0.85rem;
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
        }

        .santri-info {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .santri-name {
            font-weight: 800;
            color: var(--text-primary);
            font-size: 0.9rem;
            letter-spacing: -0.2px;
        }

        .santri-nis {
            color: var(--text-secondary);
            font-size: 0.7rem;
            font-weight: 600;
        }

        /* ========== STATUS BUTTONS ========== */
        .status-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: flex-end;
            padding: 0 20px 15px 85px;
        }

        .status-btn {
            padding: 8px 14px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: var(--bg-primary);
            font-size: 0.7rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
            color: var(--text-secondary);
            box-shadow: 3px 3px 6px var(--shadow-dark),
                       -3px -3px 6px var(--shadow-light);
            text-decoration: none;
        }

        .status-btn:active {
            box-shadow: inset 2px 2px 4px var(--shadow-dark),
                       inset -2px -2px 4px var(--shadow-light);
        }

        .status-btn i {
            font-size: 0.95rem;
        }

        .status-btn.active-hadir,
        .status-btn.btn-hadir {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-color: #10b981;
            color: white;
            box-shadow: 0 3px 8px rgba(16, 185, 129, 0.4);
        }

        .status-btn.active-ijin,
        .status-btn.btn-ijin {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 3px 8px rgba(59, 130, 246, 0.4);
        }

        .status-btn.active-sakit,
        .status-btn.btn-sakit {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            border-color: #f59e0b;
            color: white;
            box-shadow: 0 3px 8px rgba(245, 158, 11, 0.4);
        }

        .status-btn.active-khidmat,
        .status-btn.btn-khidmat {
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-color: #8b5cf6;
            color: white;
            box-shadow: 0 3px 8px rgba(139, 92, 246, 0.4);
        }

        .status-btn.active-absen,
        .status-btn.btn-absen {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border-color: #ef4444;
            color: white;
            box-shadow: 0 3px 8px rgba(239, 68, 68, 0.4);
        }

        /* ========== SUBMIT BUTTON ========== */
        .btn-submit {
            background: var(--gradient-1);
            color: white;
            border: none;
            border-radius: 15px;
            padding: 18px;
            font-size: 1rem;
            font-weight: 800;
            width: 100%;
            box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-submit:active {
            transform: scale(0.98);
            box-shadow: 0 2px 8px rgba(23, 162, 184, 0.3);
        }

        .btn-submit i {
            font-size: 1.2rem;
        }

        /* ========== EMPTY STATE ========== */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 5rem;
            color: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state h5 {
            color: var(--text-primary);
            font-weight: 800;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* ========== ALERT ========== */
        .alert {
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 5px 5px 10px var(--shadow-dark),
                       -5px -5px 10px var(--shadow-light);
        }
    </style>

    <div id="header-section">
        <div class="header-content">
            <a href="{{ route('saungsantri.dashboard.karyawan') }}" class="back-btn">
                <ion-icon name="arrow-back-outline"></ion-icon>
            </a>
            <div class="header-title">
                <h3>Ijin Santri</h3>
                <p>Data Ijin & Kepulangan Santri</p>
            </div>
        </div>
    </div>

    <div id="content-section">
        {{-- Date Filter --}}
        <div class="date-card">
            <label class="form-label">Filter Tanggal</label>
            <input type="date" 
                class="form-control" 
                id="filterDate"
                value="{{ request('date', date('Y-m-d')) }}">
        </div>

        {{-- Alert --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Ijin List Table --}}
        <div class="list-container">
            <div class="list-header">
                <span class="header-col">#</span>
                <span class="header-col">Santri Name</span>
                <span class="header-col status">Actions</span>
            </div>
            
            @if($ijinSantri->count() > 0)
                @foreach($ijinSantri as $index => $ijin)
                <div class="santri-item">
                    <div class="santri-header">
                        <div class="santri-number">{{ $index + 1 }}</div>
                        <div class="santri-info">
                            <div class="santri-name">{{ $ijin->santri->nama_lengkap ?? 'Santri Tidak Ditemukan' }}</div>
                            <div class="santri-nis">ACK#{{ $ijin->santri->nis ?? '-' }}</div>
                        </div>
                        <div class="status-buttons">
                            <a href="{{ route('ijin-santri.karyawan.show', $ijin->id) }}" class="status-btn btn-hadir">
                                <i class="ti ti-eye"></i>
                                <span>Lihat</span>
                            </a>
                            <a href="{{ route('ijin-santri.karyawan.show', $ijin->id) }}" class="status-btn btn-ijin">
                                <i class="ti ti-file-text"></i>
                                <span>Detail</span>
                            </a>
                            @if($ijin->status == 'kembali')
                                <span class="status-btn btn-hadir">
                                    <i class="ti ti-circle-check"></i>
                                    <span>Kembali</span>
                                </span>
                            @elseif($ijin->status == 'dipulangkan')
                                <span class="status-btn btn-ijin">
                                    <i class="ti ti-plane-departure"></i>
                                    <span>Pulang</span>
                                </span>
                            @elseif($ijin->status == 'ttd_ustadz')
                                <span class="status-btn btn-khidmat">
                                    <i class="ti ti-writing-sign"></i>
                                    <span>TTD</span>
                                </span>
                            @else
                                <span class="status-btn btn-sakit">
                                    <i class="ti ti-clock"></i>
                                    <span>Pending</span>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="empty-state">
                    <i class="ti ti-file-text-off"></i>
                    <h5>Tidak Ada Data Ijin</h5>
                    <p>Belum ada data ijin santri yang tersedia</p>
                </div>
            @endif
        </div>

        <div style="height: 80px;"></div>
    </div>
@endsection

@push('myscript')
<script>
    // Auto hide alert setelah 3 detik
    @if(session('success') || session('error'))
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 3000);
    @endif

    // Filter by date
    document.getElementById('filterDate').addEventListener('change', function() {
        window.location.href = '{{ route("ijin-santri.karyawan.index") }}?date=' + this.value;
    });
</script>
@endpush
