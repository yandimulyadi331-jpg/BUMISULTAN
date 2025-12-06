# 📊 DOKUMENTASI INTEGRASI DANA OPERASIONAL DENGAN PUBLISH SYSTEM

## ✅ FITUR YANG SUDAH SELESAI

### 1. **Sistem Publish Laporan Keuangan untuk Karyawan**
   - ✅ Menu "Laporan" di dashboard karyawan
   - ✅ Halaman index dengan filter (Semua/Mingguan/Bulanan/Tahunan)
   - ✅ Halaman detail laporan
   - ✅ Download PDF dan Excel
   - ✅ Mobile-friendly design

### 2. **Admin Publish Management**
   - ✅ Section "Kelola Publish Laporan untuk Karyawan" di halaman Laporan Keuangan
   - ✅ Tabel menampilkan semua laporan (Annual Report + Dana Operasional)
   - ✅ Toggle publish/unpublish dengan tombol AJAX
   - ✅ Status badge (Published/Draft)
   - ✅ Info publisher dan tanggal publish

### 3. **Integrasi Dana Operasional dengan Publish System** ⭐ BARU!
   - ✅ Download PDF dari Dana Operasional otomatis tersimpan ke database
   - ✅ File PDF tersimpan di `storage/app/public/laporan-keuangan/`
   - ✅ Muncul di tabel publish dengan status DRAFT
   - ✅ Admin bisa publish untuk karyawan lihat
   - ✅ Karyawan hanya bisa lihat yang sudah dipublish

### 4. **Annual Report (Tetap Terpisah)**
   - ✅ Tombol "Annual Report" di halaman Laporan Keuangan tetap berfungsi
   - ✅ Generate PDF format Annual Report (fancy dengan chart)
   - ✅ Tersimpan ke database dan bisa dipublish
   - ✅ Tidak mengganggu sistem Dana Operasional

---

## 🔄 ALUR KERJA (WORKFLOW)

### **Untuk Admin:**

#### **OPSI 1: Download dari Dana Operasional** (Recommended)
```
1. Buka menu "Dana Operasional"
2. Pilih filter (Bulan/Tahun/Minggu/Range)
3. Klik tombol "Download PDF"
   ↓
4. PDF otomatis terdownload KE browser
5. PDF juga tersimpan ke database (status: DRAFT)
   ↓
6. Buka menu "Laporan Keuangan"
7. Scroll ke section "Kelola Publish Laporan untuk Karyawan"
8. Cari laporan yang baru didownload (cek periode & tanggal)
9. Klik tombol "Publish"
   ↓
10. ✅ Laporan sekarang bisa dilihat karyawan!
```

#### **OPSI 2: Download Annual Report**
```
1. Buka menu "Laporan Keuangan"
2. Isi form (Tahun, Periode, Bulan jika bulanan)
3. Klik tombol "Download Annual Report"
   ↓
4. PDF fancy dengan chart terdownload
5. PDF tersimpan ke database (status: DRAFT)
   ↓
6. Scroll ke section "Kelola Publish Laporan untuk Karyawan"
7. Klik tombol "Publish" pada laporan yang diinginkan
   ↓
8. ✅ Laporan sekarang bisa dilihat karyawan!
```

### **Untuk Karyawan:**

```
1. Login sebagai karyawan
2. Di dashboard, klik card "Laporan"
   ↓
3. Lihat daftar laporan yang sudah dipublish
4. Filter by: Semua / Mingguan / Bulanan / Tahunan
   ↓
5. Klik card laporan untuk lihat detail
   ↓
6. Klik "Download PDF" atau "Download Excel" (jika ada)
   ↓
7. ✅ File terdownload!
```

---

## 🗂️ STRUKTUR DATABASE

### Tabel: `laporan_keuangan`

