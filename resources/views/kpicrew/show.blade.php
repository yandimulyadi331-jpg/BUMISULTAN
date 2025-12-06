@extends('layouts.app')
@section('titlepage', 'Detail KPI - ' . $kpi->karyawan->nama_karyawan)

@section('content')
@section('navigasi')
    <span><a href="{{ route('kpicrew.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}">KPI Crew</a></span>
    <i class="ti ti-chevron-right"></i>
    <span>Detail</span>
@endsection

<div class="row">
    <div class="col-lg-12">
        <!-- Karyawan Info -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <h4 class="mb-3">{{ $kpi->karyawan->nama_karyawan }}</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="120"><strong>NIK</strong></td>
                                        <td>: {{ $kpi->karyawan->nik }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Departemen</strong></td>
                                        <td>: {{ $kpi->karyawan->kode_dept }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Cabang</strong></td>
                                        <td>: {{ $kpi->karyawan->kode_cabang }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td width="120"><strong>Periode</strong></td>
                                        <td>: {{ $kpi->periode_text }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Ranking</strong></td>
                                        <td>
                                            : @if($kpi->ranking == 1)
                                                <span class="badge bg-label-warning">
                                                    <i class="ti ti-trophy"></i> Peringkat {{ $kpi->ranking }}
                                                </span>
                                            @elseif($kpi->ranking == 2)
                                                <span class="badge bg-label-info">
                                                    <i class="ti ti-medal"></i> Peringkat {{ $kpi->ranking }}
                                                </span>
                                            @elseif($kpi->ranking == 3)
                                                <span class="badge bg-label-success">
                                                    <i class="ti ti-award"></i> Peringkat {{ $kpi->ranking }}
                                                </span>
                                            @else
                                                <span class="badge bg-label-secondary">Peringkat {{ $kpi->ranking }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="p-3 border rounded bg-light">
                            <h6 class="mb-2">Total Point</h6>
                            <h1 class="text-primary mb-0">{{ number_format($kpi->total_point, 1) }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">Kehadiran</h6>
                                <small class="text-muted">4 point per kehadiran</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-primary">
                                    <i class="ti ti-calendar-check ti-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-2">{{ $kpi->kehadiran_count }}</h3>
                            <small class="text-muted">kali hadir</small>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-primary">{{ number_format($kpi->kehadiran_point, 0) }} Point</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">Aktivitas</h6>
                                <small class="text-muted">3 point per aktivitas</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-info">
                                    <i class="ti ti-activity ti-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-2">{{ $kpi->aktivitas_count }}</h3>
                            <small class="text-muted">aktivitas</small>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-info">{{ number_format($kpi->aktivitas_point, 0) }} Point</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <h6 class="mb-0">Perawatan</h6>
                                <small class="text-muted">2 point per checklist</small>
                            </div>
                            <div class="avatar">
                                <span class="avatar-initial rounded bg-label-success">
                                    <i class="ti ti-checkbox ti-sm"></i>
                                </span>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <h3 class="mb-0 me-2">{{ $kpi->perawatan_count }}</h3>
                            <small class="text-muted">checklist</small>
                        </div>
                        <div class="mt-2">
                            <span class="badge bg-success">{{ number_format($kpi->perawatan_point, 0) }} Point</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs untuk Detail Data -->
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#kehadiran" aria-controls="kehadiran" aria-selected="true">
                            <i class="ti ti-calendar-check me-1"></i> Detail Kehadiran ({{ $kehadiranDetail->count() }})
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#aktivitas" aria-controls="aktivitas" aria-selected="false">
                            <i class="ti ti-activity me-1"></i> Detail Aktivitas ({{ $aktivitasDetail->count() }})
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#perawatan" aria-controls="perawatan" aria-selected="false">
                            <i class="ti ti-checkbox me-1"></i> Detail Perawatan ({{ $perawatanDetail->count() }})
                        </button>
                    </li>
                </ul>
                
                <div class="tab-content pt-3">
                    <!-- Tab Kehadiran -->
                    <div class="tab-pane fade show active" id="kehadiran" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Tanggal</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Jam Kerja</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kehadiranDetail as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                            <td>{{ $item->jam_in ?? '-' }}</td>
                                            <td>{{ $item->jam_out ?? '-' }}</td>
                                            <td>{{ $item->total_jam ?? '-' }}</td>
                                            <td>
                                                @if($item->status == 'h')
                                                    <span class="badge bg-success">Hadir</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data kehadiran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Aktivitas -->
                    <div class="tab-pane fade" id="aktivitas" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Tanggal</th>
                                        <th>Aktivitas</th>
                                        <th>Lokasi</th>
                                        <th width="100">Foto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($aktivitasDetail as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->created_at->format('d M Y H:i') }}</td>
                                            <td>{{ $item->aktivitas }}</td>
                                            <td>{{ $item->lokasi ?? '-' }}</td>
                                            <td class="text-center">
                                                @if($item->foto)
                                                    <a href="{{ Storage::url($item->foto) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="ti ti-photo"></i>
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data aktivitas</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Tab Perawatan -->
                    <div class="tab-pane fade" id="perawatan" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th width="50">No</th>
                                        <th>Tanggal</th>
                                        <th>Item Perawatan</th>
                                        <th>Tipe</th>
                                        <th>Waktu</th>
                                        <th width="100">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($perawatanDetail as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tanggal_eksekusi)->format('d M Y') }}</td>
                                            <td>{{ $item->masterPerawatan->nama_item ?? '-' }}</td>
                                            <td>
                                                <span class="badge bg-label-primary">{{ ucfirst($item->masterPerawatan->tipe ?? '-') }}</span>
                                            </td>
                                            <td>{{ $item->waktu_eksekusi }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success">
                                                    <i class="ti ti-check"></i> Selesai
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data perawatan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-3">
            <a href="{{ route('kpicrew.index', ['bulan' => $bulan, 'tahun' => $tahun]) }}" class="btn btn-secondary">
                <i class="ti ti-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
    $(document).ready(function() {
        // Initialize tooltips if any
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
