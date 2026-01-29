@extends('layouts.app')
@section('titlepage', 'Pinjaman Tukang')

@section('navigasi')
   <span class="text-muted fw-light">Keuangan Tukang /</span> Pinjaman
@endsection

@section('content')
<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">💳 Manajemen Pinjaman Tukang</h5>
                  <p class="text-muted mb-0">Kelola pinjaman dan cicilan tukang</p>
               </div>
               <div>
                  <a href="{{ route('keuangan-tukang.pinjaman.download-formulir-kosong') }}" class="btn btn-success btn-sm me-1" target="_blank">
                     <i class="ti ti-file-download me-1"></i> Download Formulir Kosong
                  </a>
                  <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalPinjaman">
                     <i class="ti ti-plus me-1"></i> Tambah Pinjaman
                  </button>
                  <a href="{{ route('kehadiran-tukang.index') }}" class="btn btn-secondary btn-sm">
                     <i class="ti ti-arrow-left me-1"></i> Kembali
                  </a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <!-- Info Alert -->
            <div class="alert alert-info alert-dismissible fade show" role="alert">
               <i class="ti ti-info-circle me-2"></i>
               <strong>Informasi Pinjaman:</strong> 
               <ul class="mb-0 mt-2" style="padding-left: 20px;">
                  <li><strong>Lihat Pinjaman Lama</strong> - Pilih "Semua Status" di filter untuk melihat semua pinjaman (aktif, lunas, dan riwayat lama)</li>
                  <li><strong>Formulir Kosong</strong> (tombol hijau di atas) - Template blanko untuk tukang yang ingin mengajukan pinjaman baru, bisa dicetak dan diisi manual.</li>
                  <li><strong>Formulir Terisi</strong> (tombol hijau di tabel) - Formulir yang sudah terisi dengan data pinjaman untuk dokumentasi.</li>
               </ul>
               <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- New: Info Potongan Terintegrasi -->
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <i class="ti ti-check-circle me-2"></i>
               <strong>⚡ Integrasi Potongan Pinjaman Otomatis:</strong><br>
               <small>
                  Saat Anda mengaktifkan/menonaktifkan toggle <strong>"Auto Potong"</strong> di kolom kanan, sistem akan:<br>
                  ✅ Mengubah status potongan untuk tukang tersebut<br>
                  ✅ Laporan Gaji (Kamis) otomatis terupdate dengan/tanpa potongan pinjaman di nominal kolom "Potongan"<br>
                  ✅ Tukang yang belum TTD akan tetap ditampilkan dengan status <strong>"Belum Dibayarkan"</strong>
               </small>
               <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            
            <!-- Filter -->
            <div class="row mb-3">
               <div class="col-md-4">
                  <label class="form-label small fw-bold">Filter Status Pinjaman</label>
                  <select class="form-select" id="filterStatus" onchange="filterData()">
                     <option value="">📋 Semua Status (Aktif + Lunas + Lama)</option>
                     <option value="aktif" {{ $status == 'aktif' ? 'selected' : '' }}>✅ Aktif (Masih Cicilan)</option>
                     <option value="lunas" {{ $status == 'lunas' ? 'selected' : '' }}>🎉 Lunas (Selesai)</option>
                  </select>
               </div>
               <div class="col-md-4">
                  <label class="form-label small fw-bold">Filter Tukang</label>
                  <select class="form-select" id="filterTukang" onchange="filterData()">
                     <option value="">👥 Semua Tukang</option>
                     @foreach($tukangs as $t)
                        <option value="{{ $t->id }}">{{ $t->kode_tukang }} - {{ $t->nama_tukang }}</option>
                     @endforeach
                  </select>
               </div>
            </div>

            <!-- Tabel -->
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="5%">No</th>
                        <th width="7%">Kode</th>
                        <th width="13%">Nama Tukang</th>
                        <th width="9%">Tanggal</th>
                        <th width="11%">Jumlah Pinjaman</th>
                        <th width="11%">Terbayar</th>
                        <th width="11%">Sisa</th>
                        <th width="9%">Cicilan/Minggu</th>
                        <th width="7%">Foto</th>
                        <th width="7%">Status</th>
                        <th width="10%" class="text-center">Auto Potong</th>
                        <th width="10%">Aksi</th>
                     </tr>
                  </thead>
                  <tbody>
                     @forelse($pinjamans as $index => $p)
                        <tr>
                           <td class="text-center">{{ $index + 1 }}</td>
                           <td>{{ $p->tukang->kode_tukang }}</td>
                           <td>{{ $p->tukang->nama_tukang }}</td>
                           <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjaman)->format('d/m/Y') }}</td>
                           <td class="text-end">Rp {{ number_format($p->jumlah_pinjaman, 0, ',', '.') }}</td>
                           <td class="text-end text-success">Rp {{ number_format($p->jumlah_terbayar, 0, ',', '.') }}</td>
                           <td class="text-end text-danger">Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}</td>
                           <td class="text-end">Rp {{ number_format($p->cicilan_per_minggu, 0, ',', '.') }}</td>
                           <td class="text-center">
                              @if($p->foto_bukti)
                                 <button type="button" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); lihatFoto('{{ asset('storage/' . $p->foto_bukti) }}');">
                                    <i class="ti ti-photo"></i>
                                 </button>
                              @else
                                 <span class="text-muted">-</span>
                              @endif
                           </td>
                           <td class="text-center">
                              @if($p->status == 'aktif')
                                 <span class="badge bg-warning">Aktif</span>
                              @else
                                 <span class="badge bg-success">Lunas</span>
                              @endif
                           </td>
                           <td class="text-center">
                              @if($p->status == 'aktif')
                                 <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           role="switch" 
                                           id="toggle-{{ $p->tukang->id }}" 
                                           {{ $p->tukang->auto_potong_pinjaman ? 'checked' : '' }}
                                           onchange="toggleAutoPotongPinjaman({{ $p->tukang->id }}, '{{ $p->tukang->nama_tukang }}')"
                                           style="cursor: pointer; width: 2.5rem; height: 1.25rem;">
                                 </div>
                                 <small id="badge-toggle-{{ $p->tukang->id }}" class="d-block mt-1">
                                    @if($p->tukang->auto_potong_pinjaman)
                                       <span class="badge bg-success">AKTIF</span>
                                    @else
                                       <span class="badge bg-secondary">NONAKTIF</span>
                                    @endif
                                 </small>
                              @else
                                 <span class="badge bg-light text-muted">-</span>
                              @endif
                           </td>
                           <td class="text-center">
                              @if($p->status == 'aktif')
                                 <button type="button" class="btn btn-sm btn-info" onclick="event.preventDefault(); bayarCicilan({{ $p->id }}, '{{ addslashes($p->tukang->nama_tukang) }}', {{ $p->sisa_pinjaman }}, {{ $p->cicilan_per_minggu }});">
                                    <i class="ti ti-cash"></i>
                                 </button>
                              @endif
                              <button type="button" class="btn btn-sm btn-primary" onclick="event.preventDefault(); detailPinjaman({{ $p->id }});">
                                 <i class="ti ti-eye"></i>
                              </button>
                              <a href="{{ route('keuangan-tukang.pinjaman.download-formulir', $p->id) }}" class="btn btn-sm btn-success" target="_blank" title="Download Formulir">
                                 <i class="ti ti-download"></i>
                              </a>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="12" class="text-center">Tidak ada data pinjaman</td>
                        </tr>
                     @endforelse
                  </tbody>
                  @if($pinjamans->count() > 0)
                     <tfoot class="table-light">
                        <tr>
                           <th colspan="4" class="text-end">Total:</th>
                           <th class="text-end">Rp {{ number_format($pinjamans->sum('jumlah_pinjaman'), 0, ',', '.') }}</th>
                           <th class="text-end text-success">Rp {{ number_format($pinjamans->sum('jumlah_terbayar'), 0, ',', '.') }}</th>
                           <th class="text-end text-danger">Rp {{ number_format($pinjamans->sum('sisa_pinjaman'), 0, ',', '.') }}</th>
                           <th colspan="5"></th>
                        </tr>
                     </tfoot>
                  @endif
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal Lihat Foto -->
<div class="modal fade" id="modalFoto" tabindex="-1">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Foto Bukti Pinjaman</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body text-center">
            <img id="fotoPreview" src="" class="img-fluid" style="max-height: 500px;" alt="Foto Bukti">
         </div>
         <div class="modal-footer">
            <a id="downloadFoto" href="" download class="btn btn-primary">
               <i class="ti ti-download me-1"></i> Download
            </a>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
         </div>
      </div>
   </div>
