<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-none">
            <div class="card-body p-0">
                <!-- Header Info -->
                <div class="alert alert-{{ $history->getJenisAktivitasColor() }} mb-4">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="ti ti-history fs-1"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $history->getJenisAktivitasLabel() }}</h5>
                            <p class="mb-0">
                                <i class="ti ti-calendar"></i> {{ $history->created_at->format('d F Y, H:i') }} WIB
                                <span class="ms-2 text-muted">({{ $history->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Informasi Barang -->
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-package me-2"></i>Informasi Barang</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Kode Inventaris</small>
                                <p class="mb-0 fw-bold">{{ $history->inventaris->kode_inventaris }}</p>
                            </div>
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Nama Barang</small>
                                <p class="mb-0 fw-bold">{{ $history->inventaris->nama_barang }}</p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Kategori</small>
                                <p class="mb-0">{{ $history->inventaris->kategori }}</p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Lokasi</small>
                                <p class="mb-0">{{ $history->inventaris->cabang->nama_cabang ?? '-' }}</p>
                            </div>
                            <div class="col-md-4 mb-2">
                                <small class="text-muted">Status Saat Ini</small>
                                <p class="mb-0">
                                    <span class="badge bg-{{ $history->inventaris->status == 'tersedia' ? 'success' : 'warning' }}">
                                        {{ ucfirst($history->inventaris->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Aktivitas -->
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-info-circle me-2"></i>Detail Aktivitas</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Deskripsi</small>
                            <p class="mb-0">{{ $history->deskripsi }}</p>
                        </div>

                        @if($history->status_sebelum || $history->status_sesudah)
                        <div class="row mb-3">
                            @if($history->status_sebelum)
                            <div class="col-md-6">
                                <small class="text-muted">Status Sebelum</small>
                                <p class="mb-0">
                                    <span class="badge bg-secondary">{{ $history->status_sebelum }}</span>
                                </p>
                            </div>
                            @endif
                            @if($history->status_sesudah)
                            <div class="col-md-6">
                                <small class="text-muted">Status Sesudah</small>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ $history->status_sesudah }}</span>
                                </p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($history->lokasi_sebelum || $history->lokasi_sesudah)
                        <div class="row mb-3">
                            @if($history->lokasi_sebelum)
                            <div class="col-md-6">
                                <small class="text-muted">Lokasi Sebelum</small>
                                <p class="mb-0">{{ $history->lokasi_sebelum }}</p>
                            </div>
                            @endif
                            @if($history->lokasi_sesudah)
                            <div class="col-md-6">
                                <small class="text-muted">Lokasi Sesudah</small>
                                <p class="mb-0">{{ $history->lokasi_sesudah }}</p>
                            </div>
                            @endif
                        </div>
                        @endif

                        @if($history->jumlah)
                        <div class="mb-3">
                            <small class="text-muted">Jumlah</small>
                            <p class="mb-0">{{ $history->jumlah }} unit</p>
                        </div>
                        @endif

                        @if($history->data_perubahan)
                        <div class="mb-3">
                            <small class="text-muted">Detail Perubahan</small>
                            <div class="mt-2">
                                @foreach($history->data_perubahan as $key => $value)
                                <div class="d-flex justify-content-between border-bottom py-1">
                                    <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                    <span class="fw-medium">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Informasi Terkait -->
                @if($history->peminjaman || $history->pengembalian || $history->karyawan || $history->user)
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-users me-2"></i>Informasi Terkait</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($history->user)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Diproses Oleh</small>
                                <p class="mb-0">
                                    <i class="ti ti-user-circle"></i> {{ $history->user->name }}
                                </p>
                            </div>
                            @endif

                            @if($history->karyawan)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Karyawan Terkait</small>
                                <p class="mb-0">
                                    <i class="ti ti-id-badge"></i> {{ $history->karyawan->nama_lengkap }}
                                </p>
                            </div>
                            @endif

                            @if($history->peminjaman)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Kode Peminjaman</small>
                                <p class="mb-0">
                                    <a href="{{ route('peminjaman-inventaris.show', $history->peminjaman_id) }}" target="_blank">
                                        {{ $history->peminjaman->kode_peminjaman }}
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </p>
                            </div>
                            @endif

                            @if($history->pengembalian)
                            <div class="col-md-6 mb-2">
                                <small class="text-muted">Kode Pengembalian</small>
                                <p class="mb-0">
                                    <a href="{{ route('pengembalian-inventaris.show', $history->pengembalian_id) }}" target="_blank">
                                        {{ $history->pengembalian->kode_pengembalian }}
                                        <i class="ti ti-external-link"></i>
                                    </a>
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Foto Bukti -->
                @if($history->foto)
                <div class="card mb-3 border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="ti ti-photo me-2"></i>Foto Bukti</h6>
                    </div>
                    <div class="card-body text-center">
                        <img src="{{ Storage::url($history->foto) }}" alt="Foto History" class="img-fluid rounded" style="max-height:400px;">
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-3">
    <button type="button" class="btn btn-secondary" onclick="$('#modalDetail').modal('hide')">
        <i class="ti ti-x me-1"></i>Tutup
    </button>
</div>
