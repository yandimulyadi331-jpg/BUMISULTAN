@extends('layouts.app')
@section('titlepage', 'Detail Pinjaman')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Keuangan Tukang / Pinjaman /</span> Detail
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">💳 Detail Pinjaman</h5>
                  <p class="text-muted mb-0">{{ $pinjaman->tukang->nama_tukang }} ({{ $pinjaman->tukang->kode_tukang }})</p>
               </div>
               <div>
                  <a href="{{ route('keuangan-tukang.pinjaman.download-formulir', $pinjaman->id) }}" class="btn btn-success btn-sm me-2" target="_blank">
                     <i class="ti ti-download me-1"></i> Download Formulir
                  </a>
                  <a href="{{ route('keuangan-tukang.pinjaman') }}" class="btn btn-secondary btn-sm">
                     <i class="ti ti-arrow-left me-1"></i> Kembali
                  </a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <!-- Info Pinjaman -->
            <div class="row mb-4">
               <div class="col-md-6">
                  <div class="card bg-light">
                     <div class="card-body">
                        <h6 class="card-title mb-3">Informasi Pinjaman</h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td width="150">Tanggal Pinjaman</td>
                              <td>: {{ \Carbon\Carbon::parse($pinjaman->tanggal_pinjaman)->format('d M Y') }}</td>
                           </tr>
                           <tr>
                              <td>Jumlah Pinjaman</td>
                              <td>: <strong>Rp {{ number_format($pinjaman->jumlah_pinjaman, 0, ',', '.') }}</strong></td>
                           </tr>
                           <tr>
                              <td>Cicilan/Minggu</td>
                              <td>: Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</td>
                           </tr>
                           <tr>
                              <td>Status</td>
                              <td>: 
                                 @if($pinjaman->status == 'aktif')
                                    <span class="badge bg-warning">Aktif</span>
                                 @else
                                    <span class="badge bg-success">Lunas</span>
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td>Dicatat Oleh</td>
                              <td>: {{ $pinjaman->dicatat_oleh }}</td>
                           </tr>
                        </table>
                     </div>
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="card bg-light">
                     <div class="card-body">
                        <h6 class="card-title mb-3">Status Pembayaran</h6>
                        <table class="table table-sm table-borderless mb-0">
                           <tr>
                              <td width="150">Terbayar</td>
                              <td>: <span class="text-success fw-bold">Rp {{ number_format($pinjaman->jumlah_terbayar, 0, ',', '.') }}</span></td>
                           </tr>
                           <tr>
                              <td>Sisa</td>
                              <td>: <span class="text-danger fw-bold">Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}</span></td>
                           </tr>
                           <tr>
                              <td>Progress</td>
                              <td>: 
                                 <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                       style="width: {{ $pinjaman->jumlah_pinjaman > 0 ? ($pinjaman->jumlah_terbayar / $pinjaman->jumlah_pinjaman * 100) : 0 }}%">
                                       {{ $pinjaman->jumlah_pinjaman > 0 ? number_format($pinjaman->jumlah_terbayar / $pinjaman->jumlah_pinjaman * 100, 1) : 0 }}%
                                    </div>
                                 </div>
                              </td>
                           </tr>
                           @if($pinjaman->status == 'lunas')
                           <tr>
                              <td>Tanggal Lunas</td>
                              <td>: {{ \Carbon\Carbon::parse($pinjaman->tanggal_lunas)->format('d M Y') }}</td>
                           </tr>
                           @endif
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <!-- Status Auto Potong -->
            @if($pinjaman->status == 'aktif')
            <div class="row mb-4">
               <div class="col-12">
                  <div class="card border-{{ $pinjaman->tukang->auto_potong_pinjaman ? 'success' : 'secondary' }}">
                     <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                           <div>
                              <h6 class="card-title mb-2">
                                 <i class="ti ti-settings me-2"></i>Status Potongan Otomatis
                              </h6>
                              <p class="mb-0">
                                 @if($pinjaman->tukang->auto_potong_pinjaman)
                                    <span class="badge bg-success" style="font-size: 14px; padding: 8px 16px;">
                                       <i class="ti ti-check"></i> AKTIF - Cicilan akan dipotong otomatis dari gaji
                                    </span>
                                 @else
                                    <span class="badge bg-secondary" style="font-size: 14px; padding: 8px 16px;">
                                       <i class="ti ti-x"></i> NONAKTIF - Cicilan tidak dipotong otomatis
                                    </span>
                                 @endif
                              </p>
                           </div>
                           <div>
                              <div class="form-check form-switch">
                                 <input class="form-check-input" 
                                        type="checkbox" 
                                        role="switch" 
                                        id="toggleAutoPotong" 
                                        {{ $pinjaman->tukang->auto_potong_pinjaman ? 'checked' : '' }}
                                        onchange="toggleAutoPotong()"
                                        style="cursor: pointer; width: 3rem; height: 1.5rem;">
                                 <label class="form-check-label" for="toggleAutoPotong">
                                    <strong>Toggle Auto Potong</strong>
                                 </label>
                              </div>
                           </div>
                        </div>
                        <hr>
                        <small class="text-muted">
                           <i class="ti ti-info-circle me-1"></i>
                           Jika diaktifkan, cicilan <strong>Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ',', '.') }}</strong> 
                           akan otomatis dipotong dari gaji {{ $pinjaman->tukang->nama_tukang }} setiap minggu.
                        </small>
                     </div>
                  </div>
               </div>
            </div>
            @endif

            <!-- Foto Bukti -->
            @if($pinjaman->foto_bukti)
            <div class="row mb-4">
               <div class="col-12">
                  <div class="card">
                     <div class="card-body">
                        <h6 class="card-title mb-3">Foto Bukti Pinjaman</h6>
                        <div class="text-center">
                           <img src="{{ asset('storage/' . $pinjaman->foto_bukti) }}" 
                                class="img-fluid rounded border" 
                                style="max-height: 400px; cursor: pointer;"
                                onclick="window.open(this.src, '_blank')"
                                alt="Foto Bukti">
                           <p class="text-muted mt-2">
                              <small>Klik gambar untuk memperbesar</small>
                           </p>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            @endif

            <!-- Keterangan -->
            @if($pinjaman->keterangan)
            <div class="alert alert-info">
               <strong>Keterangan:</strong><br>
               {{ $pinjaman->keterangan }}
            </div>
            @endif

            <!-- Riwayat Pembayaran -->
            <div class="card">
               <div class="card-header">
                  <h6 class="mb-0">
                     <i class="ti ti-history me-2"></i>Riwayat Pembayaran Cicilan
                  </h6>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table class="table table-hover table-bordered">
                        <thead class="table-dark">
                           <tr>
                              <th width="5%">No</th>
                              <th width="15%">Tanggal</th>
                              <th width="20%">Jumlah Bayar</th>
                              <th width="20%">Sisa Setelah Bayar</th>
                              <th>Keterangan</th>
                              <th width="15%">Dicatat Oleh</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse($riwayatBayar as $index => $bayar)
                              <tr>
                                 <td class="text-center">{{ $index + 1 }}</td>
                                 <td>{{ \Carbon\Carbon::parse($bayar->tanggal)->format('d/m/Y H:i') }}</td>
                                 <td class="text-end text-success fw-bold">
                                    Rp {{ number_format($bayar->jumlah, 0, ',', '.') }}
                                    @if(stripos($bayar->keterangan, 'otomatis') !== false || stripos($bayar->keterangan, 'auto') !== false)
                                       <br><span class="badge bg-info" style="font-size: 10px;"><i class="ti ti-robot"></i> AUTO</span>
                                    @endif
                                 </td>
                                 <td class="text-end text-{{ $bayar->saldo > 0 ? 'danger' : 'success' }}">
                                    Rp {{ number_format($bayar->saldo ?? 0, 0, ',', '.') }}
                                    @if($bayar->saldo == 0)
                                       <br><span class="badge bg-success" style="font-size: 10px;"><i class="ti ti-check"></i> LUNAS</span>
                                    @endif
                                 </td>
                                 <td>
                                    {{ $bayar->keterangan }}
                                    @if(stripos($bayar->keterangan, 'potongan gaji') !== false)
                                       <br><small class="text-muted"><i class="ti ti-calendar"></i> Dipotong dari gaji mingguan</small>
                                    @endif
                                 </td>
                                 <td>{{ $bayar->dicatat_oleh }}</td>
                              </tr>
                           @empty
                              <tr>
                                 <td colspan="6" class="text-center">Belum ada pembayaran</td>
                              </tr>
                           @endforelse
                        </tbody>
                        @if($riwayatBayar->count() > 0)
                           <tfoot class="table-light">
                              <tr>
                                 <th colspan="2" class="text-end">Total Terbayar:</th>
                                 <th class="text-end text-success">Rp {{ number_format($riwayatBayar->sum('jumlah'), 0, ',', '.') }}</th>
                                 <th colspan="3"></th>
                              </tr>
                           </tfoot>
                        @endif
                     </table>
                  </div>
               </div>
            </div>

            <!-- Tombol Aksi -->
            @if($pinjaman->status == 'aktif')
            <div class="mt-3 text-end">
               <button type="button" class="btn btn-primary" onclick="bayarCicilan()">
                  <i class="ti ti-cash me-1"></i> Bayar Cicilan
               </button>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>

