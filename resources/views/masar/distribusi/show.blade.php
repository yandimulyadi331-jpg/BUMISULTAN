@extends('layouts.app')

@section('title', 'Detail Distribusi Hadiah - MASAR')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('masar.index') }}">MASAR</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('masar.distribusi.index') }}">Distribusi Hadiah</a>
            </li>
            <li class="breadcrumb-item active">Detail Distribusi</li>
        </ol>
    </nav>

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h4 class="fw-bold">Detail Distribusi Hadiah</h4>
            <p class="text-muted">{{ $distribusi->nomor_distribusi }}</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('masar.distribusi.edit', Crypt::encrypt($distribusi->id)) }}" class="btn btn-warning btn-sm">
                <i class="ti ti-edit me-2"></i> Edit
            </a>
            <a href="{{ route('masar.distribusi.index') }}" class="btn btn-secondary btn-sm">
                <i class="ti ti-arrow-left me-2"></i> Kembali
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-md-8">
            <!-- Informasi Umum -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Umum</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nomor Distribusi</label>
                            <p class="fw-bold">{{ $distribusi->nomor_distribusi }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Tanggal Distribusi</label>
                            <p class="fw-bold">{{ $distribusi->tanggal_distribusi->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Status Distribusi</label>
                            <p>
                                @if($distribusi->status_distribusi === 'diterima')
                                    <span class="badge bg-success">Diterima</span>
                                @elseif($distribusi->status_distribusi === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Metode Distribusi</label>
                            <p>
                                @if($distribusi->metode_distribusi === 'langsung')
                                    <span class="badge bg-primary">Langsung</span>
                                @elseif($distribusi->metode_distribusi === 'undian')
                                    <span class="badge bg-info">Undian</span>
                                @elseif($distribusi->metode_distribusi === 'prestasi')
                                    <span class="badge bg-success">Prestasi</span>
                                @else
                                    <span class="badge bg-warning">Kehadiran</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Hadiah -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Hadiah</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Hadiah</label>
                            <p class="fw-bold">{{ $distribusi->hadiah->nama_hadiah }}</p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Jumlah</label>
                            <p class="fw-bold">{{ $distribusi->jumlah }} <small class="text-muted">pcs</small></p>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted">Ukuran</label>
                            <p class="fw-bold">{{ $distribusi->ukuran ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Jenis Hadiah</label>
                            <p class="fw-bold">{{ ucfirst($distribusi->hadiah->jenis_hadiah) }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Warna</label>
                            <p class="fw-bold">{{ $distribusi->hadiah->warna ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Penerima -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Penerima</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted">Nama Penerima</label>
                            <p class="fw-bold">{{ $distribusi->penerima }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted">Jamaah</label>
                            <p class="fw-bold">
                                @if($distribusi->jamaah)
                                    <a href="{{ route('masar.jamaah.show', Crypt::encrypt($distribusi->jamaah->id)) }}" class="text-decoration-none">
                                        {{ $distribusi->jamaah->nama }}
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Petugas -->
            <div class="card mb-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Petugas</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label class="form-label text-muted">Petugas Distribusi</label>
                            <p class="fw-bold">{{ $distribusi->petugas_distribusi }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            @if($distribusi->keterangan)
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Keterangan</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">{{ nl2br($distribusi->keterangan) }}</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-md-4">
            <!-- Timeline Aktivitas -->
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Timeline</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Dibuat pada</small>
                                <p class="mb-0 fw-bold">{{ $distribusi->created_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <small class="text-muted">Diperbarui pada</small>
                                <p class="mb-0 fw-bold">{{ $distribusi->updated_at->format('d-m-Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Aksi Cepat -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Aksi</h5>
                </div>
                <div class="card-body d-grid gap-2">
                    <a href="{{ route('masar.distribusi.edit', Crypt::encrypt($distribusi->id)) }}" class="btn btn-warning btn-sm">
                        <i class="ti ti-edit me-2"></i> Edit Distribusi
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" id="delete-btn">
                        <i class="ti ti-trash me-2"></i> Hapus Distribusi
                    </button>
                </div>
            </div>

            <!-- Info Hadiah -->
            <div class="card mt-3">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Informasi Stok Hadiah</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Stok Awal</label>
                        <p class="fw-bold">{{ $distribusi->hadiah->stok_awal }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Stok Tersedia</label>
                        <p class="fw-bold">{{ $distribusi->hadiah->stok_tersedia }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Persentase Tersisa</label>
                        <div class="progress" style="height: 25px;">
                            @php
                                $percentage = $distribusi->hadiah->stok_awal > 0 
                                    ? ($distribusi->hadiah->stok_tersedia / $distribusi->hadiah->stok_awal) * 100 
                                    : 0;
                                $color = $percentage > 50 ? 'success' : ($percentage > 20 ? 'warning' : 'danger');
                            @endphp
                            <div class="progress-bar bg-{{ $color }}" style="width: {{ $percentage }}%">
                                {{ number_format($percentage, 1) }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus distribusi hadiah <strong>{{ $distribusi->nomor_distribusi }}</strong>?</p>
                <p class="text-muted">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">Hapus</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    $(document).ready(function () {
        $('#delete-btn').on('click', function () {
            $('#deleteModal').modal('show');
        });

        $('#confirm-delete').on('click', function () {
            $.ajax({
                url: '{{ route("masar.distribusi.destroy", Crypt::encrypt($distribusi->id)) }}',
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '{{ route("masar.distribusi.index") }}';
                        });
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        title: 'Gagal!',
                        text: xhr.responseJSON?.message || 'Terjadi kesalahan',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        });
    });
</script>
@endsection
