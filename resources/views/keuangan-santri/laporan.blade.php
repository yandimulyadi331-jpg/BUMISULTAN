@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('keuangan-santri.index') }}">Dompet Santri</a> / Laporan</span>
@endsection

<div class="row">
    <div class="col-12">
        
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header bg-gradient-primary">
                <h5 class="text-white mb-0">
                    <i class="ti ti-filter me-2"></i>Filter Laporan
                </h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('keuangan-santri.laporan') }}">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Santri</label>
                                <select name="santri_id" class="form-select">
                                    <option value="">-- Semua --</option>
                                    @foreach($santriList as $santri)
                                        <option value="{{ $santri->id }}" {{ $filters['santri_id'] == $santri->id ? 'selected' : '' }}>
                                            {{ $santri->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Jenis</label>
                                <select name="jenis" class="form-select">
                                    <option value="">-- Semua --</option>
                                    <option value="pemasukan" {{ $filters['jenis'] == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                                    <option value="pengeluaran" {{ $filters['jenis'] == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="category_id" class="form-select">
                                    <option value="">-- Semua --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $filters['category_id'] == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nama_kategori }} ({{ ucfirst($cat->jenis) }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Periode</label>
                                <select name="periode" id="periodeSelect" class="form-select">
                                    <option value="">-- Custom --</option>
                                    <option value="hari" {{ $filters['periode'] == 'hari' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="minggu" {{ $filters['periode'] == 'minggu' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="bulan" {{ $filters['periode'] == 'bulan' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="tahun" {{ $filters['periode'] == 'tahun' ? 'selected' : '' }}>Tahun Ini</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row" id="customDateRange">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Mulai</label>
                                <input type="date" name="start_date" value="{{ $filters['start_date'] }}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Tanggal Akhir</label>
                                <input type="date" name="end_date" value="{{ $filters['end_date'] }}" class="form-control">
                            </div>
                        </div>
                        
                        <div class="col-lg-4 col-sm-12">
                            <div class="form-group mb-3">
                                <label class="form-label">Cari</label>
                                <input type="text" name="search" value="{{ $filters['search'] }}" 
                                       placeholder="Kode/Deskripsi..." class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between flex-wrap gap-2">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter me-2"></i>Filter
                            </button>
                            <a href="{{ route('keuangan-santri.laporan') }}" class="btn btn-secondary">
                                <i class="ti ti-refresh me-2"></i>Reset
                            </a>
                        </div>
                        
                        <div>
                            <button type="submit" formaction="{{ route('keuangan-santri.export.pdf') }}" class="btn btn-danger">
                                <i class="ti ti-file-type-pdf me-2"></i>Export PDF
                            </button>
                            <button type="submit" formaction="{{ route('keuangan-santri.export.excel') }}" class="btn btn-success">
                                <i class="ti ti-file-spreadsheet me-2"></i>Export Excel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row mb-3">
            <div class="col-lg-4 col-sm-12 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-arrow-up ti-lg"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Pemasukan</small>
                                <h4 class="card-title mb-0 text-success">
                                    Rp {{ number_format($summary['total_pemasukan'], 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-12 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger">
                                    <i class="ti ti-arrow-down ti-lg"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Total Pengeluaran</small>
                                <h4 class="card-title mb-0 text-danger">
                                    Rp {{ number_format($summary['total_pengeluaran'], 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-sm-12 mb-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-wallet ti-lg"></i>
                                </span>
                            </div>
                            <div>
                                <small class="text-muted d-block">Selisih</small>
                                <h4 class="card-title mb-0 text-primary">
                                    Rp {{ number_format($summary['selisih'], 0, ',', '.') }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-header bg-gradient-info">
                <h5 class="text-white mb-0">
                    <i class="ti ti-list-details me-2"></i>Detail Transaksi ({{ $transactions->total() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Kode</th>
                                <th>Santri</th>
                                <th>Deskripsi</th>
                                <th>Kategori</th>
                                <th>Jenis</th>
                                <th class="text-end">Nominal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $transaction)
                            <tr>
                                <td>{{ $transaction->tanggal_transaksi->format('d/m/Y') }}</td>
                                <td>
                                    <small class="text-primary">{{ $transaction->kode_transaksi }}</small>
                                </td>
                                <td>
                                    <strong>{{ $transaction->santri->nama_lengkap }}</strong>
                                    <br><small class="text-muted">{{ $transaction->santri->nis }}</small>
                                </td>
                                <td>
                                    {{ $transaction->deskripsi }}
                                    @if($transaction->catatan)
                                        <br><small class="text-muted">{{ Str::limit($transaction->catatan, 40) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->category)
                                        <span class="badge bg-{{ $transaction->category->warna }}" style="color: #fff;">
                                            <i class="{{ $transaction->category->icon }} me-1"></i>
                                            {{ $transaction->category->nama_kategori }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($transaction->jenis === 'pemasukan')
                                        <span class="badge bg-success">
                                            <i class="ti ti-arrow-up me-1"></i>Setor
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="ti ti-arrow-down me-1"></i>Tarik
                                        </span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($transaction->jenis === 'pemasukan')
                                        <strong class="text-success">
                                            + Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                        </strong>
                                    @else
                                        <strong class="text-danger">
                                            - Rp {{ number_format($transaction->jumlah, 0, ',', '.') }}
                                        </strong>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="ti ti-info-circle me-2"></i>Tidak ada data transaksi
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $transactions->appends($filters)->links() }}
                </div>
            </div>
        </div>

    </div>
</div>

@push('myscript')
<script>
// Toggle custom date range
document.getElementById('periodeSelect').addEventListener('change', function() {
    const customDateRange = document.getElementById('customDateRange');
    if (this.value === '') {
        customDateRange.style.display = 'flex';
    } else {
        customDateRange.style.display = 'none';
    }
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('periodeSelect');
    const customDateRange = document.getElementById('customDateRange');
    if (periodeSelect.value !== '') {
        customDateRange.style.display = 'none';
    }
});
</script>
@endpush
@endsection
