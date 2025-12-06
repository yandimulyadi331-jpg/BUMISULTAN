@extends('layouts.app')

@push('scripts')
<!-- SheetJS Library for Excel Processing -->
<script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
@endpush

@section('content')
<style>
    /* Custom compact button styles */
    .btn-xs {
        padding: 2px 6px !important;
        font-size: 12px !important;
        line-height: 1.4 !important;
    }
    
    .btn-group-sm .btn {
        padding: 3px 6px !important;
        font-size: 12px !important;
        border-radius: 0 !important;
    }
    
    .btn-group-sm .btn:first-child {
        border-top-left-radius: 0.25rem !important;
        border-bottom-left-radius: 0.25rem !important;
    }
    
    .btn-group-sm .btn:last-child {
        border-top-right-radius: 0.25rem !important;
        border-bottom-right-radius: 0.25rem !important;
    }
    
    .btn-group-sm .btn i {
        font-size: 13px !important;
    }
    
    /* Hover effects */
    .btn-xs:hover, .btn-group-sm .btn:hover {
        transform: scale(1.05);
        transition: transform 0.2s;
        z-index: 2;
    }
    
    /* Form inline untuk hapus */
    .btn-group-sm form {
        display: inline-block;
        margin: 0;
    }
    
    /* Upload button styling */
    .btn-upload {
        font-size: 11px !important;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    /* Modal foto enhancement */
    .modal-xl .modal-body img {
        box-shadow: 0 10px 30px rgba(0,0,0,0.2) !important;
    }
    
    /* Tombol plus tambah transaksi */
    .btn-add-transaction {
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .btn-add-transaction:hover {
        transform: scale(1.15) rotate(90deg);
        box-shadow: 0 4px 12px rgba(40,167,69,0.4);
    }
    
    .btn-add-transaction:active {
        transform: scale(1.05) rotate(90deg);
    }
    
    /* Dropdown Kategori Styling */
    .kategori-select {
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        padding: 6px 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .kategori-select:hover {
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.1);
    }
    
    .kategori-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    
    .kategori-select option {
        padding: 8px;
        font-size: 13px;
    }
    
    .kategori-select optgroup {
        font-weight: 700;
        font-size: 11px;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        background: #f3f4f6;
        padding: 6px 8px;
    }
    
    /* Stock Market Table Style */
    .stock-table {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
        font-size: 13px;
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 0;
    }
    
    .stock-table thead th {
        background: #f9fafb;
        color: #6b7280;
        font-weight: 600;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 12px 16px;
        border-bottom: 1px solid #e5e7eb;
        border-right: 1px solid #f3f4f6;
        white-space: nowrap;
    }
    
    .stock-table thead th:last-child {
        border-right: none;
    }
    
    .stock-table tbody tr {
        border-bottom: 1px solid #f3f4f6;
        transition: background-color 0.15s ease;
    }
    
    .stock-table tbody tr:hover {
        background: #f9fafb;
    }
    
    .stock-table tbody td {
        padding: 10px 16px;
        color: #1f2937;
        font-size: 13px;
        border-right: 1px solid #f9fafb;
        vertical-align: middle;
    }
    
    .stock-table tbody td:last-child {
        border-right: none;
    }
    
    /* Typography */
    .stock-table .company-name {
        font-weight: 500;
        color: #111827;
    }
    
    .stock-table .trade-time {
        color: #6b7280;
        font-size: 12px;
    }
    
    .stock-table .price-positive {
        color: #059669;
        font-weight: 500;
    }
    
    .stock-table .price-negative {
        color: #dc2626;
        font-weight: 500;
    }
    
    .stock-table .price-neutral {
        color: #6b7280;
    }
    
    /* Badge minimalist */
    .badge-minimal {
        display: inline-block;
        padding: 3px 8px;
        font-size: 11px;
        font-weight: 500;
        border-radius: 3px;
        letter-spacing: 0.3px;
    }
    
    .badge-minimal.bg-income {
        background: #d1fae5;
        color: #065f46;
    }
    
    .badge-minimal.bg-expense {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .badge-minimal.bg-balance {
        background: #dbeafe;
        color: #1e40af;
    }
    
    /* Saldo Awal Row */
    .saldo-awal-row {
        background: #f0f9ff !important;
        border-left: 3px solid #3b82f6 !important;
    }
    
    .saldo-awal-row:hover {
        background: #e0f2fe !important;
    }
    
    /* Remove old table classes effect */
    .stock-table.table-striped tbody tr:nth-of-type(odd) {
        background-color: transparent;
    }
    
    .stock-table.table-hover tbody tr:hover {
        background-color: #f9fafb;
    }
    
    /* Number styling */
    .stock-number {
        font-family: 'Courier New', monospace;
        font-size: 13px;
        font-weight: 500;
    }
</style>

<div class="container-xxl flex-grow-1 container-p-y">
    
    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-white mb-2"><i class="ti ti-cash me-2"></i>MANAJEMEN KEUANGAN</h3>
                    <p class="mb-0">Pencatatan Dana Masuk & Keluar Harian</p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h5 class="mb-0">Riwayat Transaksi</h5>
                        <small class="text-muted">Periode: {{ $periodeLabel ?? 'Semua Data' }}</small>
                    </div>
                </div>
                
                <!-- Filter Pencarian -->
                <div class="card-body border-bottom">
                    <form method="GET" class="row g-3" id="formFilter">
                        <div class="col-md-2">
                            <label class="form-label">Tipe Filter</label>
                            <select class="form-select" name="filter_type" id="filterType" onchange="toggleFilterInputs()">
                                <option value="bulan" {{ request('filter_type', 'bulan') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                                <option value="tahun" {{ request('filter_type') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                                <option value="minggu" {{ request('filter_type') == 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                                <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Range Tanggal</option>
                            </select>
                        </div>
                        
                        <div class="col-md-2" id="inputBulan" style="display: none;">
                            <label class="form-label">Bulan</label>
                            <input type="month" class="form-control" name="bulan" value="{{ request('bulan', date('Y-m')) }}">
                        </div>
                        
                        <div class="col-md-2" id="inputTahun" style="display: none;">
                            <label class="form-label">Tahun</label>
                            <input type="number" class="form-control" name="tahun" value="{{ request('tahun', date('Y')) }}" min="2020" max="2099">
                        </div>
                        
                        <div class="col-md-2" id="inputMinggu" style="display: none;">
                            <label class="form-label">Minggu</label>
                            <input type="week" class="form-control" name="minggu" value="{{ request('minggu') }}">
                        </div>
                        
                        <div class="col-md-2" id="inputRangeStart" style="display: none;">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                        </div>
                        
                        <div class="col-md-2" id="inputRangeEnd" style="display: none;">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                        </div>
                        
                        <div class="col-md-4 d-flex align-items-end gap-2">
                            <button type="submit" class="btn" style="border: 1px solid #d1d5db; border-radius: 0.375rem; height: 38px; padding: 0.375rem 0.75rem; font-size: 1rem; background: #fff; color: #374151;">
                                <i class="bx bx-search me-1"></i> Tampilkan
                            </button>
                            <a href="{{ route('dana-operasional.index') }}" class="btn" style="border: 1px solid #d1d5db; border-radius: 0.375rem; height: 38px; padding: 0.375rem 0.75rem; font-size: 1rem; display: inline-flex; align-items: center; background: #fff; color: #374151; text-decoration: none;">
                                <i class="bx bx-reset me-1"></i> Reset
                            </a>
                            <button type="button" class="btn" onclick="downloadPDF()" style="border: 1px solid #d1d5db; border-radius: 0.375rem; height: 38px; padding: 0.375rem 0.75rem; font-size: 1rem; background: #fff; color: #374151;">
                                <i class="bx bxs-file-pdf me-1"></i> Download PDF
                            </button>
                            <button type="button" class="btn" onclick="kirimEmailLaporan()" style="border: 1px solid #d1d5db; border-radius: 0.375rem; height: 38px; padding: 0.375rem 0.75rem; font-size: 1rem; background: #fff; color: #374151;">
                                <i class="bx bx-envelope me-1"></i> Kirim Email
                            </button>
                            <a href="{{ route('laporan-keuangan.index') }}" class="btn" title="Laporan Keuangan Annual Report" style="border: 1px solid #d1d5db; border-radius: 0.375rem; height: 38px; padding: 0.375rem 0.75rem; font-size: 1rem; display: inline-flex; align-items: center; background: #fff; color: #374151; text-decoration: none;">
                                <i class="bx bxs-report me-1"></i> Annual Report
                            </a>
                        </div>
                    </form>
                </div>
                
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table stock-table mb-0">
                            <thead>
                                <tr>
                                    <th width="2%" class="text-center">No</th>
                                    <th width="6%">Tanggal</th>
                                    <th width="4%" class="text-center">Jam</th>
                                    <th width="8%">No. Transaksi</th>
                                    <th width="8%">Kategori</th>
                                    <th width="20%">Keterangan</th>
                                    <th width="10%" class="text-end">Dana Masuk</th>
                                    <th width="10%" class="text-end">Dana Keluar</th>
                                    <th width="10%" class="text-end">Saldo</th>
                                    <th width="4%" class="text-center">Foto</th>
                                    <th width="18%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $no = 1;
                                    $saldoRunning = 0;
                                @endphp
                                
                                @forelse($riwayatSaldo as $saldo)
                                    @php
                                        $tanggalKey = $saldo->tanggal->format('Y-m-d');
                                        $transaksiHariIni = isset($realisasiPerTanggal[$tanggalKey]) ? $realisasiPerTanggal[$tanggalKey] : collect([]);
                                        
                                        // DEBUG: Tampilkan jumlah transaksi dan status
                                        // echo "<!-- Tanggal: {$tanggalKey}, Total Transaksi: {$transaksiHariIni->count()} -->";
                                        // foreach($transaksiHariIni as $t) {
                                        //     echo "<!-- ID: {$t->id}, Status: " . ($t->status ?? 'NULL') . " -->";
                                        // }
                                        
                                        // Set saldo running = saldo awal hari ini
                                        $saldoRunning = $saldo->saldo_awal;
                                    @endphp
                                    
                                    {{-- 1. BARIS SALDO AWAL --}}
                                    <tr class="saldo-awal-row">
                                        <td class="text-center">{{ $no++ }}</td>
                                        <td class="company-name">{{ $saldo->tanggal->format('d-M-Y') }}</td>
                                        <td class="text-center trade-time">-</td>
                                        <td><span class="badge-minimal bg-balance">SALDO AWAL</span></td>
                                        <td class="trade-time">-</td>
                                        <td class="company-name">Sisa saldo sebelumnya</td>
                                        <td class="text-end stock-number price-positive">
                                            {{ $saldo->saldo_awal > 0 ? number_format($saldo->saldo_awal, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end stock-number price-negative">
                                            {{ $saldo->saldo_awal < 0 ? number_format(abs($saldo->saldo_awal), 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="text-end stock-number" style="font-weight: 600; color: #1e40af;">{{ number_format($saldoRunning, 0, ',', '.') }}</td>
                                        <td class="text-center">-</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center">
                                                <a href="javascript:void(0)" 
                                                   onclick="editSaldoAwal({{ $saldo->id }}, '{{ $saldo->tanggal->format('Y-m-d') }}', {{ $saldo->saldo_awal }})"
                                                   class="me-2" 
                                                   title="Edit Saldo Awal">
                                                    <i class="ti ti-edit text-success"></i>
                                                </a>
                                                
                                                <form method="POST" class="deleteform d-inline-block"
                                                      action="{{ route('dana-operasional.delete-saldo-awal', $saldo->id) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <a href="#" class="delete-confirm" title="Hapus Saldo Awal">
                                                        <i class="ti ti-trash text-danger"></i>
                                                    </a>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    {{-- 2. TRANSAKSI HARIAN --}}
                                    @foreach($transaksiHariIni as $transaksi)
                                        @php
                                            // Update saldo running (ONLY for active transactions)
                                            if ($transaksi->status === 'active') {
                                                if ($transaksi->tipe_transaksi == 'masuk') {
                                                    $saldoRunning += $transaksi->nominal;
                                                } else {
                                                    $saldoRunning -= $transaksi->nominal;
                                                }
                                            }
                                        @endphp
                                        <tr class="{{ $transaksi->status === 'voided' ? 'text-decoration-line-through' : '' }}" style="{{ $transaksi->status === 'voided' ? 'opacity: 0.5;' : '' }}">
                                            <td class="text-center trade-time">{{ $no++ }}</td>
                                            <td class="company-name">{{ $transaksi->tanggal_realisasi->format('d-M-Y') }}</td>
                                            <td class="text-center trade-time">
                                                <small>{{ $transaksi->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <span class="company-name" style="font-size: 12px;">{{ $transaksi->nomor_transaksi ?? 'N/A' }}</span>
                                                @if($transaksi->status === 'voided')
                                                    <br><span class="badge-minimal bg-expense mt-1">VOID</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($transaksi->status === 'active')
                                                    {{-- Dropdown Kategori yang Bisa Langsung Diubah --}}
                                                    <select class="form-select form-select-sm kategori-select" 
                                                            data-transaksi-id="{{ $transaksi->id }}"
                                                            onchange="updateKategori({{ $transaksi->id }}, this.value)"
                                                            style="width: auto; min-width: 140px; font-size: 12px;">
                                                        <option value="">-- Pilih Kategori --</option>
                                                        
                                                        <optgroup label="OPERASIONAL">
                                                            <option value="Transportasi" {{ $transaksi->kategori === 'Transportasi' ? 'selected' : '' }}>Transportasi</option>
                                                            <option value="Konsumsi" {{ $transaksi->kategori === 'Konsumsi' ? 'selected' : '' }}>Konsumsi</option>
                                                            <option value="ATK" {{ $transaksi->kategori === 'ATK' ? 'selected' : '' }}>ATK (Alat Tulis Kantor)</option>
                                                            <option value="Utilitas" {{ $transaksi->kategori === 'Utilitas' ? 'selected' : '' }}>Utilitas (Listrik, Air, Internet)</option>
                                                            <option value="Kebersihan" {{ $transaksi->kategori === 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                                                            <option value="Keamanan" {{ $transaksi->kategori === 'Keamanan' ? 'selected' : '' }}>Keamanan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="SDM & GAJI">
                                                            <option value="Gaji Karyawan" {{ $transaksi->kategori === 'Gaji Karyawan' ? 'selected' : '' }}>Gaji Karyawan</option>
                                                            <option value="Gaji Tukang" {{ $transaksi->kategori === 'Gaji Tukang' ? 'selected' : '' }}>Gaji Tukang</option>
                                                            <option value="Lembur" {{ $transaksi->kategori === 'Lembur' ? 'selected' : '' }}>Lembur</option>
                                                            <option value="Bonus" {{ $transaksi->kategori === 'Bonus' ? 'selected' : '' }}>Bonus</option>
                                                            <option value="Tunjangan" {{ $transaksi->kategori === 'Tunjangan' ? 'selected' : '' }}>Tunjangan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="PROYEK & MATERIAL">
                                                            <option value="Material Bangunan" {{ $transaksi->kategori === 'Material Bangunan' ? 'selected' : '' }}>Material Bangunan</option>
                                                            <option value="Peralatan" {{ $transaksi->kategori === 'Peralatan' ? 'selected' : '' }}>Peralatan</option>
                                                            <option value="Sewa Alat" {{ $transaksi->kategori === 'Sewa Alat' ? 'selected' : '' }}>Sewa Alat</option>
                                                            <option value="Renovasi" {{ $transaksi->kategori === 'Renovasi' ? 'selected' : '' }}>Renovasi</option>
                                                            <option value="Pemeliharaan" {{ $transaksi->kategori === 'Pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="KEUANGAN">
                                                            <option value="Infaq/Sedekah" {{ $transaksi->kategori === 'Infaq/Sedekah' ? 'selected' : '' }}>Infaq/Sedekah</option>
                                                            <option value="Donasi" {{ $transaksi->kategori === 'Donasi' ? 'selected' : '' }}>Donasi</option>
                                                            <option value="Pinjaman" {{ $transaksi->kategori === 'Pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                                            <option value="Pembayaran Hutang" {{ $transaksi->kategori === 'Pembayaran Hutang' ? 'selected' : '' }}>Pembayaran Hutang</option>
                                                            <option value="Investasi" {{ $transaksi->kategori === 'Investasi' ? 'selected' : '' }}>Investasi</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="PENDIDIKAN">
                                                            <option value="Biaya Santri" {{ $transaksi->kategori === 'Biaya Santri' ? 'selected' : '' }}>Biaya Santri</option>
                                                            <option value="Buku & Modul" {{ $transaksi->kategori === 'Buku & Modul' ? 'selected' : '' }}>Buku & Modul</option>
                                                            <option value="Kegiatan Pendidikan" {{ $transaksi->kategori === 'Kegiatan Pendidikan' ? 'selected' : '' }}>Kegiatan Pendidikan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="ACARA & EVENT">
                                                            <option value="Event" {{ $transaksi->kategori === 'Event' ? 'selected' : '' }}>Event/Acara</option>
                                                            <option value="Perayaan" {{ $transaksi->kategori === 'Perayaan' ? 'selected' : '' }}>Perayaan</option>
                                                            <option value="Kegiatan Sosial" {{ $transaksi->kategori === 'Kegiatan Sosial' ? 'selected' : '' }}>Kegiatan Sosial</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="TEKNOLOGI">
                                                            <option value="IT & Komputer" {{ $transaksi->kategori === 'IT & Komputer' ? 'selected' : '' }}>IT & Komputer</option>
                                                            <option value="Software" {{ $transaksi->kategori === 'Software' ? 'selected' : '' }}>Software/Aplikasi</option>
                                                            <option value="Komunikasi" {{ $transaksi->kategori === 'Komunikasi' ? 'selected' : '' }}>Komunikasi (Pulsa, Paket Data)</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="ADMINISTRASI">
                                                            <option value="Perizinan" {{ $transaksi->kategori === 'Perizinan' ? 'selected' : '' }}>Perizinan</option>
                                                            <option value="Pajak" {{ $transaksi->kategori === 'Pajak' ? 'selected' : '' }}>Pajak</option>
                                                            <option value="Asuransi" {{ $transaksi->kategori === 'Asuransi' ? 'selected' : '' }}>Asuransi</option>
                                                            <option value="Legal" {{ $transaksi->kategori === 'Legal' ? 'selected' : '' }}>Legal/Hukum</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="KENDARAAN">
                                                            <option value="BBM" {{ $transaksi->kategori === 'BBM' ? 'selected' : '' }}>BBM (Bahan Bakar)</option>
                                                            <option value="Service Kendaraan" {{ $transaksi->kategori === 'Service Kendaraan' ? 'selected' : '' }}>Service Kendaraan</option>
                                                            <option value="Sewa Kendaraan" {{ $transaksi->kategori === 'Sewa Kendaraan' ? 'selected' : '' }}>Sewa Kendaraan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="KESEHATAN">
                                                            <option value="Kesehatan" {{ $transaksi->kategori === 'Kesehatan' ? 'selected' : '' }}>Kesehatan</option>
                                                            <option value="Obat-obatan" {{ $transaksi->kategori === 'Obat-obatan' ? 'selected' : '' }}>Obat-obatan</option>
                                                        </optgroup>
                                                        
                                                        <optgroup label="LAINNYA">
                                                            <option value="Lain-Lain" {{ $transaksi->kategori === 'Lain-Lain' ? 'selected' : '' }}>Lain-Lain</option>
                                                            <option value="Umum" {{ $transaksi->kategori === 'Umum' ? 'selected' : '' }}>Umum</option>
                                                            <option value="Operasional Umum" {{ $transaksi->kategori === 'Operasional Umum' ? 'selected' : '' }}>Operasional Umum</option>
                                                        </optgroup>
                                                    </select>
                                                @else
                                                    {{-- Tampilan Badge untuk transaksi yang sudah void --}}
                                                    @if($transaksi->kategori)
                                                        @php
                                                            $badgeColor = 'bg-info';
                                                            if($transaksi->kategori === 'Transportasi') $badgeColor = 'bg-primary';
                                                            elseif($transaksi->kategori === 'Konsumsi') $badgeColor = 'bg-warning';
                                                            elseif($transaksi->kategori === 'Utilitas') $badgeColor = 'bg-success';
                                                            elseif($transaksi->kategori === 'ATK') $badgeColor = 'bg-secondary';
                                                            elseif(str_contains($transaksi->kategori, 'Gaji')) $badgeColor = 'bg-danger';
                                                        @endphp
                                                        <span class="badge {{ $badgeColor }}">{{ $transaksi->kategori }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                {{ $transaksi->keterangan ?? $transaksi->uraian }}
                                                @if($transaksi->status === 'voided' && $transaksi->void_reason)
                                                    <br><small class="text-danger"><strong>Void:</strong> {{ $transaksi->void_reason }}</small>
                                                @endif
                                            </td>
                                            <td class="text-end stock-number">
                                                @if($transaksi->tipe_transaksi == 'masuk')
                                                    <span class="price-positive">{{ number_format($transaksi->nominal, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="price-neutral">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end stock-number">
                                                @if($transaksi->tipe_transaksi == 'keluar')
                                                    <span class="price-negative">{{ number_format($transaksi->nominal, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="price-neutral">-</span>
                                                @endif
                                            </td>
                                            <td class="text-end stock-number" style="font-weight: 600;">{{ number_format($saldoRunning, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if($transaksi->foto_bukti)
                                                    <button type="button" class="btn btn-info btn-xs p-1" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $transaksi->id }}" title="Lihat Foto">
                                                        <i class="ti ti-photo" style="font-size: 14px;"></i>
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-primary btn-xs p-1 px-2 btn-upload" onclick="document.getElementById('uploadFoto{{ $transaksi->id }}').click()" title="Upload Foto">
                                                        <i class="ti ti-upload" style="font-size: 11px;"></i> Upload
                                                    </button>
                                                    <input type="file" id="uploadFoto{{ $transaksi->id }}" accept="image/*" style="display: none;" onchange="uploadFotoBukti({{ $transaksi->id }}, this.files[0])">
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center">
                                                    <a href="javascript:void(0)" 
                                                       onclick="showDetail({{ $transaksi->id }})"
                                                       class="me-2" 
                                                       title="Detail">
                                                        <i class="ti ti-eye text-info"></i>
                                                    </a>
                                                    
                                                    <a href="javascript:void(0)" 
                                                       onclick="showEdit({{ $transaksi->id }})"
                                                       class="me-2" 
                                                       title="Edit">
                                                        <i class="ti ti-edit text-success"></i>
                                                    </a>
                                                    
                                                    <form method="POST" class="deleteform d-inline-block"
                                                          action="{{ route('dana-operasional.destroy', $transaksi->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <a href="#" class="delete-confirm" title="Hapus">
                                                            <i class="ti ti-trash text-danger"></i>
                                                        </a>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    
                                    {{-- 3. BARIS SUBTOTAL --}}
                                    <tr style="background: #fef3c7; border-top: 2px solid #f59e0b;">
                                        <td class="text-center trade-time">{{ $no++ }}</td>
                                        <td colspan="5"><strong style="color: #92400e; font-size: 13px;">SUBTOTAL</strong></td>
                                        <td class="text-end stock-number"><strong class="price-positive">{{ number_format($saldo->dana_masuk, 0, ',', '.') }}</strong></td>
                                        <td class="text-end stock-number"><strong class="price-negative">{{ number_format($saldo->total_realisasi, 0, ',', '.') }}</strong></td>
                                        <td class="text-end stock-number" style="font-weight: 700; font-size: 14px; color: #1e40af;">{{ number_format($saldo->saldo_akhir, 0, ',', '.') }}</td>
                                        <td class="text-center trade-time">-</td>
                                        <td class="text-center trade-time">-</td>
                                    </tr>
                                    
                                @empty
                                    <tr>
                                        <td colspan="11" class="text-center py-5" style="color: #9ca3af;">
                                            <i class="ti ti-database-off" style="font-size: 48px; opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                                            Belum ada transaksi untuk periode ini
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Transaksi --}}
<div class="modal fade" id="modalTambahTransaksi" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-plus me-2"></i>Tambah Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dana-operasional.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="tanggal_realisasi" id="tanggal_transaksi_hidden">
                    
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_display" id="tanggal_transaksi_display" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keterangan" rows="2" required placeholder="Contoh: Khidmat, BBM, Listrik, dll"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" name="tipe_transaksi" required onchange="toggleNominal(this.value)">
                            <option value="">-- Pilih --</option>
                            <option value="masuk">Dana Masuk</option>
                            <option value="keluar">Dana Keluar</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nominal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="nominal" required min="0" step="0.01" placeholder="0">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto Bukti (Opsional)</label>
                        <input type="file" class="form-control" name="foto_bukti" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Import Excel --}}
<div class="modal fade" id="modalImportExcel" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-upload me-2"></i>Import dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dana-operasional.import-excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Format Excel:</strong>
                        <ul class="mb-0 mt-2">
                            <li>Kolom: Tanggal, Keterangan, Dana Masuk, Dana Keluar</li>
                            <li>Download template untuk format lengkap</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-upload me-1"></i> Import
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Download PDF --}}
<div class="modal fade" id="modalDownloadPdf" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-file-type-pdf me-2"></i>Download PDF
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dana-operasional.export-pdf') }}" method="GET" id="formDownloadPdf">
                <div class="modal-body">
                    <!-- Tombol Aksi Cepat -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ti ti-zap me-1"></i>Aksi Cepat:
                        </label>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-info" onclick="setPdfMingguIni()">
                                <i class="ti ti-calendar-week me-2"></i>Minggu Ini
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="setPdfBulanIni()">
                                <i class="ti ti-calendar-month me-2"></i>Bulan Ini
                            </button>
                            <button type="button" class="btn btn-outline-warning" onclick="setPdfTahunIni()">
                                <i class="ti ti-calendar me-2"></i>Tahun Ini
                            </button>
                        </div>
                    </div>

                    <div class="border-top pt-3">
                        <label class="form-label fw-bold">
                            <i class="ti ti-calendar-event me-1"></i>Atau Pilih Periode Custom:
                        </label>
                        
                        <!-- Tipe Filter -->
                        <div class="mb-3">
                            <label class="form-label">Tipe Periode</label>
                            <select class="form-select" name="filter_type" id="pdfFilterType" onchange="togglePdfInputs()">
                                <option value="bulan" {{ request('filter_type') == 'bulan' ? 'selected' : '' }}>Per Bulan</option>
                                <option value="tahun" {{ request('filter_type') == 'tahun' ? 'selected' : '' }}>Per Tahun</option>
                                <option value="minggu" {{ request('filter_type') == 'minggu' ? 'selected' : '' }}>Per Minggu</option>
                                <option value="range" {{ request('filter_type') == 'range' ? 'selected' : '' }}>Range Tanggal</option>
                            </select>
                        </div>

                        <!-- Input Bulan -->
                        <div class="mb-3" id="pdfInputBulan" style="display: none;">
                            <label class="form-label">Bulan</label>
                            <input type="month" class="form-control" name="bulan" id="pdfBulan" value="{{ date('Y-m') }}">
                        </div>

                        <!-- Input Tahun -->
                        <div class="mb-3" id="pdfInputTahun" style="display: none;">
                            <label class="form-label">Tahun</label>
                            <input type="number" class="form-control" name="tahun" id="pdfTahun" value="{{ date('Y') }}" min="2020" max="2099">
                        </div>

                        <!-- Input Minggu -->
                        <div class="mb-3" id="pdfInputMinggu" style="display: none;">
                            <label class="form-label">Minggu</label>
                            <input type="week" class="form-control" name="minggu" id="pdfMinggu">
                        </div>

                        <!-- Input Range -->
                        <div class="mb-3" id="pdfInputRangeStart" style="display: none;">
                            <label class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" name="start_date" id="pdfStartDate">
                        </div>
                        <div class="mb-3" id="pdfInputRangeEnd" style="display: none;">
                            <label class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" name="end_date" id="pdfEndDate">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="ti ti-x me-1"></i>Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ti ti-download me-1"></i>Download
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Foto Bukti - Loop untuk setiap transaksi --}}
@if(isset($realisasiPerTanggal))
    @foreach($realisasiPerTanggal as $tanggalKey => $transaksiHariIni)
        @foreach($transaksiHariIni as $transaksi)
            @if($transaksi->foto_bukti)
                <div class="modal fade" id="modalFoto{{ $transaksi->id }}" tabindex="-1">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title text-white">
                                    <i class="ti ti-photo me-2"></i>Foto Bukti Transaksi
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center p-4" style="background-color: #f8f9fa;">
                                <div class="mb-3">
                                    <span class="badge bg-primary mb-2">{{ $transaksi->nomor_transaksi }}</span>
                                    <h5 class="mb-1">{{ $transaksi->keterangan }}</h5>
                                    <small class="text-muted">{{ $transaksi->tanggal_realisasi->format('d F Y') }} • Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</small>
                                </div>
                                <div class="position-relative d-inline-block">
                                    <img src="{{ asset('storage/' . $transaksi->foto_bukti) }}" 
                                         alt="Foto Bukti" 
                                         class="img-fluid rounded shadow"
                                         style="max-height: 70vh; max-width: 100%; object-fit: contain; cursor: zoom-in;"
                                         onclick="window.open(this.src, '_blank')">
                                    <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background: rgba(0,0,0,0.5); border-radius: 0 0 0.375rem 0.375rem;">
                                        <small class="text-white"><i class="ti ti-click me-1"></i>Klik untuk memperbesar</small>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="ti ti-x me-1"></i> Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
@endif

{{-- Modal Detail Transaksi --}}
<div class="modal fade" id="modalDetailTransaksi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-file-text me-2"></i>Detail Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailTransaksiContent">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Transaksi --}}
<div class="modal fade" id="modalEditTransaksi" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-edit me-2"></i>Edit Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditTransaksi" method="POST">
                @csrf
                <div class="modal-body" id="editTransaksiContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat form...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Floating Action Button (FAB) dengan Menu --}}
<div class="fab-container">
    <!-- Menu FAB -->
    <div class="fab-menu" id="fabMenu">
        <button class="fab-option" onclick="openModalTambahCepat()" title="Tambah Manual">
            <i class="ti ti-edit"></i>
            <span>Tambah Manual</span>
        </button>
        <button class="fab-option" onclick="openModalImportCepat()" title="Import Excel">
            <i class="ti ti-file-upload"></i>
            <span>Import Excel</span>
        </button>
    </div>
    
    <!-- Main FAB Button -->
    <button class="fab-main" id="fabMain" onclick="toggleFabMenu()">
        <i class="ti ti-plus fab-icon"></i>
    </button>
</div>

<style>
/* Floating Action Button Styles */
.fab-container {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 1000;
}

.fab-main {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    border: none;
    box-shadow: 0 4px 20px rgba(40, 167, 69, 0.4);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    position: relative;
}

.fab-main:hover {
    transform: scale(1.1);
    box-shadow: 0 6px 25px rgba(40, 167, 69, 0.6);
}

.fab-main.active {
    transform: rotate(45deg);
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}

.fab-icon {
    font-size: 28px;
    color: white;
    transition: transform 0.3s ease;
}

.fab-menu {
    position: absolute;
    bottom: 80px;
    right: 0;
    display: flex;
    flex-direction: column;
    gap: 15px;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.3s ease;
}

.fab-menu.active {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.fab-option {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    background: white;
    border: none;
    border-radius: 50px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
    white-space: nowrap;
    font-weight: 500;
    color: #495057;
}

.fab-option:hover {
    transform: translateX(-5px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    background: #f8f9fa;
}

.fab-option i {
    font-size: 20px;
    width: 24px;
    text-align: center;
}

.fab-option:nth-child(1) i {
    color: #28a745;
}

.fab-option:nth-child(2) i {
    color: #007bff;
}

/* Responsive */
@media (max-width: 768px) {
    .fab-container {
        bottom: 20px;
        right: 20px;
    }
    
    .fab-main {
        width: 50px;
        height: 50px;
    }
    
    .fab-icon {
        font-size: 24px;
    }
    
    .fab-option span {
        display: none;
    }
    
    .fab-option {
        padding: 12px;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        justify-content: center;
    }
}
</style>

{{-- Modal Tambah Transaksi Cepat (Tanpa Tanggal Fixed) --}}
<div class="modal fade" id="modalTambahCepat" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-plus me-2"></i>Tambah Transaksi Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('dana-operasional.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="tanggal_realisasi" required value="{{ request('filter_type') == 'bulan' ? request('bulan').'-01' : date('Y-m-d') }}">
                        <small class="text-muted">Filter aktif: {{ $periodeLabel ?? 'Semua Data' }}</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Keterangan <span class="text-danger">*</span></label>
                        <textarea class="form-control" name="keterangan" rows="2" required placeholder="Contoh: Khidmat, BBM, Listrik, dll"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Tipe Transaksi <span class="text-danger">*</span></label>
                        <select class="form-select" name="tipe_transaksi" required>
                            <option value="">-- Pilih --</option>
                            <option value="masuk">Dana Masuk</option>
                            <option value="keluar">Dana Keluar</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nominal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" name="nominal" required min="0" step="0.01" placeholder="0">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Foto Bukti (Opsional)</label>
                        <input type="file" class="form-control" name="foto_bukti" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">
                        <i class="ti ti-check me-1"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Import Excel Cepat dengan Preview (Bank-Grade System) --}}
<div class="modal fade" id="modalImportCepat" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-file-upload me-2"></i>Import Data dari Excel
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" id="closeModalImport"></button>
            </div>
            
            {{-- STEP 1: Upload File --}}
            <div id="step1-upload">
                <div class="modal-body">
                    <!-- Info Filter Aktif -->
                    <div class="alert alert-info">
                        <i class="ti ti-info-circle me-2"></i>
                        <strong>Filter Aktif:</strong> {{ $periodeLabel ?? 'Semua Data' }}
                        <br>
                        <small>Data yang diimpor akan otomatis ditampilkan sesuai periode filter</small>
                    </div>
                    
                    <div class="alert alert-success">
                        <i class="ti ti-lightbulb me-2"></i>
                        <strong>Sistem Import Bank-Grade:</strong>
                        <ul class="mb-0 mt-2">
                            <li><strong>Upload</strong> → Validasi → <strong>Preview</strong> → Confirm → Process</li>
                            <li>Anda bisa <strong>melihat preview data</strong> sebelum import</li>
                            <li>Format: <strong>Tanggal, Keterangan, Dana Masuk, Dana Keluar</strong></li>
                            <li>Import bisa: <strong>Harian, Mingguan, Bulanan, Tahunan</strong></li>
                            <li>Sistem akan auto-detect periode dan redirect ke filter yang sesuai</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Pilih File Excel <span class="text-danger">*</span></label>
                        <input type="file" class="form-control" id="fileExcelImport" accept=".xlsx,.xls" required>
                        <small class="text-muted">Format: .xlsx, .xls (Max 5MB)</small>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('dana-operasional.download-template') }}" class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="ti ti-download me-1"></i> Download Template Excel
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="validateAndPreviewImport()">
                        <i class="ti ti-eye me-1"></i> Validasi & Preview Data
                    </button>
                </div>
            </div>
            
            {{-- STEP 2: Preview Data --}}
            <div id="step2-preview" style="display:none;">
                <div class="modal-body">
                    <div class="alert alert-warning">
                        <i class="ti ti-alert-circle me-2"></i>
                        <strong>PREVIEW SEBELUM IMPORT</strong>
                        <br>
                        <small>Periksa data di bawah ini sebelum melanjutkan. Pastikan semua data sudah benar!</small>
                    </div>
                    
                    <!-- Summary Card -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-1">Total Transaksi</h6>
                                    <h4 class="mb-0 text-primary"><span id="previewTotalRows">0</span> data</h4>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-1">Dana Masuk</h6>
                                    <h4 class="mb-0 text-success">Rp <span id="previewTotalMasuk">0</span></h4>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-1">Dana Keluar</h6>
                                    <h4 class="mb-0 text-danger">Rp <span id="previewTotalKeluar">0</span></h4>
                                </div>
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-1">Periode</h6>
                                    <h4 class="mb-0 text-info"><span id="previewPeriode">-</span></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Preview Table -->
                    <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                        <table class="table table-sm table-bordered table-hover">
                            <thead class="table-primary sticky-top">
                                <tr>
                                    <th width="5%" class="text-center">#</th>
                                    <th width="12%">Tanggal</th>
                                    <th width="35%">Keterangan</th>
                                    <th width="12%">Tipe</th>
                                    <th width="18%" class="text-end">Nominal</th>
                                    <th width="10%" class="text-center">Status</th>
                                    <th width="8%">Kategori</th>
                                </tr>
                            </thead>
                            <tbody id="previewTableBody">
                                <!-- Will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Error Messages -->
                    <div id="previewErrors" style="display:none;">
                        <div class="alert alert-danger mt-3">
                            <i class="ti ti-alert-triangle me-2"></i>
                            <strong>Error Ditemukan:</strong>
                            <ul id="previewErrorList" class="mb-0 mt-2"></ul>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="backToUpload()">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </button>
                    <button type="button" class="btn btn-success" id="btnConfirmImport" onclick="confirmImport()">
                        <i class="ti ti-check me-1"></i> Confirm & Import Data
                    </button>
                </div>
            </div>
            
            {{-- STEP 3: Processing --}}
            <div id="step3-process" style="display:none;">
                <div class="modal-body text-center py-5">
                    <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;"></div>
                    <h4>Memproses Import...</h4>
                    <p class="text-muted">Mohon tunggu, jangan tutup halaman ini</p>
                    <div class="progress mt-3" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" 
                             id="progressBarImport" 
                             role="progressbar" 
                             style="width: 0%">0%</div>
                    </div>
                </div>
            </div>
            
            {{-- STEP 4: Success --}}
            <div id="step4-success" style="display:none;">
                <div class="modal-body text-center py-5">
                    <i class="ti ti-circle-check text-success" style="font-size: 80px;"></i>
                    <h3 class="mt-3">Import Berhasil!</h3>
                    <p class="text-muted">
                        <strong><span id="successCount">0</span> transaksi</strong> berhasil diimport untuk periode 
                        <strong><span id="successPeriode">-</span></strong>
                    </p>
                    <div class="alert alert-info mt-3">
                        <i class="ti ti-info-circle me-2"></i>
                        Halaman akan otomatis refresh dan menampilkan data yang baru diimport
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" onclick="closeAndRedirect()">
                        <i class="ti ti-eye me-1"></i> Lihat Data Sekarang
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Saldo Awal --}}
<div class="modal fade" id="modalEditSaldoAwal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title text-white">
                    <i class="ti ti-edit me-2"></i>Edit Saldo Awal
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="formEditSaldoAwal" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="text" class="form-control" id="editSaldoTanggal" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Saldo Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="editSaldoNominal" name="saldo_awal" required step="0.01" placeholder="0">
                        <small class="text-muted">Masukkan nilai positif untuk saldo surplus, negatif untuk defisit</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="ti ti-check me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function setTanggalTransaksi(tanggal) {
    document.getElementById('tanggal_transaksi_hidden').value = tanggal;
    document.getElementById('tanggal_transaksi_display').value = tanggal;
}

// Fungsi untuk menampilkan detail transaksi
function showDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalDetailTransaksi'));
    const content = document.getElementById('detailTransaksiContent');
    
    // Reset content
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat data...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch detail via AJAX
    fetch(`/dana-operasional/${id}/detail`)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i> Gagal memuat data: ${error.message}
                </div>
            `;
        });
}

// Fungsi untuk menampilkan form edit
function showEdit(id) {
    const modal = new bootstrap.Modal(document.getElementById('modalEditTransaksi'));
    const content = document.getElementById('editTransaksiContent');
    const form = document.getElementById('formEditTransaksi');
    
    // Reset content
    content.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-warning" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat form...</p>
        </div>
    `;
    
    modal.show();
    
    // Fetch form via AJAX
    fetch(`/dana-operasional/${id}/edit`)
        .then(response => response.text())
        .then(html => {
            content.innerHTML = html;
            // Set form action
            form.action = `/dana-operasional/${id}/update`;
        })
        .catch(error => {
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="ti ti-alert-circle me-2"></i> Gagal memuat form: ${error.message}
                </div>
            `;
        });
}

// Handle form edit submission
document.getElementById('formEditTransaksi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalBtnText = submitBtn.innerHTML;
    
    // Disable button dan tampilkan loading
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tutup modal
            bootstrap.Modal.getInstance(document.getElementById('modalEditTransaksi')).hide();
            
            // Tampilkan pesan sukses
            alert('✓ ' + data.message);
            
            // Reload halaman
            location.reload();
        } else {
            alert('✗ ' + (data.message || 'Terjadi kesalahan'));
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        }
    })
    .catch(error => {
        alert('✗ Gagal menyimpan: ' + error.message);
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalBtnText;
    });
});

// Fungsi untuk upload foto bukti
function uploadFotoBukti(transaksiId, file) {
    if (!file) {
        alert('Pilih file foto terlebih dahulu');
        return;
    }
    
    // Validasi tipe file
    if (!file.type.match('image.*')) {
        alert('File harus berupa gambar (JPG, PNG, GIF)');
        return;
    }
    
    // Validasi ukuran (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5MB');
        return;
    }
    
    // Tampilkan loading - cari tombol upload berdasarkan input file yang dipilih
    const inputFile = document.getElementById(`uploadFoto${transaksiId}`);
    const uploadBtn = inputFile.previousElementSibling;
    const originalBtnHtml = uploadBtn.innerHTML;
    uploadBtn.disabled = true;
    uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" style="width: 12px; height: 12px;"></span>';
    
    // Prepare form data
    const formData = new FormData();
    formData.append('foto', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    
    // Upload via AJAX
    fetch(`/dana-operasional/${transaksiId}/upload-foto`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Tampilkan notifikasi sukses
            const alert = document.createElement('div');
            alert.className = 'alert alert-success alert-dismissible fade show position-fixed top-0 start-50 translate-middle-x mt-3';
            alert.style.zIndex = '9999';
            alert.innerHTML = `
                <i class="ti ti-check-circle me-2"></i>
                <strong>Berhasil!</strong> Foto berhasil diupload
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(alert);
            
            // Auto dismiss setelah 2 detik
            setTimeout(() => {
                alert.remove();
                location.reload(); // Reload untuk update tampilan
            }, 2000);
        } else {
            alert('✗ ' + (data.message || 'Gagal upload foto'));
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = originalBtnHtml;
        }
    })
    .catch(error => {
        alert('✗ Gagal upload: ' + error.message);
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalBtnHtml;
    });
}

// Toggle filter inputs berdasarkan tipe yang dipilih
function toggleFilterInputs() {
    const filterType = document.getElementById('filterType').value;
    
    // Sembunyikan semua input dulu
    document.getElementById('inputBulan').style.display = 'none';
    document.getElementById('inputTahun').style.display = 'none';
    document.getElementById('inputMinggu').style.display = 'none';
    document.getElementById('inputRangeStart').style.display = 'none';
    document.getElementById('inputRangeEnd').style.display = 'none';
    
    // Tampilkan input sesuai tipe filter
    if (filterType === 'bulan') {
        document.getElementById('inputBulan').style.display = 'block';
    } else if (filterType === 'tahun') {
        document.getElementById('inputTahun').style.display = 'block';
    } else if (filterType === 'minggu') {
        document.getElementById('inputMinggu').style.display = 'block';
    } else if (filterType === 'range') {
        document.getElementById('inputRangeStart').style.display = 'block';
        document.getElementById('inputRangeEnd').style.display = 'block';
    }
}

// Download PDF dengan filter yang sedang aktif
function downloadPdfFiltered() {
    const form = document.getElementById('formFilter');
    const formData = new FormData(form);
    
    // Build query string dari form data
    const params = new URLSearchParams();
    for (let [key, value] of formData.entries()) {
        if (value) params.append(key, value);
    }
    
    // Redirect ke route download PDF dengan parameter filter
    window.location.href = '{{ route("dana-operasional.export-pdf") }}?' + params.toString();
}

// Panggil fungsi saat halaman load untuk menampilkan input yang sesuai
document.addEventListener('DOMContentLoaded', function() {
    toggleFilterInputs();
    // Pastikan input range tampil jika value 'range' terpilih di modal PDF
    const pdfFilterType = document.getElementById('pdfFilterType');
    if (pdfFilterType && pdfFilterType.value === 'range') {
        togglePdfInputs();
    } else {
        togglePdfInputs();
    }
});

// ===== FUNGSI UNTUK MODAL DOWNLOAD PDF =====

// Toggle inputs di modal PDF
function togglePdfInputs() {
    const filterType = document.getElementById('pdfFilterType').value;
    
    // Sembunyikan semua input
    document.getElementById('pdfInputBulan').style.display = 'none';
    document.getElementById('pdfInputTahun').style.display = 'none';
    document.getElementById('pdfInputMinggu').style.display = 'none';
    document.getElementById('pdfInputRangeStart').style.display = 'none';
    document.getElementById('pdfInputRangeEnd').style.display = 'none';
    
    // Tampilkan sesuai tipe
    if (filterType === 'bulan') {
        document.getElementById('pdfInputBulan').style.display = 'block';
    } else if (filterType === 'tahun') {
        document.getElementById('pdfInputTahun').style.display = 'block';
    } else if (filterType === 'minggu') {
        document.getElementById('pdfInputMinggu').style.display = 'block';
    } else if (filterType === 'range') {
        document.getElementById('pdfInputRangeStart').style.display = 'block';
        document.getElementById('pdfInputRangeEnd').style.display = 'block';
    }
}

// Aksi Cepat: Minggu Ini
function setPdfMingguIni() {
    // Set filter type ke minggu
    document.getElementById('pdfFilterType').value = 'minggu';
    togglePdfInputs();
    
    // Set minggu ini (format ISO: YYYY-Www)
    const today = new Date();
    const year = today.getFullYear();
    const weekNumber = getWeekNumber(today);
    const weekString = year + '-W' + (weekNumber < 10 ? '0' + weekNumber : weekNumber);
    document.getElementById('pdfMinggu').value = weekString;
    
    // Highlight button
    highlightButton(event.target);
}

// Aksi Cepat: Bulan Ini
function setPdfBulanIni() {
    // Set filter type ke bulan
    document.getElementById('pdfFilterType').value = 'bulan';
    togglePdfInputs();
    
    // Set bulan ini (format: YYYY-MM)
    const today = new Date();
    const year = today.getFullYear();
    const month = (today.getMonth() + 1).toString().padStart(2, '0');
    document.getElementById('pdfBulan').value = year + '-' + month;
    
    // Highlight button
    highlightButton(event.target);
}

// Aksi Cepat: Tahun Ini
function setPdfTahunIni() {
    // Set filter type ke tahun
    document.getElementById('pdfFilterType').value = 'tahun';
    togglePdfInputs();
    
    // Set tahun ini
    const today = new Date();
    document.getElementById('pdfTahun').value = today.getFullYear();
    
    // Highlight button
    highlightButton(event.target);
}

// Helper: Get ISO week number
function getWeekNumber(date) {
    const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
    const dayNum = d.getUTCDay() || 7;
    d.setUTCDate(d.getUTCDate() + 4 - dayNum);
    const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
    return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
}

// Helper: Highlight button yang dipilih
function highlightButton(button) {
    // Remove active class dari semua button aksi cepat
    const buttons = document.querySelectorAll('#modalDownloadPdf .btn-outline-info, #modalDownloadPdf .btn-outline-success, #modalDownloadPdf .btn-outline-warning');
    buttons.forEach(btn => {
        btn.classList.remove('active');
        btn.style.boxShadow = '';
    });
    
    // Add active class ke button yang dipilih
    button.classList.add('active');
    button.style.boxShadow = '0 0 0 0.2rem rgba(13, 110, 253, 0.25)';
}

// ===== FUNGSI EDIT & HAPUS SALDO AWAL =====

// Edit Saldo Awal
function editSaldoAwal(saldoId, tanggal, saldoAwal) {
    // Set data ke modal
    document.getElementById('editSaldoTanggal').value = tanggal;
    document.getElementById('editSaldoNominal').value = saldoAwal;
    
    // Set form action
    const form = document.getElementById('formEditSaldoAwal');
    form.action = `/dana-operasional/saldo-awal/${saldoId}`;
    
    // Tampilkan modal
    const modal = new bootstrap.Modal(document.getElementById('modalEditSaldoAwal'));
    modal.show();
}

// ===== FUNGSI FAB (Floating Action Button) =====

// Toggle FAB Menu
function toggleFabMenu() {
    const fabMain = document.getElementById('fabMain');
    const fabMenu = document.getElementById('fabMenu');
    
    fabMain.classList.toggle('active');
    fabMenu.classList.toggle('active');
}

// Close FAB Menu when clicking outside
document.addEventListener('click', function(event) {
    const fabContainer = document.querySelector('.fab-container');
    const fabMain = document.getElementById('fabMain');
    const fabMenu = document.getElementById('fabMenu');
    
    if (fabContainer && !fabContainer.contains(event.target)) {
        fabMain.classList.remove('active');
        fabMenu.classList.remove('active');
    }
});

// Open Modal Tambah Cepat
function openModalTambahCepat() {
    // Close FAB menu
    document.getElementById('fabMain').classList.remove('active');
    document.getElementById('fabMenu').classList.remove('active');
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('modalTambahCepat'));
    modal.show();
}

// Open Modal Import Cepat
function openModalImportCepat() {
    // Close FAB menu
    document.getElementById('fabMain').classList.remove('active');
    document.getElementById('fabMenu').classList.remove('active');
    
    // Reset modal ke step 1
    resetImportModal();
    
    // Open modal
    const modal = new bootstrap.Modal(document.getElementById('modalImportCepat'));
    modal.show();
}

// ===== FUNGSI IMPORT DENGAN PREVIEW (BANK-GRADE SYSTEM) =====

let importedData = []; // Store parsed data

// Reset modal to step 1
function resetImportModal() {
    document.getElementById('step1-upload').style.display = 'block';
    document.getElementById('step2-preview').style.display = 'none';
    document.getElementById('step3-process').style.display = 'none';
    document.getElementById('step4-success').style.display = 'none';
    document.getElementById('fileExcelImport').value = '';
    importedData = [];
}

// Back to upload step
function backToUpload() {
    document.getElementById('step1-upload').style.display = 'block';
    document.getElementById('step2-preview').style.display = 'none';
    importedData = [];
}

// Validate and Preview Import
async function validateAndPreviewImport() {
    const fileInput = document.getElementById('fileExcelImport');
    const file = fileInput.files[0];
    
    if (!file) {
        Swal.fire({
            icon: 'warning',
            title: 'File Belum Dipilih',
            text: 'Silakan pilih file Excel terlebih dahulu!'
        });
        return;
    }
    
    // Validasi ukuran file (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: 'Ukuran file maksimal 5MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + ' MB'
        });
        return;
    }
    
    // Validasi ekstensi file
    const fileName = file.name.toLowerCase();
    if (!fileName.endsWith('.xlsx') && !fileName.endsWith('.xls')) {
        Swal.fire({
            icon: 'error',
            title: 'Format File Salah',
            text: 'Hanya file Excel (.xlsx atau .xls) yang diperbolehkan'
        });
        return;
    }
    
    // Check if XLSX library is loaded
    if (typeof XLSX === 'undefined') {
        Swal.fire({
            icon: 'error',
            title: 'Library Excel Tidak Tersedia',
            html: 'Library SheetJS tidak dapat dimuat.<br>Pastikan koneksi internet Anda aktif dan refresh halaman.',
            confirmButtonText: 'Refresh Halaman',
            showCancelButton: true,
            cancelButtonText: 'Tutup'
        }).then((result) => {
            if (result.isConfirmed) {
                location.reload();
            }
        });
        return;
    }
    
    // Show loading with Swal
    Swal.fire({
        title: 'Memproses File Excel',
        html: 'Mohon tunggu, sedang membaca dan memvalidasi data...',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        // Read Excel file using SheetJS (xlsx.full.min.js)
        const data = await readExcelFile(file);
        
        // Check if data is empty
        if (!data || data.length < 2) {
            Swal.fire({
                icon: 'error',
                title: 'File Kosong',
                text: 'File Excel tidak memiliki data atau hanya ada header'
            });
            return;
        }
        
        // Validate and parse data
        const result = validateExcelData(data);
        
        if (result.errors.length > 0) {
            // Show errors
            Swal.close();
            displayPreviewErrors(result.errors);
            
            Swal.fire({
                icon: 'error',
                title: 'Terdapat ' + result.errors.length + ' Error',
                html: 'Periksa detail error di bawah tombol dan perbaiki file Excel Anda',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        if (result.validData.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Tidak Ada Data Valid',
                text: 'Tidak ditemukan data yang valid untuk diimport'
            });
            return;
        }
        
        // Store validated data
        importedData = result.validData;
        
        // Display preview
        displayPreview(result.validData, result.summary);
        
        // Close loading and show success
        Swal.fire({
            icon: 'success',
            title: 'Validasi Berhasil!',
            html: `Ditemukan <strong>${result.validData.length} transaksi</strong> yang siap diimport.<br>Silakan periksa preview data.`,
            timer: 2000,
            showConfirmButton: false
        });
        
        // Go to step 2
        document.getElementById('step1-upload').style.display = 'none';
        document.getElementById('step2-preview').style.display = 'block';
        
    } catch (error) {
        console.error('Error reading file:', error);
        Swal.fire({
            icon: 'error',
            title: 'Gagal Membaca File',
            html: '<strong>Error:</strong> ' + error.message + '<br><br>Kemungkinan penyebab:<br>• File Excel rusak atau corrupt<br>• Format file tidak sesuai<br>• Browser tidak support',
            confirmButtonText: 'OK'
        });
    }
}

// Read Excel file using SheetJS
function readExcelFile(file) {
    return new Promise((resolve, reject) => {
        console.log('📖 Membaca file:', file.name, 'Size:', file.size, 'bytes');
        
        const reader = new FileReader();
        
        reader.onload = function(e) {
            try {
                console.log('✅ File berhasil dibaca, ukuran buffer:', e.target.result.byteLength);
                
                // Check if XLSX library is loaded
                if (typeof XLSX === 'undefined') {
                    console.error('❌ Library XLSX tidak tersedia!');
                    reject(new Error('Library XLSX tidak dapat dimuat. Pastikan koneksi internet aktif.'));
                    return;
                }
                
                console.log('✅ Library XLSX tersedia, version:', XLSX.version);
                
                const data = new Uint8Array(e.target.result);
                console.log('📊 Parsing Excel dengan XLSX.read...');
                
                const workbook = XLSX.read(data, { type: 'array', cellDates: true });
                console.log('✅ Workbook berhasil dibaca. Sheet names:', workbook.SheetNames);
                
                if (workbook.SheetNames.length === 0) {
                    reject(new Error('File Excel tidak memiliki sheet'));
                    return;
                }
                
                const firstSheet = workbook.Sheets[workbook.SheetNames[0]];
                const jsonData = XLSX.utils.sheet_to_json(firstSheet, { 
                    header: 1,
                    raw: false,
                    dateNF: 'yyyy-mm-dd'
                });
                
                console.log('✅ Data berhasil dikonversi:', jsonData.length, 'baris');
                console.log('Preview 3 baris pertama:', jsonData.slice(0, 3));
                
                resolve(jsonData);
            } catch (error) {
                console.error('❌ Error saat parsing Excel:', error);
                reject(new Error('Gagal membaca Excel: ' + error.message));
            }
        };
        
        reader.onerror = function(error) {
            console.error('❌ FileReader error:', error);
            reject(new Error('Gagal membaca file: ' + error));
        };
        
        console.log('🔄 Memulai FileReader.readAsArrayBuffer...');
        reader.readAsArrayBuffer(file);
    });
}

// Validate Excel data
function validateExcelData(data) {
    console.log('🔍 Memulai validasi data...', data.length, 'baris');
    
    const errors = [];
    const validData = [];
    let totalMasuk = 0;
    let totalKeluar = 0;
    let minDate = null;
    let maxDate = null;
    
    // Log header untuk debugging
    console.log('📋 Header (baris 1):', data[0]);
    
    // Skip header row (index 0)
    for (let i = 1; i < data.length; i++) {
        const row = data[i];
        const lineNumber = i + 1;
        
        // Skip empty rows
        if (!row || row.length === 0 || !row[0]) {
            console.log(`⏭️ Skip baris ${lineNumber}: kosong`);
            continue;
        }
        
        // Log baris yang sedang diproses (untuk debugging 5 baris pertama)
        if (i <= 5) {
            console.log(`🔎 Memproses baris ${lineNumber}:`, row);
        }
        
        // Validate tanggal (column 0)
        const tanggalStr = row[0];
        const tanggal = parseTanggal(tanggalStr);
        
        if (!tanggal) {
            errors.push(`Baris ${lineNumber}: Tanggal "${tanggalStr}" tidak valid. Gunakan format YYYY-MM-DD atau DD/MM/YYYY`);
            continue;
        }
        
        // Check if date is too old (more than 1 year ago) - OPTIONAL WARNING, bukan error
        const oneYearAgo = new Date();
        oneYearAgo.setFullYear(oneYearAgo.getFullYear() - 1);
        if (tanggal < oneYearAgo) {
            console.warn(`⚠️ Baris ${lineNumber}: Tanggal terlalu lama (lebih dari 1 tahun lalu) - ${tanggalStr}`);
            // Tetap lanjutkan, tidak di-skip
        }
        
        // Validate keterangan (column 1)
        const keterangan = row[1] ? String(row[1]).trim() : '';
        if (!keterangan) {
            errors.push(`Baris ${lineNumber}: Keterangan tidak boleh kosong`);
            continue;
        }
        
        // Parse nominal (column 2 = Dana Masuk, column 3 = Dana Keluar)
        const danaMasuk = parseNominal(row[2]);
        const danaKeluar = parseNominal(row[3]);
        
        // Validate nominal
        if (danaMasuk === 0 && danaKeluar === 0) {
            errors.push(`Baris ${lineNumber}: Nominal tidak boleh 0`);
            continue;
        }
        
        if (danaMasuk > 0 && danaKeluar > 0) {
            errors.push(`Baris ${lineNumber}: Tidak boleh ada dana masuk dan keluar bersamaan`);
            continue;
        }
        
        // Determine tipe and nominal
        const tipe = danaMasuk > 0 ? 'masuk' : 'keluar';
        const nominal = danaMasuk > 0 ? danaMasuk : danaKeluar;
        
        // Auto-detect kategori
        const kategori = detectKategori(keterangan);
        
        // Track date range
        if (!minDate || tanggal < minDate) minDate = tanggal;
        if (!maxDate || tanggal > maxDate) maxDate = tanggal;
        
        // Add to totals
        if (tipe === 'masuk') {
            totalMasuk += nominal;
        } else {
            totalKeluar += nominal;
        }
        
        // Add to valid data
        validData.push({
            lineNumber: lineNumber,
            tanggal: formatDate(tanggal),
            tanggalObj: tanggal,
            keterangan: keterangan,
            tipe: tipe,
            nominal: nominal,
            kategori: kategori,
            status: 'valid'
        });
    }
    
    console.log('✅ Validasi selesai:');
    console.log('   - Total baris diproses:', data.length - 1);
    console.log('   - Data valid:', validData.length);
    console.log('   - Error:', errors.length);
    console.log('   - Total Dana Masuk: Rp', totalMasuk.toLocaleString('id-ID'));
    console.log('   - Total Dana Keluar: Rp', totalKeluar.toLocaleString('id-ID'));
    
    return {
        validData: validData,
        errors: errors,
        summary: {
            totalRows: validData.length,
            totalMasuk: totalMasuk,
            totalKeluar: totalKeluar,
            minDate: minDate,
            maxDate: maxDate,
            periode: minDate && maxDate ? formatPeriode(minDate, maxDate) : '-'
        }
    };
}

// Parse tanggal dari berbagai format
function parseTanggal(value) {
    if (!value) return null;
    
    // Convert Excel serial date to Date
    if (typeof value === 'number') {
        // Excel date serial (days since 1900-01-01)
        const date = new Date((value - 25569) * 86400 * 1000);
        return date;
    }
    
    const str = String(value).trim();
    
    // Try ISO format: YYYY-MM-DD
    let match = str.match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
    if (match) {
        return new Date(match[1], match[2] - 1, match[3]);
    }
    
    // Try DD/MM/YYYY format
    match = str.match(/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/);
    if (match) {
        return new Date(match[3], match[2] - 1, match[1]);
    }
    
    // Try DD-MM-YYYY format
    match = str.match(/^(\d{1,2})-(\d{1,2})-(\d{4})$/);
    if (match) {
        return new Date(match[3], match[2] - 1, match[1]);
    }
    
    return null;
}

// Parse nominal (hapus Rp, titik, koma)
function parseNominal(value) {
    if (!value) return 0;
    if (typeof value === 'number') return Math.abs(value);
    
    const str = String(value).replace(/[Rp\s.]/g, '').replace(',', '.');
    const num = parseFloat(str);
    return isNaN(num) ? 0 : Math.abs(num);
}

// Auto-detect kategori berdasarkan keterangan
function detectKategori(keterangan) {
    const lower = keterangan.toLowerCase();
    
    if (lower.includes('atk') || lower.includes('alat tulis')) return 'ATK';
    if (lower.includes('bensin') || lower.includes('bbm')) return 'Transportasi';
    if (lower.includes('listrik') || lower.includes('air') || lower.includes('pdam')) return 'Utilitas';
    if (lower.includes('gaji') || lower.includes('upah')) return 'Gaji';
    if (lower.includes('honor')) return 'Honor';
    if (lower.includes('konsumsi') || lower.includes('makan')) return 'Konsumsi';
    if (lower.includes('maintenance') || lower.includes('perbaikan')) return 'Maintenance';
    if (lower.includes('donasi') || lower.includes('sumbangan')) return 'Donasi';
    
    return 'Lain-lain';
}

// Format date to YYYY-MM-DD
function formatDate(date) {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
}

// Format periode
function formatPeriode(minDate, maxDate) {
    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    
    if (minDate.getTime() === maxDate.getTime()) {
        // Single date
        return `${minDate.getDate()} ${monthNames[minDate.getMonth()]} ${minDate.getFullYear()}`;
    }
    
    if (minDate.getMonth() === maxDate.getMonth() && minDate.getFullYear() === maxDate.getFullYear()) {
        // Same month
        return `${monthNames[minDate.getMonth()]} ${minDate.getFullYear()}`;
    }
    
    if (minDate.getFullYear() === maxDate.getFullYear()) {
        // Same year
        return `${monthNames[minDate.getMonth()]} - ${monthNames[maxDate.getMonth()]} ${minDate.getFullYear()}`;
    }
    
    // Different years
    return `${monthNames[minDate.getMonth()]} ${minDate.getFullYear()} - ${monthNames[maxDate.getMonth()]} ${maxDate.getFullYear()}`;
}

// Display preview
function displayPreview(data, summary) {
    // Update summary
    document.getElementById('previewTotalRows').textContent = summary.totalRows;
    document.getElementById('previewTotalMasuk').textContent = formatRupiah(summary.totalMasuk);
    document.getElementById('previewTotalKeluar').textContent = formatRupiah(summary.totalKeluar);
    document.getElementById('previewPeriode').textContent = summary.periode;
    
    // Build table rows
    const tbody = document.getElementById('previewTableBody');
    tbody.innerHTML = '';
    
    data.forEach((item, index) => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="text-center">${index + 1}</td>
            <td>${formatDateIndo(item.tanggalObj)}</td>
            <td>${escapeHtml(item.keterangan)}</td>
            <td>
                <span class="badge ${item.tipe === 'masuk' ? 'bg-success' : 'bg-danger'}">
                    ${item.tipe === 'masuk' ? 'Dana Masuk' : 'Dana Keluar'}
                </span>
            </td>
            <td class="text-end"><strong>Rp ${formatRupiah(item.nominal)}</strong></td>
            <td class="text-center">
                <i class="ti ti-circle-check text-success" title="Valid"></i>
            </td>
            <td><span class="badge bg-secondary">${item.kategori}</span></td>
        `;
        tbody.appendChild(row);
    });
}

// Display preview errors
function displayPreviewErrors(errors) {
    const errorDiv = document.getElementById('previewErrors');
    const errorList = document.getElementById('previewErrorList');
    
    errorList.innerHTML = '';
    errors.forEach(error => {
        const li = document.createElement('li');
        li.textContent = error;
        errorList.appendChild(li);
    });
    
    errorDiv.style.display = 'block';
    
    // Auto hide after 10 seconds
    setTimeout(() => {
        errorDiv.style.display = 'none';
    }, 10000);
}

// Confirm Import
async function confirmImport() {
    if (importedData.length === 0) {
        alert('Tidak ada data untuk diimport');
        return;
    }
    
    // Show step 3 (processing)
    document.getElementById('step2-preview').style.display = 'none';
    document.getElementById('step3-process').style.display = 'block';
    
    // Disable close button
    document.getElementById('closeModalImport').disabled = true;
    
    try {
        // Send data to server via AJAX
        const response = await fetch('{{ route("dana-operasional.import-excel-preview") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                data: importedData
            })
        });
        
        // Update progress
        updateProgress(50);
        
        const result = await response.json();
        
        if (result.success) {
            // Update progress
            updateProgress(100);
            
            // Show success step
            setTimeout(() => {
                document.getElementById('step3-process').style.display = 'none';
                document.getElementById('step4-success').style.display = 'block';
                
                document.getElementById('successCount').textContent = result.count;
                document.getElementById('successPeriode').textContent = result.periode;
                
                // Store redirect URL
                window.importRedirectUrl = result.redirect_url;
            }, 500);
        } else {
            throw new Error(result.message || 'Import gagal');
        }
    } catch (error) {
        console.error('Error importing:', error);
        alert('Import gagal: ' + error.message);
        
        // Back to preview
        document.getElementById('step3-process').style.display = 'none';
        document.getElementById('step2-preview').style.display = 'block';
        document.getElementById('closeModalImport').disabled = false;
    }
}

// Update progress bar
function updateProgress(percent) {
    const progressBar = document.getElementById('progressBarImport');
    progressBar.style.width = percent + '%';
    progressBar.textContent = percent + '%';
}

// Close modal and redirect
function closeAndRedirect() {
    if (window.importRedirectUrl) {
        window.location.href = window.importRedirectUrl;
    } else {
        window.location.reload();
    }
}

// Helper: Format rupiah
function formatRupiah(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Helper: Format date indo
function formatDateIndo(date) {
    const days = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    
    const dayName = days[date.getDay()];
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();
    
    return `${dayName}, ${day} ${month} ${year}`;
}

// Helper: Escape HTML
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Download PDF dengan filter yang sama seperti tampilan
function downloadPDF() {
    // Get filter values from the form
    const filterType = document.querySelector('select[name="filter_type"]').value;
    const bulan = document.querySelector('input[name="bulan"]')?.value || '';
    const tahun = document.querySelector('input[name="tahun"]')?.value || '';
    const minggu = document.querySelector('input[name="minggu"]')?.value || '';
    const startDate = document.querySelector('input[name="start_date"]')?.value || '';
    const endDate = document.querySelector('input[name="end_date"]')?.value || '';
    
    // Build URL with query parameters
    let url = '{{ route("dana-operasional.export-pdf") }}?filter_type=' + filterType;
    
    if (filterType === 'bulan' && bulan) {
        url += '&bulan=' + bulan;
    } else if (filterType === 'tahun' && tahun) {
        url += '&tahun=' + tahun;
    } else if (filterType === 'minggu' && minggu) {
        url += '&minggu=' + minggu;
    } else if (filterType === 'range' && startDate && endDate) {
        url += '&start_date=' + startDate + '&end_date=' + endDate;
    }
    
    // Open PDF in new tab/download
    window.open(url, '_blank');
}

// ===== UPDATE KATEGORI TRANSAKSI (AJAX) =====

// Update kategori transaksi langsung dari dropdown
async function updateKategori(transaksiId, kategori) {
    // Confirm dulu
    if (!kategori) {
        alert('Silakan pilih kategori!');
        return;
    }
    
    // Show loading indicator
    const selectElement = document.querySelector(`select[data-transaksi-id="${transaksiId}"]`);
    const originalValue = selectElement.value;
    selectElement.disabled = true;
    selectElement.style.opacity = '0.6';
    
    try {
        const response = await fetch(`/dana-operasional/${transaksiId}/update-kategori`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({
                kategori: kategori
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            // Show success notification
            showNotification('success', '✅ Kategori berhasil diupdate!');
            
            // Optional: Reload untuk update tampilan (jika ada filter kategori)
            // setTimeout(() => location.reload(), 1000);
        } else {
            throw new Error(result.message || 'Gagal update kategori');
        }
        
    } catch (error) {
        console.error('Error:', error);
        showNotification('error', '❌ Gagal update kategori: ' + error.message);
        
        // Kembalikan ke nilai semula jika gagal
        selectElement.value = originalValue;
    } finally {
        // Hide loading
        selectElement.disabled = false;
        selectElement.style.opacity = '1';
    }
}

// Helper: Show notification
function showNotification(type, message) {
    // Buat elemen notifikasi
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Tambahkan ke body
    document.body.appendChild(notification);
    
    // Auto remove setelah 3 detik
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// ===== SWEETALERT DELETE CONFIRMATION =====
$('.delete-confirm').click(function(event) {
    var form = $(this).closest("form");
    event.preventDefault();
    Swal.fire({
        title: 'Apakah Anda Yakin?',
        text: "Data akan dihapus permanen dan tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#dc2626",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "Ya, Hapus!",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

// ===== CHECK XLSX LIBRARY ON PAGE LOAD =====
$(document).ready(function() {
    console.log('🔍 Checking XLSX library availability...');
    
    if (typeof XLSX !== 'undefined') {
        console.log('✅ XLSX library loaded successfully! Version:', XLSX.version);
    } else {
        console.error('❌ XLSX library NOT loaded!');
        console.log('🔄 Attempting to load library...');
        
        // Try to load library manually if failed
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
        script.onload = function() {
            console.log('✅ XLSX library loaded manually!');
        };
        script.onerror = function() {
            console.error('❌ Failed to load XLSX library manually!');
        };
        document.head.appendChild(script);
    }
    
    // Event listener untuk modal import
    $('#modalImportCepat').on('show.bs.modal', function() {
        console.log('📂 Modal import dibuka');
        
        // Reset form
        document.getElementById('fileExcelImport').value = '';
        document.getElementById('step1-upload').style.display = 'block';
        document.getElementById('step2-preview').style.display = 'none';
        document.getElementById('step3-process').style.display = 'none';
        document.getElementById('step4-success').style.display = 'none';
        
        // Check library again
        if (typeof XLSX === 'undefined') {
            Swal.fire({
                icon: 'warning',
                title: 'Library Belum Siap',
                html: 'Library Excel sedang dimuat...<br>Mohon tunggu beberapa detik dan coba lagi.',
                timer: 3000
            });
        }
    });
});

// ===== KIRIM EMAIL LAPORAN KEUANGAN =====
// Local Storage untuk histori email
function getEmailHistory() {
    const history = localStorage.getItem('emailHistoryLaporan');
    return history ? JSON.parse(history) : [];
}

function saveEmailToHistory(email) {
    let history = getEmailHistory();
    const emails = email.split(',').map(e => e.trim());
    
    // Tambahkan email baru ke history (tidak duplikat)
    emails.forEach(e => {
        if (!history.includes(e)) {
            history.unshift(e); // Tambah di awal array
        }
    });
    
    // Batasi history maksimal 10 email
    if (history.length > 10) {
        history = history.slice(0, 10);
    }
    
    localStorage.setItem('emailHistoryLaporan', JSON.stringify(history));
}

function buildEmailHistoryOptions() {
    const history = getEmailHistory();
    if (history.length === 0) {
        return '';
    }
    
    let options = '<div class="mt-3" style="border-top: 1px solid #dee2e6; padding-top: 15px;"><label class="form-label fw-bold" style="font-size: 13px;">Email Tersimpan:</label><div class="d-flex flex-wrap gap-1 mb-2">';
    
    history.forEach(email => {
        options += '<button type="button" class="btn btn-sm btn-outline-primary email-history-btn" onclick="selectEmailFromHistory(\'' + email + '\')" style="font-size: 11px; padding: 3px 10px; border-radius: 15px;">' + email + '</button>';
    });
    
    options += '</div><div class="d-flex justify-content-between align-items-center"><small class="text-muted" style="font-size: 11px;">Klik email untuk memilih</small>';
    options += '<button type="button" class="btn btn-sm btn-outline-danger" onclick="clearEmailHistory()" style="font-size: 10px; padding: 2px 8px;">Hapus Semua</button></div></div>';
    
    return options;
}

function selectEmailFromHistory(email) {
    const textarea = document.getElementById('emailPenerima');
    const currentValue = textarea.value.trim();
    
    if (currentValue === '') {
        textarea.value = email;
    } else {
        // Tambahkan ke existing emails
        textarea.value = currentValue + ', ' + email;
    }
    
    // Focus ke textarea
    textarea.focus();
}

function clearEmailHistory() {
    Swal.fire({
        title: 'Hapus Semua Email Tersimpan?',
        text: 'Histori email yang tersimpan akan dihapus permanen',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            localStorage.removeItem('emailHistoryLaporan');
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Histori email berhasil dihapus',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // Reopen modal tanpa history
                kirimEmailLaporan();
            });
        }
    });
}

function kirimEmailLaporan() {
    const historyHtml = buildEmailHistoryOptions();
    
    Swal.fire({
        title: 'Kirim Email Laporan Keuangan',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label fw-bold">Pilih Periode Laporan:</label>
                    <select class="form-select" id="emailFilterType" onchange="toggleEmailPeriodeInputs()">
                        <option value="bulan">Per Bulan</option>
                        <option value="tahun">Per Tahun</option>
                        <option value="minggu">Per Minggu</option>
                        <option value="range">Range Tanggal</option>
                    </select>
                </div>
                
                <!-- Input Bulan -->
                <div class="mb-3" id="emailBulanInput">
                    <label class="form-label">Bulan:</label>
                    <input type="month" class="form-control" id="emailBulan" value="${new Date().getFullYear()}-${String(new Date().getMonth() + 1).padStart(2, '0')}">
                </div>
                
                <!-- Input Tahun -->
                <div class="mb-3" id="emailTahunInput" style="display: none;">
                    <label class="form-label">Tahun:</label>
                    <input type="number" class="form-control" id="emailTahun" value="${new Date().getFullYear()}" min="2020" max="2030">
                </div>
                
                <!-- Input Minggu -->
                <div class="mb-3" id="emailMingguInput" style="display: none;">
                    <label class="form-label">Minggu:</label>
                    <input type="week" class="form-control" id="emailMinggu">
                </div>
                
                <!-- Input Range -->
                <div class="mb-3" id="emailRangeInput" style="display: none;">
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">Tanggal Awal:</label>
                            <input type="date" class="form-control" id="emailTanggalAwal">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Tanggal Akhir:</label>
                            <input type="date" class="form-control" id="emailTanggalAkhir">
                        </div>
                    </div>
                </div>
                
                <div class="mb-3" style="border-top: 1px solid #dee2e6; padding-top: 15px;">
                    <label class="form-label fw-bold">Email Penerima:</label>
                    <textarea id="emailPenerima" class="form-control" rows="3" 
                        placeholder="Masukkan email penerima (pisahkan dengan koma untuk multiple email)&#10;Contoh: email1@example.com, email2@example.com"></textarea>
                    <small class="text-muted">Pisahkan dengan koma (,) untuk multiple email</small>
                </div>
                
                ${historyHtml}
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="bx bx-send me-1"></i> Kirim Email',
        cancelButtonText: 'Batal',
        width: '600px',
        didOpen: () => {
            // Set default values for range
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            
            document.getElementById('emailTanggalAwal').value = firstDay.toISOString().split('T')[0];
            document.getElementById('emailTanggalAkhir').value = lastDay.toISOString().split('T')[0];
        },
        preConfirm: () => {
            const email = document.getElementById('emailPenerima').value.trim();
            if (!email) {
                Swal.showValidationMessage('Email penerima tidak boleh kosong!');
                return false;
            }
            
            // Validasi format email
            const emails = email.split(',').map(e => e.trim());
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            for (let e of emails) {
                if (!emailRegex.test(e)) {
                    Swal.showValidationMessage(`Email "${e}" tidak valid!`);
                    return false;
                }
            }
            
            // Validasi periode
            const filterType = document.getElementById('emailFilterType').value;
            let periodeValid = true;
            
            if (filterType === 'bulan') {
                const bulan = document.getElementById('emailBulan').value;
                if (!bulan) {
                    Swal.showValidationMessage('Bulan harus diisi!');
                    return false;
                }
            } else if (filterType === 'tahun') {
                const tahun = document.getElementById('emailTahun').value;
                if (!tahun) {
                    Swal.showValidationMessage('Tahun harus diisi!');
                    return false;
                }
            } else if (filterType === 'range') {
                const tanggalAwal = document.getElementById('emailTanggalAwal').value;
                const tanggalAkhir = document.getElementById('emailTanggalAkhir').value;
                if (!tanggalAwal || !tanggalAkhir) {
                    Swal.showValidationMessage('Tanggal awal dan akhir harus diisi!');
                    return false;
                }
                if (new Date(tanggalAwal) > new Date(tanggalAkhir)) {
                    Swal.showValidationMessage('Tanggal awal tidak boleh lebih besar dari tanggal akhir!');
                    return false;
                }
            }
            
            return email;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const emailPenerima = result.value;
            
            // Ambil parameter filter dari modal
            const filterType = document.getElementById('emailFilterType').value;
            const bulan = document.getElementById('emailBulan') ? document.getElementById('emailBulan').value : '';
            const tahun = document.getElementById('emailTahun') ? document.getElementById('emailTahun').value : '';
            const minggu = document.getElementById('emailMinggu') ? document.getElementById('emailMinggu').value : '';
            const tanggalAwal = document.getElementById('emailTanggalAwal') ? document.getElementById('emailTanggalAwal').value : '';
            const tanggalAkhir = document.getElementById('emailTanggalAkhir') ? document.getElementById('emailTanggalAkhir').value : '';
            
            // Build periode label untuk preview
            let periodeLabel = '';
            if (filterType === 'bulan' && bulan) {
                const [y, m] = bulan.split('-');
                const monthNames = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                periodeLabel = `${monthNames[parseInt(m) - 1]} ${y}`;
            } else if (filterType === 'tahun' && tahun) {
                periodeLabel = `Tahun ${tahun}`;
            } else if (filterType === 'minggu' && minggu) {
                periodeLabel = `Minggu ${minggu}`;
            } else if (filterType === 'range' && tanggalAwal && tanggalAkhir) {
                periodeLabel = `${tanggalAwal} s/d ${tanggalAkhir}`;
            }
            
            // Show loading with confirmation
            Swal.fire({
                title: 'Mengirim Email...',
                html: `
                    <div class="text-start" style="font-size: 14px;">
                        <p><strong>Periode:</strong> ${periodeLabel}</p>
                        <p><strong>Penerima:</strong> ${emailPenerima}</p>
                        <hr>
                        <p class="text-muted">Mohon tunggu, sedang memproses laporan dan mengirim email...</p>
                    </div>
                `,
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Kirim request
            $.ajax({
                url: '{{ route("dana-operasional.send-email") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    email: emailPenerima,
                    filter_type: filterType,
                    bulan: bulan,
                    tahun: tahun,
                    minggu: minggu,
                    tanggal_awal: tanggalAwal,
                    tanggal_akhir: tanggalAkhir
                },
                success: function(response) {
                    // Simpan email ke history
                    saveEmailToHistory(emailPenerima);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Email Terkirim!',
                        html: response.message,
                        confirmButtonColor: '#28a745'
                    });
                },
                error: function(xhr) {
                    let errorMsg = 'Terjadi kesalahan saat mengirim email.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mengirim Email',
                        html: errorMsg,
                        confirmButtonColor: '#dc2626'
                    });
                }
            });
        }
    });
}

// Toggle input periode untuk email
function toggleEmailPeriodeInputs() {
    const filterType = document.getElementById('emailFilterType').value;
    
    // Hide all
    document.getElementById('emailBulanInput').style.display = 'none';
    document.getElementById('emailTahunInput').style.display = 'none';
    document.getElementById('emailMingguInput').style.display = 'none';
    document.getElementById('emailRangeInput').style.display = 'none';
    
    // Show selected
    if (filterType === 'bulan') {
        document.getElementById('emailBulanInput').style.display = 'block';
    } else if (filterType === 'tahun') {
        document.getElementById('emailTahunInput').style.display = 'block';
    } else if (filterType === 'minggu') {
        document.getElementById('emailMingguInput').style.display = 'block';
    } else if (filterType === 'range') {
        document.getElementById('emailRangeInput').style.display = 'block';
    }
}

</script>

@endsection

