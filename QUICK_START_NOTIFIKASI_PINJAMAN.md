# ⚡ Quick Start - Notifikasi Email Pinjaman Jatuh Tempo

## 🚀 Setup Cepat (5 Menit)

### 1️⃣ Jalankan Migration

```bash
php artisan migrate
```

### 2️⃣ Konfigurasi Email (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcompany.com
```

### 3️⃣ Test Command

```bash
# Mode testing (tidak kirim email betulan)
php artisan pinjaman:send-jatuh-tempo-notifications --test
```

### 4️⃣ Setup Scheduler (Pilih salah satu)

**Development:**
```bash
php artisan schedule:work
```

**Production Linux/Mac:**
```bash
crontab -e
# Tambahkan: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Production Windows:**
- Task Scheduler → Create Task
- Program: `php.exe`
- Arguments: `artisan schedule:run`
- Trigger: Daily

---

## ✅ Cek Apakah Berhasil

```bash
# Lihat log
tail -f storage/logs/laravel.log

# Cek database
SELECT * FROM pinjaman_email_notifications ORDER BY created_at DESC LIMIT 10;
```

---

## 📋 Kapan Email Dikirim?

- **7 hari sebelum** jatuh tempo
- **3 hari sebelum** jatuh tempo
- **1 hari sebelum** jatuh tempo (besok)
- **Hari ini** jatuh tempo
- **Sudah lewat** jatuh tempo

Email dikirim otomatis setiap hari jam **08:00 WIB**

---

## 🎯 Yang Perlu Diisi di Form Pinjaman

### Untuk Crew:
- Email akan diambil dari tabel `karyawan.email`

### Untuk Non-Crew:
- Pastikan isi field **Email Peminjam** saat input pinjaman baru

---

## 📖 Dokumentasi Lengkap

Lihat: `DOKUMENTASI_NOTIFIKASI_EMAIL_PINJAMAN.md`

---

## 🐛 Troubleshooting

**Email tidak terkirim?**
- Cek konfigurasi MAIL di `.env`
- Cek log: `storage/logs/laravel.log`
- Test dengan: `php artisan pinjaman:send-jatuh-tempo-notifications --test`

**Scheduler tidak jalan?**
- Pastikan cron job aktif
- Test dengan: `php artisan schedule:run`
- Lihat list: `php artisan schedule:list`

---

**✨ Fitur siap digunakan!**