```sql
- id (primary key)
- nomor_laporan (LAP-YYMM-XXX)
- jenis_laporan (enum)
  * LAPORAN_BUDGET (Annual Report)
  * LAPORAN_MINGGUAN (Dana Ops Mingguan)
  * LAPORAN_BULANAN (Dana Ops Bulanan)
  * LAPORAN_TAHUNAN (Dana Ops Tahunan)
  * LAPORAN_CUSTOM (Dana Ops Range)
- nama_laporan (misal: "Minggu 20-26 Jan 2025")
- tanggal_mulai
- tanggal_selesai
- periode (MINGGUAN/BULANAN/TAHUNAN/CUSTOM)
- file_pdf (path: laporan-keuangan/xxx.pdf)
- file_excel (optional)
- status (DRAFT/COMPLETED)
- is_published (boolean) ⭐ untuk publish ke karyawan
- published_at (timestamp)
- published_by (user_id admin)
- user_id (creator)
- generated_at
- created_at
- updated_at
```

---

## 📝 PERBEDAAN ANNUAL REPORT vs DANA OPERASIONAL

| Aspek | Annual Report | Dana Operasional |
|-------|---------------|------------------|
| **Tombol** | "Annual Report" | "Download PDF" |
| **Lokasi** | `/laporan-keuangan` | `/dana-operasional` |
| **Format PDF** | Fancy dengan chart & grafik | Simple table transaksi |
| **View Template** | `laporan-keuangan.pdf-annual-report` | `dana-operasional.pdf-simple` |
| **Jenis Laporan** | `LAPORAN_BUDGET` | `LAPORAN_MINGGUAN/BULANAN/TAHUNAN/CUSTOM` |
| **Controller** | `LaporanKeuanganController::downloadAnnualReport()` | `DanaOperasionalController::exportPdf()` |
| **File Storage** | ✅ Tersimpan ke storage | ✅ Tersimpan ke storage |
| **Database Entry** | ✅ Ya | ✅ Ya |
| **Bisa Dipublish** | ✅ Ya | ✅ Ya |
| **Tampilan Karyawan** | Sama (mobile cards) | Sama (mobile cards) |

---

## 🔐 PERMISSIONS

```php
// Karyawan
'laporan-keuangan-karyawan.index' => Bisa lihat daftar laporan yang dipublish

// Admin
'laporan-keuangan.publish' => Bisa publish/unpublish laporan
'laporan-keuangan.download' => Bisa download Annual Report
'dana-operasional.view' => Bisa akses Dana Operasional
```

---

## 🧪 CARA TESTING

### 1. Test Download Dana Operasional
```bash
# Login sebagai admin
1. Buka: http://localhost:8000/dana-operasional
2. Pilih filter bulan: 2025-01
3. Klik "Download PDF"
4. Cek browser: PDF harus terdownload
5. Cek storage: storage/app/public/laporan-keuangan/Laporan_Keuangan_20250101_20250131.pdf harus ada
```

### 2. Test Database Entry
```bash
php test_dana_operasional_publish.php
```

Output yang diharapkan:
```
✅ Ditemukan 1 laporan Dana Operasional

-----------------------------------
ID: 1
Nomor: LAP-2501-001
Jenis: LAPORAN_BULANAN
Nama: Januari 2025
Periode: BULANAN
Tanggal: 2025-01-01 s/d 2025-01-31
Status: DRAFT
Published: ❌ TIDAK
File PDF: laporan-keuangan/Laporan_Keuangan_20250101_20250131.pdf
File Status: ✅ EXISTS (145.23 KB)
Created: 2025-01-19 10:30:00
```

### 3. Test Publish ke Karyawan
```bash
# Login sebagai admin
1. Buka: http://localhost:8000/laporan-keuangan
2. Scroll ke "Kelola Publish Laporan untuk Karyawan"
3. Cari laporan "Januari 2025" (BULANAN)
4. Klik tombol "Publish"
5. Status harus berubah jadi "Published" dengan badge hijau
```

