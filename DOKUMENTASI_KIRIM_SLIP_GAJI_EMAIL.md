# 📧 Fitur Kirim Slip Gaji via Email

## 📋 Deskripsi
Fitur ini memungkinkan admin untuk mengirim slip gaji karyawan secara otomatis melalui email yang sudah terdaftar di database karyawan.

## ✨ Fitur Utama

### 1. **Kirim Email Massal**
- Mengirim slip gaji ke semua karyawan aktif yang memiliki email terdaftar
- Pilih periode (bulan & tahun) slip gaji yang akan dikirim
- Sistem otomatis menghitung berapa karyawan yang berhasil dan gagal menerima email

### 2. **Email Template Professional**
- Template email menggunakan Laravel Markdown Mail
- Informasi lengkap: NIK, Nama, Jabatan, Departemen, Periode
- Link akses ke aplikasi untuk melihat slip gaji online
- Kontak HRD untuk pertanyaan

### 3. **Validasi Otomatis**
- Hanya mengirim ke karyawan yang memiliki email terdaftar
- Validasi slip gaji sudah dibuat untuk periode tersebut
- Notifikasi hasil pengiriman (berhasil/gagal)

## 🛠️ Cara Penggunaan

### Kirim Slip Gaji Massal

1. **Akses Halaman Slip Gaji**
   - Login sebagai admin/HRD
   - Buka menu "Slip Gaji" dari sidebar
   - Atau akses: `http://127.0.0.1:8000/slipgaji`

2. **Klik Tombol Kirim Email**
   - Klik tombol hijau **"Kirim Email Slip Gaji"**
   - Dialog akan muncul meminta pemilihan periode

3. **Pilih Periode**
   - Pilih **Bulan** dari dropdown
   - Pilih **Tahun** dari dropdown
   - Pastikan periode sudah benar

4. **Konfirmasi Pengiriman**
   - Klik tombol **"Ya, Kirim Email"**
   - Sistem akan memproses pengiriman
   - Tunggu hingga selesai

5. **Hasil Pengiriman**
   - Akan muncul notifikasi berapa email yang berhasil dikirim
   - Jika ada yang gagal, akan ditampilkan jumlahnya

## 📂 File yang Dibuat/Dimodifikasi

### 1. Mail Class
**File:** `app/Mail/SlipGajiMail.php`
```php
// Class untuk mengirim email slip gaji
// Menggunakan Laravel Mailable
// Support attachment PDF (jika ada)
```

### 2. Controller Methods
**File:** `app/Http/Controllers/SlipgajiController.php`

**Method baru:**
- `sendSlipGajiEmail()` - Kirim ke semua karyawan
- `sendSlipGajiEmailSingle()` - Kirim ke satu karyawan (future use)

### 3. Routes
**File:** `routes/web.php`

**Route baru:**
```php
Route::post('/slipgaji/send-email', 'sendSlipGajiEmail')->name('slipgaji.sendEmail');
Route::post('/slipgaji/send-email-single', 'sendSlipGajiEmailSingle')->name('slipgaji.sendEmailSingle');
```

### 4. Email Template
**File:** `resources/views/emails/slipgaji/kirim-slip.blade.php`
- Template email dengan format markdown
- Responsive dan professional
- Informasi lengkap karyawan dan periode

### 5. View Update
**File:** `resources/views/payroll/slipgaji/index.blade.php`
- Tambah tombol "Kirim Email Slip Gaji"
- JavaScript untuk dialog pemilihan periode
- AJAX untuk mengirim request ke server

## ⚙️ Konfigurasi Email

Pastikan konfigurasi email sudah benar di file `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=manajemenbumisultan@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=manajemenbumisultan@gmail.com
MAIL_FROM_NAME="Manajemen Bumi Sultan"
```

## 📊 Proses Kerja Sistem

```
1. Admin klik tombol "Kirim Email Slip Gaji"
   ↓
2. Pilih Bulan & Tahun
   ↓
3. Sistem validasi slip gaji exists
   ↓
4. Ambil data karyawan aktif dengan email
   ↓
5. Loop setiap karyawan:
   - Generate email dengan data karyawan
   - Kirim email via SMTP
   - Hitung berhasil/gagal
   ↓
6. Tampilkan hasil: "Berhasil kirim X email, Y gagal"
```

## 🔒 Keamanan & Validasi

### Validasi Input
- ✅ Bulan & tahun harus diisi
- ✅ Slip gaji periode tersebut harus sudah dibuat
- ✅ Hanya karyawan dengan email valid yang diproses

### Authorization
- ✅ Hanya user dengan permission `slipgaji.index` yang bisa kirim
- ✅ Menggunakan middleware `can()` di route

### Data Privacy
- ✅ Email bersifat rahasia dan personal
- ✅ Tidak ada CC atau BCC ke pihak lain
- ✅ Template mengingatkan sifat kerahasiaan slip gaji

## 📧 Format Email yang Dikirim

**Subject:** `📄 Slip Gaji [Bulan] [Tahun] - [Nama Karyawan]`

**Konten:**
- Salam pembuka personal
- Detail karyawan (NIK, Nama, Jabatan, Departemen)
- Informasi periode gaji
- Tombol akses slip gaji online
- Catatan penting
- Kontak HRD
- Footer professional

## 🎯 Keuntungan Fitur Ini

1. **Efisiensi Waktu** ⏱️
   - Tidak perlu kirim email manual satu per satu
   - Proses otomatis dan cepat

2. **Akurasi Data** ✅
   - Email langsung ke karyawan bersangkutan
   - Data diambil langsung dari database

3. **Tracking** 📊
   - Tahu berapa email yang berhasil dikirim
   - Notifikasi jika ada yang gagal

4. **Professional** 💼
   - Template email menarik dan informatif
   - Branding perusahaan konsisten

5. **Paperless** 🌱
   - Mengurangi penggunaan kertas
   - Ramah lingkungan

## 🚀 Pengembangan Selanjutnya

### Future Features (Opsional)
- [ ] Kirim email ke karyawan tertentu saja (filter)
- [ ] Attach PDF slip gaji otomatis
- [ ] History log pengiriman email
- [ ] Scheduled email (kirim otomatis setiap tanggal tertentu)
- [ ] Email reminder jika slip belum dibuka
- [ ] Export report pengiriman email

## ❓ Troubleshooting

### Email Tidak Terkirim
**Problem:** Email tidak sampai ke karyawan

**Solusi:**
1. Cek konfigurasi SMTP di `.env`
2. Pastikan email karyawan valid dan terdaftar
3. Cek quota pengiriman email Gmail (500 email/hari)
4. Lihat log error di `storage/logs/laravel.log`

### Email Masuk Spam
**Problem:** Email masuk folder spam

**Solusi:**
1. Gunakan email domain sendiri (bukan Gmail)
2. Setup SPF & DKIM record
3. Minta karyawan whitelist email pengirim

### Proses Lambat
**Problem:** Pengiriman email memakan waktu lama

**Solusi:**
1. Gunakan queue Laravel untuk background processing
2. Implementasi job queue dengan Redis/Database
3. Batasi jumlah email per batch

## 📞 Kontak Support

Jika ada kendala teknis:
- **Developer:** Tim IT Bumi Sultan
- **Email:** manajemenbumisultan@gmail.com
- **Telp:** 0857-1537-5490

---

## ✅ Checklist Testing

Sebelum deploy ke production, pastikan:

- [x] Konfigurasi email sudah benar
- [x] Testing kirim email ke 1-2 karyawan dulu
- [x] Validasi template email di berbagai email client (Gmail, Outlook)
- [x] Pastikan tidak ada error di console/log
- [x] Test dengan berbagai periode (bulan/tahun)
- [x] Verifikasi permission access control

---

**Dokumentasi dibuat:** 25 November 2025
**Status:** ✅ **READY TO USE**
**Version:** 1.0.0
