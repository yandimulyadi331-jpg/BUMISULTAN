@extends('layouts.app')
@section('titlepage', 'Potongan Pinjaman Payroll')

@section('content')
@section('navigasi')
    <span>Potongan Pinjaman Payroll</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="#" class="btn btn-primary" id="btnCreate">
                    <i class="fa fa-plus me-2"></i> Tambah Potongan Pinjaman
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('potongan_pinjaman_master.index') }}">
                            <div class="row">
                                <div class="col-lg-4 col-sm-12 col-md-12">
                                    <x-input-with-icon label="Cari Nama Karyawan" value="{{ Request('nama_karyawan') }}" 
                                        name="nama_karyawan" icon="ti ti-search" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Cabang" name="kode_cabang" :data="$cabang" key="kode_cabang" 
                                        textShow="nama_cabang" selected="{{ Request('kode_cabang') }}" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <x-select label="Departemen" name="kode_dept" :data="$departemen" key="kode_dept" 
                                        textShow="nama_dept" selected="{{ Request('kode_dept') }}" upperCase="true" />
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Semua</option>
                                            <option value="aktif" {{ Request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                            <option value="selesai" {{ Request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="ditunda" {{ Request('status') == 'ditunda' ? 'selected' : '' }}>Ditunda</option>
                                            <option value="dibatalkan" {{ Request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-12 col-md-12">
                                    <label class="form-label">&nbsp;</label>
                                    <button class="btn btn-primary w-100">
                                        <i class="ti ti-search me-1"></i>Cari
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Dept</th>
                                        <th>Cabang</th>
                                        <th class="text-end">Total Pinjaman</th>
                                        <th class="text-end">Cicilan/Bulan</th>
                                        <th>Periode</th>
                                        <th>Progress</th>
                                        <th>Status</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($potongan as $d)
                                        <tr>
                                            <td>{{ $d->kode_potongan }}</td>
                                            <td>{{ $d->karyawan->nik_show ?? $d->nik }}</td>
                                            <td><strong>{{ $d->karyawan->nama_karyawan ?? 'N/A' }}</strong></td>
                                            <td>{{ $d->karyawan->kode_dept ?? '-' }}</td>
                                            <td>{{ $d->karyawan->kode_cabang ?? '-' }}</td>
                                            <td class="text-end">{{ formatAngka($d->jumlah_pinjaman) }}</td>
                                            <td class="text-end">{{ formatAngka($d->cicilan_per_bulan) }}</td>
                                            <td>
                                                <small>
                                                    {{ config('global.nama_bulan')[$d->bulan_mulai] }} {{ $d->tahun_mulai }} - 
                                                    {{ config('global.nama_bulan')[$d->bulan_selesai] }} {{ $d->tahun_selesai }}
                                                </small>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <small class="me-2">{{ $d->progress_text }}</small>
                                                    <div class="progress" style="width: 80px; height: 20px;">
                                                        <div class="progress-bar 
                                                            @if($d->progress_percentage >= 100) bg-success
                                                            @elseif($d->progress_percentage >= 50) bg-info
                                                            @else bg-warning
                                                            @endif" 
                                                            role="progressbar" 
                                                            style="width: {{ min($d->progress_percentage, 100) }}%"
                                                            aria-valuenow="{{ $d->progress_percentage }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <small class="ms-2">{{ number_format($d->progress_percentage, 0) }}%</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($d->status == 'aktif')
                                                    <span class="badge bg-success">Aktif</span>
                                                @elseif($d->status == 'selesai')
                                                    <span class="badge bg-primary">Selesai</span>
                                                @elseif($d->status == 'ditunda')
                                                    <span class="badge bg-warning">Ditunda</span>
                                                @else
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('potongan_pinjaman.edit')
                                                        <a href="#" class="btnEdit me-1" 
                                                            data-id="{{ Crypt::encrypt($d->id) }}">
                                                            <i class="ti ti-edit text-success"></i>
                                                        </a>
                                                    @endcan
                                                    @can('potongan_pinjaman.delete')
                                                        <div>
                                                            <form method="POST" class="deleteform"
                                                                action="{{ route('potongan_pinjaman_master.delete', Crypt::encrypt($d->id)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        {{ $potongan->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" size="modal-lg" show="loadmodal" />
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
        }

        $("#btnCreate").click(function(e) {
            e.preventDefault();
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Potongan Pinjaman");
            loading();
            $("#loadmodal").load("{{ route('potongan_pinjaman_master.create') }}");
        });

        $(".btnEdit").click(function(e) {
            e.preventDefault();
            const id = $(this).data("id");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Potongan Pinjaman");
            loading();
            $("#loadmodal").load(`/payroll/potongan-pinjaman-master/${id}/edit`);
        });
    });
</script>
@endpush
