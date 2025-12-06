@extends('layouts.app')
@section('titlepage', 'Gedung')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Gedung</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="#" class="btn btn-primary" id="btncreateGedung">
                            <i class="fa fa-plus me-2"></i> Tambah Gedung
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('gedung.exportPDF') }}" class="btn btn-danger" target="_blank">
                            <i class="ti ti-file-download me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('gedung.index') }}">
                            <div class="row">
                                <div class="col-lg-5 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Gedung" value="{{ Request('nama_gedung') }}" 
                                        name="nama_gedung" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-5 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <select name="kode_cabang" id="kode_cabang" class="form-select">
                                            <option value="">Semua Cabang</option>
                                            @foreach ($cabang as $c)
                                                <option value="{{ $c->kode_cabang }}" {{ Request('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                                    {{ $c->nama_cabang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <button class="btn btn-primary"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Foto</th>
                                        <th>Kode</th>
                                        <th>Nama Gedung</th>
                                        <th>Cabang</th>
                                        <th>Alamat</th>
                                        <th>Jumlah Lantai</th>
                                        <th>Total Ruangan</th>
                                        <th>Total Barang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gedung as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $gedung->firstItem() - 1 }}</td>
                                            <td>
                                                @if($d->foto)
                                                    <img src="{{ asset('storage/gedung/' . $d->foto) }}" 
                                                        alt="Foto Gedung" 
                                                        class="img-thumbnail foto-preview" 
                                                        style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                        data-foto="{{ asset('storage/gedung/' . $d->foto) }}"
                                                        data-title="{{ $d->nama_gedung }}">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_gedung }}</td>
                                            <td>{{ textUpperCase($d->nama_gedung) }}</td>
                                            <td>{{ $d->cabang ? $d->cabang->nama_cabang : '-' }}</td>
                                            <td>{{ $d->alamat }}</td>
                                            <td>{{ $d->jumlah_lantai }}</td>
                                            <td>{{ $d->total_ruangan }}</td>
                                            <td>{{ $d->total_barang }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="{{ route('ruangan.index', Crypt::encrypt($d->id)) }}" 
                                                            class="me-2" title="Lihat Ruangan">
                                                            <i class="ti ti-door text-primary"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="me-2 editGedung" 
                                                            gedung_id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('gedung.delete', Crypt::encrypt($d->id)) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <a href="#" class="delete-confirm ml-1">
                                                                <i class="ti ti-trash text-danger"></i>
                                                            </a>
                                                        </form>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $gedung->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateGedung" size="" show="loadcreateGedung" title="Tambah Gedung" />
<x-modal-form id="mdleditGedung" size="" show="loadeditGedung" title="Edit Gedung" />

<!-- Modal Preview Foto -->
<div class="modal fade" id="mdlPreviewFoto" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="fotoTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="fotoPreview" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $("#btncreateGedung").click(function(e) {
                e.preventDefault();
                $('#mdlcreateGedung').modal("show");
                $("#loadcreateGedung").load('/gedung/create');
            });

            $(".editGedung").click(function(e) {
                e.preventDefault();
                var gedung_id = $(this).attr("gedung_id");
                $('#mdleditGedung').modal("show");
                $("#loadeditGedung").load('/gedung/' + gedung_id + '/edit');
            });

            // Preview foto
            $(".foto-preview").click(function() {
                var fotoSrc = $(this).data('foto');
                var title = $(this).data('title');
                $("#fotoPreview").attr('src', fotoSrc);
                $("#fotoTitle").text(title);
                $('#mdlPreviewFoto').modal('show');
            });
        });
    </script>
@endpush
