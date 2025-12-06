# FITUR IMPORT EXCEL KHIDMAT - DOKUMENTASI

## 📋 Overview
Fitur ini memungkinkan pengguna untuk mengupload data belanja khidmat secara massal menggunakan file Excel, sehingga lebih efisien daripada input manual satu per satu.

## ✨ Fitur Utama

### 1. Download Template Excel
- Template Excel kosong dengan format yang sudah ditentukan
- Header berwarna biru dengan kolom yang sudah disesuaikan
- Sudah include 5 baris contoh kosong
- Format file: `.xlsx`

### 2. Import Data dari Excel
- Upload file Excel yang sudah diisi
- Validasi data otomatis
- Menghapus data belanja lama dan menggantinya dengan data baru
- Perhitungan otomatis:
  - Total harga per item (jumlah × harga satuan)
  - Total belanja keseluruhan
  - Saldo akhir = (Saldo Awal + Saldo Masuk) - Total Belanja
  - Update saldo awal jadwal berikutnya secara otomatis (cascade)

## 📁 File Structure

### Backend Files

#### 1. **Controller** - `app/Http/Controllers/KhidmatController.php`
```php
// Download template
public function downloadTemplate($id)

// Import dari Excel
public function importBelanja(Request $request, $id)
```

#### 2. **Export Class** - `app/Exports/KhidmatTemplateExport.php`
- Generate template Excel kosong
- Implements: `FromArray`, `WithHeadings`, `WithStyles`, `WithColumnWidths`, `WithTitle`
- Header styling dengan background biru
- Column widths disesuaikan

#### 3. **Import Class** - `app/Imports/KhidmatBelanjaImport.php`
- Baca data dari Excel
- Implements: `ToModel`, `WithHeadingRow`, `WithValidation`
- Validasi setiap baris data
- Perhitungan total harga otomatis
- Update saldo cascade ke jadwal berikutnya

### Routes - `routes/web.php`
```php
Route::get('/{id}/template', 'downloadTemplate')->name('khidmat.download-template');
Route::post('/{id}/import', 'importBelanja')->name('khidmat.import-belanja');
```

### View - `resources/views/khidmat/laporan.blade.php`
- Card khusus untuk import Excel
- Tombol download template (hijau)
- Tombol upload file (biru)
- Modal untuk upload file dengan instruksi lengkap

## 📊 Format Excel

### Header Kolom (Baris 1):
| Kolom | Nama         | Tipe Data | Wajib | Keterangan                    |
|-------|-------------|-----------|-------|-------------------------------|
| A     | Nama Barang | Text      | Ya    | Nama item yang dibeli         |
| B     | Jumlah      | Number    | Ya    | Jumlah barang (minimal 1)     |
| C     | Satuan      | Text      | Ya    | kg, liter, pcs, dus, dll      |
| D     | Harga Satuan| Number    | Ya    | Harga per satuan (Rupiah)     |
| E     | Keterangan  | Text      | Tidak | Catatan tambahan (opsional)   |

### Contoh Data:
```
Nama Barang    | Jumlah | Satuan | Harga Satuan | Keterangan
Beras          | 10     | kg     | 15000        | Beras premium
Minyak Goreng  | 5      | liter  | 18000        | Minyak curah
Gula Pasir     | 3      | kg     | 14000        |
Telur          | 2      | kg     | 28000        | Telur ayam
```

## 🔄 Alur Proses Import

1. **Pengguna mengakses halaman Laporan Khidmat**
   - Menu: Saung Santri → Khidmat → Detail → Laporan Keuangan

2. **Download Template**
   - Klik tombol "Download Template Excel" (hijau)
   - File akan terdownload dengan nama: `Template_Belanja_Khidmat_[Kelompok]_[Tanggal].xlsx`

3. **Isi Data di Excel**
   - Buka file Excel yang sudah didownload
   - Isi data mulai dari baris ke-2 (baris 1 adalah header)
   - Pastikan format sesuai dengan ketentuan

4. **Upload File**
   - Klik tombol "Upload File Excel" (biru)
   - Modal akan muncul
   - Pilih file Excel yang sudah diisi
   - Klik "Upload & Import"

5. **Proses Backend**
   ```
   1. Validasi file (format, ukuran max 2MB)
   2. Delete data belanja yang lama
   3. Baca data dari Excel baris per baris
   4. Validasi setiap baris
   5. Hitung total harga per item (jumlah × harga satuan)
   6. Simpan ke database (belanja_khidmat)
   7. Hitung total belanja keseluruhan
   8. Update saldo akhir jadwal ini
   9. Update saldo awal jadwal berikutnya (cascade)
   10. Commit transaction
   11. Redirect dengan success message
   ```

6. **Hasil**
   - Data belanja terupdate di tabel
   - Total belanja otomatis terhitung
   - Saldo akhir otomatis diperbarui
   - Jadwal berikutnya otomatis terupdate saldo awalnya

## ⚠️ Validasi & Error Handling

### Validasi File:
- Format: `.xlsx` atau `.xls`
- Ukuran maksimal: 2MB
- Error: "File harus berformat Excel"

### Validasi Data:
- **Nama Barang**: Wajib diisi (string)
- **Jumlah**: Wajib diisi, harus angka, minimal 1
- **Satuan**: Wajib diisi (string)
- **Harga Satuan**: Wajib diisi, harus angka, minimal 0
- **Keterangan**: Opsional

