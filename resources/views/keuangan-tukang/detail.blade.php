@extends('layouts.app')
@section('titlepage', 'Detail Keuangan Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Keuangan Tukang /</span> Detail
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">💰 Detail Keuangan Tukang</h5>
                  <p class="text-muted mb-0">{{ $tukang->nama_tukang }} ({{ $tukang->kode_tukang }}) - {{ $bulanNama }}</p>
               </div>
               <a href="{{ route('keuangan-tukang.index') }}" class="btn btn-secondary btn-sm">
                  <i class="ti ti-arrow-left me-1"></i> Kembali
               </a>
            </div>
         </div>
         <div class="card-body">
            <!-- Summary Cards -->
            <div class="row mb-4">
               <div class="col-md-4">
                  <div class="card bg-success text-white">
                     <div class="card-body">
                        <h6 class="card-title">Total Pendapatan</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}</h3>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="card bg-danger text-white">
                     <div class="card-body">
                        <h6 class="card-title">Total Potongan</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['total_kredit'], 0, ',', '.') }}</h3>
                     </div>
                  </div>
               </div>
               <div class="col-md-4">
                  <div class="card bg-primary text-white">
                     <div class="card-body">
                        <h6 class="card-title">Saldo Bersih</h6>
                        <h3 class="mb-0">Rp {{ number_format($summary['total_bersih'], 0, ',', '.') }}</h3>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Pinjaman Aktif -->
            @if($pinjamanAktif->count() > 0)
            <div class="alert alert-warning">
               <h6 class="alert-heading"><i class="ti ti-alert-triangle me-2"></i> Pinjaman Aktif</h6>
               @foreach($pinjamanAktif as $pinjaman)
               <div class="mb-2">
                  <strong>{{ $pinjaman->keterangan }}</strong><br>
                  Sisa: Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }} | 
                  Cicilan/Minggu: Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}
               </div>
               @endforeach
            </div>
            @endif

            <!-- Tabel Transaksi -->
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="5%">No</th>
                        <th width="10%">Tanggal</th>
                        <th width="10%">Tipe</th>
                        <th width="15%">Kategori</th>
                        <th width="30%">Keterangan</th>
                        <th width="15%">Debit</th>
                        <th width="15%">Kredit</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($transaksi as $index => $t)
                        <tr>
                           <td class="text-center">{{ $index + 1 }}</td>
                           <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d/m/Y') }}</td>
                           <td>
                              @if($t->tipe == 'debit')
                                 <span class="badge bg-success">Debit</span>
                              @else
                                 <span class="badge bg-danger">Kredit</span>
                              @endif
                           </td>
                           <td>{{ ucwords(str_replace('_', ' ', $t->kategori)) }}</td>
                           <td>{{ $t->keterangan }}</td>
                           <td class="text-end text-success fw-bold">
                              @if($t->tipe == 'debit')
                                 Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                              @endif
                           </td>
                           <td class="text-end text-danger fw-bold">
                              @if($t->tipe == 'kredit')
                                 Rp {{ number_format($t->jumlah, 0, ',', '.') }}
                              @endif
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="7" class="text-center">Tidak ada transaksi</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($transaksi->count() > 0)
                     <tfoot class="table-light">
                        <tr>
                           <th colspan="5" class="text-end">Total:</th>
                           <th class="text-end text-success">Rp {{ number_format($summary['total_debit'], 0, ',', '.') }}</th>
                           <th class="text-end text-danger">Rp {{ number_format($summary['total_kredit'], 0, ',', '.') }}</th>
                        </tr>
                        <tr>
                           <th colspan="5" class="text-end">Saldo Bersih:</th>
                           <th colspan="2" class="text-end text-primary fw-bold">
                              Rp {{ number_format($summary['total_bersih'], 0, ',', '.') }}
                           </th>
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
