@extends('layouts.app')
@section('titlepage', 'Data Tukang')

@section('content')
@section('navigasi')
   <span class="text-muted fw-light">Manajemen Tukang /</span> Data Tukang
@endsection

<div class="row">
   <div class="col-12">
      <div class="card">
         <div class="card-header">
            @can('tukang.create')
               <a href="{{ route('tukang.create') }}" class="btn btn-primary">
                  <i class="ti ti-plus me-2"></i> Tambah Data Tukang
               </a>
            @endcan
         </div>
         <div class="card-body">
            <div class="row mb-3">
               <div class="col-12">
                  <form action="{{ route('tukang.index') }}" method="GET">
                     <div class="row">
                        <div class="col-lg-4 col-sm-12 col-md-12">
                           <div class="form-group">
                              <input type="text" name="search" class="form-control" 
                                 placeholder="Cari nama, kode, keahlian..." 
                                 value="{{ Request('search') }}">
                           </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 col-md-12">
                           <div class="form-group">
                              <select name="status" class="form-select">
                                 <option value="">Semua Status</option>
                                 <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                 <option value="nonaktif" {{ Request('status') == 'nonaktif' ? 'selected' : '' }}>Non Aktif</option>
                              </select>
                           </div>
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button type="submit" class="btn btn-primary">
                              <i class="ti ti-search me-1"></i>Cari
                           </button>
                           <a href="{{ route('tukang.index') }}" class="btn btn-secondary">
                              <i class="ti ti-refresh"></i>
                           </a>
                        </div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="row">
               <div class="col-12">
                  <div class="table-responsive mb-2">
                     <table class="table table-hover table-bordered table-striped">
                        <thead class="table-dark">
                           <tr>
                              <th>No</th>
                              <th>Kode</th>
                              <th>Foto</th>
                              <th>Nama Tukang</th>
                              <th>NIK</th>
                              <th>Keahlian</th>
                              <th>No HP</th>
                              <th>Tarif/Hari</th>
                              <th>Status</th>
                              <th>Aksi</th>
                           </tr>
                        </thead>
                        <tbody>
                           @forelse ($tukangs as $index => $tukang)
                              <tr>
                                 <td>{{ $tukangs->firstItem() + $index }}</td>
                                 <td>{{ $tukang->kode_tukang }}</td>
                                 <td>
                                    @if($tukang->foto)
                                       <img src="{{ Storage::url('tukang/' . $tukang->foto) }}" 
                                          alt="Foto" class="rounded" width="50" height="50"
                                          style="object-fit: cover;">
                                    @else
                                       <div class="avatar avatar-sm">
                                          <span class="avatar-initial rounded bg-label-secondary">
                                             <i class="ti ti-user"></i>
                                          </span>
                                       </div>
                                    @endif
                                 </td>
                                 <td>{{ $tukang->nama_tukang }}</td>
                                 <td>{{ $tukang->nik ?? '-' }}</td>
                                 <td>{{ $tukang->keahlian ?? '-' }}</td>
                                 <td>{{ $tukang->no_hp ?? '-' }}</td>
                                 <td>{{ $tukang->tarif_harian ? 'Rp ' . number_format($tukang->tarif_harian, 0, ',', '.') : '-' }}</td>
                                 <td>
                                    @if($tukang->status == 'aktif')
                                       <span class="badge bg-success">Aktif</span>
                                    @else
                                       <span class="badge bg-secondary">Non Aktif</span>
                                    @endif
                                 </td>
                                 <td>
                                    <div class="d-flex" style="gap: 8px;">
                                       <a href="{{ route('tukang.show', Crypt::encrypt($tukang->id)) }}" 
                                          class="btn btn-sm btn-info" title="Detail">
                                          <i class="ti ti-eye"></i>
                                       </a>
                                       <a href="{{ route('tukang.edit', Crypt::encrypt($tukang->id)) }}" 
                                          class="btn btn-sm btn-success" title="Edit">
                                          <i class="ti ti-edit"></i>
                                       </a>
                                       <form method="POST" class="deleteform d-inline"
                                          action="{{ route('tukang.delete', Crypt::encrypt($tukang->id)) }}">
                                          @csrf
                                          @method('DELETE')
                                          <button type="button" class="btn btn-sm btn-danger delete-confirm" title="Hapus">
                                             <i class="ti ti-trash"></i>
                                          </button>
                                       </form>
                                    </div>
                                 </td>
                              </tr>
                           @empty
                              <tr>
                                 <td colspan="10" class="text-center">Tidak ada data tukang</td>
                              </tr>
                           @endforelse
                        </tbody>
                     </table>
                  </div>
                  <div class="d-flex justify-content-end">
                     {{ $tukangs->links() }}
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Delete confirmation
        $('.delete-confirm').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            
            Swal.fire({
                title: 'Hapus Data Tukang?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush