@extends('layouts.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h3 class="text-white mb-2">
                                    <i class="ti ti-building-bank me-2"></i>
                                    MANAJEMEN KEUANGAN
                                </h3>
                                <p class="mb-0">Sistem Akuntansi & Keuangan Terintegrasi</p>
                                <small>Periode: {{ \Carbon\Carbon::parse($periode)->locale('id')->isoFormat('MMMM YYYY') }}</small>
                            </div>
                            <div class="text-end">
                                <a href="{{ route('manajemen-keuangan.laporan.index') }}" class="btn btn-light">
                                    <i class="ti ti-file-report me-1"></i> Laporan Keuangan
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-3 mb-4">
            <!-- Total Aset -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1">Total Aset</span>
                                <h3 class="card-title mb-1">Rp {{ number_format($totalAset, 0, ',', '.') }}</h3>
                                <small class="text-success fw-medium">
                                    <i class="ti ti-trending-up"></i> Assets
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-briefcase ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Kewajiban -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1">Total Kewajiban</span>
                                <h3 class="card-title mb-1">Rp {{ number_format($totalKewajiban, 0, ',', '.') }}</h3>
                                <small class="text-danger fw-medium">
                                    <i class="ti ti-trending-down"></i> Liabilities
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ti ti-file-invoice ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Modal -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1">Total Modal</span>
                                <h3 class="card-title mb-1">Rp {{ number_format($totalModal, 0, ',', '.') }}</h3>
                                <small class="text-info fw-medium">
                                    <i class="ti ti-wallet"></i> Equity
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-building-bank ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Laba Bulan Ini -->
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <span class="d-block mb-1">Laba Bulan Ini</span>
                                <h3 class="card-title mb-1 {{ $labaBulanIni >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format(abs($labaBulanIni), 0, ',', '.') }}
                                </h3>
                                <small class="{{ $labaBulanIni >= 0 ? 'text-success' : 'text-danger' }} fw-medium">
                                    <i class="ti ti-{{ $labaBulanIni >= 0 ? 'arrow-up' : 'arrow-down' }}"></i> 
                                    {{ $labaBulanIni >= 0 ? 'Profit' : 'Loss' }}
                                </small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-{{ $labaBulanIni >= 0 ? 'success' : 'warning' }}">
                                    <i class="ti ti-chart-line ti-md"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <!-- Kas & Bank -->
            <div class="col-xl-8">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ti ti-coin me-2"></i>Kas & Bank
                        </h5>
                        <a href="{{ route('manajemen-keuangan.kas-bank.index') }}" class="btn btn-sm btn-primary">
                            <i class="ti ti-plus me-1"></i> Kelola
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Tipe</th>
                                        <th>No. Rekening</th>
                                        <th class="text-end">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kasBank as $kb)
                                        <tr>
                                            <td>
                                                <strong>{{ $kb->nama }}</strong>
                                                @if($kb->is_default)
                                                    <span class="badge bg-label-primary ms-1">Default</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-label-{{ $kb->tipe == 'KAS' ? 'success' : 'info' }}">
                                                    {{ $kb->tipe }}
                                                </span>
                                            </td>
                                            <td>{{ $kb->nomor_rekening ?? '-' }}</td>
                                            <td class="text-end">
                                                <strong>Rp {{ number_format($kb->saldo_saat_ini, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="ti ti-info-circle ti-lg text-muted"></i>
                                                <p class="mb-0 mt-2">Belum ada data Kas & Bank</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-end">Total Kas & Bank:</th>
                                        <th class="text-end">
                                            <span class="badge bg-primary">
                                                Rp {{ number_format($totalKasBank, 0, ',', '.') }}
                                            </span>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-xl-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-layout-grid me-2"></i>Menu Cepat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.transaksi-kas-bank.create') }}" class="btn btn-outline-primary w-100 text-start">
                                    <i class="ti ti-cash me-2"></i>
                                    <br>Transaksi Kas
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.jurnal-umum.create') }}" class="btn btn-outline-info w-100 text-start">
                                    <i class="ti ti-notebook me-2"></i>
                                    <br>Input Jurnal
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.buku-besar.index') }}" class="btn btn-outline-secondary w-100 text-start">
                                    <i class="ti ti-book me-2"></i>
                                    <br>Buku Besar
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.chart-of-accounts.index') }}" class="btn btn-outline-warning w-100 text-start">
                                    <i class="ti ti-list-details me-2"></i>
                                    <br>Chart of Accounts
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.budget.index') }}" class="btn btn-outline-success w-100 text-start">
                                    <i class="ti ti-target me-2"></i>
                                    <br>Budget
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('manajemen-keuangan.rekonsiliasi-bank.index') }}" class="btn btn-outline-danger w-100 text-start">
                                    <i class="ti ti-receipt-refund me-2"></i>
                                    <br>Rekonsiliasi
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jurnal Draft & Budget -->
        <div class="row g-3 mb-4">
            <!-- Jurnal Draft -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">
                            <i class="ti ti-file-text me-2"></i>Jurnal Draft (Belum Posting)
                        </h5>
                        <a href="{{ route('manajemen-keuangan.jurnal-umum.index') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Semua
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No. Jurnal</th>
                                        <th>Tanggal</th>
                                        <th class="text-end">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($jurnalDraft->take(5) as $jurnal)
                                        <tr>
                                            <td>
                                                <a href="{{ route('manajemen-keuangan.jurnal-umum.show', $jurnal->id) }}">
                                                    {{ $jurnal->nomor_jurnal }}
                                                </a>
                                            </td>
                                            <td>{{ $jurnal->tanggal_transaksi->format('d/m/Y') }}</td>
                                            <td class="text-end">Rp {{ number_format($jurnal->total_debit, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center py-3">
                                                <small class="text-muted">Tidak ada jurnal draft</small>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Monitoring -->
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">
                            <i class="ti ti-target me-2"></i>Monitoring Budget
                        </h5>
                        <a href="{{ route('manajemen-keuangan.budget.monitoring') }}" class="btn btn-sm btn-outline-primary">
                            Lihat Detail
                        </a>
                    </div>
                    <div class="card-body">
                        @forelse($budgetData->take(5) as $budget)
                            <div class="mb-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small>{{ Str::limit($budget->nama_budget, 30) }}</small>
                                    <small class="fw-medium">{{ number_format($budget->persentase_realisasi, 1) }}%</small>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar {{ $budget->persentase_realisasi > 100 ? 'bg-danger' : ($budget->persentase_realisasi > 80 ? 'bg-warning' : 'bg-success') }}" 
                                         style="width: {{ min($budget->persentase_realisasi, 100) }}%">
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-muted py-3 mb-0">
                                <small>Belum ada budget aktif</small>
                            </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Pendapatan & Beban -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ti ti-chart-bar me-2"></i>Trend Pendapatan & Beban (6 Bulan Terakhir)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPendapatanBeban" height="80"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Data
    const chartData = @json($chartData);
    
    const ctx = document.getElementById('chartPendapatanBeban').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartData.map(d => d.periode),
            datasets: [
                {
                    label: 'Pendapatan',
                    data: chartData.map(d => d.pendapatan),
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Beban',
                    data: chartData.map(d => d.beban),
                    backgroundColor: 'rgba(255, 99, 132, 0.5)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Laba/Rugi',
                    data: chartData.map(d => d.laba),
                    type: 'line',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 2,
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            return label;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
