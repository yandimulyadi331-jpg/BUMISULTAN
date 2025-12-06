@extends('layouts.app')
@section('titlepage', 'Dashboard Statistik Kehadiran')

@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                @include('masar.partials.navigation')
                <h2 class="page-title">Dashboard Statistik Kehadiran</h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('masar.kehadiran.index') }}" class="btn btn-primary">
                    <i class="ti ti-list me-1"></i> Lihat Data Kehadiran
                </a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Summary Cards -->
        <div class="row row-cards mb-3">
            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Total Pertemuan</div>
                        </div>
                        <div class="h1 mb-3">{{ $totalPertemuan }}</div>
                        <div class="d-flex mb-2">
                            <div>Pertemuan tercatat</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Rata-rata Kehadiran</div>
                        </div>
                        <div class="h1 mb-3">{{ number_format($rataRataKehadiran, 0) }}</div>
                        <div class="d-flex mb-2">
                            <div>Jamaah per pertemuan</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Rata-rata Persentase</div>
                        </div>
                        <div class="h1 mb-3">{{ number_format($rataRataPersentase, 1) }}%</div>
                        <div class="d-flex mb-2">
                            <div>Tingkat kehadiran</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-lg-3">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="subheader">Kehadiran Minggu Ini</div>
                        </div>
                        @if($mingguIni)
                            <div class="h1 mb-3">{{ $mingguIni->jumlah_hadir }}</div>
                            <div class="d-flex mb-2">
                                <div>
                                    <span class="badge bg-{{ $mingguIni->persentase >= 80 ? 'success' : ($mingguIni->persentase >= 60 ? 'warning' : 'danger') }}">
                                        {{ $mingguIni->persentase }}%
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="h1 mb-3 text-muted">-</div>
                            <div class="d-flex mb-2">
                                <div>Belum ada data</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Comparison Card -->
        @if($mingguIni && $mingguLalu)
        <div class="row row-cards mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Perbandingan Minggu Ini vs Minggu Lalu</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Minggu Ini</h4>
                                <p class="text-muted">{{ $mingguIni->tanggal->format('d M Y') }}</p>
                                <h2>{{ $mingguIni->jumlah_hadir }} jamaah 
                                    <span class="badge bg-{{ $mingguIni->persentase >= 80 ? 'success' : 'warning' }}">
                                        {{ $mingguIni->persentase }}%
                                    </span>
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <h4>Minggu Lalu</h4>
                                <p class="text-muted">{{ $mingguLalu->tanggal->format('d M Y') }}</p>
                                <h2>{{ $mingguLalu->jumlah_hadir }} jamaah 
                                    <span class="badge bg-{{ $mingguLalu->persentase >= 80 ? 'success' : 'warning' }}">
                                        {{ $mingguLalu->persentase }}%
                                    </span>
                                </h2>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-12">
                                @php
                                    $selisih = $mingguIni->jumlah_hadir - $mingguLalu->jumlah_hadir;
                                    $selisihPersen = $mingguIni->persentase - $mingguLalu->persentase;
                                @endphp
                                @if($selisih > 0)
                                    <div class="alert alert-success">
                                        <i class="ti ti-trending-up me-2"></i>
                                        Kehadiran <strong>meningkat {{ abs($selisih) }} jamaah</strong> ({{ number_format(abs($selisihPersen), 1) }}%) dibanding minggu lalu
                                    </div>
                                @elseif($selisih < 0)
                                    <div class="alert alert-warning">
                                        <i class="ti ti-trending-down me-2"></i>
                                        Kehadiran <strong>menurun {{ abs($selisih) }} jamaah</strong> ({{ number_format(abs($selisihPersen), 1) }}%) dibanding minggu lalu
                                    </div>
                                @else
                                    <div class="alert alert-info">
                                        <i class="ti ti-minus me-2"></i>
                                        Kehadiran <strong>sama</strong> dengan minggu lalu
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Chart & Recent Data -->
        <div class="row row-cards">
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Tren Kehadiran 6 Bulan Terakhir</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartKehadiran"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">10 Pertemuan Terbaru</h3>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            @forelse($rekapTerbaru as $rekap)
                            <div class="list-group-item">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span class="badge bg-{{ $rekap->persentase >= 80 ? 'success' : ($rekap->persentase >= 60 ? 'warning' : 'danger') }} badge-lg">
                                            {{ $rekap->persentase }}%
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">{{ $rekap->tanggal->format('d M Y') }}</div>
                                        <div class="text-muted">{{ $rekap->jumlah_hadir }} dari {{ $rekap->total_jamaah }} jamaah</div>
                                        @if($rekap->keterangan)
                                        <div class="text-muted small">{{ Str::limit($rekap->keterangan, 50) }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="list-group-item text-center text-muted">
                                Belum ada data kehadiran
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
$(document).ready(function() {
    // Data untuk chart
    const trenData = @json($trenBulanan);
    
    const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const labels = trenData.map(item => bulanNames[item.bulan - 1] + ' ' + item.tahun);
    const dataHadir = trenData.map(item => parseFloat(item.rata_hadir));
    const dataPersentase = trenData.map(item => parseFloat(item.rata_persentase));
    
    // Create chart
    const ctx = document.getElementById('chartKehadiran').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Rata-rata Kehadiran',
                    data: dataHadir,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.3,
                    yAxisID: 'y'
                },
                {
                    label: 'Persentase (%)',
                    data: dataPersentase,
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    tension: 0.3,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Jumlah Jamaah'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Persentase (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });
});
</script>
@endpush
