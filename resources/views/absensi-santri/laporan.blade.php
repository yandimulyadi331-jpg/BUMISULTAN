@extends('layouts.app')
@section('titlepage', 'Laporan Absensi Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span>Manajemen Saung Santri / Laporan Absensi Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-file-analytics me-2"></i> Laporan Absensi Santri</h4>
                    <a href="{{ route('jadwal-santri.index') }}" class="btn btn-light btn-sm">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Filter -->
                <form action="{{ route('absensi-santri.laporan') }}" method="GET" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label for="jadwal_id" class="form-label">Jadwal</label>
                            <select name="jadwal_id" id="jadwal_id" class="form-select">
                                <option value="">Semua Jadwal</option>
                                @foreach($jadwalList as $j)
                                    <option value="{{ $j->id }}" {{ $jadwalId == $j->id ? 'selected' : '' }}>
                                        {{ $j->nama_jadwal }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="bulan" class="form-label">Bulan</label>
                            <select name="bulan" id="bulan" class="form-select">
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $bulan == $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="tahun" class="form-label">Tahun</label>
                            <select name="tahun" id="tahun" class="form-select">
                                @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                                    <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-filter me-1"></i> Filter
                                </button>
                                <a href="{{ route('absensi-santri.export-pdf', ['jadwal_id' => $jadwalId, 'bulan' => $bulan, 'tahun' => $tahun]) }}" 
                                    class="btn btn-danger" 
                                    target="_blank"
                                    title="Download PDF">
                                    <i class="ti ti-file-type-pdf"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Statistik -->
                <div class="row mb-4">
                    <div class="col-md-2">
                        <div class="card bg-success text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['hadir'] }}</h3>
                                <small>Hadir</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-warning text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['ijin'] }}</h3>
                                <small>Ijin</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-info text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['sakit'] }}</h3>
                                <small>Sakit</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-primary text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['khidmat'] }}</h3>
                                <small>Khidmat</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-danger text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['absen'] }}</h3>
                                <small>Absen</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card bg-dark text-white">
                            <div class="card-body text-center p-3">
                                <h3 class="mb-0">{{ $statistik['total'] }}</h3>
                                <small>Total</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Laporan -->
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th>Tanggal</th>
                                <th>Jadwal</th>
                                <th>NIS</th>
                                <th>Nama Santri</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiList as $index => $absensi)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $absensi->tanggal_absensi->translatedFormat('d M Y') }}</td>
                                <td>{{ $absensi->jadwalSantri->nama_jadwal }}</td>
                                <td>{{ $absensi->santri->nis }}</td>
                                <td>{{ $absensi->santri->nama_lengkap }}</td>
                                <td>
                                    @if($absensi->status_kehadiran == 'hadir')
                                        <span class="badge bg-success">Hadir</span>
                                    @elseif($absensi->status_kehadiran == 'ijin')
                                        <span class="badge bg-warning">Ijin</span>
                                    @elseif($absensi->status_kehadiran == 'sakit')
                                        <span class="badge bg-info">Sakit</span>
                                    @elseif($absensi->status_kehadiran == 'khidmat')
                                        <span class="badge bg-primary">Khidmat</span>
                                    @else
                                        <span class="badge bg-danger">Absen</span>
                                    @endif
                                </td>
                                <td>{{ $absensi->keterangan ?? '-' }}</td>
                                <td>{{ $absensi->waktu_absensi ? \Carbon\Carbon::parse($absensi->waktu_absensi)->format('H:i') : '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="ti ti-file-off" style="font-size: 48px; color: #ccc;"></i>
                                    <p class="text-muted mt-2">Tidak ada data absensi untuk periode yang dipilih.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
