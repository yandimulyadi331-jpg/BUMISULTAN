@extends('layouts.app')
@section('titlepage', 'Pembagian Gaji Kamis')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Keuangan Tukang /</span> Pembagian Gaji Kamis
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
               <div>
                  <h5 class="mb-0">💰 Pembagian Gaji Kamis (TTD Digital)</h5>
                  <p class="text-muted mb-0">Periode: {{ $periodeText }}</p>
               </div>
               <div>
                  <a href="{{ route('kehadiran-tukang.index') }}" class="btn btn-secondary btn-sm">
                     <i class="ti ti-arrow-left me-1"></i> Kembali
                  </a>
               </div>
            </div>
         </div>
         <div class="card-body">
            <div class="alert alert-info">
               <i class="ti ti-info-circle me-2"></i>
               <strong>Cara Kerja:</strong><br>
               1. Klik tombol "Bayar Gaji" pada tukang yang akan menerima gaji<br>
               2. Tukang membubuhkan tanda tangan digital di canvas<br>
               3. Klik "Simpan & Bayar" untuk menyelesaikan pembayaran<br>
               4. Slip gaji dengan TTD akan tersimpan otomatis
            </div>
            
            <div class="table-responsive">
               <table class="table table-hover table-bordered">
                  <thead class="table-dark">
                     <tr>
                        <th width="5%">No</th>
                        <th width="10%">Kode</th>
                        <th width="50%">Nama Tukang</th>
                        <th width="15%" class="text-center">Status</th>
                        <th width="20%" class="text-center">Aksi</th>
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
                                       class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                                 @endif
                                 <strong>{{ $tukang->nama_tukang }}</strong>
                              </div>
                           </td>
                           <td class="text-center">
                              @if(is_object($tukang->pembayaran) && $tukang->pembayaran->status == 'lunas')
                                 <span class="badge bg-success" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="ti ti-check"></i> Lunas
                                 </span>
                              @else
                                 <span class="badge bg-warning" style="font-size: 14px; padding: 8px 12px;">
                                    <i class="ti ti-clock"></i> Belum Bayar
                                 </span>
                              @endif
                           </td>
                           <td class="text-center">
                              @if(is_object($tukang->pembayaran) && $tukang->pembayaran->status == 'lunas')
                                 <button class="btn btn-info" onclick="lihatSlip({{ $tukang->pembayaran->id }})">
                                    <i class="ti ti-file me-1"></i> Lihat Slip
                                 </button>
                              @else
                                 <button class="btn btn-primary" 
                                    onclick="bayarGaji({{ $tukang->id }}, '{{ $tukang->nama_tukang }}')">
                                    <i class="ti ti-cash me-1"></i> Bayar Gaji
                                 </button>
                              @endif
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="5" class="text-center">Tidak ada data tukang</td>
                        </tr>
                     @endforelse
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </div>
</div>

