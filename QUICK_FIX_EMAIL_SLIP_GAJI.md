# 📧 Quick Fix: Email Slip Gaji Tidak Terkirim

## ⚡ Solusi Cepat (5 Menit)

### Step 1: Akses Tool Diagnostic via Browser
```
https://bumisultan.com/test_email_production.php
```
(Ganti dengan domain Anda)

### Step 2: Cek Konfigurasi Email
Lihat tabel konfigurasi. Pastikan semua value **TIDAK "NOT SET"**

### Step 3: Test Kirim Email
1. Masukkan email Anda di form
2. Klik "Test Kirim Email"
3. Cek inbox/spam Anda

---

## ✅ Jika Test Email Berhasil

Berarti konfigurasi email sudah OK! Coba kirim slip gaji lagi dari aplikasi.

---

## ❌ Jika Test Email Gagal

### Error: "Connection timed out"
**Solusi:** Port SMTP diblok hosting
```bash
# Via SSH/Termius
cd /path/to/project
nano .env
```

Ubah ke port 465:
```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

Lalu:
```bash
php artisan config:clear
```

---

### Error: "Username and Password not accepted"
**Solusi:** Harus pakai App Password Gmail

1. Buka: https://myaccount.google.com/apppasswords
2. Aktifkan 2FA dulu (jika belum)
3. Generate App Password → pilih "Mail" dan "Other device"
4. Copy password (contoh: `abcd efgh ijkl mnop`)
5. Update .env:
```env
MAIL_PASSWORD="abcd efgh ijkl mnop"
```
6. Clear cache:
```bash
php artisan config:clear
```

---

### Error Lainnya
Buka file: **TROUBLESHOOTING_EMAIL_SLIP_GAJI.md** untuk solusi lengkap

---

## 🚀 Deploy ke Hosting (via Termius)

### 1. Connect SSH
```bash
ssh user@your-server.com
```

### 2. Masuk ke Folder Project
```bash
cd /home/bumisultan/public_html
# Atau sesuai path hosting Anda
```

### 3. Pull Update dari Git
```bash
git pull origin main
```

### 4. Upload test_email_production.php
Via SFTP (FileZilla/Termius):
- Upload file `test_email_production.php` ke folder root hosting

### 5. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
```

### 6. Set Permission
```bash
chmod -R 775 storage bootstrap/cache
```

### 7. Cek Konfigurasi Email
Buka browser: `https://your-domain.com/test_email_production.php`

### 8. Fix .env Jika Perlu
```bash
nano .env
```

Pastikan ada:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=manajemenbumisultan@gmail.com
MAIL_PASSWORD="qvnn zogm tvsg hqbl"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=manajemenbumisultan@gmail.com
MAIL_FROM_NAME="Manajemen Bumi Sultan"
```

Save: `Ctrl+X` → `Y` → `Enter`

### 9. Clear Cache Lagi
```bash
php artisan config:clear
```

### 10. Test!
Buka aplikasi → Menu Slip Gaji → Kirim Email

---

## 📊 Cek Log Error

```bash
# Via SSH
tail -f storage/logs/laravel.log
```

Cari baris yang ada kata "email" atau "ERROR"

---

## 💡 Tips

✅ **PENTING:** Jika pakai Gmail, HARUS pakai App Password (bukan password biasa)

✅ **Port:** 587 (TLS) atau 465 (SSL)

✅ **Clear cache** setiap kali ubah .env

✅ **Cek spam folder** saat test email

---

## 🆘 Masih Error?

1. Screenshot error dari `test_email_production.php`
2. Copy log error dari `storage/logs/laravel.log`
3. Hubungi developer dengan info tersebut

---

**Update:** 30 Desember 2025
