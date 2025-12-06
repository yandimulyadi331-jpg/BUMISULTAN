@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0 text-white">
                        <i class="ti ti-cash me-2"></i>Pencairan Dana Operasional
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Info Pengajuan -->
                    <div class="alert alert-info mb-4">
                        <h6 class="alert-heading mb-3">Informasi Pengajuan:</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="d-block"><strong>No. Pengajuan:</strong></small>
                                <span>{{ $pengajuan->nomor_pengajuan }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Tanggal:</strong></small>
                                <span>{{ $pengajuan->tanggal_pengajuan->format('d F Y') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Diajukan oleh:</strong></small>
                                <span>{{ $pengajuan->user->name }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Total Pengajuan:</strong></small>
                                <span class="fs-5 fw-bold text-primary">Rp {{ number_format($pengajuan->total_pengajuan, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rincian Pengajuan -->
                    <div class="mb-4">
                        <h6 class="mb-2">Rincian Kebutuhan:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Uraian</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuan->rincian_kebutuhan as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $item['uraian'] }}</td>
                                        <td class="text-end">Rp {{ number_format($item['nominal'], 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Form Pencairan -->
                    <form action="{{ route('dana-operasional.proses-pencairan', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label required">Nominal yang Dicairkan</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal_cair" class="form-control form-control-lg @error('nominal_cair') is-invalid @enderror" 
                                    value="{{ old('nominal_cair', $pengajuan->total_pengajuan) }}" 
                                    min="0" step="1000" required autofocus>
                            </div>
                            @error('nominal_cair')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Nominal yang dicairkan bisa berbeda dengan pengajuan (bisa lebih besar atau lebih kecil)
                            </small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Catatan Pencairan</label>
                            <textarea name="catatan_pencairan" class="form-control" rows="3" 
                                placeholder="Contoh: Dana sudah ditransfer, silakan dicek...">{{ old('catatan_pencairan') }}</textarea>
                            <small class="text-muted">Catatan atau pesan untuk yang mengajukan</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Bukti Transfer / Foto Uang (Opsional)</label>
                            <input type="file" name="file_pencairan" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, max 2MB</small>
                        </div>

                        <div class="alert alert-success">
                            <i class="ti ti-check-circle me-2"></i>
                            <strong>Konfirmasi:</strong> Dengan menekan tombol "Cairkan Dana", Anda mengkonfirmasi bahwa dana telah diberikan kepada pemohon.
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dana-operasional.show-pengajuan', $pengajuan->id) }}" class="btn btn-label-secondary">
                                <i class="ti ti-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="ti ti-check me-1"></i> Cairkan Dana
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
