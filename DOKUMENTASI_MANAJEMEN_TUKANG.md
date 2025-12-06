# DOKUMENTASI MANAJEMEN TUKANG

## 📋 Ringkasan
Modul **Manajemen Tukang** adalah sistem standalone untuk mengelola data tukang bangunan secara lengkap. Modul ini tidak terintegrasi dengan modul lain dan berfungsi sebagai database mandiri untuk informasi tukang.

## 🎯 Fitur Utama
- ✅ CRUD lengkap (Create, Read, Update, Delete)
- ✅ Upload foto tukang
- ✅ Pencarian dan filter data
- ✅ Detail informasi lengkap
- ✅ Status aktif/non-aktif
- ✅ Manajemen tarif harian
- ✅ Permission-based access control

## 📂 Struktur File

### 1. Database Migration
**File:** `database/migrations/2025_11_10_000001_create_tukangs_table.php`

**Struktur Tabel:**
```sql
- id (primary key)
- kode_tukang (string, unique)
- nama_tukang (string)
- nik (string, nullable)
- alamat (text, nullable)
- no_hp (string, nullable)
- email (string, nullable)
- keahlian (string, nullable)
- status (enum: aktif/nonaktif)
- tarif_harian (decimal, nullable)
- keterangan (text, nullable)
- foto (string, nullable)
- timestamps
```

### 2. Model
**File:** `app/Models/Tukang.php`

**Fitur:**
- Fillable attributes untuk mass assignment
- Cast tarif_harian sebagai decimal
- Timestamps otomatis

### 3. Controller
**File:** `app/Http/Controllers/TukangController.php`

**Method:**
- `index()` - Menampilkan daftar tukang dengan pencarian & filter
- `create()` - Form tambah tukang baru
- `store()` - Menyimpan data tukang baru
- `show()` - Detail data tukang
- `edit()` - Form edit tukang
- `update()` - Update data tukang
- `destroy()` - Hapus data tukang

### 4. Views
**Folder:** `resources/views/manajemen-tukang/data-tukang/`

**File:**
- `index.blade.php` - Halaman daftar tukang
- `create.blade.php` - Form tambah tukang
- `edit.blade.php` - Form edit tukang
- `show.blade.php` - Detail tukang

### 5. Routes
**File:** `routes/web.php`

**Endpoints:**
```
GET    /tukang           - Daftar tukang
GET    /tukang/create    - Form tambah
POST   /tukang           - Simpan data
GET    /tukang/{id}      - Detail
GET    /tukang/{id}/edit - Form edit
PUT    /tukang/{id}      - Update data
DELETE /tukang/{id}      - Hapus data
```

### 6. Sidebar Menu
**File:** `resources/views/layouts/sidebar.blade.php`

**Posisi:** Setelah menu "Manajemen Yayasan"
**Icon:** `ti ti-tool`
**Sub Menu:** Data Tukang

## 🔐 Permissions

**Permission Group:** Manajemen Tukang

**Daftar Permissions:**
- `tukang.index` - Melihat daftar tukang
- `tukang.create` - Menambah tukang baru
- `tukang.show` - Melihat detail tukang
- `tukang.edit` - Edit data tukang
- `tukang.delete` - Hapus data tukang

## 🚀 Cara Instalasi

### Step 1: Jalankan Migration
```bash
php artisan migrate
```

### Step 2: Setup Permissions
```bash
php setup_permissions_tukang.php
```

### Step 3: Clear Cache (Opsional)
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Step 4: Buat Storage Link (Jika Belum)
```bash
php artisan storage:link
```

## 📖 Cara Penggunaan

### 1. Menambah Data Tukang Baru
1. Login sebagai super admin atau user dengan permission `tukang.create`
2. Buka menu **Manajemen Tukang > Data Tukang**
3. Klik tombol **"Tambah Data Tukang"**
4. Isi form dengan lengkap:
   - **Kode Tukang*** (wajib, unique) - Contoh: TK001
   - **Nama Tukang*** (wajib)
   - NIK (opsional)
   - No HP (opsional)
   - Email (opsional)
   - Keahlian (opsional) - Contoh: Tukang Batu, Tukang Cat
   - **Status*** (wajib) - Aktif/Non Aktif
   - Tarif Harian (opsional) - dalam Rupiah
   - Alamat (opsional)
   - Keterangan (opsional)
   - Foto (opsional) - Max 2MB, format: JPG/PNG
5. Klik **"Simpan"**

### 2. Melihat Daftar Tukang
1. Buka menu **Manajemen Tukang > Data Tukang**
2. Gunakan fitur pencarian untuk mencari berdasarkan:
   - Nama tukang
   - Kode tukang
   - Keahlian
   - No HP
