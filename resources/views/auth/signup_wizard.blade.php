<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            background: url('{{ asset('assets/login/images/background.png') }}') no-repeat center center fixed;
            background-size: cover;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.8) 0%, rgba(118, 75, 162, 0.8) 100%);
            z-index: 0;
        }

        .signup-container {
            width: 100%;
            max-width: 800px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 45px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .signup-title {
            text-align: center;
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .signup-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 35px;
            font-size: 1.05rem;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 35px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 8%;
            right: 8%;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            z-index: 0;
        }

        .step-item {
            flex: 1;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .step-circle {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.4s;
            border: 3px solid transparent;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            border-color: #fff;
            box-shadow: 0 6px 20px rgba(168, 224, 99, 0.7);
            color: #fff;
            transform: scale(1.1);
        }

        .step-item.completed .step-circle {
            background: rgba(86, 171, 47, 0.8);
            border-color: rgba(255, 255, 255, 0.8);
            color: #fff;
        }

        .step-item.completed .step-circle i {
            font-size: 1.2rem;
        }

        .step-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .step-item.active .step-label {
            color: #fff;
            font-weight: 700;
        }

        /* Step Content */
        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeInSlide 0.4s ease-out;
        }

        @keyframes fadeInSlide {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
            font-weight: 600;
            font-size: 0.95rem;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
        }

        .required {
            color: #ffeb3b;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            backdrop-filter: blur(10px);
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-control:focus {
            outline: none;
            border-color: #a8e063;
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 0 0 4px rgba(168, 224, 99, 0.3);
        }

        select.form-control {
            cursor: pointer;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        select.form-control option {
            background: rgba(50, 50, 80, 0.95);
            color: #fff;
            padding: 10px;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 90px;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        /* Camera Preview */
        .camera-preview {
            position: relative;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 15px;
            overflow: hidden;
            aspect-ratio: 4/3;
            margin-bottom: 15px;
        }

        .camera-preview video,
        .camera-preview canvas {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .camera-preview canvas {
            display: none;
        }

        .camera-preview.captured video {
            display: none;
        }

        .camera-preview.captured canvas {
            display: block;
        }

        /* Multi Photos Grid */
        .multi-photos {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 15px;
        }

        .photo-slot {
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed rgba(255, 255, 255, 0.4);
            position: relative;
            overflow: hidden;
            transition: all 0.3s;
        }

        .photo-slot:hover {
            border-color: #a8e063;
            background: rgba(168, 224, 99, 0.1);
        }

        .photo-slot.filled {
            border-color: #56ab2f;
            border-style: solid;
            background: rgba(86, 171, 47, 0.2);
        }

        .photo-slot img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
        }

        .photo-label {
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.9);
            text-align: center;
            padding: 8px;
            z-index: 1;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        /* Buttons */
        .btn {
            padding: 14px 28px;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
        }

        .btn:active {
            transform: translateY(0);
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-success {
            background: linear-gradient(135deg, #a8e063 0%, #56ab2f 100%);
            color: white;
        }

        .btn-warning {
            background: linear-gradient(135deg, #ffd89b 0%, #19547b 100%);
            color: white;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.25);
            color: white;
            backdrop-filter: blur(10px);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            gap: 15px;
        }

        .btn-prev,
        .btn-next {
            flex: 1;
            max-width: 200px;
        }

        .btn-next {
            margin-left: auto;
        }

        /* Progress Bar */
        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #a8e063, #56ab2f);
            transition: width 0.3s;
            width: 0%;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            backdrop-filter: blur(10px);
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.3);
            border: 1px solid rgba(255, 107, 107, 0.6);
            color: #fff;
        }

        /* Back to Login */
        .back-to-login {
            text-align: center;
            margin-top: 25px;
        }

        .back-to-login a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.15);
            transition: all 0.3s;
        }

        .back-to-login a:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .signup-container {
                padding: 30px 25px;
            }

            .signup-title {
                font-size: 2rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .multi-photos {
                grid-template-columns: repeat(2, 1fr);
            }

            .step-label {
                font-size: 0.7rem;
            }

            .step-circle {
                width: 38px;
                height: 38px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h1 class="signup-title">Sign Up</h1>
        <p class="signup-subtitle">Create your account to join our team</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-item active" data-step="1">
                <div class="step-circle">1</div>
                <div class="step-label">Data Pribadi</div>
            </div>
            <div class="step-item" data-step="2">
                <div class="step-circle">2</div>
                <div class="step-label">Data Pekerjaan</div>
            </div>
            <div class="step-item" data-step="3">
                <div class="step-circle">3</div>
                <div class="step-label">Foto Profil</div>
            </div>
            <div class="step-item" data-step="4">
                <div class="step-circle">4</div>
                <div class="step-label">Foto Wajah</div>
            </div>
            <div class="step-item" data-step="5">
                <div class="step-circle">5</div>
                <div class="step-label">Password</div>
            </div>
        </div>

        <form id="formSignup" action="{{ route('signup.store') }}" method="POST">
            @csrf

            <!-- Step 1: Data Pribadi -->
            <div class="step-content active" data-step="1">
                <div class="form-row">
                    <div class="form-group">
                        <label>NIK Display <span class="required">*</span></label>
                        <input type="text" name="nik_show" class="form-control" placeholder="Contoh: 12.34.567" value="{{ old('nik_show') }}" required>
                    </div>
                    <div class="form-group">
                        <label>No. KTP <span class="required">*</span></label>
                        <input type="text" name="no_ktp" class="form-control" placeholder="16 digit" maxlength="16" value="{{ old('no_ktp') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap <span class="required">*</span></label>
                    <input type="text" name="nama_karyawan" class="form-control" placeholder="Nama lengkap sesuai KTP" value="{{ old('nama_karyawan') }}" required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tempat Lahir <span class="required">*</span></label>
                        <input type="text" name="tempat_lahir" class="form-control" placeholder="Kota lahir" value="{{ old('tempat_lahir') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="required">*</span></label>
                        <input type="date" name="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="alamat" class="form-control" placeholder="Alamat sesuai KTP" required>{{ old('alamat') }}</textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>No. HP <span class="required">*</span></label>
                        <input type="text" name="no_hp" class="form-control" placeholder="08xxxxxxxxxx" value="{{ old('no_hp') }}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email <span class="required">*</span></label>
                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com" value="{{ old('email') }}" required>
                    <small style="color: rgba(255,255,255,0.8); font-size: 0.85rem; display: block; margin-top: 5px;">
                        <i class="fas fa-info-circle"></i> Email diperlukan untuk pengiriman slip gaji
                    </small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Status Perkawinan <span class="required">*</span></label>
                        <select name="kode_status_kawin" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach($status_kawin as $sk)
                                <option value="{{ $sk->kode_status_kawin }}" {{ old('kode_status_kawin') == $sk->kode_status_kawin ? 'selected' : '' }}>
                                    {{ $sk->status_kawin }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Pendidikan Terakhir <span class="required">*</span></label>
                        <select name="pendidikan_terakhir" class="form-control" required>
                            <option value="">-- Pilih Pendidikan --</option>
                            <option value="SD" {{ old('pendidikan_terakhir') == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ old('pendidikan_terakhir') == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ old('pendidikan_terakhir') == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="SMK" {{ old('pendidikan_terakhir') == 'SMK' ? 'selected' : '' }}>SMK</option>
                            <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                            <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                            <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 2: Data Pekerjaan -->
            <div class="step-content" data-step="2">
                <div class="form-group">
                    <label>Kantor Cabang <span class="required">*</span></label>
                    <select name="kode_cabang" class="form-control" required>
                        <option value="">-- Pilih Kantor Cabang --</option>
                        @foreach($cabang as $c)
                            <option value="{{ $c->kode_cabang }}" {{ old('kode_cabang') == $c->kode_cabang ? 'selected' : '' }}>
                                {{ $c->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Departemen <span class="required">*</span></label>
                        <select name="kode_dept" class="form-control" required>
                            <option value="">-- Pilih Departemen --</option>
                            @foreach($departemen as $d)
                                <option value="{{ $d->kode_dept }}" {{ old('kode_dept') == $d->kode_dept ? 'selected' : '' }}>
                                    {{ strtoupper($d->nama_dept) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jabatan <span class="required">*</span></label>
                        <select name="kode_jabatan" class="form-control" required>
                            <option value="">-- Pilih Jabatan --</option>
                            @foreach($jabatan as $j)
                                <option value="{{ $j->kode_jabatan }}" {{ old('kode_jabatan') == $j->kode_jabatan ? 'selected' : '' }}>
                                    {{ strtoupper($j->nama_jabatan) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label>Tanggal Masuk <span class="required">*</span></label>
                        <input type="date" name="tanggal_masuk" class="form-control" value="{{ old('tanggal_masuk') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Status Karyawan <span class="required">*</span></label>
                        <select name="status_karyawan" class="form-control" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="K" {{ old('status_karyawan') == 'K' ? 'selected' : '' }}>Kontrak</option>
                            <option value="T" {{ old('status_karyawan') == 'T' ? 'selected' : '' }}>Tetap</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Step 3: Foto Profil -->
            <div class="step-content" data-step="3">
                <h3 style="color: #fff; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-user-circle"></i> Foto Profil Karyawan
                </h3>
                <p style="color: rgba(255,255,255,0.9); text-align: center; margin-bottom: 25px; font-size: 0.95rem;">
                    Ambil 1 foto untuk tampilan profil Anda
                </p>

                <div class="camera-preview" id="previewProfil">
                    <video id="videoProfil" autoplay></video>
                    <canvas id="canvasProfil"></canvas>
                </div>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary" id="startCameraProfil">
                        <i class="fas fa-camera"></i> Buka Kamera
                    </button>
                    <button type="button" class="btn btn-success" id="capturePhotoProfil" style="display: none;">
                        <i class="fas fa-camera-retro"></i> Ambil Foto
                    </button>
                    <button type="button" class="btn btn-warning" id="retakePhotoProfil" style="display: none;">
                        <i class="fas fa-redo"></i> Ambil Ulang
                    </button>
                </div>
                <input type="hidden" name="foto_profil" id="fotoProfilInput">
            </div>

            <!-- Step 4: Foto Wajah Absensi -->
            <div class="step-content" data-step="4">
                <h3 style="color: #fff; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-id-card"></i> Foto Wajah untuk Absensi
                </h3>
                <p style="color: rgba(255,255,255,0.9); text-align: center; margin-bottom: 25px; font-size: 0.95rem;">
                    5 foto dari berbagai sudut untuk sistem face recognition
                </p>

                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>

                <div class="camera-preview" id="previewWajah" style="max-width: 500px; margin: 0 auto 20px;">
                    <video id="videoWajah" autoplay></video>
                    <canvas id="canvasWajah"></canvas>
                </div>

                <div class="multi-photos">
                    <div class="photo-slot" id="slot1">
                        <div class="photo-label">1. Depan</div>
                    </div>
                    <div class="photo-slot" id="slot2">
                        <div class="photo-label">2. Kiri</div>
                    </div>
                    <div class="photo-slot" id="slot3">
                        <div class="photo-label">3. Kanan</div>
                    </div>
                    <div class="photo-slot" id="slot4">
                        <div class="photo-label">4. Atas</div>
                    </div>
                    <div class="photo-slot" id="slot5">
                        <div class="photo-label">5. Bawah</div>
                    </div>
                </div>

                <div style="display: flex; gap: 12px; justify-content: center; flex-wrap: wrap; margin-top: 20px;">
                    <button type="button" class="btn btn-primary" id="startMultiCapture">
                        <i class="fas fa-video"></i> Mulai Rekam Wajah
                    </button>
                    <button type="button" class="btn btn-warning" id="resetMultiCapture" style="display: none;">
                        <i class="fas fa-redo"></i> Ulang Semua
                    </button>
                </div>
                <input type="hidden" name="foto_wajah_multiple" id="fotoWajahMultiple">
            </div>

            <!-- Step 5: Password -->
            <div class="step-content" data-step="5">
                <h3 style="color: #fff; margin-bottom: 20px; text-align: center;">
                    <i class="fas fa-lock"></i> Buat Password
                </h3>
                <p style="color: rgba(255,255,255,0.9); text-align: center; margin-bottom: 25px; font-size: 0.95rem;">
                    Password untuk login ke sistem
                </p>

                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" minlength="6" required>
                </div>

                <div class="form-group">
                    <label>Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" minlength="6" required>
                </div>

                <div style="background: rgba(168, 224, 99, 0.2); border: 1px solid rgba(168, 224, 99, 0.5); border-radius: 12px; padding: 20px; margin-top: 25px;">
                    <h4 style="color: #fff; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-check-circle" style="color: #a8e063;"></i>
                        Data Siap Didaftarkan
                    </h4>
                    <p style="color: rgba(255,255,255,0.95); margin: 0; font-size: 0.9rem;">
                        Setelah klik "Daftar Sekarang", data Anda akan dikirim untuk persetujuan admin. Anda akan menerima notifikasi setelah akun disetujui.
                    </p>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="nav-buttons">
                <button type="button" class="btn btn-secondary btn-prev" id="btnPrev" style="display: none;">
                    <i class="fas fa-arrow-left"></i> Kembali
                </button>
                <button type="button" class="btn btn-success btn-next" id="btnNext">
                    Berikutnya <i class="fas fa-arrow-right"></i>
                </button>
                <button type="submit" class="btn btn-success btn-next" id="btnSubmit" style="display: none;">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </div>
        </form>

        <div class="back-to-login">
            <a href="{{ route('loginuser') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Login
            </a>
        </div>
    </div>

    <script>
        // Multi-Step Navigation
        let currentStep = 1;
        const totalSteps = 5;

        function showStep(step) {
            document.querySelectorAll('.step-content').forEach(content => {
                content.classList.remove('active');
            });
            document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');

            document.querySelectorAll('.step-item').forEach((item, index) => {
                item.classList.remove('active', 'completed');
                if (index + 1 < step) {
                    item.classList.add('completed');
                    item.querySelector('.step-circle').innerHTML = '<i class="fas fa-check"></i>';
                } else if (index + 1 === step) {
                    item.classList.add('active');
                    item.querySelector('.step-circle').textContent = index + 1;
                } else {
                    item.querySelector('.step-circle').textContent = index + 1;
                }
            });

            document.getElementById('btnPrev').style.display = step === 1 ? 'none' : 'flex';
            document.getElementById('btnNext').style.display = step === totalSteps ? 'none' : 'flex';
            document.getElementById('btnSubmit').style.display = step === totalSteps ? 'flex' : 'none';
        }

        function validateStep(step) {
            const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
            const inputs = stepContent.querySelectorAll('input[required], select[required], textarea[required]');
            
            for (let input of inputs) {
                if (!input.value) {
                    input.focus();
                    Swal.fire('Perhatian', 'Mohon lengkapi semua field yang wajib diisi!', 'warning');
                    return false;
                }
            }

            if (step === 3 && !document.getElementById('fotoProfilInput').value) {
                Swal.fire('Perhatian', 'Silakan ambil foto profil terlebih dahulu!', 'warning');
                return false;
            }

            if (step === 4 && !document.getElementById('fotoWajahMultiple').value) {
                Swal.fire('Perhatian', 'Silakan ambil 5 foto wajah untuk absensi!', 'warning');
                return false;
            }

            return true;
        }

        document.getElementById('btnNext').addEventListener('click', () => {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    showStep(currentStep);
                }
            }
        });

        document.getElementById('btnPrev').addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });

        // Foto Profil
        const videoProfil = document.getElementById('videoProfil');
        const canvasProfil = document.getElementById('canvasProfil');
        const startCameraProfilBtn = document.getElementById('startCameraProfil');
        const capturePhotoProfilBtn = document.getElementById('capturePhotoProfil');
        const retakePhotoProfilBtn = document.getElementById('retakePhotoProfil');
        const fotoProfilInput = document.getElementById('fotoProfilInput');
        const previewProfil = document.getElementById('previewProfil');
        let streamProfil = null;

        startCameraProfilBtn.addEventListener('click', async () => {
            try {
                streamProfil = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
                });
                videoProfil.srcObject = streamProfil;
                startCameraProfilBtn.style.display = 'none';
                capturePhotoProfilBtn.style.display = 'inline-flex';
            } catch (err) {
                Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + err.message, 'error');
            }
        });

        capturePhotoProfilBtn.addEventListener('click', () => {
            const context = canvasProfil.getContext('2d');
            canvasProfil.width = videoProfil.videoWidth;
            canvasProfil.height = videoProfil.videoHeight;
            context.drawImage(videoProfil, 0, 0, canvasProfil.width, canvasProfil.height);
            
            fotoProfilInput.value = canvasProfil.toDataURL('image/jpeg', 0.8);
            
            if (streamProfil) {
                streamProfil.getTracks().forEach(track => track.stop());
            }
            
            previewProfil.classList.add('captured');
            capturePhotoProfilBtn.style.display = 'none';
            retakePhotoProfilBtn.style.display = 'inline-flex';
            
            Swal.fire({
                icon: 'success',
                title: 'Foto Profil Berhasil!',
                text: 'Klik "Berikutnya" untuk melanjutkan',
                timer: 2000,
                showConfirmButton: false
            });
        });

        retakePhotoProfilBtn.addEventListener('click', () => {
            fotoProfilInput.value = '';
            retakePhotoProfilBtn.style.display = 'none';
            previewProfil.classList.remove('captured');
            startCameraProfilBtn.click();
        });

        // Foto Wajah Multiple
        const videoWajah = document.getElementById('videoWajah');
        const canvasWajah = document.getElementById('canvasWajah');
        const startMultiCaptureBtn = document.getElementById('startMultiCapture');
        const resetMultiCaptureBtn = document.getElementById('resetMultiCapture');
        const fotoWajahMultipleInput = document.getElementById('fotoWajahMultiple');
        const progressFill = document.getElementById('progressFill');
        const previewWajah = document.getElementById('previewWajah');
        
        const DIRECTIONS = [
            { key: '1_front', label: 'Hadapkan wajah ke DEPAN', instruction: 'Posisi 1 dari 5: Hadap DEPAN' },
            { key: '2_left', label: 'Tengok ke KIRI', instruction: 'Posisi 2 dari 5: Tengok KIRI' },
            { key: '3_right', label: 'Tengok ke KANAN', instruction: 'Posisi 3 dari 5: Tengok KANAN' },
            { key: '4_up', label: 'Lihat ke ATAS', instruction: 'Posisi 4 dari 5: Lihat ATAS' },
            { key: '5_down', label: 'Lihat ke BAWAH', instruction: 'Posisi 5 dari 5: Lihat BAWAH' }
        ];
        
        let capturedImages = [];
        let currentIndex = 0;
        let streamWajah = null;

        async function startMultiCapture() {
            try {
                streamWajah = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
                });
                videoWajah.srcObject = streamWajah;
                previewWajah.classList.remove('captured');
                
                capturedImages = [];
                currentIndex = 0;
                startMultiCaptureBtn.style.display = 'none';
                updateProgress();
                
                showInstruction();
            } catch (err) {
                Swal.fire('Error', 'Tidak dapat mengakses kamera: ' + err.message, 'error');
            }
        }

        function showInstruction() {
            if (currentIndex < DIRECTIONS.length) {
                Swal.fire({
                    title: DIRECTIONS[currentIndex].instruction,
                    text: DIRECTIONS[currentIndex].label,
                    icon: 'info',
                    confirmButtonText: 'Ambil Foto',
                    confirmButtonColor: '#56ab2f',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        captureMultiPhoto();
                    }
                });
            }
        }

        function captureMultiPhoto() {
            const context = canvasWajah.getContext('2d');
            canvasWajah.width = videoWajah.videoWidth;
            canvasWajah.height = videoWajah.videoHeight;
            context.drawImage(videoWajah, 0, 0, canvasWajah.width, canvasWajah.height);
            
            const imageData = canvasWajah.toDataURL('image/jpeg', 0.8);
            capturedImages.push({
                direction: DIRECTIONS[currentIndex].key,
                image: imageData
            });
            
            const slot = document.getElementById(`slot${currentIndex + 1}`);
            slot.innerHTML = `<img src="${imageData}" alt="Foto ${currentIndex + 1}">`;
            slot.classList.add('filled');
            
            currentIndex++;
            updateProgress();
            
            if (currentIndex < DIRECTIONS.length) {
                setTimeout(() => showInstruction(), 500);
            } else {
                if (streamWajah) {
                    streamWajah.getTracks().forEach(track => track.stop());
                }
                previewWajah.classList.add('captured');
                
                fotoWajahMultipleInput.value = JSON.stringify(capturedImages);
                resetMultiCaptureBtn.style.display = 'inline-flex';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Semua Foto Berhasil!',
                    text: '5 foto wajah telah diambil. Klik "Berikutnya" untuk melanjutkan',
                    timer: 2500,
                    showConfirmButton: false
                });
            }
        }

        function updateProgress() {
            const percent = (currentIndex / DIRECTIONS.length) * 100;
            progressFill.style.width = percent + '%';
        }

        startMultiCaptureBtn.addEventListener('click', startMultiCapture);

        resetMultiCaptureBtn.addEventListener('click', () => {
            capturedImages = [];
            currentIndex = 0;
            fotoWajahMultipleInput.value = '';
            progressFill.style.width = '0%';
            
            for (let i = 1; i <= 5; i++) {
                const slot = document.getElementById(`slot${i}`);
                const label = ['Depan', 'Kiri', 'Kanan', 'Atas', 'Bawah'][i-1];
                slot.innerHTML = `<div class="photo-label">${i}. ${label}</div>`;
                slot.classList.remove('filled');
            }
            
            resetMultiCaptureBtn.style.display = 'none';
            startMultiCaptureBtn.style.display = 'inline-flex';
        });

        // Form Submit
        document.getElementById('formSignup').addEventListener('submit', (e) => {
            e.preventDefault();
            
            if (!fotoProfilInput.value) {
                Swal.fire('Perhatian', 'Foto profil belum diambil!', 'warning');
                return false;
            }
            
            if (!fotoWajahMultipleInput.value) {
                Swal.fire('Perhatian', 'Foto wajah absensi belum diambil!', 'warning');
                return false;
            }
            
            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu, sistem sedang memproses pendaftaran Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            e.target.submit();
        });

        // Improve Select Visibility
        document.querySelectorAll('select.form-control').forEach(select => {
            select.addEventListener('change', function() {
                this.style.color = this.value !== '' ? '#fff' : 'rgba(255, 255, 255, 0.6)';
            });
        });

        // Initialize
        showStep(1);
    </script>
</body>
</html>
