@extends('layouts.app')

@section('title', 'Laporan Pinjaman')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1"><i class="bi bi-file-earmark-text"></i> Laporan Pinjaman</h4>
            <p class="text-muted mb-0">Laporan dan statistik pinjaman</p>
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
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Dicairkan</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_dicairkan'], 0, ',', '.') }}</h3>
                    <small>Dana Keluar</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Total Terbayar</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_terbayar'], 0, ',', '.') }}</h3>
                    <small>Dana Masuk</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-white-50">Sisa Pinjaman</h6>
                    <h3 class="card-title mb-0">Rp {{ number_format($stats['total_sisa'], 0, ',', '.') }}</h3>
                    <small>Belum Terbayar</small>
                </div>
            </div>
        </div>
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
@endsection
