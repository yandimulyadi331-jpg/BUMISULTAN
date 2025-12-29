@extends('layouts.app')
@section('titlepage', 'Proses Potongan Pinjaman Bulanan')

@section('navigasi')
    <span>Proses Potongan Pinjaman Bulanan</span>
@endsection

@section('content')
<!-- Alert Messages -->
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

@if(session('warning'))
<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <i class="fa fa-exclamation-triangle me-2"></i>
    <strong>Perhatian!</strong> {{ session('warning') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-calendar-check"></i> Proses Potongan Pinjaman Bulanan
                    </h5>
                    <a href="{{ route('potongan_pinjaman_master.index') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter Periode -->
                <form method="GET" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label">Bulan</label>
                        <select name="bulan" class="form-select">
                            @foreach($nama_bulan as $index => $nama)
                            @if($index > 0)
                            <option value="{{ $index }}" {{ $bulan == $index ? 'selected' : '' }}>
                                {{ $nama }}
                            </option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tahun</label>
                        <select name="tahun" class="form-select">
                            @for($y = date('Y'); $y >= $start_year; $y--)
                            <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">&nbsp;</label>
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-search"></i> Tampilkan
                            </button>
                            <button type="button" class="btn btn-success" id="btnGenerate">
                                <i class="fa fa-cog"></i> Generate Detail
                            </button>
                            <button type="button" class="btn btn-warning" id="btnProses">
                                <i class="fa fa-check"></i> Proses Potongan
                            </button>
                            <button type="button" class="btn btn-danger" id="btnDelete">
                                <i class="fa fa-trash"></i> Hapus Periode Ini
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Summary Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center">
                                <h6>Pending</h6>
                                <h3>{{ $summary->get('pending')->total_cicilan ?? 0 }}</h3>
                                <small>Rp {{ number_format($summary->get('pending')->total_potongan ?? 0, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center">
                                <h6>Dipotong</h6>
                                <h3>{{ $summary->get('dipotong')->total_cicilan ?? 0 }}</h3>
                                <small>Rp {{ number_format($summary->get('dipotong')->total_potongan ?? 0, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center">
                                <h6>Batal</h6>
                                <h3>{{ $summary->get('batal')->total_cicilan ?? 0 }}</h3>
                                <small>Rp {{ number_format($summary->get('batal')->total_potongan ?? 0, 0, ',', '.') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center">
                                <h6>Total Karyawan</h6>
                                <h3>{{ $summary->sum('total_karyawan') }}</h3>
                                <small>Periode {{ $nama_bulan[$bulan] }} {{ $tahun }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Potongan -->
                @if($details->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Kode Master</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th class="text-center">Cicilan Ke</th>
                                <th class="text-end">Jumlah Potongan</th>
                                <th>Status</th>
                                <th>Tanggal Dipotong</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($details as $detail)
                            <tr>
                                <td>{{ $detail->master->kode_potongan }}</td>
                                <td>{{ $detail->master->karyawan->nik_show ?? $detail->master->nik }}</td>
                                <td><strong>{{ $detail->master->karyawan->nama_karyawan ?? 'N/A' }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-info">
                                        {{ $detail->cicilan_ke }} / {{ $detail->master->jumlah_bulan }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($detail->jumlah_potongan, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    @if($detail->status == 'pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($detail->status == 'dipotong')
                                        <span class="badge bg-success">Dipotong</span>
                                    @else
                                        <span class="badge bg-danger">Batal</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $detail->tanggal_dipotong ? $detail->tanggal_dipotong->format('d-m-Y') : '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="4" class="text-end">GRAND TOTAL</th>
                                <th class="text-end">
                                    <strong>Rp {{ number_format($details->sum('jumlah_potongan'), 0, ',', '.') }}</strong>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Belum ada data detail potongan untuk periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong>.
                    <br>Klik tombol <strong>"Generate Detail"</strong> untuk membuat data potongan dari master yang aktif.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="formGenerate" method="POST" action="{{ route('potongan_pinjaman_master.generateDetail') }}">
    @csrf
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

<form id="formProses" method="POST" action="{{ route('potongan_pinjaman_master.prosesPotongan') }}">
    @csrf
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

<form id="formDelete" method="POST" action="{{ route('potongan_pinjaman_master.deletePeriode') }}">
    @csrf
    @method('DELETE')
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

@endsection

@push('myscript')
<script>
$(document).ready(function() {
    // Generate Button
    $('#btnGenerate').click(function() {
        Swal.fire({
            title: 'Generate Detail Potongan?',
            html: 'System akan membuat detail potongan untuk semua master yang aktif di periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong>.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Generate!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...',
                    text: 'Sedang generate detail potongan',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                $('#formGenerate').submit();
            }
        });
    });

    // Proses Button
    $('#btnProses').click(function() {
        Swal.fire({
            title: 'Proses Potongan?',
            html: 'Semua potongan dengan status <strong>PENDING</strong> akan diubah menjadi <strong>DIPOTONG</strong> dan akan masuk ke slip gaji karyawan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Proses!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formProses').submit();
            }
        });
    });

    // Delete Button
    $('#btnDelete').click(function() {
        Swal.fire({
            title: 'Hapus Semua Data?',
            html: 'Semua data detail potongan untuk periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong> akan dihapus.',
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $('#formDelete').submit();
            }
        });
    });
});
</script>
@endpush
