# 📧 Dokumentasi Fitur Notifikasi Email Pinjaman Jatuh Tempo

## 📋 Overview

Fitur ini memberikan notifikasi email otomatis kepada peminjam ketika tanggal jatuh tempo cicilan pinjaman mereka sudah dekat atau sudah lewat. Sistem akan mengirim email pengingat pada interval waktu tertentu (H-7, H-3, H-1, hari ini, dan sudah lewat jatuh tempo).

---

## ✨ Fitur Utama

### 1. **Notifikasi Multi-Level**
   - **H-7**: Notifikasi 7 hari sebelum jatuh tempo
   - **H-3**: Notifikasi 3 hari sebelum jatuh tempo  
   - **H-1**: Notifikasi 1 hari sebelum jatuh tempo (besok)
   - **H-0**: Notifikasi hari ini jatuh tempo
   - **Lewat Tempo**: Notifikasi untuk cicilan yang sudah lewat jatuh tempo

### 2. **Tracking Notifikasi**
   - Sistem mencatat setiap notifikasi yang dikirim
   - Mencegah pengiriman notifikasi duplikat
   - Menyimpan status (pending, sent, failed)
   - Mencatat retry count untuk email yang gagal

### 3. **Template Email Profesional**
   - Desain responsive dengan Markdown
   - Menampilkan detail pinjaman lengkap
   - Informasi cicilan dan sisa pinjaman
   - Warna dan emoji sesuai tingkat urgensi

### 4. **Scheduler Otomatis**
   - Berjalan otomatis setiap hari jam 8 pagi
   - Menggunakan Laravel Task Scheduler
   - Logging otomatis untuk monitoring

---

## 🗄️ Struktur Database

### Tabel: `pinjaman_email_notifications`

Menyimpan log semua notifikasi email yang dikirim:

```sql
- id: bigint (PK)
- pinjaman_id: bigint (FK ke pinjaman)
- email_tujuan: string
- tipe_notifikasi: enum (jatuh_tempo_hari_ini, jatuh_tempo_besok, jatuh_tempo_3_hari, jatuh_tempo_7_hari, sudah_lewat_jatuh_tempo)
- hari_sebelum_jatuh_tempo: int (nullable)
- tanggal_jatuh_tempo: date
- status: enum (pending, sent, failed)
- sent_at: timestamp (nullable)
- error_message: text (nullable)
- retry_count: int (default: 0)
- created_at, updated_at: timestamps
```

### Tabel: `pinjaman_notification_settings`

Pengaturan global untuk notifikasi:

```sql
- id: bigint (PK)
- notifikasi_7_hari_aktif: boolean (default: true)
- notifikasi_3_hari_aktif: boolean (default: true)
- notifikasi_1_hari_aktif: boolean (default: true)
- notifikasi_hari_ini_aktif: boolean (default: true)
- notifikasi_lewat_tempo_aktif: boolean (default: true)
- jam_kirim: string (default: '08:00')
- email_cc: string (nullable)
- template_tambahan: text (nullable)
- created_at, updated_at: timestamps
```

### Update Tabel: `pinjaman`

Ditambahkan field baru:

```sql
- email_peminjam: string (nullable) - Email untuk peminjam non-crew
```

---

## 🚀 Cara Penggunaan

### 1. **Jalankan Migration**

```bash
php artisan migrate
```

Migration yang akan dijalankan:
- `create_pinjaman_email_notifications_table`
- `create_pinjaman_notification_settings_table`
- `add_email_peminjam_to_pinjaman_table`

### 2. **Konfigurasi Email (jika belum)**

Edit file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

**Untuk Gmail:**
- Aktifkan 2-Factor Authentication
- Generate App Password di Google Account Settings
- Gunakan App Password sebagai MAIL_PASSWORD

### 3. **Testing Command Manual**

```bash
# Mode testing (tidak kirim email betulan)
php artisan pinjaman:send-jatuh-tempo-notifications --test

# Mode production (kirim email betulan)
php artisan pinjaman:send-jatuh-tempo-notifications
```

### 4. **Setup Scheduler (Otomatis)**

Scheduler sudah dikonfigurasi di `app/Console/Kernel.php` untuk berjalan setiap hari jam 8 pagi.

**Linux/Mac (Crontab):**

```bash
crontab -e
```

