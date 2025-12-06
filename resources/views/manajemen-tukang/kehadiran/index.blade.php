@extends('layouts.app')
@section('titlepage', 'Kehadiran Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang /</span> Kehadiran Tukang
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">Absensi Tukang</h5>
                  <p class="text-muted mb-0">{{ $hariNama }}</p>
               </div>
               <div class="d-flex gap-2">
                  @can('kehadiran-tukang.rekap')
                  <a href="{{ route('kehadiran-tukang.rekap') }}" class="btn btn-info btn-sm">
                     <i class="ti ti-file-chart me-1"></i> Lihat Rekap Kehadiran
                  </a>
                  @endcan
                  <form action="{{ route('kehadiran-tukang.index') }}" method="GET" class="d-flex align-items-center">
                     <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}" onchange="this.form.submit()">
                  </form>
               </div>
            </div>
         </div>
         <div class="card-body">
            @if($isJumat)
               <div class="alert alert-info">
                  <i class="ti ti-info-circle me-2"></i>
                  <strong>Hari Jumat (Libur)</strong> - Tidak ada absensi hari ini
               </div>
            @else
               <div class="alert alert-primary">
                  <i class="ti ti-info-circle me-2"></i>
                  <div>
                     <strong>Status Kehadiran:</strong> Klik tombol untuk cycle → <strong>Tidak Hadir → Hadir → Setengah Hari</strong><br>
                     <strong>Lembur:</strong> Klik tombol untuk cycle → <strong>Tidak → Full → Setengah Hari</strong><br>
                     <small class="text-muted">💡 Tukang bisa lembur meskipun tidak hadir (contoh: lembur hari libur)</small>
                  </div>
               </div>

               <div class="alert alert-success alert-dismissible" role="alert">
                  <div class="d-flex align-items-center">
                     <i class="ti ti-wallet ti-lg me-2"></i>
                     <div>
                        <strong>Info Keuangan:</strong> Untuk melihat akumulasi upah, lembur cash, pinjaman, dan potongan, silakan buka menu 
                        <a href="{{ route('keuangan-tukang.index') }}" class="alert-link fw-bold">Keuangan Tukang</a>
                     </div>
                  </div>
                  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
               </div>
               
               <div class="table-responsive">
                  <table class="table table-hover table-bordered">
                     <thead class="table-dark">
                        <tr>
                           <th width="5%">No</th>
                           <th width="10%">Kode</th>
                           <th>Nama Tukang</th>
                           <th width="20%">Status Kehadiran</th>
                           <th width="15%">Lembur</th>
                        </tr>
                     </thead>
                     <tbody>
                        @forelse($tukangs as $index => $tukang)
                           <tr id="row-{{ $tukang->id }}">
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
                              <td class="text-center">
                                 @php
                                    $status = $tukang->kehadiran_hari_ini->status ?? 'tidak_hadir';
                                 @endphp
                                 <button type="button" 
                                    class="btn btn-status btn-sm w-100 status-{{ $status }}" 
                                    data-tukang-id="{{ $tukang->id }}"
                                    data-tanggal="{{ $tanggal }}"
                                    onclick="toggleStatus(this)">
                                    <span class="status-text">
                                       @if($status == 'hadir')
                                          <i class="ti ti-check"></i> Hadir
                                       @elseif($status == 'setengah_hari')
                                          <i class="ti ti-clock"></i> Setengah Hari
                                       @else
                                          <i class="ti ti-x"></i> Tidak Hadir
                                       @endif
                                    </span>
                                 </button>
                              </td>
                              <td class="text-center">
                                 @php
                                    $lembur = $tukang->kehadiran_hari_ini->lembur ?? 'tidak';
                                    $lemburCash = $tukang->kehadiran_hari_ini->lembur_dibayar_cash ?? false;
                                 @endphp
                                 <button type="button" 
                                    class="btn btn-lembur btn-sm w-100 lembur-{{ $lembur }}"
                                    data-tukang-id="{{ $tukang->id }}"
                                    data-tanggal="{{ $tanggal }}"
                                    onclick="toggleLembur(this)">
                                    <span class="lembur-text">
                                       @if($lembur == 'full')
                                          <i class="ti ti-clock-hour-8"></i> Full
                                       @elseif($lembur == 'setengah_hari')
                                          <i class="ti ti-clock-hour-4"></i> 1/2
                                       @else
                                          <i class="ti ti-minus"></i> Tidak
                                       @endif
                                    </span>
                                 </button>
                              </td>
                           </tr>
                        @empty
                           <tr>
                              <td colspan="5" class="text-center">Tidak ada data tukang aktif</td>
                           </tr>
                        @endforelse
                     </tbody>
                  </table>
               </div>
            @endif
         </div>
      </div>
   </div>
