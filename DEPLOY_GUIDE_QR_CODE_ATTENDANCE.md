# 🚀 DEPLOYMENT GUIDE: QR CODE ATTENDANCE SYSTEM

## 📋 DAFTAR ISI
1. [Requirements](#requirements)
2. [Instalasi](#instalasi)
3. [Migration Database](#migration-database)
4. [Konfigurasi](#konfigurasi)
5. [Testing](#testing)
6. [Troubleshooting](#troubleshooting)

---

## 📦 REQUIREMENTS

### Server Requirements
- PHP >= 8.1
- MySQL >= 5.7
- Laravel 10.x
- Composer
- Extension PHP: GD, BCMath, JSON

### Package Dependencies
```json
{
    "simplesoftwareio/simple-qrcode": "^4.2"
}
```

---

## 🔧 INSTALASI

### Step 1: Install Package QR Code
```bash
composer require simplesoftwareio/simple-qrcode
```

### Step 2: Publish Config (Optional)
```bash
php artisan vendor:publish --provider="SimpleSoftwareIO\QrCode\QrCodeServiceProvider"
```

---

## 💾 MIGRATION DATABASE

### Step 1: Jalankan Migration
```bash
# Jalankan semua migration baru
php artisan migrate
```

Migration akan membuat:
- ✅ `qr_attendance_events` - Tabel event pengajian
- ✅ `qr_attendance_codes` - Tabel QR code dinamis
- ✅ `jamaah_devices` - Tabel device binding
- ✅ `qr_attendance_logs` - Tabel audit log
- ✅ Modifikasi `presensi_yayasan` - Tambah kolom attendance_method, qr_event_id, device_id

### Step 2: Verifikasi Migration
```bash
php artisan migrate:status
```

Pastikan semua migration status: **Ran**

### Step 3: Rollback (Jika Perlu)
```bash
# Rollback migration terakhir
php artisan migrate:rollback

# Rollback specific migration
php artisan migrate:rollback --step=5
```

---

## ⚙️ KONFIGURASI

### 1. Konfigurasi Environment (.env)
```env
# Sudah ada di .env existing, tidak perlu tambah apapun
APP_URL=http://127.0.0.1:8000

# Pastikan session driver sudah diset
SESSION_DRIVER=file
```

### 2. Konfigurasi Storage (Untuk Foto Selfie)
```bash
# Link storage ke public
php artisan storage:link
```

Pastikan folder ini exist:
```
storage/app/public/attendance-selfies/
```

### 3. Permissions Folder
```bash
# Windows PowerShell (run as administrator)
icacls "storage" /grant "IIS_IUSRS:(OI)(CI)F" /T
icacls "bootstrap/cache" /grant "IIS_IUSRS:(OI)(CI)F" /T

# Linux
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

---

## 🧪 TESTING

### 1. Test Database Connection
```bash
php artisan tinker
```

Kemudian test:
```php
// Cek tabel event
DB::table('qr_attendance_events')->count();

// Cek tabel jamaah devices
DB::table('jamaah_devices')->count();

// Cek modifikasi presensi_yayasan
DB::select("SHOW COLUMNS FROM presensi_yayasan LIKE 'attendance_method'");
```

### 2. Test Buat Event (Via Browser)
1. Login sebagai Admin/Super Admin
2. Buka menu **Yayasan Masar** → **QR Code Attendance**
3. Klik **Buat Event Baru**
4. Isi form:
   - Nama Event: "Test Kajian Jumat"
   - Tanggal: Hari ini
   - Jam Mulai: (sesuaikan dengan waktu sekarang)
   - Jam Selesai: (1-2 jam dari sekarang)
   - Latitude: (klik tombol GPS atau manual)
   - Longitude: (klik tombol GPS atau manual)
   - Radius: 100 meter
5. Simpan
6. Klik **Tampilkan QR** (icon QR Code)

### 3. Test Jamaah Scan QR
1. **Dengan HP:**
   - Buka kamera HP
   - Scan QR Code yang muncul di layar
   - Akan redirect ke form login
   - Login dengan No HP & PIN
   - Izinkan akses GPS
   - Submit absensi

2. **Tanpa HP (Testing Manual):**
   - Copy URL dari QR Code
   - Buka di browser
   - Login dengan kredensial jamaah test

### 4. Verifikasi Data
Cek di menu **Monitoring Presensi Yayasan**:
- ✅ Ada badge **QR** berwarna hijau (untuk absensi via QR)
- ✅ Ada badge **FP** berwarna biru (untuk absensi via fingerprint)
- ✅ Kolom Method terisi

---

## 🔍 TROUBLESHOOTING

### Problem 1: Migration Gagal
**Error:** `SQLSTATE[42S01]: Base table or view already exists`

**Solution:**
```bash
# Drop table manual (HATI-HATI!)
php artisan tinker

# Drop one by one
DB::statement('DROP TABLE IF EXISTS qr_attendance_logs');
DB::statement('DROP TABLE IF EXISTS qr_attendance_codes');
DB::statement('DROP TABLE IF EXISTS jamaah_devices');
DB::statement('DROP TABLE IF EXISTS qr_attendance_events');

# Kemudian migrate ulang
php artisan migrate
```

---

### Problem 2: Package SimpleSoftwareIO Error
**Error:** `Class 'SimpleSoftwareIO\QrCode\Facades\QrCode' not found`

**Solution:**
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan route:clear

# Reinstall package
composer remove simplesoftwareio/simple-qrcode
composer require simplesoftwareio/simple-qrcode

# Restart server
php artisan serve
```

---

### Problem 3: QR Code Tidak Muncul
**Error:** QR Code blank atau 404

**Solution:**
1. Cek apakah event **is_active = true**
2. Cek apakah waktu event sedang berlangsung
3. Cek log error:
```bash
tail -f storage/logs/laravel.log
```
4. Test generate manual:
```php
php artisan tinker

use SimpleSoftwareIO\QrCode\Facades\QrCode;
QrCode::format('png')->size(200)->generate('Test QR Code');
```

---

### Problem 4: GPS Tidak Jalan di HP
**Error:** "GPS diblokir" atau "Tidak dapat mengakses lokasi"

**Solution untuk Jamaah:**
1. **Browser Settings:**
   - Chrome: Settings → Privacy → Site Settings → Location → Allow
   - Firefox: Settings → Privacy → Permissions → Location → Allow

2. **HP Settings:**
   - Android: Settings → Apps → Chrome → Permissions → Location → Allow
   - iOS: Settings → Chrome → Location → Allow While Using App

3. **Testing GPS:**
   - Buka https://www.gps-coordinates.net/my-location
   - Pastikan koordinat muncul

**Solution untuk Admin:**
- Gunakan HTTPS (GPS hanya work di HTTPS atau localhost)
- Atau deploy di localhost dengan ngrok/tunneling

---

### Problem 5: Device Binding Error
**Error:** "Akun sudah terdaftar di perangkat lain"

**Solution:**
1. Reset device secara manual:
```sql
-- Via PhpMyAdmin atau Tinker
UPDATE jamaah_devices 
SET is_active = 0 
WHERE kode_yayasan = 'JML001';

-- Atau hapus permanent
DELETE FROM jamaah_devices WHERE kode_yayasan = 'JML001';
```

2. Atau jamaah request reset via form (jika sudah dibuat)

---

### Problem 6: Geofencing Terlalu Ketat
**Error:** "Anda berada di luar area venue" padahal sudah di lokasi

**Solution:**
1. Cek akurasi GPS:
   - GPS HP akurasi ~5-50 meter
   - Indoor GPS lebih tidak akurat
   
2. Perbesar radius:
   - Edit event → Radius: 100m → 200m
   
3. Test koordinat venue:
```php
use App\Services\GeolocationService;

$distance = GeolocationService::calculateDistance(
    -6.208812, 106.845599, // Jamaah GPS
    -6.209012, 106.845799  // Venue GPS
);

echo "Distance: $distance meter";
```

---

### Problem 7: Foto Selfie Tidak Tersimpan
**Error:** Foto tidak muncul atau error 500

**Solution:**
```bash
# Cek permission folder
ls -la storage/app/public/attendance-selfies/

# Buat folder jika belum ada
mkdir -p storage/app/public/attendance-selfies

# Set permission
chmod -R 775 storage/app/public

# Link storage
php artisan storage:link
```

---

## 📊 MONITORING & MAINTENANCE

### 1. Cek Log Absensi
```sql
-- Lihat log absensi hari ini
SELECT 
    l.*, 
    e.event_name, 
    y.nama 
FROM qr_attendance_logs l
LEFT JOIN qr_attendance_events e ON l.event_id = e.id
LEFT JOIN yayasan_masar y ON l.kode_yayasan = y.kode_yayasan
WHERE DATE(l.scan_at) = CURDATE()
ORDER BY l.scan_at DESC;
```

### 2. Cek Failed Attempts
```sql
-- Lihat scan yang gagal
SELECT 
    status,
    COUNT(*) as total,
    failure_reason
FROM qr_attendance_logs
WHERE status != 'success'
GROUP BY status, failure_reason
ORDER BY total DESC;
```

### 3. Bersihkan QR Code Lama
```sql
-- Hapus QR code yang sudah expired > 7 hari
DELETE FROM qr_attendance_codes 
WHERE expired_at < DATE_SUB(NOW(), INTERVAL 7 DAY);
```

### 4. Backup Database
```bash
# Backup specific tables
mysqldump -u root -p bumisultan_db \
    qr_attendance_events \
    qr_attendance_codes \
    qr_attendance_logs \
    jamaah_devices \
    > qr_attendance_backup_$(date +%Y%m%d).sql
```

---

## 🎯 FITUR TAMBAHAN (OPSIONAL)

### 1. Auto-Delete QR Code Expired
Buat scheduled task di `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Hapus QR code expired setiap 1 jam
    $schedule->call(function () {
        DB::table('qr_attendance_codes')
            ->where('expired_at', '<', now())
            ->where('is_active', true)
            ->update(['is_active' => false]);
    })->hourly();
}
```

### 2. Notifikasi WhatsApp Otomatis
Tambah di `QRAttendanceController@submit`:

```php
// Setelah berhasil simpan presensi
use App\Jobs\SendWaMessage;

SendWaMessage::dispatch([
    'to' => $jamaah->no_hp,
    'message' => "Assalamu'alaikum {$jamaah->nama}, absensi Anda untuk event {$event->event_name} telah tercatat. Jazakallah khair! 🤲"
]);
```

### 3. Export Laporan Excel
Install package:
```bash
composer require maatwebsite/excel
```

---

## ✅ CHECKLIST DEPLOYMENT

### Pre-Deployment
- [ ] Backup database
- [ ] Test di local/development
- [ ] Install composer dependencies
- [ ] Set permission folder storage

### Deployment
- [ ] Pull code ke production
- [ ] Run `composer install --no-dev`
- [ ] Run `php artisan migrate`
- [ ] Run `php artisan storage:link`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

### Post-Deployment
- [ ] Test buat event
- [ ] Test generate QR Code
- [ ] Test scan QR Code (mobile)
- [ ] Test GPS & Geofencing
- [ ] Test device binding
- [ ] Verifikasi data di monitoring presensi

---

## 📞 SUPPORT & DOKUMENTASI

### Dokumentasi Lengkap
- [ANALISA_IMPLEMENTASI_QR_CODE_ABSENSI_YAYASAN.md](ANALISA_IMPLEMENTASI_QR_CODE_ABSENSI_YAYASAN.md)

### Package Documentation
- SimpleSoftwareIO QR Code: https://www.simplesoftwareio.com/docs/simple-qrcode

### Kontak
- Jika ada kendala, hubungi tim developer

---

**Status:** ✅ READY FOR PRODUCTION
**Last Updated:** 02 Januari 2026
**Version:** 1.0.0
