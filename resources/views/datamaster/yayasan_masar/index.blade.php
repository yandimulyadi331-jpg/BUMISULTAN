@extends('layouts.app')
@section('titlepage', 'Yayasan Masar')

@section('content')
@section('navigasi')
    <span>Yayasan Masar</span>
@endsection

<!-- Tab Navigation untuk Filter Jenis Kelamin dan Status Umroh -->
<div class="card mb-3">
    <div class="card-body">
        <ul class="nav nav-pills flex-wrap" role="tablist">
            <li class="nav-item" role="presentation">
                <a href="{{ route('yayasan_masar.index') }}" 
                   class="nav-link {{ !request()->has('jenis_kelamin') && !request()->has('status_umroh') ? 'active' : '' }}">
                    <i class="ti ti-users me-1"></i> Semua Data
                    <span class="badge bg-primary ms-2">{{ $total_count }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('yayasan_masar.index') }}?jenis_kelamin=L" 
                   class="nav-link {{ request('jenis_kelamin') === 'L' && !request()->has('status_umroh') ? 'active' : '' }}">
                    <i class="ti ti-user me-1"></i> Laki-laki
                    <span class="badge bg-info ms-2">{{ $laki_count }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('yayasan_masar.index') }}?jenis_kelamin=P" 
                   class="nav-link {{ request('jenis_kelamin') === 'P' && !request()->has('status_umroh') ? 'active' : '' }}">
                    <i class="ti ti-user-circle me-1"></i> Perempuan
                    <span class="badge bg-danger ms-2">{{ $perempuan_count }}</span>
                </a>
            </li>
            <li class="nav-item ms-2 ps-2 border-start" role="presentation">
                <a href="{{ route('yayasan_masar.index') }}?status_umroh=1" 
                   class="nav-link {{ request('status_umroh') === '1' ? 'active' : '' }}">
                    <i class="ti ti-briefcase me-1"></i> Umroh
                    <span class="badge bg-success ms-2">{{ $umroh_count }}</span>
                </a>
            </li>
            <li class="nav-item" role="presentation">
                <a href="{{ route('yayasan_masar.index') }}?status_umroh=0" 
                   class="nav-link {{ request('status_umroh') === '0' ? 'active' : '' }}">
                    <i class="ti ti-x me-1"></i> Tidak Umroh
                    <span class="badge bg-secondary ms-2">{{ $tidak_umroh_count }}</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('yayasan_masar.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah Data</a>
                @endcan
                <a href="#" class="btn btn-success" id="btnDownloadExcel"><i class="ti ti-download me-2"></i> Download Excel</a>
                <a href="#" class="btn btn-info" id="btnImportExcel"><i class="ti ti-upload me-2"></i> Import Excel</a>
                <a href="#" class="btn btn-warning" id="btnExportExcel"><i class="ti ti-file-export me-2"></i> Export Excel</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('yayasan_masar.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama" value="{{ Request('nama') }}" name="nama"
                                        icon="ti ti-search" />
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
                            <table class="table  table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama Yayasan Masar</th>
                                        <th>Status Umroh</th>
                                        <th>Jumlah Kehadiran</th>
                                        <th>Status</th>
                                        <th>Tanggal Masuk</th>
                                        <th class="text-center">PIN</th>
                                        <th>Foto</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yayasan_masar as $d)
                                        <tr>
                                            <td>{{ $d->kode_yayasan ?? $d->kode_yayasan }}</td>
                                            <td>{{ $d->nama }}</td>
                                            <td>
                                                @if ($d->status_umroh == '1')
                                                    <span class="badge bg-success">Umroh</span>
                                                @else
                                                    <span class="badge bg-secondary">Tidak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $kehadiran = $d->jumlah_kehadiran ?? 0;
                                                    $badgeClass = 'bg-danger'; // Default: Rendah (< 10)
                                                    if ($kehadiran >= 25) {
                                                        $badgeClass = 'bg-success'; // Tinggi (>= 25)
                                                    } elseif ($kehadiran >= 10) {
                                                        $badgeClass = 'bg-warning'; // Sedang (10-24)
                                                    }
                                                @endphp
                                                <span class="badge {{ $badgeClass }}">{{ $kehadiran }} kali</span>
                                            </td>
                                            <td>
                                                @if ($d->status_aktif == '1')
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-danger">Non Aktif</span>
                                                @endif
                                            </td>
                                            <td>{{ date('d-m-y', strtotime($d->tanggal_masuk)) }}</td>
                                            <td class="text-center">{{ $d->pin ?? '-' }}</td>
                                            <td>
                                                @if (!empty($d->foto) && Storage::disk('public')->exists('yayasan_masar/' . $d->foto))
                                                    <a href="{{ getfotoYayasanMasar($d->foto) }}" data-bs-toggle="modal" data-bs-target="#modalFotoYayasan" data-image="{{ getfotoYayasanMasar($d->foto) }}" class="btnViewFoto">
                                                        <img src="{{ getfotoYayasanMasar($d->foto) }}" alt="" class="rounded" style="width: 50px; height: 50px; object-fit: cover; cursor: pointer;">
                                                    </a>
                                                @else
                                                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="" class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('yayasan_masar.setjamkerja')
                                                        <div>
                                                            <a href="#" class="me-2 btnSetJamkerja" kode_yayasan="{{ Crypt::encrypt($d->kode_yayasan) }}">
                                                                <i class="ti ti-device-watch text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('yayasan_masar.show')
                                                        <div>
                                                            <a href="{{ route('yayasan_masar.downloadIdCard', Crypt::encrypt($d->kode_yayasan)) }}" class="me-2" title="Download ID Card" target="_blank">
                                                                <i class="ti ti-id text-warning"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('yayasan_masar.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" kode_yayasan="{{ Crypt::encrypt($d->kode_yayasan) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('yayasan_masar.show')
                                                        <div>
                                                            <a href="{{ route('yayasan_masar.show', Crypt::encrypt($d->kode_yayasan)) }}" class="me-2">
                                                                <i class="ti ti-file-description text-info"></i>
                                                            </a>
                                                        </div>
                                                    @endcan
                                                    @can('yayasan_masar.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform me-1"
                                                                action="{{ route('yayasan_masar.destroy', Crypt::encrypt($d->kode_yayasan)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan

                                                    @can('users.create')
                                                        @if (empty($d->id_user))
                                                            <a href="{{ route('yayasan_masar.createuser', Crypt::encrypt($d->kode_yayasan)) }}">
                                                                <i class="ti ti-user-plus text-danger"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('yayasan_masar.deleteuser', Crypt::encrypt($d->kode_yayasan)) }}">
                                                                <i class="ti ti-user text-success"></i>
                                                            </a>
                                                        @endif
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $yayasan_masar->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
<x-modal-form id="modalSetJamkerja" show="loadmodalSetJamkerja" size="modal-lg" title="Set Jam Kerja" />
<x-modal-form id="modalSetCabang" show="loadmodalSetCabang" size="modal-lg" title="Set Cabang Yayasan Masar" />
<x-modal-form id="modalImport" show="loadmodalImport" size="modal-lg" title="Import Data Yayasan Masar" />

<!-- Modal untuk menampilkan foto besar -->
<div class="modal fade" id="modalFotoYayasan" tabindex="-1" aria-labelledby="modalFotoYayasanLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoYayasanLabel">Foto Jamaah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="fotoDisplay" src="" alt="Foto" style="max-width: 100%; max-height: 500px; object-fit: contain;">
            </div>
        </div>
    </div>
</div>
@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        loading();
        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Yayasan Masar");
            $("#loadmodal").load("{{ route('yayasan_masar.create') }}");
        });

        $("#btnImport").click(function() {
            // Import feature removed - not needed for basic version
        });

        $("#btnDownloadExcel").click(function(e) {
            e.preventDefault();
            window.location.href = "{{ route('yayasan_masar.downloadTemplate') }}";
        });

        $("#btnImportExcel").click(function(e) {
            e.preventDefault();
            $("#modalImport").modal("show");
            $("#loadmodalImport").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalImport").load("{{ route('yayasan_masar.importForm') }}");
        });

        $("#btnExportExcel").click(function(e) {
            e.preventDefault();
            window.location.href = "{{ route('yayasan_masar.exportExcel') }}";
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            loading();
            const kode_yayasan = $(this).attr("kode_yayasan");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Data");
            $("#loadmodal").load(`/yayasan-masar/${kode_yayasan}/edit`);
        });

        $(".btnSetJamkerja").click(function(e) {
            e.preventDefault();
            const kode_yayasan = $(this).attr("kode_yayasan");
            $("#modalSetJamkerja").modal("show");
            $("#loadmodalSetJamkerja").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalSetJamkerja").load(`/yayasan-masar/${kode_yayasan}/setjamkerja`);
        });

        $(".btnSetCabang").click(function(e) {
            e.preventDefault();
            const kode_yayasan = $(this).attr("kode_yayasan");
            $("#modalSetCabang").modal("show");
            $("#loadmodalSetCabang").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#loadmodalSetCabang").load(`/yayasan-masar/${kode_yayasan}/setcabang`);
        });

        // Handler untuk modal foto
        $('#modalFotoYayasan').on('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const imageUrl = button.getAttribute('data-image');
            const modal = $(this);
            modal.find('#fotoDisplay').attr('src', imageUrl);
        });

    });
</script>
@endpush