Tambahkan:
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

**Windows (Task Scheduler):**

1. Buka Task Scheduler
2. Create Basic Task
3. Name: Laravel Scheduler
4. Trigger: Daily, every day
5. Action: Start a program
6. Program: `php.exe`
7. Arguments: `artisan schedule:run`
8. Start in: `C:\path\to\your\project`

**Atau gunakan artisan command untuk development:**

```bash
php artisan schedule:work
```

---

## 📧 Contoh Email yang Dikirim

### Subject Line Berdasarkan Tipe:

- 🔔 **Pinjaman Jatuh Tempo HARI INI**
- ⏰ **Pinjaman Jatuh Tempo BESOK**
- 📅 **Pinjaman Jatuh Tempo 3 Hari Lagi**
- 📋 **Pinjaman Jatuh Tempo 7 Hari Lagi**
- ⚠️ **Pinjaman Sudah Lewat Jatuh Tempo**

### Isi Email:

```
Pengingat Pembayaran Cicilan Pinjaman

HARI INI adalah tanggal jatuh tempo cicilan pinjaman Anda.

Detail Pinjaman
┌─────────────────────┬─────────────────────────┐
│ Nomor Pinjaman      │ PNJ-202511-0001        │
│ Nama Peminjam       │ Ahmad Sudrajat         │
│ Cicilan Per Bulan   │ Rp 1.000.000           │
│ Total Pinjaman      │ Rp 12.000.000          │
│ Total Terbayar      │ Rp 5.000.000           │
│ Sisa Pinjaman       │ Rp 7.000.000           │
│ Tanggal Jatuh Tempo │ 24 November 2025       │
└─────────────────────┴─────────────────────────┘

Cara Pembayaran
Silakan hubungi bagian keuangan untuk melakukan pembayaran.

[Lihat Detail Pinjaman - Button]
```

---

## 🎯 Algoritma Notifikasi

### Logika Penentuan Waktu Kirim:

```php
Hari ini: 24 November
Tanggal jatuh tempo pinjaman: 28 November
Selisih: 4 hari

Tidak ada notifikasi hari ini karena tidak cocok dengan trigger (7, 3, 1, 0)

Hari ini: 27 November
Tanggal jatuh tempo: 28 November
Selisih: 1 hari
✓ Kirim notifikasi "jatuh_tempo_besok"
```

### Anti-Duplikasi:

Sebelum mengirim email, sistem mengecek:
1. Apakah notifikasi dengan tipe sama sudah pernah dikirim bulan ini?
2. Apakah status notifikasi = 'sent'?
3. Jika YA, skip. Jika TIDAK, kirim email baru.

---

## 🔧 Kustomisasi

### 1. **Ubah Jam Pengiriman**

Edit di `app/Console/Kernel.php`:

```php
$schedule->command('pinjaman:send-jatuh-tempo-notifications')
    ->dailyAt('08:00') // Ubah jam di sini
    ->timezone('Asia/Jakarta');
```

### 2. **Tambah Interval Notifikasi Baru**

Edit file `app/Console/Commands/SendPinjamanJatuhTempoNotifications.php`:

```php
// Tambahkan kondisi baru
elseif ($selisihHari == 14) {
    $tipeNotifikasi = 'jatuh_tempo_14_hari';
    $hariSebelum = 14;
}
```

Jangan lupa update enum di migration tabel `pinjaman_email_notifications`.

### 3. **Kustomisasi Template Email**

Edit file `resources/views/emails/pinjaman/jatuh-tempo.blade.php`:

```blade
@component('mail::message')
# Judul Custom Anda

Konten custom...

@endcomponent
```

### 4. **Tambah Email CC/BCC**

Edit `app/Mail/PinjamanJatuhTempoMail.php`:

```php
public function envelope(): Envelope
{
    return new Envelope(
        subject: $subjectMap[$this->tipeNotifikasi] ?? 'Notifikasi Pinjaman',
        cc: ['admin@company.com'],
        bcc: ['backup@company.com'],
    );
}
```

---

## 🐛 Troubleshooting

### Email Tidak Terkirim

**Cek log Laravel:**
```bash
tail -f storage/logs/laravel.log
```

**Cek status notifikasi di database:**
```sql
SELECT * FROM pinjaman_email_notifications 
WHERE status = 'failed' 
ORDER BY created_at DESC;
```

