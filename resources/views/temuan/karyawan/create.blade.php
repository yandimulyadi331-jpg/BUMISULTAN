@extends('layouts.mobile.app')

@section('content')
<style>
    * {
        -webkit-overflow-scrolling: touch !important;
    }

    html, body {
        overflow: visible !important;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        height: auto !important;
        max-height: none !important;
        min-height: 100vh !important;
    }

    #appCapsule {
        overflow: visible !important;
        height: auto !important;
        max-height: none !important;
        min-height: calc(100vh + 200px) !important;
        padding-bottom: 150px !important;
    }

    :root {
        --bg-primary: #f8f9fa;
        --bg-secondary: #ffffff;
        --text-primary: #1a202c;
        --text-secondary: #718096;
        --border-color: #e2e8f0;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    [data-theme="dark"] {
        --bg-primary: #1a202c;
        --bg-secondary: #2d3748;
        --text-primary: #f7fafc;
        --text-secondary: #cbd5e0;
        --border-color: #4a5568;
        --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
    }

    #content-section {
        margin-top: 56px;
        padding: 8px 16px 150px 16px;
        background: var(--bg-primary);
        min-height: calc(100vh - 56px);
    }

    .header-top {
        background: var(--bg-secondary);
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
        border-radius: 12px;
        box-shadow: var(--shadow);
    }

    .header-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-primary);
    }

    .btn-back {
        background: none;
        border: none;
        color: var(--text-primary);
        font-size: 24px;
        cursor: pointer;
        padding: 0;
    }

    .form-container {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        box-shadow: var(--shadow);
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-group:last-child {
        margin-bottom: 0;
    }

    .form-label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 8px;
    }

    .form-required {
        color: #ef4444;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 14px;
        background: var(--bg-primary);
        color: var(--text-primary);
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        background: var(--bg-secondary);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
        font-family: inherit;
    }

    .form-hint {
        font-size: 12px;
        color: var(--text-secondary);
        margin-top: 4px;
    }

    .form-error {
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    }

    .upload-area {
        border: 2px dashed var(--border-color);
        border-radius: 8px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: var(--bg-primary);
    }

    .upload-area:hover {
        border-color: #667eea;
        background: rgba(102, 126, 234, 0.05);
    }

    .upload-area input[type="file"] {
        display: none;
    }

    .upload-icon {
        font-size: 32px;
        margin-bottom: 8px;
    }

    .upload-text {
        font-size: 14px;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 4px;
    }

    .upload-hint {
        font-size: 12px;
        color: var(--text-secondary);
    }

    .preview-container {
        margin-top: 12px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
        gap: 8px;
    }

    .preview-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        background: var(--bg-primary);
    }

    .preview-image {
        width: 100%;
        height: 80px;
        object-fit: cover;
    }

    .btn-submit {
        width: 100%;
        padding: 14px 16px;
        background: #667eea;
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 16px;
    }

    .btn-submit:hover {
        background: #5568d3;
    }

    .btn-submit:active {
        transform: scale(0.98);
    }

    .urgency-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        margin-left: 4px;
    }

    .urgency-high {
        background: #fee2e2;
        color: #dc2626;
    }

    .urgency-medium {
        background: #fef3c7;
        color: #f59e0b;
    }

    .urgency-low {
        background: #dcfce7;
        color: #16a34a;
    }

    .alert-success {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }

    .alert-error {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 12px;
        font-size: 14px;
    }
</style>

