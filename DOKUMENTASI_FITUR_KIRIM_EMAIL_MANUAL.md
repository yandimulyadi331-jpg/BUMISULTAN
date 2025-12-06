# 📧 DOKUMENTASI FITUR KIRIM EMAIL MANUAL

## 📋 Deskripsi
Fitur untuk mengirim email notifikasi pinjaman jatuh tempo secara **MANUAL** dari halaman daftar pinjaman, dengan status email yang jelas (sudah dikirim/belum).

---

## ✨ Fitur Utama

### 1. **Status Email di Tabel Pinjaman**
✅ Menampilkan status email untuk setiap pinjaman:
- 🟢 **Terkirim**: Email sudah pernah dikirim (tampilkan waktu terakhir)
- 🟡 **Belum**: Email belum pernah dikirim
- ⚫ **Tidak ada**: Tidak ada email peminjam

### 2. **Tombol Kirim Email**
📤 Tombol untuk mengirim email notifikasi secara manual:
- Hanya muncul jika ada email peminjam
- Kirim email dengan 1 klik
- Konfirmasi sebelum mengirim
- Status real-time (loading, success, error)

### 3. **Riwayat Email**
📊 Sistem mencatat setiap email yang dikirim:
- Tanggal & waktu kirim
- Email tujuan
- Tipe notifikasi
- Status (sent/failed)
- Error message (jika gagal)

---

## 🎨 Tampilan UI

### Kolom Email di Tabel
```
┌──────────────────────────┐
│ 📧 Email                │
├──────────────────────────┤
│ ✅ Terkirim             │
│ 2 hari yang lalu         │
│ [📤 Kirim]               │
└──────────────────────────┘

┌──────────────────────────┐
│ 📧 Email                │
├──────────────────────────┤
│ ⏰ Belum                 │
│ [📤 Kirim]               │
└──────────────────────────┘

┌──────────────────────────┐
│ 📧 Email                │
├──────────────────────────┤
│ ❌ Tidak ada             │
└──────────────────────────┘
```

---

## 🔧 Cara Kerja

### 1. **Deteksi Email Peminjam**
```php
// Logika di Blade Template
@php
    $emailTersedia = false;
    $emailTujuan = null;
    
    if ($item->kategori_peminjam === 'crew' && $item->karyawan && $item->karyawan->email) {
        $emailTersedia = true;
        $emailTujuan = $item->karyawan->email;
    } elseif ($item->kategori_peminjam === 'non_crew' && $item->email_peminjam) {
        $emailTersedia = true;
        $emailTujuan = $item->email_peminjam;
    }
@endphp
```

**Prioritas Email:**
- **Crew**: Ambil dari `karyawan.email`
- **Non-Crew**: Ambil dari `pinjaman.email_peminjam`

### 2. **Cek Status Email Terakhir**
```php
$lastEmail = $item->emailNotifications()
    ->where('status', 'sent')
    ->latest('sent_at')
    ->first();
```

### 3. **Tombol Kirim Email (Jika Ada Email)**
```html
<button 
    class="btn btn-sm btn-primary btn-kirim-email" 
    data-pinjaman-id="{{ $item->id }}"
    data-email="{{ $emailTujuan }}">
    <i class="bi bi-send"></i> Kirim
</button>
```

### 4. **AJAX Request ke Server**
```javascript
fetch(`/pinjaman/${pinjamanId}/kirim-email`, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    }
})
```

### 5. **Controller: Kirim Email**
```php
public function kirimEmailManual(Request $request, $id)
{
    // 1. Validasi pinjaman & email
    // 2. Tentukan tipe notifikasi berdasarkan tanggal JT
    // 3. Kirim email via Mail::to()
    // 4. Simpan log ke pinjaman_email_notifications
    // 5. Return JSON response
}
```

---

## 📊 Database Schema

### Tabel: `pinjaman_email_notifications`
```sql
CREATE TABLE pinjaman_email_notifications (
    id BIGINT PRIMARY KEY,
    pinjaman_id BIGINT,
    email_tujuan VARCHAR(255),
    tipe_notifikasi VARCHAR(50),
    tanggal_jatuh_tempo DATE,
    status ENUM('pending','sent','failed'),
    sent_at TIMESTAMP,
    error_message TEXT,
    retry_count INT DEFAULT 0,
    keterangan TEXT
);
```

**Status Email:**
- `sent`: Email berhasil dikirim
- `failed`: Email gagal dikirim
- `pending`: Email dalam antrian (untuk queue)

