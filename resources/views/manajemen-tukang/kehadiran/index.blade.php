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
                  @if($mode == 'single')
                     <p class="text-muted mb-0">{{ $hariNama }}</p>
                  @else
                     <p class="text-muted mb-0">{{ $periodeText }}</p>
                  @endif
               </div>
               <div class="d-flex gap-2">
                  @can('keuangan-tukang.index')
                  <a href="{{ route('keuangan-tukang.download-laporan-pengajuan-gaji') }}?periode_mulai={{ $periode_mulai }}&periode_akhir={{ $periode_akhir }}" 
                     class="btn btn-warning btn-sm"
                     target="_blank">
                     <i class="ti ti-file-text me-1"></i> Laporan Pengajuan
                  </a>
                  <a href="{{ route('keuangan-tukang.download-laporan-gaji-kamis') }}?periode_mulai={{ $periode_mulai }}&periode_akhir={{ $periode_akhir }}" 
                     class="btn btn-danger btn-sm"
                     target="_blank">
                     <i class="ti ti-file-download me-1"></i> Laporan PDF
                  </a>
                  <a href="{{ route('keuangan-tukang.pembagian-gaji-kamis') }}" class="btn btn-primary btn-sm">
                     <i class="ti ti-writing-sign me-1"></i> Gaji Kamis (TTD)
                  </a>
                  @endcan
                  @can('keuangan-tukang.lembur-cash')
                  <a href="{{ route('keuangan-tukang.lembur-cash') }}" class="btn btn-success btn-sm">
                     <i class="ti ti-cash me-1"></i> Lembur Cash
                  </a>
                  @endcan
                  @can('kehadiran-tukang.rekap')
                  <a href="{{ route('kehadiran-tukang.rekap') }}" class="btn btn-info btn-sm">
                     <i class="ti ti-file-chart me-1"></i> Lihat Rekap Kehadiran
                  </a>
                  @endcan
                  <form action="{{ route('kehadiran-tukang.index') }}" method="GET" class="d-flex align-items-center gap-2">
                     <input type="date" name="tanggal_mulai" class="form-control" placeholder="Dari tanggal" value="{{ $tanggal_mulai ?? '' }}" style="width: 150px;">
                     <span class="text-muted">s/d</span>
                     <input type="date" name="tanggal_akhir" class="form-control" placeholder="Sampai tanggal" value="{{ $tanggal_akhir ?? '' }}" style="width: 150px;">
                     <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ti ti-search"></i> Cari
                     </button>
                     <a href="{{ route('kehadiran-tukang.index') }}" class="btn btn-secondary btn-sm">
                        <i class="ti ti-refresh"></i> Reset
                     </a>
                  </form>
               </div>
            </div>
         </div>
         <div class="card-body">
            @if($mode == 'single' && isset($isJumat) && $isJumat)
               <div class="alert alert-info">
                  <i class="ti ti-info-circle me-2"></i>
                  <strong>Hari Jumat (Libur)</strong> - Tidak ada absensi hari ini
               </div>
            @elseif($mode == 'range')
               <div class="alert alert-primary">
                  <i class="ti ti-info-circle me-2"></i>
                  <strong>Mode Pencarian Range Tanggal</strong> - Menampilkan kehadiran dari {{ Carbon\Carbon::parse($tanggal_mulai)->format('d M Y') }} hingga {{ Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}
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
               
               @if($mode == 'single')
                  <!-- TAMPILAN MODE SINGLE TANGGAL -->
                  <div class="table-responsive">
                     <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                           <tr>
                              <th width="5%">No</th>
                              <th width="8%">Kode</th>
                              <th width="20%">Nama Tukang</th>
                              <th width="15%">Status Kehadiran</th>
                              <th width="12%">Lembur</th>
                              <th width="13%">Upah Harian</th>
                              <th width="13%">Upah Lembur</th>
                              <th width="14%">Total Upah</th>
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
                                 <td class="text-end upah-harian-{{ $tukang->id }}">
                                    @php
                                       $upahHarian = $tukang->kehadiran_hari_ini->upah_harian ?? 0;
                                    @endphp
                                    <strong class="text-success">Rp {{ number_format($upahHarian, 0, ',', '.') }}</strong>
                                 </td>
                                 <td class="text-end upah-lembur-{{ $tukang->id }}">
                                    @php
                                       $upahLembur = $tukang->kehadiran_hari_ini->upah_lembur ?? 0;
                                    @endphp
                                    <strong class="text-primary">Rp {{ number_format($upahLembur, 0, ',', '.') }}</strong>
                                 </td>
                                 <td class="text-end total-upah-{{ $tukang->id }}">
                                    @php
                                       $totalUpah = ($tukang->kehadiran_hari_ini->upah_harian ?? 0) + ($tukang->kehadiran_hari_ini->upah_lembur ?? 0);
                                    @endphp
                                    <strong class="text-info">Rp {{ number_format($totalUpah, 0, ',', '.') }}</strong>
                                 </td>
                              </tr>
                           @empty
                              <tr>
                                 <td colspan="8" class="text-center">Tidak ada data tukang aktif</td>
                              </tr>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               @else
                  <!-- TAMPILAN MODE RANGE TANGGAL -->
                  <div class="table-responsive">
                     <table class="table table-hover table-bordered table-sm">
                        <thead class="table-dark">
                           <tr>
                              <th width="4%">No</th>
                              <th width="6%">Kode</th>
                              <th width="18%">Nama Tukang</th>
                              <th width="8%">Hadir</th>
                              <th width="8%">Setengah</th>
                              <th width="8%">Tidak Hadir</th>
                              <th width="8%">Lembur</th>
                              <th width="16%">Total Upah</th>
                              <th width="14%">Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse($tukangs as $index => $tukang)
                              @php
                                 $hadir = $tukang->kehadiran_list->where('status', 'hadir')->count();
                                 $setengah = $tukang->kehadiran_list->where('status', 'setengah_hari')->count();
                                 $tidakHadir = $tukang->kehadiran_list->where('status', 'tidak_hadir')->count();
                                 $lembur = $tukang->kehadiran_list->whereIn('lembur', ['full', 'setengah_hari'])->count();
                                 $totalUpah = $tukang->kehadiran_list->sum('total_upah');
                              @endphp
                              <tr id="row-{{ $tukang->id }}">
                                 <td>{{ $index + 1 }}</td>
                                 <td><strong>{{ $tukang->kode_tukang }}</strong></td>
                                 <td>
                                    <div class="d-flex align-items-center">
                                       @if($tukang->foto)
                                          <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" 
                                             class="rounded me-2" width="28" height="28" style="object-fit: cover;">
                                       @endif
                                       <span>{{ $tukang->nama_tukang }}</span>
                                    </div>
                                 </td>
                                 <td class="text-center">
                                    <span class="badge bg-success">{{ $hadir }}</span>
                                 </td>
                                 <td class="text-center">
                                    <span class="badge bg-warning">{{ $setengah }}</span>
                                 </td>
                                 <td class="text-center">
                                    <span class="badge bg-danger">{{ $tidakHadir }}</span>
                                 </td>
                                 <td class="text-center">
                                    <span class="badge bg-info">{{ $lembur }}</span>
                                 </td>
                                 <td class="text-end">
                                    <strong class="text-primary">Rp {{ number_format($totalUpah, 0, ',', '.') }}</strong>
                                 </td>
                                 <td class="text-center">
                                    <button type="button" class="btn btn-sm btn-outline-primary" 
                                       onclick="lihatDetailRange({{ $tukang->id }}, '{{ $tukang->nama_tukang }}')">
                                       <i class="ti ti-eye"></i> Lihat
                                    </button>
                                 </td>
                              </tr>
                           @empty
                              <tr>
                                 <td colspan="9" class="text-center">Tidak ada data tukang aktif</td>
                              </tr>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
               @endif
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

/* Animasi untuk perubahan upah */
td[class*="upah-"], td[class*="total-upah"] {
   transition: background-color 1s ease;
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
            
            // UPDATE UPAH REALTIME
            updateUpahDisplay(tukangId, response);
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
            
            // UPDATE UPAH REALTIME
            updateUpahDisplay(tukangId, response);
         }
         button.disabled = false;
      },
      error: function(xhr) {
         Swal.fire('Error', xhr.responseJSON?.message || 'Gagal mengupdate lembur', 'error');
         button.disabled = false;
      }
   });
}