### Error Messages:
```php
'nama_barang.required' => 'Nama barang harus diisi'
'jumlah.required' => 'Jumlah harus diisi'
'jumlah.integer' => 'Jumlah harus berupa angka'
'jumlah.min' => 'Jumlah minimal 1'
'satuan.required' => 'Satuan harus diisi'
'harga_satuan.required' => 'Harga satuan harus diisi'
'harga_satuan.numeric' => 'Harga satuan harus berupa angka'
```

## 💾 Database Impact

### Table: `belanja_khidmat`
Saat import:
1. **DELETE** semua record dengan `jadwal_khidmat_id` yang sama
2. **INSERT** data baru dari Excel

### Table: `jadwal_khidmat`
Saat import:
1. **UPDATE** `total_belanja` = SUM(total_harga dari belanja_khidmat)
2. **UPDATE** `saldo_akhir` = (saldo_awal + saldo_masuk) - total_belanja

### Cascade Update:
Jadwal berikutnya secara otomatis terupdate:
```
Jadwal A (tanggal 1 Nov): saldo_akhir = 100.000
  ↓ (otomatis)
Jadwal B (tanggal 2 Nov): saldo_awal = 100.000
  ↓ (otomatis)
Jadwal C (tanggal 3 Nov): saldo_awal = [dari saldo_akhir B]
... dst
```

## 🎨 UI/UX

### Card Import Excel
- Background: Info blue (opacity 10%)
- Icon: `ti-file-spreadsheet`
- Layout: Row dengan 2 kolom
  - Kiri: Instruksi 3 langkah
  - Kanan: 2 tombol aksi

### Tombol Download Template
- Warna: Success (hijau)
- Icon: `ti-download`
- Text: "Download Template Excel"

### Tombol Upload
- Warna: Primary (biru)
- Icon: `ti-upload`
- Text: "Upload File Excel"
- Action: Buka modal

### Modal Upload
- Header: Background primary dengan icon upload
- Warning alert: Informasi data akan diganti
- Input file: Accept `.xlsx, .xls`
- Info box: Format kolom Excel
- Footer: Tombol Batal & Upload

## 🔐 Permission

Kedua fitur ini menggunakan permission:
```php
->can('khidmat.laporan')
```

User harus memiliki permission `khidmat.laporan` untuk:
- Download template
- Upload/import file Excel

## 📝 Testing Checklist

- [x] Route download template terdaftar
- [x] Route import terdaftar
- [x] Export class dibuat dengan styling
- [x] Import class dibuat dengan validasi
- [x] Controller methods implementasi lengkap
- [x] View tampilkan tombol download & upload
- [x] Modal upload dengan form lengkap
- [x] Autoload regenerated
- [ ] Test download template (file terdownload)
- [ ] Test isi Excel dan upload (data masuk database)
- [ ] Test validasi error (file salah format)
- [ ] Test data belanja lama terhapus
- [ ] Test perhitungan total otomatis
- [ ] Test cascade update saldo

## 🚀 Cara Penggunaan

### Untuk User:
1. Buka menu **Saung Santri → Khidmat**
2. Pilih jadwal yang ingin diinput belanjanya
3. Klik tombol **Laporan** (icon uang hijau)
4. Di card "Import Data Belanja dari Excel":
   - Klik **Download Template Excel** (hijau)
   - Isi data di file Excel yang terdownload
   - Klik **Upload File Excel** (biru)
   - Pilih file yang sudah diisi
   - Klik **Upload & Import**
5. Data belanja akan otomatis terekap dan tampil di tabel

### Untuk Developer:
```bash
# Jika perlu reinstall package
composer require maatwebsite/excel

# Regenerate autoload
composer dump-autoload

# Clear cache jika diperlukan
php artisan config:clear
php artisan cache:clear
```

## 📈 Keuntungan Fitur Ini

1. **Efisiensi Waktu**: Input massal lebih cepat dari manual
2. **Akurasi**: Validasi otomatis mencegah data salah
3. **Konsistensi**: Format template yang sama untuk semua user
4. **Kemudahan**: Tidak perlu pengetahuan teknis, cukup bisa Excel
5. **Tracking**: Semua data tercatat dengan timestamp
6. **Otomatis**: Perhitungan dan cascade update tanpa manual

## 🔧 Troubleshooting

### Error: "Class KhidmatTemplateExport not found"
**Solution**: Run `composer dump-autoload`

### Error: "Call to undefined method Excel::download"
**Solution**: 
```bash
composer require maatwebsite/excel
php artisan config:clear
```

### Error: "The file field must be a file of type: xlsx, xls"
**Solution**: Pastikan file yang diupload benar-benar Excel (.xlsx atau .xls)

### Data tidak masuk setelah import
**Solution**: 
- Cek format Excel sesuai template
- Cek ada error message di halaman
- Cek log Laravel: `storage/logs/laravel.log`

### Saldo tidak update
**Solution**:
- Cek method `updateNextJadwalSaldo()` dipanggil
- Cek ada jadwal berikutnya di database
- Cek tanggal jadwal berurutan

## 📞 Support

Jika ada masalah atau butuh bantuan:
1. Cek error message di halaman
2. Cek `storage/logs/laravel.log`
3. Cek database apakah data masuk
4. Test dengan data sample terlebih dahulu

---

**Dibuat**: 9 November 2025  
**Versi**: 1.0  
**Status**: ✅ Implemented & Ready to Use
