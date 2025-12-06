@extends('layouts.app')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ti ti-file-plus me-2"></i>Buat Pengajuan Dana Operasional
                    </h5>
                    <a href="{{ route('dana-operasional.index') }}" class="btn btn-sm btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    <!-- Info Saldo Kemarin -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ti ti-info-circle ti-md me-3"></i>
                            <div>
                                <strong>Saldo Kemarin:</strong> 
                                <span class="fs-5 {{ $saldoKemarin >= 0 ? 'text-success' : 'text-danger' }}">
                                    Rp {{ number_format(abs($saldoKemarin), 0, ',', '.') }}
                                </span>
                                @if($saldoKemarin < 0)
                                    <span class="badge bg-danger ms-2">Kekurangan</span>
                                @else
                                    <span class="badge bg-success ms-2">Sisa Dana</span>
                                @endif
                            </div>
                        </div>
                        <small class="d-block mt-2">
                            <i class="ti ti-bulb me-1"></i>
                            Saldo ini akan otomatis ditambahkan ke pengajuan hari ini
                        </small>
                    </div>

                    <form action="{{ route('dana-operasional.store-pengajuan') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label required">Tanggal Pengajuan</label>
                                <input type="date" name="tanggal_pengajuan" class="form-control @error('tanggal_pengajuan') is-invalid @enderror" 
                                    value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required>
                                @error('tanggal_pengajuan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Rincian Kebutuhan -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Rincian Kebutuhan Operasional</h6>
                                <button type="button" class="btn btn-sm btn-primary" onclick="tambahRincian()">
                                    <i class="ti ti-plus me-1"></i> Tambah Item
                                </button>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="tableRincian">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="50%">Uraian Kebutuhan</th>
                                            <th width="35%">Nominal (Rp)</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="rincianContainer">
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>
                                                <input type="text" name="rincian[0][uraian]" class="form-control" 
                                                    placeholder="Contoh: Bensin kendaraan operasional" required>
                                            </td>
                                            <td>
                                                <input type="number" name="rincian[0][nominal]" class="form-control nominal-input" 
                                                    placeholder="0" min="0" step="1000" required onchange="hitungTotal()">
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-danger" onclick="hapusRincian(this)" disabled>
                                                    <i class="ti ti-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="2" class="text-end">TOTAL PENGAJUAN:</th>
                                            <th>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="text" id="totalPengajuan" class="form-control fw-bold" 
                                                        value="0" readonly>
                                                </div>
                                            </th>
                                            <th></th>
                                        </tr>
                                        @if($saldoKemarin != 0)
                                        <tr>
                                            <th colspan="2" class="text-end">Saldo Kemarin:</th>
                                            <th>
                                                <span class="{{ $saldoKemarin >= 0 ? 'text-success' : 'text-danger' }}">
                                                    Rp {{ number_format(abs($saldoKemarin), 0, ',', '.') }}
                                                </span>
                                            </th>
                                            <th></th>
                                        </tr>
                                        <tr class="table-primary">
                                            <th colspan="2" class="text-end">TOTAL KEBUTUHAN DANA:</th>
                                            <th>
                                                <span id="totalKebutuhan" class="fs-5 fw-bold">Rp 0</span>
                                            </th>
                                            <th></th>
                                        </tr>
                                        @endif
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <!-- Keterangan -->
                        <div class="mb-3">
                            <label class="form-label">Keterangan Tambahan</label>
                            <textarea name="keterangan" class="form-control" rows="3" 
                                placeholder="Catatan atau informasi tambahan tentang pengajuan ini...">{{ old('keterangan') }}</textarea>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label class="form-label">Screenshot Pengajuan WA (Opsional)</label>
                            <input type="file" name="file_pengajuan" class="form-control" accept="image/*">
                            <small class="text-muted">Format: JPG, PNG, max 2MB</small>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('dana-operasional.index') }}" class="btn btn-label-secondary">
                                <i class="ti ti-x me-1"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ti ti-device-floppy me-1"></i> Simpan Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let rincianIndex = 1;
const saldoKemarin = {{ $saldoKemarin }};

function tambahRincian() {
    const container = document.getElementById('rincianContainer');
    const newRow = `
        <tr>
            <td class="text-center">${rincianIndex + 1}</td>
            <td>
                <input type="text" name="rincian[${rincianIndex}][uraian]" class="form-control" 
                    placeholder="Uraian kebutuhan..." required>
            </td>
            <td>
                <input type="number" name="rincian[${rincianIndex}][nominal]" class="form-control nominal-input" 
                    placeholder="0" min="0" step="1000" required onchange="hitungTotal()">
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger" onclick="hapusRincian(this)">
                    <i class="ti ti-trash"></i>
                </button>
            </td>
        </tr>
    `;
    container.insertAdjacentHTML('beforeend', newRow);
    rincianIndex++;
    updateRowNumbers();
}

function hapusRincian(btn) {
    btn.closest('tr').remove();
    updateRowNumbers();
    hitungTotal();
}

function updateRowNumbers() {
    const rows = document.querySelectorAll('#rincianContainer tr');
    rows.forEach((row, index) => {
        row.querySelector('td:first-child').textContent = index + 1;
    });
}

function hitungTotal() {
    const nominalInputs = document.querySelectorAll('.nominal-input');
    let total = 0;
    
    nominalInputs.forEach(input => {
        const value = parseFloat(input.value) || 0;
        total += value;
    });
    
    // Format untuk display
    document.getElementById('totalPengajuan').value = total.toLocaleString('id-ID');
    
    // Hitung total kebutuhan (termasuk saldo kemarin)
    const totalKebutuhan = total + saldoKemarin;
    const totalKebutuhanEl = document.getElementById('totalKebutuhan');
    if (totalKebutuhanEl) {
        totalKebutuhanEl.textContent = 'Rp ' + Math.abs(totalKebutuhan).toLocaleString('id-ID');
        totalKebutuhanEl.className = totalKebutuhan >= 0 ? 'fs-5 fw-bold text-success' : 'fs-5 fw-bold text-danger';
    }
}

// Hitung total saat halaman load
document.addEventListener('DOMContentLoaded', function() {
    hitungTotal();
});
</script>
@endpush
@endsection
