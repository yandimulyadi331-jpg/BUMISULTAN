@extends('layouts.app')
@section('titlepage', 'Laporan Stok Per Ukuran')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Majlis Ta'lim /</span> Laporan Stok Per Ukuran
@endsection

<!-- Navigation Tabs -->
@include('majlistaklim.partials.navigation')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="ti ti-chart-bar me-2"></i>Laporan Stok Hadiah Per Ukuran</h5>
                <button class="btn btn-sm btn-success" onclick="window.print()">
                    <i class="ti ti-printer me-1"></i> Cetak
                </button>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Filter Jenis Hadiah</label>
                            <select name="jenis_hadiah" class="form-select" onchange="this.form.submit()">
                                <option value="all" {{ $jenisFilter == 'all' ? 'selected' : '' }}>Semua Jenis</option>
                                <option value="sarung" {{ $jenisFilter == 'sarung' ? 'selected' : '' }}>Sarung</option>
                                <option value="peci" {{ $jenisFilter == 'peci' ? 'selected' : '' }}>Peci</option>
                                <option value="gamis" {{ $jenisFilter == 'gamis' ? 'selected' : '' }}>Gamis</option>
                                <option value="mukena" {{ $jenisFilter == 'mukena' ? 'selected' : '' }}>Mukena</option>
                                <option value="tasbih" {{ $jenisFilter == 'tasbih' ? 'selected' : '' }}>Tasbih</option>
                                <option value="sajadah" {{ $jenisFilter == 'sajadah' ? 'selected' : '' }}>Sajadah</option>
                                <option value="al_quran" {{ $jenisFilter == 'al_quran' ? 'selected' : '' }}>Al-Quran</option>
                                <option value="buku" {{ $jenisFilter == 'buku' ? 'selected' : '' }}>Buku</option>
                                <option value="lainnya" {{ $jenisFilter == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                    </div>
                </form>

                <!-- Laporan Table -->
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableLaporan">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Kode Hadiah</th>
                                <th>Nama Hadiah</th>
                                <th>Jenis</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp
                            @forelse($hadiahList as $hadiah)
                                @if(!empty($hadiah->stok_ukuran))
                                    @php 
                                        $totalUkuran = count($hadiah->stok_ukuran);
                                        $index = 0;
                                    @endphp
                                    @foreach($hadiah->stok_ukuran as $ukuran => $stok)
                                        <tr>
                                            @if($index == 0)
                                                <td rowspan="{{ $totalUkuran }}" class="align-middle">{{ $no++ }}</td>
                                                <td rowspan="{{ $totalUkuran }}" class="align-middle">{{ $hadiah->kode_hadiah }}</td>
                                                <td rowspan="{{ $totalUkuran }}" class="align-middle">{{ $hadiah->nama_hadiah }}</td>
                                                <td rowspan="{{ $totalUkuran }}" class="align-middle">
                                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $hadiah->jenis_hadiah)) }}</span>
                                                </td>
                                            @endif
                                            <td><strong>{{ $ukuran }}</strong></td>
                                            <td>
                                                <span class="badge {{ $stok > 10 ? 'bg-success' : ($stok > 5 ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ $stok }} pcs
                                                </span>
                                            </td>
                                            @if($index == 0)
                                                <td rowspan="{{ $totalUkuran }}" class="align-middle">
                                                    @if($hadiah->status == 'tersedia')
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @elseif($hadiah->status == 'habis')
                                                        <span class="badge bg-danger">Habis</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                        @php $index++; @endphp
                                    @endforeach
                                @endif
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data hadiah dengan tracking ukuran</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                @if($hadiahList->count() > 0)
                <div class="alert alert-info mt-3">
                    <strong>Total Hadiah dengan Tracking Ukuran:</strong> {{ $hadiahList->count() }} item<br>
                    <strong>Total Ukuran:</strong> {{ $hadiahList->sum(function($h) { return count($h->stok_ukuran ?? []); }) }} variasi<br>
                    <strong>Total Stok:</strong> {{ $hadiahList->sum(function($h) { return array_sum($h->stok_ukuran ?? []); }) }} pcs
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<style>
@media print {
    .card-header button, form { display: none !important; }
}
</style>
@endpush
