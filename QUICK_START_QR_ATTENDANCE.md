# 🚀 QUICK START: QR Code Attendance System

## ⚡ Instalasi Cepat (5 Menit)

### 1. Install Package
```bash
composer require simplesoftwareio/simple-qrcode
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Link Storage
```bash
php artisan storage:link
```

### 4. Done! ✅
Buka menu: **Yayasan Masar** → **QR Code Attendance**

---

## 📱 Cara Pakai (Admin)

### Buat Event Baru
1. Klik **Buat Event Baru**
2. Isi form (nama, tanggal, jam, lokasi GPS)
3. Simpan
4. Klik **QR Code** icon
5. Tampilkan QR di layar/TV

### Generate QR Dinamis
QR Code otomatis refresh tiap **2 menit**

---

## 📱 Cara Pakai (Jamaah)

### Absensi via QR Code
1. **Scan QR** yang muncul di layar
2. **Login** dengan No HP + PIN
3. **Izinkan GPS**
4. **Submit** absensi
5. **Selesai** ✅

### Validasi Otomatis
- ✅ QR harus masih aktif (< 2 menit)
- ✅ GPS dalam radius venue (default 100m)
- ✅ Device binding (1 HP per jamaah)
- ✅ Waktu sesuai jadwal event

---

## 🔍 Monitoring

### Lihat Absensi
Menu: **Monitoring Presensi Yayasan**

Badge Method:
- 🟢 **QR** = Absen via QR Code
- 🔵 **FP** = Absen via Fingerprint

---

## ❓ FAQ Cepat

**Q: QR Code expired terlalu cepat?**
A: QR sengaja expire 2 menit untuk keamanan. Auto-refresh otomatis.

**Q: GPS tidak akurat?**
A: Perbesar radius geofencing di edit event (100m → 200m).

**Q: Jamaah ganti HP?**
A: Reset device di database atau hubungi admin.

**Q: Foto selfie wajib?**
A: Tidak wajib. Opsional untuk verifikasi tambahan.

---

## 📚 Dokumentasi Lengkap
- [ANALISA_IMPLEMENTASI_QR_CODE_ABSENSI_YAYASAN.md](ANALISA_IMPLEMENTASI_QR_CODE_ABSENSI_YAYASAN.md)
- [DEPLOY_GUIDE_QR_CODE_ATTENDANCE.md](DEPLOY_GUIDE_QR_CODE_ATTENDANCE.md)

---

**Status:** ✅ PRODUCTION READY  
**Version:** 1.0.0  
**Date:** 02 Januari 2026
