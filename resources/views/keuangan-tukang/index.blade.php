@extends('layouts.app')
@section('titlepage', 'Keuangan Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang /</span> Keuangan Tukang
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">Dashboard Keuangan Tukang</h5>
                  <p class="text-muted mb-0">
                     <strong>Periode Minggu Ini:</strong> {{ $periode }}
                  </p>
               </div>
               <div class="d-flex gap-2">
                  @can('keuangan-tukang.index')
                  <a href="{{ route('keuangan-tukang.pembagian-gaji-kamis') }}" class="btn btn-primary btn-sm">
                     <i class="ti ti-writing-sign me-1"></i> Gaji Kamis (TTD)
                  </a>
                  @endcan
                  @can('keuangan-tukang.lembur-cash')
                  <a href="{{ route('keuangan-tukang.lembur-cash') }}" class="btn btn-success btn-sm">
                     <i class="ti ti-cash me-1"></i> Lembur Cash
                  </a>
                  @endcan
                  @can('keuangan-tukang.pinjaman')
                  <a href="{{ route('keuangan-tukang.pinjaman') }}" class="btn btn-warning btn-sm">
                     <i class="ti ti-wallet me-1"></i> Pinjaman
                  </a>
                  @endcan
                  @can('keuangan-tukang.laporan')
                  <a href="{{ route('keuangan-tukang.laporan') }}" class="btn btn-info btn-sm">
                     <i class="ti ti-file-chart me-1"></i> Laporan
                  </a>
                  @endcan
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
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th>Nama Tukang</th>
                        <th width="11%" class="text-end">Upah</th>
                        <th width="11%" class="text-end">Lembur</th>
                        <th width="11%" class="text-end">Potongan</th>
                        <th width="11%" class="text-end">Cicilan</th>
                        <th width="11%" class="text-end">Gaji Bersih</th>
                        <th width="8%" class="text-center">Potong Auto</th>
                        <th width="8%" class="text-center">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($tukangs as $index => $tukang)
                        <tr>
                           <td class="text-center">{{ $index + 1 }}</td>
                           <td>{{ $tukang->kode_tukang }}</td>
                           <td>
                              <strong>{{ $tukang->nama_tukang }}</strong><br>
                              <small class="text-muted">{{ $tukang->keahlian }}</small>
                           </td>
                           <td class="text-end text-success">
                              Rp {{ number_format($tukang->total_upah_harian, 0, ',', '.') }}
                           </td>
                           <td class="text-end text-info">
                              Rp {{ number_format($tukang->total_lembur, 0, ',', '.') }}
                           </td>
                           <td class="text-end text-danger">
                              Rp {{ number_format($tukang->total_potongan, 0, ',', '.') }}
                           </td>
                           <td class="text-end text-warning">
                              @if($tukang->pinjaman_aktif > 0 && $tukang->cicilan_mingguan > 0 && $tukang->auto_potong_pinjaman)
                                 Rp {{ number_format($tukang->cicilan_mingguan, 0, ',', '.') }}
                              @else
                                 <span class="text-muted">-</span>
                              @endif
                           </td>
                           <td class="text-end">
                              <strong class="{{ $tukang->total_bersih >= 0 ? 'text-success' : 'text-danger' }}">
                                 Rp {{ number_format($tukang->total_bersih, 0, ',', '.') }}
                              </strong>
                           </td>
                           <td class="text-center">
                              @if($tukang->pinjaman_aktif > 0 && $tukang->cicilan_mingguan > 0)
                                 <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" type="checkbox" role="switch" 
                                           id="switch{{ $tukang->id }}" 
                                           {{ $tukang->auto_potong_pinjaman ? 'checked' : '' }}
                                           onchange="togglePotongan({{ $tukang->id }}, '{{ $tukang->nama_tukang }}')">
                                 </div>
                              @else
                                 <span class="text-muted">-</span>
                              @endif
                           </td>
                           <td class="text-center">
                              <div class="dropdown">
                                 <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="dropdownAksi{{ $tukang->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ti ti-menu-2"></i> Aksi
                                 </button>
                                 <ul class="dropdown-menu" aria-labelledby="dropdownAksi{{ $tukang->id }}">
                                    <li>
                                       <a class="dropdown-item" href="{{ route('keuangan-tukang.detail', $tukang->id) }}">
                                          <i class="ti ti-eye me-1"></i> Detail Transaksi
                                       </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                       <a class="dropdown-item text-success" href="javascript:void(0)" onclick="bayarGaji({{ $tukang->id }}, '{{ $tukang->nama_tukang }}', {{ $tukang->total_bersih }})">
                                          <i class="ti ti-cash me-1"></i> Bayar Gaji Minggu Ini
                                       </a>
                                    </li>
                                    <li>
                                       <a class="dropdown-item text-info" href="javascript:void(0)" onclick="lihatStatus({{ $tukang->id }})">
                                          <i class="ti ti-list-check me-1"></i> Lihat Status Pembayaran
                                       </a>
                                    </li>
                                    <li>
                                       <a class="dropdown-item text-warning" href="{{ route('keuangan-tukang.download-slip', $tukang->id) }}" target="_blank">
                                          <i class="ti ti-download me-1"></i> Download Slip Gaji
                                       </a>
                                    </li>
                                 </ul>
                              </div>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="10" class="text-center">Tidak ada data tukang aktif</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($tukangs->count() > 0)
                     <tfoot class="table-secondary">
                        <tr>
                           <th colspan="3" class="text-end">TOTAL:</th>
                           <th class="text-end text-success">
                              Rp {{ number_format($tukangs->sum('total_upah_harian'), 0, ',', '.') }}
                           </th>
                           <th class="text-end text-info">
                              Rp {{ number_format($tukangs->sum('total_lembur'), 0, ',', '.') }}
                           </th>
                           <th class="text-end text-danger">
                              Rp {{ number_format($tukangs->sum('total_potongan'), 0, ',', '.') }}
                           </th>
                           <th class="text-end text-warning">
                              Rp {{ number_format($tukangs->sum('cicilan_mingguan'), 0, ',', '.') }}
                           </th>
                           <th class="text-end">
                              <strong class="text-primary">
                                 Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}
                              </strong>
                           </th>
                           <th></th>
                           <th></th>
                        </tr>
                     </tfoot>
                  @endif
               </table>
            </div>

            <div class="row mt-3">
               <div class="col-md-3">
                  <div class="card bg-success-subtle">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                           <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-success">
                                 <i class="ti ti-cash ti-md"></i>
                              </span>
                           </div>
                           <div>
                              <small class="text-muted d-block">Total Upah Harian</small>
                              <h5 class="mb-0">Rp {{ number_format($tukangs->sum('total_upah_harian'), 0, ',', '.') }}</h5>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-info-subtle">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                           <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-info">
                                 <i class="ti ti-clock ti-md"></i>
                              </span>
                           </div>
                           <div>
                              <small class="text-muted d-block">Total Lembur</small>
                              <h5 class="mb-0">Rp {{ number_format($tukangs->sum('total_lembur'), 0, ',', '.') }}</h5>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-danger-subtle">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                           <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-danger">
                                 <i class="ti ti-cut ti-md"></i>
                              </span>
                           </div>
                           <div>
                              <small class="text-muted d-block">Total Potongan</small>
                              <h5 class="mb-0">Rp {{ number_format($tukangs->sum('total_potongan'), 0, ',', '.') }}</h5>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
               <div class="col-md-3">
                  <div class="card bg-primary-subtle">
                     <div class="card-body">
                        <div class="d-flex align-items-center">
                           <div class="avatar flex-shrink-0 me-3">
                              <span class="avatar-initial rounded bg-label-primary">
                                 <i class="ti ti-wallet ti-md"></i>
                              </span>
                           </div>
                           <div>
                              <small class="text-muted d-block">Total Bersih</small>
                              <h5 class="mb-0 text-primary">Rp {{ number_format($tukangs->sum('total_bersih'), 0, ',', '.') }}</h5>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>

            <div class="alert alert-info mt-3">
               <i class="ti ti-info-circle me-2"></i>
               <strong>Info:</strong>
               <ul class="mb-0 mt-2">
                  <li><strong>Upah Harian:</strong> Total upah kehadiran bulan ini</li>
                  <li><strong>Lembur:</strong> Total lembur (full + setengah + cash) bulan ini</li>
                  <li><strong>Potongan:</strong> Total potongan gaji bulan ini</li>
                  <li><strong>Pinjaman Aktif:</strong> Sisa pinjaman yang belum lunas (keseluruhan, bukan bulan ini)</li>
                  <li><strong>Gaji Bersih:</strong> Upah + Lembur - Potongan</li>
                  <li><strong>Potong Auto:</strong> Toggle saklar untuk mengaktifkan/menonaktifkan potongan otomatis pinjaman dari gaji mingguan</li>
               </ul>
            </div>
         </div>
      </div>
   </div>
