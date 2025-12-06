# ⚡ QUICK GUIDE: Dapatkan App Password Gmail (5 Menit)

## 🎯 3 Langkah Mudah

### **1️⃣ Aktifkan 2-Factor Authentication**
```
🌐 Buka: https://myaccount.google.com/security
↓
📱 Cari: "2-Step Verification"
↓
🔘 Klik: "Turn On"
↓
📞 Verifikasi dengan SMS/Authenticator
↓
✅ 2FA Aktif!
```

### **2️⃣ Generate App Password**
```
🌐 Buka: https://myaccount.google.com/apppasswords
↓
📧 Select app: "Mail"
↓
💻 Select device: "Windows Computer"
↓
🔘 Klik: "Generate"
↓
📋 COPY password (16 karakter: xxxx xxxx xxxx xxxx)
↓
✅ App Password Didapat!
```

### **3️⃣ Update .env**
```php
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=admin@gmail.com          // ← GANTI dengan email Anda
MAIL_PASSWORD=abcd efgh ijkl mnop      // ← GANTI dengan App Password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=admin@gmail.com      // ← GANTI dengan email Anda
```

---

## ✅ Test Email

```bash
php artisan pinjaman:send-jatuh-tempo-notifications --test
```

Jika berhasil: `✓ Email ... dikirim`  
Jika gagal: Cek `PANDUAN_SETUP_EMAIL_LENGKAP.md`

---

## ⚠️ PENTING!

- ✅ Password adalah **App Password** (16 karakter), BUKAN password email biasa
- ✅ 2-Factor Authentication **HARUS** aktif dulu
- ✅ App Password hanya muncul 1x, simpan baik-baik!
- ✅ Bisa hapus spasi: `abcd efgh ijkl mnop` → `abcdefghijklmnop`

---

## 🔗 Links:

- **Generate App Password:** https://myaccount.google.com/apppasswords
- **Panduan Lengkap:** `PANDUAN_SETUP_EMAIL_LENGKAP.md`
- **Troubleshooting:** `PANDUAN_SETUP_EMAIL_LENGKAP.md` (bagian Troubleshooting)

---

**Done! Sekarang email notifikasi sudah bisa jalan! 🚀**
