@extends('layouts.app')

@section('title', 'Laporan Pinjaman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-file-earmark-text"></i> Laporan Pinjaman</h4>
            <p class="text-muted mb-0">✅ Laporan dan statistik pinjaman (Real-Time Akurat)</p>
        </div>
        <a href="{{ route('pinjaman.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Filter -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('pinjaman.laporan') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-select">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-select">
                        <option value="">Semua Tahun</option>
                        @for($i = date('Y'); $i >= date('Y') - 5; $i--)
                            <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-select">
                        <option value="">Semua Kategori</option>
                        <option value="crew" {{ request('kategori') == 'crew' ? 'selected' : '' }}>Crew</option>
                        <option value="non_crew" {{ request('kategori') == 'non_crew' ? 'selected' : '' }}>Non Crew</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="bi bi-funnel"></i> Filter
                    </button>
                    <a href="{{ route('pinjaman.laporan') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Pinjaman</h6>
                    <h3 class="card-title mb-0">{{ $stats['total_pinjaman'] }}</h3>
                    <small>Transaksi</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white card-dicairkan">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Dicairkan</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_dicairkan'], 0, ',', '.') }}</h3>
                    <small>Dana Keluar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white card-terbayar">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Terbayar</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_terbayar'], 0, ',', '.') }}</h3>
                    <small>Dana Masuk</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white card-sisa">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Sisa Pinjaman</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_sisa'], 0, ',', '.') }}</h3>
                    <small>Belum Terbayar</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update -->
    <div class="alert alert-info alert-dismissible fade show">
        <i class="bi bi-info-circle"></i>
        <strong>Laporan Real-Time Akurat:</strong> Data ini di-update otomatis setiap 30 detik dan menggunakan perhitungan akurat dari cicilan pinjaman. Setiap ada pembayaran baru, laporan langsung terupdate.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Tabel Laporan -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Data Pinjaman</h5>
            <div>
                <form method="GET" action="{{ route('pinjaman.laporan') }}" class="d-inline">
                    <input type="hidden" name="bulan" value="{{ request('bulan') }}">
                    <input type="hidden" name="tahun" value="{{ request('tahun') }}">
                    <input type="hidden" name="kategori" value="{{ request('kategori') }}">
                    <input type="hidden" name="download_pdf" value="1">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-file-pdf"></i> Download PDF
                    </button>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No. Pinjaman</th>
                            <th>Peminjam</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Jumlah Disetujui</th>
                            <th>Terbayar</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pinjaman as $item)
                        <tr>
                            <td>
                                <a href="{{ route('pinjaman.show', $item->id) }}" class="text-decoration-none">
                                    {{ $item->nomor_pinjaman }}
                                </a>
                            </td>
                            <td>{{ $item->nama_peminjam_lengkap }}</td>
                            <td>
                                <span class="badge bg-{{ $item->kategori_peminjam == 'crew' ? 'primary' : 'secondary' }}">
                                    {{ strtoupper($item->kategori_peminjam) }}
                                </span>
                            </td>
                            <td>{{ $item->tanggal_pengajuan->format('d/m/Y') }}</td>
                            <td>Rp {{ number_format($item->jumlah_disetujui ?? $item->jumlah_pengajuan, 0, ',', '.') }}</td>
                            <td class="text-success">Rp {{ number_format($item->total_terbayar, 0, ',', '.') }}</td>
                            <td class="text-danger">Rp {{ number_format($item->sisa_pinjaman, 0, ',', '.') }}</td>
                            <td>
                                @php
                                $statusColors = [
                                    'pengajuan' => 'warning',
                                    'review' => 'info',
                                    'disetujui' => 'primary',
                                    'ditolak' => 'danger',
                                    'dicairkan' => 'success',
                                    'berjalan' => 'primary',
                                    'lunas' => 'success',
                                    'dibatalkan' => 'secondary'
                                ];
                                $statusLabels = [
                                    'pengajuan' => 'PENGAJUAN',
                                    'review' => 'REVIEW',
                                    'disetujui' => 'DISETUJUI',
                                    'ditolak' => 'DITOLAK',
                                    'dicairkan' => 'DICAIRKAN',
                                    'berjalan' => 'BERJALAN',
                                    'lunas' => 'LUNAS',
                                    'dibatalkan' => 'DIBATALKAN'
                                ];
                                @endphp
                                <span class="badge bg-{{ $statusColors[$item->status] ?? 'secondary' }}">
                                    {{ $statusLabels[$item->status] ?? strtoupper($item->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                                Tidak ada data pinjaman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($pinjaman->count() > 0)
                    <tfoot class="table-secondary">
                        <tr>
                            <th colspan="4" class="text-end">TOTAL:</th>
                            <th>Rp {{ number_format($pinjaman->sum('jumlah_disetujui'), 0, ',', '.') }}</th>
                            <th class="text-success">Rp {{ number_format($stats['total_terbayar'], 0, ',', '.') }}</th>
                            <th class="text-danger">Rp {{ number_format($stats['total_sisa'], 0, ',', '.') }}</th>
                            <th>-</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ✅ REAL-TIME REFRESH SCRIPT FOR ACCURATE REPORTING -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('✅ Inisialisasi Real-Time Laporan Pinjaman (Akurat)...');
    
    let autoRefreshInterval = null;

    function refreshLaporanRealTime() {
        const bulan = new URLSearchParams(window.location.search).get('bulan') || '{{ request('bulan') }}';
        const tahun = new URLSearchParams(window.location.search).get('tahun') || '{{ request('tahun') }}';
        const kategori = new URLSearchParams(window.location.search).get('kategori') || '{{ request('kategori') }}';

        fetch(`/api/laporan-pinjaman?bulan=${bulan}&tahun=${tahun}&kategori=${kategori}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const result = data.data;
                
                // Update statistik cards
                updateStatsCards(result.summary);
                
                // Update tabel data
                updateTabelData(result.detail);
                
                // Show last updated time
                const now = new Date();
                const timeStr = now.toLocaleTimeString('id-ID');
                const badge = document.querySelector('.real-time-status-badge') || createStatusBadge();
                badge.textContent = `✅ Update terakhir: ${timeStr} (data akurat dari cicilan)`;
                badge.style.display = 'block';
            }
        })
        .catch(error => {
            console.error('Error refreshing laporan:', error);
        });
    }

    function updateStatsCards(stats) {
        // Update card Total Dicairkan
        const dicairkanCard = document.querySelector('.card-dicairkan .card-title');
        if (dicairkanCard) {
            dicairkanCard.textContent = 'Rp ' + (stats.total_dicairkan || 0).toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        // Update card Total Terbayar
        const terbayarCard = document.querySelector('.card-terbayar .card-title');
        if (terbayarCard) {
            terbayarCard.textContent = 'Rp ' + (stats.total_terbayar || 0).toLocaleString('id-ID', {maximumFractionDigits: 0});
        }

        // Update card Sisa Pinjaman
        const sisaCard = document.querySelector('.card-sisa .card-title');
        if (sisaCard) {
            sisaCard.textContent = 'Rp ' + (stats.total_sisa || 0).toLocaleString('id-ID', {maximumFractionDigits: 0});
        }
    }

    function updateTabelData(detail) {
        const tbody = document.querySelector('table tbody');
        if (!tbody) return;

        const rows = tbody.querySelectorAll('tr');
        
        detail.forEach((item, index) => {
            if (rows[index]) {
                const cells = rows[index].querySelectorAll('td');
                if (cells.length >= 8) {
                    // Update kolom Terbayar (index 5) dan Sisa (index 6)
                    cells[5].textContent = 'Rp ' + (item.total_dibayar || 0).toLocaleString('id-ID', {maximumFractionDigits: 0});
                    cells[6].textContent = 'Rp ' + (item.total_sisa || 0).toLocaleString('id-ID', {maximumFractionDigits: 0});
                }
            }
        });

        // Update footer total
        const tfoot = document.querySelector('table tfoot tr');
        if (tfoot) {
            const totalTerbayar = detail.reduce((sum, item) => sum + (item.total_dibayar || 0), 0);
            const totalSisa = detail.reduce((sum, item) => sum + (item.total_sisa || 0), 0);
            
            const thCells = tfoot.querySelectorAll('th');
            if (thCells.length >= 7) {
                thCells[5].textContent = 'Rp ' + totalTerbayar.toLocaleString('id-ID', {maximumFractionDigits: 0});
                thCells[6].textContent = 'Rp ' + totalSisa.toLocaleString('id-ID', {maximumFractionDigits: 0});
            }
        }
    }

    function createStatusBadge() {
        const badge = document.createElement('div');
        badge.className = 'alert alert-success mt-3 real-time-status-badge';
        badge.style.position = 'fixed';
        badge.style.top = '100px';
        badge.style.right = '20px';
        badge.style.zIndex = '9999';
        badge.style.width = 'auto';
        badge.style.maxWidth = '350px';
        badge.style.padding = '12px 15px';
        badge.style.fontSize = '13px';
        badge.innerHTML = '<i class="bi bi-check-circle"></i> Update terakhir: -';
        document.body.appendChild(badge);
        return badge;
    }

    // Start auto-refresh setiap 30 detik
    autoRefreshInterval = setInterval(refreshLaporanRealTime, 30000);
    
    // Also load status badge immediately
    createStatusBadge();

    // Cleanup on page leave
    window.addEventListener('beforeunload', () => {
        if (autoRefreshInterval) clearInterval(autoRefreshInterval);
    });

    console.log('✅ Real-time laporan aktif! Refresh setiap 30 detik');
});

// Listen untuk broadcast events (jika tersedia)
if (typeof window.Echo !== 'undefined' && window.Echo) {
    try {
        window.Echo.private('laporan.pinjaman')
            .listen('PinjamanPaymentUpdated', (event) => {
                console.log('📢 Pembayaran terdeteksi! Refresh laporan sekarang...');
                location.reload();
            });
    } catch(e) {
        console.log('Broadcasting tidak tersedia, gunakan polling saja');
    }
}
</script>
@endsection
