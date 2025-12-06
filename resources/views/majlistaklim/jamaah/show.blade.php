@extends('layouts.app')
@section('titlepage', 'Detail Jamaah')

@section('content')
@section('navigasi')
    <span class="text-muted fw-light">Manajemen Yayasan / Data Jamaah /</span> Detail Jamaah
@endsection

<div class="row">
    <!-- Profile Card -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <div class="mb-3">
                    <img src="{{ $jamaah->foto ? asset('storage/jamaah/' . $jamaah->foto) : asset('assets/img/avatars/1.png') }}" 
                        alt="Foto Jamaah" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
                <h4 class="mb-1">{{ $jamaah->nama_jamaah }}</h4>
                <p class="text-muted mb-2">{{ $jamaah->nomor_jamaah }}</p>
                
                @if($jamaah->status_umroh)
                    <span class="badge bg-success mb-3">
                        <i class="ti ti-plane me-1"></i>Sudah Umroh
                        @if($jamaah->tanggal_umroh)
                            ({{ $jamaah->tanggal_umroh->format('Y') }})
                        @endif
                    </span>
                @else
                    <span class="badge bg-secondary mb-3">Belum Umroh</span>
                @endif

                <div class="mb-3">
                    <span class="badge bg-{{ $jamaah->badge_color }} p-3">
                        <i class="ti ti-calendar-check me-1"></i>
                        Kehadiran: {{ $jamaah->jumlah_kehadiran }} kali
                        <br><small>{{ $jamaah->badge_color_name }}</small>
                    </span>
                </div>

                <div class="d-flex justify-content-center gap-2 mb-3">
                    <a href="{{ route('majlistaklim.jamaah.edit', Crypt::encrypt($jamaah->id)) }}" class="btn btn-sm btn-warning">
                        <i class="ti ti-edit"></i> Edit
                    </a>
                    <a href="{{ route('majlistaklim.jamaah.downloadIdCard', Crypt::encrypt($jamaah->id)) }}" 
                        class="btn btn-sm btn-primary" target="_blank">
                        <i class="ti ti-id"></i> Download ID Card
                    </a>
                </div>

                <a href="{{ route('majlistaklim.jamaah.index') }}" class="btn btn-sm btn-secondary w-100">
                    <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <!-- Statistik Card -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-chart-bar me-2"></i>Statistik Kehadiran</h6>
            </div>
            <div class="card-body">
                <canvas id="chartKehadiran"></canvas>
            </div>
        </div>
    </div>

    <!-- Detail Information -->
    <div class="col-lg-8 col-md-6">
        <!-- Data Pribadi -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-user me-2"></i>Data Pribadi</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">NIK</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->nik }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Jenis Kelamin</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Lahir</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->tanggal_lahir->format('d F Y') }}</p>
                        <small class="text-muted">({{ \Carbon\Carbon::parse($jamaah->tanggal_lahir)->age }} tahun)</small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tahun Masuk</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->tahun_masuk }}</p>
                        <small class="text-muted">({{ date('Y') - $jamaah->tahun_masuk }} tahun bergabung)</small>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="text-muted small">Alamat</label>
                        <p class="mb-0">{{ $jamaah->alamat }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-phone me-2"></i>Informasi Kontak</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">No. Telepon</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->no_telepon ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status & PIN -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-settings me-2"></i>Status & Sistem</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Status Kepesertaan</label>
                        <p class="mb-0">
                            <span class="badge bg-{{ $jamaah->status_aktif == 'aktif' ? 'success' : 'danger' }}">
                                {{ ucfirst($jamaah->status_aktif) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">PIN Fingerprint</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->pin_fingerprint ?? '-' }}</p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="text-muted small">Terdaftar Sejak</label>
                        <p class="mb-0 fw-bold">{{ $jamaah->created_at->format('d M Y') }}</p>
                    </div>
                </div>
                @if($jamaah->keterangan)
                <div class="row">
                    <div class="col-12">
                        <label class="text-muted small">Keterangan</label>
                        <p class="mb-0">{{ $jamaah->keterangan }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Kehadiran -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-calendar-stats me-2"></i>Riwayat Kehadiran Terakhir</h6>
            </div>
            <div class="card-body">
                @if($jamaah->kehadiran->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam Masuk</th>
                                <th>Jam Pulang</th>
                                <th>Status</th>
                                <th>Sumber</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamaah->kehadiran->take(10) as $kehadiran)
                            <tr>
                                <td>{{ $kehadiran->tanggal_kehadiran->format('d M Y') }}</td>
                                <td>{{ $kehadiran->jam_masuk ? $kehadiran->jam_masuk->format('H:i') : '-' }}</td>
                                <td>{{ $kehadiran->jam_pulang ? $kehadiran->jam_pulang->format('H:i') : '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $kehadiran->status_kehadiran == 'hadir' ? 'success' : 'warning' }}">
                                        {{ ucfirst($kehadiran->status_kehadiran) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ ucfirst($kehadiran->sumber_absen) }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="ti ti-calendar-x" style="font-size: 48px;"></i>
                    <p class="mt-2">Belum ada riwayat kehadiran</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Hadiah -->
        <div class="card mb-3">
            <div class="card-header">
                <h6 class="mb-0"><i class="ti ti-gift me-2"></i>Riwayat Penerimaan Hadiah</h6>
            </div>
            <div class="card-body">
                @if($jamaah->distribusiHadiah->count() > 0)
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Hadiah</th>
                                <th>Jumlah</th>
                                <th>Ukuran</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jamaah->distribusiHadiah as $distribusi)
                            <tr>
                                <td>{{ $distribusi->tanggal_distribusi->format('d M Y') }}</td>
                                <td>{{ $distribusi->hadiah->nama_hadiah }}</td>
                                <td>{{ $distribusi->jumlah }}</td>
                                <td>{{ $distribusi->ukuran_diterima ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-{{ $distribusi->status_distribusi == 'diterima' ? 'success' : 'warning' }}">
                                        {{ ucfirst($distribusi->status_distribusi) }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="ti ti-gift-off" style="font-size: 48px;"></i>
                    <p class="mt-2">Belum pernah menerima hadiah</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Undian Umroh -->
        @if($jamaah->pemenangUndian->count() > 0)
        <div class="card mb-3">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0 text-white"><i class="ti ti-trophy me-2"></i>Pemenang Undian Umroh</h6>
            </div>
            <div class="card-body">
                @foreach($jamaah->pemenangUndian as $pemenang)
                <div class="alert alert-success mb-2">
                    <strong>{{ $pemenang->undian->nama_program }}</strong>
                    <br>Urutan Pemenang: {{ $pemenang->urutan_pemenang }}
                    <br>Tanggal Pengumuman: {{ $pemenang->tanggal_pengumuman->format('d M Y') }}
                    <br>Status: <span class="badge bg-{{ $pemenang->status_keberangkatan == 'selesai' ? 'success' : 'warning' }}">
                        {{ ucfirst(str_replace('_', ' ', $pemenang->status_keberangkatan)) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@push('myscript')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    $(document).ready(function() {
        // Chart Kehadiran per Bulan
        const kehadiranData = @json($kehadiranPerBulan);
        const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
        
        const labels = [];
        const data = [];
        
        for(let i = 1; i <= 12; i++) {
            labels.push(bulanNames[i-1]);
            data.push(kehadiranData[i] || 0);
        }

        const ctx = document.getElementById('chartKehadiran').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Kehadiran',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.6)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Kehadiran per Bulan {{ date("Y") }}'
                    }
                }
            }
        });
    });
</script>
@endpush
