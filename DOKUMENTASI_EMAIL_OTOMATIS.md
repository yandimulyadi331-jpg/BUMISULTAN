# 📧 DOKUMENTASI EMAIL OTOMATIS PINJAMAN

> **Status:** ✅ AKTIF & BERJALAN  
> **Update Terakhir:** 24 November 2025

---

## 📋 RINGKASAN SISTEM

Sistem notifikasi email **OTOMATIS** untuk pengingat jatuh tempo pinjaman. Email dikirim secara otomatis oleh scheduler Laravel tanpa perlu intervensi manual dari admin.

---

## 🎯 FITUR YANG AKTIF

### ✅ Email Otomatis (5 Level)
1. **H-7** - 7 hari sebelum jatuh tempo → 📋 Pemberitahuan awal
2. **H-3** - 3 hari sebelum jatuh tempo → 📅 Pengingat mendesak
3. **H-1** - 1 hari sebelum jatuh tempo → ⏰ Pengingat sangat mendesak
4. **H-0** - Hari jatuh tempo → 🔔 Hari ini jatuh tempo!
5. **OVERDUE** - Setelah jatuh tempo → ⚠️ Pembayaran tertunda

### ✅ Anti Duplikasi
- Satu pinjaman hanya menerima **1 email per tipe per bulan**
- Database mencatat semua email yang sudah terkirim
- Tidak ada spam email berulang

### ✅ Scheduler Laravel
- **Waktu Eksekusi:** Setiap hari jam **08:00 WIB**
- **Timezone:** Asia/Jakarta
- **Command:** `php artisan schedule:work` (sudah berjalan di background)

---

## 📊 STATUS TAMPILAN UI

### Di Halaman Pinjaman

**Kolom "📧 EMAIL"** menampilkan status:

| Status | Badge | Keterangan |
|--------|-------|------------|
| **Terkirim** | ✅ Hijau | Email sudah dikirim (dengan timestamp) |
| **Otomatis** | 🤖 Biru | Email akan dikirim otomatis jam 08:00 |
| **Tidak ada email** | ❌ Abu-abu | Peminjam tidak ada email |

> **Catatan:** Tidak ada tombol "Kirim Manual" karena sistem 100% otomatis

---

## 🔧 KONFIGURASI EMAIL

### Gmail SMTP
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=manajemenbumisultan@gmail.com
MAIL_PASSWORD=qvnn zogm tvsg hqbl
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=manajemenbumisultan@gmail.com
MAIL_FROM_NAME="Manajemen Bumi Sultan"
```

### Email Template
- **Logo:** Bumi Sultan (embedded base64)
- **Branding:** "Bumi Sultan" (bukan "PT Bumi Sultan")
- **Konten:** Detail pinjaman, jumlah, tanggal jatuh tempo, instruksi pembayaran
- **Footer:** Kontak admin, informasi perusahaan

---

## 🗄️ DATABASE

### Tabel: `pinjaman_email_notifications`
Menyimpan log semua email yang terkirim:
- `pinjaman_id` - ID pinjaman
- `tipe_notifikasi` - h-7, h-3, h-1, h-0, overdue
- `email` - Email tujuan
- `status` - sent / failed
- `sent_at` - Waktu pengiriman
- `keterangan` - Catatan tambahan

### Tabel: `pinjaman`
- `email_peminjam` - Email untuk peminjam NON_CREW
- Crew mengambil dari `karyawan.email` (kolom belum ada, perlu migrasi)

---

## 🚀 CARA KERJA

### 1. Scheduler Berjalan (08:00 WIB)
```bash
php artisan schedule:work
```

### 2. Command Dijalankan
```bash
php artisan pinjaman:kirim-notifikasi-jatuh-tempo
```

### 3. Proses Pengiriman
```
1. Cari pinjaman yang belum lunas
2. Hitung selisih hari dengan tanggal jatuh tempo
3. Tentukan tipe notifikasi (H-7, H-3, H-1, H-0, overdue)
4. Cek apakah email tipe ini sudah pernah dikirim bulan ini
5. Jika belum → kirim email
6. Simpan log ke database
```

---

## 📝 LOG & MONITORING

### Cek Status Email Pinjaman
```bash
php cek_status_email_pinjaman.php
```

Output:
- Daftar semua pinjaman
- Status email (Terkirim / Belum)
- Timestamp email terakhir
- Tipe notifikasi

### Cek Log Laravel
```bash
tail storage/logs/laravel.log
```

---

## ⚙️ MAINTENANCE

### Test Manual (Jika Diperlukan)
Jika ingin test kirim email tanpa menunggu scheduler:

```bash
php artisan pinjaman:kirim-notifikasi-jatuh-tempo
```

### Re-enable Manual Send (Jika Diperlukan Nanti)
Jika suatu saat ingin tambah tombol manual lagi:
1. Uncomment route di `routes/web.php`
2. Uncomment method `kirimEmailManual()` di `PinjamanController.php`
3. Tambah tombol di view `pinjaman/index.blade.php`

Tapi untuk sekarang: **SISTEM OTOMATIS SAJA** ✅

---

## 🎯 BENEFITS SISTEM OTOMATIS

✅ **Konsisten** - Email selalu dikirim jam yang sama  
✅ **Efisien** - Admin tidak perlu ingat kirim manual  
✅ **Scalable** - Bisa handle ratusan pinjaman sekaligus  
✅ **Trackable** - Semua email tercatat di database  
✅ **Professional** - Peminjam dapat pengingat tepat waktu  

---

## 🔒 SECURITY & COMPLIANCE

- App Password Gmail (bukan password asli)
- TLS encryption untuk SMTP
- Anti-spam dengan throttling database
- Logo embedded (tidak bergantung external URL)
- Personal data hanya di email tujuan

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek scheduler berjalan: `ps aux | grep schedule:work`
2. Cek log error: `tail storage/logs/laravel.log`
3. Test manual: `php artisan pinjaman:kirim-notifikasi-jatuh-tempo`
4. Cek status: `php cek_status_email_pinjaman.php`

---

**STATUS AKHIR:** ✅ SISTEM EMAIL OTOMATIS AKTIF & BERJALAN  
**Manual Send:** ❌ DIHAPUS (Tidak diperlukan)  
**Next Step:** Monitor log email setiap hari untuk memastikan pengiriman sukses