<!-- Modal Bayar -->
<div class="modal fade" id="modalBayar" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Bayar Cicilan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <form action="{{ route('keuangan-tukang.pinjaman.bayar', $pinjaman->id) }}" method="POST">
            @csrf
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Sisa Pinjaman</label>
                  <input type="text" class="form-control" value="Rp {{ number_format($pinjaman->sisa_pinjaman, 0, ',', '.') }}" readonly>
               </div>
               <div class="mb-3">
                  <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah_bayar" class="form-control" value="{{ $pinjaman->cicilan_per_minggu }}" required>
               </div>
               <div class="mb-3">
                  <label class="form-label">Tanggal Bayar</label>
                  <input type="date" name="tanggal_bayar" class="form-control" value="{{ date('Y-m-d') }}">
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Bayar</button>
            </div>
         </form>
      </div>
   </div>
</div>
@endsection

@push('styles')
<style>
   .form-check-input {
      width: 3rem;
      height: 1.5rem;
   }
</style>
@endpush

<script>
function bayarCicilan() {
   var myModal = new bootstrap.Modal(document.getElementById('modalBayar'));
   myModal.show();
}

async function toggleAutoPotong() {
   const toggle = document.getElementById('toggleAutoPotong');
   const isChecked = toggle.checked;
   const tukangId = {{ $pinjaman->tukang->id }};
   
   try {
      const response = await fetch(`{{ url('keuangan-tukang/toggle-potongan-pinjaman') }}/${tukangId}`, {
         method: 'POST',
         headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
         }
      });
      
      const data = await response.json();
      
      if (data.success) {
         // Reload halaman untuk update tampilan
         Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            html: `
               <div class="text-start">
                  <strong>{{ $pinjaman->tukang->nama_tukang }}</strong><br>
                  Status Auto Potong: <strong>${isChecked ? 'AKTIF' : 'NONAKTIF'}</strong><br><br>
                  ${isChecked ? 
                     '<i class="ti ti-info-circle text-info"></i> Cicilan <strong>Rp {{ number_format($pinjaman->cicilan_per_minggu, 0, ",", ".") }}</strong> akan otomatis dipotong dari gaji setiap minggu.' : 
                     '<i class="ti ti-alert-triangle text-warning"></i> Cicilan tidak akan dipotong otomatis dari gaji.'
                  }
               </div>
            `,
            showConfirmButton: true,
            timer: 3000
         }).then(() => {
            location.reload();
         });
      } else {
         throw new Error(data.message || 'Gagal mengubah status');
      }
   } catch (error) {
      console.error('Error:', error);
      toggle.checked = !isChecked; // Kembalikan ke posisi semula
      
      Swal.fire({
         icon: 'error',
         title: 'Gagal!',
         text: error.message || 'Terjadi kesalahan saat mengubah status auto potong',
         showConfirmButton: true
      });
   }
}
</script>