</div>

<!-- Modal Tambah Pinjaman -->
<div class="modal fade" id="modalPinjaman" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Tambah Pinjaman Baru</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <form action="{{ route('keuangan-tukang.pinjaman.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
               <!-- Info Alert -->
               <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <i class="ti ti-info-circle me-1"></i>
                  <strong>Informasi:</strong> Jika tukang yang dipilih masih memiliki pinjaman aktif, maka jumlah pinjaman baru akan <strong>ditambahkan</strong> ke pinjaman yang sudah ada.
                  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
               </div>
               
               <div class="mb-3">
                  <label class="form-label">Tukang <span class="text-danger">*</span></label>
                  <select name="tukang_id" id="selectTukang" class="form-select" required onchange="cekPinjamanAktif(this.value)">
                     <option value="">Pilih Tukang</option>
                     @foreach($tukangs as $t)
                        @php
                           $pinjamanAktif = $t->pinjamans->where('status', 'aktif')->first();
                        @endphp
                        <option value="{{ $t->id }}" data-pinjaman="{{ $pinjamanAktif ? $pinjamanAktif->sisa_pinjaman : 0 }}">
                           {{ $t->kode_tukang }} - {{ $t->nama_tukang }}
                           @if($pinjamanAktif)
                              (Pinjaman Aktif: Rp {{ number_format($pinjamanAktif->sisa_pinjaman, 0, ',', '.') }})
                           @endif
                        </option>
                     @endforeach
                  </select>
                  <div id="infoPinjamanAktif" class="mt-2" style="display: none;">
                     <div class="alert alert-warning mb-0">
                        <i class="ti ti-alert-triangle me-1"></i>
                        <strong>Perhatian!</strong> Tukang ini memiliki pinjaman aktif sebesar <strong id="jumlahPinjamanAktif">-</strong>. Pinjaman baru akan ditambahkan ke pinjaman yang ada.
                     </div>
                  </div>
               </div>
               <div class="mb-3">
                  <label class="form-label">Tanggal Pinjaman <span class="text-danger">*</span></label>
                  <input type="date" name="tanggal_pinjaman" class="form-control" value="{{ date('Y-m-d') }}" required>
               </div>
               <div class="mb-3">
                  <label class="form-label">Jumlah Pinjaman <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah_pinjaman" class="form-control" placeholder="0" required>
               </div>
               <div class="mb-3">
                  <label class="form-label">Cicilan Per Minggu</label>
                  <input type="number" name="cicilan_per_minggu" class="form-control" placeholder="0">
                  <small class="text-muted">Kosongkan jika tidak ada cicilan tetap</small>
               </div>
               <div class="mb-3">
                  <label class="form-label">Keterangan</label>
                  <textarea name="keterangan" class="form-control" rows="3"></textarea>
               </div>
               <div class="mb-3">
                  <label class="form-label">Foto Bukti</label>
                  <input type="file" name="foto_bukti" class="form-control" accept="image/*">
                  <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
               <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
         </form>
      </div>
   </div>
