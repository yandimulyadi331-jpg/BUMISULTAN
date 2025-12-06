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

.form-control-modern,
.feedback-input {
    width: 100%;
    padding: 14px 16px;
    border: 2px solid var(--border-color);
    border-radius: 12px;
    font-size: 15px;
    background: var(--bg-primary);
    color: var(--text-primary);
    font-weight: 500;
    box-sizing: border-box;
}

.form-control-modern:focus,
.feedback-input:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-control-modern::placeholder,
.feedback-input::placeholder {
    color: var(--text-secondary);
    opacity: 0.6;
}

textarea.form-control-modern,
textarea.feedback-input {
    resize: vertical;
    min-height: 100px;
    font-family: inherit;
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
</style>

<div id="header-section">
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Izin Dinas</div>
        <div class="right"></div>
    </div>
</div>

<div id="content-section">
    <div class="form-card">
        <div class="form-title">
            <ion-icon name="airplane-outline"></ion-icon>
            <span>Form Izin Dinas</span>
        </div>

        <form action="{{ route('izindinas.store') }}" method="POST" id="formIzin" autocomplete="off">
            @csrf

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Dari Tanggal<span class="required">*</span>
                </label>
                <input type="text" class="feedback-input dari" name="dari" placeholder="Pilih tanggal mulai" id="datePicker" />
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Sampai Tanggal<span class="required">*</span>
                </label>
                <input type="text" class="feedback-input sampai" name="sampai" placeholder="Pilih tanggal selesai" id="datePicker2" />
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Jumlah Hari
                </label>
                <input type="text" class="feedback-input jml_hari" name="jml_hari" placeholder="Auto calculate" disabled />
            </div>

            <div class="form-group-modern">
                <label class="form-label-modern">
                    Keterangan<span class="required">*</span>
                </label>
                <textarea placeholder="Alasan mengajukan izin dinas..." class="feedback-input keterangan" name="keterangan" style="height: 100px"></textarea>
            </div>

            <button class="btn-submit" id="btnSimpan" type="submit">
                <ion-icon name="checkmark-circle-outline"></ion-icon>
                <span>Buat Izin Dinas</span>
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

    var lang = {
        title: 'Pilih Tanggal',
        cancel: 'Batal',
        confirm: 'Set',
        year: '',
        month: '',
        day: '',
        hour: '',
        min: '',
        sec: ''
    };
    new Rolldate({
        el: '#datePicker',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        lang: lang,
        confirm: function(date) {
            let jmlhari = hitungHari(date, $('#datePicker2').val());
            $('.jml_hari').val(jmlhari);
        }
    });

    new Rolldate({
        el: '#datePicker2',
        format: 'YYYY-MM-DD',
        beginYear: 2000,
        endYear: 2100,
        lang: lang,
        confirm: function(date) {
            let jmlhari = hitungHari($('#datePicker').val(), date);
            $('.jml_hari').val(jmlhari);
        }
    });

    function hitungHari(startDate, endDate) {

        if (startDate && endDate) {
            // let parts1 = startDate.split("-");
            // startDate = `${parts1[2]}-${parts1[1]}-${parts1[0]}`;

            // let parts2 = endDate.split("-");
            // endDate = `${parts2[2]}-${parts2[1]}-${parts2[0]}`;

            var start = new Date(startDate);
            var end = new Date(endDate);

            // Tambahkan 1 hari agar penghitungan inklusif
            var timeDifference = end - start + (1000 * 3600 * 24);
            var dayDifference = timeDifference / (1000 * 3600 * 24);

            return dayDifference;
        } else {
            return 0;
        }
    }

    $("#formIzin").submit(function(e) {
        let dari = $('.dari').val();
        let sampai = $('.sampai').val();
        let jml_hari = $('.jml_hari').val();
        let keterangan = $('.keterangan').val();

        if (dari == "" && sampai == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Periode Izin Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $('.dari').focus();
                }
            });
            return false;
        } else if (jml_hari == "") {
            Swal.fire({
                title: "Oops!",
                text: 'Jumlah Hari Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $('.jml_hari').focus();
                }
            });
            return false;
        } else if (sampai < dari) {
            Swal.fire({
                title: "Oops!",
                text: 'Periode Izin Harus Sesuai !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    form.find("#sampai").focus();
                }
            });
            return false;
        } else if (hitungHari(dari, sampai) > 3) {
            Swal.fire({
                title: "Oops!",
                text: 'Periode Izin Tidak Boleh Lebih Dari 3 Hari !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    form.find("#sampai").focus();
                }
            });
            return false;
        } else if (keterangan == '') {
            Swal.fire({
                title: "Oops!",
                text: 'Keterangan Harus Diisi !',
                icon: "warning",
                showConfirmButton: true,
                didClose: () => {
                    $('.keterangan').focus();
                }
            });
            return false;
        }
    });

    function buttonDisabled() {
        $("#btnSimpan").prop('disabled', true);
        $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white mr-2" role="status">
            </div>
            Sedang Mengirim..`);
    }
</script>
@endpush
