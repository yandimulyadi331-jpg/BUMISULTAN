@extends('layouts.app')
@section('titlepage', 'Detail Ijin Santri')

@section('content')
@section('navigasi')
    <span><a href="{{ route('ijin-santri.index') }}">Ijin Santri</a> / Detail</span>
@endsection

<div class="row">
    <div class="col-lg-10 col-sm-12 mx-auto">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h4 class="mb-0"><i class="ti ti-file-info me-2"></i> Detail Ijin Santri</h4>
            </div>
            <div class="card-body">
                <!-- Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="ti ti-check me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="ti ti-alert-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Status Timeline -->
                <div class="card mb-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body text-white">
                        <h5 class="mb-3"><i class="ti ti-timeline me-2"></i> Status Proses Ijin</h5>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-center" style="flex: 1;">
                                <div class="mb-2">
                                    <i class="ti ti-file-plus" style="font-size: 2em; {{ in_array($ijinSantri->status, ['pending', 'ttd_ustadz', 'dipulangkan', 'kembali']) ? 'opacity: 1;' : 'opacity: 0.3;' }}"></i>
                                </div>
                                <small>Dibuat</small><br>
                                <strong>{{ $ijinSantri->created_at->format('d/m/Y H:i') }}</strong>
                            </div>
                            <div style="flex: 0; padding: 0 10px;">→</div>
                            <div class="text-center" style="flex: 1;">
                                <div class="mb-2">
                                    <i class="ti ti-check-circle" style="font-size: 2em; {{ in_array($ijinSantri->status, ['ttd_ustadz', 'dipulangkan', 'kembali']) ? 'opacity: 1;' : 'opacity: 0.3;' }}"></i>
                                </div>
                                <small>TTD Ustadz</small><br>
                                @if($ijinSantri->ttd_ustadz_at)
                                    <strong>{{ $ijinSantri->ttd_ustadz_at->format('d/m/Y H:i') }}</strong>
                                @else
                                    <strong class="text-muted">-</strong>
                                @endif
                            </div>
                            <div style="flex: 0; padding: 0 10px;">→</div>
                            <div class="text-center" style="flex: 1;">
                                <div class="mb-2">
                                    <i class="ti ti-plane-departure" style="font-size: 2em; {{ in_array($ijinSantri->status, ['dipulangkan', 'kembali']) ? 'opacity: 1;' : 'opacity: 0.3;' }}"></i>
                                </div>
                                <small>Dipulangkan</small><br>
                                @if($ijinSantri->verifikasi_pulang_at)
                                    <strong>{{ $ijinSantri->verifikasi_pulang_at->format('d/m/Y H:i') }}</strong>
                                @else
                                    <strong class="text-muted">-</strong>
                                @endif
                            </div>
                            <div style="flex: 0; padding: 0 10px;">→</div>
                            <div class="text-center" style="flex: 1;">
                                <div class="mb-2">
                                    <i class="ti ti-plane-arrival" style="font-size: 2em; {{ $ijinSantri->status == 'kembali' ? 'opacity: 1;' : 'opacity: 0.3;' }}"></i>
                                </div>
                                <small>Kembali</small><br>
                                @if($ijinSantri->verifikasi_kembali_at)
                                    <strong>{{ $ijinSantri->verifikasi_kembali_at->format('d/m/Y H:i') }}</strong>
                                @else
                                    <strong class="text-muted">-</strong>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Surat -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card border-primary">
                            <div class="card-body">
                                <h6 class="card-title text-primary"><i class="ti ti-file-text me-2"></i> Informasi Surat</h6>
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td width="40%"><strong>Nomor Surat</strong></td>
                                        <td>{{ $ijinSantri->nomor_surat }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Status</strong></td>
                                        <td>{!! $ijinSantri->status_label !!}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Dibuat Oleh</strong></td>
                                        <td>{{ $ijinSantri->creator->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Tanggal Dibuat</strong></td>
                                        <td>{{ $ijinSantri->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card border-success">
                            <div class="card-body">
                                <h6 class="card-title text-success"><i class="ti ti-user me-2"></i> Data Santri</h6>
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td width="40%"><strong>NIS</strong></td>
                                        <td>{{ $ijinSantri->santri->nis }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Nama</strong></td>
                                        <td>{{ $ijinSantri->santri->nama_lengkap }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Jenis Kelamin</strong></td>
                                        <td>{{ $ijinSantri->santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Telepon</strong></td>
                                        <td>{{ $ijinSantri->santri->no_hp_santri ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Ijin -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title"><i class="ti ti-calendar me-2"></i> Detail Ijin</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Tanggal Ijin</label>
                                <p class="mb-0"><strong>{{ $ijinSantri->tanggal_ijin->format('d/m/Y') }}</strong></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="text-muted">Rencana Kembali</label>
                                <p class="mb-0"><strong>{{ $ijinSantri->tanggal_kembali_rencana->format('d/m/Y') }}</strong></p>
                            </div>
                            @if($ijinSantri->tanggal_kembali_aktual)
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted">Kembali Aktual</label>
                                    <p class="mb-0"><strong>{{ $ijinSantri->tanggal_kembali_aktual->format('d/m/Y') }}</strong></p>
                                </div>
                            @endif
                            <div class="col-12 mb-3">
                                <label class="text-muted">Alasan Ijin</label>
                                <p class="mb-0">{{ $ijinSantri->alasan_ijin }}</p>
                            </div>
                            @if($ijinSantri->catatan)
                                <div class="col-12 mb-3">
                                    <label class="text-muted">Catatan</label>
                                    <p class="mb-0">{{ $ijinSantri->catatan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Foto Surat TTD Ortu -->
                @if($ijinSantri->foto_surat_ttd_ortu)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="card-title"><i class="ti ti-photo me-2"></i> Foto Surat (TTD Orang Tua)</h6>
                            <img src="{{ asset('storage/ijin_santri/' . $ijinSantri->foto_surat_ttd_ortu) }}" 
                                 class="img-fluid rounded" alt="Surat TTD Ortu" 
                                 style="max-height: 500px; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                    </div>
                @endif

                <!-- Riwayat Verifikasi -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="card-title"><i class="ti ti-history me-2"></i> Riwayat Verifikasi</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Tahap</th>
                                        <th>Tanggal & Waktu</th>
                                        <th>Diverifikasi Oleh</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($ijinSantri->ttd_ustadz_at)
                                        <tr>
                                            <td><span class="badge bg-warning">TTD Ustadz</span></td>
                                            <td>{{ $ijinSantri->ttd_ustadz_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $ijinSantri->ttdUstadzBy->name ?? '-' }}</td>
                                        </tr>
                                    @endif
                                    @if($ijinSantri->verifikasi_pulang_at)
                                        <tr>
                                            <td><span class="badge bg-primary">Kepulangan</span></td>
                                            <td>{{ $ijinSantri->verifikasi_pulang_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $ijinSantri->verifikasiPulangBy->name ?? '-' }}</td>
                                        </tr>
                                    @endif
                                    @if($ijinSantri->verifikasi_kembali_at)
                                        <tr>
                                            <td><span class="badge bg-success">Kembali</span></td>
                                            <td>{{ $ijinSantri->verifikasi_kembali_at->format('d/m/Y H:i:s') }}</td>
                                            <td>{{ $ijinSantri->verifikasiKembaliBy->name ?? '-' }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('ijin-santri.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                    <div>
                        @if($ijinSantri->status == 'pending')
                            <a href="{{ route('ijin-santri.download-pdf', $ijinSantri->id) }}" 
                               class="btn btn-danger me-2">
                                <i class="ti ti-file-type-pdf me-1"></i> Download PDF
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