</div>

<!-- Modal Bayar Cicilan -->
<div class="modal fade" id="modalBayar" tabindex="-1">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Bayar Cicilan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
         </div>
         <form id="formBayar" action="" method="POST">
            @csrf
            <div class="modal-body">
               <div class="mb-3">
                  <label class="form-label">Tukang</label>
                  <input type="text" id="namaTukangBayar" class="form-control" readonly>
               </div>
               <div class="mb-3">
                  <label class="form-label">Sisa Pinjaman</label>
                  <input type="text" id="sisaPinjamanBayar" class="form-control" readonly>
               </div>
               <div class="mb-3">
                  <label class="form-label">Jumlah Bayar <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah_bayar" id="jumlahBayar" class="form-control" required>
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

@push('scripts')
<script>
function filterData() {
   let status = document.getElementById('filterStatus').value;
   let tukang = document.getElementById('filterTukang').value;
   
   let url = '{{ route("keuangan-tukang.pinjaman") }}?';
   if (status) url += 'status=' + status + '&';
   if (tukang) url += 'tukang_id=' + tukang;
   
   window.location.href = url;
}

function lihatFoto(url) {
   console.log('Membuka foto:', url);
   document.getElementById('fotoPreview').src = url;
   document.getElementById('downloadFoto').href = url;
   var myModal = new bootstrap.Modal(document.getElementById('modalFoto'));
   myModal.show();
}

