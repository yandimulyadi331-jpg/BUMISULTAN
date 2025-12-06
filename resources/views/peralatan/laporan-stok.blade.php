@extends('layouts.app')
@section('titlepage', 'Laporan Stok Peralatan BS')

@section('content')
@section('navigasi')
    <span><a href="{{ route('peralatan.index') }}">PERALATAN BS</a> / Laporan Stok</span>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-chart-bar me-2"></i> Laporan Stok Peralatan</h4>
                    <a href="{{ route('peralatan.export-laporan-stok') }}?{{ http_build_query(request()->all()) }}" 
                        class="btn btn-light btn-sm" target="_blank">
                        <i class="ti ti-file-pdf me-1"></i> Export PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h3>{{ $totalPeralatan }}</h3>
                                <p class="mb-0">Total Jenis Peralatan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h3>{{ $totalStokTersedia }}</h3>
                                <p class="mb-0">Stok Tersedia</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center">
                                <h3>{{ $totalStokDipinjam }}</h3>
                                <p class="mb-0">Stok Dipinjam</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h3>{{ $stokMenipis }}</h3>
                                <p class="mb-0">Stok Menipis</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter -->
                <form action="{{ route('peralatan.laporan-stok') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="kategori" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ Request('kategori') == $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="stok_menipis" value="1" 
                                    {{ Request('stok_menipis') ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Tampilkan hanya stok menipis
                                </label>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-filter me-1"></i> Filter
                            </button>
                            <a href="{{ route('peralatan.laporan-stok') }}" class="btn btn-secondary">
                                <i class="ti ti-refresh me-1"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Tabel Laporan -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Peralatan</th>
                                <th>Kategori</th>
                                <th>Lokasi</th>
                                <th class="text-center">Stok Awal</th>
                                <th class="text-center">Tersedia</th>
                                <th class="text-center">Dipinjam</th>
                                <th class="text-center">Rusak</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($peralatan as $index => $item)
                            <tr class="{{ $item->isStokMenipis() ? 'table-warning' : '' }}">
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $item->kode_peralatan }}</strong></td>
                                <td>{{ $item->nama_peralatan }}</td>
                                <td><span class="badge bg-info">{{ $item->kategori }}</span></td>
                                <td>{{ $item->lokasi_penyimpanan ?? '-' }}</td>
                                <td class="text-center">{{ $item->stok_awal }} {{ $item->satuan }}</td>
                                <td class="text-center">
                                    <span class="badge bg-success">{{ $item->stok_tersedia }} {{ $item->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-warning">{{ $item->stok_dipinjam }} {{ $item->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-danger">{{ $item->stok_rusak }} {{ $item->satuan }}</span>
                                </td>
                                <td class="text-center">
                                    <strong>{{ $item->total_stok }} {{ $item->satuan }}</strong>
                                </td>
                                <td class="text-center">
                                    @if($item->isStokMenipis())
                                        <span class="badge bg-danger">
                                            <i class="ti ti-alert-triangle me-1"></i> Menipis
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="ti ti-check me-1"></i> Aman
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center py-4">Tidak ada data</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
