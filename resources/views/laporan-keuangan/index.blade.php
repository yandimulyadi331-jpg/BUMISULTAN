@extends('layouts.app')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Laporan Keuangan</div>
                <h2 class="page-title">📊 Annual Report - Laporan Keuangan Profesional</h2>
                <div class="text-muted mt-1">
                    Download laporan keuangan dalam format PDF profesional seperti perusahaan besar
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row row-cards">
            
            {{-- Card Utama: Download Laporan --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title">
                            <i class="ti ti-file-type-pdf me-2"></i>
                            Download Laporan Keuangan Annual Report
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('laporan-keuangan.download-annual-report') }}" method="GET" id="formDownloadLaporan">
                            <div class="row mb-4">
                                {{-- Pilih Jenis Periode --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Jenis Periode</label>
                                    <select name="periode_type" id="periode_type" class="form-select" required>
                                        <option value="">-- Pilih Periode --</option>
                                        <option value="bulanan">📅 Bulanan</option>
                                        <option value="triwulan">📊 Triwulan (Quarter)</option>
                                        <option value="semester">📈 Semester (6 Bulan)</option>
                                        <option value="tahunan">📕 Tahunan (Annual Report)</option>
                                    </select>
                                    <small class="text-muted">Pilih periode laporan yang ingin didownload</small>
                                </div>

                                {{-- Pilih Tahun --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label required">Tahun</label>
                                    <select name="tahun" id="tahun" class="form-select" required>
                                        @php
                                            $currentYear = date('Y');
                                            for ($year = $currentYear; $year >= 2020; $year--) {
                                                echo "<option value='{$year}'>{$year}</option>";
                                            }
                                        @endphp
                                    </select>
                                    <small class="text-muted">Pilih tahun laporan</small>
                                </div>

                                {{-- Pilih Bulan (untuk periode bulanan) --}}
                                <div class="col-md-6 mb-3" id="field_bulan" style="display: none;">
                                    <label class="form-label required">Bulan</label>
                                    <select name="bulan" id="bulan" class="form-select">
                                        <option value="">-- Pilih Bulan --</option>
                                        <option value="1">Januari</option>
                                        <option value="2">Februari</option>
                                        <option value="3">Maret</option>
                                        <option value="4">April</option>
                                        <option value="5">Mei</option>
                                        <option value="6">Juni</option>
                                        <option value="7">Juli</option>
                                        <option value="8">Agustus</option>
                                        <option value="9">September</option>
                                        <option value="10">Oktober</option>
                                        <option value="11">November</option>
                                        <option value="12">Desember</option>
                                    </select>
                                </div>

                                {{-- Pilih Triwulan (untuk periode triwulan) --}}
                                <div class="col-md-6 mb-3" id="field_triwulan" style="display: none;">
                                    <label class="form-label required">Triwulan</label>
                                    <select name="triwulan" id="triwulan" class="form-select">
                                        <option value="">-- Pilih Triwulan --</option>
                                        <option value="1">Q1 (Jan - Mar)</option>
                                        <option value="2">Q2 (Apr - Jun)</option>
                                        <option value="3">Q3 (Jul - Sep)</option>
                                        <option value="4">Q4 (Okt - Des)</option>
                                    </select>
                                </div>

                                {{-- Pilih Semester (untuk periode semester) --}}
                                <div class="col-md-6 mb-3" id="field_semester" style="display: none;">
                                    <label class="form-label required">Semester</label>
                                    <select name="semester" id="semester" class="form-select">
                                        <option value="">-- Pilih Semester --</option>
                                        <option value="1">Semester 1 (Jan - Jun)</option>
                                        <option value="2">Semester 2 (Jul - Des)</option>
                                    </select>
                                </div>
                            </div>

                            {{-- Info Preview --}}
                            <div class="alert alert-info mb-4" id="preview_info" style="display: none;">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="ti ti-info-circle" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Periode yang dipilih:</strong>
                                        <div id="preview_text" class="mt-1"></div>
                                    </div>
                                </div>
                            </div>

                            {{-- Button Submit --}}
                            <div class="text-center">
                                <button type="submit" class="btn btn-danger btn-lg px-5" id="btnDownload">
                                    <i class="ti ti-file-type-pdf me-2"></i>
                                    Download Laporan PDF
                                </button>
                                <button type="button" class="btn btn-success btn-lg px-5 ms-2" id="btnDownloadExcel" style="display: none;">
                                    <i class="ti ti-file-spreadsheet me-2"></i>
                                    Download Excel
                                </button>
                                <a href="{{ route('laporan-keuangan.preview', request()->all()) }}" 
                                   class="btn btn-info btn-lg px-5 ms-2" 
                                   id="btnPreview" 
                                   target="_blank"
                                   style="display: none;">
                                    <i class="ti ti-eye me-2"></i>
                                    Preview Laporan
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Card Info: Fitur Laporan --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="ti ti-info-circle me-2"></i>
                            Fitur Laporan Annual Report
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Cover Page Profesional</strong>
                                        <p class="text-muted mb-0">Halaman cover dengan logo dan informasi perusahaan</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Financial Highlights</strong>
                                        <p class="text-muted mb-0">Ikhtisar keuangan dengan perbandingan periode sebelumnya</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Income Statement</strong>
                                        <p class="text-muted mb-0">Laporan Laba Rugi dengan detail per kategori</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Balance Sheet</strong>
                                        <p class="text-muted mb-0">Neraca dengan saldo awal dan akhir periode</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Cash Flow Statement</strong>
                                        <p class="text-muted mb-0">Laporan Arus Kas Masuk dan Keluar</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Charts & Graphs</strong>
                                        <p class="text-muted mb-0">Grafik perbandingan visual (untuk laporan tahunan)</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Top Transactions</strong>
                                        <p class="text-muted mb-0">Daftar 10 transaksi terbesar dalam periode</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-start">
                                    <div class="me-3 text-primary">
                                        <i class="ti ti-circle-check" style="font-size: 24px;"></i>
                                    </div>
                                    <div>
                                        <strong>Professional Layout</strong>
                                        <p class="text-muted mb-0">Layout profesional dengan header, footer, dan page numbering</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Card: Kelola Publish Laporan untuk Karyawan --}}
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title">
                            <i class="ti ti-share me-2"></i>
                            Kelola Publish Laporan untuk Karyawan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            <strong>Info:</strong> Laporan yang dipublish akan dapat dilihat oleh semua karyawan melalui menu "Laporan Keuangan" di dashboard mereka.
                            <br>
                            <small>
                                <strong>Catatan:</strong> Tabel ini hanya menampilkan laporan <strong>Dana Operasional</strong> yang didownload dari menu Dana Operasional. 
                                Annual Report tidak ditampilkan di sini.
                            </small>
                        </div>

                        @php
                            // HANYA tampilkan Dana Operasional (BUKAN Annual Report)
                            $allLaporans = DB::table('laporan_keuangan')
                                ->leftJoin('users as creator', 'laporan_keuangan.user_id', '=', 'creator.id')
                                ->leftJoin('users as publisher', 'laporan_keuangan.published_by', '=', 'publisher.id')
                                ->select(
                                    'laporan_keuangan.*',
                                    'creator.name as creator_name',
                                    'publisher.name as publisher_name'
                                )
                                ->whereIn('laporan_keuangan.jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
                                ->orderByDesc('laporan_keuangan.created_at')
                                ->get();
                        @endphp

                        @if($allLaporans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 15%;">Jenis</th>
                                        <th style="width: 25%;">Periode</th>
                                        <th style="width: 15%;">Dibuat</th>
                                        <th style="width: 20%;">Status</th>
                                        <th style="width: 15%;">File</th>
                                        <th style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($allLaporans as $lap)
                                    <tr id="row-{{ $lap->id }}">
                                        <td>
                                            @php
                                                // Badge color berdasarkan jenis
                                                $badgeColor = match($lap->jenis_laporan) {
                                                    'LAPORAN_MINGGUAN' => 'bg-info',
                                                    'LAPORAN_BULANAN' => 'bg-primary',
                                                    'LAPORAN_TAHUNAN' => 'bg-success',
                                                    'LAPORAN_CUSTOM' => 'bg-warning',
                                                    default => 'bg-secondary'
                                                };
                                                
                                                // Label jenis
                                                $jenisLabel = match($lap->jenis_laporan) {
                                                    'LAPORAN_MINGGUAN' => 'MINGGUAN',
                                                    'LAPORAN_BULANAN' => 'BULANAN',
                                                    'LAPORAN_TAHUNAN' => 'TAHUNAN',
                                                    'LAPORAN_CUSTOM' => 'CUSTOM',
                                                    default => strtoupper($lap->periode)
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeColor }}">{{ $jenisLabel }}</span>
                                        </td>
                                        <td>
                                            <strong>{{ $lap->nama_laporan }}</strong>
                                            <br>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($lap->tanggal_mulai)->format('d M Y') }} - 
                                                {{ \Carbon\Carbon::parse($lap->tanggal_selesai)->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $lap->creator_name ?? 'Admin' }}<br>
                                                {{ \Carbon\Carbon::parse($lap->created_at)->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <span class="status-badge-{{ $lap->id }}">
                                                @if($lap->is_published)
                                                    <span class="badge bg-success">
                                                        <i class="ti ti-check-circle me-1"></i>
                                                        Published
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        {{ $lap->publisher_name ?? 'Admin' }}<br>
                                                        {{ $lap->published_at ? \Carbon\Carbon::parse($lap->published_at)->format('d M Y H:i') : '-' }}
                                                    </small>
                                                @else
                                                    <span class="badge bg-secondary">
                                                        <i class="ti ti-lock me-1"></i>
                                                        Draft
                                                    </span>
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                @if($lap->file_pdf)
                                                <span class="badge bg-danger" title="PDF tersedia">
                                                    <i class="ti ti-file-type-pdf"></i> PDF
                                                </span>
                                                @endif
                                                @if($lap->file_excel)
                                                <span class="badge bg-success" title="Excel tersedia">
                                                    <i class="ti ti-file-spreadsheet"></i> XLS
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm {{ $lap->is_published ? 'btn-warning' : 'btn-success' }} btn-toggle-publish"
                                                    data-id="{{ $lap->id }}"
                                                    data-status="{{ $lap->is_published ? '1' : '0' }}"
                                                    title="{{ $lap->is_published ? 'Klik untuk unpublish' : 'Klik untuk publish' }}">
                                                <i class="ti {{ $lap->is_published ? 'ti-lock' : 'ti-share' }}"></i>
                                                <span class="btn-text-{{ $lap->id }}">
                                                    {{ $lap->is_published ? 'Unpublish' : 'Publish' }}
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning text-center">
                            <i class="ti ti-alert-circle" style="font-size: 48px;"></i>
                            <h5 class="mt-3 mb-2">Belum ada Laporan Dana Operasional</h5>
                            <p class="mb-3">
                                Untuk membuat laporan yang bisa dipublish ke karyawan, silakan download PDF dari menu 
                                <strong>"Dana Operasional"</strong> (bukan tombol Annual Report di atas).
                            </p>
                            <hr>
                            <div class="text-start">
                                <strong>Langkah-langkah:</strong>
                                <ol class="mt-2">
                                    <li>Buka menu <strong>"Dana Operasional"</strong> di sidebar</li>
                                    <li>Pilih filter periode (Bulan/Tahun/Minggu/Range)</li>
                                    <li>Klik tombol <strong>"Download PDF"</strong> yang ada di atas tabel transaksi</li>
                                    <li>PDF akan terdownload DAN tersimpan ke database</li>
                                    <li>Kembali ke halaman ini untuk publish laporan tersebut</li>
                                </ol>
                            </div>
                            <div class="mt-3">
                                <a href="{{ url('/dana-operasional') }}" class="btn btn-primary">
                                    <i class="ti ti-arrow-right me-1"></i>
                                    Buka Dana Operasional
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@push('myscript')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodeType = document.getElementById('periode_type');
    const tahun = document.getElementById('tahun');
    const bulan = document.getElementById('bulan');
    const triwulan = document.getElementById('triwulan');
    const semester = document.getElementById('semester');
    
    const fieldBulan = document.getElementById('field_bulan');
    const fieldTriwulan = document.getElementById('field_triwulan');
    const fieldSemester = document.getElementById('field_semester');
    const previewInfo = document.getElementById('preview_info');
    const previewText = document.getElementById('preview_text');
    const btnPreview = document.getElementById('btnPreview');

    // Event: Periode Type berubah
    periodeType.addEventListener('change', function() {
        const value = this.value;
        
        // Hide semua field optional
        fieldBulan.style.display = 'none';
        fieldTriwulan.style.display = 'none';
        fieldSemester.style.display = 'none';
        
        // Reset required
        bulan.removeAttribute('required');
        triwulan.removeAttribute('required');
        semester.removeAttribute('required');
        
        // Show field sesuai pilihan
        if (value === 'bulanan') {
            fieldBulan.style.display = 'block';
            bulan.setAttribute('required', 'required');
        } else if (value === 'triwulan') {
            fieldTriwulan.style.display = 'block';
            triwulan.setAttribute('required', 'required');
        } else if (value === 'semester') {
            fieldSemester.style.display = 'block';
            semester.setAttribute('required', 'required');
        }
        
        updatePreview();
    });

    // Event: Update preview saat input berubah
    [tahun, bulan, triwulan, semester].forEach(element => {
        element.addEventListener('change', updatePreview);
    });

    // Function: Update preview periode
    function updatePreview() {
        const periodeValue = periodeType.value;
        const tahunValue = tahun.value;
        
        if (!periodeValue || !tahunValue) {
            previewInfo.style.display = 'none';
            btnPreview.style.display = 'none';
            return;
        }
        
        let previewTextValue = '';
        
        if (periodeValue === 'bulanan') {
            const bulanValue = bulan.value;
            if (bulanValue) {
                const namaBulan = bulan.options[bulan.selectedIndex].text;
                previewTextValue = `${namaBulan} ${tahunValue}`;
            }
        } else if (periodeValue === 'triwulan') {
            const triwulanValue = triwulan.value;
            if (triwulanValue) {
                previewTextValue = `Triwulan ${triwulanValue} Tahun ${tahunValue}`;
            }
        } else if (periodeValue === 'semester') {
            const semesterValue = semester.value;
            if (semesterValue) {
                previewTextValue = `Semester ${semesterValue} Tahun ${tahunValue}`;
            }
        } else if (periodeValue === 'tahunan') {
            previewTextValue = `Tahun ${tahunValue}`;
        }
        
        if (previewTextValue) {
            previewText.textContent = previewTextValue;
            previewInfo.style.display = 'block';
            btnPreview.style.display = 'inline-block';
            
            // Update href preview button
            const params = new URLSearchParams();
            params.append('periode_type', periodeValue);
            params.append('tahun', tahunValue);
            if (periodeValue === 'bulanan' && bulan.value) params.append('bulan', bulan.value);
            if (periodeValue === 'triwulan' && triwulan.value) params.append('triwulan', triwulan.value);
            if (periodeValue === 'semester' && semester.value) params.append('semester', semester.value);
            
            btnPreview.href = "{{ route('laporan-keuangan.preview') }}?" + params.toString();
        } else {
            previewInfo.style.display = 'none';
            btnPreview.style.display = 'none';
        }
    }

    // Form submit handler
    document.getElementById('formDownloadLaporan').addEventListener('submit', function(e) {
        const btn = document.getElementById('btnDownload');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating PDF...';
        
        // Re-enable button after 5 seconds
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="ti ti-file-type-pdf me-2"></i> Download Laporan PDF';
        }, 5000);
    });

    // Button Download Excel handler
    const btnDownloadExcel = document.getElementById('btnDownloadExcel');
    btnDownloadExcel.addEventListener('click', function() {
        const periodeValue = periodeType.value;
        const tahunValue = tahun.value;
        
        if (!periodeValue || !tahunValue) {
            alert('Mohon pilih periode dan tahun terlebih dahulu');
            return;
        }
        
        // Build URL with parameters
        const params = new URLSearchParams();
        params.append('periode_type', periodeValue);
        params.append('tahun', tahunValue);
        if (periodeValue === 'bulanan' && bulan.value) params.append('bulan', bulan.value);
        if (periodeValue === 'triwulan' && triwulan.value) params.append('triwulan', triwulan.value);
        if (periodeValue === 'semester' && semester.value) params.append('semester', semester.value);
        
        // Disable button & show loading
        btnDownloadExcel.disabled = true;
        btnDownloadExcel.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Generating Excel...';
        
        // Download Excel
        window.location.href = "{{ route('laporan-keuangan.download-excel') }}?" + params.toString();
        
        // Re-enable button after 5 seconds
        setTimeout(() => {
            btnDownloadExcel.disabled = false;
            btnDownloadExcel.innerHTML = '<i class="ti ti-file-spreadsheet me-2"></i> Download Excel';
        }, 5000);
    });

    // Update button Excel visibility
    function updateButtonExcelVisibility() {
        const periodeValue = periodeType.value;
        const tahunValue = tahun.value;
        
        if (periodeValue && tahunValue) {
            let showExcel = false;
            
            if (periodeValue === 'bulanan' && bulan.value) showExcel = true;
            if (periodeValue === 'triwulan' && triwulan.value) showExcel = true;
            if (periodeValue === 'semester' && semester.value) showExcel = true;
            if (periodeValue === 'tahunan') showExcel = true;
            
            btnDownloadExcel.style.display = showExcel ? 'inline-block' : 'none';
        } else {
            btnDownloadExcel.style.display = 'none';
        }
    }

    // Trigger update on change
    [periodeType, tahun, bulan, triwulan, semester].forEach(element => {
        element.addEventListener('change', updateButtonExcelVisibility);
    });

    // ========================================
    // TOGGLE PUBLISH LAPORAN
    // ========================================
    document.querySelectorAll('.btn-toggle-publish').forEach(button => {
        button.addEventListener('click', async function() {
            const laporanId = this.dataset.id;
            const currentStatus = this.dataset.status;
            const actionText = currentStatus === '1' ? 'unpublish' : 'publish';
            const confirmText = currentStatus === '1' 
                ? 'Karyawan tidak akan bisa melihat laporan ini lagi.' 
                : 'Karyawan akan bisa melihat dan mendownload laporan ini.';

            const result = await Swal.fire({
                title: `${actionText.charAt(0).toUpperCase() + actionText.slice(1)} Laporan?`,
                text: confirmText,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: currentStatus === '1' ? '#ffc107' : '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: `Ya, ${actionText}!`,
                cancelButtonText: 'Batal'
            });

            if (!result.isConfirmed) return;

            // Loading state
            this.disabled = true;
            const originalHtml = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Loading...';

            try {
                const response = await fetch(`/laporan-keuangan/${laporanId}/toggle-publish`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    // Update button
                    this.dataset.status = data.is_published ? '1' : '0';
                    this.className = `btn btn-sm ${data.is_published ? 'btn-warning' : 'btn-success'} btn-toggle-publish`;
                    this.title = data.is_published ? 'Klik untuk unpublish' : 'Klik untuk publish';
                    
                    const icon = data.is_published ? 'ti-lock' : 'ti-share';
                    const text = data.is_published ? 'Unpublish' : 'Publish';
                    this.innerHTML = `<i class="ti ${icon}"></i> <span class="btn-text-${laporanId}">${text}</span>`;

                    // Update status badge
                    const statusBadge = document.querySelector(`.status-badge-${laporanId}`);
                    if (data.is_published) {
                        statusBadge.innerHTML = `
                            <span class="badge bg-success">
                                <i class="ti ti-check-circle me-1"></i>
                                Published
                            </span>
                            <br>
                            <small class="text-muted">
                                ${data.published_by}<br>
                                ${data.published_at}
                            </small>
                        `;
                    } else {
                        statusBadge.innerHTML = `
                            <span class="badge bg-secondary">
                                <i class="ti ti-lock me-1"></i>
                                Draft
                            </span>
                        `;
                    }

                    // Success notification
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    throw new Error(data.message || 'Gagal toggle publish');
                }
            } catch (error) {
                console.error('Error:', error);
                this.innerHTML = originalHtml;
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: error.message || 'Terjadi kesalahan saat memproses request.'
                });
            } finally {
                this.disabled = false;
            }
        });
    });
});
</script>
@endpush
@endsection
