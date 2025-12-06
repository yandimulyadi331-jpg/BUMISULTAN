@extends('layouts.app')
@section('titlepage', 'Detail Administrasi')

@section('content')
@section('navigasi')
    <span>Fasilitas & Asset / Manajemen Administrasi / Detail</span>
@endsection

<div class="row">
    <!-- Detail Administrasi -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-{{ $administrasi->getJenisAdministrasiColor() }} text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="{{ $administrasi->getJenisAdministrasiIcon() }} me-2"></i>
                        {{ $administrasi->getJenisAdministrasiLabel() }}
                    </h5>
                    <div>
                        {!! $administrasi->getPrioritasBadge() !!}
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Kode dan Status -->
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Kode Administrasi</label>
                        <h4 class="text-primary fw-bold">{{ $administrasi->kode_administrasi }}</h4>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <label class="text-muted small">Status</label>
                        <div>
                            {!! $administrasi->getStatusBadge() !!}
                        </div>
                    </div>
                </div>

                <hr>

                <!-- Informasi Utama -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Nomor Surat/Dokumen</label>
                        <p class="mb-0 fw-bold">{{ $administrasi->nomor_surat ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Surat</label>
                        <p class="mb-0">
                            @if($administrasi->tanggal_surat)
                                <i class="ti ti-calendar me-1"></i>{{ $administrasi->tanggal_surat->format('d F Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    
                    @if($administrasi->isMasuk())
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Pengirim</label>
                        <p class="mb-0"><i class="ti ti-user me-1"></i>{{ $administrasi->pengirim ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Terima</label>
                        <p class="mb-0">
                            @if($administrasi->tanggal_terima)
                                <i class="ti ti-clock me-1"></i>{{ $administrasi->tanggal_terima->format('d F Y, H:i') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    @endif

                    @if($administrasi->isKeluar())
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Penerima</label>
                        <p class="mb-0"><i class="ti ti-send me-1"></i>{{ $administrasi->penerima ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Tanggal Kirim</label>
                        <p class="mb-0">
                            @if($administrasi->tanggal_kirim)
                                <i class="ti ti-clock me-1"></i>{{ $administrasi->tanggal_kirim->format('d F Y, H:i') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>
                    @endif
                </div>

                <hr>

                <!-- Perihal -->
                <div class="mb-3">
                    <label class="text-muted small">Perihal / Judul</label>
                    <h5 class="mb-0">{{ $administrasi->perihal }}</h5>
                </div>

                <!-- Ringkasan -->
                @if($administrasi->ringkasan)
                <div class="mb-3">
                    <label class="text-muted small">Ringkasan</label>
                    <p class="mb-0" style="text-align: justify;">{{ $administrasi->ringkasan }}</p>
                </div>
                @endif

                <!-- DETAIL ACARA UNDANGAN -->
                @if(in_array($administrasi->jenis_administrasi, ['undangan_masuk', 'undangan_keluar']) && $administrasi->nama_acara)
                <div class="card border-info mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="ti ti-calendar-event me-2"></i>Detail Acara Undangan</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label class="text-muted small">Nama Acara</label>
                                <h5 class="text-info mb-0">{{ $administrasi->nama_acara }}</h5>
                            </div>

                            @if($administrasi->tanggal_acara_mulai)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small"><i class="ti ti-calendar me-1"></i>Tanggal Acara</label>
                                <div>
                                    @if($administrasi->tanggal_acara_selesai && $administrasi->tanggal_acara_mulai != $administrasi->tanggal_acara_selesai)
                                        <strong>{{ $administrasi->tanggal_acara_mulai->format('d F Y') }}</strong><br>
                                        <small class="text-muted">sampai</small><br>
                                        <strong>{{ $administrasi->tanggal_acara_selesai->format('d F Y') }}</strong><br>
                                        <span class="badge bg-info">{{ $administrasi->tanggal_acara_mulai->diffInDays($administrasi->tanggal_acara_selesai) + 1 }} hari</span>
                                    @else
                                        <strong>{{ $administrasi->tanggal_acara_mulai->format('d F Y') }}</strong><br>
                                        <small class="text-muted">{{ $administrasi->tanggal_acara_mulai->translatedFormat('l') }}</small>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($administrasi->waktu_acara_mulai || $administrasi->waktu_acara_selesai)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small"><i class="ti ti-clock me-1"></i>Waktu</label>
                                <div>
                                    @if($administrasi->waktu_acara_mulai && $administrasi->waktu_acara_selesai)
                                        <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_mulai)) }}</strong> - 
                                        <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_selesai)) }}</strong> WIB
                                    @elseif($administrasi->waktu_acara_mulai)
                                        <strong>{{ date('H:i', strtotime($administrasi->waktu_acara_mulai)) }}</strong> WIB
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($administrasi->lokasi_acara)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small"><i class="ti ti-map-pin me-1"></i>Lokasi</label>
                                <div>
                                    <strong>{{ $administrasi->lokasi_acara }}</strong>
                                    @if($administrasi->alamat_acara)
                                        <br><small class="text-muted">{{ $administrasi->alamat_acara }}</small>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($administrasi->dress_code)
                            <div class="col-md-6 mb-3">
                                <label class="text-muted small"><i class="ti ti-shirt me-1"></i>Dress Code</label>
                                <div>
                                    <span class="badge bg-info fs-6">{{ $administrasi->dress_code }}</span>
                                </div>
                            </div>
                            @endif

                            @if($administrasi->catatan_acara)
                            <div class="col-md-12 mb-2">
                                <label class="text-muted small"><i class="ti ti-info-circle me-1"></i>Catatan Acara</label>
                                <div class="alert alert-info mb-0">
                                    {{ $administrasi->catatan_acara }}
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
                <!-- END DETAIL ACARA -->

                <!-- Disposisi -->
                @if($administrasi->disposisi_ke)
                <div class="mb-3">
                    <div class="alert alert-info">
                        <i class="ti ti-share me-2"></i><strong>Disposisi Ke:</strong> {{ $administrasi->disposisi_ke }}
                    </div>
                </div>
                @endif

                <hr>

                <!-- File dan Foto -->
                <div class="row">
                    @if($administrasi->file_dokumen)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">File Dokumen</label>
                        <div>
                            <a href="{{ route('administrasi.download', $administrasi->id) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="ti ti-download me-1"></i>Download Dokumen
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($administrasi->foto)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Foto Dokumen</label>
                        <div>
                            <a href="{{ Storage::url($administrasi->foto) }}" 
                               target="_blank" 
                               class="btn btn-info btn-sm">
                                <i class="ti ti-photo me-1"></i>Lihat Foto
                            </a>
                        </div>
                        <div class="mt-2">
                            <img src="{{ Storage::url($administrasi->foto) }}" 
                                 alt="Foto Dokumen" 
                                 class="img-thumbnail" 
                                 style="max-height: 200px; cursor: pointer;"
                                 onclick="window.open(this.src, '_blank')">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Catatan dan Keterangan -->
                @if($administrasi->catatan || $administrasi->keterangan)
                <hr>
                <div class="row">
                    @if($administrasi->catatan)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Catatan</label>
                        <p class="mb-0">{{ $administrasi->catatan }}</p>
                    </div>
                    @endif

                    @if($administrasi->keterangan)
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">Keterangan</label>
                        <p class="mb-0">{{ $administrasi->keterangan }}</p>
                    </div>
                    @endif
                </div>
                @endif

                <hr>

                <!-- Info Tambahan -->
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">
                            <i class="ti ti-user me-1"></i>Dibuat oleh: 
                            <strong>{{ $administrasi->creator->name ?? '-' }}</strong>
                        </small>
                    </div>
                    <div class="col-md-6 mb-2">
                        <small class="text-muted">
                            <i class="ti ti-calendar me-1"></i>Dibuat: 
                            <strong>{{ $administrasi->created_at->format('d/m/Y H:i') }}</strong>
                        </small>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('administrasi.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- History Tindak Lanjut -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h6 class="mb-0">
                    <i class="ti ti-list-check me-2"></i>History Tindak Lanjut
                </h6>
            </div>
            <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                @forelse($administrasi->tindakLanjut as $tindak)
                <div class="card mb-3 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <span class="badge bg-{{ $tindak->status_tindak_lanjut == 'selesai' ? 'success' : 'warning' }}">
                                    <i class="{{ $tindak->getJenisTindakLanjutIcon() }} me-1"></i>
                                    {{ $tindak->getJenisTindakLanjutLabel() }}
                                </span>
                                {!! $tindak->getStatusBadge() !!}
                            </div>
                            <button class="btn btn-sm btn-info" 
                                    onclick="showDetailTindakLanjut({{ $tindak->id }})" 
                                    title="Lihat Detail">
                                <i class="ti ti-eye"></i>
                            </button>
                        </div>
                        <h6 class="mb-2">{{ $tindak->judul_tindak_lanjut }}</h6>
                        <small class="text-muted d-block mb-2">
                            <i class="ti ti-code me-1"></i>{{ $tindak->kode_tindak_lanjut }}
                        </small>
                        
                        @if($tindak->deskripsi_tindak_lanjut)
                        <p class="mb-2 small">{{ Str::limit($tindak->deskripsi_tindak_lanjut, 100) }}</p>
                        @endif

                        <!-- Detail Pencairan Dana -->
                        @if($tindak->jenis_tindak_lanjut == 'pencairan_dana' && $tindak->nominal_pencairan)
                        <div class="alert alert-success py-2 px-3 mb-2">
                            <strong>{{ $tindak->formatNominalPencairan() }}</strong>
                            @if($tindak->nama_penerima_dana)
                                <br><small>Penerima: {{ $tindak->nama_penerima_dana }}</small>
                            @endif
                        </div>
                        @endif

                        <!-- Detail Disposisi -->
                        @if($tindak->jenis_tindak_lanjut == 'disposisi')
                        <div class="small text-muted">
                            <i class="ti ti-arrow-right me-1"></i>Kepada: {{ $tindak->disposisi_kepada }}
                        </div>
                        @endif

                        <div class="text-muted small mt-2">
                            <i class="ti ti-calendar-event me-1"></i>
                            {{ $tindak->created_at->format('d/m/Y H:i') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-4">
                    <i class="ti ti-clipboard-off" style="font-size: 2rem;"></i>
                    <p class="mt-2">Belum ada tindak lanjut</p>
                    <a href="{{ route('administrasi.tindak-lanjut.create', $administrasi->id) }}" 
                       class="btn btn-success btn-sm">
                        <i class="ti ti-plus me-1"></i>Tambah Tindak Lanjut
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ session('error') }}',
            showConfirmButton: true
        });
    @endif

    // Function to show detail tindak lanjut
    function showDetailTindakLanjut(tindakLanjutId) {
        // Find tindak lanjut data
        const tindakLanjutData = @json($administrasi->tindakLanjut);
        const tindak = tindakLanjutData.find(t => t.id === tindakLanjutId);
        
        if (!tindak) return;

        // Build detail HTML
        let detailHtml = `
            <div class="text-start">
                <table class="table table-bordered">
                    <tr>
                        <th width="35%">Kode Tindak Lanjut</th>
                        <td><strong>${tindak.kode_tindak_lanjut}</strong></td>
                    </tr>
                    <tr>
                        <th>Jenis</th>
                        <td><span class="badge bg-info">${getJenisLabel(tindak.jenis_tindak_lanjut)}</span></td>
                    </tr>
                    <tr>
                        <th>Judul</th>
                        <td><strong>${tindak.judul_tindak_lanjut}</strong></td>
                    </tr>
                    <tr>
                        <th>Tanggal Tindak Lanjut</th>
                        <td>${formatDate(tindak.tanggal_tindak_lanjut)}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td><span class="badge bg-${tindak.status_tindak_lanjut === 'selesai' ? 'success' : (tindak.status_tindak_lanjut === 'proses' ? 'info' : 'warning')}">${tindak.status_tindak_lanjut.toUpperCase()}</span></td>
                    </tr>
        `;

        if (tindak.deskripsi_tindak_lanjut) {
            detailHtml += `
                    <tr>
                        <th>Deskripsi</th>
                        <td>${tindak.deskripsi_tindak_lanjut}</td>
                    </tr>
            `;
        }

        // Add specific fields based on jenis
        if (tindak.jenis_tindak_lanjut === 'pencairan_dana') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Pencairan Dana</strong></td></tr>';
            if (tindak.nominal_pencairan) {
                detailHtml += `
                    <tr>
                        <th>Nominal Pencairan</th>
                        <td><strong class="text-success fs-5">Rp ${formatRupiah(tindak.nominal_pencairan)}</strong></td>
                    </tr>
                `;
            }
            if (tindak.metode_pencairan) {
                detailHtml += `<tr><th>Metode Pencairan</th><td>${tindak.metode_pencairan}</td></tr>`;
            }
            if (tindak.nama_penerima_dana) {
                detailHtml += `<tr><th>Nama Penerima</th><td>${tindak.nama_penerima_dana}</td></tr>`;
            }
            if (tindak.nomor_rekening) {
                detailHtml += `<tr><th>No. Rekening</th><td><code>${tindak.nomor_rekening}</code></td></tr>`;
            }
            if (tindak.tanggal_pencairan) {
                detailHtml += `<tr><th>Tanggal Pencairan</th><td>${formatDate(tindak.tanggal_pencairan)}</td></tr>`;
            }
            // Bukti Pencairan
            if (tindak.bukti_pencairan) {
                detailHtml += `
                    <tr>
                        <th>Bukti Pencairan</th>
                        <td>
                            <a href="/storage/${tindak.bukti_pencairan}" target="_blank" class="btn btn-sm btn-info">
                                <i class="ti ti-download"></i> Download Bukti
                            </a>
                            <div class="mt-2">
                                <img src="/storage/${tindak.bukti_pencairan}" 
                                     class="img-thumbnail" 
                                     style="max-width: 300px; cursor: pointer;" 
                                     onclick="window.open('/storage/${tindak.bukti_pencairan}', '_blank')"
                                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                                <div style="display:none;" class="alert alert-info">
                                    <i class="ti ti-file-text"></i> File tersedia untuk diunduh
                                </div>
                            </div>
                        </td>
                    </tr>
                `;
            }
            // Tanda Tangan Pencairan
            if (tindak.tandatangan_pencairan) {
                detailHtml += `
                    <tr>
                        <th>Tanda Tangan Digital</th>
                        <td>
                            <div class="border p-2 bg-light d-inline-block">
                                <img src="/storage/${tindak.tandatangan_pencairan}" 
                                     alt="Tanda Tangan Digital"
                                     style="max-width: 300px; max-height: 150px; display: block;">
                                <small class="text-muted d-block mt-1">Tanda tangan digital terverifikasi</small>
                            </div>
                        </td>
                    </tr>
                `;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'disposisi') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Disposisi</strong></td></tr>';
            if (tindak.disposisi_dari) {
                detailHtml += `<tr><th>Dari</th><td>${tindak.disposisi_dari}</td></tr>`;
            }
            if (tindak.disposisi_kepada) {
                detailHtml += `<tr><th>Kepada</th><td><strong>${tindak.disposisi_kepada}</strong></td></tr>`;
            }
            if (tindak.instruksi_disposisi) {
                detailHtml += `<tr><th>Instruksi</th><td>${tindak.instruksi_disposisi}</td></tr>`;
            }
            if (tindak.deadline_disposisi) {
                detailHtml += `<tr><th>Deadline</th><td><span class="badge bg-warning">${formatDate(tindak.deadline_disposisi)}</span></td></tr>`;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'konfirmasi_terima' || tindak.jenis_tindak_lanjut === 'konfirmasi_kirim') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Konfirmasi Paket</strong></td></tr>';
            if (tindak.nama_penerima_paket) {
                detailHtml += `<tr><th>Nama Penerima</th><td>${tindak.nama_penerima_paket}</td></tr>`;
            }
            if (tindak.waktu_terima_paket) {
                detailHtml += `<tr><th>Waktu Terima</th><td>${formatDateTime(tindak.waktu_terima_paket)}</td></tr>`;
            }
            if (tindak.kondisi_paket) {
                const kondisiBadge = tindak.kondisi_paket === 'Baik' ? 'success' : 'danger';
                detailHtml += `<tr><th>Kondisi Paket</th><td><span class="badge bg-${kondisiBadge}">${tindak.kondisi_paket}</span></td></tr>`;
            }
            if (tindak.resi_pengiriman) {
                detailHtml += `<tr><th>Nomor Resi</th><td><code>${tindak.resi_pengiriman}</code></td></tr>`;
            }
            // Foto Paket
            if (tindak.foto_paket) {
                detailHtml += `
                    <tr>
                        <th>Foto Paket</th>
                        <td>
                            <img src="/storage/${tindak.foto_paket}" 
                                 class="img-thumbnail" 
                                 style="max-width: 300px; cursor: pointer;" 
                                 onclick="window.open('/storage/${tindak.foto_paket}', '_blank')">
                        </td>
                    </tr>
                `;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'rapat_pembahasan') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Rapat</strong></td></tr>';
            if (tindak.tempat_rapat) {
                detailHtml += `<tr><th>Tempat Rapat</th><td>${tindak.tempat_rapat}</td></tr>`;
            }
            if (tindak.waktu_rapat) {
                detailHtml += `<tr><th>Waktu Rapat</th><td>${formatDateTime(tindak.waktu_rapat)}</td></tr>`;
            }
            if (tindak.peserta_rapat) {
                detailHtml += `<tr><th>Peserta</th><td>${tindak.peserta_rapat}</td></tr>`;
            }
            if (tindak.hasil_rapat) {
                detailHtml += `<tr><th>Hasil Rapat</th><td>${tindak.hasil_rapat}</td></tr>`;
            }
            // Notulen Rapat
            if (tindak.notulen_rapat) {
                detailHtml += `
                    <tr>
                        <th>Notulen Rapat</th>
                        <td>
                            <a href="/storage/${tindak.notulen_rapat}" target="_blank" class="btn btn-sm btn-info">
                                <i class="ti ti-download"></i> Download Notulen
                            </a>
                        </td>
                    </tr>
                `;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'penerbitan_sk') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Penerbitan SK</strong></td></tr>';
            if (tindak.nomor_sk) {
                detailHtml += `<tr><th>Nomor SK</th><td><strong>${tindak.nomor_sk}</strong></td></tr>`;
            }
            if (tindak.tentang_sk) {
                detailHtml += `<tr><th>Tentang</th><td>${tindak.tentang_sk}</td></tr>`;
            }
            if (tindak.tanggal_terbit_sk) {
                detailHtml += `<tr><th>Tanggal Terbit</th><td>${formatDate(tindak.tanggal_terbit_sk)}</td></tr>`;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'tandatangan') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Penandatanganan</strong></td></tr>';
            if (tindak.nama_penandatangan) {
                detailHtml += `<tr><th>Penandatangan</th><td><strong>${tindak.nama_penandatangan}</strong></td></tr>`;
            }
            if (tindak.jabatan_penandatangan) {
                detailHtml += `<tr><th>Jabatan</th><td>${tindak.jabatan_penandatangan}</td></tr>`;
            }
            if (tindak.tanggal_tandatangan) {
                detailHtml += `<tr><th>Tanggal TTD</th><td>${formatDate(tindak.tanggal_tandatangan)}</td></tr>`;
            }
            // File Dokumen TTD
            if (tindak.file_dokumen_ttd) {
                detailHtml += `
                    <tr>
                        <th>Dokumen yang Ditandatangani</th>
                        <td>
                            <a href="/storage/${tindak.file_dokumen_ttd}" target="_blank" class="btn btn-sm btn-info">
                                <i class="ti ti-download"></i> Download Dokumen TTD
                            </a>
                        </td>
                    </tr>
                `;
            }
            // Signature TTD
            if (tindak.signature_ttd) {
                detailHtml += `
                    <tr>
                        <th>Tanda Tangan Digital</th>
                        <td>
                            <div class="border p-2 bg-light d-inline-block">
                                <img src="/storage/${tindak.signature_ttd}" 
                                     alt="Tanda Tangan Digital"
                                     style="max-width: 300px; max-height: 150px; display: block;">
                                <small class="text-muted d-block mt-1">Ditandatangani secara digital oleh: <strong>${tindak.nama_penandatangan || 'N/A'}</strong></small>
                            </div>
                        </td>
                    </tr>
                `;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'verifikasi') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Verifikasi</strong></td></tr>';
            if (tindak.hasil_verifikasi) {
                const hasilBadge = tindak.hasil_verifikasi === 'Lolos' ? 'success' : 'danger';
                detailHtml += `<tr><th>Hasil Verifikasi</th><td><span class="badge bg-${hasilBadge}">${tindak.hasil_verifikasi}</span></td></tr>`;
            }
            if (tindak.catatan_verifikasi) {
                detailHtml += `<tr><th>Catatan Verifikasi</th><td>${tindak.catatan_verifikasi}</td></tr>`;
            }
            if (tindak.nama_verifikator) {
                detailHtml += `<tr><th>Verifikator</th><td>${tindak.nama_verifikator}</td></tr>`;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'approval') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Approval</strong></td></tr>';
            if (tindak.status_approval) {
                const approvalBadge = tindak.status_approval === 'Disetujui' ? 'success' : 'danger';
                detailHtml += `<tr><th>Status Approval</th><td><span class="badge bg-${approvalBadge}">${tindak.status_approval}</span></td></tr>`;
            }
            if (tindak.nama_approver) {
                detailHtml += `<tr><th>Approver</th><td><strong>${tindak.nama_approver}</strong></td></tr>`;
            }
            if (tindak.jabatan_approver) {
                detailHtml += `<tr><th>Jabatan</th><td>${tindak.jabatan_approver}</td></tr>`;
            }
            if (tindak.tanggal_approval) {
                detailHtml += `<tr><th>Tanggal Approval</th><td>${formatDate(tindak.tanggal_approval)}</td></tr>`;
            }
            if (tindak.catatan_approval) {
                detailHtml += `<tr><th>Catatan</th><td>${tindak.catatan_approval}</td></tr>`;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'revisi') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Revisi</strong></td></tr>';
            if (tindak.poin_revisi) {
                detailHtml += `<tr><th>Poin Revisi</th><td>${tindak.poin_revisi}</td></tr>`;
            }
            if (tindak.deadline_revisi) {
                detailHtml += `<tr><th>Deadline</th><td><span class="badge bg-warning">${formatDate(tindak.deadline_revisi)}</span></td></tr>`;
            }
        } 
        
        else if (tindak.jenis_tindak_lanjut === 'arsip') {
            detailHtml += '<tr><td colspan="2" class="bg-light"><strong>Detail Pengarsipan</strong></td></tr>';
            if (tindak.lokasi_arsip) {
                detailHtml += `<tr><th>Lokasi Arsip</th><td>${tindak.lokasi_arsip}</td></tr>`;
            }
            if (tindak.nomor_box_arsip) {
                detailHtml += `<tr><th>Nomor Box</th><td><code>${tindak.nomor_box_arsip}</code></td></tr>`;
            }
            if (tindak.tanggal_arsip) {
                detailHtml += `<tr><th>Tanggal Arsip</th><td>${formatDate(tindak.tanggal_arsip)}</td></tr>`;
            }
        }

        // Catatan umum
        if (tindak.catatan) {
            detailHtml += `
                    <tr>
                        <th>Catatan Tambahan</th>
                        <td>${tindak.catatan}</td>
                    </tr>
            `;
        }

        // PIC info
        if (tindak.pic) {
            detailHtml += `
                    <tr>
                        <th>PIC (Person In Charge)</th>
                        <td><span class="badge bg-primary">${tindak.pic.name}</span></td>
                    </tr>
            `;
        }

        if (tindak.catatan_pic) {
            detailHtml += `
                    <tr>
                        <th>Catatan PIC</th>
                        <td>${tindak.catatan_pic}</td>
                    </tr>
            `;
        }

        detailHtml += `
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>${tindak.creator ? tindak.creator.name : '-'}</td>
                    </tr>
                    <tr>
                        <th>Waktu Dibuat</th>
                        <td>${formatDateTime(tindak.created_at)}</td>
                    </tr>
                </table>
            </div>
        `;

        Swal.fire({
            title: '<strong>Detail Tindak Lanjut</strong>',
            html: detailHtml,
            width: '800px',
            showCloseButton: true,
            showConfirmButton: false,
            customClass: {
                popup: 'text-start'
            }
        });
    }

    // Helper functions
    function getJenisLabel(jenis) {
        const labels = {
            'pencairan_dana': 'Pencairan Dana',
            'disposisi': 'Disposisi',
            'konfirmasi_terima': 'Konfirmasi Terima',
            'konfirmasi_kirim': 'Konfirmasi Kirim',
            'rapat_pembahasan': 'Rapat Pembahasan',
            'penerbitan_sk': 'Penerbitan SK',
            'tandatangan': 'Penandatanganan',
            'verifikasi': 'Verifikasi',
            'approval': 'Approval',
            'revisi': 'Revisi',
            'arsip': 'Pengarsipan',
            'lainnya': 'Lainnya'
        };
        return labels[jenis] || jenis;
    }

    function formatDate(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric' 
        });
    }

    function formatDateTime(dateString) {
        if (!dateString) return '-';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { 
            day: '2-digit', 
            month: 'long', 
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID').format(number);
    }
</script>
@endpush