3. Filter berdasarkan status (Aktif/Non Aktif)
4. Tabel menampilkan:
   - Foto
   - Kode dan Nama
   - NIK
   - Keahlian
   - No HP
   - Tarif Harian
   - Status
   - Aksi (Detail, Edit, Hapus)

### 3. Melihat Detail Tukang
1. Pada daftar tukang, klik icon mata (👁️) pada kolom aksi
2. Akan menampilkan informasi lengkap:
   - Foto tukang
   - Semua data personal
   - Kontak (WhatsApp link jika ada No HP)
   - Email (mailto link)
   - Tanggal dibuat & diupdate

### 4. Edit Data Tukang
1. Pada daftar tukang, klik icon edit (✏️) pada kolom aksi
2. Ubah data yang diperlukan
3. Upload foto baru jika ingin mengganti (opsional)
4. Klik **"Update"**

### 5. Hapus Data Tukang
1. Pada daftar tukang, klik icon hapus (🗑️) pada kolom aksi
2. Konfirmasi penghapusan
3. Data dan foto akan dihapus permanen

## 💡 Catatan Penting

### Keamanan Data
- ✅ **AMAN:** Modul ini tidak akan menghapus atau memodifikasi data dari modul lain
- ✅ **STANDALONE:** Berjalan independen tanpa ketergantungan pada modul lain
- ✅ **PERMISSION-BASED:** Akses dikontrol menggunakan permission Spatie

### Upload Foto
- Foto disimpan di: `storage/app/public/tukang/`
- Format: JPG, JPEG, PNG
- Maksimal ukuran: 2MB
- Foto lama otomatis dihapus saat update/delete

### Validasi
- Kode tukang harus unique
- Email divalidasi format
- Tarif harian harus numeric
- Status hanya: aktif atau nonaktif

### Pagination
- Daftar tukang menggunakan pagination (10 per halaman)
- Memudahkan navigasi untuk data banyak

## 🎨 Tampilan

### Fitur UI
- ✨ Modern Bootstrap 5 design
- 📱 Responsive untuk semua device
- 🔍 Live preview foto sebelum upload
- 🎯 Icon yang intuitif
- 💬 Notifikasi success/error menggunakan SweetAlert
- 🏷️ Badge untuk status dan keahlian

### Integrasi dengan Sistem
- Menggunakan layout existing (`layouts.app`)
- Mengikuti pattern UI yang sama dengan modul lain
- Icon menggunakan Tabler Icons
- Menggunakan helper function yang sudah ada

## 🛠️ Troubleshooting

### Menu tidak muncul di sidebar
**Solusi:**
1. Pastikan sudah menjalankan `setup_permissions_tukang.php`
2. Pastikan user memiliki permission `tukang.index`
3. Clear cache: `php artisan view:clear`

### Foto tidak tersimpan
**Solusi:**
1. Pastikan storage link sudah dibuat: `php artisan storage:link`
2. Cek permission folder: `chmod -R 775 storage`
3. Cek apakah folder `storage/app/public/tukang` ada

### Error 404 Not Found
**Solusi:**
1. Clear route cache: `php artisan route:clear`
2. Pastikan routes sudah terdaftar: `php artisan route:list | grep tukang`

### Permission denied
**Solusi:**
1. Login sebagai super admin
2. Atau assign permission ke role yang sesuai melalui menu Roles

## 📊 Database

### Query Contoh
```sql
-- Lihat semua tukang aktif
SELECT * FROM tukangs WHERE status = 'aktif';

-- Cari tukang berdasarkan keahlian
SELECT * FROM tukangs WHERE keahlian LIKE '%batu%';

-- Hitung jumlah tukang per status
SELECT status, COUNT(*) as total FROM tukangs GROUP BY status;
```

## 🔄 Update Future (Opsional)

Jika di masa depan ingin menambahkan fitur:
- Riwayat pekerjaan tukang
- Rating/review tukang
- Jadwal ketersediaan
- Absensi tukang
- Pembayaran upah

Dapat membuat tabel baru yang berelasi dengan `tukangs.id`

## ✅ Checklist Implementasi

- [x] Migration tabel tukangs
- [x] Model Tukang
- [x] Controller dengan CRUD lengkap
- [x] Views (index, create, edit, show)
- [x] Routes dengan permission
- [x] Menu di sidebar
- [x] Setup permissions script
- [x] Validasi form
- [x] Upload foto
- [x] Search & filter
- [x] Pagination
- [x] Dokumentasi lengkap

## 📞 Support

Jika ada pertanyaan atau masalah:
1. Cek dokumentasi ini terlebih dahulu
2. Lihat console browser untuk error JavaScript
3. Cek log Laravel: `storage/logs/laravel.log`

---

**Dibuat:** 10 November 2025
**Status:** ✅ Complete & Ready to Use
**Version:** 1.0.0
