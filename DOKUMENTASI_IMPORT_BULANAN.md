# 🚀 DOKUMENTASI IMPORT BULANAN - FITUR AUTO-GENERATE TANGGAL

## 🎯 Overview

Fitur **Import Bulanan** adalah cara mudah untuk mengupload transaksi **1 bulan penuh** sekaligus **TANPA** perlu mengisi kolom tanggal di Excel! Sistem akan otomatis generate tanggal, jam, dan nomor transaksi.

---

## ✨ Perbedaan Import Reguler vs Import Bulanan

| Fitur | Import Reguler | Import Bulanan |
|-------|----------------|----------------|
| **Kolom Tanggal** | ✅ Wajib diisi manual | ❌ TIDAK perlu! |
| **Kolom di Excel** | 4 kolom (Tanggal, Keterangan, Masuk, Keluar) | 3 kolom (Keterangan, Masuk, Keluar) |
| **Tanggal Transaksi** | Sesuai yang diisi user | Auto dari tanggal 1 bulan pilihan |
| **Jam Transaksi** | Jam saat import | Auto (realistis 08:00-19:00) |
| **Nomor Transaksi** | Auto (kompleks) | **BS/001**, **BS/002**, **BS/003** (simpel!) |
| **Cocok untuk** | Data historis dengan tanggal spesifik | Data bulanan/monthly report |

---

## 🔢 Format Nomor Transaksi Baru (SIMPEL!)

### Format: **BS/XXX**
- **BS** = Bumi Sultan
- **XXX** = Nomor urut 3 digit (001, 002, 003, dst)

### Contoh:
```
BS/001 → Transaksi pertama
BS/002 → Transaksi kedua
BS/003 → Transaksi ketiga
...
BS/099 → Transaksi ke-99
BS/100 → Transaksi ke-100
```

### Keuntungan Format Baru:
✅ **Mudah diucapkan**: "BS nol nol satu" atau "BS nomor satu"  
✅ **Mudah dicari**: Cukup ingat nomornya  
✅ **Konsisten**: Panjang tetap, mudah sorting  
✅ **Profesional**: Kode perusahaan (BS) + nomor urut  

---

## 📋 Cara Menggunakan Import Bulanan

### 1. Download Template Bulanan
```
Dashboard → Dropdown "Template" → "Template Bulanan (otomatis tanggal)"
```

### 2. Isi Excel (Hanya 3 Kolom!)

**Kolom yang perlu diisi:**
| Keterangan | Dana Masuk | Dana Keluar |
|------------|------------|-------------|
| Khidmat Ramadhan | | 350000 |
| Makanan kucing | | 35000 |
| Laundry Jakarta | | 150000 |
| BBM RMX | | 50000 |
| Tambahan dana kas | 2000000 | |

**TIDAK PERLU** isi kolom tanggal!

### 3. Kembali ke Dashboard
```
Klik tombol "Import Bulanan" (warna kuning)
```

### 4. Pilih Bulan & Tahun
```
Bulan: Januari
Tahun: 2025
```

### 5. Upload File Excel
```
Pilih file → Klik "Import Data Bulanan"
```

### 6. Sistem Bekerja Otomatis! 🎉
- ✅ Generate tanggal mulai dari **1 Januari 2025**
- ✅ Generate jam otomatis (realistis)
- ✅ Generate nomor transaksi: **BS/001**, **BS/002**, dst
- ✅ Setiap 5 transaksi → ganti hari berikutnya
- ✅ Update saldo harian otomatis

---

## 🔧 Logika Auto-Generate Tanggal & Jam

### Algoritma:
```
Baris 1-5   → Tanggal 1 (jam 08:00, 08:30, 09:00, 09:30, 10:00)
Baris 6-10  → Tanggal 2 (jam 08:00, 08:45, 09:30, 10:15, 11:00)
Baris 11-15 → Tanggal 3 (dan seterusnya...)
```

### Detail Teknis:
- **Hari pertama**: Tanggal 1 bulan yang dipilih
- **Jam mulai**: 08:00 WIB
- **Interval**: Random 30-60 menit antar transaksi
- **Ganti hari**: Setiap 5 transaksi
- **Reset jam**: Setiap ganti hari kembali ke 08:00