<div id="content-section">
    {{-- Header --}}
    <div class="header-top">
        <a href="{{ route('temuan.karyawan.list') }}" class="btn-back">
            <ion-icon name="arrow-back-outline"></ion-icon>
        </a>
        <div class="header-title">📝 Laporkan Temuan</div>
        <div style="width: 40px;"></div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
        <div class="alert-success">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-error">
            ⚠ {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('temuan.karyawan.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Judul Temuan --}}
        <div class="form-container">
            <div class="form-group">
                <label class="form-label" for="judul">
                    Judul Temuan <span class="form-required">*</span>

                </label>
                <input type="text" class="form-input @error('judul') is-invalid @enderror" 
                       id="judul" name="judul" placeholder="Contoh: Kebocoran plafon di ruang rapat" 
                       value="{{ old('judul') }}" required>
                @error('judul')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Deskripsi --}}
        <div class="form-container">
            <div class="form-group">
                <label class="form-label" for="deskripsi">
                    Deskripsi Lengkap <span class="form-required">*</span>
                </label>
                <textarea class="form-textarea @error('deskripsi') is-invalid @enderror" 
                          id="deskripsi" name="deskripsi"
                          placeholder="Jelaskan detail masalah yang Anda temukan, kondisi kerusakan, dan dampaknya..."
                          required>{{ old('deskripsi') }}</textarea>
                <div class="form-hint">Deskripsi minimal 10 karakter</div>
                @error('deskripsi')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Lokasi --}}
        <div class="form-container">
            <div class="form-group">
                <label class="form-label" for="lokasi">
                    📍 Lokasi Temuan <span class="form-required">*</span>
                </label>
                <input type="text" class="form-input @error('lokasi') is-invalid @enderror" 
                       id="lokasi" name="lokasi" placeholder="Contoh: Gedung 2, Lantai 3, Ruang Rapat" 
                       value="{{ old('lokasi') }}" required>
                @error('lokasi')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Urgensi --}}
        <div class="form-container">
            <div class="form-group">
                <label class="form-label" for="urgensi">
                    ⚠️ Tingkat Urgensi <span class="form-required">*</span>
                </label>
                <select class="form-select @error('urgensi') is-invalid @enderror" 
                        id="urgensi" name="urgensi" required onchange="updateUrgencyBadge()">
                    <option value="">-- Pilih Tingkat Urgensi --</option>
                    <option value="rendah" {{ old('urgensi') == 'rendah' ? 'selected' : '' }}>
                        🟢 Rendah - Tidak mengganggu operasional
                    </option>
                    <option value="sedang" {{ old('urgensi') == 'sedang' ? 'selected' : '' }}>
                        🟡 Sedang - Sedikit mengganggu operasional
                    </option>
                    <option value="tinggi" {{ old('urgensi') == 'tinggi' ? 'selected' : '' }}>
                        🔴 Tinggi - Sangat mengganggu operasional
                    </option>
                    <option value="kritis" {{ old('urgensi') == 'kritis' ? 'selected' : '' }}>
                        🔴 Kritis - Membahayakan keselamatan
                    </option>
                </select>
                <div id="urgency-badge" class="form-hint" style="margin-top: 8px;"></div>
                @error('urgensi')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Foto --}}
        <div class="form-container">
            <div class="form-group">
                <label class="form-label">📸 Foto Bukti (Opsional)</label>
                <div class="upload-area" onclick="document.getElementById('foto').click()">
                    <div class="upload-icon">📷</div>
                    <div class="upload-text">Tap untuk upload foto</div>
                    <div class="upload-hint">JPG, PNG · Max 5 MB</div>
                </div>
                <input type="file" class="form-input" id="foto" name="foto" accept="image/*" 
                       style="display: none;" onchange="previewImage(event)">
                <div id="foto-preview" class="preview-container"></div>
                @error('foto')
                    <div class="form-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn-submit">
            ✓ Kirim Laporan Temuan
        </button>
    </form>

    {{-- Info Tips --}}
    <div class="form-container" style="margin-top: 12px; border-left: 3px solid #667eea;">
        <div class="form-group" style="margin-bottom: 0;">
            <div style="font-weight: 600; color: var(--text-primary); margin-bottom: 8px;">💡 Tips Membuat Laporan</div>
            <ul style="margin: 0; padding-left: 20px; font-size: 13px; color: var(--text-secondary);">
                <li>Jelaskan dengan detail dan spesifik</li>
                <li>Cantumkan lokasi yang jelas</li>
                <li>Sesuaikan tingkat urgensi</li>
                <li>Sertakan foto sebagai bukti</li>
                <li>Laporan ditangani maksimal 24 jam</li>
            </ul>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const previewDiv = document.getElementById('foto-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewDiv.innerHTML = `
                <div class="preview-item">
                    <img src="${e.target.result}" alt="Preview" class="preview-image">
                </div>
            `;
        }
        reader.readAsDataURL(file);
    } else {
        previewDiv.innerHTML = '';
    }
}

function updateUrgencyBadge() {
    const select = document.getElementById('urgensi');
    const badge = document.getElementById('urgency-badge');
    const urgencies = {
        'rendah': '<span class="urgency-badge urgency-low">🟢 Rendah</span>',
        'sedang': '<span class="urgency-badge urgency-medium">🟡 Sedang</span>',
        'tinggi': '<span class="urgency-badge urgency-high">🔴 Tinggi</span>',
        'kritis': '<span class="urgency-badge urgency-high">🔴 Kritis</span>'
    };
    badge.innerHTML = urgencies[select.value] || '';
}

// Update badge saat halaman load
document.addEventListener('DOMContentLoaded', updateUrgencyBadge);
</script>
@endsection