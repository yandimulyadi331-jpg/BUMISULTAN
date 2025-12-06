# ✅ Checklist Implementasi Notifikasi Email Pinjaman

## 📋 Checklist untuk Developer

### ✅ Backend Implementation

- [x] **Database Schema**
  - [x] Migration `create_pinjaman_email_notifications_table`
  - [x] Migration `create_pinjaman_notification_settings_table`
  - [x] Migration `add_email_peminjam_to_pinjaman_table`
  - [x] Semua migration berhasil dijalankan

- [x] **Models**
  - [x] Model `PinjamanEmailNotification` dibuat
  - [x] Model `Pinjaman` diupdate (tambah field & relasi)
  - [x] Relasi `emailNotifications()` ditambahkan

- [x] **Mailable & Email Template**
  - [x] Class `PinjamanJatuhTempoMail` dibuat
  - [x] Template `emails/pinjaman/jatuh-tempo.blade.php` dibuat
  - [x] Subject dinamis berdasarkan tipe notifikasi
  - [x] Email template responsive & profesional

- [x] **Command & Logic**
  - [x] Command `SendPinjamanJatuhTempoNotifications` dibuat
  - [x] Logic pengecekan jatuh tempo (H-7, H-3, H-1, H-0, lewat tempo)
  - [x] Anti-duplikasi notifikasi per bulan
  - [x] Error handling & retry mechanism
  - [x] Mode testing dengan flag `--test`

- [x] **Scheduler**
  - [x] Scheduler ditambahkan di `Kernel.php`
  - [x] Dijadwalkan setiap hari jam 08:00 WIB
  - [x] Logging on success & on failure

- [x] **Documentation**
  - [x] Dokumentasi lengkap (500+ baris)
  - [x] Quick start guide
  - [x] Summary implementasi
  - [x] Demo script

---

## 📋 Checklist untuk Admin/User

### 🔧 Konfigurasi Awal

- [ ] **Setup Email SMTP**
  - [ ] Edit file `.env`
  - [ ] Isi `MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`
  - [ ] Isi `MAIL_USERNAME`, `MAIL_PASSWORD`
  - [ ] Isi `MAIL_FROM_ADDRESS`, `MAIL_FROM_NAME`
  - [ ] Test kirim email dengan `php artisan pinjaman:send-jatuh-tempo-notifications --test`

- [ ] **Setup Scheduler**
  - [ ] **Jika Linux/Mac:**
    - [ ] Jalankan `crontab -e`
    - [ ] Tambahkan: `* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1`
  - [ ] **Jika Windows:**
    - [ ] Buka Task Scheduler
    - [ ] Create Basic Task: "Laravel Scheduler"
    - [ ] Trigger: Daily
    - [ ] Action: `php.exe artisan schedule:run`
  - [ ] **Untuk Development:**
    - [ ] Jalankan: `php artisan schedule:work`

- [ ] **Data Preparation**
  - [ ] Isi email untuk karyawan (crew) di tabel `karyawan`
  - [ ] Isi `email_peminjam` untuk pinjaman non-crew
  - [ ] Gunakan script `update_email_peminjam.php` jika perlu
  - [ ] Pastikan field `tanggal_jatuh_tempo_setiap_bulan` terisi untuk semua pinjaman

---

## 🧪 Testing Checklist

- [ ] **Test Command Manual**
  - [ ] Jalankan `php artisan pinjaman:send-jatuh-tempo-notifications --test`
  - [ ] Cek output: apakah pinjaman terdeteksi?
  - [ ] Cek warning: apakah ada pinjaman tanpa email?

- [ ] **Test Scheduler**
  - [ ] Jalankan `php artisan schedule:run`
  - [ ] Jalankan `php artisan schedule:list`
  - [ ] Cek apakah command terdaftar dengan waktu yang benar

- [ ] **Test Email Sending**
  - [ ] Update 1 pinjaman dengan email valid
  - [ ] Set tanggal jatuh tempo = besok
  - [ ] Jalankan command (tanpa `--test`)
  - [ ] Cek inbox email: apakah email masuk?
  - [ ] Cek database `pinjaman_email_notifications`: status = sent?

- [ ] **Test Demo Script**
  - [ ] Jalankan `php demo_notifikasi_pinjaman.php`
  - [ ] Cek output: apakah ada pinjaman yang perlu notifikasi?
  - [ ] Cek statistik notifikasi

---

## 📊 Monitoring Checklist

### Harian

- [ ] **Cek Log Notifikasi**
  ```sql
  SELECT * FROM pinjaman_email_notifications 
  WHERE DATE(created_at) = CURDATE();
  ```

