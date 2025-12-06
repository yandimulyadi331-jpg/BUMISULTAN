@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                        <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                        <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
                        <path d="M12 17v1m0 -8v1"></path>
                    </svg>
                    Laporan Transaksi Keuangan
                </h2>
                <div class="text-muted mt-1">
                    Download laporan transaksi keuangan dalam format PDF profesional bergaya bank internasional
                </div>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <!-- Card Download PDF -->
                <div class="card">
                    <div class="card-header bg-blue-lt">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-download me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
                                <path d="M12 17v-6"></path>
                                <path d="M9.5 14.5l2.5 2.5l2.5 -2.5"></path>
                            </svg>
                            Download Laporan PDF
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Alert Info -->
                        <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <circle cx="12" cy="12" r="9"></circle>
                                <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                <polyline points="11 12 12 12 12 16 13 16"></polyline>
                            </svg>
                            <div>
                                <h4 class="alert-title">Informasi</h4>
                                <div class="text-muted">
                                    Laporan PDF akan dihasilkan dengan desain profesional bergaya bank internasional. 
                                    Dokumen mencakup header BUMI SULTAN dengan alamat lengkap, detail transaksi, dan ringkasan keuangan.
                                </div>
                            </div>
                        </div>

                        <!-- Form Download -->
                        <form action="{{ route('transaksi-keuangan.export-pdf') }}" method="GET" id="formDownloadPdf">
                            <div class="row">
                                <div class="col-md-5 mb-3">
                                    <label class="form-label required">Tanggal Dari</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="tanggal_dari" 
                                           id="tanggal_dari" 
                                           value="{{ request('tanggal_dari', date('Y-m-01')) }}" 
                                           required>
                                    <small class="form-hint">Pilih tanggal awal periode laporan</small>
                                </div>

                                <div class="col-md-5 mb-3">
                                    <label class="form-label required">Tanggal Sampai</label>
                                    <input type="date" 
                                           class="form-control" 
                                           name="tanggal_sampai" 
                                           id="tanggal_sampai" 
                                           value="{{ request('tanggal_sampai', date('Y-m-d')) }}" 
                                           required>
                                    <small class="form-hint">Pilih tanggal akhir periode laporan</small>
                                </div>

                                <div class="col-md-2 mb-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100" id="btnDownload">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-type-pdf" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                            <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                                            <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                                            <path d="M17 18h2"></path>
                                            <path d="M20 15h-3v6"></path>
                                            <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                                        </svg>
                                        Download PDF
                                    </button>
                                </div>
                            </div>

                            <!-- Quick Date Filters -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label class="form-label">Filter Cepat:</label>
                                    <div class="btn-group w-100" role="group">
                                        <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="today">
                                            Hari Ini
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="week">
                                            Minggu Ini
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="month">
                                            Bulan Ini
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="last-month">
                                            Bulan Lalu
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary quick-filter" data-filter="year">
                                            Tahun Ini
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Card Preview Features -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-star me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z"></path>
                            </svg>
                            Fitur Laporan PDF
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Header Profesional BUMI SULTAN</strong>
                                        <p class="text-muted small mb-0">Logo, nama perusahaan, dan alamat lengkap di Jonggol</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Desain Bank Internasional</strong>
                                        <p class="text-muted small mb-0">Layout profesional seperti statement bank dunia</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Detail Transaksi Lengkap</strong>
                                        <p class="text-muted small mb-0">Tanggal, nama tukang, keterangan, dan nominal</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Ringkasan Keuangan</strong>
                                        <p class="text-muted small mb-0">Total pemasukan, pengeluaran, dan saldo akhir</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Watermark & Security</strong>
                                        <p class="text-muted small mb-0">Watermark BUMI SULTAN untuk keaslian dokumen</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-blue badge-pill">✓</span>
                                    </div>
                                    <div>
                                        <strong>Area Tanda Tangan</strong>
                                        <p class="text-muted small mb-0">Kolom tanda tangan Finance Manager</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    // Quick Filter Buttons
    document.querySelectorAll('.quick-filter').forEach(button => {
        button.addEventListener('click', function() {
            const filter = this.dataset.filter;
            const today = new Date();
            let startDate, endDate;

            switch(filter) {
                case 'today':
                    startDate = today;
                    endDate = today;
                    break;
                case 'week':
                    const firstDayOfWeek = new Date(today);
                    firstDayOfWeek.setDate(today.getDate() - today.getDay());
                    startDate = firstDayOfWeek;
                    endDate = today;
                    break;
                case 'month':
                    startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                    endDate = today;
                    break;
                case 'last-month':
                    startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                    endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                    break;
                case 'year':
                    startDate = new Date(today.getFullYear(), 0, 1);
                    endDate = today;
                    break;
            }

            document.getElementById('tanggal_dari').value = formatDate(startDate);
            document.getElementById('tanggal_sampai').value = formatDate(endDate);

            // Highlight active button
            document.querySelectorAll('.quick-filter').forEach(btn => {
                btn.classList.remove('active');
            });
            this.classList.add('active');
        });
    });

    function formatDate(date) {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    }

    // Form validation
    document.getElementById('formDownloadPdf').addEventListener('submit', function(e) {
        const tanggalDari = new Date(document.getElementById('tanggal_dari').value);
        const tanggalSampai = new Date(document.getElementById('tanggal_sampai').value);

        if (tanggalSampai < tanggalDari) {
            e.preventDefault();
            alert('Tanggal sampai tidak boleh lebih kecil dari tanggal dari!');
            return false;
        }

        // Show loading state
        const btnDownload = document.getElementById('btnDownload');
        btnDownload.disabled = true;
        btnDownload.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghasilkan PDF...';

        // Reset button after 3 seconds
        setTimeout(function() {
            btnDownload.disabled = false;
            btnDownload.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-type-pdf" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                    <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"></path>
                    <path d="M5 18h1.5a1.5 1.5 0 0 0 0 -3h-1.5v6"></path>
                    <path d="M17 18h2"></path>
                    <path d="M20 15h-3v6"></path>
                    <path d="M11 15v6h1a2 2 0 0 0 2 -2v-2a2 2 0 0 0 -2 -2h-1z"></path>
                </svg>
                Download PDF
            `;
        }, 3000);
    });
</script>
@endpush
@endsection
