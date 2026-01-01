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
                  <button type="button" class="btn btn-success btn-sm" onclick="pilihMingguIni()">
                     <i class="ti ti-calendar-week me-1"></i> Minggu Ini
                  </button>
                  <a href="{{ route('kehadiran-tukang.export-pdf') }}?tanggal_mulai={{ $tanggal_mulai }}&tanggal_akhir={{ $tanggal_akhir }}" 
                     class="btn btn-danger btn-sm" target="_blank">
                     <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                  </a>
                  <form action="{{ route('kehadiran-tukang.rekap') }}" method="GET" id="formFilter" class="d-flex align-items-center gap-2">
                     <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" 
                            value="{{ $tanggal_mulai }}" required>
                     <span class="text-muted">s/d</span>
                     <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" 
                            value="{{ $tanggal_akhir }}" required>
                     <button type="submit" class="btn btn-primary btn-sm">
                        <i class="ti ti-search"></i> Filter
                     </button>
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
                        <th width="10%" class="text-danger">Potongan</th>
                        <th width="10%" class="text-success">Total Nett</th>
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
                              @php
                                 // Cek apakah auto potong aktif
                                 $autoPotongAktif = $tukang->auto_potong_pinjaman ?? false;
                                 
                                 // Hitung potongan HANYA jika auto potong aktif
                                 $potonganPinjaman = 0;
                                 if ($autoPotongAktif) {
                                    $potonganPinjaman = \App\Models\PinjamanTukang::where('tukang_id', $tukang->id)
                                       ->where('status', 'aktif')
                                       ->where('sisa_pinjaman', '>', 0)
                                       ->sum('cicilan_per_minggu');
                                 }
                                 
                                 // Hitung berapa minggu dalam range tanggal
                                 $start = \Carbon\Carbon::parse($tanggal_mulai);
                                 $end = \Carbon\Carbon::parse($tanggal_akhir);
                                 $jumlahMinggu = ceil($start->diffInDays($end) / 7);
                                 
                                 $totalPotongan = $potonganPinjaman * $jumlahMinggu;
                                 $totalNett = $tukang->total_upah - $totalPotongan;
                              @endphp
                              @if($totalPotongan > 0)
                                 <strong class="text-danger fs-6">
                                    -Rp {{ number_format($totalPotongan, 0, ',', '.') }}
                                 </strong>
                                 <br><small class="text-muted">
                                    ({{ $jumlahMinggu }}x Rp {{ number_format($potonganPinjaman, 0, ',', '.') }})
                                 </small>
                              @else
                                 <span class="text-muted">-</span>
                              @endif
                           </td>
                           <td>
                              <strong class="fs-6" style="color: {{ $totalNett >= 0 ? '#28a745' : '#dc3545' }};">
                                 Rp {{ number_format($totalNett, 0, ',', '.') }}
                              </strong>
                           </td>
                           <td class="text-center">
                              <a href="{{ route('kehadiran-tukang.detail', $tukang->id) }}?tanggal_mulai={{ $tanggal_mulai }}&tanggal_akhir={{ $tanggal_akhir }}" 
                                 class="btn btn-sm btn-info" title="Detail">
                                 <i class="ti ti-eye"></i>
                              </a>
                           </td>
                        </tr>
                     @empty
                        <tr>
                           <td colspan="14" class="text-center">Tidak ada data</td>
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
                           <th>
                              @php
                                 $start = \Carbon\Carbon::parse($tanggal_mulai);
                                 $end = \Carbon\Carbon::parse($tanggal_akhir);
                                 $jumlahMinggu = ceil($start->diffInDays($end) / 7);
                                 
                                 $totalPotonganAll = 0;
                                 foreach($tukangs as $t) {
                                    // Hitung potongan HANYA jika auto potong aktif
                                    if ($t->auto_potong_pinjaman) {
                                       $cicilan = \App\Models\PinjamanTukang::where('tukang_id', $t->id)
                                          ->where('status', 'aktif')
                                          ->where('sisa_pinjaman', '>', 0)
                                          ->sum('cicilan_per_minggu');
                                       $totalPotonganAll += ($cicilan * $jumlahMinggu);
                                    }
                                 }
                                 $totalNettAll = $tukangs->sum('total_upah') - $totalPotonganAll;
                              @endphp
                              <strong class="text-danger fs-6">
                                 -RP {{ number_format($totalPotonganAll, 0, ',', '.') }}
                              </strong>
                           </th>
                           <th>
                              <strong class="fs-5" style="color: {{ $totalNettAll >= 0 ? '#28a745' : '#dc3545' }};">
                                 Rp {{ number_format($totalNettAll, 0, ',', '.') }}
                              </strong>
                           </th>
                           <th></th>
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

@push('scripts')
<script>
function pilihMingguIni() {
   // Hitung periode minggu ini (Sabtu - Kamis)
   const today = new Date();
   const dayOfWeek = today.getDay(); // 0 = Minggu, 6 = Sabtu
   
   let sabtu, kamis;
   
   if (dayOfWeek === 5) { // Jumat (libur)
      // Ambil Sabtu minggu lalu sampai Kamis kemarin
      const daysToSabtu = 7; // 7 hari ke belakang ke Sabtu minggu lalu
      sabtu = new Date(today);
      sabtu.setDate(today.getDate() - daysToSabtu);
      
      kamis = new Date(today);
      kamis.setDate(today.getDate() - 1); // Kamis kemarin
   } else if (dayOfWeek === 6) { // Sabtu
      // Sabtu hari ini sampai Kamis minggu ini
      sabtu = new Date(today);
      
      kamis = new Date(today);
      kamis.setDate(today.getDate() + 5); // +5 hari = Kamis
   } else { // Minggu (0) - Kamis (4)
      // Cari Sabtu terdekat ke belakang
      const daysToSabtu = (dayOfWeek === 0) ? 1 : (7 - dayOfWeek + 6) % 7 + 1;
      sabtu = new Date(today);
      sabtu.setDate(today.getDate() - daysToSabtu);
      
      // Kamis = Sabtu + 5 hari
      kamis = new Date(sabtu);
      kamis.setDate(sabtu.getDate() + 5);
   }
   
   // Format tanggal ke YYYY-MM-DD
   const formatDate = (date) => {
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      return `${year}-${month}-${day}`;
   };
   
   // Set nilai input
   document.getElementById('tanggal_mulai').value = formatDate(sabtu);
   document.getElementById('tanggal_akhir').value = formatDate(kamis);
   
   // Auto submit form
   document.getElementById('formFilter').submit();
}
</script>
@endpush
