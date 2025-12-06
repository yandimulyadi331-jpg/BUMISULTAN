@extends('layouts.mobile.app')
@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-primary);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }

        /* Navbar Bottom Adjustment */
        .appBottomMenu .item.active-lembur .col {
            background: var(--bg-primary);
            border-radius: 16px;
            padding: 8px 12px;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
        }

        .appBottomMenu .item.active-lembur {
            color: var(--text-primary);
        }

        .appBottomMenu .item.active-lembur ion-icon {
            color: var(--text-primary);
            transform: scale(1.1);
        }

        .appBottomMenu .item.active-lembur strong {
            color: var(--text-primary);
            font-weight: 700;
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
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .logo-wrapper {
            display: flex;
            flex-direction: column;
        }

        .logo-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
            letter-spacing: -0.5px;
        }

        .back-button {
            background: var(--bg-primary);
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

        .back-button:active {
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .back-button ion-icon {
            font-size: 24px;
            color: var(--icon-color);
        }

        /* Content Section */
        #content-section {
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
            padding-bottom: 100px;
        }

        /* Filter Card with Animated Rainbow Border */
        .filter-card {
            background: var(--bg-primary);
            border-radius: 20px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            position: relative;
            overflow: visible;
        }

        .filter-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #ff0000, #ff7f00, #ffff00, #00ff00,
                #0000ff, #4b0082, #9400d3, #ff0000
            );
            background-size: 400% 400%;
            border-radius: 22px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .filter-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 20px;
            z-index: -1;
        }

        @keyframes gradientMove {
            0%, 100% {
                background-position: 0% 50%;
            }
            50% {
                background-position: 100% 50%;
            }
        }

        .filter-input {
            background: var(--bg-primary);
            border: none;
            border-radius: 12px;
            padding: 14px 18px;
            width: 100%;
            margin-bottom: 12px;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .filter-input:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .filter-input::placeholder {
            color: var(--text-secondary);
        }

        .btn-search {
            background: linear-gradient(145deg, #27ae60, #229954);
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-search:active {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
            transform: scale(0.98);
        }

        /* Lembur Card with Animated Rainbow Border */
        .lembur-card {
            background: var(--bg-primary);
            border-radius: 18px;
            padding: 18px;
            margin-bottom: 15px;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: block;
            position: relative;
            overflow: visible;
        }

        /* Rainbow border untuk card pending (Orange-Yellow theme) */
        .lembur-card.status-pending-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #ff9500, #ffaa00, #ffbf00, #ffd400,
                #ffe900, #ffff00, #ffe900, #ffd400,
                #ffbf00, #ffaa00, #ff9500
            );
            background-size: 400% 400%;
            border-radius: 20px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .lembur-card.status-pending-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 18px;
            z-index: -1;
        }

        /* Rainbow border untuk card approved (Green-Cyan theme) */
        .lembur-card.status-approved-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #00ff00, #00ff7f, #00ffff, #00bfff,
                #007fff, #003fff, #007fff, #00bfff,
                #00ffff, #00ff7f, #00ff00
            );
            background-size: 400% 400%;
            border-radius: 20px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .lembur-card.status-approved-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 18px;
            z-index: -1;
        }

        /* Rainbow border untuk card rejected (Red-Pink theme) */
        .lembur-card.status-rejected-card::before {
            content: '';
            position: absolute;
            top: -2px;
            left: -2px;
            right: -2px;
            bottom: -2px;
            background: linear-gradient(
                45deg,
                #ff0000, #ff3333, #ff6666, #ff9999,
                #ffcccc, #ff9999, #ff6666, #ff3333,
                #ff0000, #cc0000, #990000
            );
            background-size: 400% 400%;
            border-radius: 20px;
            z-index: -1;
            animation: gradientMove 8s ease infinite;
        }

        .lembur-card.status-rejected-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--bg-primary);
            border-radius: 18px;
            z-index: -1;
        }

        .lembur-card:hover {
            transform: translateY(-2px);
        }

        .lembur-card:active {
            transform: translateY(1px);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
        }

        .lembur-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 12px;
        }

        .lembur-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(145deg, #27ae60, #229954);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--shadow-light);
        }

        .lembur-icon ion-icon {
            font-size: 28px;
            color: white;
        }

        .lembur-info {
            flex: 1;
        }

        .lembur-date {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 4px 0;
        }

        .lembur-time {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0 0 4px 0;
        }

        .lembur-description {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .lembur-status {
            margin-left: auto;
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .status-pending {
            background: linear-gradient(145deg, #f39c12, #e67e22);
            color: white;
            box-shadow: 0 2px 8px rgba(243, 156, 18, 0.3);
        }

        .status-approved {
            background: linear-gradient(145deg, #27ae60, #229954);
            color: white;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }

        .status-rejected {
            background: linear-gradient(145deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }

        /* Floating Action Button */
        .fab-container {
            position: fixed;
            bottom: 90px;
            right: 20px;
            z-index: 99;
        }

        .fab-add {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(145deg, #3498db, #2980b9);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .fab-add:active {
            transform: scale(0.95);
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
        }

        .fab-add ion-icon {
            font-size: 32px;
            color: white;
        }

        /* Modal Popup */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 999;
            animation: fadeIn 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: var(--bg-primary);
            border-radius: 25px;
            padding: 30px 25px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 20px 20px 40px var(--shadow-dark),
                       -20px -20px 40px var(--shadow-light);
            animation: slideUp 0.4s ease;
            position: relative;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
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

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
        }

        .modal-close {
            background: var(--bg-primary);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--shadow-light);
            transition: all 0.3s ease;
        }

        .modal-close:active {
            box-shadow: inset 3px 3px 6px var(--shadow-dark),
                       inset -3px -3px 6px var(--shadow-light);
        }

        .modal-close ion-icon {
            font-size: 24px;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 8px;
            display: block;
        }

        .form-input {
            background: var(--bg-primary);
            border: none;
            border-radius: 15px;
            padding: 14px 18px;
            width: 100%;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
        }

        .form-textarea {
            background: var(--bg-primary);
            border: none;
            border-radius: 15px;
            padding: 14px 18px;
            width: 100%;
            min-height: 100px;
            resize: vertical;
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--shadow-light);
            color: var(--text-primary);
            font-size: 0.95rem;
            transition: all 0.3s ease;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .form-textarea:focus {
            outline: none;
            box-shadow: inset 6px 6px 12px var(--shadow-dark),
                       inset -6px -6px 12px var(--shadow-light);
        }

        .form-textarea::placeholder {
            color: var(--text-secondary);
        }

        .btn-submit {
            background: linear-gradient(145deg, #27ae60, #229954);
            border: none;
            border-radius: 15px;
            padding: 15px;
            width: 100%;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--shadow-light);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            margin-top: 10px;
        }

        .btn-submit:active {
            box-shadow: inset 4px 4px 8px rgba(0, 0, 0, 0.2),
                       inset -4px -4px 8px rgba(255, 255, 255, 0.1);
            transform: scale(0.98);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state ion-icon {
            font-size: 80px;
            color: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 20px;
        }

        .empty-state p {
            font-size: 1rem;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .lembur-card {
                padding: 15px;
            }

            .lembur-icon {
                width: 42px;
                height: 42px;
            }

            .lembur-icon ion-icon {
                font-size: 24px;
            }
        }
    </style>

    <!-- Header -->
    <div id="header-section">
        <div class="logo-wrapper">
            <span class="logo-title">Lembur</span>
        </div>
        <a href="{{ route('dashboard.index') }}" class="back-button">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
    </div>

    <!-- Content -->
    <div id="content-section">
        <!-- Filter Card -->
        <div class="filter-card">
            <form action="{{ route('lembur.index') }}" method="GET">
                <input type="text" class="filter-input" name="dari" placeholder="Dari" id="filterDari"
                    value="{{ Request('dari') }}" />
                <input type="text" class="filter-input" name="sampai" placeholder="Sampai" id="filterSampai"
                    value="{{ Request('sampai') }}" />
                <button type="submit" class="btn-search">
                    <ion-icon name="search-outline"></ion-icon>
                    Cari
                </button>
            </form>
        </div>

        <!-- Lembur List -->
        <div class="lembur-list">
            @forelse ($lembur as $d)
                <form method="POST" name="deleteform" class="deleteform"
                    action="{{ route('lembur.delete', Crypt::encrypt($d->id)) }}">
                    @csrf
                    @method('DELETE')
                    <a href="#" class="lembur-card 
                        {{ $d->status == 0 ? 'cancel-confirm status-pending-card' : '' }}
                        {{ $d->status == 1 ? 'status-approved-card' : '' }}
                        {{ $d->status == 2 ? 'status-rejected-card' : '' }}">
                        <div class="lembur-header">
                            <div class="lembur-icon">
                                <ion-icon name="time-outline"></ion-icon>
                            </div>
                            <div class="lembur-info">
                                <h6 class="lembur-date">{{ DateToIndo($d->tanggal) }}</h6>
                                <p class="lembur-time">
                                    {{ date('d-m-Y H:i', strtotime($d->lembur_mulai)) }} - 
                                    {{ date('H:i', strtotime($d->lembur_selesai)) }}
                                </p>
                                <p class="lembur-description">{{ $d->keterangan }}</p>
                            </div>
                            <div class="lembur-status">
                                @if ($d->status == 0)
                                    <span class="status-badge status-pending">Pending</span>
                                @elseif ($d->status == 1)
                                    <span class="status-badge status-approved">Disetujui</span>
                                @elseif ($d->status == 2)
                                    <span class="status-badge status-rejected">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </form>
            @empty
                <div class="empty-state">
                    <ion-icon name="calendar-outline"></ion-icon>
                    <p>Belum ada data lembur</p>
                </div>
            @endforelse
        </div>

        <!-- Floating Action Button -->
        <div class="fab-container">
            <button onclick="openModal()" class="fab-add">
                <ion-icon name="add-outline"></ion-icon>
            </button>
        </div>

        <div style="height: 20px;"></div>
    </div>

    <!-- Modal Popup untuk Ajukan Lembur -->
    <div class="modal-overlay" id="modalLembur">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Ajukan Lembur</h3>
                <button class="modal-close" onclick="closeModal()">
                    <ion-icon name="close-outline"></ion-icon>
                </button>
            </div>

            <form action="{{ route('lembur.store') }}" method="POST" id="formLembur">
                @csrf
                
                <div class="form-group">
                    <label class="form-label">Dari</label>
                    <input type="text" class="form-input dari" name="dari" placeholder="Klik untuk pilih tanggal & waktu" id="modalDari" style="cursor: pointer;" />
                </div>

                <div class="form-group">
                    <label class="form-label">Sampai</label>
                    <input type="text" class="form-input sampai" name="sampai" placeholder="Klik untuk pilih tanggal & waktu" id="modalSampai" style="cursor: pointer;" />
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea class="form-textarea keterangan" name="keterangan" placeholder="Masukkan keterangan lembur..."></textarea>
                </div>

                <button type="submit" class="btn-submit" id="btnSimpan">
                    <ion-icon name="send-outline"></ion-icon>
                    <span>Ajukan Lembur</span>
                </button>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        // Date Picker Variables
        var datePickerInitialized = false;

        // Modal Functions
        function openModal() {
            document.getElementById('modalLembur').classList.add('active');
            document.body.style.overflow = 'hidden';
            
            // Initialize date pickers after modal animation completes
            if (!datePickerInitialized) {
                setTimeout(function() {
                    initializeDatePickers();
                    datePickerInitialized = true;
                }, 500);
            }
        }

        function initializeDatePickers() {
            console.log('Initializing MODAL date pickers...');
            
            var lang = {
                title: 'Pilih Tanggal & Waktu',
                cancel: 'Batal',
                confirm: 'Pilih',
                year: 'Tahun',
                month: 'Bulan',
                day: 'Hari',
                hour: 'Jam',
                min: 'Menit',
                sec: 'Detik'
            };

            // Initialize MODAL "Dari" date picker
            new Rolldate({
                el: '#modalDari',
                format: 'YYYY-MM-DD hh:mm',
                beginYear: 2000,
                endYear: 2100,
                lang: lang
            });
            
            // Initialize MODAL "Sampai" date picker
            new Rolldate({
                el: '#modalSampai',
                format: 'YYYY-MM-DD hh:mm',
                beginYear: 2000,
                endYear: 2100,
                lang: lang
            });

            console.log('MODAL date pickers initialized successfully!');
        }

        function closeModal() {
            document.getElementById('modalLembur').classList.remove('active');
            document.body.style.overflow = 'auto';
            document.getElementById('formLembur').reset();
        }

        // Close modal when clicking outside
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize filter card date pickers
            initializeFilterDatePickers();
            
            document.getElementById('modalLembur').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal();
                }
            });
        });

        // Initialize date pickers for FILTER CARD
        function initializeFilterDatePickers() {
            console.log('Initializing FILTER date pickers...');
            
            var lang = {
                title: 'Pilih Tanggal',
                cancel: 'Batal',
                confirm: 'Pilih',
                year: 'Tahun',
                month: 'Bulan',
                day: 'Hari'
            };

            // Initialize FILTER "Dari" date picker (without time)
            new Rolldate({
                el: '#filterDari',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang
            });
            
            // Initialize FILTER "Sampai" date picker (without time)
            new Rolldate({
                el: '#filterSampai',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang
            });

            console.log('FILTER date pickers initialized successfully!');
        }

        // Form Validation & Submit
        $(document).ready(function() {
            $("#formLembur").submit(function(e) {
                e.preventDefault();
                
                let dari = $('#modalDari').val();
                let sampai = $('#modalSampai').val();
                let keterangan = $('.keterangan').val();

                if (dari == "" || sampai == "") {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Periode Lembur Harus Diisi!',
                        icon: "warning",
                        showConfirmButton: true
                    });
                    return false;
                } else if (sampai < dari) {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Tanggal selesai harus setelah tanggal mulai!',
                        icon: "warning",
                        showConfirmButton: true
                    });
                    return false;
                } else if (keterangan == '') {
                    Swal.fire({
                        title: "Oops!",
                        text: 'Keterangan Harus Diisi!',
                        icon: "warning",
                        showConfirmButton: true,
                        didClose: () => {
                            $('.keterangan').focus();
                        }
                    });
                    return false;
                }

                // Disable button & show loading
                buttonDisabled();

                // Submit form via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: 'Pengajuan lembur berhasil dikirim',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        
                        setTimeout(() => {
                            closeModal();
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        buttonEnabled();
                        Swal.fire({
                            title: 'Gagal!',
                            text: xhr.responseJSON?.message || 'Terjadi kesalahan saat mengirim pengajuan',
                            icon: 'error'
                        });
                    }
                });

                return false;
            });
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
                <div class="spinner-border spinner-border-sm text-white" role="status"></div>
                <span>Mengirim...</span>
            `);
        }

        function buttonEnabled() {
            $("#btnSimpan").prop('disabled', false);
            $("#btnSimpan").html(`
                <ion-icon name="send-outline"></ion-icon>
                <span>Ajukan Lembur</span>
            `);
        }
    </script>
@endpush
