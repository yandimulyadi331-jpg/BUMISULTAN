# 🚀 Quick Start: Kirim Slip Gaji via Email

## Langkah Cepat (5 Menit)

### 1️⃣ Pastikan Email Sudah Dikonfigurasi
Cek file `.env` sudah ada konfigurasi email:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=manajemenbumisultan@gmail.com
MAIL_PASSWORD=xxxx
```

### 2️⃣ Pastikan Karyawan Punya Email
Di database, tabel `karyawan` harus ada kolom `email` yang terisi.

Cek dengan query:
```sql
SELECT nik, nama_karyawan, email 
FROM karyawan 
WHERE email IS NOT NULL AND email != '';
```

### 3️⃣ Buat Slip Gaji untuk Periode Tertentu
1. Login ke aplikasi
2. Menu **Slip Gaji** → Klik **"Buat Slip Gaji"**
3. Pilih Bulan & Tahun
4. Set Status **Published** (1)
5. Simpan

### 4️⃣ Kirim Email
1. Di halaman Slip Gaji, klik tombol hijau **"Kirim Email Slip Gaji"**
2. Pilih **Bulan** dan **Tahun**
3. Klik **"Ya, Kirim Email"**
4. Tunggu proses selesai ✅

### 5️⃣ Verifikasi
Cek email karyawan untuk memastikan slip gaji sudah diterima.

---

## 📸 Screenshot Tombol

Di halaman `/slipgaji`, akan ada tombol baru:

```
┌────────────────────────────────────────┐
│ [+ Buat Slip Gaji] [📧 Kirim Email]   │
└────────────────────────────────────────┘
```

---

## ⚠️ Catatan Penting

- **Email karyawan harus valid** - Sistem hanya kirim ke karyawan yang punya email
- **Slip gaji harus sudah dibuat** - Periode harus ada di database
- **Limit Gmail: 500 email/hari** - Jika karyawan banyak, pertimbangkan pakai SMTP dedicated

---

## 🎯 Contoh Penggunaan

**Scenario:** Kirim slip gaji November 2025 ke semua karyawan

1. Pastikan slip gaji November 2025 sudah dibuat
2. Klik tombol "Kirim Email Slip Gaji"
3. Pilih:
   - Bulan: **November**
   - Tahun: **2025**
4. Konfirmasi
5. Sistem akan kirim ke semua karyawan aktif dengan email

**Hasil:**
```
✅ Berhasil mengirim slip gaji ke 45 karyawan
```

---

## 🆘 Jika Ada Masalah

### Email tidak terkirim?
```bash
# Cek log Laravel
tail -f storage/logs/laravel.log
```

### Test kirim email manual
Gunakan Laravel Tinker:
```bash
php artisan tinker

# Test kirim email
Mail::raw('Test email', function($message) {
    $message->to('test@example.com')->subject('Test');
});
```

---

**Ready to use!** 🎉
