@extends('layouts.app')
@section('titlepage', 'Detail Data Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang / Data Tukang /</span> Detail
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Data Tukang</h5>
            <div>
               @can('tukang.edit')
                  <a href="{{ route('tukang.edit', Crypt::encrypt($tukang->id)) }}" class="btn btn-warning btn-sm">
                     <i class="ti ti-edit me-1"></i> Edit
                  </a>
               @endcan
               <a href="{{ route('tukang.index') }}" class="btn btn-secondary btn-sm">
                  <i class="ti ti-arrow-left me-1"></i> Kembali
               </a>
            </div>
         </div>
         <div class="card-body">
            <div class="row">
               <!-- Foto -->
               <div class="col-md-3 text-center mb-4">
                  @if($tukang->foto)
                     <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" 
                        alt="Foto Tukang" class="img-thumbnail mb-3" 
                        style="max-width: 100%; max-height: 300px; object-fit: cover;">
                  @else
                     <div class="avatar avatar-xl mb-3">
                        <span class="avatar-initial rounded bg-label-secondary" style="font-size: 3rem;">
                           <i class="ti ti-user"></i>
                        </span>
                     </div>
                  @endif
                  <div>
                     @if($tukang->status == 'aktif')
                        <span class="badge bg-success">Aktif</span>
                     @else
                        <span class="badge bg-secondary">Non Aktif</span>
                     @endif
                  </div>
               </div>

               <!-- Data Tukang -->
               <div class="col-md-9">
                  <div class="table-responsive">
                     <table class="table table-borderless">
                        <tbody>
                           <tr>
                              <td width="30%" class="fw-bold">Kode Tukang</td>
                              <td width="5%">:</td>
                              <td>{{ $tukang->kode_tukang }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Nama Tukang</td>
                              <td>:</td>
                              <td>{{ $tukang->nama_tukang }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">NIK</td>
                              <td>:</td>
                              <td>{{ $tukang->nik ?? '-' }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">No HP</td>
                              <td>:</td>
                              <td>
                                 @if($tukang->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $tukang->no_hp) }}" 
                                       target="_blank" class="text-decoration-none">
                                       <i class="ti ti-brand-whatsapp text-success"></i> {{ $tukang->no_hp }}
                                    </a>
                                 @else
                                    -
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Email</td>
                              <td>:</td>
                              <td>
                                 @if($tukang->email)
                                    <a href="mailto:{{ $tukang->email }}" class="text-decoration-none">
                                       <i class="ti ti-mail"></i> {{ $tukang->email }}
                                    </a>
                                 @else
                                    -
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Keahlian</td>
                              <td>:</td>
                              <td>
                                 @if($tukang->keahlian)
                                    <span class="badge bg-label-primary">{{ $tukang->keahlian }}</span>
                                 @else
                                    -
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Tarif Harian</td>
                              <td>:</td>
                              <td>
                                 @if($tukang->tarif_harian)
                                    <span class="text-success fw-bold">Rp {{ number_format($tukang->tarif_harian, 0, ',', '.') }}</span>
                                 @else
                                    -
                                 @endif
                              </td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Alamat</td>
                              <td>:</td>
                              <td>{{ $tukang->alamat ?? '-' }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Keterangan</td>
                              <td>:</td>
                              <td>{{ $tukang->keterangan ?? '-' }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Tanggal Dibuat</td>
                              <td>:</td>
                              <td>{{ $tukang->created_at->format('d F Y H:i') }}</td>
                           </tr>
                           <tr>
                              <td class="fw-bold">Terakhir Diupdate</td>
                              <td>:</td>
                              <td>{{ $tukang->updated_at->format('d F Y H:i') }}</td>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection
