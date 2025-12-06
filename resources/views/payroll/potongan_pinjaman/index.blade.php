@extends('layouts.app')
@section('titlepage', 'Potongan Pinjaman Payroll')

@section('navigasi')
    <span>Potongan Pinjaman Payroll</span>
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

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-money-bill-wave"></i> Potongan Pinjaman Payroll
                    </h5>
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
                                <i class="fa fa-cog"></i> Generate Potongan
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

                <!-- Detail Potongan Per Karyawan -->
                @if($potongan->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th class="text-center">Jumlah Cicilan</th>
                                <th class="text-end">Total Potongan</th>
                                <th>Detail Pinjaman</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($potongan as $nik => $items)
                            @php
                                $karyawan = $items->first()->karyawan;
                                $totalPotongan = $items->sum('jumlah_potongan');
                            @endphp
                            <tr>
                                <td>{{ $karyawan->nik_show ?? $nik }}</td>
                                <td><strong>{{ $karyawan->nama_karyawan ?? 'N/A' }}</strong></td>
                                <td class="text-center">
                                    <span class="badge bg-info">{{ $items->count() }}</span>
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($totalPotongan, 0, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0">
                                        @foreach($items as $item)
                                        <li class="mb-1">
                                            <small>
                                                <i class="fa fa-circle {{ $item->status == 'dipotong' ? 'text-success' : ($item->status == 'pending' ? 'text-warning' : 'text-danger') }}"></i>
                                                <strong>{{ $item->pinjaman->nomor_pinjaman }}</strong> - 
                                                Cicilan ke-{{ $item->cicilan_ke }}
                                                <span class="text-muted">(Rp {{ number_format($item->jumlah_potongan, 0, ',', '.') }})</span>
                                                <br>
                                                <span class="text-muted ms-3">
                                                    JT: {{ $item->tanggal_jatuh_tempo->format('d M Y') }}
                                                    @if($item->tanggal_dipotong)
                                                        | Dipotong: {{ $item->tanggal_dipotong->format('d M Y') }}
                                                    @endif
                                                </span>
                                            </small>
                                        </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>
                                    @php
                                        $statusCount = $items->groupBy('status');
                                    @endphp
                                    @foreach($statusCount as $status => $statusItems)
                                        <span class="badge bg-{{ $status == 'dipotong' ? 'success' : ($status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ strtoupper($status) }}: {{ $statusItems->count() }}
                                        </span>
                                    @endforeach
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <th colspan="2" class="text-end">GRAND TOTAL</th>
                                <th class="text-center">
                                    <span class="badge bg-primary">{{ $potongan->flatten()->count() }}</span>
                                </th>
                                <th class="text-end">
                                    <strong>Rp {{ number_format($potongan->flatten()->sum('jumlah_potongan'), 0, ',', '.') }}</strong>
                                </th>
                                <th colspan="2"></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Belum ada data potongan pinjaman untuk periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong>.
                    <br>Klik tombol <strong>"Generate Potongan"</strong> untuk membuat data potongan dari cicilan yang jatuh tempo.
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Hidden Forms -->
<form id="formGenerate" method="POST" action="{{ route('potongan_pinjaman.generate') }}">
    @csrf
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

<form id="formProses" method="POST" action="{{ route('potongan_pinjaman.proses') }}">
    @csrf
    <input type="hidden" name="bulan" value="{{ $bulan }}">
    <input type="hidden" name="tahun" value="{{ $tahun }}">
</form>

<form id="formDelete" method="POST" action="{{ route('potongan_pinjaman.deletePeriode') }}">
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
            title: 'Generate Potongan Pinjaman?',
            html: 'System akan mendeteksi semua cicilan yang jatuh tempo di periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong> dan membuat data potongan.',
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
                    text: 'Sedang generate potongan pinjaman',
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
            html: 'Semua data potongan untuk periode <strong>{{ $nama_bulan[$bulan] }} {{ $tahun }}</strong> akan dihapus dan cicilan akan di-reset.',
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
