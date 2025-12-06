@extends('layouts.app')
@section('titlepage', 'Import Data Keuangan')

@section('content')
@section('navigasi')
    <span><a href="{{ route('keuangan-santri.index') }}">Dompet Santri</a> / Import Data</span>
@endsection

<div class="row">
    <div class="col-lg-10 col-sm-12 mx-auto">
        
        <!-- Instructions Card -->
        <div class="card mb-3">
            <div class="card-header bg-gradient-info">
                <h5 class="text-white mb-0">
                    <i class="ti ti-info-circle me-2"></i>Petunjuk Import
                </h5>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li>Download template Excel terlebih dahulu</li>
                    <li>Isi data sesuai format: Tanggal, Jenis (pemasukan/pengeluaran), Jumlah, Deskripsi</li>
                    <li><strong>Kategori akan terdeteksi otomatis</strong> berdasarkan deskripsi</li>
                    <li>Format tanggal: DD/MM/YYYY atau YYYY-MM-DD</li>
                    <li>Upload file Excel yang sudah diisi</li>
                    <li>Sistem akan memproses dan menambahkan transaksi ke santri yang dipilih</li>
                </ul>
            </div>
        </div>

        <!-- Download Template -->
        <div class="card mb-3">
            <div class="card-header bg-gradient-success">
                <h5 class="text-white mb-0">
                    <i class="ti ti-download me-2"></i>1. Download Template
                </h5>
            </div>
            <div class="card-body">
                <p class="text-muted mb-3">
                    Download template Excel untuk memudahkan proses import data
                </p>
                <a href="{{ route('keuangan-santri.download-template') }}" class="btn btn-success">
                    <i class="ti ti-file-download me-2"></i>Download Template Excel
                </a>
            </div>
        </div>

        <!-- Upload Form -->
        <div class="card">
            <div class="card-header bg-gradient-primary">
                <h5 class="text-white mb-0">
                    <i class="ti ti-upload me-2"></i>2. Upload File Excel
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('keuangan-santri.import') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Pilih Santri -->
                    <div class="form-group mb-3">
                        <label class="form-label">Pilih Santri <span class="text-danger">*</span></label>
                        <select name="santri_id" required class="form-select">
                            <option value="">-- Pilih Santri --</option>
                            @foreach(\App\Models\Santri::orderBy('nama_lengkap')->get() as $santri)
                                <option value="{{ $santri->id }}">{{ $santri->nama_lengkap }} ({{ $santri->nis }})</option>
                            @endforeach
                        </select>
                        <small class="text-muted">
                            Semua transaksi dalam file akan ditambahkan untuk santri ini
                        </small>
                        @error('santri_id')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Upload File -->
                    <div class="form-group mb-3">
                        <label class="form-label">File Excel <span class="text-danger">*</span></label>
                        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="form-control">
                        <small class="text-muted">Format: XLSX, XLS, atau CSV (Max 5MB)</small>
                        @error('file')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- Info Auto-Detect -->
                    <div class="alert alert-success mb-3" role="alert">
                        <h6 class="alert-heading">
                            <i class="ti ti-wand me-2"></i>Fitur Auto-Kategorisasi
                        </h6>
                        <p class="mb-2">Sistem akan otomatis mendeteksi kategori berdasarkan deskripsi:</p>
                        <ul class="mb-0">
                            <li>"Beli sabun" → Kategori: <strong>Kebersihan & Kesehatan</strong></li>
                            <li>"Jajan bakso" → Kategori: <strong>Makanan & Minuman</strong></li>
                            <li>"Bayar SPP" → Kategori: <strong>Pendidikan</strong></li>
                            <li>"Dari orang tua" → Kategori: <strong>Kiriman Keluarga</strong></li>
                        </ul>
                    </div>

                    <!-- Format Table Example -->
                    <div class="card bg-light mb-3">
                        <div class="card-body">
                            <h6 class="card-title">
                                <i class="ti ti-table me-2"></i>Contoh Format Excel:
                            </h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Jumlah</th>
                                            <th>Deskripsi</th>
                                            <th>Metode</th>
                                            <th>Catatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>08/11/2025</td>
                                            <td>pemasukan</td>
                                            <td>500000</td>
                                            <td>Kiriman dari orang tua</td>
                                            <td>Transfer Bank</td>
                                            <td>Uang saku bulan November</td>
                                        </tr>
                                        <tr>
                                            <td>08/11/2025</td>
                                            <td>pengeluaran</td>
                                            <td>15000</td>
                                            <td>Beli sabun mandi</td>
                                            <td>Tunai</td>
                                            <td>-</td>
                                        </tr>
                                        <tr>
                                            <td>09/11/2025</td>
                                            <td>pengeluaran</td>
                                            <td>20000</td>
                                            <td>Jajan bakso dan es teh</td>
                                            <td>Tunai</td>
                                            <td>-</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-upload me-2"></i>Upload & Import Data
                        </button>
                        <a href="{{ route('keuangan-santri.index') }}" class="btn btn-secondary">
                            <i class="ti ti-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
