# 📧 Panduan Lengkap Setup Email untuk Notifikasi Pinjaman

## 🎯 Yang Perlu Anda Lakukan

Anda perlu mendapatkan **App Password** dari Gmail, bukan password email biasa!

---

## 🔐 OPTION 1: Gmail (RECOMMENDED) ⭐

### ✅ **Keuntungan Gmail:**
- Gratis
- Mudah setup
- Reliable
- Limit: 500 email/hari (cukup untuk notifikasi)

### 📋 **Step-by-Step:**

#### **1️⃣ Aktifkan 2-Factor Authentication (2FA)**

**Link:** https://myaccount.google.com/security

**Langkah:**
1. Scroll ke bagian **"2-Step Verification"**
2. Klik **"Get Started"** atau **"Turn On"**
3. Masukkan password email Anda
4. Pilih metode verifikasi (SMS atau Google Authenticator)
5. Masukkan kode verifikasi
6. Klik **"Turn On"**

**Screenshot Guide:**
```
Google Account
├── Security (menu kiri)
    ├── Signing in to Google
        └── 2-Step Verification → [Turn On]
```

---

#### **2️⃣ Generate App Password**

**Link:** https://myaccount.google.com/apppasswords

**Atau navigasi manual:**
```
Google Account
├── Security
    ├── 2-Step Verification (harus sudah aktif)
        └── App passwords (di bagian bawah)
```

**Langkah:**
1. Klik **"App passwords"**
2. Mungkin diminta password lagi → masukkan password email
3. Di halaman App Passwords:
   - **Select app:** Pilih "Mail"
   - **Select device:** Pilih "Windows Computer" (atau Other)
   - Atau ketik nama custom: "Laravel Notifikasi Pinjaman"
4. Klik **"Generate"**
5. **MUNCUL PASSWORD 16 KARAKTER!** 
   - Format: `xxxx xxxx xxxx xxxx`
   - Contoh: `abcd efgh ijkl mnop`
6. **COPY password ini!** (hanya muncul 1x)
7. Klik **"Done"**

⚠️ **PENTING:** Password ini hanya ditampilkan 1x! Jika lupa, buat baru lagi.

---

#### **3️⃣ Update File .env**

Buka file `.env` di project Anda (sudah saya update), ganti bagian ini:

```env
# GANTI INI:
MAIL_USERNAME=[EMAIL_ANDA]@gmail.com
MAIL_PASSWORD=[APP_PASSWORD_DARI_GOOGLE]

# CONTOH NYATA:
MAIL_USERNAME=admin@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
```

**Konfigurasi Lengkap:**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@gmail.com
MAIL_FROM_NAME="Sistem Pinjaman - Bumi Sultan"
```

---

#### **4️⃣ Test Email**

```bash
# Test command notifikasi
php artisan pinjaman:send-jatuh-tempo-notifications --test
```

Jika berhasil, Anda akan lihat:
```
✓ Email jatuh_tempo_besok dikirim ke peminjam@email.com
```

---

## 📧 OPTION 2: Email Provider Lain

### **A. Outlook/Hotmail**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.office365.com
MAIL_PORT=587
MAIL_USERNAME=anda@outlook.com
MAIL_PASSWORD=password_outlook_anda
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=anda@outlook.com
```

**Catatan:** Outlook tidak perlu App Password, bisa pakai password biasa.

---

### **B. Yahoo Mail**

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mail.yahoo.com
MAIL_PORT=587
MAIL_USERNAME=anda@yahoo.com
MAIL_PASSWORD=app_password_yahoo
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=anda@yahoo.com
```

**Generate App Password Yahoo:**
1. https://login.yahoo.com/account/security
2. Scroll ke "App passwords"
3. Generate new password

---

### **C. Email Hosting/cPanel**

Jika Anda punya domain sendiri (contoh: @bumisultan.com):

```env
MAIL_MAILER=smtp
MAIL_HOST=mail.bumisultan.com
MAIL_PORT=587
MAIL_USERNAME=noreply@bumisultan.com
MAIL_PASSWORD=password_cpanel
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@bumisultan.com
```

**Info SMTP bisa didapat dari:**
- cPanel → Email Accounts → Configure Email Client
- Atau tanya hosting provider

---

## 🧪 Cara Testing Konfigurasi

### **1. Test via Command (Recommended)**

```bash
php artisan pinjaman:send-jatuh-tempo-notifications --test
```

### **2. Test via Tinker**

```bash
php artisan tinker
```

Lalu jalankan:
```php
Mail::raw('Test email dari Laravel', function($msg) {
    $msg->to('email_tujuan@gmail.com')
        ->subject('Test Email');
});
```

Jika berhasil: `null` (tidak ada error)  
Jika gagal: akan muncul error message

### **3. Cek Log**

```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 50

