<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - {{ config('app.name') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            max-width: 700px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 25px;
            padding: 40px;
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            position: relative;
            z-index: 1;
        }

        .signup-title {
            text-align: center;
            color: #fff;
            font-size: 2.2rem;
            margin-bottom: 10px;
            font-weight: 700;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }

        .signup-subtitle {
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            font-size: 1rem;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            position: relative;
        }

        .step-indicator::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
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
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 8px;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s;
            border: 2px solid transparent;
        }

        .step-item.active .step-circle {
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            border-color: #fff;
            box-shadow: 0 4px 15px rgba(168, 224, 99, 0.6);
        }

        .step-item.completed .step-circle {
            background: linear-gradient(135deg, #56ab2f, #a8e063);
            border-color: #fff;
        }

        .step-label {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
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
            animation: fadeIn 0.4s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .photo-section {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .section-title {
            font-size: 1.2rem;
            color: #667eea;
            margin-bottom: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .photo-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .camera-preview {
            position: relative;
            background: #000;
            border-radius: 10px;
            overflow: hidden;
            aspect-ratio: 1;
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

        .multi-photos {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .photo-slot {
            aspect-ratio: 1;
            background: #e9ecef;
            border-radius: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
            position: relative;
            overflow: hidden;
        }

        .photo-slot.filled {
            border-color: #28a745;
            background: #d4edda;
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
            color: #666;
            text-align: center;
            padding: 5px;
            z-index: 1;
        }

        .camera-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: #667eea;
            color: white;
        }

        .btn-primary:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-2px);
        }

        .btn-warning {
            background: #ffc107;
            color: #000;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-2px);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            margin-top: 20px;
            font-size: 1.1rem;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }

        .required {
            color: #dc3545;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 80px;
        }

        .alert {
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .alert-danger {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .instruction {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .instruction-title {
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .instruction-list {
            padding-left: 25px;
            margin: 0;
        }

        .instruction-list li {
            margin-bottom: 5px;
        }

        .back-to-login {
            text-align: center;
            margin-top: 20px;
        }

        .back-to-login a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: #e9ecef;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #28a745, #20c997);
            transition: width 0.3s;
            width: 0%;
        }

        @media (max-width: 768px) {
            .photo-grid {
                grid-template-columns: 1fr;
            }

            .signup-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <form id="formSignup" action="{{ route('signup.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <h1 class="signup-title">Sign Up</h1>
            <p class="signup-subtitle">Create your account to join our team</p>

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Instruction -->
            <div class="instruction">
                <div class="instruction-title">
                    <i class="fas fa-info-circle"></i>
                    Petunjuk Pengambilan Foto
                </div>
                <ul class="instruction-list">
                    <li><strong>Foto Profil:</strong> 1 foto untuk tampilan profil Anda</li>
                    <li><strong>Foto Wajah Absensi:</strong> 5 foto dari berbagai sudut (depan, kiri, kanan, atas, bawah) untuk sistem face recognition</li>
                    <li>Pastikan pencahayaan cukup dan wajah terlihat jelas</li>
                    <li>Ikuti instruksi arah wajah yang muncul di layar</li>
                </ul>
            </div>

            <!-- Foto Profil Section -->
            <div class="photo-section">
                <div class="section-title">
                    <i class="fas fa-user-circle"></i>
                    Foto Profil Karyawan
                </div>
                <div class="camera-preview" id="previewProfil">
                    <video id="videoProfil" autoplay></video>
                    <canvas id="canvasProfil"></canvas>
                </div>
                <div class="camera-buttons" style="margin-top: 15px;">
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

            <!-- Foto Wajah Absensi Section -->
            <div class="photo-section">
                <div class="section-title">
                    <i class="fas fa-id-card"></i>
                    Foto Wajah untuk Absensi (5 Gambar)
                </div>
                
                <div class="progress-bar">
                    <div class="progress-fill" id="progressFill"></div>
                </div>
                
                <div class="photo-grid">
                    <div class="camera-preview" id="previewWajah">
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
                </div>
                
                <div class="camera-buttons" style="margin-top: 15px;">
                    <button type="button" class="btn btn-primary" id="startMultiCapture">
                        <i class="fas fa-video"></i> Mulai Rekam Wajah (5 Gambar)
                    </button>
                    <button type="button" class="btn btn-warning" id="resetMultiCapture" style="display: none;">
                        <i class="fas fa-redo"></i> Ulang Semua
                    </button>
                </div>
                
                <input type="hidden" name="foto_wajah_multiple" id="fotoWajahMultiple">
            </div>

            <!-- Personal Information -->
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
                        <option value="">-- Pilih Status Perkawinan --</option>
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
                        <option value="D1" {{ old('pendidikan_terakhir') == 'D1' ? 'selected' : '' }}>D1</option>
                        <option value="D2" {{ old('pendidikan_terakhir') == 'D2' ? 'selected' : '' }}>D2</option>
                        <option value="D3" {{ old('pendidikan_terakhir') == 'D3' ? 'selected' : '' }}>D3</option>
                        <option value="D4" {{ old('pendidikan_terakhir') == 'D4' ? 'selected' : '' }}>D4</option>
                        <option value="S1" {{ old('pendidikan_terakhir') == 'S1' ? 'selected' : '' }}>S1</option>
                        <option value="S2" {{ old('pendidikan_terakhir') == 'S2' ? 'selected' : '' }}>S2</option>
                        <option value="S3" {{ old('pendidikan_terakhir') == 'S3' ? 'selected' : '' }}>S3</option>
                    </select>
                </div>
            </div>

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
                        <option value="">-- Pilih Status Karyawan --</option>
                        <option value="K" {{ old('status_karyawan') == 'K' ? 'selected' : '' }}>Kontrak</option>
                        <option value="T" {{ old('status_karyawan') == 'T' ? 'selected' : '' }}>Tetap</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Password <span class="required">*</span></label>
                    <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" minlength="6" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password <span class="required">*</span></label>
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn btn-submit" id="btnSubmit">
                <i class="fas fa-user-plus"></i> Daftar Sekarang
            </button>

            <div class="back-to-login">
                <a href="{{ route('loginuser') }}">
                    <i class="fas fa-arrow-left"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>

    <script>
        // ============ FOTO PROFIL ============
        const videoProfil = document.getElementById('videoProfil');
        const canvasProfil = document.getElementById('canvasProfil');
        const startCameraProfilBtn = document.getElementById('startCameraProfil');
        const capturePhotoProfilBtn = document.getElementById('capturePhotoProfil');
        const retakePhotoProfilBtn = document.getElementById('retakePhotoProfil');
        const fotoProfilInput = document.getElementById('fotoProfilInput');
        let streamProfil = null;

        startCameraProfilBtn.addEventListener('click', async () => {
            try {
                streamProfil = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
                });
                videoProfil.srcObject = streamProfil;
                videoProfil.style.display = 'block';
                canvasProfil.style.display = 'none';
                startCameraProfilBtn.style.display = 'none';
                capturePhotoProfilBtn.style.display = 'inline-block';
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
            
            videoProfil.style.display = 'none';
            canvasProfil.style.display = 'block';
            capturePhotoProfilBtn.style.display = 'none';
            retakePhotoProfilBtn.style.display = 'inline-block';
            
            Swal.fire({
                icon: 'success',
                title: 'Foto Profil Berhasil!',
                text: 'Silakan lanjutkan untuk mengambil foto wajah absensi',
                timer: 2000,
                showConfirmButton: false
            });
        });

        retakePhotoProfilBtn.addEventListener('click', () => {
            fotoProfilInput.value = '';
            retakePhotoProfilBtn.style.display = 'none';
            startCameraProfilBtn.click();
        });

        // ============ FOTO WAJAH MULTIPLE ============
        const videoWajah = document.getElementById('videoWajah');
        const canvasWajah = document.getElementById('canvasWajah');
        const startMultiCaptureBtn = document.getElementById('startMultiCapture');
        const resetMultiCaptureBtn = document.getElementById('resetMultiCapture');
        const fotoWajahMultipleInput = document.getElementById('fotoWajahMultiple');
        const progressFill = document.getElementById('progressFill');
        
        const DIRECTIONS = [
            { key: '1_front', label: 'Hadapkan wajah ke DEPAN' },
            { key: '2_left', label: 'Tengok ke KIRI' },
            { key: '3_right', label: 'Tengok ke KANAN' },
            { key: '4_up', label: 'Lihat ke ATAS' },
            { key: '5_down', label: 'Lihat ke BAWAH' }
        ];
        
        let capturedImages = [];
        let currentIndex = 0;
        let streamWajah = null;
        let isMultiCaptureActive = false;

        async function startMultiCapture() {
            try {
                streamWajah = await navigator.mediaDevices.getUserMedia({ 
                    video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
                });
                videoWajah.srcObject = streamWajah;
                videoWajah.style.display = 'block';
                canvasWajah.style.display = 'none';
                
                capturedImages = [];
                currentIndex = 0;
                isMultiCaptureActive = true;
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
                    title: `Foto ${currentIndex + 1}/5`,
                    text: DIRECTIONS[currentIndex].label,
                    icon: 'info',
                    confirmButtonText: 'Ambil Foto',
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
            
            // Update slot dengan foto
            const slot = document.getElementById(`slot${currentIndex + 1}`);
            slot.innerHTML = `<img src="${imageData}" alt="Foto ${currentIndex + 1}">`;
            slot.classList.add('filled');
            
            currentIndex++;
            updateProgress();
            
            if (currentIndex < DIRECTIONS.length) {
                setTimeout(() => showInstruction(), 500);
            } else {
                // Selesai
                if (streamWajah) {
                    streamWajah.getTracks().forEach(track => track.stop());
                }
                videoWajah.style.display = 'none';
                isMultiCaptureActive = false;
                
                fotoWajahMultipleInput.value = JSON.stringify(capturedImages);
                resetMultiCaptureBtn.style.display = 'inline-block';
                
                Swal.fire({
                    icon: 'success',
                    title: 'Semua Foto Berhasil!',
                    text: '5 foto wajah telah diambil untuk sistem absensi',
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
            
            // Reset all slots
            for (let i = 1; i <= 5; i++) {
                const slot = document.getElementById(`slot${i}`);
                slot.innerHTML = `<div class="photo-label">${i}. ${DIRECTIONS[i-1].label.split(' ')[2]}</div>`;
                slot.classList.remove('filled');
            }
            
            resetMultiCaptureBtn.style.display = 'none';
            startMultiCaptureBtn.style.display = 'inline-block';
        });

        // ============ FORM VALIDATION ============
        document.getElementById('formSignup').addEventListener('submit', (e) => {
            if (!fotoProfilInput.value) {
                e.preventDefault();
                Swal.fire('Perhatian', 'Silakan ambil foto profil terlebih dahulu!', 'warning');
                return false;
            }
            
            if (!fotoWajahMultipleInput.value) {
                e.preventDefault();
                Swal.fire('Perhatian', 'Silakan ambil 5 foto wajah untuk absensi terlebih dahulu!', 'warning');
                return false;
            }
            
            // Show loading
            Swal.fire({
                title: 'Menyimpan Data...',
                text: 'Mohon tunggu, sistem sedang memproses pendaftaran Anda',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        });
    </script>
</body>
</html>
