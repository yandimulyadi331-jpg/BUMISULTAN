@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-file-report me-2"></i>Laporan Keuangan Harian
                    </h5>
                    <div>
                        <input type="date" class="form-control" value="{{ $tanggal->format('Y-m-d') }}" 
                            onchange="window.location.href='{{ route('dana-operasional.laporan-harian', '') }}/' + this.value">
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h4>Laporan Keuangan Operasional</h4>
                        <h5 class="text-muted">{{ $tanggal->locale('id')->isoFormat('dddd, D MMMM Y') }}</h5>
                    </div>

                    @if($saldo)
                        <!-- Summary Saldo -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-label-secondary">
                                    <div class="card-body text-center">
                                        <small class="d-block mb-2">Saldo Awal</small>
                                        <h4 class="{{ $saldo->saldo_awal >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                                            Rp {{ number_format(abs($saldo->saldo_awal), 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-label-info">
                                    <div class="card-body text-center">
                                        <small class="d-block mb-2">Dana Masuk</small>
                                        <h4 class="text-info mb-0">
                                            Rp {{ number_format($saldo->dana_masuk, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-label-warning">
                                    <div class="card-body text-center">
                                        <small class="d-block mb-2">Total Realisasi</small>
                                        <h4 class="text-warning mb-0">
                                            Rp {{ number_format($saldo->total_realisasi, 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-label-{{ $saldo->saldo_akhir >= 0 ? 'success' : 'danger' }}">
                                    <div class="card-body text-center">
                                        <small class="d-block mb-2">Saldo Akhir</small>
                                        <h4 class="{{ $saldo->saldo_akhir >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                                            Rp {{ number_format(abs($saldo->saldo_akhir), 0, ',', '.') }}
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tabel Perhitungan -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="60%">Keterangan</th>
                                        <th width="40%" class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><strong>Saldo Awal (Dari Hari Sebelumnya)</strong></td>
                                        <td class="text-end {{ $saldo->saldo_awal >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format(abs($saldo->saldo_awal), 0, ',', '.') }}
                                            @if($saldo->saldo_awal < 0)
                                                <small class="text-danger">(Kekurangan)</small>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dana Masuk (Pencairan)</strong></td>
                                        <td class="text-end text-info">
                                            <strong>+ Rp {{ number_format($saldo->dana_masuk, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                    <tr class="table-secondary">
                                        <td><strong>TOTAL DANA TERSEDIA</strong></td>
                                        <td class="text-end">
                                            <strong>Rp {{ number_format($saldo->saldo_awal + $saldo->dana_masuk, 0, ',', '.') }}</strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Detail Realisasi -->
                        @if($saldo->pengajuan && $saldo->pengajuan->realisasi->count() > 0)
                        <h6 class="mb-3">Rincian Realisasi Pengeluaran:</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="50%">Uraian</th>
                                        <th width="15%">Kategori</th>
                                        <th width="30%" class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($saldo->pengajuan->realisasi as $index => $real)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $real->uraian }}</strong>
                                            @if($real->keterangan)
                                                <br><small class="text-muted">{{ $real->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($real->kategori)
                                                <span class="badge bg-label-secondary">{{ $real->kategori }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format($real->nominal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL REALISASI:</th>
                                        <th class="text-end">
                                            <strong>- Rp {{ number_format($saldo->total_realisasi, 0, ',', '.') }}</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="ti ti-info-circle me-2"></i>
                            Belum ada realisasi pengeluaran pada hari ini.
                        </div>
                        @endif

                        <!-- Saldo Akhir -->
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr class="table-{{ $saldo->saldo_akhir >= 0 ? 'success' : 'danger' }}">
                                        <td width="60%">
                                            <strong class="fs-5">SALDO AKHIR HARI INI</strong>
                                            <br>
                                            <small>
                                                = Saldo Awal ({{ $saldo->saldo_awal >= 0 ? '+' : '-' }}{{ number_format(abs($saldo->saldo_awal), 0, ',', '.') }}) 
                                                + Dana Masuk ({{ number_format($saldo->dana_masuk, 0, ',', '.') }}) 
                                                - Realisasi ({{ number_format($saldo->total_realisasi, 0, ',', '.') }})
                                            </small>
                                        </td>
                                        <td width="40%" class="text-end">
                                            <h3 class="{{ $saldo->saldo_akhir >= 0 ? 'text-success' : 'text-danger' }} mb-0">
                                                Rp {{ number_format(abs($saldo->saldo_akhir), 0, ',', '.') }}
                                            </h3>
                                            @if($saldo->saldo_akhir < 0)
                                                <span class="badge bg-danger">Kekurangan</span>
                                            @else
                                                <span class="badge bg-success">Sisa Dana</span>
                                            @endif
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-info mt-4">
                            <i class="ti ti-bulb me-2"></i>
                            <strong>Catatan:</strong> Saldo akhir hari ini akan menjadi saldo awal untuk pengajuan di hari berikutnya.
                            @if($saldo->saldo_akhir < 0)
                                Terdapat kekurangan yang perlu ditambahkan ke pengajuan besok.
                            @endif
                        </div>

                        <!-- Print Button -->
                        <div class="text-center mt-4">
                            <button onclick="window.print()" class="btn btn-primary">
                                <i class="ti ti-printer me-1"></i> Cetak Laporan
                            </button>
                        </div>

                    @else
                        <div class="alert alert-warning text-center">
                            <i class="ti ti-alert-circle ti-lg mb-3"></i>
                            <h5>Belum Ada Data</h5>
                            <p class="mb-0">Tidak ada transaksi keuangan pada tanggal {{ $tanggal->format('d/m/Y') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .card-header, .btn, .breadcrumb, nav, .alert {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
}
</style>
@endpush
@endsection