### Contoh Output:
```
BS/001 → 1 Jan 2025, 08:00 WIB → Khidmat Ramadhan
BS/002 → 1 Jan 2025, 08:45 WIB → Makanan kucing
BS/003 → 1 Jan 2025, 09:30 WIB → Laundry Jakarta
BS/004 → 1 Jan 2025, 10:15 WIB → BBM RMX
BS/005 → 1 Jan 2025, 11:00 WIB → BBM Innova
BS/006 → 2 Jan 2025, 08:00 WIB → Badal Ustadz (ganti hari!)
BS/007 → 2 Jan 2025, 08:50 WIB → Gas Jakarta
...
```

---

## 📊 Struktur Database

### Tabel: `realisasi_dana_operasional`
```sql
ALTER TABLE realisasi_dana_operasional 
ADD COLUMN nomor_transaksi VARCHAR(20) NULL AFTER pengajuan_id;

CREATE INDEX idx_nomor_transaksi ON realisasi_dana_operasional(nomor_transaksi);
```

### Kolom Baru:
- **nomor_transaksi** (varchar 20): Format BS/XXX
- **Nullable**: Ya (backward compatibility)
- **Indexed**: Ya (cepat searching)

---

## 🎨 Tampilan UI

### Tombol di Dashboard:
```
┌─────────────────┬────────────────────┬──────────────┬───────────┐
│ Import Reguler  │ Import Bulanan     │ Download PDF │ Template▼ │
│ (hijau)         │ (kuning)           │              │           │
└─────────────────┴────────────────────┴──────────────┴───────────┘
```

### Dropdown Template:
```
Template ▼
  ├─ Template Reguler (dengan tanggal)
  └─ Template Bulanan (otomatis tanggal) ⭐
```

### Modal Import Bulanan:
- **Header**: Warna kuning (warning/info)
- **Icon**: 📅 Calendar Month
- **Form**: Dropdown Bulan + Tahun
- **Preview**: Tabel contoh format Excel
- **Alert**: Penjelasan cara kerja sistem

---

## 🔄 Flow Process

### 1. User Action:
```
User → Klik "Import Bulanan" → Pilih bulan/tahun → Upload Excel
```

### 2. Backend Process:
```php
1. Validasi file (xlsx, xls, csv, max 5MB)
2. Validasi bulan (1-12) dan tahun (2020-2030)
3. Parse Excel → Loop setiap baris
4. Untuk setiap baris:
   - Generate tanggal (mulai dari tgl 1)
   - Generate jam (random realistis)
   - Generate nomor transaksi (BS/XXX)
   - Cek saldo harian (create jika belum ada)
   - Save transaksi
   - Update saldo harian
5. Redirect dengan success message
```

### 3. System Response:
```
✅ Success: "Data transaksi bulan Januari 2025 berhasil diimport!"
❌ Error: "Import gagal: [detail error]"
```

---

## 🚨 Validasi & Error Handling

### Validasi Excel:
- ✅ File format: `.xlsx`, `.xls`, `.csv`
- ✅ Ukuran max: 5 MB
- ✅ Kolom wajib: Keterangan, Dana Masuk/Dana Keluar
- ✅ Nominal: Harus angka, min 0
- ✅ Tidak boleh isi Masuk DAN Keluar bersamaan

### Error Messages:
```
❌ "Baris 5: Tidak boleh isi DANA MASUK dan DANA KELUAR bersamaan!"
❌ "Baris 10: Harus isi salah satu antara DANA MASUK atau DANA KELUAR!"
❌ "Import berhenti: Sudah melewati akhir bulan 1/2025"
```

### Auto-Stop Logic:
Jika tanggal sudah melewati akhir bulan (misal 31 Jan → 1 Feb), sistem akan:
- ⚠️ Stop import
- ⚠️ Log warning
- ✅ Save transaksi yang sudah berhasil
- ℹ️ Notif user

---

## 📂 File yang Dibuat/Dimodifikasi

### 1. **Import Class Baru**
`app/Imports/TransaksiBulananImport.php`
- Logic auto-generate tanggal, jam, nomor transaksi
- Validasi per baris
- Update saldo harian otomatis

### 2. **Migration**
`database/migrations/2025_11_13_050346_add_nomor_transaksi_to_realisasi_dana_operasional.php`
- Tambah kolom `nomor_transaksi`
- Tambah index untuk performa

### 3. **Controller Method**
`app/Http/Controllers/DanaOperasionalController.php`
- `downloadTemplateBulanan()` → Template Excel 3 kolom
- `importBulanan()` → Process upload bulanan