### 4. Test Karyawan Access
```bash
# Login sebagai karyawan
1. Buka dashboard: http://localhost:8000/karyawan/dashboard
2. Klik card "Laporan"
3. Harus muncul laporan "Januari 2025"
4. Klik card → lihat detail
5. Klik "Download PDF" → file harus terdownload
```

---

## 🔧 TROUBLESHOOTING

### ❌ File PDF tidak ditemukan saat download
**Penyebab:** File tidak tersimpan ke storage  
**Solusi:**
```bash
# Cek permission folder
php artisan storage:link
chmod -R 775 storage/app/public/
```

### ❌ Laporan tidak muncul di tabel publish
**Penyebab:** Download PDF belum dipanggil atau error saat save  
**Solusi:**
```bash
# Cek log error
tail -f storage/logs/laravel.log

# Cek database
php test_dana_operasional_publish.php
```

### ❌ Karyawan tidak bisa akses menu Laporan
**Penyebab:** Permission belum di-assign  
**Solusi:**
```bash
php setup_permissions_laporan_karyawan.php
```

### ❌ Download PDF blank/error 404
**Penyebab:** File path salah atau storage link belum dibuat  
**Solusi:**
```bash
php artisan storage:link
php artisan cache:clear
php artisan config:clear
```

---

## 📁 FILE YANG DIMODIFIKASI

### Controllers
- `app/Http/Controllers/DanaOperasionalController.php`
  - Method `exportPdf()` → tambah `saveDanaOperasionalToDatabase()`
  - Private method `saveDanaOperasionalToDatabase()` (NEW)

- `app/Http/Controllers/LaporanKeuanganController.php`
  - Method `downloadAnnualReport()` → tetap gunakan Annual Report view
  - Method `togglePublish()` → AJAX endpoint
  - Method `publishLaporan()` dan `unpublishLaporan()`
  - Private method `saveLaporanToDatabase()`

- `app/Http/Controllers/LaporanKeuanganKaryawanController.php`
  - Method `index()`, `show()`, `downloadPDF()`, `downloadExcel()`

### Views
- `resources/views/laporan-keuangan/index.blade.php`
  - Section "Kelola Publish Laporan untuk Karyawan"
  - JavaScript untuk toggle publish

- `resources/views/laporan-keuangan-karyawan/index.blade.php`
  - Mobile card layout dengan filter tabs

- `resources/views/laporan-keuangan-karyawan/show.blade.php`
  - Detail view dengan download buttons

- `resources/views/dashboard/karyawan.blade.php`
  - Card "Laporan" menu

### Routes
- `routes/web.php`
  - Karyawan routes (role:karyawan)
  - Admin publish routes (role:super admin)

### Database
- Migration: `2025_11_19_013249_add_published_fields_to_laporan_keuangan_table`

---

## 🎯 NEXT STEPS (OPTIONAL IMPROVEMENTS)

1. **Excel Export untuk Dana Operasional**
   - Saat download PDF, juga generate Excel
   - Save file_excel ke database
   - Karyawan bisa download Excel juga

2. **Notifikasi Karyawan**
   - Kirim notif saat ada laporan baru dipublish
   - Badge "NEW" untuk laporan yang baru

3. **Bulk Publish**
   - Checkbox untuk select multiple laporan
   - Tombol "Publish Selected"

4. **Preview PDF**
   - Modal preview sebelum publish
   - Iframe embed PDF

5. **Filter & Search**
   - Search by nama laporan
   - Filter by status (Published/Draft)
   - Filter by jenis laporan

---

## 📞 SUPPORT

Jika ada masalah atau pertanyaan:

1. Cek log error: `storage/logs/laravel.log`
2. Run test script: `php test_dana_operasional_publish.php`
3. Cek database: `php cek_tabel_laporan.php`
4. Clear cache: `php artisan cache:clear && php artisan config:clear`

---

**Dibuat:** 19 Januari 2025  
**Versi:** 1.0 - Integrasi Complete  
**Status:** ✅ Production Ready