---

## 🚀 Alur Penggunaan

### 1. **Admin Buka Halaman Pinjaman**
```
GET /pinjaman
```

### 2. **Lihat Status Email**
```
┌─────────────────────────────────────────┐
│ No. Pinjaman │ Nama      │ 📧 Email    │
├─────────────────────────────────────────┤
│ PNJ-001      │ John Doe  │ ✅ Terkirim │
│              │           │ 2 hari lalu │
│              │           │ [📤 Kirim]  │
└─────────────────────────────────────────┘
```

### 3. **Klik Tombol "Kirim"**
```
┌────────────────────────────────────────┐
│ 📧 Kirim Email Notifikasi             │
├────────────────────────────────────────┤
│ Kirim notifikasi email pinjaman        │
│ jatuh tempo?                           │
│                                        │
│ 📧 Email Tujuan:                       │
│ john@example.com                       │
│                                        │
│ [📤 Kirim Sekarang]  [❌ Batal]       │
└────────────────────────────────────────┘
```

### 4. **Loading State**
```
┌────────────────────────────────────────┐
│ 📤 Mengirim Email...                   │
│ Mohon tunggu, sedang mengirim ke       │
│ john@example.com                       │
└────────────────────────────────────────┘
```

### 5. **Success Response**
```
┌────────────────────────────────────────┐
│ ✅ Email Terkirim!                     │
├────────────────────────────────────────┤
│ Email notifikasi berhasil dikirim!     │
│                                        │
│ 📧 Email Tujuan: john@example.com      │
│ 📋 Tipe: jatuh_tempo_hari_ini          │
│                                        │
│ ℹ Penerima akan menerima email         │
│   dalam beberapa menit                 │
│                                        │
│ [✅ OK]                                │
└────────────────────────────────────────┘
```

### 6. **Halaman Reload (Update Status)**
```
Status email berubah menjadi "Terkirim"
dengan timestamp terbaru
```

---

## 🎯 Tipe Notifikasi Email

### Otomatis (Berdasarkan Tanggal JT)
```php
if ($hariSebelum < 0) {
    $tipe = 'lewat_jatuh_tempo';  // ⚠️ Sudah lewat
} elseif ($hariSebelum == 0) {
    $tipe = 'jatuh_tempo_hari_ini'; // 🔔 HARI INI
} elseif ($hariSebelum == 1) {
    $tipe = 'jatuh_tempo_besok';   // ⏰ BESOK
} elseif ($hariSebelum <= 3) {
    $tipe = 'jatuh_tempo_3_hari';  // 📅 H-3
} elseif ($hariSebelum <= 7) {
    $tipe = 'jatuh_tempo_7_hari';  // 📋 H-7
}
```

**Contoh:**
- Jatuh tempo: Tanggal 25
- Hari ini: 25 November → **Jatuh Tempo HARI INI**
- Hari ini: 24 November → **Jatuh Tempo BESOK**
- Hari ini: 22 November → **Jatuh Tempo H-3**
- Hari ini: 26 November → **LEWAT Jatuh Tempo**

---

## 📧 Format Email yang Dikirim

### Subject
```
🔔 Pinjaman Anda Jatuh Tempo HARI INI
⏰ Pinjaman Anda Jatuh Tempo BESOK
📅 Pinjaman Anda Jatuh Tempo 3 Hari Lagi
📋 Pinjaman Anda Jatuh Tempo 7 Hari Lagi
⚠️ Pinjaman Anda SUDAH LEWAT Jatuh Tempo
```

### Isi Email
```
===========================================
Pemberitahuan Jatuh Tempo Cicilan Pinjaman
===========================================

Yth. Bapak/Ibu John Doe,

[Pesan sesuai tipe notifikasi]

Detail Pinjaman:
┌────────────────────────────────────────┐
│ No. Pinjaman      : PNJ-202511-001    │
│ Nama Peminjam     : John Doe          │
│ Cicilan/Bulan     : Rp 1.000.000      │
│ Total Pinjaman    : Rp 12.000.000     │
│ Sudah Dibayar     : Rp 5.000.000      │
│ Sisa Pinjaman     : Rp 7.000.000      │
│ Jatuh Tempo       : Tanggal 25        │
└────────────────────────────────────────┘

[Login ke Sistem] → http://localhost:8000

===========================================
PT Bumi Sultan
📞 0857-1537-5490
📧 manajemenbumisultan@gmail.com
Senin-Jumat, 08:00-17:00 WIB
===========================================
```

