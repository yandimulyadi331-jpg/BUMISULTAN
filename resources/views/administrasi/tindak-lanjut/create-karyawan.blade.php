@extends('layouts.mobile.app')
@section('titlepage', 'Tambah Tindak Lanjut')

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

    .info-card, .form-card {
        background: var(--bg-primary);
        border-radius: 18px;
        padding: 20px;
        box-shadow: 5px 5px 15px var(--shadow-dark),
                   -5px -5px 15px var(--shadow-light);
        margin-bottom: 20px;
    }

    .info-label {
        font-size: 0.7rem;
        color: var(--text-secondary);
        font-weight: 700;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 0.95rem;
        color: var(--text-primary);
        font-weight: 600;
        line-height: 1.5;
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

    .conditional-field {
        display: none;
    }

    #preview-container {
        margin-top: 12px;
    }

    #preview-image, #preview-bukti img, #preview-paket img {
        max-width: 100%;
        height: auto;
        border-radius: 14px;
        box-shadow: 4px 4px 10px var(--shadow-dark),
                   -4px -4px 10px var(--shadow-light);
        margin-top: 10px;
    }
</style>

<div id="header-section">
    <div class="header-content">
        <a href="{{ route('administrasi.karyawan.show', $administrasi->id) }}" class="back-btn">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">
            <h3>Tambah Tindak Lanjut</h3>
            <p>Form Tindak Lanjut Dokumen</p>
        </div>
    </div>
</div>