</div>
@endsection

@push('myscript')
<style>
.btn-status {
   transition: all 0.3s ease;
}
.status-tidak_hadir {
   background-color: #e0e0e0;
   color: #666;
   border: 1px solid #ccc;
}
.status-hadir {
   background-color: #28a745;
   color: white;
   border: 1px solid #28a745;
}
.status-setengah_hari {
   background-color: #ffc107;
   color: #000;
   border: 1px solid #ffc107;
}
.btn-status:hover {
   opacity: 0.8;
   transform: scale(1.05);
}

/* Style untuk tombol lembur */
.btn-lembur {
   transition: all 0.3s ease;
   font-size: 0.85rem;
}
.lembur-tidak {
   background-color: #e0e0e0;
   color: #666;
   border: 1px solid #ccc;
}
.lembur-full {
   background-color: #dc3545;
   color: white;
   border: 1px solid #dc3545;
}
.lembur-setengah_hari {
   background-color: #fd7e14;
   color: white;
   border: 1px solid #fd7e14;
}
.btn-lembur:hover:not(:disabled) {
   opacity: 0.8;
   transform: scale(1.05);
}
.btn-lembur:disabled {
   opacity: 0.5;
   cursor: not-allowed;
}
</style>

<script>
function toggleStatus(button) {
   const tukangId = button.getAttribute('data-tukang-id');
   const tanggal = button.getAttribute('data-tanggal');
   const row = document.getElementById('row-' + tukangId);
   
   // Disable button sementara
   button.disabled = true;
   
   $.ajax({
      url: '{{ route("kehadiran-tukang.toggle-status") }}',
      method: 'POST',
      data: {
         _token: '{{ csrf_token() }}',
         tukang_id: tukangId,
         tanggal: tanggal
      },
      success: function(response) {
         if (response.success) {
            // Update button class dan text
            button.className = 'btn btn-status btn-sm w-100 status-' + response.status;
            
            let icon = '<i class="ti ti-x"></i>';
            let text = 'Tidak Hadir';
            
            if (response.status == 'hadir') {
               icon = '<i class="ti ti-check"></i>';
               text = 'Hadir';
            } else if (response.status == 'setengah_hari') {
               icon = '<i class="ti ti-clock"></i>';
               text = 'Setengah Hari';
            }
            
            button.querySelector('.status-text').innerHTML = icon + ' ' + text;
            
            // Enable/disable lembur button
            const lemburButton = row.querySelector('.btn-lembur');
            lemburButton.disabled = (response.status == 'tidak_hadir');
            
            if (response.status == 'tidak_hadir') {
               lemburButton.className = 'btn btn-lembur btn-sm w-100 lembur-tidak';
               lemburButton.querySelector('.lembur-text').innerHTML = '<i class="ti ti-minus"></i> Tidak';
            }
         }
         button.disabled = false;
      },
      error: function() {
         Swal.fire('Error', 'Gagal mengupdate status', 'error');
         button.disabled = false;
      }
   });
}

function toggleLembur(button) {
   const tukangId = button.getAttribute('data-tukang-id');
   const tanggal = button.getAttribute('data-tanggal');
   const row = document.getElementById('row-' + tukangId);
   
   // Disable button sementara
   button.disabled = true;
   
   $.ajax({
      url: '{{ route("kehadiran-tukang.toggle-lembur") }}',
      method: 'POST',
      data: {
         _token: '{{ csrf_token() }}',
         tukang_id: tukangId,
         tanggal: tanggal
      },
      success: function(response) {
         if (response.success) {
            // Update button lembur
            let btnClass = 'btn btn-lembur btn-sm w-100 lembur-' + response.lembur;
            let icon = '<i class="ti ti-minus"></i>';
            let text = 'Tidak';
            
            if (response.lembur == 'full') {
               icon = '<i class="ti ti-clock-hour-8"></i>';
               text = 'Full';
            } else if (response.lembur == 'setengah_hari') {
               icon = '<i class="ti ti-clock-hour-4"></i>';
               text = '1/2';
            }
            
            button.className = btnClass;
            button.querySelector('.lembur-text').innerHTML = icon + ' ' + text;
         }
         button.disabled = false;
      },
      error: function(xhr) {
         Swal.fire('Error', xhr.responseJSON?.message || 'Gagal mengupdate lembur', 'error');
         button.disabled = false;
      }
   });
}
</script>
@endpush
