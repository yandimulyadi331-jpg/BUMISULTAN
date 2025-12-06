@extends('layouts.app')
@section('titlepage', 'Data Pengembalian Inventaris')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Inventaris / Pengembalian</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('pengembalian-inventaris.peminjaman-aktif') }}" class="btn btn-primary">
                            <i class="fa fa-plus me-2"></i> Proses Pengembalian
                        </a>
                        <a href="{{ route('pengembalian-inventaris.export-pdf') }}" class="btn btn-danger" target="_blank">
                            <i class="fa fa-file-pdf me-2"></i> Export PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('pengembalian-inventaris.index') }}" method="GET">
                            <div class="row">
                                <div class="col-lg-3 col-sm-12">
                                    <x-input-with-icon label="Cari Kode / Peminjam" value="{{ Request('search') }}" 
                                        name="search" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status_keterlambatan" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="tepat_waktu" {{ Request('status_keterlambatan') == 'tepat_waktu' ? 'selected' : '' }}>Tepat Waktu</option>
                                            <option value="terlambat" {{ Request('status_keterlambatan') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_dari" class="form-control" value="{{ Request('tanggal_dari') }}" placeholder="Dari Tanggal">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <div class="form-group mb-3">
                                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ Request('tanggal_sampai') }}" placeholder="Sampai Tanggal">
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <button type="submit" class="btn btn-primary me-2">
                                        <i class="fa fa-search me-1"></i> Cari
                                    </button>
                                    <a href="{{ route('pengembalian-inventaris.index') }}" class="btn btn-secondary">
                                        <i class="fa fa-refresh me-1"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Peminjaman</th>
                                <th>Kode Pengembalian</th>
                                <th>Inventaris</th>
                                <th>Peminjam</th>
                                <th>Tgl Kembali</th>
                                <th>Keterlambatan</th>
                                <th>Denda</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pengembalian as $item)
                            <tr class="{{ $item->status_keterlambatan == 'terlambat' ? 'table-warning' : '' }}">
                                <td>{{ $loop->iteration + ($pengembalian->currentPage() - 1) * $pengembalian->perPage() }}</td>
                                <td><strong>{{ $item->peminjaman->kode_peminjaman }}</strong></td>
                                <td><strong>{{ $item->kode_pengembalian }}</strong></td>
                                <td>
                                    <strong>{{ $item->peminjaman->inventaris->nama_barang }}</strong><br>
                                    <small class="text-muted">{{ $item->peminjaman->inventaris->kode_inventaris }}</small>
                                </td>
                                <td>
                                    @if($item->peminjaman->karyawan)
                                        <strong>{{ $item->peminjaman->karyawan->nama_lengkap }}</strong><br>
                                        <small class="text-muted">{{ $item->peminjaman->karyawan->nik }}</small>
                                    @else
                                        <strong>{{ $item->peminjaman->nama_peminjam ?? '-' }}</strong>
                                    @endif
                                </td>
                                <td>
                                    {{ $item->tanggal_pengembalian ? \Carbon\Carbon::parse($item->tanggal_pengembalian)->format('d/m/Y H:i') : '-' }}<br>
                                    <small class="text-muted">
                                        Rencana: {{ $item->peminjaman && $item->peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($item->peminjaman->tanggal_kembali_rencana)->format('d/m/Y H:i') : '-' }}
                                    </small>
                                </td>
                                <td class="text-center">
                                    @if($item->lama_keterlambatan > 0)
                                        <span class="badge bg-danger">{{ $item->lama_keterlambatan }} hari</span>
                                    @else
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($item->denda > 0)
                                        <strong class="text-danger">Rp {{ number_format($item->denda, 0, ',', '.') }}</strong>
                                    @else
                                        <span class="text-success">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->status_keterlambatan == 'terlambat')
                                        <span class="badge bg-warning">Terlambat</span>
                                    @else
                                        <span class="badge bg-success">Tepat Waktu</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('pengembalian-inventaris.show', $item->id) }}" class="btn btn-sm btn-info" title="Detail">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" class="text-center">Tidak ada data pengembalian</td>
                            </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <td colspan="7" class="text-end"><strong>Total Denda:</strong></td>
                                <td class="text-end">
                                    <strong class="text-danger">
                                        Rp {{ number_format($pengembalian->sum('denda'), 0, ',', '.') }}
                                    </strong>
                                </td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-3">
                    {{ $pengembalian->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mt-3">
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Tepat Waktu</h5>
                <h2>{{ $pengembalian->where('status_keterlambatan', 'tepat_waktu')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-check-circle"></i> Pengembalian</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h5>Terlambat</h5>
                <h2>{{ $pengembalian->where('status_keterlambatan', 'terlambat')->count() }}</h2>
                <p class="mb-0"><i class="fa fa-exclamation-triangle"></i> Pengembalian</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Total Denda</h5>
                <h2>Rp {{ number_format($pengembalian->sum('denda') / 1000, 0) }}K</h2>
                <p class="mb-0"><i class="fa fa-money"></i> Denda Keterlambatan</p>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-sm-6">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h5>Rata-rata Keterlambatan</h5>
                <h2>{{ round($pengembalian->avg('lama_keterlambatan'), 1) }} hari</h2>
                <p class="mb-0"><i class="fa fa-clock"></i> Per Pengembalian</p>
            </div>
        </div>
    </div>
</div>

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 3000
        });
    @endif
</script>
@endpush
@endsection
