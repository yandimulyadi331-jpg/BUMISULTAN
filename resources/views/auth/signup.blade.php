<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - BUMI SULTAN APP</title>

    <!-- PWA Meta Tags -->
    <meta name="application-name" content="BUMI SULTAN APP">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#696cff">

    <link rel="stylesheet" href="{{ asset('assets/login/css/style.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    
    <style>
        .signup-container {
            max-width: 600px;
            width: 100%;
        }

        .signup-box {
            max-height: 85vh;
            overflow-y: auto;
            padding: 40px 35px;
        }

        .signup-box::-webkit-scrollbar {
            width: 6px;
        }

        .signup-box::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .signup-box::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .signup-title {
            font-size: 2rem;
            margin-bottom: 8px;
        }

        .signup-subtitle {
            font-size: 0.85rem;
            margin-bottom: 25px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            color: #fff;
            font-size: 0.9rem;
            margin-bottom: 8px;
            font-weight: 600;
            text-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
        }

        .form-group label .required {
            color: #ff6b6b;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            height: 45px;
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            border-radius: 12px;
            padding: 0 15px;
            font-size: 0.9rem;
            color: #fff;
            font-weight: 500;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.65);
            font-weight: 400;
        }

        .form-control:focus {
            background: rgba(255, 255, 255, 0.35);
            border-color: rgba(255, 255, 255, 0.7);
        }

        textarea.form-control {
            height: 80px;
            padding: 12px 15px;
            resize: vertical;
        }

        select.form-control {
            cursor: pointer;
            color: #fff;
            font-weight: 500;
        }

        select.form-control option {
            background: rgba(50, 50, 80, 0.95);
            color: #fff;
            padding: 10px;
            font-weight: 500;
        }

        select.form-control option:first-child {
            color: rgba(255, 255, 255, 0.6);
            font-style: italic;
        }

        select.form-control:invalid {
            color: rgba(255, 255, 255, 0.6);
        }

        .camera-section {
            margin-bottom: 20px;
            text-align: center;
        }

        .camera-preview {
            position: relative;
            width: 100%;
            max-width: 300px;
            margin: 0 auto 15px;
            border-radius: 15px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.3);
        }

        #video, #canvas {
            width: 100%;
            display: block;
            border-radius: 15px;
        }

        #canvas {
            display: none;
        }

        .camera-buttons {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-success {
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            color: #fff;
        }

        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(86, 171, 47, 0.4);
        }

        .btn-warning {
            background: linear-gradient(135deg, #f093fb, #f5576c);
            color: #fff;
        }

        .btn-submit {
            width: 100%;
            height: 50px;
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(86, 171, 47, 0.6);
        }

        .alert {
            padding: 12px 15px;
            margin-bottom: 15px;
            border-radius: 10px;
            font-size: 0.85rem;
        }

        .alert-danger {
            background: rgba(255, 107, 107, 0.2);
            border: 1px solid rgba(255, 107, 107, 0.5);
            color: #fff;
        }

        .alert-success {
            background: rgba(86, 171, 47, 0.2);
            border: 1px solid rgba(86, 171, 47, 0.5);
            color: #fff;
        }

        .back-to-login {
            text-align: center;
            margin-top: 15px;
        }

        .back-to-login a {
            color: #a8e063;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .back-to-login a:hover {
            text-decoration: underline;
        }

        .photo-status {
            margin-top: 10px;
            padding: 8px 15px;
            background: rgba(86, 171, 47, 0.2);
            border-radius: 8px;
            color: #a8e063;
            font-size: 0.85rem;
            display: none;
        }

        .photo-status.show {
            display: block;
        }

        .validation-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 10px;
            color: #fff;
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }

        .validation-alert.error {
            background: linear-gradient(135deg, #ff6b6b, #ee5a6f);
            border-left: 4px solid #ff6b6b;
        }

        .validation-alert.warning {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-left: 4px solid #fbbf24;
        }

        .validation-alert.success {
            background: linear-gradient(135deg, #a8e063, #56ab2f);
            border-left: 4px solid #a8e063;
        }

        .validation-alert i {
            margin-right: 8px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }

            .signup-box {
                padding: 30px 25px;
            }

            .signup-title {
                font-size: 1.6rem;
            }
        }
    </style>
</head>

<body>
    <main>
        <div class="login-container signup-container">
            <form id="formSignup" class="login-box signup-box" action="{{ route('signup.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h1 class="login-title signup-title">Sign Up</h1>
                <p class="login-subtitle signup-subtitle">Create your account to join our team</p>

                <!-- Notification Container -->
                <div id="notificationContainer"></div>

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

                <!-- Camera Section -->
                <div class="camera-section">
                    <div class="form-group">
                        <label>Foto Wajah <span class="required">*</span></label>
                        <div class="camera-preview">
                            <video id="video" autoplay></video>
                            <canvas id="canvas"></canvas>
                        </div>
                        <div class="camera-buttons">
                            <button type="button" class="btn btn-primary" id="startCamera">
                                <i class="fas fa-camera"></i> Buka Kamera
                            </button>
                            <button type="button" class="btn btn-success" id="capturePhoto" style="display: none;">
                                <i class="fas fa-camera-retro"></i> Ambil Foto
                            </button>
                            <button type="button" class="btn btn-warning" id="retakePhoto" style="display: none;">
                                <i class="fas fa-redo"></i> Ambil Ulang
                            </button>
                        </div>
                        <div class="photo-status" id="photoStatus">
                            <i class="fas fa-check-circle"></i> Foto berhasil diambil
                        </div>
                        <input type="hidden" name="foto_base64" id="fotoBase64">
                        <input type="file" name="foto" id="fotoInput" style="display: none;" required>
                    </div>
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
                            <option value="" disabled selected>-- Pilih Jenis Kelamin --</option>
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
                            <option value="" disabled selected>-- Pilih Status Perkawinan --</option>
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
                            <option value="" disabled selected>-- Pilih Pendidikan --</option>
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

                <!-- Employment Information -->
                <div class="form-group">
                    <label>Kantor Cabang <span class="required">*</span></label>
                    <select name="kode_cabang" class="form-control" required>
                        <option value="" disabled selected>-- Pilih Kantor Cabang --</option>
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
                            <option value="" disabled selected>-- Pilih Departemen --</option>
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
                            <option value="" disabled selected>-- Pilih Jabatan --</option>
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
                            <option value="" disabled selected>-- Pilih Status Karyawan --</option>
                            <option value="K" {{ old('status_karyawan') == 'K' ? 'selected' : '' }}>Kontrak</option>
                            <option value="T" {{ old('status_karyawan') == 'T' ? 'selected' : '' }}>Tetap</option>
                        </select>
                    </div>
                </div>

                <!-- Password -->
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
    </main>

    <script>
        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const startCameraBtn = document.getElementById('startCamera');
        const capturePhotoBtn = document.getElementById('capturePhoto');
        const retakePhotoBtn = document.getElementById('retakePhoto');
        const photoStatus = document.getElementById('photoStatus');
        const fotoBase64Input = document.getElementById('fotoBase64');
        const fotoInput = document.getElementById('fotoInput');
        const formSignup = document.getElementById('formSignup');
        let stream = null;

        startCameraBtn.addEventListener('click', async () => {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ 
                    video: { 
                        facingMode: 'user',
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    } 
                });
                video.srcObject = stream;
                video.style.display = 'block';
                canvas.style.display = 'none';
                startCameraBtn.style.display = 'none';
                capturePhotoBtn.style.display = 'inline-block';
                retakePhotoBtn.style.display = 'none';
                photoStatus.classList.remove('show');
            } catch (err) {
                alert('Tidak dapat mengakses kamera: ' + err.message);
            }
        });

        capturePhotoBtn.addEventListener('click', () => {
            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            canvas.toBlob((blob) => {
                const file = new File([blob], "photo.jpg", { type: "image/jpeg" });
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(file);
                fotoInput.files = dataTransfer.files;
                
                fotoBase64Input.value = canvas.toDataURL('image/jpeg', 0.8);
                
                if (stream) {
                    stream.getTracks().forEach(track => track.stop());
                }
                
                video.style.display = 'none';
                canvas.style.display = 'block';
                capturePhotoBtn.style.display = 'none';
                retakePhotoBtn.style.display = 'inline-block';
                photoStatus.classList.add('show');
            }, 'image/jpeg', 0.8);
        });

        retakePhotoBtn.addEventListener('click', () => {
            startCameraBtn.click();
        });

        // Fungsi untuk menampilkan notifikasi
        function showNotification(message, type = 'error') {
            const container = document.getElementById('notificationContainer');
            const notification = document.createElement('div');
            notification.className = `validation-alert ${type}`;
            
            let icon = '';
            if (type === 'error') {
                icon = '<i class="fas fa-exclamation-circle"></i>';
            } else if (type === 'warning') {
                icon = '<i class="fas fa-info-circle"></i>';
            } else if (type === 'success') {
                icon = '<i class="fas fa-check-circle"></i>';
            }
            
            notification.innerHTML = icon + message;
            container.appendChild(notification);
            
            // Hapus notifikasi setelah 5 detik
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Validasi form submit
        formSignup.addEventListener('submit', (e) => {
            const fotoFiles = fotoInput.files.length;
            const nikInput = document.querySelector('input[name="no_ktp"]');
            const namaInput = document.querySelector('input[name="nama_karyawan"]');
            const emailInput = document.querySelector('input[name="email"]');
            const passwordInput = document.querySelector('input[name="password"]');
            const passwordConfirm = document.querySelector('input[name="password_confirmation"]');
            
            let errors = [];
            
            // Validasi Foto
            if (!fotoFiles) {
                errors.push('❌ Silakan ambil foto wajah terlebih dahulu!');
            }
            
            // Validasi NIK
            if (!nikInput.value.trim()) {
                errors.push('❌ No. KTP tidak boleh kosong');
            } else if (nikInput.value.length !== 16) {
                errors.push('❌ No. KTP harus 16 digit');
            }
            
            // Validasi Nama
            if (!namaInput.value.trim()) {
                errors.push('❌ Nama lengkap tidak boleh kosong');
            }
            
            // Validasi Email
            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailInput.value.trim()) {
                    errors.push('❌ Email tidak boleh kosong');
                } else if (!emailRegex.test(emailInput.value)) {
                    errors.push('❌ Format email tidak valid');
                }
            }
            
            // Validasi Password
            if (!passwordInput.value) {
                errors.push('❌ Password tidak boleh kosong');
            } else if (passwordInput.value.length < 6) {
                errors.push('❌ Password minimal 6 karakter');
            }
            
            // Validasi Konfirmasi Password
            if (passwordConfirm.value !== passwordInput.value) {
                errors.push('❌ Konfirmasi password tidak sesuai');
            }
            
            // Tampilkan error jika ada
            if (errors.length > 0) {
                e.preventDefault();
                errors.forEach(error => {
                    showNotification(error, 'error');
                });
                return false;
            }
        });

        // Improve select visibility
        document.querySelectorAll('select.form-control').forEach(select => {
            select.addEventListener('change', function() {
                if (this.value !== '') {
                    this.style.color = '#fff';
                    this.style.fontWeight = '600';
                } else {
                    this.style.color = 'rgba(255, 255, 255, 0.6)';
                    this.style.fontWeight = '400';
                }
            });
            
            // Set initial state
            if (select.value === '') {
                select.style.color = 'rgba(255, 255, 255, 0.6)';
                select.style.fontWeight = '400';
            }
        });
    </script>
</body>

</html>