<!-- Modal TTD Digital -->
<div class="modal fade" id="modalTTD" tabindex="-1" aria-hidden="true">
   <div class="modal-dialog modal-lg">
      <div class="modal-content">
         <div class="modal-header bg-primary text-white">
            <h5 class="modal-title">
               <i class="ti ti-writing-sign me-2"></i>
               Tanda Tangan Digital - <span id="modal-nama-tukang"></span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
         </div>
         <div class="modal-body">
            <!-- Detail Slip Gaji -->
            <div class="card border mb-3">
               <div class="card-body">
                  <h6 class="text-center mb-3">SLIP GAJI TUKANG</h6>
                  <div class="row">
                     <div class="col-6">
                        <p class="mb-1"><strong>Kode Tukang:</strong> <span id="detail-kode"></span></p>
                        <p class="mb-1"><strong>Nama:</strong> <span id="detail-nama"></span></p>
                        <p class="mb-1"><strong>Periode:</strong> <span id="detail-periode"></span></p>
                     </div>
                     <div class="col-6">
                        <p class="mb-1"><strong>Tanggal Bayar:</strong> {{ date('d M Y') }}</p>
                        <p class="mb-1"><strong>Dibayar oleh:</strong> {{ Auth::user()->name }}</p>
                     </div>
                  </div>
                  <hr>
                  <table class="table table-sm table-borderless mb-0">
                     <tr>
                        <td>Upah Harian</td>
                        <td class="text-end" id="detail-upah-harian">Rp 0</td>
                     </tr>
                     <tr>
                        <td>Upah Lembur</td>
                        <td class="text-end" id="detail-upah-lembur">Rp 0</td>
                     </tr>
                     <tr>
                        <td><small class="text-muted">Lembur Cash (Terbayar)</small></td>
                        <td class="text-end text-muted" id="detail-lembur-cash">- Rp 0</td>
                     </tr>
                     <tr class="border-top">
                        <td><strong>Total Kotor</strong></td>
                        <td class="text-end"><strong id="detail-total-kotor">Rp 0</strong></td>
                     </tr>
                     <tr class="border-top">
                        <td colspan="2"><strong>Potongan:</strong></td>
                     </tr>
                     <tbody id="detail-potongan-list">
                        <!-- Potongan akan diisi via JS -->
                     </tbody>
                     <tr class="border-top">
                        <td><strong class="text-danger">Total Potongan</strong></td>
                        <td class="text-end"><strong class="text-danger" id="detail-total-potongan">Rp 0</strong></td>
                     </tr>
                     <tr class="border-top bg-light">
                        <td><h5 class="mb-0 text-primary">TOTAL NETT</h5></td>
                        <td class="text-end"><h5 class="mb-0 text-primary" id="detail-total-nett">Rp 0</h5></td>
                     </tr>
                  </table>
               </div>
            </div>
            
            <!-- Canvas TTD -->
            <div class="text-center mb-3">
               <label class="form-label"><strong>Tanda Tangan Tukang:</strong></label>
               <div class="border rounded" style="background: #f8f9fa;">
                  <canvas id="signature-pad" width="700" height="200" style="cursor: crosshair; touch-action: none;"></canvas>
               </div>
               <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="clearSignature()">
                  <i class="ti ti-eraser"></i> Hapus TTD
               </button>
            </div>
            
            <div class="alert alert-warning">
               <i class="ti ti-alert-triangle me-2"></i>
               <strong>Perhatian:</strong> Pastikan tukang sudah membubuhkan tanda tangan sebelum menyimpan!
            </div>
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
               <i class="ti ti-x"></i> Batal
            </button>
            <button type="button" class="btn btn-success" onclick="simpanPembayaran()">
               <i class="ti ti-check"></i> Simpan & Bayar
            </button>
         </div>
      </div>
   </div>
</div>
@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
<script>
let signaturePad;
let currentTukangId;
let currentDetailGaji;

