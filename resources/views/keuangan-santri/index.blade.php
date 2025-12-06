@extends('layouts.app')
@section('titlepage', 'Dompet Santri')

@section('content')
@section('navigasi')
    <span>Saung Santri / Dompet Santri</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="text-white mb-0">
                            <i class="ti ti-wallet me-2"></i>Manajemen Dompet Santri
                        </h5>
                    </div>
                    <div>
                        <a href="{{ route('keuangan-santri.laporan') }}" class="btn btn-light me-2">
                            <i class="ti ti-report me-2"></i> Laporan
                        </a>
                        <a href="{{ route('keuangan-santri.import.form') }}" class="btn btn-light me-2">
                            <i class="ti ti-upload me-2"></i> Import Excel
                        </a>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <!-- Statistik Cards -->
                <div class="row mb-4">
                    <!-- Total Saldo Semua Santri -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Saldo Semua</p>
                                        <h4 class="text-primary mb-0">
                                            Rp {{ number_format($statistik['total_saldo_semua'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-wallet text-primary" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pemasukan -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Setoran</p>
                                        <h4 class="text-success mb-0">
                                            Rp {{ number_format($statistik['total_pemasukan'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-arrow-up text-success" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Pengeluaran -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Penarikan</p>
                                        <h4 class="text-danger mb-0">
                                            Rp {{ number_format($statistik['total_pengeluaran'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-danger bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-arrow-down text-danger" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Jumlah Santri -->
                    <div class="col-lg-3 col-sm-6">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Jumlah Santri</p>
                                        <h4 class="text-info mb-0">
                                            {{ number_format($statistik['jumlah_santri'], 0, ',', '.') }}
                                        </h4>
                                    </div>
                                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                        <i class="ti ti-users text-info" style="font-size: 24px;"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Section -->
                <div class="row mb-3">
                    <div class="col-12">
                        <form method="GET" action="{{ route('keuangan-santri.index') }}">
                            <div class="row g-2">
                                <div class="col-lg-4 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Cari Santri (Nama/NIS)</label>
                                        <input type="text" name="search" value="{{ $search }}" 
                                               class="form-control" placeholder="Ketik nama atau NIS...">
                                    </div>
                                </div>
                                
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="cuti" {{ $status == 'cuti' ? 'selected' : '' }}>Cuti</option>
                                            <option value="alumni" {{ $status == 'alumni' ? 'selected' : '' }}>Alumni</option>
                                            <option value="keluar" {{ $status == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label small">&nbsp;</label>
                                        <div class="d-grid gap-2 d-md-flex">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search me-1"></i> Cari
                                            </button>
                                            <a href="{{ route('keuangan-santri.index') }}" class="btn btn-secondary">
                                                <i class="fa fa-refresh me-1"></i> Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 100px;">NIS</th>
                                <th>Nama Santri</th>
                                <th style="width: 100px;">Status</th>
                                <th style="width: 150px;" class="text-end">Saldo</th>
                                <th style="width: 150px;" class="text-end">Total Setoran</th>
                                <th style="width: 150px;" class="text-end">Total Penarikan</th>
                                <th style="width: 120px;">Transaksi Terakhir</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($santriList as $santri)
                            @php
                                $saldo = $santri->keuanganSaldo;
                            @endphp
                            <tr>
                                <td class="text-center">{{ $loop->iteration + ($santriList->currentPage() - 1) * $santriList->perPage() }}</td>
                                <td>
                                    <strong class="text-primary">{{ $santri->nis }}</strong>
                                </td>
                                <td>
                                    <strong>{{ $santri->nama_lengkap }}</strong>
                                </td>
                                <td>
                                    @if($santri->status_santri == 'aktif')
                                        <span class="badge bg-success">Aktif</span>
                                    @elseif($santri->status_santri == 'cuti')
                                        <span class="badge bg-warning">Cuti</span>
                                    @elseif($santri->status_santri == 'alumni')
                                        <span class="badge bg-info">Alumni</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($santri->status_santri) }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <strong class="{{ $saldo && $saldo->saldo_akhir > 0 ? 'text-success' : 'text-muted' }}">
                                        Rp {{ number_format($saldo->saldo_akhir ?? 0, 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td class="text-end">
                                    <small class="text-success">
                                        Rp {{ number_format($saldo->total_pemasukan ?? 0, 0, ',', '.') }}
                                    </small>
                                </td>
                                <td class="text-end">
                                    <small class="text-danger">
                                        Rp {{ number_format($saldo->total_pengeluaran ?? 0, 0, ',', '.') }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($saldo && $saldo->last_transaction_date)
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($saldo->last_transaction_date)->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <small class="text-muted">-</small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('keuangan-santri.show', $santri->id) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Detail & History">
                                        <i class="ti ti-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="ti ti-info-circle me-2"></i>Tidak ada data santri
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-3">
                    {{ $santriList->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
