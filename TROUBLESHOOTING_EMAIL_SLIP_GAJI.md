# 🔧 Troubleshooting Email Slip Gaji

**Masalah:** Tidak bisa mengirim slip gaji lewat email di aplikasi online

## ✅ Perbaikan yang Sudah Dilakukan

### 1. **Update SlipgajiController.php**
   - ✅ Menambahkan logging lengkap di setiap proses
   - ✅ Menambahkan error handling yang lebih detail
   - ✅ Menampilkan pesan error yang jelas ke user
   - ✅ Generate PDF slip gaji otomatis sebelum kirim email

### 2. **File Test Email Dibuat**
   - ✅ `test_email_production.php` - untuk cek konfigurasi email di hosting

## 🔍 Cara Diagnosa Masalah

### Step 1: Upload File Test ke Hosting

```bash
# Via Termius/SSH
cd /path/to/hosting/public_html
# Upload test_email_production.php ke folder root
```

### Step 2: Akses File Test via Browser

```
https://your-domain.com/test_email_production.php
```

**Yang akan tampil:**
- ✅ Konfigurasi email saat ini (.env)
- ✅ Form test kirim email
- ✅ Checklist troubleshooting
- ✅ Solusi untuk error umum

### Step 3: Cek Konfigurasi di .env (Hosting)

**SSH ke hosting:**

```bash
cd /path/to/your-project
nano .env
```

**Pastikan konfigurasi ini benar:**

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

### Step 4: Clear Cache di Hosting

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### Step 5: Test Kirim Email

Via `test_email_production.php`:
1. Masukkan email Anda
2. Klik "Test Kirim Email"
3. Cek inbox/spam folder

## 🚨 Error Umum & Solusi

### ❌ Error: "Connection timed out"

**Penyebab:**
- Port SMTP 587/465 diblok oleh firewall hosting

**Solusi:**
```bash
# 1. Hubungi provider hosting untuk buka port SMTP
# 2. Atau ganti dengan service email lain (Mailgun, SendGrid)
# 3. Coba port alternatif
```

Update .env:
```env
# Coba port 465 dengan SSL
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

---

### ❌ Error: "Username and Password not accepted"

**Penyebab:**
- Password Gmail salah
- "Less secure app access" tidak aktif (sudah deprecated)
- Belum menggunakan App Password

**Solusi:**

1. **Generate Gmail App Password:**
   - Buka: https://myaccount.google.com/apppasswords
   - Aktifkan 2FA dulu kalau belum
   - Generate App Password (pilih "Mail" dan "Other device")
   - Copy password (format: xxxx xxxx xxxx xxxx)

2. **Update .env dengan App Password:**
```env
MAIL_PASSWORD="abcd efgh ijkl mnop"
```

3. **Clear cache:**
```bash
php artisan config:clear
```

---

### ❌ Error: "Could not instantiate mail function"

**Penyebab:**
- PHP mail() function tidak tersedia di hosting
- PHP extension tidak lengkap

**Solusi:**
```bash
# Cek PHP extension
php -m | grep -i mail

# Cek apakah openssl aktif
php -m | grep -i openssl
```

Jika openssl tidak aktif, aktifkan di `php.ini`:
```ini
extension=openssl
extension=sockets
```

---

### ❌ Error: "SSL certificate problem"

**Penyebab:**
- Sertifikat SSL expired atau tidak valid

**Solusi:**

1. **Update CA certificates di server:**
```bash
# Debian/Ubuntu
sudo apt-get update
sudo apt-get install ca-certificates
sudo update-ca-certificates

# CentOS/RHEL
sudo yum install ca-certificates
sudo update-ca-trust
```

2. **Atau ubah encryption type:**
```env
# Coba ganti dari tls ke ssl
MAIL_ENCRYPTION=ssl
MAIL_PORT=465
```

---

### ❌ Error: "Address in mailbox given does not comply with RFC"

**Penyebab:**
- Format email tidak valid
- Ada spasi atau karakter aneh di email

**Solusi:**

Cek data karyawan:
```sql
SELECT nik, nama_karyawan, email 
FROM karyawan 
WHERE email LIKE '% %' OR email NOT LIKE '%@%.%';
```

Bersihkan email yang tidak valid:
```sql
UPDATE karyawan 
SET email = TRIM(email) 
WHERE email LIKE '% %';
```

---

### ❌ Error: "Swift_TransportException: Failed to authenticate"

**Penyebab:**
- Kredensial SMTP salah
- SMTP server tidak bisa diakses

**Solusi:**

1. **Test koneksi SMTP manual:**
```bash
telnet smtp.gmail.com 587
# Atau
openssl s_client -connect smtp.gmail.com:465 -crlf -ign_eof
```

2. **Gunakan alternative SMTP:**
```env
# Mailgun (gratis 5000 email/bulan)
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=your-domain.com
MAILGUN_SECRET=your-secret-key

