<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi - {{ $event->event_name }}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .main-card {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .event-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .event-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 40px;
            color: white;
        }
        
        .event-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .event-detail {
            color: #666;
            font-size: 14px;
        }
        
        /* Modal Overlay */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        
        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 30px;
            max-width: 400px;
            width: 90%;
            position: relative;
            animation: slideDown 0.3s ease;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .modal-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #f0f0f0;
            border: none;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
            z-index: 10001;
            font-size: 20px;
            color: #666;
        }
        
        .modal-close:hover {
            background: #e0e0e0;
            transform: rotate(90deg);
            color: #333;
        }
        
        .modal-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
        
        .modal-subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
            font-size: 14px;
        }
        
        .pin-input {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 10px;
            letter-spacing: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        
        .pin-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .btn-submit-pin {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 15px;
        }
        
        .btn-submit-pin:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-submit-pin:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .loading-spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 0.8s linear infinite;
            display: inline-block;
            margin-right: 10px;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .error-message {
            background: #fee;
            color: #c33;
            padding: 12px;
            border-radius: 8px;
            margin-top: 15px;
            text-align: center;
            font-size: 14px;
            display: none;
        }
        
        .info-box {
            background: #f0f7ff;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
        }
        
        .info-box .title {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        
        .info-box .content {
            color: #666;
            font-size: 14px;
        }
        
        .jamaah-list-container {
            display: none;
        }
        
        .jamaah-card {
            background: white;
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .jamaah-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .jamaah-photo {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #667eea;
        }
        
        .jamaah-photo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        
        .jamaah-info {
            flex: 1;
        }
        
        .jamaah-name {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        
        .jamaah-detail {
            font-size: 13px;
            color: #666;
        }
        
        .search-box {
            margin-bottom: 20px;
        }
        
        .search-input-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 14px;
        }
        
        .search-input:focus {
            outline: none;
            border-color: #667eea;
        }
        
        .btn-search {
            padding: 12px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-search:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="main-card">
        <div class="event-header">
            <div class="event-icon">
                <i class="ti ti-calendar-event"></i>
            </div>
            <div class="event-name">{{ $event->event_name }}</div>
            <div class="event-detail">
                <i class="ti ti-calendar"></i> {{ $event->event_date->format('d F Y') }}<br>
                <i class="ti ti-clock"></i> {{ date('H:i', strtotime($event->event_start_time)) }} - {{ date('H:i', strtotime($event->event_end_time)) }}
            </div>
        </div>

        <div class="info-box">
            <div class="title">
                <i class="ti ti-info-circle"></i> Cara Absensi
            </div>
            <div class="content">
                1. Masukkan PIN Anda pada pop-up yang muncul<br>
                2. Atau tutup pop-up dan pilih nama Anda dari daftar<br>
                3. Lakukan validasi wajah dan lokasi<br>
                4. Selesai! Absensi tercatat
            </div>
        </div>
        
        <!-- Loading Jamaah List -->
        <div class="jamaah-list-container" id="jamaahListContainer">
            <div class="search-box">
                <div class="search-input-group">
                    <input type="text" class="search-input" id="searchJamaah" placeholder="🔍 Cari nama atau NIK jamaah...">
                    <button class="btn-search" id="btnSearch">
                        <i class="ti ti-search"></i> Cari
                    </button>
                </div>
            </div>
            <div id="jamaahCards">
                <div style="text-align: center; padding: 20px; color: #999;">
                    <div class="spinner-border" role="status"></div>
                    <p class="mt-2">Memuat daftar jamaah...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pop-up PIN -->
    <div class="modal-overlay" id="pinModal">
        <div class="modal-content">
            <button class="modal-close" id="btnCloseModal">
                <i class="ti ti-x" style="font-size: 20px;"></i>
            </button>
            
            <div class="modal-title">
                <i class="ti ti-lock"></i> Masuk dengan PIN
            </div>
            <div class="modal-subtitle">
                Masukkan PIN Anda untuk absensi cepat
            </div>
            
            <form id="formPIN">
                <input type="password" 
                       class="pin-input" 
                       id="pinInput" 
                       name="pin" 
                       placeholder="Masukkan PIN" 
                       inputmode="numeric"
                       autocomplete="off"
                       required>
                
                <button type="submit" class="btn-submit-pin" id="btnSubmitPin">
                    <i class="ti ti-check"></i> Masuk
                </button>
                
                <div class="error-message" id="errorMessage"></div>
            </form>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const token = '{{ $token }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        
        $(document).ready(function() {
            // Auto-focus pada input PIN
            $('#pinInput').focus();
            
            // Hanya izinkan angka (unlimited digits)
            $('#pinInput').on('keypress', function(e) {
                const charCode = e.which || e.keyCode;
                // Allow only numbers (0-9)
                if (charCode < 48 || charCode > 57) {
                    e.preventDefault();
                    return false;
                }
            });
            
            // Submit PIN form
            $('#formPIN').on('submit', function(e) {
                e.preventDefault();
                
                const pin = $('#pinInput').val();
                
                if (pin.length < 4) {
                    showError('PIN minimal 4 digit');
                    return;
                }
                
                // Disable button dan tampilkan loading
                const btnSubmit = $('#btnSubmitPin');
                const originalText = btnSubmit.html();
                btnSubmit.prop('disabled', true).html('<span class="loading-spinner"></span> Memverifikasi...');
                
                // Kirim request verifikasi PIN
                $.ajax({
                    url: `/absensi-qr/${token}/verify-pin`,
                    method: 'POST',
                    data: {
                        _token: csrfToken,
                        token: token,
                        pin: pin
                    },
                    success: function(response) {
                        if (response.success) {
                            // Redirect ke halaman absensi jamaah
                            window.location.href = response.redirect_url;
                        } else {
                            showError(response.message || 'PIN tidak valid');
                            btnSubmit.prop('disabled', false).html(originalText);
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }
                        showError(message);
                        btnSubmit.prop('disabled', false).html(originalText);
                        $('#pinInput').val('').focus();
                    }
                });
            });
            
            // Close modal dan tampilkan daftar jamaah
            $('#btnCloseModal').on('click', function() {
                console.log('Close button clicked');
                $('#pinModal').fadeOut(300, function() {
                    $('#jamaahListContainer').fadeIn(300);
                    loadJamaahList();
                });
            });
            
            // Function untuk menampilkan error
            function showError(message) {
                $('#errorMessage').text(message).fadeIn(300);
                setTimeout(function() {
                    $('#errorMessage').fadeOut(300);
                }, 3000);
            }
            
            // Function untuk load daftar jamaah
            function loadJamaahList() {
                console.log('Loading jamaah list...');
                $('#jamaahCards').html('<div style="text-align: center; padding: 20px; color: #999;"><div class="spinner-border" role="status"></div><p class="mt-2">Memuat daftar jamaah...</p></div>');
                
                $.ajax({
                    url: `/absensi-qr/jamaah-list/${token}`,
                    method: 'GET',
                    success: function(html) {
                        console.log('Jamaah list loaded successfully');
                        // Extract jamaah cards from response
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const cards = doc.querySelector('#jamaahContainer');
                        
                        if (cards) {
                            $('#jamaahCards').html(cards.innerHTML);
                        } else {
                            $('#jamaahCards').html('<div style="text-align: center; color: #999; padding: 20px;">Tidak ada data jamaah</div>');
                        }
                    },
                    error: function(xhr) {
                        console.error('Failed to load jamaah list', xhr);
                        $('#jamaahCards').html('<div style="text-align: center; color: #c33; padding: 20px;">Gagal memuat daftar jamaah. Silakan refresh halaman.</div>');
                    }
                });
            }
            
            // Search functionality
            function performSearch() {
                const searchValue = $('#searchJamaah').val().toLowerCase();
                
                $('.jamaah-card').each(function() {
                    const nama = $(this).data('nama') || '';
                    const identitas = $(this).data('identitas') || '';
                    
                    if (nama.indexOf(searchValue) > -1 || identitas.indexOf(searchValue) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
            
            // Keyup event untuk search otomatis
            $(document).on('keyup', '#searchJamaah', function(e) {
                performSearch();
                
                // Trigger search on Enter key
                if (e.keyCode === 13) {
                    performSearch();
                }
            });
            
            // Click event untuk tombol Cari
            $(document).on('click', '#btnSearch', function() {
                performSearch();
            });
        });
    </script>
</body>
</html>
