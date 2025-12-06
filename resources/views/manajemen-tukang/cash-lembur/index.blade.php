@extends('layouts.app')
@section('titlepage', 'Pembayaran Cash Lembur')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang /</span> Pembayaran Cash Lembur
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">💰 Pembayaran Cash Lembur</h5>
                  <p class="text-muted mb-0">{{ $hariNama }}</p>
               </div>
               <div class="d-flex gap-2">
                  <a href="{{ route('kehadiran-tukang.index') }}" class="btn btn-secondary btn-sm">
                     <i class="ti ti-arrow-left me-1"></i> Kembali ke Absensi
                  </a>
                  <form action="{{ route('cash-lembur.index') }}" method="GET" class="d-flex align-items-center">
                     <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                  </form>
               </div>
            </div>
         </div>
         <div class="card-body">
            @if($isJumat)
               <div class="alert alert-info">
                  <i class="ti ti-info-circle me-2"></i>
                  <strong>Hari Jumat (Libur)</strong> - Tidak ada lembur hari ini
               </div>
            @else
               <div class="alert alert-success">
                  <i class="ti ti-info-circle me-2"></i>
                  Halaman ini khusus untuk mengatur pembayaran lembur: <strong>Bayar Cash Hari Ini</strong> atau <strong>Bayar Kamis</strong>
               </div>

               @if($kehadirans->count() > 0)
                  <div class="alert alert-warning">
                     <strong>Total Tukang Lembur Hari Ini: {{ $kehadirans->count() }} orang</strong>
                     <br>Total yang bayar cash: <strong>{{ $kehadirans->where('lembur_dibayar_cash', true)->count() }} orang</strong>
                  </div>
               @endif
               
               <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                     <thead class="table-dark">
                        <tr>
                           <th width="5%">No</th>
                           <th width="10%">Kode</th>
                           <th>Nama Tukang</th>
                           <th width="12%">Tarif/Hari</th>
                           <th width="10%">Status</th>
                           <th width="12%">Jenis Lembur</th>
                           <th width="12%">Upah Lembur</th>
                           <th width="15%">Pembayaran</th>
                           <th width="10%">Tanggal Bayar</th>
                           <th width="8%">Aksi</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse($kehadirans as $index => $kehadiran)
                           <tr id="row-{{ $kehadiran->id }}">
                              <td>{{ $index + 1 }}</td>
                              <td><strong>{{ $kehadiran->tukang->kode_tukang }}</strong></td>
                              <td>
                                 <div class="d-flex align-items-center">
                                    @if($kehadiran->tukang->foto)
                                       <img src="{{ Storage::url('tukang/' . $kehadiran->tukang->foto) }}" 
                                          class="rounded me-2" width="32" height="32" style="object-fit: cover;">
                                    @endif
                                    {{ $kehadiran->tukang->nama_tukang }}
                                 </div>
                              </td>
                              <td>Rp {{ number_format($kehadiran->tukang->tarif_harian, 0, ',', '.') }}</td>
                              <td class="text-center">
                                 @if($kehadiran->status == 'hadir')
                                    <span class="badge bg-success">Hadir</span>
                                 @elseif($kehadiran->status == 'setengah_hari')
                                    <span class="badge bg-warning">Setengah Hari</span>
                                 @endif
                              </td>
                              <td class="text-center">
                                 @if($kehadiran->lembur == 'full')
                                    <span class="badge bg-danger">
                                       <i class="ti ti-clock-hour-8"></i> Lembur Full
                                    </span>
                                 @elseif($kehadiran->lembur == 'setengah_hari')
                                    <span class="badge bg-warning">
                                       <i class="ti ti-clock-hour-4"></i> Lembur 1/2 Hari
                                    </span>
                                 @endif
                              </td>
                              <td>
                                 <strong class="text-primary">
                                    Rp {{ number_format($kehadiran->upah_lembur, 0, ',', '.') }}
                                 </strong>
                              </td>
                              <td class="text-center">
                                 <button type="button" 
                                    class="btn btn-sm w-100 {{ $kehadiran->lembur_dibayar_cash ? 'btn-success' : 'btn-secondary' }}"
                                    data-kehadiran-id="{{ $kehadiran->id }}"
                                    onclick="toggleCash(this)">
                                    @if($kehadiran->lembur_dibayar_cash)
                                       <i class="ti ti-cash"></i> Bayar Hari Ini
                                    @else
                                       <i class="ti ti-calendar"></i> Bayar Kamis
                                    @endif
                                 </button>
                              </td>
                              <td class="text-center tanggal-bayar">
                                 @if($kehadiran->lembur_dibayar_cash && $kehadiran->tanggal_bayar_lembur)
                                    <small class="text-success">
                                       <i class="ti ti-calendar-check"></i>
                                       {{ $kehadiran->tanggal_bayar_lembur->format('d/m/Y') }}
                                    </small>
                                 @else
                                    <small class="text-muted">
                                       <i class="ti ti-calendar"></i> Kamis
                                    </small>
                                 @endif
                              </td>
                              <td class="text-center">
                                 <button type="button" class="btn btn-danger btn-sm" onclick="hapusKehadiran({{ $kehadiran->id }}, '{{ $kehadiran->tukang->nama_tukang }}')">
                                    <i class="ti ti-trash"></i>
                                 </button>
                              </td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="10" class="text-center text-muted">
                                 <i class="ti ti-info-circle"></i> Tidak ada tukang yang lembur hari ini
                              </td>
                           </tr>
                        @endforelse
                     </tbody>
                     @if($kehadirans->count() > 0)
                        <tfoot class="table-light">
                           <tr>
                              <th colspan="6" class="text-end">Total Upah Lembur:</th>
                              <th>
                                 <strong class="text-success">
                                    Rp {{ number_format($kehadirans->sum('upah_lembur'), 0, ',', '.') }}
                                 </strong>
                              </th>
                              <th colspan="2">
                                 <small class="text-muted">
                                    Cash: Rp {{ number_format($kehadirans->where('lembur_dibayar_cash', true)->sum('upah_lembur'), 0, ',', '.') }}
                                    | Kamis: Rp {{ number_format($kehadirans->where('lembur_dibayar_cash', false)->sum('upah_lembur'), 0, ',', '.') }}
                                 </small>
                              </th>
                           </tr>
                        </tfoot>
                     @endif
                  </table>
               </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection

