@extends('layouts.mobile.app')
@section('titlepage', 'Tambah Administrasi')

@section('content')
<style>
    :root {
        /* Minimal Elegant Colors */
        --bg-body-light: #ecf0f3;
        --bg-primary-light: #ecf0f3;
        --shadow-dark-light: #bec3c9;
        --shadow-light-light: #ffffff;
        --text-primary-light: #3d5467;
        --text-secondary-light: #6b7c8d;
        --border-light: rgba(0, 0, 0, 0.05);
        --icon-color-light: #6b7c8d;

        --bg-body-dark: #1a202c;
        --bg-primary-dark: #2d3748;
        --shadow-dark-dark: #171923;
        --shadow-light-dark: #3f4c63;
        --text-primary-dark: #f7fafc;
        --text-secondary-dark: #a0aec0;
        --border-dark: rgba(255, 255, 255, 0.08);
        --icon-color-dark: #a0aec0;

        /* Accent Colors - Minimal */
        --accent-primary: #4a90a4;
        --accent-success: #26c281;
        --accent-warning: #f8b739;
        --accent-danger: #e74c3c;
        --accent-info: #17a2b8;
    }

    /* Apply light mode by default */
    body {
        --bg-body: var(--bg-body-light);
        --bg-primary: var(--bg-primary-light);
        --shadow-dark: var(--shadow-dark-light);
        --shadow-light: var(--shadow-light-light);
        --text-primary: var(--text-primary-light);
        --text-secondary: var(--text-secondary-light);
        --border-color: var(--border-light);
        --icon-color: var(--icon-color-light);
    }

    /* Dark mode */
    body.dark-mode {
        --bg-body: var(--bg-body-dark);
        --bg-primary: var(--bg-primary-dark);
        --shadow-dark: var(--shadow-dark-dark);
        --shadow-light: var(--shadow-light-dark);
        --text-primary: var(--text-primary-dark);
        --text-secondary: var(--text-secondary-dark);
        --border-color: var(--border-dark);
        --icon-color: var(--icon-color-dark);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: var(--bg-body);
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* ========== HEADER ========== */
    #header-section {
        background: var(--bg-primary);
        padding: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: sticky;
        top: 0;
        z-index: 100;
        margin-bottom: 0;
        box-shadow: 0 2px 15px rgba(0,0,0,0.03);
    }

    .header-content {
        display: flex;
        align-items: center;
        gap: 15px;
        width: 100%;
    }

    .back-btn {
        background: var(--bg-primary);
        border: none;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        justify-content: center;
        align-items: center;
        cursor: pointer;
        box-shadow: 6px 6px 12px var(--shadow-dark),
                   -6px -6px 12px var(--shadow-light);
        transition: all 0.3s ease;
        text-decoration: none;
        flex-shrink: 0;
        color: var(--icon-color);
        font-size: 22px;
    }

    .back-btn:hover {
        transform: translateY(-2px);
        box-shadow: 8px 8px 16px var(--shadow-dark),
                   -8px -8px 16px var(--shadow-light);
    }

    .back-btn:active {
        transform: scale(0.95);
        box-shadow: inset 4px 4px 8px var(--shadow-dark),
                   inset -4px -4px 8px var(--shadow-light);
    }

    .header-title {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .header-title h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1.2;
        letter-spacing: -0.5px;
        margin: 0;
    }

    .header-title p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
        margin-top: 4px;
        letter-spacing: 0.1px;
    }

    #content-section {
        padding: 25px 20px;
        max-width: 1200px;
        margin: 0 auto;
        padding-bottom: 100px;
    }

    .form-card {
        background: var(--bg-primary);
        border-radius: 18px;
        padding: 22px;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: var(--text-secondary);
        margin-bottom: 10px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-label .text-danger {
        color: var(--accent-danger);
    }

    .form-control, .form-select {
        width: 100%;
        padding: 14px 18px;
        border: none;
        border-radius: 14px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: var(--bg-primary);
        box-shadow: inset 3px 3px 8px var(--shadow-dark),
                   inset -3px -3px 8px var(--shadow-light);
        color: var(--text-primary);
        font-weight: 500;
    }

    .form-control:focus, .form-select:focus {
        outline: none;
        box-shadow: inset 4px 4px 10px var(--shadow-dark),
                   inset -4px -4px 10px var(--shadow-light);
        background: var(--bg-primary);
    }

    .form-control::placeholder {
        color: var(--text-secondary);
        opacity: 0.7;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 100px;
    }

    .invalid-feedback {
        color: var(--accent-danger);
        font-size: 0.75rem;
        margin-top: 6px;
        display: block;
        font-weight: 600;
    }

    .text-muted {
        font-size: 0.7rem;
        color: var(--text-secondary);
        margin-top: 6px;
        display: block;
        font-weight: 500;
    }

    #preview-container, #preview-image {
        margin-top: 12px;
        max-width: 100%;
        height: auto;
        border-radius: 14px;
        box-shadow: 4px 4px 10px var(--shadow-dark),
                   -4px -4px 10px var(--shadow-light);
    }

    .btn-submit {
        background: var(--bg-primary);
        color: var(--text-primary);
        border: none;
        padding: 16px 30px;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 6px 6px 18px var(--shadow-dark),
                   -6px -6px 18px var(--shadow-light);
    }

    .btn-submit:active {
        transform: scale(0.98);
        box-shadow: inset 4px 4px 10px var(--shadow-dark),
                   inset -4px -4px 10px var(--shadow-light);
    }

    .section-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 18px;
        padding-bottom: 12px;
        border-bottom: 2px solid var(--accent-primary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .alert {
        background: var(--bg-primary);
        border-radius: 14px;
        padding: 14px 18px;
        margin-bottom: 20px;
        box-shadow: 4px 4px 10px var(--shadow-dark),
                   -4px -4px 10px var(--shadow-light);
        border-left: 4px solid var(--accent-success);
        color: var(--text-primary);
    }

    #section-undangan-alert > div {
        background: var(--bg-primary) !important;
        color: var(--text-primary) !important;
        border-left: 4px solid var(--accent-info) !important;
        border-radius: 14px !important;
        padding: 14px 18px !important;
        box-shadow: inset 3px 3px 6px var(--shadow-dark),
                   inset -3px -3px 6px var(--shadow-light) !important;
    }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('administrasi.karyawan.index') }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Tambah Administrasi</h3>
            <p>Form Input Dokumen Baru</p>
        </div>
    </div>
