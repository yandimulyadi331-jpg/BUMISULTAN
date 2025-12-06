@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning">
                    <h5 class="mb-0">
                        <i class="ti ti-receipt me-2"></i>Tambah Realisasi Pengeluaran
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Info Pengajuan -->
                    <div class="alert alert-info mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <small class="d-block"><strong>No. Pengajuan:</strong></small>
                                <span>{{ $pengajuan->nomor_pengajuan }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Dana Dicairkan:</strong></small>
                                <span class="text-success fw-bold">Rp {{ number_format($pengajuan->nominal_cair, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <small class="d-block"><strong>Total Realisasi:</strong></small>
                                <span class="text-warning fw-bold">Rp {{ number_format($pengajuan->total_realisasi, 0, ',', '.') }}</span>
                            </div>
                            <div class="col-md-6">
                                <small class="d-block"><strong>Sisa Dana:</strong></small>
                                <span class="{{ $pengajuan->sisa_dana >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                                    Rp {{ number_format(abs($pengajuan->sisa_dana), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Realisasi yang sudah ada -->
                    @if($pengajuan->realisasi->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-2">Realisasi Sebelumnya:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Uraian</th>
                                        <th class="text-end">Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($pengajuan->realisasi as $real)
                                    <tr>
                                        <td>{{ $real->tanggal_realisasi->format('d/m/Y') }}</td>
                                        <td>{{ $real->uraian }}</td>
                                        <td class="text-end">Rp {{ number_format($real->nominal, 0, ',', '.') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endif

                    <hr class="my-4">

                    <!-- Form Realisasi Baru -->
                    <h6 class="mb-3">Tambah Realisasi Baru:</h6>
                    <form action="{{ route('dana-operasional.store-realisasi', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Realisasi</label>
                                <input type="date" name="tanggal_realisasi" class="form-control @error('tanggal_realisasi') is-invalid @enderror" 
                                    value="{{ old('tanggal_realisasi', date('Y-m-d')) }}" required>
                                @error('tanggal_realisasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kategori</label>
                                <select name="kategori" class="form-select">
                                    <option value="">- Pilih Kategori -</option>
                                    <option value="Transport">Transport</option>
                                    <option value="Makan/Minum">Makan/Minum</option>
                                    <option value="ATK">ATK (Alat Tulis Kantor)</option>
                                    <option value="Maintenance">Maintenance/Perbaikan</option>
                                    <option value="Konsumsi">Konsumsi</option>
                                    <option value="Komunikasi">Komunikasi</option>
                                    <option value="Lain-lain">Lain-lain</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Uraian Pengeluaran</label>
                            <input type="text" name="uraian" class="form-control @error('uraian') is-invalid @enderror" 
                                value="{{ old('uraian') }}" 
                                placeholder="Contoh: Bensin motor untuk kirim dokumen ke kantor pusat" required>
                            @error('uraian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Jelaskan secara detail untuk apa pengeluaran ini</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label required">Nominal Pengeluaran</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text">Rp</span>
                                <input type="number" name="nominal" class="form-control form-control-lg @error('nominal') is-invalid @enderror" 
                                    value="{{ old('nominal') }}" 
                                    min="0" step="100" required 
                                    max="{{ $pengajuan->sisa_dana }}"
                                    placeholder="0">
                            </div>
                            @error('nominal')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            @if($pengajuan->sisa_dana > 0)
                            <small class="text-muted">
                                <i class="ti ti-info-circle me-1"></i>
                                Sisa dana yang tersedia: Rp {{ number_format($pengajuan->sisa_dana, 0, ',', '.') }}
                            </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" class="form-control" rows="2" 
                                placeholder="Informasi tambahan tentang pengeluaran ini...">{{ old('keterangan') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Foto Struk / Nota Pembelian</label>
                            <input type="file" name="file_bukti" class="form-control" accept="image/*">
                            <small class="text-muted">Upload foto struk sebagai bukti pengeluaran (Format: JPG, PNG, max 2MB)</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dana-operasional.show-pengajuan', $pengajuan->id) }}" class="btn btn-label-secondary">
                                <i class="ti ti-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Realisasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
