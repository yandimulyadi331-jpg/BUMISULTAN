@extends('layouts.app')
@section('titlepage', 'Manajemen Kendaraan')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Kendaraan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="#" class="btn btn-primary" id="btncreateKendaraan">
                            <i class="fa fa-plus me-2"></i> Tambah Kendaraan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-12">
                        <form action="{{ route('kendaraan.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12">
                                    <x-input-with-icon label="Cari Nama Kendaraan" value="{{ Request('nama_kendaraan') }}" 
                                        name="nama_kendaraan" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="jenis_kendaraan" class="form-select">
                                            <option value="">Semua Jenis</option>
                                            <option value="Mobil" {{ Request('jenis_kendaraan') == 'Mobil' ? 'selected' : '' }}>Mobil</option>
                                            <option value="Motor" {{ Request('jenis_kendaraan') == 'Motor' ? 'selected' : '' }}>Motor</option>
                                            <option value="Truk" {{ Request('jenis_kendaraan') == 'Truk' ? 'selected' : '' }}>Truk</option>
                                            <option value="Bus" {{ Request('jenis_kendaraan') == 'Bus' ? 'selected' : '' }}>Bus</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-sm-12">
                                    <div class="form-group mb-3">
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="tersedia" {{ Request('status') == 'tersedia' ? 'selected' : '' }}>Tersedia</option>
                                            <option value="keluar" {{ Request('status') == 'keluar' ? 'selected' : '' }}>Keluar</option>
                                            <option value="dipinjam" {{ Request('status') == 'dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                                            <option value="service" {{ Request('status') == 'service' ? 'selected' : '' }}>Service</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12">
                                    <button class="btn btn-primary w-100"><i class="ti ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Foto</th>
                                        <th>Kode</th>
                                        <th>Nama/No.Polisi</th>
                                        <th>Jenis</th>
                                        <th>Merk/Model</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($kendaraan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $kendaraan->firstItem() - 1 }}</td>
                                            <td>
                                                @if($d->foto)
                                                    <img src="{{ asset('storage/kendaraan/' . $d->foto) }}" 
                                                        alt="Foto" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td><strong>{{ $d->kode_kendaraan }}</strong></td>
                                            <td>
                                                <strong>{{ $d->nama_kendaraan }}</strong><br>
                                                <small class="text-muted">{{ $d->no_polisi }}</small>
                                            </td>
                                            <td>{{ $d->jenis_kendaraan }}</td>
                                            <td>
                                                {{ $d->merk ?? '-' }}<br>
                                                <small class="text-muted">{{ $d->model ?? '-' }}</small>
                                            </td>
                                            <td>
                                                @if($d->status == 'tersedia')
                                                    <span class="badge bg-success">Tersedia</span>
                                                    @if($d->perluService())
                                                        <br><span class="badge bg-warning mt-1">Perlu Service</span>
                                                    @endif
                                                @elseif($d->status == 'keluar')
                                                    <span class="badge bg-info">Sedang Keluar</span>
                                                @elseif($d->status == 'dipinjam')
                                                    <span class="badge bg-primary">Dipinjam</span>
                                                @else
                                                    <span class="badge bg-danger">Service</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <!-- Detail Kendaraan -->
                                                    <a href="{{ route('kendaraan.detail', Crypt::encrypt($d->id)) }}?tab=aktivitas" class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="ti ti-eye"></i>
                                                    </a>

                                                    <!-- Edit & Delete -->
                                                    <a href="#" class="btn btn-sm btn-success editKendaraan" 
                                                        kendaraan_id="{{ Crypt::encrypt($d->id) }}">
                                                        <i class="ti ti-edit"></i>
                                                    </a>
                                                    <form method="POST" class="deleteform d-inline"
                                                        action="{{ route('kendaraan.delete', Crypt::encrypt($d->id)) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger delete-confirm">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $kendaraan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<x-modal-form id="mdlcreateKendaraan" size="modal-lg" show="loadcreateKendaraan" title="Tambah Kendaraan" />
<x-modal-form id="mdleditKendaraan" size="modal-lg" show="loadeditKendaraan" title="Edit Kendaraan" />
@endsection

@push('myscript')
    <style>
        /* Pastikan dropdown menu tampil di atas semua elemen */
        .dropdown-menu {
            z-index: 9999 !important;
        }
        
        /* Pastikan tabel tidak overflow */
        .table-responsive {
            overflow: visible !important;
        }
        
        /* Dropdown menu styling */
        .dropdown-menu {
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6;
        }
    </style>
    <script>
        $(function() {
            $("#btncreateKendaraan").click(function(e) {
                e.preventDefault();
                $('#mdlcreateKendaraan').modal("show");
                $("#loadcreateKendaraan").load('/kendaraan/create');
            });

            $(".editKendaraan").click(function(e) {
                e.preventDefault();
                var kendaraan_id = $(this).attr("kendaraan_id");
                $('#mdleditKendaraan').modal("show");
                $("#loadeditKendaraan").load('/kendaraan/' + kendaraan_id + '/edit');
            });

            $(".delete-confirm").click(function(e) {
                e.preventDefault();
                var form = $(this).closest('form');
                Swal.fire({
                    title: 'Hapus Data?',
                    text: "Data kendaraan akan dihapus permanen!",
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
