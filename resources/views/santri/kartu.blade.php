<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>ID Card Santri - {{ $santri->nama_lengkap }}</title>
    <style>
        body {
            background: #dff9fb;
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .idcard-container {
            width: 400px;
            min-height: 520px;
            margin: 20px auto;
            background: #fff;
            border-radius: 28px;
            box-shadow: 0 8px 32px 0 rgba(16, 185, 129, 0.13);
            overflow: hidden;
            position: relative;
            border: 1.5px solid #e0e7ef;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .idcard-header-modern {
            background: linear-gradient(120deg, #10b981 80%, #34d399 100%);
            height: 120px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding-left: 36px;
        }

        .profile-pic-modern {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #fff;
            box-shadow: 0 2px 16px rgba(16, 185, 129, 0.13);
            position: absolute;
            top: 80px;
            left: 36px;
            background: #fff;
        }

        .company-logo-modern {
            position: absolute;
            top: 24px;
            right: 32px;
            width: 60px;
            height: 60px;
            opacity: 0.95;
            border-radius: 50%;
            background: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            color: #10b981;
            border: 3px solid rgba(255, 255, 255, 0.5);
        }

        .idcard-body-modern {
            padding: 110px 28px 18px 28px;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .idcard-name-modern {
            font-size: 1.5rem;
            font-weight: 700;
            color: #10b981;
            letter-spacing: 1px;
            margin-bottom: 2px;
        }

        .idcard-position-modern {
            font-size: 1.08rem;
            color: #34d399;
            font-weight: 500;
        }

        .idcard-info-modern {
            display: flex;
            align-items: center;
            font-size: 1.01rem;
            color: #444;
            margin-bottom: 10px;
        }

        .idcard-info-modern i {
            font-size: 1.13rem;
            color: #10b981;
            margin-right: 10px;
            width: 22px;
            text-align: center;
        }

        .barcode-modern {
            margin: 32px 0 16px 0;
            text-align: center;
        }

        .barcode-svg {
            height: 80px;
            width: 100%;
            max-width: 300px;
        }

        .idcard-footer-modern {
            text-align: center;
            font-size: 1.01rem;
            color: #10b981;
            font-weight: 500;
            margin-bottom: 18px;
            letter-spacing: 0.5px;
        }

        .company-name-modern {
            position: absolute;
            left: 36px;
            top: 30px;
            color: #fff;
            font-size: 1.08rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
            max-width: 60%;
        }

        .status-badge {
            display: inline-block;
            background: #10b981;
            color: white;
            padding: 6px 16px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-top: 8px;
        }

        @media print {
            body {
                background: white;
            }
            .idcard-container {
                box-shadow: none;
                border: 1px solid #ddd;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
</head>
<body>
    <div class="idcard-container">
        <div class="idcard-header-modern">
            <div class="company-logo-modern">SS</div>
            <div class="company-name-modern">
                SAUNG SANTRI
            </div>
        </div>

        @if($santri->foto)
            <img src="{{ public_path('storage/santri/'.$santri->foto) }}" class="profile-pic-modern" alt="Foto Santri">
        @else
            <img src="https://ui-avatars.com/api/?name={{ urlencode($santri->nama_lengkap) }}&size=200&background=10b981&color=fff" class="profile-pic-modern" alt="Foto Santri">
        @endif

        <div class="idcard-body-modern">
            <div class="idcard-name-modern">{{ strtoupper($santri->nama_lengkap) }}</div>
            <div class="idcard-position-modern">Santri Tahfidz Al-Qur'an</div>

            <div class="idcard-info-modern">
                <i class="fa-solid fa-id-badge"></i> NIS: {{ $santri->nis }}
            </div>
            <div class="idcard-info-modern">
                <i class="fa-solid fa-calendar-plus"></i> Tahun Masuk: {{ $santri->tahun_masuk ?? '-' }}
            </div>
            <div class="idcard-info-modern">
                <i class="fa-solid fa-book-quran"></i> Hafalan: {{ $santri->jumlah_juz_hafalan ?? 0 }} Juz
            </div>
            <div class="idcard-info-modern">
                <i class="fa-solid fa-home"></i> {{ $santri->nama_asrama ?? 'Belum Ada Asrama' }}
            </div>

            <div style="text-align: center;">
                <span class="status-badge">
                    {{ $santri->status_santri == 'aktif' ? 'SANTRI AKTIF' : strtoupper($santri->status_santri) }}
                </span>
            </div>

            <div class="barcode-modern">
                <svg class="barcode-svg" viewBox="0 0 200 80" xmlns="http://www.w3.org/2000/svg">
                    <!-- Barcode Pattern -->
                    <rect x="10" y="10" width="3" height="50" fill="#000"/>
                    <rect x="15" y="10" width="2" height="50" fill="#000"/>
                    <rect x="20" y="10" width="4" height="50" fill="#000"/>
                    <rect x="26" y="10" width="2" height="50" fill="#000"/>
                    <rect x="30" y="10" width="3" height="50" fill="#000"/>
                    <rect x="35" y="10" width="2" height="50" fill="#000"/>
                    <rect x="39" y="10" width="4" height="50" fill="#000"/>
                    <rect x="45" y="10" width="3" height="50" fill="#000"/>
                    <rect x="50" y="10" width="2" height="50" fill="#000"/>
                    <rect x="54" y="10" width="4" height="50" fill="#000"/>
                    <rect x="60" y="10" width="2" height="50" fill="#000"/>
                    <rect x="64" y="10" width="3" height="50" fill="#000"/>
                    <rect x="69" y="10" width="2" height="50" fill="#000"/>
                    <rect x="73" y="10" width="4" height="50" fill="#000"/>
                    <rect x="79" y="10" width="3" height="50" fill="#000"/>
                    <rect x="84" y="10" width="2" height="50" fill="#000"/>
                    <rect x="88" y="10" width="4" height="50" fill="#000"/>
                    <rect x="94" y="10" width="2" height="50" fill="#000"/>
                    <rect x="98" y="10" width="3" height="50" fill="#000"/>
                    <rect x="103" y="10" width="2" height="50" fill="#000"/>
                    <rect x="107" y="10" width="4" height="50" fill="#000"/>
                    <rect x="113" y="10" width="3" height="50" fill="#000"/>
                    <rect x="118" y="10" width="2" height="50" fill="#000"/>
                    <rect x="122" y="10" width="4" height="50" fill="#000"/>
                    <rect x="128" y="10" width="2" height="50" fill="#000"/>
                    <rect x="132" y="10" width="3" height="50" fill="#000"/>
                    <rect x="137" y="10" width="2" height="50" fill="#000"/>
                    <rect x="141" y="10" width="4" height="50" fill="#000"/>
                    <rect x="147" y="10" width="3" height="50" fill="#000"/>
                    <rect x="152" y="10" width="2" height="50" fill="#000"/>
                    <rect x="156" y="10" width="4" height="50" fill="#000"/>
                    <rect x="162" y="10" width="2" height="50" fill="#000"/>
                    <rect x="166" y="10" width="3" height="50" fill="#000"/>
                    <rect x="171" y="10" width="2" height="50" fill="#000"/>
                    <rect x="175" y="10" width="4" height="50" fill="#000"/>
                    <rect x="181" y="10" width="3" height="50" fill="#000"/>
                    <rect x="186" y="10" width="2" height="50" fill="#000"/>
                    <!-- Barcode Number -->
                    <text x="100" y="72" font-size="10" text-anchor="middle" fill="#000">{{ $santri->nis }}</text>
                </svg>
            </div>
        </div>
        <div class="idcard-footer-modern">
            SAUNG SANTRI - Pondok Pesantren Tahfidz Al-Qur'an
        </div>
    </div>
</body>
</html>