# Atau buka manual
notepad storage/logs/laravel.log
```

---

## ❌ Troubleshooting

### **Error: "Username and Password not accepted"**

**Solusi:**
1. Pastikan 2FA sudah aktif
2. Generate App Password baru
3. Copy paste password dengan benar (jangan ketik manual)
4. Hapus spasi dalam password: `abcd efgh ijkl mnop` → `abcdefghijklmnop`

---

### **Error: "Connection could not be established"**

**Solusi:**
1. Cek koneksi internet
2. Cek MAIL_HOST benar: `smtp.gmail.com`
3. Cek MAIL_PORT benar: `587`
4. Cek MAIL_ENCRYPTION: `tls` (bukan `ssl`)

---

### **Error: "SSL operation failed"**

**Solusi:**
Ubah di `.env`:
```env
MAIL_ENCRYPTION=tls
```

Bukan `ssl`.

---

### **Email terkirim tapi masuk SPAM**

**Solusi:**
1. Tambahkan email ke contact list
2. Gunakan domain sendiri (bukan Gmail) untuk lebih profesional
3. Setup SPF dan DKIM record jika pakai domain sendiri

---

### **Email tidak terkirim tapi tidak ada error**

**Solusi:**
1. Cek queue: `SELECT * FROM jobs` (jika pakai queue)
2. Jalankan queue worker: `php artisan queue:work`
3. Cek log: `storage/logs/laravel.log`

---

## 📝 Checklist Setup Email

- [ ] **2-Factor Authentication aktif** di Google Account
- [ ] **App Password sudah di-generate**
- [ ] **App Password sudah di-copy** (16 karakter)
- [ ] **File .env sudah diupdate** dengan email & password
- [ ] **MAIL_HOST = smtp.gmail.com**
- [ ] **MAIL_PORT = 587**
- [ ] **MAIL_ENCRYPTION = tls**
- [ ] **Test command sudah dijalankan**
- [ ] **Email test berhasil masuk inbox**

---

## 🎯 Template .env Lengkap

```env
# ===========================================
# EMAIL CONFIGURATION - GMAIL
# ===========================================

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@gmail.com
MAIL_PASSWORD=abcd efgh ijkl mnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@gmail.com
MAIL_FROM_NAME="Sistem Pinjaman - Bumi Sultan"
```

**Ganti:**
- `admin@gmail.com` → Email Gmail Anda
- `abcd efgh ijkl mnop` → App Password dari Google (16 karakter)

---

## 💡 Tips & Best Practices

1. **Gunakan email khusus** untuk aplikasi (bukan email pribadi)
   - Contoh: `noreply@bumisultan.com` atau `notifikasi@bumisultan.com`

2. **Jangan commit password ke Git**
   - File `.env` sudah ada di `.gitignore`

3. **Backup App Password**
   - Simpan di password manager atau tempat aman

4. **Monitor email bouncing**
   - Cek email yang gagal terkirim
   - Update email yang tidak valid

5. **Gunakan domain sendiri untuk production**
   - Lebih profesional
   - Tidak ada limit ketat seperti Gmail

---

## 📞 Bantuan Lebih Lanjut

### Link Berguna:
- **Gmail App Password:** https://myaccount.google.com/apppasswords
- **Gmail 2FA:** https://myaccount.google.com/security
- **Test SMTP Connection:** https://www.smtper.net/

### Command Berguna:
```bash
# Test notifikasi
php artisan pinjaman:send-jatuh-tempo-notifications --test

# Cek log
Get-Content storage/logs/laravel.log -Tail 50

# Test manual via tinker
php artisan tinker
>>> Mail::raw('Test', fn($m)=>$m->to('test@email.com')->subject('Test'));
```

---

## ✅ Selesai!

Setelah setup selesai, fitur notifikasi email akan:
- ✅ Berjalan otomatis setiap hari jam 08:00
- ✅ Kirim email ke peminjam yang akan jatuh tempo
- ✅ Log semua email yang terkirim
- ✅ Handle error dengan baik

**Good luck! 🚀**
