@extends('layouts.app')
@section('titlepage', 'Summary Potongan Pinjaman')

@section('content')
@section('navigasi')
    <span>Summary Potongan Pinjaman</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text"></i> Summary Potongan Pinjaman
                    </h5>
                    <a href="{{ route('penyesuaiangaji.setkaryawan', Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji)) }}" 
                       class="btn btn-light btn-sm">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Periode -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="text-muted mb-2">Periode Payroll</h6>
                                <h4>{{ getNamabulan($penyesuaiangaji->bulan) }} {{ $penyesuaiangaji->tahun }}</h4>
                                <small class="text-muted">Kode: {{ $penyesuaiangaji->kode_penyesuaian_gaji }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <h6 class="mb-1">Total Karyawan</h6>
                                        <h3 class="mb-0">{{ $summary['total_karyawan'] }}</h3>
                                    </div>
                                    <div class="col-4">
                                        <h6 class="mb-1">Total Cicilan</h6>
                                        <h3 class="mb-0">{{ $summary['total_cicilan'] }}</h3>
                                    </div>
                                    <div class="col-4">
                                        <h6 class="mb-1">Total Potongan</h6>
                                        <h3 class="mb-0">{{ formatAngka($summary['total_amount']) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail per Karyawan -->
                @if(count($summary['details']) > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th class="text-center">Jumlah Cicilan</th>
                                <th class="text-end">Total Potongan</th>
                                <th>Detail Pinjaman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($summary['details'] as $detail)
                            <tr>
                                <td>{{ $detail['nik'] }}</td>
                                <td><strong>{{ $detail['nama'] }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $detail['jumlah_cicilan'] }}</span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($detail['total_potongan'], 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($detail['items'] as $item)
                                        <li class="mb-1">
                                            <small>
                                                <i class="bi bi-check-circle text-success"></i>
                                                <strong>{{ $item->pinjaman->nomor_pinjaman }}</strong> - 
                                                Cicilan ke-{{ $item->cicilan_ke }} 
                                                <span class="text-muted">(Rp {{ number_format($item->jumlah_cicilan, 0, ',', '.') }})</span>
                                                <br>
                                                <span class="text-muted ms-3">
                                                    Jatuh Tempo: {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                                                </span>
                                            </small>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">GRAND TOTAL</th>
                                <th class="text-center">
                                    <span class="badge bg-primary">{{ $summary['total_cicilan'] }}</span>
                                </th>
                                <th class="text-end">
                                    <strong>Rp {{ number_format($summary['total_amount'], 0, ',', '.') }}</strong>
                                </th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Belum ada potongan pinjaman untuk periode ini.
                    Klik tombol "Generate Potongan Pinjaman" untuk memproses cicilan yang jatuh tempo.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        margin-bottom: 1rem;
    }
    .table-responsive {
        max-height: 600px;
        overflow-y: auto;
    }
</style>

@endsection