</div>

<div id="content-section">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('administrasi.karyawan.store') }}" method="POST" enctype="multipart/form-data" id="formAdministrasi">
        @csrf

        <!-- Informasi Dasar -->
        <div class="form-card">
            <div class="section-title">Informasi Dasar</div>

            <div class="form-group">
                <label class="form-label">Jenis Administrasi <span class="text-danger">*</span></label>
                <select name="jenis_administrasi" id="jenis_administrasi" class="form-select @error('jenis_administrasi') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($jenisAdministrasi as $key => $value)
                        <option value="{{ $key }}" {{ old('jenis_administrasi') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
                @error('jenis_administrasi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Surat / Dokumen</label>
                <input type="text" name="nomor_surat" class="form-control @error('nomor_surat') is-invalid @enderror" 
                       value="{{ old('nomor_surat') }}" placeholder="Contoh: 001/ADM/2024">
                @error('nomor_surat')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Opsional - Isi jika ada nomor surat</small>
            </div>

            <div class="form-group">
                <label class="form-label">Perihal / Judul <span class="text-danger">*</span></label>
                <input type="text" name="perihal" class="form-control @error('perihal') is-invalid @enderror" 
                       value="{{ old('perihal') }}" placeholder="Masukkan perihal dokumen" required>
                @error('perihal')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- DETAIL ACARA UNDANGAN -->
            <div id="section-undangan-alert" style="display: none;">
                <div style="background: #d1ecf1; color: #0c5460; padding: 10px; border-radius: 10px; margin-bottom: 15px; border-left: 4px solid #17a2b8;">
                    <strong>📅 Detail Acara Undangan</strong>
                </div>
            </div>

            <div class="form-group" id="field-nama-acara" style="display: none;">
                <label class="form-label">Nama Acara <span class="text-danger">*</span></label>
                <input type="text" name="nama_acara" class="form-control @error('nama_acara') is-invalid @enderror" 
                       value="{{ old('nama_acara') }}" placeholder="Contoh: Rapat Tahunan 2025">
                @error('nama_acara')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-tanggal-acara-mulai" style="display: none;">
                <label class="form-label">Tanggal Mulai Acara <span class="text-danger">*</span></label>
                <input type="date" name="tanggal_acara_mulai" class="form-control @error('tanggal_acara_mulai') is-invalid @enderror" 
                       value="{{ old('tanggal_acara_mulai') }}">
                @error('tanggal_acara_mulai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-tanggal-acara-selesai" style="display: none;">
                <label class="form-label">Tanggal Selesai Acara</label>
                <input type="date" name="tanggal_acara_selesai" class="form-control @error('tanggal_acara_selesai') is-invalid @enderror" 
                       value="{{ old('tanggal_acara_selesai') }}">
                @error('tanggal_acara_selesai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Kosongkan jika acara hanya 1 hari</small>
            </div>

            <div class="form-group" id="field-waktu-acara-mulai" style="display: none;">
                <label class="form-label">Waktu Mulai</label>
                <input type="time" name="waktu_acara_mulai" class="form-control @error('waktu_acara_mulai') is-invalid @enderror" 
                       value="{{ old('waktu_acara_mulai') }}">
                @error('waktu_acara_mulai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-waktu-acara-selesai" style="display: none;">
                <label class="form-label">Waktu Selesai</label>
                <input type="time" name="waktu_acara_selesai" class="form-control @error('waktu_acara_selesai') is-invalid @enderror" 
                       value="{{ old('waktu_acara_selesai') }}">
                @error('waktu_acara_selesai')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-lokasi-acara" style="display: none;">
                <label class="form-label">Lokasi / Tempat Acara <span class="text-danger">*</span></label>
                <input type="text" name="lokasi_acara" class="form-control @error('lokasi_acara') is-invalid @enderror" 
                       value="{{ old('lokasi_acara') }}" placeholder="Contoh: Gedung Graha Bhakti">
                @error('lokasi_acara')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-alamat-acara" style="display: none;">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat_acara" class="form-control @error('alamat_acara') is-invalid @enderror" 
                          rows="2" placeholder="Alamat lengkap lokasi acara">{{ old('alamat_acara') }}</textarea>
                @error('alamat_acara')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-dress-code" style="display: none;">
                <label class="form-label">Dress Code</label>
                <input type="text" name="dress_code" class="form-control @error('dress_code') is-invalid @enderror" 
                       value="{{ old('dress_code') }}" placeholder="Contoh: Batik, Formal">
                @error('dress_code')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-catatan-acara" style="display: none;">
                <label class="form-label">Catatan Acara</label>
                <textarea name="catatan_acara" class="form-control @error('catatan_acara') is-invalid @enderror" 
                          rows="2" placeholder="Catatan khusus acara">{{ old('catatan_acara') }}</textarea>
                @error('catatan_acara')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
            <!-- END DETAIL ACARA -->

            <div class="form-group" id="field-pengirim" style="display: none;">
                <label class="form-label">Pengirim</label>
                <input type="text" name="pengirim" class="form-control @error('pengirim') is-invalid @enderror" 
                       value="{{ old('pengirim') }}" placeholder="Nama pengirim">
                @error('pengirim')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-penerima" style="display: none;">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" class="form-control @error('penerima') is-invalid @enderror" 
                       value="{{ old('penerima') }}" placeholder="Nama penerima">
                @error('penerima')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Tanggal -->
        <div class="form-card">
            <div class="section-title">Informasi Tanggal</div>

            <div class="form-group">
                <label class="form-label">Tanggal Surat / Dokumen</label>
                <input type="date" name="tanggal_surat" class="form-control @error('tanggal_surat') is-invalid @enderror" 
                       value="{{ old('tanggal_surat', date('Y-m-d')) }}">
                @error('tanggal_surat')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-tanggal-terima" style="display: none;">
                <label class="form-label">Tanggal Terima</label>
                <input type="datetime-local" name="tanggal_terima" class="form-control @error('tanggal_terima') is-invalid @enderror" 
                       value="{{ old('tanggal_terima') }}">
                @error('tanggal_terima')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group" id="field-tanggal-kirim" style="display: none;">
                <label class="form-label">Tanggal Kirim</label>
                <input type="datetime-local" name="tanggal_kirim" class="form-control @error('tanggal_kirim') is-invalid @enderror" 
                       value="{{ old('tanggal_kirim') }}">
                @error('tanggal_kirim')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Status & Prioritas -->
        <div class="form-card">
            <div class="section-title">Status & Prioritas</div>

            <div class="form-group">
                <label class="form-label">Prioritas <span class="text-danger">*</span></label>
                <select name="prioritas" class="form-select @error('prioritas') is-invalid @enderror" required>
                    <option value="normal" {{ old('prioritas') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="rendah" {{ old('prioritas') == 'rendah' ? 'selected' : '' }}>Rendah</option>
                    <option value="tinggi" {{ old('prioritas') == 'tinggi' ? 'selected' : '' }}>Tinggi</option>
                    <option value="urgent" {{ old('prioritas') == 'urgent' ? 'selected' : '' }}>URGENT</option>
                </select>
                @error('prioritas')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="proses" {{ old('status') == 'proses' ? 'selected' : '' }}>Proses</option>
                    <option value="selesai" {{ old('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="ditolak" {{ old('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                </select>
                @error('status')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Detail Tambahan -->
        <div class="form-card">
            <div class="section-title">Detail Tambahan</div>

            <div class="form-group">
                <label class="form-label">Ringkasan / Isi Singkat</label>
                <textarea name="ringkasan" class="form-control @error('ringkasan') is-invalid @enderror" 
                          rows="3" placeholder="Ringkasan singkat isi dokumen">{{ old('ringkasan') }}</textarea>
                @error('ringkasan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Disposisi Ke</label>
                <input type="text" name="disposisi_ke" class="form-control @error('disposisi_ke') is-invalid @enderror" 
                       value="{{ old('disposisi_ke') }}" placeholder="Nama bagian/orang">
                @error('disposisi_ke')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Isi jika dokumen perlu didisposisikan</small>
            </div>

            <div class="form-group">
                <label class="form-label">Cabang</label>
                <select name="cabang_id" class="form-select @error('cabang_id') is-invalid @enderror">
                    <option value="">-- Pilih Cabang --</option>
                    @foreach($cabangs as $cabang)
                        <option value="{{ $cabang->id }}" {{ old('cabang_id') == $cabang->id ? 'selected' : '' }}>
                            {{ $cabang->nama_cabang }}
                        </option>
                    @endforeach
                </select>
                @error('cabang_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                          rows="2" placeholder="Catatan tambahan">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control @error('keterangan') is-invalid @enderror" 
                          rows="2" placeholder="Keterangan tambahan">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Upload File -->
        <div class="form-card">
            <div class="section-title">Upload File</div>

            <div class="form-group">
                <label class="form-label">Upload Dokumen (PDF, Word, Excel)</label>
                <input type="file" name="file_dokumen" class="form-control @error('file_dokumen') is-invalid @enderror" 
                       accept=".pdf,.doc,.docx,.xls,.xlsx">
                @error('file_dokumen')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Max: 10MB - Format: PDF, DOC, DOCX, XLS, XLSX</small>
            </div>

            <div class="form-group">
                <label class="form-label">Upload Foto Dokumen (JPG, PNG)</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" 
                       accept="image/jpeg,image/png,image/jpg" onchange="previewImage(this)">
                @error('foto')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Max: 2MB - Format: JPG, PNG</small>
                <div id="preview-container" style="display: none;">
                    <img id="preview-image" src="" alt="Preview" style="max-width: 100%; margin-top: 10px; border-radius: 10px;">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-card">
            <button type="submit" class="btn-submit">
                <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
                Simpan Data
            </button>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    // Dynamic field visibility based on jenis_administrasi
    document.getElementById('jenis_administrasi').addEventListener('change', function() {
        const value = this.value;
        const isMasuk = ['surat_masuk', 'undangan_masuk', 'proposal_masuk', 'paket_masuk'].includes(value);
        const isKeluar = ['surat_keluar', 'undangan_keluar', 'proposal_keluar', 'paket_keluar'].includes(value);
        const isUndangan = ['undangan_masuk', 'undangan_keluar'].includes(value);
        
        // Show/hide pengirim field
        document.getElementById('field-pengirim').style.display = isMasuk ? 'block' : 'none';
        
        // Show/hide penerima field
        document.getElementById('field-penerima').style.display = isKeluar ? 'block' : 'none';
        
        // Show/hide tanggal terima/kirim
        document.getElementById('field-tanggal-terima').style.display = isMasuk ? 'block' : 'none';
        document.getElementById('field-tanggal-kirim').style.display = isKeluar ? 'block' : 'none';

        // Show/hide UNDANGAN FIELDS
        document.getElementById('section-undangan-alert').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-nama-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-tanggal-acara-mulai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-tanggal-acara-selesai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-waktu-acara-mulai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-waktu-acara-selesai').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-lokasi-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-alamat-acara').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-dress-code').style.display = isUndangan ? 'block' : 'none';
        document.getElementById('field-catatan-acara').style.display = isUndangan ? 'block' : 'none';

        // Set required attribute for undangan fields
        const namaAcara = document.querySelector('[name="nama_acara"]');
        const tanggalMulai = document.querySelector('[name="tanggal_acara_mulai"]');
        const lokasiAcara = document.querySelector('[name="lokasi_acara"]');
        
        if (isUndangan) {
            namaAcara.setAttribute('required', 'required');
            tanggalMulai.setAttribute('required', 'required');
            lokasiAcara.setAttribute('required', 'required');
        } else {
            namaAcara.removeAttribute('required');
            tanggalMulai.removeAttribute('required');
            lokasiAcara.removeAttribute('required');
        }
    });

    // Preview image
    function previewImage(input) {
        const preview = document.getElementById('preview-image');
        const container = document.getElementById('preview-container');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.src = e.target.result;
                container.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    document.getElementById('formAdministrasi').addEventListener('submit', function(e) {
        const jenisAdministrasi = document.getElementById('jenis_administrasi').value;
        
        if (!jenisAdministrasi) {
            e.preventDefault();
            alert('Silakan pilih jenis administrasi terlebih dahulu');
            return false;
        }

        // Validate undangan fields
        const isUndangan = ['undangan_masuk', 'undangan_keluar'].includes(jenisAdministrasi);
        if (isUndangan) {
            const namaAcara = document.querySelector('[name="nama_acara"]').value;
            const tanggalMulai = document.querySelector('[name="tanggal_acara_mulai"]').value;
            const lokasiAcara = document.querySelector('[name="lokasi_acara"]').value;

            if (!namaAcara || !tanggalMulai || !lokasiAcara) {
                e.preventDefault();
                alert('Data Undangan Tidak Lengkap!\nHarap isi: Nama Acara, Tanggal Mulai, dan Lokasi Acara');
                return false;
            }
        }
    });

    // Trigger change on page load to show appropriate fields
    document.getElementById('jenis_administrasi').dispatchEvent(new Event('change'));
</script>
@endpush