<div id="content-section">
    <!-- Info Administrasi -->
    <div class="info-card">
        <div class="info-label">Dokumen</div>
        <div class="info-value">{{ $administrasi->kode_administrasi }}</div>
        <div class="info-value" style="color: #666; font-size: 0.85rem; margin-top: 5px;">
            {{ $administrasi->perihal }}
        </div>
    </div>

    <form action="{{ route('administrasi.karyawan.tindak-lanjut.store', $administrasi->id) }}" method="POST" enctype="multipart/form-data" id="formTindakLanjut">
        @csrf

        <!-- Informasi Dasar -->
        <div class="form-card">
            <div class="section-title">Informasi Dasar</div>

            <div class="form-group">
                <label class="form-label">Jenis Tindak Lanjut <span class="text-danger">*</span></label>
                <select name="jenis_tindak_lanjut" id="jenis_tindak_lanjut" class="form-select @error('jenis_tindak_lanjut') is-invalid @enderror" required>
                    <option value="">-- Pilih Jenis --</option>
                    <option value="pencairan_dana">💰 Pencairan Dana</option>
                    <option value="disposisi">📋 Disposisi</option>
                    <option value="konfirmasi_terima">📥 Konfirmasi Terima</option>
                    <option value="konfirmasi_kirim">📤 Konfirmasi Kirim</option>
                    <option value="rapat_pembahasan">👥 Rapat Pembahasan</option>
                    <option value="tandatangan">✍️ Penandatanganan</option>
                    <option value="verifikasi">✅ Verifikasi</option>
                    <option value="approval">👍 Approval</option>
                    <option value="revisi">🔄 Revisi</option>
                    <option value="arsip">📁 Pengarsipan</option>
                    <option value="lainnya">📝 Lainnya</option>
                </select>
                @error('jenis_tindak_lanjut')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Judul Tindak Lanjut <span class="text-danger">*</span></label>
                <input type="text" name="judul_tindak_lanjut" class="form-control @error('judul_tindak_lanjut') is-invalid @enderror" 
                       value="{{ old('judul_tindak_lanjut') }}" placeholder="Contoh: Pencairan Dana Operasional" required>
                @error('judul_tindak_lanjut')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Status <span class="text-danger">*</span></label>
                <select name="status_tindak_lanjut" class="form-select @error('status_tindak_lanjut') is-invalid @enderror" required>
                    <option value="pending">⏳ Pending</option>
                    <option value="proses" selected>🔄 Proses</option>
                    <option value="selesai">✅ Selesai</option>
                    <option value="ditolak">❌ Ditolak</option>
                </select>
                @error('status_tindak_lanjut')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi_tindak_lanjut" class="form-control @error('deskripsi_tindak_lanjut') is-invalid @enderror" 
                          rows="3" placeholder="Deskripsi detail tindak lanjut">{{ old('deskripsi_tindak_lanjut') }}</textarea>
                @error('deskripsi_tindak_lanjut')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Detail Pencairan Dana -->
        <div class="form-card conditional-field" id="field-pencairan">
            <div class="section-title">Detail Pencairan Dana</div>

            <div class="form-group">
                <label class="form-label">Nominal Pencairan</label>
                <input type="number" name="nominal_pencairan" class="form-control @error('nominal_pencairan') is-invalid @enderror" 
                       value="{{ old('nominal_pencairan') }}" placeholder="Contoh: 1000000">
                @error('nominal_pencairan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Metode Pencairan</label>
                <select name="metode_pencairan" class="form-select @error('metode_pencairan') is-invalid @enderror">
                    <option value="">-- Pilih Metode --</option>
                    <option value="Transfer Bank">Transfer Bank</option>
                    <option value="Cash">Cash</option>
                    <option value="Cek">Cek</option>
                    <option value="Giro">Giro</option>
                </select>
                @error('metode_pencairan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nama Penerima Dana</label>
                <input type="text" name="nama_penerima_dana" class="form-control @error('nama_penerima_dana') is-invalid @enderror" 
                       value="{{ old('nama_penerima_dana') }}" placeholder="Nama penerima">
                @error('nama_penerima_dana')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Nomor Rekening</label>
                <input type="text" name="nomor_rekening" class="form-control @error('nomor_rekening') is-invalid @enderror" 
                       value="{{ old('nomor_rekening') }}" placeholder="Nomor rekening">
                @error('nomor_rekening')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Tanggal Pencairan</label>
                <input type="date" name="tanggal_pencairan" class="form-control @error('tanggal_pencairan') is-invalid @enderror" 
                       value="{{ old('tanggal_pencairan') }}">
                @error('tanggal_pencairan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Bukti Pencairan (Foto/PDF)</label>
                <input type="file" name="bukti_pencairan" class="form-control @error('bukti_pencairan') is-invalid @enderror" 
                       accept="image/*,.pdf" onchange="previewImage(this, 'preview-bukti')">
                @error('bukti_pencairan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Max: 2MB - Format: JPG, PNG, PDF</small>
                <div id="preview-bukti" style="display: none; margin-top: 10px;">
                    <img src="" alt="Preview" style="max-width: 100%; border-radius: 10px;">
                </div>
            </div>
        </div>

        <!-- Detail Disposisi -->
        <div class="form-card conditional-field" id="field-disposisi">
            <div class="section-title">Detail Disposisi</div>

            <div class="form-group">
                <label class="form-label">Disposisi Dari</label>
                <input type="text" name="disposisi_dari" class="form-control @error('disposisi_dari') is-invalid @enderror" 
                       value="{{ old('disposisi_dari', Auth::user()->name) }}" placeholder="Nama pemberi disposisi">
                @error('disposisi_dari')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Disposisi Kepada</label>
                <input type="text" name="disposisi_kepada" class="form-control @error('disposisi_kepada') is-invalid @enderror" 
                       value="{{ old('disposisi_kepada') }}" placeholder="Nama penerima disposisi">
                @error('disposisi_kepada')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Instruksi Disposisi</label>
                <textarea name="instruksi_disposisi" class="form-control @error('instruksi_disposisi') is-invalid @enderror" 
                          rows="3" placeholder="Instruksi yang diberikan">{{ old('instruksi_disposisi') }}</textarea>
                @error('instruksi_disposisi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Deadline Disposisi</label>
                <input type="date" name="deadline_disposisi" class="form-control @error('deadline_disposisi') is-invalid @enderror" 
                       value="{{ old('deadline_disposisi') }}">
                @error('deadline_disposisi')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Detail Konfirmasi Paket -->
        <div class="form-card conditional-field" id="field-konfirmasi">
            <div class="section-title">Detail Konfirmasi Paket</div>

            <div class="form-group">
                <label class="form-label">Nama Penerima</label>
                <input type="text" name="nama_penerima_paket" class="form-control @error('nama_penerima_paket') is-invalid @enderror" 
                       value="{{ old('nama_penerima_paket') }}" placeholder="Nama penerima paket">
                @error('nama_penerima_paket')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Resi Pengiriman</label>
                <input type="text" name="resi_pengiriman" class="form-control @error('resi_pengiriman') is-invalid @enderror" 
                       value="{{ old('resi_pengiriman') }}" placeholder="Nomor resi">
                @error('resi_pengiriman')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Kondisi Paket</label>
                <select name="kondisi_paket" class="form-select @error('kondisi_paket') is-invalid @enderror">
                    <option value="">-- Pilih Kondisi --</option>
                    <option value="Baik">Baik</option>
                    <option value="Rusak">Rusak</option>
                    <option value="Basah">Basah</option>
                    <option value="Segel Terbuka">Segel Terbuka</option>
                </select>
                @error('kondisi_paket')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Foto Paket</label>
                <input type="file" name="foto_paket" class="form-control @error('foto_paket') is-invalid @enderror" 
                       accept="image/*" onchange="previewImage(this, 'preview-paket')">
                @error('foto_paket')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <small class="text-muted">Max: 2MB - Format: JPG, PNG</small>
                <div id="preview-paket" style="display: none; margin-top: 10px;">
                    <img src="" alt="Preview" style="max-width: 100%; border-radius: 10px;">
                </div>
            </div>
        </div>

        <!-- Catatan -->
        <div class="form-card">
            <div class="section-title">Catatan Tambahan</div>

            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" 
                          rows="3" placeholder="Catatan tambahan (opsional)">{{ old('catatan') }}</textarea>
                @error('catatan')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <!-- Submit Button -->
        <div class="form-card">
            <button type="submit" class="btn-submit">
                <ion-icon name="save-outline" style="font-size: 1.3rem;"></ion-icon>
                Simpan Tindak Lanjut
            </button>
        </div>
    </form>
</div>
@endsection

@push('myscript')
<script>
    // Show/hide conditional fields based on jenis
    document.getElementById('jenis_tindak_lanjut').addEventListener('change', function() {
        const value = this.value;
        
        // Hide all conditional fields first
        document.querySelectorAll('.conditional-field').forEach(field => {
            field.style.display = 'none';
        });
        
        // Show relevant fields
        if (value === 'pencairan_dana') {
            document.getElementById('field-pencairan').style.display = 'block';
        } else if (value === 'disposisi') {
            document.getElementById('field-disposisi').style.display = 'block';
        } else if (value === 'konfirmasi_terima' || value === 'konfirmasi_kirim') {
            document.getElementById('field-konfirmasi').style.display = 'block';
        }
    });

    // Preview image
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const img = preview.querySelector('img');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                img.src = e.target.result;
                preview.style.display = 'block';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Form validation
    document.getElementById('formTindakLanjut').addEventListener('submit', function(e) {
        const jenis = document.getElementById('jenis_tindak_lanjut').value;
        
        if (!jenis) {
            e.preventDefault();
            alert('Silakan pilih jenis tindak lanjut terlebih dahulu');
            return false;
        }
    });

    // Trigger change on page load
    document.getElementById('jenis_tindak_lanjut').dispatchEvent(new Event('change'));
</script>
@endpush
