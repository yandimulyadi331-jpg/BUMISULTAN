@extends('layouts.app')
@section('titlepage', 'Laporan Keuangan Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Keuangan Tukang /</span> Laporan
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">📊 Laporan Keuangan Tukang</h5>
                  <p class="text-muted mb-0">{{ $bulanNama }}</p>
               </div>
               <div>
                  <a href="{{ route('keuangan-tukang.export-pdf') }}?bulan={{ $bulan }}&tahun={{ $tahun }}" 
                     class="btn btn-danger btn-sm me-2" target="_blank">
                     <i class="ti ti-file-pdf me-1"></i> Export PDF
                  </a>
                  <a href="{{ route('keuangan-tukang.index') }}" class="btn btn-secondary btn-sm">
                     <i class="ti ti-arrow-left me-1"></i> Kembali
                  </a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <!-- Filter -->
            <div class="row mb-3">
               <div class="col-md-3">
                  <select class="form-select" id="filterBulan">
                     <option value="">Pilih Bulan</option>
                     @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                           {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}
                        </option>
                     @endfor
                  </select>
               </div>
               <div class="col-md-2">
                  <select class="form-select" id="filterTahun">
                     @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                     @endfor
                  </select>
               </div>
               <div class="col-md-2">
                  <button class="btn btn-primary" onclick="filterData()">
                     <i class="ti ti-search me-1"></i> Tampilkan
                  </button>
               </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
               <div class="col-md-3">
                  <div class="card bg-success text-white">
                     <div class="card-body">
                        <h6 class="card-title">Total Pendapatan</h6>
                        <h4 class="mb-0">Rp {{ number_format($tukangs->sum('total_debit'), 0, ',', '.') }}</h4>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-danger text-white">
                     <div class="card-body">
                        <h6 class="card-title">Total Potongan</h6>
                        <h4 class="mb-0">Rp {{ number_format($tukangs->sum('total_kredit'), 0, ',', '.') }}</h4>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-primary text-white">
                     <div class="card-body">
                        <h6 class="card-title">Total Bersih</h6>
                        <h4 class="mb-0">Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}</h4>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-warning text-white">
                     <div class="card-body">
                        <h6 class="card-title">Pinjaman Aktif</h6>
                        <h4 class="mb-0">Rp {{ number_format($tukangs->sum('pinjaman_aktif'), 0, ',', '.') }}</h4>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="20%">Nama Tukang</th>
                        <th width="15%">Total Pendapatan</th>
                        <th width="15%">Total Potongan</th>
                        <th width="15%">Total Bersih</th>
                        <th width="15%">Pinjaman Aktif</th>
                        <th width="10%">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($tukangs as $index => $t)
                        <tr>
                           <td class="text-center">{{ $index + 1 }}</td>
                           <td>{{ $t->kode_tukang }}</td>
                           <td>{{ $t->nama_tukang }}</td>
                           <td class="text-end text-success fw-bold">Rp {{ number_format($t->total_debit, 0, ',', '.') }}</td>
                           <td class="text-end text-danger fw-bold">Rp {{ number_format($t->total_kredit, 0, ',', '.') }}</td>
                           <td class="text-end text-primary fw-bold">Rp {{ number_format($t->total_bersih, 0, ',', '.') }}</td>
                           <td class="text-end text-warning fw-bold">Rp {{ number_format($t->pinjaman_aktif, 0, ',', '.') }}</td>
                           <td class="text-center">
                              <a href="{{ route('keuangan-tukang.detail', $t->id) }}?bulan={{ $bulan }}&tahun={{ $tahun }}" 
                                 class="btn btn-sm btn-primary" title="Lihat Detail">
                                 <i class="ti ti-eye"></i>
                              </a>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($tukangs->count() > 0)
                     <tfoot class="table-light">
                        <tr>
                           <th colspan="3" class="text-end">TOTAL:</th>
                           <th class="text-end text-success">Rp {{ number_format($tukangs->sum('total_debit'), 0, ',', '.') }}</th>
                           <th class="text-end text-danger">Rp {{ number_format($tukangs->sum('total_kredit'), 0, ',', '.') }}</th>
                           <th class="text-end text-primary">Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}</th>
                           <th class="text-end text-warning">Rp {{ number_format($tukangs->sum('pinjaman_aktif'), 0, ',', '.') }}</th>
                           <th></th>
                        </tr>
                     </tfoot>
                  @endif
               </table>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

<script>
function filterData() {
   let bulan = document.getElementById('filterBulan').value;
   let tahun = document.getElementById('filterTahun').value;
   
   window.location.href = '{{ route("keuangan-tukang.laporan") }}?bulan=' + bulan + '&tahun=' + tahun;
}
</script>
