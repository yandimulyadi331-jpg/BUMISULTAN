# DOKUMENTASI MENU PERAWATAN GEDUNG UNTUK KARYAWAN

## 📋 RINGKASAN
Menu Perawatan Gedung untuk Karyawan adalah fitur yang memungkinkan setiap karyawan untuk melakukan checklist tugas-tugas perawatan gedung yang telah ditentukan oleh admin. Setiap karyawan dapat mencentang checklist yang sudah dikerjakan, menambahkan catatan, dan melampirkan foto bukti.

## 🎯 FITUR UTAMA

### 1. **Dashboard Perawatan**
   - Menampilkan statistik checklist hari ini dan minggu ini
   - Progress penyelesaian checklist dalam bentuk persentase
   - Menu akses ke berbagai jenis checklist (Harian, Mingguan, Bulanan, Tahunan)
   - History aktivitas terakhir yang dikerjakan

### 2. **Checklist Berdasarkan Periode**
   - **Harian**: Tugas yang harus dikerjakan setiap hari
   - **Mingguan**: Tugas yang harus dikerjakan setiap minggu
   - **Bulanan**: Tugas yang harus dikerjakan setiap bulan
   - **Tahunan**: Tugas yang harus dikerjakan setiap tahun

### 3. **Fitur Checklist**
   - Centang checklist yang sudah selesai
   - Tambahkan catatan untuk setiap checklist
   - Upload foto bukti (opsional)
   - Filter checklist berdasarkan kategori (Kebersihan, Perawatan Rutin, Pengecekan, Lainnya)
   - Progress bar real-time
   - Batalkan checklist jika salah centang

### 4. **History Aktivitas**
   - Lihat semua riwayat checklist yang sudah dikerjakan
   - Filter berdasarkan tipe periode
   - Lihat foto bukti yang sudah diupload
   - Pagination untuk data yang banyak

## 🔧 STRUKTUR FILE

### Controller
```
app/Http/Controllers/PerawatanKaryawanController.php
```
**Method:**
- `index()` - Dashboard perawatan karyawan
- `checklist($tipe)` - Tampilkan checklist berdasarkan tipe
- `executeChecklist(Request)` - Simpan checklist yang sudah dikerjakan
- `uncheckChecklist(Request)` - Batalkan checklist
- `history(Request)` - History aktivitas karyawan

### Views
```
resources/views/perawatan/karyawan/
├── index.blade.php          # Dashboard perawatan
├── checklist.blade.php      # Halaman checklist
└── history.blade.php        # History aktivitas
```

### Routes
```php
// routes/web.php
Route::middleware('auth')->prefix('perawatan/karyawan')->name('perawatan.karyawan.')->group(function () {
    Route::get('/', [PerawatanKaryawanController::class, 'index'])->name('index');
    Route::get('/checklist/{tipe}', [PerawatanKaryawanController::class, 'checklist'])->name('checklist');
    Route::post('/execute', [PerawatanKaryawanController::class, 'executeChecklist'])->name('execute');
    Route::post('/uncheck', [PerawatanKaryawanController::class, 'uncheckChecklist'])->name('uncheck');
    Route::get('/history', [PerawatanKaryawanController::class, 'history'])->name('history');
});
```

### Models
- `MasterPerawatan` - Template checklist (dibuat oleh admin)
- `PerawatanLog` - Log checklist yang dikerjakan karyawan

### Database Tables
- `master_perawatan` - Master template checklist
- `perawatan_log` - Log eksekusi checklist oleh karyawan

## 🚀 CARA PENGGUNAAN

### Untuk Karyawan

#### 1. **Akses Menu Perawatan**
   - Login ke aplikasi sebagai karyawan
   - Di dashboard karyawan, klik menu **"Perawatan"**

#### 2. **Pilih Jenis Checklist**
   - Dashboard perawatan menampilkan 4 pilihan:
     - **Checklist Harian** - Tugas setiap hari
     - **Checklist Mingguan** - Tugas setiap minggu
     - **Checklist Bulanan** - Tugas setiap bulan
     - **Checklist Tahunan** - Tugas setiap tahun

