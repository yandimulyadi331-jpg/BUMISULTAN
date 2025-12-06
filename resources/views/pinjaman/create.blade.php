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
                    <i class="bi bi-file-earmark-plus"></i> 
                    @if(isset($duplicateData))
                        Pinjaman Tambahan untuk {{ $duplicateData['nama_peminjam_lengkap'] }}
                    @else
                        Pengajuan Pinjaman Baru
                    @endif
                </h3>
                <p class="text-white-50 mb-0">
                    @if(isset($duplicateData))
                        Pinjaman baru untuk peminjam yang sama - <strong>Dokumen administrasi harus dilengkapi ulang</strong>
                    @else
                        Form pengajuan pinjaman untuk crew dan non-crew
                    @endif
                </p>
            </div>
            <div>
                <a href="{{ route('pinjaman.index') }}" class="btn btn-light">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Alert Pinjaman Aktif -->
@if(!empty($pinjamanAktif) && count($pinjamanAktif) > 0)
<div class="alert alert-warning alert-dismissible fade show">
    <h5><i class="bi bi-exclamation-triangle"></i> Informasi Pinjaman Aktif:</h5>
    <p class="mb-2">Peminjam ini masih memiliki <strong>{{ count($pinjamanAktif) }} pinjaman aktif</strong>:</p>
    <ul class="mb-0">
        @foreach($pinjamanAktif as $p)
        <li>
            <strong>{{ $p->nomor_pinjaman }}</strong> - 
            Status: <span class="badge bg-{{ $p->status == 'berjalan' ? 'primary' : 'warning' }}">{{ strtoupper($p->status) }}</span> - 
            Sisa: <strong class="text-danger">Rp {{ number_format($p->sisa_pinjaman, 0, ',', '.') }}</strong>
            ({{ number_format($p->persentase_pembayaran, 1) }}% terbayar)
        </li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

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

<!-- Info Dokumen Wajib -->
@if(isset($duplicateData))
<div class="alert alert-info">
    <i class="bi bi-info-circle"></i> 
    <strong>PENTING:</strong> Untuk pinjaman tambahan, <strong>SEMUA DOKUMEN ADMINISTRASI WAJIB DIISI ULANG</strong> 
    untuk keperluan arsip, meskipun peminjam sudah pernah mengajukan pinjaman sebelumnya.
</div>
@endif

