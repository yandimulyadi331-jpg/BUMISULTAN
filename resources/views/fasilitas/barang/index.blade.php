@extends('layouts.app')
@section('titlepage', 'Barang')

@section('content')
@section('navigasi')
    <span>
        <a href="{{ route('gedung.index') }}">Manajemen Gedung</a> / 
        <a href="{{ route('ruangan.index', Crypt::encrypt($gedung->id)) }}">{{ $gedung->nama_gedung }}</a> / 
        {{ $ruangan->nama_ruangan }} / Barang
    </span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Ruangan: {{ $ruangan->nama_ruangan }}</h5>
                        <small class="text-muted">Gedung {{ $gedung->nama_gedung }} - Lantai {{ $ruangan->lantai ?? '-' }}</small>
                    </div>
                    <div>
                        <a href="{{ route('ruangan.index', Crypt::encrypt($gedung->id)) }}" class="btn btn-secondary me-2">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                        <a href="#" class="btn btn-primary" id="btncreateBarang">
                            <i class="fa fa-plus me-2"></i> Tambah Barang
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
                                        <th>Nama Barang</th>
                                        <th>Kategori</th>
                                        <th>Merk</th>
                                        <th>Jumlah</th>
                                        <th>Kondisi</th>
                                        <th>Harga</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($barang as $d)
                                        <tr>
                                            <td>{{ $loop->iteration + $barang->firstItem() - 1 }}</td>
                                            <td>
                                                @if($d->foto)
                                                    <img src="{{ asset('storage/barang/' . $d->foto) }}" 
                                                        alt="Foto Barang" 
                                                        class="img-thumbnail foto-preview" 
                                                        style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;"
                                                        data-foto="{{ asset('storage/barang/' . $d->foto) }}"
                                                        data-title="{{ $d->nama_barang }}">
                                                @else
                                                    <span class="badge bg-secondary">No Photo</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->kode_barang }}</td>
                                            <td>{{ textUpperCase($d->nama_barang) }}</td>
                                            <td>{{ $d->kategori ?? '-' }}</td>
                                            <td>{{ $d->merk ?? '-' }}</td>
                                            <td>{{ $d->jumlah }} {{ $d->satuan }}</td>
                                            <td>
                                                @if($d->kondisi == 'Baik')
                                                    <span class="badge bg-success">{{ $d->kondisi }}</span>
                                                @elseif($d->kondisi == 'Rusak Ringan')
                                                    <span class="badge bg-warning">{{ $d->kondisi }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ $d->kondisi }}</span>
                                                @endif
                                            </td>
                                            <td>{{ $d->harga_perolehan ? 'Rp ' . number_format($d->harga_perolehan, 0, ',', '.') : '-' }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <div>
                                                        <a href="{{ route('barang.transfer', [
                                                            'gedung_id' => Crypt::encrypt($gedung->id),
                                                            'ruangan_id' => Crypt::encrypt($ruangan->id),
                                                            'id' => Crypt::encrypt($d->id)
                                                        ]) }}" class="me-2" title="Transfer Barang">
                                                            <i class="ti ti-arrow-forward-up text-primary" style="font-size: 18px;"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="{{ route('barang.riwayat', [
                                                            'gedung_id' => Crypt::encrypt($gedung->id),
                                                            'ruangan_id' => Crypt::encrypt($ruangan->id),
                                                            'id' => Crypt::encrypt($d->id)
                                                        ]) }}" class="me-2" title="Riwayat Transfer">
                                                            <i class="ti ti-clock-hour-4 text-warning" style="font-size: 18px;"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <a href="#" class="me-2 editBarang" 
                                                            barang_id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    </div>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('barang.delete', [
                                                                'gedung_id' => Crypt::encrypt($gedung->id),
                                                                'ruangan_id' => Crypt::encrypt($ruangan->id),
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
                            {{ $barang->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="mdlcreateBarang" size="" show="loadcreateBarang" title="Tambah Barang" />
<x-modal-form id="mdleditBarang" size="" show="loadeditBarang" title="Edit Barang" />

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
            $("#btncreateBarang").click(function(e) {
                e.preventDefault();
                $('#mdlcreateBarang').modal("show");
                $("#loadcreateBarang").load('/gedung/{{ Crypt::encrypt($gedung->id) }}/ruangan/{{ Crypt::encrypt($ruangan->id) }}/barang/create');
            });

            $(".editBarang").click(function(e) {
                e.preventDefault();
                var barang_id = $(this).attr("barang_id");
                $('#mdleditBarang').modal("show");
                $("#loadeditBarang").load('/gedung/{{ Crypt::encrypt($gedung->id) }}/ruangan/{{ Crypt::encrypt($ruangan->id) }}/barang/' + barang_id + '/edit');
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
