@extends('layouts.app')
@section('titlepage', 'Detail Kehadiran Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang / Rekap Kehadiran /</span> Detail
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">Detail Kehadiran: {{ $tukang->nama_tukang }}</h5>
                  <p class="text-muted mb-0">{{ $bulanNama }}</p>
               </div>
               <a href="{{ route('kehadiran-tukang.rekap') }}?bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-secondary">
                  <i class="ti ti-arrow-left me-1"></i> Kembali
               </a>
            </div>
         </div>
         <div class="card-body">
            <!-- Info Tukang -->
            <div class="row mb-4">
               <div class="col-md-2 text-center">
                  @if($tukang->foto)
                     <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" 
                        class="img-thumbnail" style="max-width: 120px;">
                  @else
                     <div class="avatar avatar-xl">
                        <span class="avatar-initial rounded bg-label-secondary" style="font-size: 3rem;">
                           <i class="ti ti-user"></i>
                        </span>
                     </div>
                  @endif
               </div>
               <div class="col-md-10">
                  <table class="table table-borderless">
                     <tr>
                        <td width="20%"><strong>Kode Tukang</strong></td>
                        <td width="5%">:</td>
                        <td>{{ $tukang->kode_tukang }}</td>
                     </tr>
                     <tr>
                        <td><strong>Nama</strong></td>
                        <td>:</td>
                        <td>{{ $tukang->nama_tukang }}</td>
                     </tr>
                     <tr>
                        <td><strong>Keahlian</strong></td>
                        <td>:</td>
                        <td>{{ $tukang->keahlian ?? '-' }}</td>
                     </tr>
                     <tr>
                        <td><strong>Tarif Harian</strong></td>
                        <td>:</td>
                        <td><strong class="text-primary">Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</strong></td>
                     </tr>
                  </table>
               </div>
            </div>

            <hr>

            <!-- Detail Kehadiran -->
            <h6 class="mb-3">Rincian Kehadiran</h6>
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="5%">No</th>
                        <th width="15%">Tanggal</th>
                        <th width="15%">Hari</th>
                        <th width="15%">Status</th>
                        <th width="10%">Jam Kerja</th>
                        <th width="10%">Lembur</th>
                        <th width="15%">Upah</th>
                        <th>Keterangan</th>
                     </tr>
                  </thead>
                  <tbody>
                     @php
                        $totalUpahHarian = 0;
                        $totalUpahLembur = 0;
                     @endphp
                     @forelse($kehadiran as $index => $k)
                        @php
                           $totalUpahHarian += $k->upah_harian;
                           $totalUpahLembur += $k->upah_lembur;
                        @endphp
                        <tr>
                           <td>{{ $index + 1 }}</td>
                           <td>{{ \Carbon\Carbon::parse($k->tanggal)->format('d/m/Y') }}</td>
                           <td>{{ \Carbon\Carbon::parse($k->tanggal)->locale('id')->isoFormat('dddd') }}</td>
                           <td>
                              @if($k->status == 'hadir')
                                 <span class="badge bg-success">Hadir</span>
                              @elseif($k->status == 'setengah_hari')
                                 <span class="badge bg-warning">Setengah Hari</span>
                              @else
                                 <span class="badge bg-secondary">Tidak Hadir</span>
                              @endif
                           </td>
                           <td>{{ $k->jam_kerja }} jam</td>
                           <td class="text-center">
                              @if($k->lembur == 'full' && !$k->lembur_dibayar_cash)
                                 <span class="badge bg-danger">Full (Kamis)</span>
                              @elseif($k->lembur == 'setengah_hari' && !$k->lembur_dibayar_cash)
                                 <span class="badge bg-warning">Setengah (Kamis)</span>
                              @elseif($k->lembur == 'full' && $k->lembur_dibayar_cash)
                                 <span class="badge bg-success">💰 Full CASH</span>
                              @elseif($k->lembur == 'setengah_hari' && $k->lembur_dibayar_cash)
                                 <span class="badge bg-info">💰 1/2 CASH</span>
                              @else
                                 <span class="badge bg-secondary">Tidak</span>
                              @endif
                           </td>
                           <td>
                              <strong class="text-success">Rp {{ number_format($k->total_upah, 0, ',', '.') }}</strong>
                              @if($k->lembur != 'tidak' && $k->upah_lembur > 0)
                                 <br>
                                 <small class="text-muted">
                                    (Rp {{ number_format($k->upah_harian, 0, ',', '.') }} + 
                                    Rp {{ number_format($k->upah_lembur, 0, ',', '.') }})
                                 </small>
                                 @if($k->lembur_dibayar_cash)
                                    <br>
                                    <small class="badge badge-sm bg-success">Dibayar: {{ $k->tanggal_bayar_lembur ? $k->tanggal_bayar_lembur->format('d/m/Y') : 'Hari ini' }}</small>
                                 @endif
                              @endif
                           </td>
                           <td>{{ $k->keterangan ?? '-' }}</td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="8" class="text-center">Tidak ada data kehadiran bulan ini</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($kehadiran->count() > 0)
                     <tfoot class="table-light">
                        <tr>
                           <th colspan="6" class="text-end">Total Gaji Bulan Ini:</th>
                           <th colspan="2" class="text-success">
                              <strong class="fs-5">Rp {{ number_format($total_upah, 0, ',', '.') }}</strong>
                           </th>
                        </tr>
                        <tr>
                           <td colspan="8" class="text-end">
                              <small class="text-muted">
                                 Upah Harian: Rp {{ number_format($totalUpahHarian, 0, ',', '.') }} + 
                                 Upah Lembur: Rp {{ number_format($totalUpahLembur, 0, ',', '.') }}
                              </small>
                           </td>
                        </tr>
                     </tfoot>
                  @endif
               </table>
            </div>

            <!-- Summary -->
            @if($kehadiran->count() > 0)
               <div class="row mt-4">
                  <div class="col-md-3">
                     <div class="card bg-success text-white">
                        <div class="card-body text-center">
                           <h4>{{ $kehadiran->where('status', 'hadir')->count() }}</h4>
                           <p class="mb-0">Hari Hadir</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-warning">
                        <div class="card-body text-center">
                           <h4>{{ $kehadiran->where('status', 'setengah_hari')->count() }}</h4>
                           <p class="mb-0">Setengah Hari</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-info text-white">
                        <div class="card-body text-center">
                           <h4>{{ $kehadiran->where('lembur', true)->count() }}</h4>
                           <p class="mb-0">Hari Lembur</p>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-3">
                     <div class="card bg-secondary text-white">
                        <div class="card-body text-center">
                           <h4>{{ $kehadiran->where('status', 'tidak_hadir')->count() }}</h4>
                           <p class="mb-0">Tidak Hadir</p>
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
