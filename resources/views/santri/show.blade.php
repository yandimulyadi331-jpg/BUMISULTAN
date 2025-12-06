@extends('layouts.app')
@section('titlepage', 'Detail Santri - Manajemen Saung Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('santri.index') }}">Manajemen Saung Santri</a> / Detail Santri</span>
@endsection
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="ti ti-user me-2"></i> Detail Data Santri</h4>
                    <div>
                        <a href="{{ route('santri.cetak-qr', $santri->id) }}" class="btn btn-success btn-sm" target="_blank">
                            <i class="ti ti-qrcode me-1"></i> Cetak QR Code
                        </a>
                        <a href="{{ route('santri.edit', $santri->id) }}" class="btn btn-warning btn-sm">
                            <i class="ti ti-edit me-1"></i> Edit
                        </a>
                        <a href="{{ route('santri.index') }}" class="btn btn-light btn-sm">
                            <i class="ti ti-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Sidebar dengan Foto dan Info Singkat -->
                    <div class="col-md-3">
                        <div class="text-center mb-4">
                            @if($santri->foto)
                                <img src="{{ asset('storage/santri/'.$santri->foto) }}" 
                                    alt="{{ $santri->nama_lengkap }}" 
                                    class="img-fluid rounded-circle mb-3" 
                                    style="width: 200px; height: 200px; object-fit: cover; border: 5px solid #667eea;">
                            @else
                                <div class="rounded-circle bg-secondary d-inline-flex align-items-center justify-content-center mb-3" 
                                    style="width: 200px; height: 200px; border: 5px solid #667eea;">
                                    <i class="ti ti-user" style="font-size: 80px; color: white;"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $santri->nama_lengkap }}</h4>
                            @if($santri->nama_panggilan)
                                <p class="text-muted">"{{ $santri->nama_panggilan }}"</p>
                            @endif
                            
                            <div class="mt-3">
                                @if($santri->status_santri == 'aktif')
                                    <span class="badge bg-success fs-6">AKTIF</span>
                                @elseif($santri->status_santri == 'cuti')
                                    <span class="badge bg-warning fs-6">CUTI</span>
                                @elseif($santri->status_santri == 'alumni')
                                    <span class="badge bg-info fs-6">ALUMNI</span>
                                @else
                                    <span class="badge bg-danger fs-6">KELUAR</span>
                                @endif
                            </div>

                            <!-- Progress Hafalan -->
                            <div class="mt-4 p-3 bg-light rounded">
                                <h6 class="text-muted mb-2">Progress Hafalan</h6>
                                <div class="progress mb-2" style="height: 25px;">
                                    <div class="progress-bar bg-success" 
                                        role="progressbar" 
                                        style="width: {{ $santri->persentase_hafalan }}%"
                                        aria-valuenow="{{ $santri->persentase_hafalan }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                        {{ $santri->jumlah_juz_hafalan }} Juz
                                    </div>
                                </div>
                                <small class="text-muted">{{ number_format($santri->persentase_hafalan, 1) }}% dari 30 Juz</small>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Data -->
                    <div class="col-md-9">
                        <ul class="nav nav-tabs mb-3" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#dataPribadi">
                                    <i class="ti ti-user me-1"></i> Data Pribadi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dataKeluarga">
                                    <i class="ti ti-users me-1"></i> Data Keluarga
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dataPendidikan">
                                    <i class="ti ti-school me-1"></i> Data Pendidikan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dataHafalan">
                                    <i class="ti ti-book me-1"></i> Data Hafalan
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#dataAsrama">
                                    <i class="ti ti-building me-1"></i> Data Asrama
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">
                            <!-- Data Pribadi -->
                            <div class="tab-pane fade show active" id="dataPribadi">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">NIS</td>
                                        <td><strong>{{ $santri->nis }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">NIK</td>
                                        <td>{{ $santri->nik ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Jenis Kelamin</td>
                                        <td>{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tempat, Tanggal Lahir</td>
                                        <td>{{ $santri->tempat_lahir }}, {{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Umur</td>
                                        <td>{{ $santri->umur ?? '-' }} tahun</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Alamat</td>
                                        <td>{{ $santri->alamat_lengkap }}</td>
                                    </tr>
                                    @if($santri->kelurahan || $santri->kecamatan)
                                    <tr>
                                        <td class="text-muted">Kelurahan/Kecamatan</td>
                                        <td>{{ $santri->kelurahan }}, {{ $santri->kecamatan }}</td>
                                    </tr>
                                    @endif
                                    @if($santri->kabupaten_kota || $santri->provinsi)
                                    <tr>
                                        <td class="text-muted">Kab/Kota, Provinsi</td>
                                        <td>{{ $santri->kabupaten_kota }}, {{ $santri->provinsi }}</td>
                                    </tr>
                                    @endif
                                    @if($santri->kode_pos)
                                    <tr>
                                        <td class="text-muted">Kode Pos</td>
                                        <td>{{ $santri->kode_pos }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <td class="text-muted">No. HP</td>
                                        <td>{{ $santri->no_hp ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Email</td>
                                        <td>{{ $santri->email ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Data Keluarga -->
                            <div class="tab-pane fade" id="dataKeluarga">
                                <h5 class="mb-3">Data Orang Tua</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">Nama Ayah</td>
                                        <td><strong>{{ $santri->nama_ayah }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Pekerjaan Ayah</td>
                                        <td>{{ $santri->pekerjaan_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. HP Ayah</td>
                                        <td>{{ $santri->no_hp_ayah ?? '-' }}</td>
                                    </tr>
                                    <tr><td colspan="2"><hr></td></tr>
                                    <tr>
                                        <td class="text-muted">Nama Ibu</td>
                                        <td><strong>{{ $santri->nama_ibu }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Pekerjaan Ibu</td>
                                        <td>{{ $santri->pekerjaan_ibu ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. HP Ibu</td>
                                        <td>{{ $santri->no_hp_ibu ?? '-' }}</td>
                                    </tr>
                                </table>

                                @if($santri->nama_wali)
                                <h5 class="mb-3 mt-4">Data Wali</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">Nama Wali</td>
                                        <td><strong>{{ $santri->nama_wali }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Hubungan</td>
                                        <td>{{ $santri->hubungan_wali ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">No. HP Wali</td>
                                        <td>{{ $santri->no_hp_wali ?? '-' }}</td>
                                    </tr>
                                </table>
                                @endif
                            </div>

                            <!-- Data Pendidikan -->
                            <div class="tab-pane fade" id="dataPendidikan">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">Asal Sekolah</td>
                                        <td>{{ $santri->asal_sekolah ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tingkat Pendidikan</td>
                                        <td>{{ $santri->tingkat_pendidikan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tahun Masuk</td>
                                        <td><strong>{{ $santri->tahun_masuk }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Masuk</td>
                                        <td>{{ $santri->tanggal_masuk ? $santri->tanggal_masuk->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Lama Mondok</td>
                                        <td>{{ $santri->lama_mondok ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status Santri</td>
                                        <td>
                                            @if($santri->status_santri == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @elseif($santri->status_santri == 'cuti')
                                                <span class="badge bg-warning">Cuti</span>
                                            @elseif($santri->status_santri == 'alumni')
                                                <span class="badge bg-info">Alumni</span>
                                            @else
                                                <span class="badge bg-danger">Keluar</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Status Aktif</td>
                                        <td>
                                            @if($santri->status_aktif == 'aktif')
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-secondary">Non-Aktif</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Data Hafalan -->
                            <div class="tab-pane fade" id="dataHafalan">
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <div class="card bg-success text-white">
                                            <div class="card-body text-center">
                                                <h2 class="mb-0">{{ $santri->jumlah_juz_hafalan }}</h2>
                                                <small>Juz Hafalan</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-info text-white">
                                            <div class="card-body text-center">
                                                <h2 class="mb-0">{{ $santri->jumlah_halaman_hafalan }}</h2>
                                                <small>Halaman Hafalan</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card bg-warning text-white">
                                            <div class="card-body text-center">
                                                <h2 class="mb-0">{{ number_format($santri->persentase_hafalan, 1) }}%</h2>
                                                <small>Progress</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">Target Hafalan</td>
                                        <td>{{ $santri->target_hafalan ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Mulai Tahfidz</td>
                                        <td>{{ $santri->tanggal_mulai_tahfidz ? $santri->tanggal_mulai_tahfidz->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Tanggal Khatam Terakhir</td>
                                        <td>{{ $santri->tanggal_khatam_terakhir ? $santri->tanggal_khatam_terakhir->format('d F Y') : '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted" style="vertical-align: top;">Catatan Hafalan</td>
                                        <td>{{ $santri->catatan_hafalan ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Data Asrama -->
                            <div class="tab-pane fade" id="dataAsrama">
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="30%" class="text-muted">Nama Asrama</td>
                                        <td>{{ $santri->nama_asrama ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nomor Kamar</td>
                                        <td>{{ $santri->nomor_kamar ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted">Nama Pembina</td>
                                        <td>{{ $santri->nama_pembina ?? '-' }}</td>
                                    </tr>
                                    @if($santri->keterangan)
                                    <tr>
                                        <td class="text-muted" style="vertical-align: top;">Keterangan</td>
                                        <td>{{ $santri->keterangan }}</td>
                                    </tr>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    color: #666;
}
.nav-tabs .nav-link.active {
    color: #667eea;
    font-weight: 600;
}
</style>
@endsection
