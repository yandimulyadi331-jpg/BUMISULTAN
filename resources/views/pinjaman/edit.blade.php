@extends('layouts.app')

@section('content')
<style>
    .form-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        border-left: 4px solid #007bff;
    }
    .form-section h5 {
        color: #007bff;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .required::after {
        content: " *";
        color: red;
    }
    #form-non-crew {
        display: none;
    }
</style>

<!-- Header -->
<div class="bg-primary" style="padding: 2rem 0; margin: -1.5rem -1.5rem 1.5rem -1.5rem;">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="text-white mb-1">
                    <i class="bi bi-pencil-square"></i> Edit Pinjaman
                </h3>
                <p class="text-white-50 mb-0">{{ $pinjaman->nomor_pinjaman }}</p>
            </div>
            <div>
                <a href="{{ route('pinjaman.show', $pinjaman->id) }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show">
    <h5><i class="bi bi-exclamation-triangle"></i> Terdapat Kesalahan:</h5>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<form action="{{ route('pinjaman.update', $pinjaman->id) }}" method="POST" enctype="multipart/form-data" id="formPinjaman">
    @csrf
    @method('PUT')

    <!-- Kategori Peminjam -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-person-badge"></i> 1. Kategori Peminjam</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-label required">Kategori Peminjam</label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" class="btn-check" name="kategori_peminjam" id="kategori_crew" value="crew" 
                            {{ old('kategori_peminjam', 'crew') == 'crew' ? 'checked' : '' }} required>
                        <label class="btn btn-outline-primary" for="kategori_crew">
                            <i class="bi bi-person-vcard"></i> CREW / Karyawan
                        </label>

                        <input type="radio" class="btn-check" name="kategori_peminjam" id="kategori_non_crew" value="non_crew"
                            {{ old('kategori_peminjam') == 'non_crew' ? 'checked' : '' }}>
                        <label class="btn btn-outline-secondary" for="kategori_non_crew">
                            <i class="bi bi-person"></i> NON-CREW / Umum
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Peminjam - Crew -->
    <div class="card mb-4" id="form-crew">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> 2. Data Peminjam (Crew)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label required">Pilih Karyawan</label>
                        <select name="karyawan_id_select" id="karyawan_id_select" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->nik }}" 
                                data-nama="{{ $karyawan->nama_karyawan }}"
                                {{ old('karyawan_id', $pinjaman->karyawan_id) == $karyawan->nik ? 'selected' : '' }}>
                                {{ $karyawan->nik_show ?? $karyawan->nik }} - {{ $karyawan->nama_karyawan }}
                                @if($karyawan->kode_jabatan)
                                    ({{ $karyawan->jabatan->nama_jabatan ?? '' }})
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <small class="text-muted">Pilih nama karyawan dari database untuk integrasi dengan payroll</small>
                    </div>
                </div>
            </div>
            
            <!-- Hidden field untuk karyawan_id -->
            <input type="hidden" name="karyawan_id" id="karyawan_id" value="{{ old('karyawan_id', $pinjaman->karyawan_id) }}">
            
            <!-- Preview data karyawan yang dipilih -->
            <div id="preview_karyawan_edit" class="alert alert-info">
                <strong><i class="bi bi-person-check"></i> Data Karyawan Terpilih:</strong><br>
                <span id="preview_nama_edit">{{ $pinjaman->nama_peminjam_lengkap }}</span><br>
                <span id="preview_nik_edit">NIK: {{ $pinjaman->karyawan_id }}</span>
            </div>
        </div>
    </div>

    <!-- Data Peminjam - Non Crew -->
    <div class="card mb-4" id="form-non-crew">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> 2. Data Peminjam (Non-Crew)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_peminjam" class="form-control" value="{{ old('nama_peminjam') }}" 
                            placeholder="Nama lengkap sesuai KTP">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">NIK (KTP)</label>
                        <input type="text" name="nik_peminjam" class="form-control" value="{{ old('nik_peminjam') }}" 
                            placeholder="16 digit NIK" maxlength="16">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">No. Telepon</label>
                        <input type="text" name="no_telp_peminjam" class="form-control" value="{{ old('no_telp_peminjam') }}" 
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Pekerjaan</label>
                        <input type="text" name="pekerjaan_peminjam" class="form-control" value="{{ old('pekerjaan_peminjam') }}" 
                            placeholder="Contoh: Wiraswasta">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label required">Alamat Lengkap</label>
                        <textarea name="alamat_peminjam" class="form-control" rows="3" 
                            placeholder="Alamat lengkap sesuai KTP">{{ old('alamat_peminjam') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Pinjaman -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="bi bi-cash-stack"></i> 3. Detail Pinjaman</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label required">Tanggal Pengajuan</label>
                        <input type="date" name="tanggal_pengajuan" class="form-control" 
                            value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" required>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label required">Jumlah Pinjaman</label>
                        <input type="number" name="jumlah_pengajuan" class="form-control" 
                            value="{{ old('jumlah_pengajuan') }}" placeholder="Contoh: 5000000" 
                            min="100000" step="100000" required id="jumlah_pengajuan">
                        <small class="text-muted">Min. Rp 100.000</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label required">Tenor (Bulan)</label>
                        <select name="tenor_bulan" class="form-select" required id="tenor_bulan">
                            <option value="">-- Pilih Tenor --</option>
                            <option value="3" {{ old('tenor_bulan') == 3 ? 'selected' : '' }}>3 Bulan</option>
                            <option value="6" {{ old('tenor_bulan') == 6 ? 'selected' : '' }}>6 Bulan</option>
                            <option value="12" {{ old('tenor_bulan', 12) == 12 ? 'selected' : '' }}>12 Bulan</option>
                            <option value="18" {{ old('tenor_bulan') == 18 ? 'selected' : '' }}>18 Bulan</option>
                            <option value="24" {{ old('tenor_bulan') == 24 ? 'selected' : '' }}>24 Bulan</option>
                            <option value="36" {{ old('tenor_bulan') == 36 ? 'selected' : '' }}>36 Bulan</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label required">
                            Tanggal Jatuh Tempo Setiap Bulan
                            <i class="bi bi-info-circle text-primary" title="Tanggal potongan cicilan setiap bulannya"></i>
                        </label>
                        <select name="tanggal_jatuh_tempo_setiap_bulan" class="form-select" required>
                            <option value="">-- Pilih Tanggal --</option>
                            @for($i = 1; $i <= 31; $i++)
                            <option value="{{ $i }}" {{ old('tanggal_jatuh_tempo_setiap_bulan', 1) == $i ? 'selected' : '' }}>
                                Tanggal {{ $i }} setiap bulan
                            </option>
                            @endfor
                        </select>
                        <small class="text-muted">
                            <i class="bi bi-calendar-event"></i> Contoh: Pilih tanggal 5 = cicilan jatuh tempo tanggal 5 setiap bulan
                        </small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Estimasi Cicilan/Bulan</label>
                        <input type="text" class="form-control bg-light" id="estimasi_cicilan" readonly 
                            value="Rp 0">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label required">Tujuan Pinjaman</label>
                        <textarea name="tujuan_pinjaman" class="form-control" rows="3" required
                            placeholder="Jelaskan tujuan penggunaan pinjaman...">{{ old('tujuan_pinjaman') }}</textarea>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan</label>
                        <textarea name="keterangan" class="form-control" rows="2" 
                            placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-paperclip"></i> 4. Dokumen Pendukung</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Upload KTP</label>
                        <input type="file" name="dokumen_ktp" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Upload Slip Gaji <small>(Untuk Crew)</small></label>
                        <input type="file" name="dokumen_slip_gaji" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Dokumen Pendukung Lain</label>
                        <input type="file" name="dokumen_pendukung_lain" class="form-control" accept="image/*,.pdf">
                        <small class="text-muted">Format: JPG, PNG, PDF (Max 2MB)</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Penjamin (Opsional) -->
    <div class="card mb-4">
        <div class="card-header bg-dark text-white">
            <h5 class="mb-0"><i class="bi bi-shield-check"></i> 5. Data Penjamin (Opsional)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Penjamin</label>
                        <input type="text" name="nama_penjamin" class="form-control" 
                            value="{{ old('nama_penjamin') }}" placeholder="Nama lengkap penjamin">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">Hubungan</label>
                        <input type="text" name="hubungan_penjamin" class="form-control" 
                            value="{{ old('hubungan_penjamin') }}" placeholder="Contoh: Saudara">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label class="form-label">No. Telepon Penjamin</label>
                        <input type="text" name="no_telp_penjamin" class="form-control" 
                            value="{{ old('no_telp_penjamin') }}" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Alamat Penjamin</label>
                        <textarea name="alamat_penjamin" class="form-control" rows="2" 
                            placeholder="Alamat lengkap penjamin">{{ old('alamat_penjamin') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tombol Submit -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <a href="{{ route('pinjaman.index') }}" class="btn btn-secondary btn-lg">
                    <i class="bi bi-x-circle"></i> Batal
                </a>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-send"></i> Ajukan Pinjaman
                </button>
            </div>
        </div>
    </div>
</form>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle form berdasarkan kategori peminjam
    function toggleFormKategori() {
        const kategori = $('input[name="kategori_peminjam"]:checked').val();
        
        if (kategori === 'crew') {
            $('#form-crew').show();
            $('#form-non-crew').hide();
            $('#karyawan_id_select').prop('required', true);
            $('[name="nama_peminjam"], [name="nik_peminjam"], [name="alamat_peminjam"], [name="no_telp_peminjam"], [name="pekerjaan_peminjam"]').prop('required', false);
        } else {
            $('#form-crew').hide();
            $('#form-non-crew').show();
            $('#karyawan_id_select').prop('required', false);
            $('[name="nama_peminjam"], [name="nik_peminjam"], [name="alamat_peminjam"], [name="no_telp_peminjam"], [name="pekerjaan_peminjam"]').prop('required', true);
        }
    }

    // Event listener untuk perubahan kategori
    $('input[name="kategori_peminjam"]').on('change', toggleFormKategori);
    
    // Inisialisasi saat load
    toggleFormKategori();

    // Hitung estimasi cicilan
    function hitungEstimasiCicilan() {
        const jumlah = parseFloat($('#jumlah_pengajuan').val()) || 0;
        const tenor = parseInt($('#tenor_bulan').val()) || 1;

        if (jumlah > 0 && tenor > 0) {
            // Tanpa bunga - cicilan tetap
            const cicilan = jumlah / tenor;
            $('#estimasi_cicilan').val('Rp ' + Math.round(cicilan).toLocaleString('id-ID'));
        } else {
            $('#estimasi_cicilan').val('Rp 0');
        }
    }

    // Event listener untuk perhitungan cicilan
    $('#jumlah_pengajuan, #tenor_bulan').on('input change', hitungEstimasiCicilan);

    // Hitung saat load jika ada nilai
    hitungEstimasiCicilan();

    // Validasi NIK 16 digit
    $('[name="nik_peminjam"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
    });

    // Format nomor telepon
    $('[name="no_telp_peminjam"], [name="no_telp_penjamin"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // AUTO-FILL data karyawan saat dipilih dari dropdown (untuk edit)
    $('#karyawan_id_select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const nik = $(this).val();
        const nama = selectedOption.data('nama');
        
        if (nik) {
            // Fill hidden field
            $('#karyawan_id').val(nik);
            
            // Update preview
            $('#preview_nama_edit').html('<strong>Nama:</strong> ' + nama);
            $('#preview_nik_edit').html('<strong>NIK:</strong> ' + nik);
        } else {
            $('#karyawan_id').val('');
        }
    });
});