**Ulangi pengiriman email yang gagal:**
```php
// Buat command baru atau jalankan manual
$failedNotifications = DB::table('pinjaman_email_notifications')
    ->where('status', 'failed')
    ->where('retry_count', '<', 3)
    ->get();

foreach ($failedNotifications as $notif) {
    // Retry kirim email
}
```

### Scheduler Tidak Jalan

**Test scheduler secara manual:**
```bash
php artisan schedule:run
```

**Cek list scheduled tasks:**
```bash
php artisan schedule:list
```

**Pastikan cron job aktif (Linux):**
```bash
crontab -l
```

### Email Duplikat Terkirim

Sistem sudah memiliki anti-duplikasi. Jika masih terjadi:

1. Cek apakah ada data corrupt di tabel `pinjaman_email_notifications`
2. Pastikan index database berfungsi dengan baik
3. Cek log untuk memastikan tidak ada race condition

---

## 📊 Monitoring & Reporting

### Query untuk Statistik Notifikasi

**Jumlah email terkirim hari ini:**
```sql
SELECT COUNT(*) 
FROM pinjaman_email_notifications 
WHERE DATE(sent_at) = CURDATE() 
AND status = 'sent';
```

**Email gagal terkirim:**
```sql
SELECT p.nomor_pinjaman, pen.email_tujuan, pen.error_message, pen.retry_count
FROM pinjaman_email_notifications pen
JOIN pinjaman p ON pen.pinjaman_id = p.id
WHERE pen.status = 'failed'
ORDER BY pen.created_at DESC;
```

**Notifikasi per tipe:**
```sql
SELECT tipe_notifikasi, COUNT(*) as total, 
       SUM(CASE WHEN status = 'sent' THEN 1 ELSE 0 END) as berhasil,
       SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as gagal
FROM pinjaman_email_notifications
WHERE MONTH(created_at) = MONTH(CURDATE())
GROUP BY tipe_notifikasi;
```

---

## 🔒 Security & Best Practices

### 1. **Queue untuk Email**
Untuk production, gunakan queue agar tidak blocking:

```php
// Di SendPinjamanJatuhTempoNotifications.php
Mail::to($email)->queue(new PinjamanJatuhTempoMail($pinjaman, $tipeNotifikasi, $hariSebelum));
```

Setup queue worker:
```bash
php artisan queue:work --tries=3
```

### 2. **Rate Limiting**
Batasi jumlah email per menit untuk menghindari spam detection:

```php
// Di command
if ($emailsSentThisMinute >= 50) {
    sleep(60); // Tunggu 1 menit
    $emailsSentThisMinute = 0;
}
```

### 3. **Email Validation**
Pastikan email valid sebelum mengirim:

```php
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Kirim email
}
```

### 4. **GDPR Compliance**
Tambahkan unsubscribe link di email jika diperlukan.

---

## 📱 Integrasi dengan UI (Coming Soon)

Berikut adalah rencana untuk UI admin:

### Halaman Setting Notifikasi

**URL:** `/admin/pinjaman/notification-settings`

**Fitur:**
- Toggle on/off untuk setiap tipe notifikasi
- Ubah jam pengiriman
- Preview template email
- Testing kirim email ke admin
- Statistik notifikasi terkirim

### Halaman Log Notifikasi

**URL:** `/admin/pinjaman/notification-logs`

**Fitur:**
- Daftar semua notifikasi terkirim
- Filter by status, tipe, tanggal
- Retry untuk email yang gagal
- Export to Excel

---

## 📞 Support

Jika ada pertanyaan atau issue:
1. Cek dokumentasi ini terlebih dahulu
2. Cek log di `storage/logs/laravel.log`
3. Hubungi tim development

---

## 🎉 Changelog

**v1.0.0 - 24 November 2025**
- ✅ Fitur notifikasi email multi-level (H-7, H-3, H-1, H-0, lewat tempo)
- ✅ Anti-duplikasi notifikasi
- ✅ Template email profesional
- ✅ Scheduler otomatis
- ✅ Tracking & logging lengkap
- ✅ Support crew & non-crew
- ✅ Command testing mode

---

**Dibuat oleh:** GitHub Copilot  
**Tanggal:** 24 November 2025  
**Versi:** 1.0.0