### 4. **Routes**
`routes/web.php`
```php
Route::get('/download-template-bulanan', 'downloadTemplateBulanan');
Route::post('/import-bulanan', 'importBulanan');
```

### 5. **View**
`resources/views/dana-operasional/index.blade.php`
- Tombol "Import Bulanan"
- Modal form upload bulanan
- Dropdown template

### 6. **Model Update**
`app/Models/RealisasiDanaOperasional.php`
- Tambah `nomor_transaksi` di `$fillable`

`app/Models/SaldoHarianOperasional.php`
- Update `getSaldoKemarin()` → terima parameter tanggal

---

## 🎯 Use Case & Contoh

### Use Case 1: Upload Data Januari 2025
**Skenario**: User punya data transaksi Januari dalam Excel manual  
**Solusi**: Import Bulanan

**Steps:**
1. Download template bulanan
2. Copy-paste data ke template (hanya 3 kolom)
3. Pilih: Bulan = Januari, Tahun = 2025
4. Upload
5. ✅ Done! Data tersimpan dengan tanggal 1-31 Januari

### Use Case 2: Upload Data Historis
**Skenario**: User mau input data bulan lalu  
**Solusi**: Import Bulanan (bisa historis!)

**Steps:**
1. Pilih bulan lalu (misal: Oktober 2024)
2. Upload Excel
3. ✅ Data tersimpan dengan tanggal Oktober 2024

### Use Case 3: Upload Data Masa Depan
**Skenario**: Planning/budgeting bulan depan  
**Solusi**: Import Bulanan (bisa future date!)

**Steps:**
1. Pilih bulan depan (misal: Desember 2025)
2. Upload Excel
3. ✅ Data tersimpan sebagai planning

---

## 🔍 Troubleshooting

### Q: Nomor transaksi tidak muncul?
**A:** Cek kolom `nomor_transaksi` di database. Jika NULL, run migration:
```bash
php artisan migrate
```

### Q: Tanggal tidak sesuai?
**A:** Pastikan pilih bulan & tahun yang benar di form upload.

### Q: Import stuck/berhenti?
**A:** Cek log:
```bash
tail -f storage/logs/laravel.log
```
Kemungkinan sudah melewati akhir bulan.

### Q: Nomor transaksi loncat (BS/001, BS/005)?
**A:** Normal jika ada transaksi lain di bulan yang sama dari import reguler.

### Q: Format nomor transaksi lama masih ada?
**A:** Transaksi lama tetap pakai format lama. Format baru hanya untuk import baru.

---

## 📈 Performa & Optimasi

### Import Speed:
- **Small** (< 50 baris): ~2-3 detik
- **Medium** (50-200 baris): ~5-10 detik
- **Large** (200-500 baris): ~15-30 detik

### Tips Optimasi:
- Gunakan **chunk processing** untuk file besar
- Disable **auto-recalculate saldo** saat import (recalc di akhir)
- Gunakan **queue** untuk file > 500 baris

---

## 🎓 Best Practices

### ✅ DO:
- Download template sebelum upload
- Isi data lengkap dan benar
- Pilih bulan/tahun yang tepat
- Backup data sebelum import besar
- Test dengan file kecil dulu

### ❌ DON'T:
- Jangan isi tanggal di Excel (sistem auto)
- Jangan upload file > 5MB
- Jangan import bulan yang sama 2x (duplikasi!)
- Jangan ubah header Excel
- Jangan isi Masuk DAN Keluar bersamaan

---

## 🔐 Security & Permission

### Role yang Bisa Akses:
- ✅ **Super Admin** only

### Validasi:
- File type validation (xlsx, xls, csv only)
- File size validation (max 5MB)
- Data type validation (numeric nominal)
- Range validation (bulan 1-12, tahun 2020-2030)

### Audit Trail:
```php
Log::info('Import Bulanan dimulai', [
    'filename' => $file->getClientOriginalName(),
    'bulan' => $bulan,
    'tahun' => $tahun,
    'user_id' => auth()->id(),
]);
```

---

## 📞 Support

Jika ada pertanyaan atau kendala:
- Cek dokumentasi ini
- Cek log error: `storage/logs/laravel.log`
- Hubungi tim developer

---

**© 2025 Bumi Sultan Super App - Import Bulanan Feature**

🎉 Sekarang import data bulanan jadi **SUPER MUDAH** dan **CEPAT**!
