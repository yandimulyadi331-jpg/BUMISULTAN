@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-file-text me-2"></i>Detail Pengajuan Dana
                    </h5>
                    <div>
                        <a href="{{ route('dana-operasional.index') }}" class="btn btn-sm btn-secondary">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Info Pengajuan -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">No. Pengajuan:</th>
                                    <td><strong>{{ $pengajuan->nomor_pengajuan }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Tanggal:</th>
                                    <td>{{ $pengajuan->tanggal_pengajuan->format('d F Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Diajukan oleh:</th>
                                    <td>{{ $pengajuan->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($pengajuan->status == 'draft')
                                            <span class="badge bg-secondary">Draft</span>
                                        @elseif($pengajuan->status == 'diajukan')
                                            <span class="badge bg-warning">Menunggu Pencairan</span>
                                        @elseif($pengajuan->status == 'dicairkan')
                                            <span class="badge bg-info">Sudah Dicairkan</span>
                                        @else
                                            <span class="badge bg-success">Selesai</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-sm">
                                <tr>
                                    <th width="40%">Saldo Sebelumnya:</th>
                                    <td>
                                        <span class="{{ $pengajuan->saldo_sebelumnya >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format(abs($pengajuan->saldo_sebelumnya), 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Total Pengajuan:</th>
                                    <td><strong>Rp {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}</strong></td>
                                </tr>
                                @if($pengajuan->nominal_cair)
                                <tr>
                                    <th>Nominal Cair:</th>
                                    <td><strong class="text-success">Rp {{ number_format($pengajuan->nominal_cair, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <th>Dicairkan pada:</th>
                                    <td>{{ $pengajuan->tanggal_cair->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Dicairkan oleh:</th>
                                    <td>{{ $pengajuan->pencair->name ?? '-' }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>

                    <!-- Rincian Kebutuhan -->
                    <h6 class="mb-3">Rincian Kebutuhan:</h6>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="60%">Uraian</th>
                                    <th width="35%" class="text-end">Nominal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pengajuan->rincian_kebutuhan as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $item['uraian'] }}</td>
                                    <td class="text-end">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="2" class="text-end">TOTAL:</th>
                                    <th class="text-end">Rp {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Keterangan -->
                    @if($pengajuan->keterangan)
                    <div class="mb-4">
                        <h6>Keterangan:</h6>
                        <p class="text-muted">{{ $pengajuan->keterangan }}</p>
                    </div>
                    @endif

                    <!-- Catatan Pencairan -->
                    @if($pengajuan->catatan_pencairan)
                    <div class="mb-4">
                        <h6>Catatan Pencairan dari Boss:</h6>
                        <div class="alert alert-info">
                            {{ $pengajuan->catatan_pencairan }}
                        </div>
                    </div>
                    @endif

                    <!-- Realisasi -->
                    @if($pengajuan->status == 'dicairkan' || $pengajuan->status == 'selesai')
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0">Laporan Realisasi:</h6>
                            <a href="{{ route('dana-operasional.create-realisasi', $pengajuan->id) }}" class="btn btn-sm btn-success">
                                <i class="ti ti-plus me-1"></i> Tambah Realisasi
                            </a>
                        </div>

                        @if($pengajuan->realisasi->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Tanggal</th>
                                        <th width="45%">Uraian</th>
                                        <th width="20%" class="text-end">Nominal</th>
                                        <th width="15%">Input By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuan->realisasi as $index => $real)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>{{ $real->tanggal_realisasi->format('d/m/Y') }}</td>
                                        <td>
                                            <strong>{{ $real->uraian }}</strong>
                                            @if($real->kategori)
                                                <br><small class="text-muted">{{ $real->kategori }}</small>
                                            @endif
                                            @if($real->keterangan)
                                                <br><small class="text-muted">{{ $real->keterangan }}</small>
                                            @endif
                                        </td>
                                        <td class="text-end">Rp {{ number_format($real->nominal, 0, ',', '.') }}</td>
                                        <td><small>{{ $real->creator->name }}</small></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-warning">
                                    <tr>
                                        <th colspan="3" class="text-end">TOTAL REALISASI:</th>
                                        <th class="text-end">Rp {{ number_format($pengajuan->total_realisasi, 0, ',', '.') }}</th>
                                        <th></th>
                                    </tr>
                                    <tr class="table-info">
                                        <th colspan="3" class="text-end">Sisa Dana:</th>
                                        <th class="text-end {{ $pengajuan->sisa_dana >= 0 ? 'text-success' : 'text-danger' }}">
                                            Rp {{ number_format(abs($pengajuan->sisa_dana), 0, ',', '.') }}
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="ti ti-alert-circle me-2"></i>
                            Belum ada laporan realisasi. Silakan tambahkan realisasi pengeluaran.
                        </div>
                        @endif
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 justify-content-end">
                        @if($pengajuan->status == 'draft')
                            <form action="{{ route('dana-operasional.ajukan-pengajuan', $pengajuan->id) }}" method="POST" 
                                onsubmit="return confirm('Ajukan pengajuan ini ke grup WA?')">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-send me-1"></i> Ajukan ke Boss
                                </button>
                            </form>
                        @endif

                        @if($pengajuan->status == 'diajukan' && auth()->user()->hasRole('super admin'))
                            <a href="{{ route('dana-operasional.form-pencairan', $pengajuan->id) }}" class="btn btn-success">
                                <i class="ti ti-cash me-1"></i> Cairkan Dana
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