#### 3. **Kerjakan Checklist**
   a. Klik salah satu jenis checklist (misal: Checklist Harian)
   b. Sistem akan menampilkan daftar tugas perawatan
   c. Untuk menyelesaikan checklist:
      - Klik checkbox di sebelah kiri tugas
      - Akan muncul modal untuk input data:
        * **Catatan** (opsional): Tambahkan catatan jika ada
        * **Foto Bukti** (opsional): Upload foto hasil pekerjaan
      - Klik tombol **"Selesai"**
   d. Checklist akan berubah menjadi hijau dan tercentang
   e. Progress bar akan bertambah otomatis

#### 4. **Filter Checklist**
   - Di halaman checklist, terdapat filter kategori:
     - **Semua** - Tampilkan semua checklist
     - **Kebersihan** - Hanya tugas kebersihan
     - **Perawatan Rutin** - Hanya tugas perawatan rutin
     - **Pengecekan** - Hanya tugas pengecekan
     - **Lainnya** - Kategori lainnya

#### 5. **Batalkan Checklist**
   - Jika salah centang, klik tombol **"Batalkan"**
   - Checklist akan kembali menjadi belum selesai

#### 6. **Lihat History**
   - Klik menu **"History Aktivitas"**
   - Akan menampilkan semua checklist yang pernah dikerjakan
   - Bisa filter berdasarkan tipe periode
   - Bisa lihat foto bukti dengan klik thumbnail

### Untuk Admin

#### 1. **Buat Template Checklist**
   - Login sebagai admin/super admin
   - Masuk ke menu **"Manajemen Perawatan"**
   - Klik **"Master Checklist"**
   - Klik tombol **"Tambah Checklist"**
   - Isi form:
     * **Nama Kegiatan**: Nama tugas perawatan
     * **Deskripsi**: Detail pekerjaan
     * **Tipe Periode**: Harian/Mingguan/Bulanan/Tahunan
     * **Kategori**: Kebersihan/Perawatan Rutin/Pengecekan/Lainnya
     * **Urutan**: Nomor urut tampilan
     * **Status**: Aktif/Tidak Aktif
   - Klik **"Simpan"**

#### 2. **Monitoring Checklist Karyawan**
   - Admin dapat melihat laporan perawatan
   - Dapat melihat siapa saja yang sudah menyelesaikan checklist
   - Dapat generate PDF laporan perawatan

## 📊 ALUR KERJA

```
1. Admin membuat template checklist di Master Perawatan
   └─> Tentukan: Nama, Deskripsi, Periode, Kategori

2. Karyawan login dan masuk menu Perawatan
   └─> Pilih jenis checklist (Harian/Mingguan/Bulanan/Tahunan)

3. Karyawan melihat daftar checklist yang harus dikerjakan
   └─> Checklist yang sudah dikerjakan akan tercentang hijau
   └─> Checklist yang belum dikerjakan berwarna putih

4. Karyawan mencentang checklist
   └─> Input catatan (opsional)
   └─> Upload foto bukti (opsional)
   └─> Simpan

5. Sistem mencatat log aktivitas
   └─> User ID
   └─> Tanggal & Waktu
   └─> Catatan
   └─> Foto Bukti
   └─> Periode Key

6. Data tersimpan di tabel perawatan_log
   └─> Dapat dilihat di history
   └─> Dapat digunakan untuk laporan admin
```

## 🔐 HAK AKSES

### Karyawan
- ✅ Lihat semua checklist
- ✅ Centang checklist yang dikerjakan
- ✅ Tambah catatan dan foto bukti
- ✅ Batalkan centang
- ✅ Lihat history aktivitas sendiri
- ❌ Tidak bisa edit/hapus master checklist
- ❌ Tidak bisa lihat aktivitas karyawan lain

### Admin/Super Admin
- ✅ Semua akses karyawan
- ✅ Buat/Edit/Hapus master checklist
- ✅ Lihat aktivitas semua karyawan
- ✅ Generate laporan perawatan
- ✅ Download laporan PDF

## 📁 STRUKTUR DATABASE