- [ ] **Cek Email Gagal**
  ```sql
  SELECT * FROM pinjaman_email_notifications 
  WHERE status = 'failed' 
  ORDER BY created_at DESC;
  ```

- [ ] **Cek Laravel Log**
  ```bash
  tail -f storage/logs/laravel.log
  ```

### Mingguan

- [ ] **Statistik Notifikasi per Tipe**
  ```sql
  SELECT tipe_notifikasi, 
         COUNT(*) as total,
         SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as berhasil,
         SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as gagal
  FROM pinjaman_email_notifications
  WHERE WEEK(created_at) = WEEK(CURDATE())
  GROUP BY tipe_notifikasi;
  ```

- [ ] **Cek Pinjaman Tanpa Email**
  - [ ] Jalankan script `update_email_peminjam.php`
  - [ ] Update email yang masih kosong

### Bulanan

- [ ] **Review Efektivitas Notifikasi**
  - [ ] Berapa % pinjaman yang menerima notifikasi?
  - [ ] Berapa % email yang berhasil terkirim?
  - [ ] Apakah ada pattern email yang gagal?

- [ ] **Cleanup Data Lama** (optional)
  ```sql
  -- Hapus log notifikasi > 6 bulan
  DELETE FROM pinjaman_email_notifications 
  WHERE created_at < DATE_SUB(NOW(), INTERVAL 6 MONTH);
  ```

---

## 🚨 Troubleshooting Checklist

### Email Tidak Terkirim

- [ ] Cek konfigurasi SMTP di `.env`
- [ ] Cek koneksi ke mail server: `telnet smtp.gmail.com 587`
- [ ] Cek log Laravel: `storage/logs/laravel.log`
- [ ] Cek status notifikasi: `SELECT * FROM pinjaman_email_notifications WHERE status='failed'`
- [ ] Test manual: `php artisan pinjaman:send-jatuh-tempo-notifications`

### Scheduler Tidak Jalan

- [ ] Cek cron job aktif: `crontab -l` (Linux)
- [ ] Cek Task Scheduler aktif (Windows)
- [ ] Test manual: `php artisan schedule:run`
- [ ] Cek scheduler list: `php artisan schedule:list`
- [ ] Cek timezone: `config('app.timezone')`

### Email Duplikat

- [ ] Cek query anti-duplikasi di command
- [ ] Cek index database: `SHOW INDEX FROM pinjaman_email_notifications`
- [ ] Cek apakah scheduler berjalan 2x: `ps aux | grep schedule` (Linux)

### Performance Issue

- [ ] Aktifkan queue: ubah `Mail::to()->send()` jadi `Mail::to()->queue()`
- [ ] Setup queue worker: `php artisan queue:work`
- [ ] Monitor queue: `SELECT * FROM jobs`
- [ ] Tambahkan rate limiting di command

---

## 🎯 Next Steps (Optional Enhancement)

### Short Term

- [ ] **UI Admin Panel**
  - [ ] Halaman setting notifikasi (on/off per tipe)
  - [ ] Halaman log notifikasi dengan filter
  - [ ] Tombol retry untuk email gagal
  - [ ] Preview template email

- [ ] **Notification History di Detail Pinjaman**
  - [ ] Tab "Riwayat Notifikasi"
  - [ ] Menampilkan semua notifikasi yang sudah dikirim
  - [ ] Status & waktu pengiriman

### Mid Term

- [ ] **Dashboard Monitoring**
  - [ ] Chart notifikasi terkirim per hari/minggu/bulan
  - [ ] Alert jika email gagal > threshold
  - [ ] Top 10 pinjaman dengan notifikasi terbanyak

- [ ] **Personalisasi Template**
  - [ ] Custom message per peminjam
  - [ ] Attach PDF slip cicilan
  - [ ] Link pembayaran online

### Long Term

- [ ] **Multi-Channel Notification**
  - [ ] WhatsApp notification
  - [ ] SMS notification
  - [ ] Push notification (mobile app)

- [ ] **AI/Smart Notification**
  - [ ] Prediksi kapan peminjam biasa bayar
  - [ ] Sesuaikan waktu kirim notif per peminjam
  - [ ] Auto reminder jika belum bayar 3 hari setelah JT

---

## 📝 Notes

**Tanggal Setup:** _____________  
**PIC (Person in Charge):** _____________  
**Email Test Berhasil:** ☐ Ya ☐ Tidak  
**Scheduler Aktif:** ☐ Ya ☐ Tidak  
**Semua Pinjaman Punya Email:** ☐ Ya ☐ Tidak  

---

**Status Implementasi:** ✅ COMPLETED  
**Ready for Production:** ✅ YES  
**Last Updated:** 24 November 2025