# SendGrid (gratis 100 email/hari)
MAIL_MAILER=sendmail
# Atau install sendgrid package
```

---

## 📋 Checklist Lengkap

Sebelum kirim email, pastikan:

- [ ] File .env sudah dikonfigurasi dengan benar
- [ ] App Password Gmail sudah digenerate dan digunakan
- [ ] Port SMTP tidak diblok oleh hosting
- [ ] PHP extension openssl dan sockets aktif
- [ ] Cache sudah di-clear (`php artisan config:clear`)
- [ ] Email karyawan sudah valid (cek di database)
- [ ] Test email berhasil via `test_email_production.php`
- [ ] Log error sudah dicek (`storage/logs/laravel.log`)

## 📝 Cek Log Error

```bash
# Via SSH
cd /path/to/project

# Cek log realtime
tail -f storage/logs/laravel.log

# Cek log error hari ini
grep "ERROR" storage/logs/laravel-$(date +%Y-%m-%d).log

# Cek error email spesifik
grep -i "email\|mail" storage/logs/laravel.log | tail -50
```

## 🔄 Alternatif Jika SMTP Gmail Tidak Bisa

### Option 1: Mailgun (Recommended)

```bash
composer require symfony/mailgun-mailer symfony/http-client
```

Update .env:
```env
MAIL_MAILER=mailgun
MAILGUN_DOMAIN=mg.your-domain.com
MAILGUN_SECRET=your-mailgun-api-key
MAILGUN_ENDPOINT=api.mailgun.net
```

### Option 2: SendGrid

```bash
composer require symfony/sendgrid-mailer symfony/http-client
```

Update .env:
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key
```

### Option 3: Amazon SES

```bash
composer require aws/aws-sdk-php
```

Update .env:
```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=your-access-key
AWS_SECRET_ACCESS_KEY=your-secret-key
AWS_DEFAULT_REGION=us-east-1
```

## 🎯 Step by Step Deploy ke Hosting

### 1. Commit Perubahan (Lokal)

```bash
git add .
git commit -m "Fix email slip gaji dengan logging dan error handling"
git push origin main
```

### 2. Pull di Hosting (Termius/SSH)

```bash
cd /path/to/project
git pull origin main
```

### 3. Upload test_email_production.php

Via Termius SFTP atau FileZilla, upload file ke root folder hosting.

### 4. Clear Cache di Hosting

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 5. Set Permission

```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 6. Test Email

Akses: `https://your-domain.com/test_email_production.php`

### 7. Test Kirim Slip Gaji

Login ke aplikasi → Menu Slip Gaji → Pilih periode → Klik "Kirim Email"

## 📊 Monitoring

### Cek Status Email Queue (Jika pakai queue)

```bash
# Cek jumlah jobs
php artisan queue:work --once

# Monitor queue realtime
php artisan queue:listen
```

### Cek Email yang Berhasil Terkirim

```bash
# Di log file
grep "Email slip gaji berhasil dikirim" storage/logs/laravel.log
```

### Cek Email yang Gagal

```bash
# Di log file
grep "Email slip gaji gagal dikirim" storage/logs/laravel.log
```

## 🆘 Bantuan Lebih Lanjut

Jika masalah masih berlanjut:

1. **Cek log error lengkap:**
   ```bash
   cat storage/logs/laravel.log | tail -100
   ```

2. **Test PHP mail function:**
   ```bash
   php -r "mail('your@email.com', 'Test', 'Test message');"
   ```

3. **Hubungi provider hosting:**
   - Minta buka port SMTP 587/465
   - Minta cek PHP mail configuration
   - Tanya apakah ada firewall yang block outgoing SMTP

4. **Alternatif terakhir:**
   - Gunakan service email third-party (Mailgun/SendGrid/SES)
   - Semua service ini lebih reliable dan punya free tier

## ✅ Hasil Setelah Perbaikan

Setelah perbaikan, sistem akan:
- ✅ Menampilkan error yang jelas jika email gagal terkirim
- ✅ Menyimpan log lengkap di `storage/logs/laravel.log`
- ✅ Generate PDF slip gaji otomatis
- ✅ Menampilkan jumlah email berhasil/gagal
- ✅ Memberikan saran solusi di pesan error

---

**Last Updated:** 30 Desember 2025
**Status:** ✅ Fixed with enhanced logging and error handling
