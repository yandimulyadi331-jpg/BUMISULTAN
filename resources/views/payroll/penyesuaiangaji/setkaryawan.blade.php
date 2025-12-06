@extends('layouts.app')
@section('titlepage', 'Set Penyesuaian Gaji Karyawan')

@section('content')
@section('navigasi')
    <span>Set Penyesuaian Gaji Karyawan</span>
@endsection

<!-- Notifikasi Success/Error -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="fa fa-check-circle me-2"></i>
    <strong>Berhasil!</strong> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="fa fa-exclamation-circle me-2"></i>
    <strong>Error!</strong> {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-lg-8 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <a href="#" class="btn btn-primary" id="btnCreate">
                        <i class="fa fa-plus me-2"></i> Tambah Karyawan
                    </a>
                </div>
                <div>
                    <form method="POST" action="{{ route('penyesuaiangaji.generatePotonganPinjaman', Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji)) }}" class="d-inline" id="formGeneratePinjaman">
                        @csrf
                        <button type="button" class="btn btn-warning" id="btnGeneratePinjaman">
                            <i class="fa fa-bolt me-2"></i> Generate Potongan Pinjaman
                        </button>
                    </form>
                    <a href="{{ route('penyesuaiangaji.summaryPinjaman', Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji)) }}" 
                       class="btn btn-info">
                        <i class="fa fa-list me-2"></i> Summary Pinjaman
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col">
                        <table class="table">
                            <tr>
                                <td>Bulan</td>
                                <td class="text-end">{{ getNamabulan($penyesuaiangaji->bulan) }}</td>
                            </tr>
                            <tr>
                                <td>Tahun</td>
                                <td class="text-end">{{ $penyesuaiangaji->tahun }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table  table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Penambah</th>
                                        <th>Pengurang</th>
                                        <th>Keterangan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($detailpenyesuaian as $d)
                                        <tr>
                                            <td>{{ $d->nik_show ?? $d->nik }}</td>
                                            <td>{{ $d->nama_karyawan }}</td>
                                            <td class="text-end">{{ formatAngka($d->penambah) }}</td>
                                            <td class="text-end">{{ formatAngka($d->pengurang) }}</td>
                                            <td>{{ $d->keterangan }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="#" class="btnEdit me-1" nik="{{ Crypt::encrypt($d->nik) }}"><i
                                                            class="ti ti-edit text-success"></i></a>
                                                    <div>
                                                        <form method="POST" name="deleteform" class="deleteform"
                                                            action="{{ route('penyesuaiangaji.deletekaryawan', [Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji), Crypt::encrypt($d->nik)]) }}">
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

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
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
            $(".modal-title").text("Tambah Karyawan");
            $("#loadmodal").load(
                "{{ route('penyesuaiangaji.addkaryawan', Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji)) }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const kode_penyesuaian_gaji = "{{ Crypt::encrypt($penyesuaiangaji->kode_penyesuaian_gaji) }}";
            const nik = $(this).attr("nik");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Penyesuaian Gaji");
            $("#loadmodal").load(`/penyesuaiangaji/${kode_penyesuaian_gaji}/${nik}/editkaryawan`);
        });

        // Generate Potongan Pinjaman dengan SweetAlert
        $("#btnGeneratePinjaman").click(function() {
            Swal.fire({
                title: 'Generate Potongan Pinjaman?',
                text: 'System akan otomatis mendeteksi cicilan yang jatuh tempo di periode ini dan menambahkan ke penyesuaian gaji.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Generate!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading
                    Swal.fire({
                        title: 'Memproses...',
                        text: 'Sedang generate potongan pinjaman',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    // Submit form
                    $("#formGeneratePinjaman").submit();
                }
            });
        });
    });
</script>
@endpush
