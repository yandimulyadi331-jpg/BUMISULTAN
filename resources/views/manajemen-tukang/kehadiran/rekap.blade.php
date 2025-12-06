@extends('layouts.app')
@section('titlepage', 'Rekap Kehadiran Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang /</span> Rekap Kehadiran
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">Rekap Kehadiran Tukang</h5>
                  <p class="text-muted mb-0">{{ $bulanNama }}</p>
               </div>
               <div class="d-flex gap-2">
                  <a href="{{ route('kehadiran-tukang.export-pdf', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-danger btn-sm" target="_blank">
                     <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                  </a>
                  <form action="{{ route('kehadiran-tukang.rekap') }}" method="GET" class="d-flex align-items-center gap-2">
                     <select name="bulan" class="form-select" onchange="this.form.submit()">
                        @for($m = 1; $m <= 12; $m++)
                           <option value="{{ $m }}" {{ $m == $bulan ? 'selected' : '' }}>
                              {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}
                           </option>
                        @endfor
                     </select>
                     <select name="tahun" class="form-select" onchange="this.form.submit()">
                        @for($y = date('Y'); $y >= date('Y') - 3; $y--)
                           <option value="{{ $y }}" {{ $y == $tahun ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                     </select>
                  </form>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="3%">No</th>
                        <th width="7%">Kode</th>
                        <th width="15%">Nama Tukang</th>
                        <th width="9%">Tarif/Hari</th>
                        <th width="6%" class="text-center">Hadir</th>
                        <th width="6%" class="text-center">1/2 Hari</th>
                        <th width="6%" class="text-center">Alfa</th>
                        <th width="6%" class="text-center" title="Lembur Full dibayar Kamis">L.Full</th>
                        <th width="6%" class="text-center" title="Lembur Setengah dibayar Kamis">L.1/2</th>
                        <th width="7%" class="text-center" title="Lembur Full CASH hari ini">💰Full</th>
                        <th width="7%" class="text-center" title="Lembur Setengah CASH hari ini">💰1/2</th>
                        <th width="12%">Total Upah</th>
                        <th width="6%">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($tukangs as $index => $tukang)
                        <tr>
                           <td>{{ $index + 1 }}</td>
                           <td><strong>{{ $tukang->kode_tukang }}</strong></td>
                           <td>
                              <div class="d-flex align-items-center">
                                 @if($tukang->foto)
                                    <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" 
                                       class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                 @endif
                                 {{ $tukang->nama_tukang }}
                              </div>
                           </td>
                           <td>Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</td>
                           <td class="text-center">
                              <span class="badge bg-success">{{ $tukang->total_hadir }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-warning">{{ $tukang->total_setengah_hari }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-secondary">{{ $tukang->total_tidak_hadir }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-danger">{{ $tukang->total_lembur_full }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-warning">{{ $tukang->total_lembur_setengah }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-success" style="font-weight: bold;">{{ $tukang->total_lembur_full_cash }}</span>
                           </td>
                           <td class="text-center">
                              <span class="badge bg-info" style="font-weight: bold;">{{ $tukang->total_lembur_setengah_cash }}</span>
                           </td>
                           <td>
                              <strong class="text-success fs-6">
                                 Rp {{ number_format($tukang->total_upah, 0, ',', '.') }}
                              </strong>
                           </td>
                           <td class="text-center">
                              <a href="{{ route('kehadiran-tukang.detail', $tukang->id) }}?bulan={{ $bulan }}&tahun={{ $tahun }}" 
                                 class="btn btn-sm btn-info" title="Detail">
                                 <i class="ti ti-eye"></i>
                              </a>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="13" class="text-center">Tidak ada data</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($tukangs->count() > 0)
                     <tfoot class="table-light">
                        <tr>
                           <th colspan="4" class="text-end">Total Keseluruhan:</th>
                           <th class="text-center">{{ $tukangs->sum('total_hadir') }}</th>
                           <th class="text-center">{{ $tukangs->sum('total_setengah_hari') }}</th>
                           <th class="text-center">{{ $tukangs->sum('total_tidak_hadir') }}</th>
                           <th class="text-center">{{ $tukangs->sum('total_lembur_full') }}</th>
                           <th class="text-center">{{ $tukangs->sum('total_lembur_setengah') }}</th>
                           <th class="text-center"><strong>{{ $tukangs->sum('total_lembur_full_cash') }}</strong></th>
                           <th class="text-center"><strong>{{ $tukangs->sum('total_lembur_setengah_cash') }}</strong></th>
                           <th class="text-success">
                              <strong class="fs-5">
                                 Rp {{ number_format($tukangs->sum('total_upah'), 0, ',', '.') }}
                              </strong>
                           </th>
                           <th></th>
                        </tr>
                     </tfoot>
                  @endif
               </table>
            </div>
            
            <!-- Summary Cards -->
            @if($tukangs->count() > 0)
               <div class="row mt-4">
                  <div class="col-md-3">
                     <div class="card bg-success text-white">
                        <div class="card-body text-center">
                           <h3>{{ $tukangs->sum('total_hadir') }}</h3>
                           <p class="mb-0">Total Hari Hadir</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-warning">
                        <div class="card-body text-center">
                           <h3>{{ $tukangs->sum('total_setengah_hari') }}</h3>
                           <p class="mb-0">Total Setengah Hari</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-info text-white">
                        <div class="card-body text-center">
                           <h3>{{ $tukangs->sum('total_lembur') }}</h3>
                           <p class="mb-0">Total Hari Lembur</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                           <h5>Rp {{ number_format($tukangs->sum('total_upah'), 0, ',', '.') }}</h5>
                           <p class="mb-0">Total Pengeluaran Gaji</p>
                        </div>
                     </div>
                  </div>
               </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection
