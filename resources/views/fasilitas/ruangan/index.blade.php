@extends('layouts.app')
@section('titlepage', 'Ruangan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('gedung.index') }}">Manajemen Gedung</a> / {{ $gedung->nama_gedung }} / Ruangan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Gedung: {{ $gedung->nama_gedung }}</h5>
                        <small class="text-muted">{{ $gedung->alamat }}</small>
                    </div>
                    <div>
                        <a href="{{ route('gedung.index') }}" class="btn btn-secondary me-2">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="#" class="btn btn-primary" id="btncreateRuangan">
                            <i class="fa fa-plus me-2"></i> Tambah Ruangan
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>No.</th>
                                        <th>Foto</th>
                                        <th>Kode</th>
                                        <th>Nama Ruangan</th>
                                        <th>Lantai</th>
                                        <th>Luas (m²)</th>
                                        <th>Kapasitas</th>
                                        <th>Total Barang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ruangan as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $ruangan->firstItem() - 1 }}</td>
                                            <td>
                                                @if($d->foto)
                                                    <img src="{{ asset('storage/ruangan/' . $d->foto) }}" 
                                                        alt="Foto Ruangan" 
                                                        class="img-thumbnail foto-preview" 
                                                        style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                        data-foto="{{ asset('storage/ruangan/' . $d->foto) }}"
                                                        data-title="{{ $d->nama_ruangan }}">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_ruangan }}</td>
                                            <td>{{ textUpperCase($d->nama_ruangan) }}</td>
                                            <td>{{ $d->lantai ?? '-' }}</td>
                                            <td>{{ $d->luas ? number_format($d->luas, 2) : '-' }}</td>
                                            <td>{{ $d->kapasitas ?? '-' }}</td>
                                            <td>{{ $d->total_barang }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="{{ route('barang.index', [
                                                            'gedung_id' => Crypt::encrypt($gedung->id),
                                                            'ruangan_id' => Crypt::encrypt($d->id)
                                                        ]) }}" class="me-2" title="Lihat Barang">
                                                            <i class="ti ti-package text-primary"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="me-2 editRuangan" 
                                                            ruangan_id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('ruangan.delete', [
                                                                'gedung_id' => Crypt::encrypt($gedung->id),
                                                                'id' => Crypt::encrypt($d->id)
                                                            ]) }}">
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
                            {{ $ruangan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateRuangan" size="" show="loadcreateRuangan" title="Tambah Ruangan" />
<x-modal-form id="mdleditRuangan" size="" show="loadeditRuangan" title="Edit Ruangan" />

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
            $("#btncreateRuangan").click(function(e) {
                e.preventDefault();
                $('#mdlcreateRuangan').modal("show");
                $("#loadcreateRuangan").load('/gedung/{{ Crypt::encrypt($gedung->id) }}/ruangan/create');
            });

            $(".editRuangan").click(function(e) {
                e.preventDefault();
                var ruangan_id = $(this).attr("ruangan_id");
                $('#mdleditRuangan').modal("show");
                $("#loadeditRuangan").load('/gedung/{{ Crypt::encrypt($gedung->id) }}/ruangan/' + ruangan_id + '/edit');
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