---

## ⚠️ Error Handling

### 1. **Email Tidak Tersedia**
```json
{
    "success": false,
    "message": "❌ Email tidak tersedia untuk peminjam ini"
}
```

**Solusi:**
- Update data karyawan (tambah email)
- Update data pinjaman (tambah email_peminjam)

### 2. **Format Email Invalid**
```json
{
    "success": false,
    "message": "❌ Format email tidak valid: invalid-email"
}
```

**Solusi:**
- Perbaiki format email (contoh: user@domain.com)

### 3. **SMTP Error**
```json
{
    "success": false,
    "message": "❌ Gagal mengirim email: Connection refused"
}
```

**Solusi:**
- Cek konfigurasi SMTP di `.env`
- Pastikan Gmail App Password valid
- Cek koneksi internet

### 4. **Model Not Found**
```json
{
    "success": false,
    "message": "❌ Gagal mengirim email: No query results for model"
}
```

**Solusi:**
- Pastikan ID pinjaman valid
- Cek relasi karyawan sudah di-load

---

## 🔍 Monitoring & Log

### 1. **Cek Email Terkirim**
```sql
SELECT * FROM pinjaman_email_notifications
WHERE status = 'sent'
ORDER BY sent_at DESC
LIMIT 10;
```

### 2. **Cek Email Gagal**
```sql
SELECT * FROM pinjaman_email_notifications
WHERE status = 'failed'
ORDER BY created_at DESC;
```

### 3. **Cek Email per Pinjaman**
```sql
SELECT 
    pen.*,
    p.nomor_pinjaman,
    p.nama_peminjam
FROM pinjaman_email_notifications pen
JOIN pinjaman p ON pen.pinjaman_id = p.id
WHERE p.id = 123
ORDER BY pen.sent_at DESC;
```

### 4. **Statistik Email**
```sql
SELECT 
    tipe_notifikasi,
    status,
    COUNT(*) as total,
    DATE(sent_at) as tanggal
FROM pinjaman_email_notifications
WHERE sent_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
GROUP BY tipe_notifikasi, status, DATE(sent_at)
ORDER BY tanggal DESC;
```

---

## 📝 File yang Diubah/Ditambahkan

### 1. **Controller**
```
app/Http/Controllers/PinjamanController.php
```
**Perubahan:**
- ✅ Import `PinjamanEmailNotification` model
- ✅ Import `Mail` facade
- ✅ Import `PinjamanJatuhTempoMail` mailable
- ✅ Update `index()`: Load relasi `emailNotifications`
- ✅ Tambah method `kirimEmailManual($request, $id)`

### 2. **Routes**
```
routes/web.php
```
**Perubahan:**
- ✅ Tambah route: `POST /pinjaman/{pinjaman}/kirim-email`

### 3. **View**
```
resources/views/pinjaman/index.blade.php
```
**Perubahan:**
- ✅ Tambah kolom "📧 Email" di thead
- ✅ Tambah cell status email di tbody
- ✅ Tambah tombol "Kirim" untuk setiap pinjaman
- ✅ Tambah JavaScript AJAX untuk kirim email
- ✅ Tambah SweetAlert2 konfirmasi & notifikasi
- ✅ Update colspan empty state (10 → 12)

### 4. **Model (Existing)**
```
app/Models/Pinjaman.php
```
**Relasi yang sudah ada:**
```php
public function emailNotifications()
{
    return $this->hasMany(PinjamanEmailNotification::class);
}
```

---

## 🎯 Use Cases

### 1. **Reminder Manual untuk Peminjam Tertentu**
**Scenario:**
Admin ingin mengingatkan peminjam yang sering telat bayar.

**Langkah:**
1. Buka halaman `/pinjaman`
2. Cari pinjaman yang dimaksud (filter/search)
3. Klik tombol "📤 Kirim" di kolom Email
4. Konfirmasi pengiriman
5. Email langsung terkirim

### 2. **Test Email Sebelum Deploy**
**Scenario:**
Admin ingin test email notifikasi berfungsi.

**Langkah:**
1. Buat pinjaman dummy dengan email admin
2. Klik "📤 Kirim" untuk test
3. Cek inbox/spam
4. Validasi format & isi email

### 3. **Re-send Email yang Gagal**
**Scenario:**
Email pernah gagal kirim (status: failed).

**Langkah:**
1. Cek pinjaman dengan email failed
2. Perbaiki masalah (koneksi, email format)
3. Klik "📤 Kirim" ulang
4. Email akan kirim ulang & update status

