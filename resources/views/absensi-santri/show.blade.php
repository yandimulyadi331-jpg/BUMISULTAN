@extends('layouts.app')
@section('titlepage', 'Detail Absensi - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('jadwal-santri.index') }}">Manajemen Saung Santri</a> / Detail Absensi</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-file-text me-2"></i> Rekap Absensi: {{ $jadwal->nama_jadwal }}</h4>
                    <div>
                        <a href="{{ route('absensi-santri.create', $jadwal->id) }}" class="btn btn-light btn-sm">
                            <i class="ti ti-plus me-1"></i> Input Absensi
                        </a>
                        <a href="{{ route('jadwal-santri.index') }}" class="btn btn-secondary btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Jadwal -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="35%">Nama Jadwal</th>
                                <td>: {{ $jadwal->nama_jadwal }}</td>
                            </tr>
                            <tr>
                                <th>Tipe</th>
                                <td>: <span class="badge bg-primary">{{ ucfirst($jadwal->tipe_jadwal) }}</span></td>
                            </tr>
                            <tr>
                                <th>Waktu</th>
                                <td>: {{ \Carbon\Carbon::parse($jadwal->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($jadwal->jam_selesai)->format('H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <th width="35%">Tempat</th>
                                <td>: {{ $jadwal->tempat ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Pembimbing</th>
                                <td>: {{ $jadwal->pembimbing ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Total Absensi</th>
                                <td>: <strong>{{ $absensiPerTanggal->count() }}</strong> hari</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <hr>

                <!-- Riwayat Absensi Per Tanggal -->
                @if($absensiPerTanggal->count() > 0)
                    @foreach($absensiPerTanggal->sortKeysDesc() as $tanggal => $absensiList)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="ti ti-calendar me-2"></i>
                                    <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}</strong>
                                    <span class="ms-2 badge bg-primary">{{ $absensiList->count() }} santri</span>
                                </div>
                                <div>
                                    <span class="badge bg-success">Hadir: {{ $absensiList->where('status_kehadiran', 'hadir')->count() }}</span>
                                    <span class="badge bg-warning">Ijin: {{ $absensiList->where('status_kehadiran', 'ijin')->count() }}</span>
                                    <span class="badge bg-info">Sakit: {{ $absensiList->where('status_kehadiran', 'sakit')->count() }}</span>
                                    <span class="badge bg-primary">Khidmat: {{ $absensiList->where('status_kehadiran', 'khidmat')->count() }}</span>
                                    <span class="badge bg-danger">Absen: {{ $absensiList->where('status_kehadiran', 'absen')->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="12%">NIS</th>
                                            <th>Nama Santri</th>
                                            <th width="15%">Status Kehadiran</th>
                                            <th width="20%">Keterangan</th>
                                            <th width="12%">Waktu</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($absensiList as $index => $absensi)
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td>{{ $absensi->santri->nis }}</td>
                                            <td>
                                                {{ $absensi->santri->nama_lengkap }}
                                                @if($absensi->santri->jenis_kelamin)
                                                    <span class="badge bg-{{ $absensi->santri->jenis_kelamin == 'L' ? 'primary' : 'danger' }} ms-1" style="font-size: 9px;">
                                                        {{ $absensi->santri->jenis_kelamin }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($absensi->status_kehadiran == 'hadir')
                                                    <span class="badge bg-success"><i class="ti ti-check"></i> Hadir</span>
                                                @elseif($absensi->status_kehadiran == 'ijin')
                                                    <span class="badge bg-warning"><i class="ti ti-calendar-minus"></i> Ijin</span>
                                                @elseif($absensi->status_kehadiran == 'sakit')
                                                    <span class="badge bg-info"><i class="ti ti-ambulance"></i> Sakit</span>
                                                @elseif($absensi->status_kehadiran == 'khidmat')
                                                    <span class="badge bg-primary"><i class="ti ti-heart-handshake"></i> Khidmat</span>
                                                @else
                                                    <span class="badge bg-danger"><i class="ti ti-x"></i> Absen</span>
                                                @endif
                                            </td>
                                            <td>{{ $absensi->keterangan ?? '-' }}</td>
                                            <td class="text-muted">
                                                <small>{{ $absensi->waktu_absensi ? \Carbon\Carbon::parse($absensi->waktu_absensi)->format('H:i') : '-' }}</small>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="alert alert-info text-center py-5">
                        <i class="ti ti-info-circle" style="font-size: 48px;"></i>
                        <h5 class="mt-3">Belum Ada Data Absensi</h5>
                        <p class="text-muted">Silakan input absensi untuk jadwal ini.</p>
                        <a href="{{ route('absensi-santri.create', $jadwal->id) }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> Input Absensi Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