// Fungsi untuk update tampilan upah secara realtime
function updateUpahDisplay(tukangId, response) {
   // Update Upah Harian
   const upahHarianCell = document.querySelector('.upah-harian-' + tukangId);
   if (upahHarianCell && response.upah_harian !== undefined) {
      upahHarianCell.innerHTML = '<strong class="text-success">Rp ' + formatRupiah(response.upah_harian) + '</strong>';
      // Animasi perubahan
      upahHarianCell.style.backgroundColor = '#d4edda';
      setTimeout(() => {
         upahHarianCell.style.backgroundColor = '';
      }, 1000);
   }
   
   // Update Upah Lembur
   const upahLemburCell = document.querySelector('.upah-lembur-' + tukangId);
   if (upahLemburCell && response.upah_lembur !== undefined) {
      upahLemburCell.innerHTML = '<strong class="text-primary">Rp ' + formatRupiah(response.upah_lembur) + '</strong>';
      // Animasi perubahan
      upahLemburCell.style.backgroundColor = '#cfe2ff';
      setTimeout(() => {
         upahLemburCell.style.backgroundColor = '';
      }, 1000);
   }
   
   // Update Total Upah
   const totalUpahCell = document.querySelector('.total-upah-' + tukangId);
   if (totalUpahCell && response.total_upah !== undefined) {
      totalUpahCell.innerHTML = '<strong class="text-info">Rp ' + formatRupiah(response.total_upah) + '</strong>';
      // Animasi perubahan
      totalUpahCell.style.backgroundColor = '#d1ecf1';
      setTimeout(() => {
         totalUpahCell.style.backgroundColor = '';
      }, 1000);
   }
}

// Fungsi helper untuk format rupiah
function formatRupiah(angka) {
   return parseInt(angka).toLocaleString('id-ID');
}

// Fungsi untuk lihat detail kehadiran range tanggal (modal)
function lihatDetailRange(tukangId, namaTukang) {
   Swal.fire({
      title: 'Detail Kehadiran - ' + namaTukang,
      html: 'Loading...',
      didOpen: () => {
         Swal.showLoading();
      }
   });
   
   // Bisa dikembangkan untuk menampilkan detail per hari dalam modal
   // Untuk sekarang, akan menampilkan notifikasi sederhana
   setTimeout(() => {
      Swal.fire({
         title: 'Detail Kehadiran',
         html: '<p>Fitur detail per hari akan segera ditambahkan</p><p><small class="text-muted">Tukang: ' + namaTukang + '</small></p>',
         icon: 'info',
         confirmButtonText: 'OK'
      });
   }, 500);
}
</script>
@endpush