<form action="{{ route('pinjaman.store') }}" method="POST" enctype="multipart/form-data" id="formPinjaman">
    @csrf

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
                            {{ old('kategori_peminjam', $duplicateData['kategori_peminjam'] ?? 'crew') == 'crew' ? 'checked' : '' }} required>
                        <label class="btn btn-outline-primary" for="kategori_crew">
                            <i class="bi bi-person-vcard"></i> CREW / Karyawan
                        </label>

                        <input type="radio" class="btn-check" name="kategori_peminjam" id="kategori_non_crew" value="non_crew"
                            {{ old('kategori_peminjam', $duplicateData['kategori_peminjam'] ?? '') == 'non_crew' ? 'checked' : '' }}>
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
            @if(isset($duplicateData) && $duplicateData['kategori_peminjam'] == 'crew')
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Data peminjam otomatis terisi dari pinjaman sebelumnya
            </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label required">Pilih Karyawan</label>
                        <select name="karyawan_id_select" id="karyawan_id_select" class="form-select" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->nik }}" 
                                data-nama="{{ $karyawan->nama_karyawan }}"
                                {{ old('nik_crew', $duplicateData['karyawan_id'] ?? '') == $karyawan->nik ? 'selected' : '' }}>
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
            
            <!-- Hidden fields yang akan terisi otomatis -->
            <input type="hidden" name="nama_peminjam_crew" id="nama_peminjam_crew" 
                value="{{ old('nama_peminjam_crew', $duplicateData['nama_peminjam_lengkap'] ?? '') }}">
            <input type="hidden" name="nik_crew" id="nik_crew" 
                value="{{ old('nik_crew', $duplicateData['karyawan_id'] ?? '') }}">
            
            <!-- Preview data karyawan yang dipilih -->
            <div id="preview_karyawan" style="display: none;" class="alert alert-info mt-3">
                <strong><i class="bi bi-person-check"></i> Data Karyawan Terpilih:</strong><br>
                <span id="preview_nama"></span><br>
                <span id="preview_nik"></span>
            </div>
        </div>
    </div>

    <!-- Data Peminjam - Non Crew -->
    <div class="card mb-4" id="form-non-crew">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-person-lines-fill"></i> 2. Data Peminjam (Non-Crew)</h5>
        </div>
        <div class="card-body">
            @if(isset($duplicateData) && $duplicateData['kategori_peminjam'] == 'non_crew')
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i> Data peminjam otomatis terisi dari pinjaman sebelumnya
            </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Nama Lengkap</label>
                        <input type="text" name="nama_peminjam" class="form-control" 
                            value="{{ old('nama_peminjam', $duplicateData['nama_peminjam'] ?? '') }}" 
                            placeholder="Nama lengkap sesuai KTP">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">NIK (KTP)</label>
                        <input type="text" name="nik_peminjam" class="form-control" 
                            value="{{ old('nik_peminjam', $duplicateData['nik_peminjam'] ?? '') }}" 
                            placeholder="16 digit NIK" maxlength="16">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">No. Telepon</label>
                        <input type="text" name="no_telp_peminjam" class="form-control" 
                            value="{{ old('no_telp_peminjam', $duplicateData['no_telp_peminjam'] ?? '') }}" 
                            placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email_peminjam" class="form-control" 
                            value="{{ old('email_peminjam', $duplicateData['email_peminjam'] ?? '') }}" 
                            placeholder="email@example.com">
                        <small class="text-muted">
                            <i class="bi bi-info-circle"></i> Email untuk notifikasi jatuh tempo cicilan
                        </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label required">Pekerjaan</label>
                        <input type="text" name="pekerjaan_peminjam" class="form-control" 
                            value="{{ old('pekerjaan_peminjam', $duplicateData['pekerjaan_peminjam'] ?? '') }}" 
                            placeholder="Contoh: Wiraswasta">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label required">Alamat Lengkap</label>
                        <textarea name="alamat_peminjam" class="form-control" rows="3" 
                            placeholder="Alamat lengkap sesuai KTP">{{ old('alamat_peminjam', $duplicateData['alamat_peminjam'] ?? '') }}</textarea>
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
                        <label class="form-label required">Cicilan per Bulan</label>
                        <input type="number" name="cicilan_per_bulan" class="form-control" 
                            value="{{ old('cicilan_per_bulan') }}" placeholder="Contoh: 1000000" 
                            min="10000" step="10000" required id="cicilan_per_bulan">
                        <small class="text-muted">Tentukan cicilan bulanan</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">
                            Tenor (Bulan) 
                            <i class="bi bi-info-circle text-primary" title="Otomatis dihitung dari Jumlah Pinjaman ÷ Cicilan"></i>
                        </label>
                        <input type="number" name="tenor_bulan" class="form-control bg-light" 
                            value="{{ old('tenor_bulan') }}" placeholder="Akan otomatis terisi" 
                            min="1" max="60" readonly required id="tenor_bulan">
                        <small class="text-muted">
                            <i class="bi bi-calculator"></i> Otomatis: Jumlah Pinjaman ÷ Cicilan per Bulan
                        </small>
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
                
                <!-- Info Perhitungan Otomatis -->
                <div class="col-md-12 info-perhitungan">
                    <!-- Will be filled by JavaScript -->
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

    <!-- Data Jaminan -->
    <div class="card mb-4">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="bi bi-shield-fill-check"></i> 4. Data Jaminan / Agunan (Opsional)</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jenis Jaminan</label>
                        <select name="jenis_jaminan" class="form-select">
                            <option value="">-- Pilih Jenis Jaminan --</option>
                            <option value="bpkb" {{ old('jenis_jaminan') == 'bpkb' ? 'selected' : '' }}>BPKB Kendaraan</option>
                            <option value="sertifikat" {{ old('jenis_jaminan') == 'sertifikat' ? 'selected' : '' }}>Sertifikat Tanah/Rumah</option>
                            <option value="elektronik" {{ old('jenis_jaminan') == 'elektronik' ? 'selected' : '' }}>Elektronik</option>
                            <option value="lainnya" {{ old('jenis_jaminan') == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nomor / Identitas Jaminan</label>
                        <input type="text" name="nomor_jaminan" class="form-control" 
                            value="{{ old('nomor_jaminan') }}" placeholder="Nomor BPKB, Sertifikat, dll">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Deskripsi Jaminan</label>
                        <textarea name="deskripsi_jaminan" class="form-control" rows="2" 
                            placeholder="Contoh: Motor Honda Vario 2020, warna hitam">{{ old('deskripsi_jaminan') }}</textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Estimasi Nilai Jaminan</label>
                        <input type="number" name="nilai_jaminan" class="form-control" 
                            value="{{ old('nilai_jaminan') }}" placeholder="15000000" min="0" step="100000">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Atas Nama</label>
                        <input type="text" name="atas_nama_jaminan" class="form-control" 
                            value="{{ old('atas_nama_jaminan') }}" placeholder="Nama pemilik jaminan">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Kondisi Jaminan</label>
                        <select name="kondisi_jaminan" class="form-select">
                            <option value="">-- Pilih Kondisi --</option>
                            <option value="baru" {{ old('kondisi_jaminan') == 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="bekas_baik" {{ old('kondisi_jaminan') == 'bekas_baik' ? 'selected' : '' }}>Bekas (Baik)</option>
                            <option value="bekas_cukup" {{ old('kondisi_jaminan') == 'bekas_cukup' ? 'selected' : '' }}>Bekas (Cukup)</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="mb-3">
                        <label class="form-label">Keterangan Tambahan Jaminan</label>
                        <textarea name="keterangan_jaminan" class="form-control" rows="2" 
                            placeholder="Informasi tambahan tentang jaminan">{{ old('keterangan_jaminan') }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Dokumen Pendukung -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-paperclip"></i> 5. Dokumen Pendukung</h5>
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
            <h5 class="mb-0"><i class="bi bi-shield-check"></i> 6. Data Penjamin (Opsional)</h5>
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
            $('[name="nama_peminjam_crew"], [name="nik_crew"]').prop('required', true);
            $('[name="nama_peminjam"], [name="nik_peminjam"], [name="alamat_peminjam"], [name="no_telp_peminjam"], [name="email_peminjam"], [name="pekerjaan_peminjam"]').prop('required', false);
        } else {
            $('#form-crew').hide();
            $('#form-non-crew').show();
            $('#karyawan_id_select').prop('required', false);
            $('[name="nama_peminjam_crew"], [name="nik_crew"]').prop('required', false);
            $('[name="nama_peminjam"], [name="nik_peminjam"], [name="alamat_peminjam"], [name="no_telp_peminjam"], [name="email_peminjam"], [name="pekerjaan_peminjam"]').prop('required', true);
        }
    }

    // Event listener untuk perubahan kategori
    $('input[name="kategori_peminjam"]').on('change', toggleFormKategori);
    
    // Inisialisasi saat load
    toggleFormKategori();

    // Validasi NIK 16 digit
    $('[name="nik_peminjam"], [name="nik_crew"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '').substring(0, 16);
    });

    // Format nomor telepon
    $('[name="no_telp_peminjam"], [name="no_telp_penjamin"]').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, '');
    });

    // Format angka untuk nilai jaminan
    $('[name="nilai_jaminan"]').on('blur', function() {
        if (this.value) {
            this.value = Math.round(this.value / 100000) * 100000;
        }
    });

    // AUTO-CALCULATE TENOR berdasarkan Jumlah Pinjaman dan Cicilan
    function hitungTenor() {
        const jumlahPinjaman = parseFloat($('[name="jumlah_pengajuan"]').val()) || 0;
        const cicilanPerBulan = parseFloat($('[name="cicilan_per_bulan"]').val()) || 0;
        
        if (jumlahPinjaman > 0 && cicilanPerBulan > 0) {
            // Hitung tenor = Jumlah Pinjaman / Cicilan per Bulan
            const tenor = Math.ceil(jumlahPinjaman / cicilanPerBulan);
            
            // Update field tenor
            $('[name="tenor_bulan"]').val(tenor);
            
            // Update placeholder dengan info
            $('[name="tenor_bulan"]').attr('placeholder', 'Otomatis: ' + tenor + ' bulan');
            
            // Tampilkan info
            const sisaPembayaran = cicilanPerBulan * tenor;
            const selisih = sisaPembayaran - jumlahPinjaman;
            
            if (selisih > 0) {
                $('.info-perhitungan').html(
                    '<div class="alert alert-info mt-2">' +
                    '<i class="bi bi-calculator"></i> <strong>Perhitungan Otomatis:</strong><br>' +
                    'Jumlah Pinjaman: <strong>Rp ' + formatRupiah(jumlahPinjaman) + '</strong><br>' +
                    'Cicilan per Bulan: <strong>Rp ' + formatRupiah(cicilanPerBulan) + '</strong><br>' +
                    'Tenor: <strong>' + tenor + ' bulan</strong><br>' +
                    'Total Dibayar: <strong>Rp ' + formatRupiah(sisaPembayaran) + '</strong>' +
                    (selisih > 0 ? '<br><small class="text-muted">(Pembayaran terakhir dikurangi: Rp ' + formatRupiah(selisih) + ')</small>' : '') +
                    '</div>'
                );
            }
        } else {
            $('[name="tenor_bulan"]').val('');
            $('[name="tenor_bulan"]').attr('placeholder', 'Akan otomatis terisi');
            $('.info-perhitungan').html('');
        }
    }
    
    // Helper format rupiah
    function formatRupiah(angka) {
        return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }
    
    // Event listener untuk auto-calculate
    $('[name="jumlah_pengajuan"], [name="cicilan_per_bulan"]').on('input change blur', function() {
        hitungTenor();
    });
    
    // Prevent manual edit tenor (optional - bisa dihapus jika mau tetap bisa manual)
    $('[name="tenor_bulan"]').on('focus', function() {
        $(this).attr('readonly', true).css('background-color', '#e9ecef');
        $(this).attr('title', 'Tenor dihitung otomatis berdasarkan Jumlah Pinjaman dan Cicilan per Bulan');
    });
    
    // Trigger calculate saat load jika ada nilai
    if ($('[name="jumlah_pengajuan"]').val() || $('[name="cicilan_per_bulan"]').val()) {
        hitungTenor();
    }

    // AUTO-FILL data karyawan saat dipilih dari dropdown
    $('#karyawan_id_select').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const nik = $(this).val();
        const nama = selectedOption.data('nama');
        
        if (nik) {
            // Fill hidden fields
            $('#nik_crew').val(nik);
            $('#nama_peminjam_crew').val(nama);
            
            // Show preview
            $('#preview_karyawan').show();
            $('#preview_nama').html('<strong>Nama:</strong> ' + nama);
            $('#preview_nik').html('<strong>NIK:</strong> ' + nik);
        } else {
            // Clear fields
            $('#nik_crew').val('');
            $('#nama_peminjam_crew').val('');
            $('#preview_karyawan').hide();
        }
    });
    
    // Trigger jika ada nilai default (untuk edit/duplicate)
    if ($('#karyawan_id_select').val()) {
        $('#karyawan_id_select').trigger('change');
    }
});
</script>
@endpush
