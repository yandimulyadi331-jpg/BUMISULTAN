@extends('layouts.mobile.app')
@section('content')
<style>
:root {
    --bg-primary: #f8f9fa;
    --bg-secondary: #ffffff;
    --text-primary: #1a202c;
    --text-secondary: #718096;
    --border-color: #e2e8f0;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

[data-theme="dark"] {
    --bg-primary: #1a202c;
    --bg-secondary: #2d3748;
    --text-primary: #f7fafc;
    --text-secondary: #cbd5e0;
    --border-color: #4a5568;
    --shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
}

* {
    transition: background 0.3s ease, color 0.3s ease, border-color 0.3s ease;
}

#header-section {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
}

#content-section {
    margin-top: 56px;
    padding: 20px;
    background: var(--bg-primary);
    min-height: 100vh;
    margin-bottom: 20px;
}

.form-card {
    background: var(--bg-secondary);
    border-radius: 16px;
    padding: 24px;
    box-shadow: var(--shadow);
    margin-bottom: 20px;
}

.form-title {
    font-size: 18px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-title ion-icon {
    font-size: 24px;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.form-group-modern {
    margin-bottom: 20px;
}

.form-label-modern {
    font-size: 13px;
    font-weight: 600;
    color: var(--text-secondary);
    margin-bottom: 8px;
    display: block;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.form-label-modern .required {
    color: #ef4444;
    margin-left: 4px;
}

.form-control-modern {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 15px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-weight: 500;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control-modern::placeholder {
    color: var(--text-secondary);
    opacity: 0.6;
}

textarea.form-control-modern {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
}

.info-alert {
    background: #3b82f620;
    border: 2px solid #3b82f640;
    border-radius: 12px;
    padding: 16px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
    margin-bottom: 20px;
}

.info-alert ion-icon {
    font-size: 24px;
    color: #3b82f6;
    flex-shrink: 0;
    margin-top: 2px;
}

.info-alert-content {
    flex: 1;
    font-size: 13px;
    color: var(--text-primary);
    line-height: 1.5;
}

.info-alert-content strong {
    font-weight: 700;
    display: block;
    margin-bottom: 4px;
}

.btn-submit {
    width: 100%;
    padding: 16px;
    background: var(--gradient-primary);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}

.btn-submit:active {
    transform: scale(0.98);
}

.btn-submit ion-icon {
    font-size: 22px;
}

.theme-toggle-floating {
    position: fixed;
    bottom: 90px;
    right: 20px;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 999;
}

.theme-toggle-floating ion-icon {
    font-size: 24px;
    color: var(--text-primary);
}

.theme-toggle-floating:active {
    transform: scale(0.95);
}

.error-message {
    font-size: 13px;
    color: #ef4444;
    margin-top: 6px;
    font-weight: 500;
}
</style>

<div id="header-section">
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{ route('pengajuanizin.index') }}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Pengajuan Tugas Luar</div>
        <div class="right"></div>
    </div>
</div>

<div id="content-section">
    <div class="form-card">
        <div class="form-title">
            <ion-icon name="briefcase-outline"></ion-icon>
            <span>Form Tugas Luar</span>
        </div>

        <form action="{{ route('tugasluar.store') }}" method="POST" id="formTugasLuar">
            @csrf
            
            <div class="form-group-modern">
                <label class="form-label-modern">
                    Tanggal<span class="required">*</span>
                </label>
                <input type="date" class="form-control-modern" name="tanggal" 
                    value="{{ old('tanggal', date('Y-m-d')) }}" required>
                @error('tanggal')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Tujuan<span class="required">*</span>
                </label>
                <input type="text" class="form-control-modern" name="tujuan" 
                    placeholder="Contoh: Meeting dengan Client, Survey Lokasi" 
                    value="{{ old('tujuan') }}" required>
                @error('tujuan')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Waktu Keluar<span class="required">*</span>
                </label>
                <input type="time" class="form-control-modern" name="waktu_keluar" 
                    value="{{ old('waktu_keluar') }}" required>
                @error('waktu_keluar')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Keterangan
                </label>
                <textarea class="form-control-modern" name="keterangan" 
                    placeholder="Detail tambahan mengenai tugas luar...">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="info-alert">
                <ion-icon name="information-circle-outline"></ion-icon>
                <div class="info-alert-content">
                    <strong>Catatan Penting</strong>
                    Pastikan Anda sudah melakukan presensi hadir sebelum mengajukan tugas luar.
                </div>
            </div>

            <button type="submit" class="btn-submit">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <span>Ajukan Tugas Luar</span>
            </button>
        </form>
    </div>
</div>

<button class="theme-toggle-floating" onclick="toggleDarkMode()">
    <ion-icon name="sunny-outline" id="themeIcon"></ion-icon>
</button>

@endsection

@push('myscript')
<script>
    // Dark Mode Toggle
    function toggleDarkMode() {
        const body = document.body;
        const themeIcon = document.getElementById('themeIcon');
        
        if (body.getAttribute('data-theme') === 'dark') {
            body.removeAttribute('data-theme');
            themeIcon.setAttribute('name', 'sunny-outline');
            localStorage.setItem('theme', 'light');
        } else {
            body.setAttribute('data-theme', 'dark');
            themeIcon.setAttribute('name', 'moon-outline');
            localStorage.setItem('theme', 'dark');
        }
    }

    // Load saved theme
    document.addEventListener('DOMContentLoaded', function() {
        const savedTheme = localStorage.getItem('theme');
        const themeIcon = document.getElementById('themeIcon');
        
        if (savedTheme === 'dark') {
            document.body.setAttribute('data-theme', 'dark');
            themeIcon.setAttribute('name', 'moon-outline');
        }
    });

    $(document).ready(function() {
        $('#formTugasLuar').submit(function(e) {
            e.preventDefault();
            
            Swal.fire({
                title: 'Konfirmasi Pengajuan',
                text: "Ajukan tugas luar ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Ajukan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endpush
