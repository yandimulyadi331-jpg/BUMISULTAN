@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">

    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <a href="{{ route('temuan.index') }}" class="btn btn-link btn-sm mb-3">
                <i class="ti ti-arrow-left me-1"></i> Kembali ke Daftar
            </a>
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h3 class="text-white mb-2">
                        <i class="ti ti-alert-circle me-2"></i>{{ $temuan->judul }}
                    </h3>
                    <p class="mb-0">
                        <span class="badge bg-white text-primary me-2">{{ $temuan->getStatusLabel() }}</span>
                        <span class="badge bg-white text-primary">{{ $temuan->getUrgensiLabel() }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ti ti-check-circle me-2"></i>
            <strong>Berhasil!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ti ti-alert-circle me-2"></i>
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        {{-- Content Column --}}
        <div class="col-lg-8">
            {{-- Detail Information --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-info-circle me-2"></i>Informasi Temuan
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Tanggal Temuan</label>
                            <p class="mb-3">{{ $temuan->tanggal_temuan->format('d M Y H:i') }}</p>

                            <label class="form-label text-muted small">Pelapor</label>
                            <p class="mb-3">
                                <strong>{{ $temuan->pelapor->name }}</strong><br>
                                <small class="text-muted">{{ $temuan->pelapor->email }}</small>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small">Lokasi Temuan</label>
                            <p class="mb-3"><strong>{{ $temuan->lokasi }}</strong></p>

                            <label class="form-label text-muted small">Tingkat Urgensi</label>
                            <p class="mb-3">
                                <span class="badge bg-{{ $temuan->getUrgensiBadgeColor() }} p-2">
                                    {{ $temuan->getUrgensiLabel() }}
                                </span>
                            </p>
                        </div>
                    </div>

                    {{-- Photo --}}
                    @if($temuan->foto_path)
                        <label class="form-label text-muted small d-block mb-2">Foto Bukti</label>
                        <div class="mb-4">
                            <img src="{{ Storage::url($temuan->foto_path) }}" 
                                 alt="Foto Temuan" class="img-fluid rounded" style="max-height: 400px;">
                        </div>
                    @endif

                    {{-- Deskripsi --}}
                    <label class="form-label text-muted small">Deskripsi Lengkap</label>
                    <div class="p-3 bg-light rounded mb-4">
                        <p>{{ nl2br($temuan->deskripsi) }}</p>
                    </div>

                    {{-- Catatan Admin --}}
                    @if($temuan->catatan_admin)
                        <label class="form-label text-muted small">Catatan Admin</label>
                        <div class="alert alert-info mb-4">
                            <p class="mb-0">{{ nl2br($temuan->catatan_admin) }}</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Timeline --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-timeline me-2"></i>Riwayat Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker">
                                <i class="ti ti-circle-dot-filled" style="color: #0d6efd;"></i>
                            </div>
                            <div class="timeline-content">
                                <strong>Dilaporkan</strong><br>
                                <small class="text-muted">{{ $temuan->tanggal_temuan->format('d M Y H:i') }}</small>
                            </div>
                        </div>

                        @if($temuan->tanggal_ditindaklanjuti)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="ti ti-circle-dot-filled" style="color: #ffc107;"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Ditindaklanjuti</strong><br>
                                    <small class="text-muted">{{ $temuan->tanggal_ditindaklanjuti->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                        @endif

                        @if($temuan->tanggal_selesai)
                            <div class="timeline-item">
                                <div class="timeline-marker">
                                    <i class="ti ti-circle-dot-filled" style="color: #198754;"></i>
                                </div>
                                <div class="timeline-content">
                                    <strong>Selesai</strong><br>
                                    <small class="text-muted">{{ $temuan->tanggal_selesai->format('d M Y H:i') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Sidebar Column --}}
        <div class="col-lg-4">
            {{-- Update Status Card --}}
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ti ti-edit me-2"></i>Update Status
                    </h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('temuan.updateStatus', $temuan->id) }}">
                        @csrf @method('PUT')

                        <div class="mb-3">
                            <label class="form-label" for="status">Ubah Status</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="baru" {{ $temuan->status == 'baru' ? 'selected' : '' }}>Baru</option>
                                <option value="sedang_diproses" {{ $temuan->status == 'sedang_diproses' ? 'selected' : '' }}>Sedang Diproses</option>
                                <option value="sudah_diperbaiki" {{ $temuan->status == 'sudah_diperbaiki' ? 'selected' : '' }}>Sudah Diperbaiki</option>
                                <option value="tindaklanjuti" {{ $temuan->status == 'tindaklanjuti' ? 'selected' : '' }}>Tindaklanjuti</option>
                                <option value="selesai" {{ $temuan->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="catatan_admin">Catatan Perbaikan</label>
                            <textarea class="form-control @error('catatan_admin') is-invalid @enderror" 
                                      id="catatan_admin" name="catatan_admin" rows="5" 
                                      placeholder="Tuliskan catatan atau progress perbaikan...">{{ $temuan->catatan_admin }}</textarea>
                            @error('catatan_admin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-check me-1"></i> Simpan Perubahan
                        </button>
                    </form>
                </div>
            </div>

            {{-- Info Penanganan --}}
            @if($temuan->admin_id)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ti ti-user-check me-2"></i>Ditangani Oleh
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2">
                            <strong>{{ $temuan->admin->name }}</strong><br>
                            <small class="text-muted">{{ $temuan->admin->email }}</small>
                        </p>
                    </div>
                </div>
            @endif

            {{-- Delete Button --}}
            <div class="card card-danger">
                <div class="card-header">
                    <h5 class="card-title mb-0 text-danger">
                        <i class="ti ti-trash me-2"></i>Hapus Temuan
                    </h5>
                </div>
                <div class="card-body">
                    <p class="small text-muted mb-3">Menghapus temuan tidak bisa dibatalkan.</p>
                    <form method="POST" action="{{ route('temuan.destroy', $temuan->id) }}" 
                          onsubmit="return confirm('Yakin ingin menghapus temuan ini? Tindakan ini tidak bisa dibatalkan.');">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100">
                            <i class="ti ti-trash me-1"></i> Hapus Temuan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 0;
}

.timeline-item {
    display: flex;
    margin-bottom: 2rem;
    position: relative;
}

.timeline-item::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 40px;
    width: 2px;
    height: calc(100% + 20px);
    background-color: #e9ecef;
}

.timeline-item:last-child::before {
    display: none;
}

.timeline-marker {
    position: relative;
    z-index: 1;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-radius: 50%;
    margin-right: 20px;
}

.timeline-marker i {
    font-size: 16px;
}

.timeline-content {
    padding-top: 5px;
}
</style>
@endsection