// ✅ FUNGSI TOGGLE AUTO POTONG REAL-TIME
function toggleAutoPotong(tukangId, namaTukang) {
   const checkbox = document.getElementById('toggle-' + tukangId);
   const status = checkbox.checked ? 'AKTIF' : 'NONAKTIF';
   
   Swal.fire({
      title: 'Konfirmasi Perubahan',
      html: `
         <div style="text-align: left;">
            <p><strong>Tukang:</strong> ${namaTukang}</p>
            <p><strong>Status Auto Potong:</strong> <span style="color: ${checkbox.checked ? '#28a745' : '#ffc107'}; font-weight: bold;">${status}</span></p>
            <hr>
            <p style="font-size: 0.9em; color: #666;">
               ${checkbox.checked 
                  ? '✅ Cicilan pinjaman akan <strong>otomatis dipotong</strong> dari gaji' 
                  : '⚠️ Cicilan pinjaman <strong>TIDAK akan dipotong</strong>, gaji diterima utuh'}
            </p>
         </div>
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: checkbox.checked ? '#28a745' : '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Ubah!',
      cancelButtonText: 'Batal',
      reverseButtons: true
   }).then((result) => {
      if (result.isConfirmed) {
         Swal.fire({
            title: 'Memproses...',
            html: 'Menghitung ulang potongan dan gaji bersih...',
            allowOutsideClick: false,
            didOpen: () => {
               Swal.showLoading();
            }
         });
         
         // AJAX request
         fetch(`{{ url('keuangan-tukang') }}/toggle-potongan-pinjaman/${tukangId}?periode={{ $periodeMulai->format('Y-m-d') }}|{{ $periodeAkhir->format('Y-m-d') }}`, {
            method: 'POST',
            headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
         })
         .then(response => response.json())
         .then(data => {
            if (data.success) {
               // ✅ UPDATE UI REAL-TIME
               if (data.data && data.data.total_bersih !== undefined) {
                  // Update kolom POTONGAN
                  const potonganCell = document.getElementById('potongan-' + tukangId);
                  if (potonganCell) {
                     const totalPotongan = data.data.potongan + data.data.cicilan;
                     potonganCell.innerHTML = 'Rp ' + new Intl.NumberFormat('id-ID').format(totalPotongan);
                     potonganCell.className = 'text-danger';
                     
                     // Animasi highlight
                     potonganCell.style.backgroundColor = '#fff3cd';
                     potonganCell.style.transition = 'background-color 0.5s ease';
                     setTimeout(() => {
                        potonganCell.style.backgroundColor = '';
                     }, 2000);
                  }
                  
                  // Update kolom TOTAL NETT
                  const totalNettCell = document.getElementById('total-nett-' + tukangId);
                  if (totalNettCell) {
                     totalNettCell.innerHTML = '<strong class="text-primary">Rp ' + new Intl.NumberFormat('id-ID').format(data.data.total_bersih) + '</strong>';
                     
                     // Animasi highlight
                     totalNettCell.style.backgroundColor = '#d4edda';
                     totalNettCell.style.transition = 'background-color 0.5s ease';
                     setTimeout(() => {
                        totalNettCell.style.backgroundColor = '';
                     }, 2000);
                  }
                  
                  // Update badge status
                  const badgeCell = document.getElementById('badge-toggle-' + tukangId);
                  if (badgeCell) {
                     badgeCell.innerHTML = data.status ? 
                        '<span class="badge bg-success">AKTIF</span>' :
                        '<span class="badge bg-secondary">NONAKTIF</span>';
                  }
               }
               
               // Success notification
               Swal.fire({
                  icon: 'success',
                  title: 'Berhasil!',
                  html: `
                     <div style="text-align: left;">
                        <p>${data.message}</p>
                        ${data.data && data.data.total_bersih !== undefined ? `
                           <hr>
                           <p class="mb-1"><strong>Perhitungan Baru:</strong></p>
                           <table style="width: 100%; font-size: 0.9em;">
                              <tr>
                                 <td>Upah + Lembur:</td>
                                 <td class="text-end">Rp ${new Intl.NumberFormat('id-ID').format(data.data.upah_harian + data.data.lembur)}</td>
                              </tr>
                              <tr>
                                 <td>Potongan Lain:</td>
                                 <td class="text-end text-danger">-Rp ${new Intl.NumberFormat('id-ID').format(data.data.potongan)}</td>
                              </tr>
                              <tr>
                                 <td>Cicilan Pinjaman:</td>
                                 <td class="text-end text-danger">-Rp ${new Intl.NumberFormat('id-ID').format(data.data.cicilan)}</td>
                              </tr>
                              <tr style="border-top: 2px solid #333; font-weight: bold;">
                                 <td>Gaji Bersih:</td>
                                 <td class="text-end text-success">Rp ${new Intl.NumberFormat('id-ID').format(data.data.total_bersih)}</td>
                              </tr>
                           </table>
                        ` : ''}
                     </div>
                  `,
                  confirmButtonColor: '#3085d6',
                  confirmButtonText: 'OK'
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

$(document).ready(function() {
   // Initialize Signature Pad
   const canvas = document.getElementById('signature-pad');
   signaturePad = new SignaturePad(canvas, {
      backgroundColor: 'rgb(255, 255, 255)',
      penColor: 'rgb(0, 0, 0)'
   });
});

function bayarGaji(tukangId, namaTukang) {
   currentTukangId = tukangId;
   $('#modal-nama-tukang').text(namaTukang);
   
   // Load detail gaji via AJAX
   $.ajax({
      url: '{{ route("keuangan-tukang.detail-gaji", ":id") }}'.replace(':id', tukangId),
      method: 'GET',
      data: {
         periode_mulai: '{{ $periodeMulai->format("Y-m-d") }}',
         periode_akhir: '{{ $periodeAkhir->format("Y-m-d") }}'
      },
      success: function(response) {
         if (response.success) {
            currentDetailGaji = response;
            
            // Isi detail slip
            $('#detail-kode').text(response.tukang.kode_tukang);
            $('#detail-nama').text(response.tukang.nama_tukang);
            $('#detail-periode').text(response.periode_mulai + ' - ' + response.periode_akhir);
            $('#detail-upah-harian').text('Rp ' + formatNumber(response.total_upah_harian));
            $('#detail-upah-lembur').text('Rp ' + formatNumber(response.total_upah_lembur));
            $('#detail-lembur-cash').text('- Rp ' + formatNumber(response.lembur_cash_terbayar));
            $('#detail-total-kotor').text('Rp ' + formatNumber(response.total_kotor));
            $('#detail-total-potongan').text('Rp ' + formatNumber(response.total_potongan));
            $('#detail-total-nett').text('Rp ' + formatNumber(response.total_nett));
            
            // Isi rincian potongan
            let potonganHtml = '';
            if (response.rincian_potongan.length > 0) {
               response.rincian_potongan.forEach(function(item) {
                  potonganHtml += '<tr><td class="ps-4"><small>' + item.jenis + '</small><br><small class="text-muted">' + item.keterangan + '</small></td>';
                  potonganHtml += '<td class="text-end"><small>- Rp ' + formatNumber(item.jumlah) + '</small></td></tr>';
               });
            } else {
               potonganHtml = '<tr><td colspan="2" class="ps-4"><small class="text-muted">Tidak ada potongan</small></td></tr>';
            }
            $('#detail-potongan-list').html(potonganHtml);
            
            // Clear TTD
            signaturePad.clear();
            
            // Show modal
            $('#modalTTD').modal('show');
         }
      },
      error: function(xhr) {
         Swal.fire('Error', xhr.responseJSON?.message || 'Gagal memuat detail gaji', 'error');
      }
   });
}

function clearSignature() {
   signaturePad.clear();
}

function simpanPembayaran() {
   // Validasi TTD
   if (signaturePad.isEmpty()) {
      Swal.fire({
         icon: 'warning',
         title: 'TTD Belum Diisi',
         text: 'Silakan minta tukang untuk membubuhkan tanda tangan terlebih dahulu!'
      });
      return;
   }
   
   // Get TTD as base64
   const ttdBase64 = signaturePad.toDataURL();
   
   // Konfirmasi
   Swal.fire({
      title: 'Konfirmasi Pembayaran',
      html: 'Bayar gaji kepada <strong>' + currentDetailGaji.tukang.nama_tukang + '</strong><br>Sebesar: <strong class="text-primary">Rp ' + formatNumber(currentDetailGaji.total_nett) + '</strong>?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#28a745',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="ti ti-check"></i> Ya, Bayar!',
      cancelButtonText: 'Batal'
   }).then((result) => {
      if (result.isConfirmed) {
         // Submit via AJAX
         $.ajax({
            url: '{{ route("keuangan-tukang.simpan-pembayaran-gaji") }}',
            method: 'POST',
            data: {
               _token: '{{ csrf_token() }}',
               tukang_id: currentTukangId,
               periode_mulai: '{{ $periodeMulai->format("Y-m-d") }}',
               periode_akhir: '{{ $periodeAkhir->format("Y-m-d") }}',
               tanda_tangan: ttdBase64,
               total_nett: currentDetailGaji.total_nett
            },
            success: function(response) {
               if (response.success) {
                  Swal.fire({
                     icon: 'success',
                     title: 'Pembayaran Berhasil!',
                     text: 'Gaji telah dibayarkan dan TTD tersimpan',
                     timer: 2000,
                     showConfirmButton: false
                  });
                  
                  $('#modalTTD').modal('hide');
                  
                  // Reload halaman setelah 2 detik
                  setTimeout(() => {
                     location.reload();
                  }, 2000);
               }
            },
            error: function(xhr) {
               Swal.fire('Error', xhr.responseJSON?.message || 'Gagal menyimpan pembayaran', 'error');
            }
         });
      }
   });
}

function formatNumber(num) {
   return parseFloat(num).toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

function lihatSlip(pembayaranId) {
   // Download PDF Slip Gaji
   window.open('{{ route('keuangan-tukang.download-slip-gaji', '') }}/' + pembayaranId, '_blank');
}
</script>
@endpush
