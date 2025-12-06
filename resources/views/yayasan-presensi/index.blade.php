@extends('layouts.app')
@section('titlepage', 'Monitoring Presensi Yayasan')

@section('content')
@section('navigasi')
    <span>Monitoring Presensi Yayasan</span>
@endsection
<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('yayasan-presensi.index') }}">
                            <x-input-with-icon label="Tanggal" value="{{ Request('tanggal') }}" name="tanggal" icon="ti ti-calendar"
                                datepicker="flatpickr-date" />
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" textShow="nama_cabang"
                                        selected="{{ Request('kode_cabang') }}" upperCase="true" select2="select2Kodecabangsearch" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Yayasan" value="{{ Request('nama') }}" name="nama"
                                        icon="ti ti-search" />
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col">
                                    <button class="btn btn-primary w-100"><i class="ti ti-icons ti-search me-1"></i>Cari</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-12">
                        <button class="btn btn-info w-100 btngetDatamesinAll" tanggal="{{ !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d') }}">
                            <i class="ti ti-download me-2"></i>Get Data Presensi - Semua Jamaah
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-bordered">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama Yayasan</th>
                                        <th>Dept</th>
                                        <th>Cbg</th>
                                        <th>Jam Kerja</th>
                                        <th>Status</th>
                                        <th class="text-center">Jam Masuk</th>
                                        <th class="text-center">Jam Pulang</th>
                                        <th class="text-center">Status</th>
                                        {{-- <th class="text-center">Keluar</th> --}}
                                        <th class="text-center">Terlambat</th>
                                        <th>Denda</th>
                                        <th>POT. JAM</th>
                                        {{-- <th class="text-center">Total</th> --}}
                                        <th class="text-center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($yayasan as $d)
                                        @php
                                            $tanggal_presensi = !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d');
                                            $jam_masuk = $tanggal_presensi . ' ' . $d->jam_masuk;
                                            $terlambat = hitungjamterlambat($d->jam_in, $jam_masuk);
                                            $potongan_tidak_hadir = $d->status == 'a' ? $d->total_jam : 0;
                                            $pulangcepat = hitungpulangcepat(
                                                $tanggal_presensi,
                                                $d->jam_out,
                                                $d->jam_pulang,
                                                $d->istirahat,
                                                $d->jam_awal_istirahat,
                                                $d->jam_akhir_istirahat,
                                                $d->lintashari,
                                            );
                                        @endphp
                                        @if ($terlambat != null)
                                            @if ($terlambat['desimal_terlambat'] < 1)
                                                @php
                                                    $potongan_jam_terlambat = 0;
                                                    $denda = hitungdenda($denda_list, $terlambat['menitterlambat']);
                                                @endphp
                                            @else
                                                @php
                                                    $potongan_jam_terlambat = $terlambat['desimal_terlambat'];
                                                    $denda = 0;
                                                @endphp
                                            @endif
                                        @else
                                            @php
                                                $potongan_jam_terlambat = 0;
                                                $denda = 0;
                                            @endphp
                                        @endif
                                        <tr>
                                            <td>{{ $d->kode_yayasan }}</td>
                                            <td>{{ $d->nama }}</td>
                                            <td>{{ $d->kode_dept }}</td>
                                            <td>{{ $d->kode_cabang }}</td>
                                            <td>
                                                @if ($d->kode_jam_kerja != null)
                                                    {{ $d->nama_jam_kerja }} {{ date('H:i', strtotime($d->jam_masuk)) }} -
                                                    {{ date('H:i', strtotime($d->jam_pulang)) }}
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($d->status == 'h')
                                                    <span class="badge bg-success">H</span>
                                                @elseif($d->status == 'i')
                                                    <span class="badge bg-info">I</span>
                                                @elseif($d->status == 's')
                                                    <span class="badge bg-warning">S</span>
                                                @elseif($d->status == 'a')
                                                    <span class="badge bg-danger">A</span>
                                                @elseif($d->status == 'c')
                                                    <span class="badge bg-primary">C</span>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex items-center justify-content-center gap-2">
                                                    @if ($d->jam_in != null)
                                                        @if (!empty($d->foto_in))
                                                            @if (Storage::disk('public')->exists('/uploads/absensi/yayasan/' . $d->foto_in))
                                                                <div class="avatar avatar-xs">
                                                                    <img src="{{ url('/storage/uploads/absensi/yayasan/' . $d->foto_in) }}" alt=""
                                                                        class="rounded-circle">
                                                                </div>
                                                            @else
                                                                <div class="avatar avatar-xs">
                                                                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                                        alt="" class="rounded-circle">
                                                                </div>
                                                            @endif
                                                        @else
                                                            <i class="ti ti-fingerprint"></i>
                                                        @endif
                                                        <a href="#" class="btnShowpresensi_in" id="{{ $d->id }}" status="in">
                                                            {{ date('H:i', strtotime($d->jam_in)) }}
                                                        </a>
                                                        <span class="text-danger">
                                                            @if ($potongan_jam_terlambat > 0)
                                                                (-{{ $potongan_jam_terlambat }})
                                                            @endif
                                                        </span>
                                                    @else
                                                        <i class="ti ti-hourglass-low text-warning"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex items-center justify-content-center gap-2">
                                                    @if ($d->jam_out != null)
                                                        @if (!empty($d->foto_out))
                                                            @if (Storage::disk('public')->exists('/uploads/absensi/yayasan/' . $d->foto_out))
                                                                <div class="avatar avatar-xs">
                                                                    <img src="{{ url('/storage/uploads/absensi/yayasan/' . $d->foto_out) }}" alt=""
                                                                        class="rounded-circle">
                                                                </div>
                                                            @else
                                                                <div class="avatar avatar-xs">
                                                                    <img src="{{ asset('assets/img/avatars/No_Image_Available.jpg') }}"
                                                                        alt="" class="rounded-circle">
                                                                </div>
                                                            @endif
                                                        @else
                                                            <i class="ti ti-fingerprint"></i>
                                                        @endif
                                                        <a href="#" class="btnShowpresensi_out" id="{{ $d->id }}" status="out">
                                                            {{ date('H:i', strtotime($d->jam_out)) }}
                                                        </a>
                                                        <span class="text-danger">
                                                            @if ($pulangcepat > 0)
                                                                (-{{ $pulangcepat }})
                                                            @endif
                                                        </span>
                                                    @else
                                                        <i class="ti ti-hourglass-low text-warning"></i>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if ($d->status == 'h')
                                                    <span class="badge bg-success">H</span>
                                                @elseif($d->status == 'i')
                                                    <span class="badge bg-info">I</span>
                                                @elseif($d->status == 's')
                                                    <span class="badge bg-warning">S</span>
                                                @elseif($d->status == 'a')
                                                    <span class="badge bg-danger">A</span>
                                                @else
                                                    <i class="ti ti-hourglass-low text-warning"></i>
                                                @endif
                                            </td>
                                            <td class="text-center">

                                                {!! $terlambat != null ? $terlambat['show'] : '<i class="ti ti-hourglass-low text-warning"></i>' !!}
                                            </td>
                                            <td class="text-end">

                                                {{ formatAngka($denda) }}
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $total_potongan_jam = $pulangcepat + $potongan_jam_terlambat + $potongan_tidak_hadir;
                                                @endphp
                                                @if ($total_potongan_jam > 0)
                                                    <span class="badge bg-danger">
                                                        {{ formatAngkaDesimal($total_potongan_jam) }}
                                                    </span>
                                                @endif

                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="#" class="me-1 koreksiPresensi" kode_yayasan="{{ Crypt::encrypt($d->kode_yayasan) }}"
                                                        tanggal="{{ $tanggal_presensi }}"><i class="ti ti-edit text-success"></i></a>

                                                    <a href="#" class="btngetDatamesin" pin="{{ $d->pin }}"
                                                        tanggal="{{ !empty(Request('tanggal')) ? Request('tanggal') : date('Y-m-d') }}"> <i
                                                            class="ti ti-device-desktop text-primary"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                        <div style="float: right;">
                            {{ $yayasan->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" title="" />
@endsection
@push('myscript')
<script>
    $(function() {
        $(document).on('click', '.koreksiPresensi', function() {
            let kode_yayasan = $(this).attr('kode_yayasan');
            let tanggal = $(this).attr('tanggal');
            $.ajax({
                type: 'POST',
                url: "{{ route('yayasan-presensi.edit') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    kode_yayasan: kode_yayasan,
                    tanggal: tanggal
                },
                cache: false,
                success: function(res) {
                    $('#modal').modal('show');
                    $('#modal').find('.modal-title').text('Koreksi Presensi Yayasan');
                    $('#loadmodal').html(res);
                }
            });
        });




        $(".btnShowpresensi_in, .btnShowpresensi_out").click(function(e) {
            e.preventDefault();
            const id = $(this).attr("id");
            const status = $(this).attr("status");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
                <div class="sk-wave-rect"></div>
            </div>`);
            //alert(kode_jadwal);
            $("#modal").modal("show");
            $(".modal-title").text("Data Presensi Yayasan");
            $("#loadmodal").load(`/yayasan-presensi/${id}/${status}/show`);
        });

        $(".btngetDatamesin").click(function(e) {
            e.preventDefault();
            var pin = $(this).attr("pin");
            var tanggal = $(this).attr("tanggal");
            // var kode_jadwal = $(this).attr("kode_jadwal");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            //alert(kode_jadwal);
            $("#modal").modal("show");
            $(".modal-title").text("Get Data Mesin Yayasan");
            $.ajax({
                type: 'POST',
                url: '/yayasan-presensi/getdatamesin',
                data: {
                    _token: "{{ csrf_token() }}",
                    pin: pin,
                    tanggal: tanggal,
                    // kode_jadwal: kode_jadwal
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#loadmodal").html(respond);
                }
            });
        });

        // Get data dari mesin untuk SEMUA jamaah sekaligus
        $(".btngetDatamesinAll").click(function(e) {
            e.preventDefault();
            var tanggal = $(this).attr("tanggal");
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
            $("#modal").modal("show");
            $(".modal-title").text("Get Data Mesin - Semua Jamaah");
            $.ajax({
                type: 'POST',
                url: '/yayasan-presensi/getdatamesinall',
                data: {
                    _token: "{{ csrf_token() }}",
                    tanggal: tanggal
                },
                cache: false,
                success: function(respond) {
                    console.log(respond);
                    $("#loadmodal").html(respond);
                },
                error: function(err) {
                    $("#loadmodal").html('<div class="alert alert-danger"><i class="ti ti-alert-circle me-2"></i>Terjadi kesalahan saat mengambil data dari mesin.</div>');
                    console.error(err);
                }
            });
        });
    });
</script>
@endpush