</div>

@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function togglePotongan(tukangId, namaTukang) {
   const checkbox = document.getElementById('switch' + tukangId);
   const status = checkbox.checked ? 'AKTIF' : 'NONAKTIF';
   const statusColor = checkbox.checked ? 'success' : 'warning';
   
   Swal.fire({
      title: 'Konfirmasi Perubahan',
      html: `
         <div style="text-align: left;">
            <p><strong>Tukang:</strong> ${namaTukang}</p>
            <p><strong>Status Baru:</strong> <span style="color: ${checkbox.checked ? '#28a745' : '#ffc107'}; font-weight: bold;">${status}</span></p>
            <hr>
            <p style="font-size: 0.9em; color: #666;">
               ${checkbox.checked 
                  ? '✅ Potongan pinjaman akan <strong>otomatis dipotong</strong> dari gaji mingguan' 
                  : '⚠️ Potongan pinjaman akan <strong>dinonaktifkan</strong>, tukang menerima gaji penuh'}
            </p>
         </div>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: checkbox.checked ? '#28a745' : '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Ubah Status!',
      cancelButtonText: 'Batal',
      reverseButtons: true
   }).then((result) => {
      if (result.isConfirmed) {
         Swal.fire({
            title: 'Memproses...',
            html: 'Sedang mengubah status potongan',
            allowOutsideClick: false,
            didOpen: () => {
               Swal.showLoading();
            }
         });
         
         fetch(`{{ url('keuangan-tukang') }}/toggle-potongan-pinjaman/${tukangId}`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
         })
         .then(response => response.json())
         .then(data => {
            if (data.success) {
               Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  html: data.message,
                  confirmButtonColor: '#3085d6',
                  timer: 2000,
                  timerProgressBar: true
               }).then(() => {
                  window.location.reload();
               });
            } else {
               Swal.fire({
                  icon: 'error',
                  title: 'Gagal!',
                  text: data.message,
                  confirmButtonColor: '#d33'
               });
               checkbox.checked = !checkbox.checked;
            }
         })
         .catch(error => {
            console.error('Error:', error);
            Swal.fire({
               icon: 'error',
               title: 'Error!',
               text: 'Terjadi kesalahan saat mengubah status',
               confirmButtonColor: '#d33'
            });
            checkbox.checked = !checkbox.checked;
         });
      } else {
         checkbox.checked = !checkbox.checked;
      }
   });
}

// Fungsi bayar gaji minggu ini - langsung redirect ke form TTD
function bayarGaji(tukangId, namaTukang, totalBersih) {
   // Langsung redirect ke halaman TTD
   window.location.href = `{{ url('keuangan-tukang/pembagian-gaji-kamis') }}?tukang_id=${tukangId}`;
}

// Fungsi lihat status pembayaran
function lihatStatus(tukangId) {
   Swal.fire({
      title: 'Memuat Data...',
      html: 'Mengambil status pembayaran',
      allowOutsideClick: false,
      didOpen: () => {
         Swal.showLoading();
      }
   });
   
   fetch(`{{ url('keuangan-tukang') }}/status-pembayaran/${tukangId}?periode={{ $sabtu->format('Y-m-d') }}|{{ $kamis->format('Y-m-d') }}`)
      .then(response => response.json())
      .then(data => {
         if (data.success) {
            let htmlContent = `
               <div style="text-align: left;">
                  <p><strong>Tukang:</strong> ${data.nama_tukang}</p>
                  <p><strong>Periode:</strong> {{ $periode }}</p>
                  <hr>
                  <h6>Riwayat Pembayaran:</h6>
            `;
            
            if (data.pembayaran.length > 0) {
               htmlContent += '<ul style="list-style: none; padding: 0;">';
               data.pembayaran.forEach(p => {
                  htmlContent += `
                     <li style="padding: 8px; margin: 5px 0; background: #f8f9fa; border-radius: 5px;">
                        <strong>${p.tanggal}</strong><br>
                        <span style="color: #28a745; font-weight: bold;">Rp ${new Intl.NumberFormat('id-ID').format(p.jumlah)}</span>
                        <span class="badge bg-${p.status === 'Lunas' ? 'success' : 'warning'} ms-2">${p.status}</span>
                     </li>
                  `;
               });
               htmlContent += '</ul>';
            } else {
               htmlContent += '<p class="text-muted text-center">Belum ada pembayaran minggu ini</p>';
            }
            
            htmlContent += '</div>';
            
            Swal.fire({
               title: 'Status Pembayaran',
               html: htmlContent,
               icon: 'info',
               confirmButtonText: 'Tutup',
               width: 600
            });
         } else {
            Swal.fire({
               icon: 'error',
               title: 'Gagal!',
               text: data.message
            });
         }
      })
      .catch(error => {
         Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Terjadi kesalahan saat mengambil data'
         });
      });
}
</script>