### 4. **Monitoring Email Terkirim**
**Scenario:**
Admin ingin tahu email mana yang sudah/belum terkirim.

**Langkah:**
1. Buka halaman `/pinjaman`
2. Lihat kolom "📧 Email":
   - 🟢 Terkirim: Email sudah dikirim
   - 🟡 Belum: Email belum dikirim
   - ⚫ Tidak ada: Tidak ada email

---

## 🚀 Testing

### 1. **Test dengan Data Real**
```bash
# 1. Buka halaman pinjaman
http://localhost:8000/pinjaman

# 2. Pilih pinjaman yang ada email
# 3. Klik tombol "Kirim"
# 4. Cek email masuk
```

### 2. **Test dengan Data Dummy**
```php
// File: test_kirim_email_ui.php
php test_kirim_email_ui.php
```

### 3. **Test Error Handling**
```php
// 1. Test tanpa email: Hapus email dari karyawan
// 2. Test SMTP error: Matikan koneksi internet
// 3. Test invalid email: Ganti email dengan "invalid-email"
```

---

## 🎨 Customization

### 1. **Ubah Warna Badge**
```css
/* Success (Terkirim) */
.badge.bg-success { background-color: #28a745 !important; }

/* Warning (Belum) */
.badge.bg-warning { background-color: #ffc107 !important; }

/* Secondary (Tidak ada) */
.badge.bg-secondary { background-color: #6c757d !important; }
```

### 2. **Ubah Icon Email**
```html
<!-- Default -->
<i class="bi bi-send"></i>

<!-- Alternatif -->
<i class="bi bi-envelope"></i>
<i class="bi bi-envelope-check"></i>
<i class="bi bi-envelope-paper"></i>
```

### 3. **Ubah Pesan SweetAlert**
```javascript
// File: resources/views/pinjaman/index.blade.php
Swal.fire({
    title: 'Kirim Email?',  // Ubah title
    html: 'Custom message', // Ubah message
    icon: 'question'        // Ubah icon
});
```

---

## 📚 Best Practices

### 1. **Jangan Spam Email**
⚠️ Jangan kirim email terlalu sering ke peminjam yang sama.

**Solusi:**
- Batasi kirim manual max 1x per hari
- Cek timestamp email terakhir sebelum kirim

### 2. **Validasi Email Format**
✅ Pastikan email valid sebelum kirim.

```php
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return error('Email tidak valid');
}
```

### 3. **Log Semua Activity**
📊 Simpan semua email (success & failed) untuk audit.

```php
PinjamanEmailNotification::create([
    'status' => 'sent',
    'keterangan' => 'Dikirim manual oleh admin'
]);
```

### 4. **Handle SMTP Limits**
⚠️ Gmail membatasi 500 email/hari.

**Solusi:**
- Gunakan queue untuk email bulk
- Setup email domain sendiri (unlimited)

---

## 🔐 Security

### 1. **CSRF Protection**
✅ Semua request POST harus include CSRF token.

```javascript
headers: {
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
}
```

### 2. **Role-Based Access**
✅ Hanya super admin yang bisa kirim email.

```php
Route::middleware('role:super admin')
    ->post('/pinjaman/{pinjaman}/kirim-email', 'kirimEmailManual');
```

### 3. **Validate Input**
✅ Validasi pinjaman ID & email format.

```php
$pinjaman = Pinjaman::findOrFail($id); // 404 jika tidak ada
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return error('Invalid email');
}
```

---

## 📞 Support

**Jika ada masalah:**
1. Cek log error: `storage/logs/laravel.log`
2. Cek email log di database: `pinjaman_email_notifications`
3. Test SMTP: `php artisan queue:work --tries=1`
4. Hubungi IT support

---

## 📅 Changelog

### Version 1.0 (24 November 2024)
- ✅ Tambah kolom status email di tabel pinjaman
- ✅ Tambah tombol kirim email manual
- ✅ Tambah AJAX request untuk kirim email
- ✅ Tambah validasi email & error handling
- ✅ Tambah log email ke database
- ✅ Tambah SweetAlert2 untuk UI feedback

---

**🎉 FITUR KIRIM EMAIL MANUAL SIAP DIGUNAKAN!**

Admin sekarang bisa:
- ✅ Lihat status email (terkirim/belum)
- ✅ Kirim email manual dengan 1 klik
- ✅ Monitoring riwayat email
- ✅ Handle error dengan baik