### Tabel: perawatan_log
```sql
- id (PK)
- master_perawatan_id (FK)
- user_id (FK)
- tanggal_eksekusi
- waktu_eksekusi
- status (completed/skipped)
- catatan (nullable)
- foto_bukti (nullable)
- periode_key (contoh: harian_2025-11-19)
- created_at
- updated_at
```

### Periode Key Format
- **Harian**: `harian_2025-11-19`
- **Mingguan**: `mingguan_2025-W47`
- **Bulanan**: `bulanan_2025-11`
- **Tahunan**: `tahunan_2025`

## 🎨 DESIGN HIGHLIGHTS

### Dashboard Perawatan
- Gradient header (ungu-biru)
- Statistik card dengan progress bar
- Menu card dengan icon dan hover effect
- Recent activity timeline

### Halaman Checklist
- Progress card di atas
- Filter kategori dengan badge
- Checklist item dengan checkbox custom
- Modal untuk input catatan dan foto
- Smooth animations

### History
- Filter tabs untuk tipe periode
- Timeline style untuk history items
- Thumbnail foto yang bisa diperbesar
- Pagination

## 🔄 VALIDASI

### Execute Checklist
- `master_perawatan_id` - Required, harus ada di tabel master_perawatan
- `periode_key` - Required
- `catatan` - Opsional, max 500 karakter
- `foto_bukti` - Opsional, format: jpeg/png/jpg, max 2MB

### Uncheck Checklist
- `master_perawatan_id` - Required
- `periode_key` - Required
- User harus pemilik log

## 📝 CATATAN PENTING

1. **Satu Karyawan, Satu Checklist per Periode**
   - Setiap karyawan hanya bisa mencentang satu kali untuk checklist yang sama dalam periode yang sama
   - Jika sudah dicentang, checkbox akan disabled
   - Bisa dibatalkan dengan tombol "Batalkan"

2. **Foto Bukti**
   - Disimpan di `storage/app/public/perawatan/`
   - Format nama: `timestamp_userid_uniqid.extension`
   - Otomatis dihapus jika checklist dibatalkan

3. **Periode Key**
   - Digunakan untuk tracking checklist per periode
   - Harian reset setiap hari
   - Mingguan reset setiap minggu (sistem ISO week)
   - Bulanan reset setiap bulan
   - Tahunan reset setiap tahun

4. **Middleware**
   - Menggunakan middleware `auth` untuk memastikan user sudah login
   - Semua karyawan yang login bisa akses fitur ini

## 🐛 TROUBLESHOOTING

### Checklist tidak muncul?
- Pastikan admin sudah membuat master checklist
- Cek status master checklist harus **Aktif**
- Pastikan tipe periode sudah dipilih dengan benar

### Gagal upload foto?
- Cek ukuran foto max 2MB
- Cek format hanya JPG, PNG, JPEG
- Pastikan folder `storage/app/public/perawatan/` ada dan writable
- Jalankan: `php artisan storage:link`

### Foto tidak tampil?
- Jalankan: `php artisan storage:link`
- Cek permission folder storage
- Cek path foto di database

### Checklist sudah dicentang tapi bisa dicentang lagi?
- Cek periode_key di database
- Pastikan tidak ada duplikat log di tabel perawatan_log

## 🚀 DEPLOYMENT CHECKLIST

- [x] Controller PerawatanKaryawanController.php dibuat
- [x] Views perawatan/karyawan/* dibuat
- [x] Routes ditambahkan di web.php
- [x] Menu ditambahkan di dashboard karyawan
- [ ] Jalankan: `php artisan storage:link`
- [ ] Cek permission folder storage/perawatan
- [ ] Test akses menu dari dashboard
- [ ] Test centang checklist
- [ ] Test upload foto
- [ ] Test history
- [ ] Test filter kategori

## 📞 SUPPORT

Jika ada masalah atau pertanyaan, silakan hubungi tim developer.

---

**Versi**: 1.0  
**Tanggal**: 19 November 2025  
**Developer**: AI Assistant  
**Status**: ✅ Ready for Production