@push('myscript')
<script>
function toggleCash(button) {
   const kehadiranId = button.getAttribute('data-kehadiran-id');
   const row = document.getElementById('row-' + kehadiranId);
   
   // Disable button sementara
   button.disabled = true;
   
   $.ajax({
      url: '{{ route("cash-lembur.toggle") }}',
      method: 'POST',
      data: {
         _token: '{{ csrf_token() }}',
         kehadiran_id: kehadiranId
      },
      success: function(response) {
         if (response.success) {
            // Update button
            if (response.lembur_dibayar_cash) {
               button.className = 'btn btn-sm w-100 btn-success';
               button.innerHTML = '<i class="ti ti-cash"></i> Bayar Hari Ini';
            } else {
               button.className = 'btn btn-sm w-100 btn-secondary';
               button.innerHTML = '<i class="ti ti-calendar"></i> Bayar Kamis';
            }
            
            // Update tanggal bayar
            const tanggalCell = row.querySelector('.tanggal-bayar');
            if (response.lembur_dibayar_cash) {
               tanggalCell.innerHTML = '<small class="text-success"><i class="ti ti-calendar-check"></i> ' + response.tanggal_bayar + '</small>';
            } else {
               tanggalCell.innerHTML = '<small class="text-muted"><i class="ti ti-calendar"></i> Kamis</small>';
            }
            
            // Show notification
            const message = response.lembur_dibayar_cash 
               ? '💰 Lembur akan dibayar CASH hari ini! (Rp ' + response.upah_lembur + ')'
               : '📅 Lembur akan dibayar hari Kamis (Rp ' + response.upah_lembur + ')';
               
            Swal.fire({
               icon: 'success',
               title: message,
               toast: true,
               position: 'top-end',
               showConfirmButton: false,
               timer: 3000
            });
            
            // Reload untuk update total
            setTimeout(() => {
               location.reload();
            }, 1000);
         }
         button.disabled = false;
      },
      error: function(xhr) {
         Swal.fire('Error', xhr.responseJSON?.message || 'Gagal mengupdate pembayaran', 'error');
         button.disabled = false;
      }
   });
}

function hapusKehadiran(kehadiranId, namaTukang) {
   Swal.fire({
      title: 'Hapus Data Lembur?',
      text: 'Hapus data lembur ' + namaTukang + '?',
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'Batal'
   }).then((result) => {
      if (result.isConfirmed) {
         $.ajax({
            url: '{{ route("kehadiran-tukang.destroy", ":id") }}'.replace(':id', kehadiranId),
            method: 'DELETE',
            data: {
               _token: '{{ csrf_token() }}'
            },
            success: function(response) {
               if (response.success) {
                  Swal.fire({
                     icon: 'success',
                     title: 'Berhasil dihapus!',
                     toast: true,
                     position: 'top-end',
                     showConfirmButton: false,
                     timer: 2000
                  });
                  setTimeout(() => {
                     location.reload();
                  }, 1000);
               }
            },
            error: function(xhr) {
               Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menghapus data', 'error');
            }
         });
      }
   });
}
</script>
@endpush