function bayarCicilan(id, nama, sisa, cicilan) {
   console.log('Bayar cicilan:', id, nama, sisa, cicilan);
   document.getElementById('namaTukangBayar').value = nama;
   document.getElementById('sisaPinjamanBayar').value = 'Rp ' + formatNumber(sisa);
   document.getElementById('jumlahBayar').value = cicilan;
   document.getElementById('formBayar').action = '{{ url("keuangan-tukang/pinjaman") }}/' + id + '/bayar';
   
   var myModal = new bootstrap.Modal(document.getElementById('modalBayar'));
   myModal.show();
}

function detailPinjaman(id) {
   console.log('Detail pinjaman:', id);
   window.location.href = '{{ url("keuangan-tukang/pinjaman") }}/' + id;
}

function formatNumber(num) {
   return parseFloat(num).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function cekPinjamanAktif(tukangId) {
   const selectTukang = document.getElementById('selectTukang');
   const selectedOption = selectTukang.options[selectTukang.selectedIndex];
   const pinjamanAktif = parseFloat(selectedOption.getAttribute('data-pinjaman')) || 0;
   
   const infoPinjaman = document.getElementById('infoPinjamanAktif');
   const jumlahPinjaman = document.getElementById('jumlahPinjamanAktif');
   
   if (pinjamanAktif > 0) {
      jumlahPinjaman.textContent = 'Rp ' + formatNumber(pinjamanAktif);
      infoPinjaman.style.display = 'block';
   } else {
      infoPinjaman.style.display = 'none';
   }
}

async function toggleAutoPotongPinjaman(tukangId, namaTukang) {
   const toggle = document.getElementById('toggle-' + tukangId);
   const isChecked = toggle.checked;
   
   // Tampilkan loading indicator
   Swal.fire({
      title: 'Memproses...',
      html: 'Mengubah status auto potong pinjaman...',
      allowOutsideClick: false,
      allowEscapeKey: false,
      didOpen: async () => {
         Swal.showLoading();
         
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
               // Update badge
               const badge = document.getElementById('badge-toggle-' + tukangId);
               if (isChecked) {
                  badge.innerHTML = '<span class="badge bg-success">AKTIF</span>';
               } else {
                  badge.innerHTML = '<span class="badge bg-secondary">NONAKTIF</span>';
               }
               
               // Tampilkan notifikasi sukses
               Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  html: `
                     <div class="text-start">
                        <strong>${namaTukang}</strong><br>
                        Status Auto Potong: <strong>${isChecked ? 'AKTIF ✅' : 'NONAKTIF ❌'}</strong><br><br>
                        ${isChecked ? 
                           '<i class="ti ti-info-circle text-info"></i> <strong>Cicilan akan otomatis dipotong dari gaji setiap minggu.</strong>' : 
                           '<i class="ti ti-alert-triangle text-warning"></i> <strong>Cicilan tidak akan dipotong otomatis dari gaji.</strong>'
                        }<br><br>
                        💡 <small>Perubahan akan terupdate pada laporan gaji berikutnya.</small>
                     </div>
                  `,
                  showConfirmButton: true,
                  confirmButtonText: 'OK',
                  timer: 4000
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
   });
}
</script>
@endpush