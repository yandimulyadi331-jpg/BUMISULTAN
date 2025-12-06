@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-profile.css') }}" />
@section('titlepage', 'Yayasan Masar')

@section('content')
@section('navigasi')
    <span class="text-muted">Yayasan Masar/</span> Detail
@endsection
<div class="row">
    <div class="col-12">
        <div class="card mb-4">
            <div class="user-profile-header-banner">
                <img src="{{ asset('assets/img/pages/profile-bg.jpg') }}" alt="Banner image" class="rounded-top">
            </div>
            <div class="user-profile-header d-flex flex-column flex-sm-row text-sm-start text-center mb-4">
                <div class="flex-shrink-0 mt-n2 mx-sm-0 mx-auto">
                    @if (!empty($yayasan_masar->foto) && Storage::disk('public')->exists('yayasan_masar/' . $yayasan_masar->foto))
                        <img src="{{ getfotoYayasanMasar($yayasan_masar->foto) }}" alt="user image" class="d-block  ms-0 ms-sm-4 rounded " height="150"
                            width="140" style="object-fit: cover">
                    @else
                        <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}" alt="user image"
                            class="d-block h-auto ms-0 ms-sm-4 rounded user-profile-img" width="150">
                    @endif

                </div>
                <div class="flex-grow-1 mt-3 mt-sm-5">
                    <div
                        class="d-flex align-items-md-end align-items-sm-start align-items-center justify-content-md-between justify-content-start mx-4 flex-md-row flex-column gap-4">
                        <div class="user-profile-info">
                            <h4>{{ textCamelCase($yayasan_masar->nama) }}</h4>
                            <ul class="list-inline mb-0 d-flex align-items-center flex-wrap justify-content-sm-start justify-content-center gap-2">
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-barcode"></i> {{ textCamelCase($yayasan_masar->no_identitas) }}
                                </li>
                                @if (!empty($yayasan_masar->nama_cabang))
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-building"></i> {{ textCamelCase($yayasan_masar->nama_cabang) }}
                                </li>
                                @endif
                                @if (!empty($yayasan_masar->nama_dept))
                                <li class="list-inline-item d-flex gap-1"><i class="ti ti-building-arch"></i>
                                    {{ textCamelCase($yayasan_masar->nama_dept) }}
                                </li>
                                @endif
                                @if (!empty($yayasan_masar->nama_jabatan))
                                <li class="list-inline-item d-flex gap-1">
                                    <i class="ti ti-user"></i> {{ textCamelCase($yayasan_masar->nama_jabatan) }}
                                </li>
                                @endif
                            </ul>
                        </div>
                        @if ($yayasan_masar->status_aktif === '1')
                            <a href="javascript:void(0)" class="btn btn-success waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Aktif
                            </a>
                        @else
                            <a href="javascript:void(0)" class="btn btn-danger waves-effect waves-light">
                                <i class="ti ti-check me-1"></i> Nonaktif
                            </a>
                        @endif
                        <a href="{{ route('yayasan_masar.downloadIdCard', Crypt::encrypt($yayasan_masar->kode_yayasan)) }}" 
                           class="btn btn-primary waves-effect waves-light" 
                           target="_blank" 
                           title="Download ID Card">
                            <i class="ti ti-id me-1"></i> Download ID Card
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- User Profile Content -->
<div class="row">
    <div class="col-xl-3 col-lg-5 col-md-5">
        <!-- About User -->
        <div class="card mb-4">
            <div class="card-body">
                <small class="card-text text-uppercase">Data User</small>
                @if ($user)
                    <ul class="list-unstyled mb-4 mt-3">
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Username :</span>
                            <span>{{ $user->username }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Email :</span>
                            <span>{{ $user->email }}</span>
                        </li>
                        <li class="d-flex align-items-center mb-3">
                            <i class="ti ti-barcode text-heading"></i><span class="fw-medium mx-2 text-heading">Password :</span>
                            <span>********</span>
                        </li>
                    </ul>
                @else
                    <div class="alert alert-danger mt-4" role="alert">
                        User Belum di Buat
                    </div>
                @endif
            </div>
        </div>




    </div>
    <div class="col-xl-9 col-lg-7 col-md-7">
        <!-- Data Yayasan Masar Section -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Data Yayasan Masar</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">NIK</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->no_identitas }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Nama Yayasan Masar</label>
                            <p class="text-muted mb-0">{{ textCamelCase($yayasan_masar->nama) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tempat Lahir</label>
                            <p class="text-muted mb-0">{{ textCamelCase($yayasan_masar->tempat_lahir) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Lahir</label>
                            <p class="text-muted mb-0">{{ !empty($yayasan_masar->tanggal_lahir) ? DateToIndo($yayasan_masar->tanggal_lahir) : '-' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jenis Kelamin</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->jenis_kelamin == 'L' ? 'Laki - Laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Status Kawin</label>
                            <p class="text-muted mb-0">{{ !empty($yayasan_masar->status_kawin) ? $yayasan_masar->status_kawin : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. Identitas</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->no_identitas }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">No. HP</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->no_hp }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alamat</label>
                            <p class="text-muted mb-0">{{ textCamelCase($yayasan_masar->alamat) }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pendidikan Terakhir</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->pendidikan_terakhir }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Masuk</label>
                            <p class="text-muted mb-0">{{ !empty($yayasan_masar->tanggal_masuk) ? DateToIndo($yayasan_masar->tanggal_masuk) : '-' }}</p>
                        </div>
                        @if (!empty($yayasan_masar->nama_jabatan))
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jabatan</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->nama_jabatan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @if (!empty($yayasan_masar->nama_cabang) || !empty($yayasan_masar->nama_dept))
                <hr>
                <div class="row">
                    @if (!empty($yayasan_masar->nama_cabang))
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Kantor</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->nama_cabang }}</p>
                        </div>
                    </div>
                    @endif
                    @if (!empty($yayasan_masar->nama_dept))
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Departemen</label>
                            <p class="text-muted mb-0">{{ $yayasan_masar->nama_dept }}</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif
                @if ($yayasan_masar->status_aktif === '0')
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Nonaktif</label>
                            <p class="text-muted mb-0">{{ !empty($yayasan_masar->tanggal_nonaktif) ? DateToIndo($yayasan_masar->tanggal_nonaktif) : '-' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal Off Gaji</label>
                            <p class="text-muted mb-0">{{ !empty($yayasan_masar->tanggal_off_gaji) ? DateToIndo($yayasan_masar->tanggal_off_gaji) : '-' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        <!--/ Activity Timeline -->
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" size="modal-lg" />
<!--/ User Profile Content -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnHapusSemua = document.getElementById('btnHapusSemuaWajah');
        if (btnHapusSemua) {
            btnHapusSemua.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Yakin ingin menghapus SEMUA data wajah Yayasan Masar ini?')) {
                    document.getElementById('formHapusSemuaWajah').submit();
                }
            });
        }
    });
</script>

<!-- Modal Foto Wajah -->
<div class="modal fade" id="modalFotoWajah" tabindex="-1" aria-labelledby="modalFotoWajahLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalFotoWajahLabel">Foto Wajah</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Foto Wajah">
            </div>
        </div>
    </div>
</div>

@endsection
@push('myscript')
<script>
    $("#btnAddface").click(function(e) {
        e.preventDefault();
        $('#modal').modal("show");
        $('#modal').find(".modal-title").text("Tambah Wajah");
        $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        $("#loadmodal").load('/facerecognition/' + '{{ Crypt::encrypt($yayasan_masar->kode_yayasan) }}' + '/create');
    });

    // Event listener untuk modal foto wajah
    document.addEventListener('DOMContentLoaded', function() {
        const modalFotoWajah = document.getElementById('modalFotoWajah');
        if (modalFotoWajah) {
            modalFotoWajah.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const imageUrl = button.getAttribute('data-image');

                const modalImage = this.querySelector('#modalImage');
                modalImage.src = imageUrl;
            });
        }
    });
</script>
@endpush
