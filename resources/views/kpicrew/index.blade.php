@extends('layouts.app')
@section('titlepage', 'KPI Crew - Key Performance Indicator')

@section('content')
@section('navigasi')
    <span>KPI Crew</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <!-- Statistik Cards -->
        <div class="row mb-4">
            <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Total Karyawan</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h3 class="mb-0 me-2">{{ $totalKaryawan }}</h3>
                                    <small class="text-muted">Karyawan</small>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-users ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Rata-rata Point</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h3 class="mb-0 me-2">{{ number_format($avgTotalPoint ?? 0, 1) }}</h3>
                                    <small class="text-muted">Point</small>
                                </div>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-chart-bar ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-4 col-md-4 col-sm-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="content-left">
                                <span>Top Performer</span>
                                <div class="d-flex align-items-end mt-2">
                                    <h6 class="mb-0 me-2">{{ ($topPerformer && $topPerformer->karyawan) ? $topPerformer->karyawan->nama_karyawan : '-' }}</h6>
                                </div>
                                @if($topPerformer && $topPerformer->karyawan)
                                    <small class="text-success">{{ number_format($topPerformer->total_point, 1) }} Point</small>
                                @endif
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-trophy ti-sm"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Card -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Data KPI Crew</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#filterModal">
                        <i class="ti ti-filter me-1"></i>Filter Periode
                    </button>
                    <form action="{{ route('kpicrew.recalculate') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="bulan" value="{{ $bulan }}">
                        <input type="hidden" name="tahun" value="{{ $tahun }}">
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Hitung ulang KPI untuk periode ini?')">
                            <i class="ti ti-refresh me-1"></i>Hitung Ulang
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body">
                <!-- Periode Info -->
                <div class="alert alert-primary" role="alert">
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <i class="ti ti-calendar-event ti-sm"></i>
                        </div>
                        <div class="flex-grow-1 ms-2">
                            <strong>Periode: </strong>{{ $bulanList[$bulan] }} {{ $tahun }}
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table table-hover table-bordered" id="kpiTable">
                        <thead class="table-dark">
                            <tr>
                                <th class="text-center" width="60">Rank</th>
                                <th>NIK</th>
                                <th>Nama Karyawan</th>
                                <th>Dept</th>
                                <th class="text-center">Kehadiran</th>
                                <th class="text-center">Aktivitas</th>
                                <th class="text-center">Perawatan</th>
                                <th class="text-center">Total Point</th>
                                <th class="text-center" width="100">#</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kpiData as $index => $kpi)
                                @if($kpi->karyawan)
                                <tr>
                                    <td class="text-center">
                                        @if($kpi->ranking == 1)
                                            <span class="badge bg-label-warning">
                                                <i class="ti ti-trophy"></i> {{ $kpi->ranking }}
                                            </span>
                                        @elseif($kpi->ranking == 2)
                                            <span class="badge bg-label-info">
                                                <i class="ti ti-medal"></i> {{ $kpi->ranking }}
                                            </span>
                                        @elseif($kpi->ranking == 3)
                                            <span class="badge bg-label-success">
                                                <i class="ti ti-award"></i> {{ $kpi->ranking }}
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary">{{ $kpi->ranking }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $kpi->karyawan->nik }}</td>
                                    <td>{{ $kpi->karyawan->nama_karyawan }}</td>
                                    <td>{{ $kpi->karyawan->kode_dept }}</td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-label-primary mb-1">{{ $kpi->kehadiran_count }}x</span>
                                            <small class="text-muted">{{ number_format($kpi->kehadiran_point, 0) }} pt</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-label-info mb-1">{{ $kpi->aktivitas_count }}x</span>
                                            <small class="text-muted">{{ number_format($kpi->aktivitas_point, 0) }} pt</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span class="badge bg-label-success mb-1">{{ $kpi->perawatan_count }}x</span>
                                            <small class="text-muted">{{ number_format($kpi->perawatan_point, 0) }} pt</small>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <strong class="text-primary">{{ number_format($kpi->total_point, 1) }}</strong>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('kpicrew.show', ['nik' => $kpi->nik, 'bulan' => $bulan, 'tahun' => $tahun]) }}" 
                                           class="btn btn-sm btn-info">
                                            <i class="ti ti-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data KPI untuk periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Keterangan Point -->
                <div class="mt-3">
                    <div class="alert alert-info">
                        <h6 class="alert-heading mb-2">Keterangan Perhitungan Point:</h6>
                        <ul class="mb-0">
                            <li><strong>Kehadiran:</strong> 4 point per kehadiran (max 100 point untuk 25 hari)</li>
                            <li><strong>Aktivitas:</strong> 3 point per aktivitas yang diupload</li>
                            <li><strong>Perawatan:</strong> 2 point per checklist perawatan yang diselesaikan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Filter Periode -->
<div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Filter Periode</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('kpicrew.index') }}" method="GET">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Bulan</label>
                            <select name="bulan" class="form-select" required>
                                @foreach($bulanList as $key => $namaBulan)
                                    <option value="{{ $key }}" {{ $bulan == $key ? 'selected' : '' }}>
                                        {{ $namaBulan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tahun</label>
                            <select name="tahun" class="form-select" required>
                                @foreach($tahunList as $thn)
                                    <option value="{{ $thn }}" {{ $tahun == $thn ? 'selected' : '' }}>
                                        {{ $thn }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#kpiTable').DataTable({
            "pageLength": 25,
            "ordering": false,
            "searching": true,
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
                "infoEmpty": "Tidak ada data tersedia",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });

        // Auto dismiss alert
        setTimeout(function() {
            $('.alert-success, .alert-danger').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush
