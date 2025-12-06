# Dokumentasi Manajemen Pengunjung untuk Karyawan

## Overview
Fitur Manajemen Pengunjung untuk Karyawan memungkinkan karyawan untuk melakukan check-in dan check-out pengunjung di sistem Bumi Sultan. Fitur ini terintegrasi dengan halaman Dashboard Fasilitas Karyawan dengan akses terbatas (hanya check-in dan check-out, tidak bisa edit/hapus).

## Fitur yang Tersedia untuk Karyawan

### 1. **Dashboard Pengunjung**
- **URL**: `/fasilitas/pengunjung-karyawan`
- **Akses**: Karyawan
- **Fitur**:
  - Statistik pengunjung (Total, Check-In, Check-Out, Hari Ini)
  - Tombol Check-In Pengunjung Baru
  - Daftar pengunjung dengan status
  - Filter dan search pengunjung

### 2. **Check-In Pengunjung**
- **Method**: POST
- **URL**: `/fasilitas/pengunjung-karyawan/checkin`
- **Form Input**:
  - Nama Lengkap (Required)
  - Instansi/Perusahaan
  - No. Identitas (KTP/SIM)
  - No. Telepon (Required)
  - Email
  - Cabang
  - Alamat
  - Keperluan (Required)
  - Bertemu Dengan
  - Foto Pengunjung (JPG, JPEG, PNG - Max 2MB)
  - Catatan

**Fitur Otomatis**:
- Generate Kode Pengunjung otomatis (Format: PGJ-YYYYMMDD-XXXX)
- Waktu Check-In otomatis
- Status otomatis "checkin"
- Upload foto ke storage

### 3. **Check-Out Pengunjung**
- **Method**: POST
- **URL**: `/fasilitas/pengunjung-karyawan/{id}/checkout`
- **Proses**:
  - Validasi status pengunjung
  - Update waktu checkout
  - Update status menjadi "checkout"
  - Konfirmasi SweetAlert sebelum checkout

### 4. **Detail Pengunjung**
- **Method**: GET
- **URL**: `/fasilitas/pengunjung-karyawan/{id}/show`
- **Informasi yang Ditampilkan**:
  - Data Diri Lengkap
  - Foto Pengunjung
  - Status (Check-In/Check-Out)
  - Informasi Kunjungan
  - Durasi Kunjungan (jika sudah checkout)
  - Tombol Check-Out (jika masih checkin)

## Perbedaan dengan Akses Admin

| Fitur | Admin | Karyawan |
|-------|-------|----------|
| Check-In Pengunjung | ✅ | ✅ |
| Check-Out Pengunjung | ✅ | ✅ |
| Lihat Detail | ✅ | ✅ |
| Edit Data Pengunjung | ✅ | ❌ |
| Hapus Data Pengunjung | ✅ | ❌ |
| Jadwal Pengunjung | ✅ | ❌ |
| QR Code | ✅ | ❌ |
| Export PDF | ✅ | ❌ |

## Struktur File

### Controller
```
app/Http/Controllers/PengunjungKaryawanController.php
```
- `index()` - Menampilkan daftar pengunjung
- `checkin()` - Proses check-in pengunjung baru
- `checkout($id)` - Proses check-out pengunjung
- `show($id)` - Menampilkan detail pengunjung

### Views
```
resources/views/fasilitas/pengunjung/
├── index-karyawan.blade.php    # Daftar pengunjung (mobile)
└── detail-karyawan.blade.php   # Detail pengunjung (mobile)
```

### Routes
```php
// routes/web.php
Route::controller(PengunjungKaryawanController::class)->group(function () {
    Route::get('/fasilitas/pengunjung-karyawan', 'index')->name('pengunjung.karyawan.index');
    Route::post('/fasilitas/pengunjung-karyawan/checkin', 'checkin')->name('pengunjung.karyawan.checkin');
    Route::post('/fasilitas/pengunjung-karyawan/{id}/checkout', 'checkout')->name('pengunjung.karyawan.checkout');
    Route::get('/fasilitas/pengunjung-karyawan/{id}/show', 'show')->name('pengunjung.karyawan.show');
});
```

## Cara Menggunakan

### Akses Menu Pengunjung
1. Login sebagai karyawan
2. Buka Dashboard → Fasilitas & Asset
3. Klik menu **"Manajemen Pengunjung"**
4. Anda akan melihat dashboard pengunjung dengan statistik

### Check-In Pengunjung Baru
1. Klik tombol **"Check-In Pengunjung Baru"** (tombol hijau dengan icon +)
2. Form check-in akan muncul (fullscreen modal)
3. Isi data pengunjung:
   - Data wajib: Nama Lengkap, No. Telepon, Keperluan
   - Data opsional: Instansi, No. Identitas, Email, Alamat, dll
4. Upload foto pengunjung (opsional)
5. Klik tombol **"Check-In"**
6. Sistem akan generate kode pengunjung otomatis
7. Notifikasi sukses akan muncul dengan kode pengunjung

### Check-Out Pengunjung
1. Pada daftar pengunjung, cari pengunjung dengan status **"Check-In"**
2. Klik tombol **"Check-Out"** (kuning) pada card pengunjung
3. Konfirmasi akan muncul dengan nama pengunjung
4. Klik **"Ya, Check-Out"** untuk konfirmasi
5. Status akan berubah menjadi **"Check-Out"**
6. Waktu checkout dan durasi kunjungan akan tercatat

### Melihat Detail Pengunjung
1. Pada card pengunjung, klik tombol **"Detail"** (biru)
2. Halaman detail akan menampilkan:
   - Foto pengunjung
   - Data diri lengkap
   - Informasi kunjungan
   - Durasi kunjungan (jika sudah checkout)
3. Jika masih check-in, tersedia tombol checkout langsung dari detail
4. Klik **"Kembali ke Daftar"** untuk kembali

## Design Pattern

### Mobile-First Design
- Tampilan responsive untuk mobile device
- Fullscreen modal untuk form input
- Card-based layout untuk daftar pengunjung
- Touch-friendly button sizes

### Color Scheme
- Primary: `#32745e` (Green)
- Secondary: `#58907D` (Light Green)
- Warning: `#ffc107` (Yellow untuk checkout)
- Info: `#17a2b8` (Blue untuk detail)

### Icons
Menggunakan Ionicons:
- `person-add` - Check-in
- `log-out-outline` - Check-out
- `eye-outline` - Detail
- `briefcase-outline` - Instansi
- `call-outline` - Telepon
- `time-outline` - Waktu

## Validasi & Security

### Validasi Check-In
```php
'nama_lengkap' => 'required|string|max:255',
'no_telepon' => 'required|string|max:20',
'keperluan' => 'required|string|max:255',
'email' => 'nullable|email|max:255',
'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
'kode_cabang' => 'nullable|exists:cabang,kode_cabang',
```

### Security Features
- CSRF Protection pada semua form
- File upload validation (type & size)
- SQL Injection protection via Eloquent ORM
- XSS protection via Blade templating

## Notifikasi

### Success Notification
- Toast notification (top-right)
- Auto-hide setelah 3 detik
- Icon success dengan pesan sukses

### Error Notification
- SweetAlert modal dengan icon error
- Menampilkan pesan error detail
- Tombol OK untuk menutup

### Confirmation Dialog
- SweetAlert confirmation sebelum checkout
- Menampilkan nama pengunjung
- Pilihan Ya/Batal dengan reverse buttons

## Database Schema

### Tabel: pengunjung
```sql
- id (PK)
- kode_pengunjung (UNIQUE)
- nama_lengkap
- instansi
- no_identitas
- no_telepon
- email
- alamat
- keperluan
- bertemu_dengan
- foto
- waktu_checkin (DATETIME)
- waktu_checkout (DATETIME, nullable)
- status (enum: checkin, checkout)
- kode_cabang (FK)
- jadwal_pengunjung_id (FK, nullable)
- catatan
- created_at
- updated_at
```

## Model Relationships

### Pengunjung Model
```php
// Relasi ke Cabang
public function cabang() {
    return $this->belongsTo(Cabang::class, 'kode_cabang', 'kode_cabang');
}

// Relasi ke Jadwal Pengunjung
public function jadwalPengunjung() {
    return $this->belongsTo(JadwalPengunjung::class, 'jadwal_pengunjung_id');
}

// Generate Kode Pengunjung
public static function generateKodePengunjung() {
    // Format: PGJ-YYYYMMDD-XXXX
}
```

## API Endpoints (untuk development)

### List Pengunjung
```
GET /fasilitas/pengunjung-karyawan
Response: View dengan data pengunjung
```

### Check-In
```
POST /fasilitas/pengunjung-karyawan/checkin
Body: Form Data (multipart/form-data)
Response: Redirect dengan success message
```

### Check-Out
```
POST /fasilitas/pengunjung-karyawan/{id}/checkout
Response: Redirect dengan success message
```

### Detail
```
GET /fasilitas/pengunjung-karyawan/{id}/show
Response: View dengan detail pengunjung
```

## Troubleshooting

### Foto Tidak Muncul
- Pastikan folder `storage/app/public/pengunjung` sudah ada
- Jalankan: `php artisan storage:link`
- Cek permission folder storage

### Kode Pengunjung Tidak Generate
- Cek method `generateKodePengunjung()` di Model Pengunjung
- Pastikan format tanggal sudah benar
- Reset sequence jika perlu

### Error Checkout
- Pastikan pengunjung memiliki status "checkin"
- Cek apakah ID pengunjung valid
- Lihat log error di `storage/logs/laravel.log`

### Modal Tidak Muncul
- Pastikan Bootstrap JS sudah terload
- Cek console browser untuk error JavaScript
- Pastikan jQuery sudah terload sebelum script custom

## Best Practices

1. **Foto Pengunjung**
   - Compress foto sebelum upload untuk performa
   - Max size 2MB untuk mobile device
   - Format: JPG/JPEG/PNG

2. **Data Entry**
   - Isi data wajib dengan lengkap
   - Validasi no. telepon sebelum submit
   - Gunakan catatan untuk informasi tambahan

3. **Check-Out**
   - Selalu check-out pengunjung saat keluar
   - Konfirmasi nama sebelum checkout
   - Cek durasi kunjungan jika perlu laporan

4. **Performance**
   - Pagination untuk list besar
   - Lazy load untuk foto
   - Cache untuk data statistik

## Update Log

### Version 1.0 (15 November 2025)
- ✅ Fitur Check-In Pengunjung untuk Karyawan
- ✅ Fitur Check-Out Pengunjung untuk Karyawan
- ✅ Halaman Detail Pengunjung (Read-Only)
- ✅ Dashboard dengan Statistik
- ✅ Mobile-First Responsive Design
- ✅ Upload Foto Pengunjung
- ✅ Generate Kode Pengunjung Otomatis
- ✅ Notifikasi & Confirmation Dialog
- ✅ Integrasi ke Dashboard Fasilitas Karyawan

## Kontak Support
Jika ada kendala atau pertanyaan, hubungi tim IT Bumi Sultan.

---
**Dokumentasi Manajemen Pengunjung Karyawan v1.0**  
© 2025 Bumi Sultan Super App
