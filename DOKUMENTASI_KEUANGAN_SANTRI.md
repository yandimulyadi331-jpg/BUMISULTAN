# DOKUMENTASI SISTEM KEUANGAN SANTRI

## 🎯 OVERVIEW

Sistem **Keuangan Santri** adalah modul manajemen keuangan yang komprehensif untuk mencatat, melacak, dan menganalisis transaksi keuangan santri di Pondok Pesantren. Sistem ini dirancang dengan fitur-fitur canggih seperti:

- ✅ **Auto-Kategorisasi Transaksi** menggunakan AI/Algoritma
- ✅ **Laporan Bergaya Bank Statement** dengan export PDF/Excel
- ✅ **Import Bulk Data** dari Excel dengan deteksi otomatis
- ✅ **Dashboard Interaktif** dengan statistik real-time
- ✅ **Tracking Saldo** otomatis per santri
- ✅ **Verifikasi Transaksi** untuk validasi data

---

## 🗂️ STRUKTUR DATABASE

### 1. **Tabel: keuangan_santri_categories**
Menyimpan kategori transaksi (pemasukan & pengeluaran)

```sql
- id (PK)
- nama_kategori (string)
- jenis (enum: pemasukan/pengeluaran)
- keywords (JSON array) → untuk auto-detect
- icon (string)
- color (string)
- deskripsi (text)
- is_active (boolean)
- timestamps
```

**Kategori Default:**

**PENGELUARAN:**
- Kebersihan & Kesehatan
- Makanan & Minuman
- Pendidikan & Alat Tulis
- Transportasi
- Komunikasi & Pulsa
- Pakaian & Laundry
- Ibadah & Keagamaan
- Hiburan & Rekreasi
- Lain-lain

**PEMASUKAN:**
- Uang Saku Orang Tua
- Beasiswa
- Hadiah & Bonus
- Pekerjaan Sampingan
- Saldo Awal
- Lain-lain

### 2. **Tabel: keuangan_santri_transactions**
Menyimpan semua transaksi keuangan

```sql
- id (PK)
- santri_id (FK ke karyawan.nik)
- category_id (FK ke keuangan_santri_categories)
- kode_transaksi (string, unique) → TRX-20250108-001
- jenis (enum: pemasukan/pengeluaran)
- jumlah (decimal 15,2)
- saldo_sebelum (decimal 15,2)
- saldo_sesudah (decimal 15,2)
- tanggal_transaksi (date)
- deskripsi (string)
- catatan (text, nullable)
- bukti_file (string, nullable)
- is_verified (boolean)
- verified_by (FK ke users, nullable)
- verified_at (datetime, nullable)
- metode_pembayaran (string, nullable)
- created_by (FK ke users)
- updated_by (FK ke users, nullable)
- timestamps
- soft_deletes
```

### 3. **Tabel: keuangan_santri_saldo**
Menyimpan saldo terkini per santri

```sql
- id (PK)
- santri_id (FK ke karyawan.nik, unique)
- saldo_awal (decimal 15,2)
- total_pemasukan (decimal 15,2)
- total_pengeluaran (decimal 15,2)
- saldo_akhir (decimal 15,2)
- last_transaction_date (date, nullable)
- timestamps
```

---

## 🚀 FITUR UTAMA

### 1. **Dashboard Keuangan**
**Route:** `/keuangan-santri`

**Fitur:**
- Statistik cards: Total Pemasukan, Pengeluaran, Selisih, Saldo Akhir
- Filter per santri & periode (Hari, Minggu, Bulan, Tahun)
- Tabel transaksi terbaru dengan pagination
- Quick action buttons

**Screenshot Layout:**
```
┌─────────────────────────────────────────┐
│  [Filter Santri] [Periode] [Filter Btn] │
├─────────┬─────────┬─────────┬──────────┤
│Pemasukan│Pengeluar│ Selisih │   Saldo  │
│ +500K   │ -250K   │ +250K   │   750K   │
├─────────────────────────────────────────┤
│ [+ Transaksi] [Laporan] [Import]        │
├─────────────────────────────────────────┤
│         TRANSAKSI TERBARU               │
│ Tgl  Kode   Santri  Desk  Kategori ... │
└─────────────────────────────────────────┘
```

### 2. **Input Transaksi (Auto-Kategorisasi)**
**Route:** `/keuangan-santri/create`

**Fitur Unggulan:**
- **Auto-detect kategori** saat mengetik deskripsi
- Algoritma keyword matching untuk kategorisasi otomatis
- Contoh:
  - "Beli sabun" → **Kebersihan & Kesehatan**
  - "Makan di kantin" → **Makanan & Minuman**
  - "Beli buku tulis" → **Pendidikan & Alat Tulis**
  - "Pulsa 50rb" → **Komunikasi & Pulsa**

**Form Fields:**
- Santri (dropdown)
- Jenis Transaksi (radio: Pemasukan/Pengeluaran)
- Tanggal
- Deskripsi (dengan live auto-detect)
- Kategori (auto-filled, bisa manual override)
- Jumlah (Rupiah)
- Metode Pembayaran (Tunai, Transfer, E-Wallet)
- Catatan (opsional)
- Upload Bukti (foto/PDF, opsional)

**Logika Auto-Detect:**
```php
// Service: KeuanganSantriService::detectCategory()
1. Ambil deskripsi transaksi
2. Lowercase & normalisasi
3. Loop setiap kategori & keywords
4. Jika keyword ditemukan di deskripsi → return kategori
5. Jika tidak ada match → return kategori "Lain-lain"
```

### 3. **Laporan & Filter**
**Route:** `/keuangan-santri/laporan/index`

**Filter Options:**
- Per Santri
- Jenis (Pemasukan/Pengeluaran)
- Kategori
- Periode (Hari/Minggu/Bulan/Tahun/Custom)
- Custom Date Range
- Search (Kode/Deskripsi)

**Export Options:**
- 📄 **Export PDF** → Format bank statement professional
- 📊 **Export Excel** → Full data dengan format rapi

### 4. **Export PDF (Bank Statement Style)**
**Route:** `/keuangan-santri/laporan/export-pdf`

**Desain Professional:**
- Header dengan logo & info periode
- Account info (Nama Santri, NIK, Saldo)
- Summary box (Total Pemasukan, Pengeluaran, Selisih)
- Transaction table dengan:
  - Tanggal, Kode, Deskripsi, Kategori
  - Jenis (badge), Jumlah (dengan warna), Saldo
  - Status verifikasi
- Footer dengan disclaimer

**Warna:**
- Pemasukan: Hijau (credit)
- Pengeluaran: Merah (debit)
- Header: Biru (brand color)

### 5. **Import Excel (Bulk Upload)**
**Route:** `/keuangan-santri/import/form`

**Flow:**
1. Download template Excel
2. Isi data transaksi (Tanggal, Deskripsi, Jumlah, Jenis, dll)
3. Upload file
4. Sistem akan:
   - Parse setiap baris
   - **Auto-detect kategori** berdasarkan deskripsi
   - Validasi data
   - Create transaksi
   - Update saldo otomatis
5. Tampilkan hasil: X transaksi ditambahkan, Y dilewati

**Format Template:**
```csv
Tanggal,Deskripsi,Jumlah,Jenis,Kategori,Metode Pembayaran,Catatan
01/11/2025,Uang saku bulan November,500000,Pemasukan,,Transfer Bank,Dari orangtua
02/11/2025,Beli sabun dan shampo,25000,Pengeluaran,,Tunai,
03/11/2025,Makan siang di kantin,15000,Pengeluaran,,Tunai,
```

**Validasi:**
- Tanggal harus valid
- Deskripsi & Jumlah wajib
- Kategori auto-detect jika kosong

### 6. **Verifikasi Transaksi**
**Route:** `/keuangan-santri/{id}/verify`

**Fitur:**
- Button verifikasi di detail transaksi
- Catat user & waktu verifikasi
- Badge "TERVERIFIKASI" di PDF & laporan

### 7. **Detail Transaksi**
**Route:** `/keuangan-santri/{id}`

**Tampilan:**
- Kode transaksi & tanggal
- Badge jenis & status verifikasi
- Nominal besar (focal point)
- Info santri, kategori, deskripsi
- Saldo sebelum & sesudah
- Bukti transaksi (view image/PDF)
- Audit trail (created by, updated by, verified by)

---

## 🔧 ALGORITMA AUTO-KATEGORISASI

### Cara Kerja:

**1. Keyword Matching**
```php
// Model: KeuanganSantriCategory::detectCategory()
public static function detectCategory($deskripsi, $jenis = 'pengeluaran')
{
    $deskripsi = strtolower($deskripsi);
    $categories = self::where('jenis', $jenis)->where('is_active', true)->get();

    foreach ($categories as $category) {
        if ($category->keywords) {
            foreach ($category->keywords as $keyword) {
                if (stripos($deskripsi, strtolower($keyword)) !== false) {
                    return $category; // Match found!
                }
            }
        }
    }

    // Default: Lain-lain
    return self::where('jenis', $jenis)->where('nama_kategori', 'LIKE', '%lain%')->first();
}
```

**2. Integration**
- **Di Form Input:** AJAX call saat user mengetik (debounce 500ms)
- **Di Import:** Auto-detect untuk setiap baris Excel
- **Manual Override:** User tetap bisa pilih kategori manual

**3. Keyword Database (JSON)**
Contoh keywords untuk **Kebersihan & Kesehatan**:
```json
[
  "sabun", "shampo", "pasta gigi", "sikat gigi", "detergen",
  "tissue", "handuk", "obat", "vitamin", "paracetamol",
  "batuk", "flu", "demam", "sakit", "antiseptik", "plaster",
  "minyak kayu putih", "balsem", "masker", "hand sanitizer"
]
```

---

## 📊 ALUR DATA & SALDO

### Flow Transaksi:

```
1. User Input Transaksi
   ↓
2. Controller: KeuanganSantriController::store()
   ↓
3. Service: KeuanganSantriService::createTransaction()
   ↓
4. Get/Create Saldo Santri
   ↓
5. Set saldo_sebelum = saldo_akhir_current
   ↓
6. Calculate saldo_sesudah:
   - Pemasukan: saldo_sebelum + jumlah
   - Pengeluaran: saldo_sebelum - jumlah
   ↓
7. Auto-detect kategori (jika tidak ada)
   ↓
8. Create Transaction Record
   ↓
9. Update Saldo Table:
   - total_pemasukan += jumlah (jika pemasukan)
   - total_pengeluaran += jumlah (jika pengeluaran)
   - saldo_akhir = updated
   - last_transaction_date = now
   ↓
10. Return Transaction
```

### Rollback Saldo (Delete/Update):
- Saat delete: Restore saldo sebelum transaksi
- Saat update: Rollback saldo lama → Apply saldo baru

---

## 🎨 UI/UX HIGHLIGHTS

### Color Scheme:
- **Primary:** Blue (#2563EB) - Headers, buttons
- **Success/Credit:** Green (#059669) - Pemasukan
- **Danger/Debit:** Red (#DC2626) - Pengeluaran
- **Gray:** Backgrounds & neutral elements

### Components:
- **Cards:** Stats dengan icon & warna
- **Badges:** Rounded pills untuk jenis & status
- **Tables:** Hover effects, alternating rows
- **Forms:** Tailwind CSS dengan validation feedback
- **Alerts:** Success/error messages dengan icon

### Responsive:
- Mobile-friendly grid system
- Collapsible filters di mobile
- Touch-friendly buttons

---

## 🔐 PERMISSIONS

Tambahkan di database (`permissions` table):

```php
// Permissions untuk Keuangan Santri
keuangan-santri.index    // View dashboard
keuangan-santri.create   // Tambah transaksi
keuangan-santri.edit     // Edit transaksi
keuangan-santri.delete   // Hapus transaksi
keuangan-santri.laporan  // Lihat & export laporan
keuangan-santri.import   // Import Excel
keuangan-santri.verify   // Verifikasi transaksi
```

**Assign ke Role:**
- Super Admin: All permissions
- Admin Keuangan: All except delete
- Staff: index, create, laporan

---

## 📁 FILE STRUCTURE

```
app/
├── Http/Controllers/
│   └── KeuanganSantriController.php
├── Models/
│   ├── KeuanganSantriCategory.php
│   ├── KeuanganSantriTransaction.php
│   └── KeuanganSantriSaldo.php
├── Services/
│   └── KeuanganSantriService.php
├── Exports/
│   └── KeuanganSantriExport.php
└── Imports/
    └── KeuanganSantriImport.php

database/
├── migrations/
│   ├── 2025_11_08_080000_create_keuangan_santri_categories_table.php
│   ├── 2025_11_08_080001_create_keuangan_santri_transactions_table.php
│   └── 2025_11_08_080002_create_keuangan_santri_saldo_table.php
└── seeders/
    └── KeuanganSantriCategorySeeder.php

resources/views/
└── keuangan-santri/
    ├── index.blade.php      // Dashboard
    ├── create.blade.php     // Form tambah
    ├── edit.blade.php       // Form edit
    ├── show.blade.php       // Detail
    ├── laporan.blade.php    // Halaman laporan
    ├── import.blade.php     // Halaman import
    └── pdf.blade.php        // Template PDF

public/templates/
└── template_import_keuangan_santri.csv
```

---

## 🚦 QUICK START

### 1. Jalankan Migration & Seeder
```bash
php artisan migrate
php artisan db:seed --class=KeuanganSantriCategorySeeder
```

### 2. Setup Permissions
```sql
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('keuangan-santri.index', 'web', NOW(), NOW()),
('keuangan-santri.create', 'web', NOW(), NOW()),
('keuangan-santri.edit', 'web', NOW(), NOW()),
('keuangan-santri.delete', 'web', NOW(), NOW()),
('keuangan-santri.laporan', 'web', NOW(), NOW()),
('keuangan-santri.import', 'web', NOW(), NOW()),
('keuangan-santri.verify', 'web', NOW(), NOW());
```

### 3. Assign ke Super Admin
```php
$role = Role::findByName('super admin');
$role->givePermissionTo([
    'keuangan-santri.index',
    'keuangan-santri.create',
    'keuangan-santri.edit',
    'keuangan-santri.delete',
    'keuangan-santri.laporan',
    'keuangan-santri.import',
    'keuangan-santri.verify',
]);
```

### 4. Akses Menu
- Login sebagai Super Admin
- Menu: **Manajemen Saung Santri** > **Keuangan Santri**

---

## 🎯 USE CASES

### Skenario 1: Santri Menerima Uang Saku
1. Pilih santri
2. Jenis: Pemasukan
3. Deskripsi: "Uang saku bulan November"
4. Jumlah: Rp 500.000
5. Auto-detect: **Uang Saku Orang Tua**
6. Save → Saldo bertambah

### Skenario 2: Santri Beli Sabun
1. Pilih santri
2. Jenis: Pengeluaran
3. Deskripsi: "Beli sabun mandi dan shampo"
4. Jumlah: Rp 25.000
5. Auto-detect: **Kebersihan & Kesehatan** ✅
6. Save → Saldo berkurang

### Skenario 3: Import Bulk Transaksi
1. Download template
2. Isi 50 transaksi di Excel
3. Upload
4. Sistem auto-detect kategori untuk setiap baris
5. 50 transaksi berhasil ditambahkan
6. Saldo santri terupdate otomatis

### Skenario 4: Generate Laporan PDF
1. Filter: Bulan November, Santri: Ahmad
2. Klik "Export PDF"
3. Download laporan bergaya bank
4. Gunakan untuk pertanggungjawaban ke orang tua

---

## 💡 TIPS & TRICKS

### Menambah Kategori Baru
1. Insert ke `keuangan_santri_categories`
2. Tambahkan keywords (JSON array)
3. Pilih icon Font Awesome & warna hex

### Custom Kategori Keyword
```sql
UPDATE keuangan_santri_categories
SET keywords = '["laptop", "komputer", "mouse", "keyboard"]'
WHERE nama_kategori = 'Elektronik';
```

### Bulk Verifikasi
Jika ingin verifikasi banyak transaksi sekaligus, bisa extend controller:
```php
public function bulkVerify(Request $request)
{
    $ids = $request->input('transaction_ids');
    KeuanganSantriTransaction::whereIn('id', $ids)->update([
        'is_verified' => true,
        'verified_by' => Auth::id(),
        'verified_at' => now(),
    ]);
}
```

---

## 📞 SUPPORT

Jika ada pertanyaan atau butuh kustomisasi:
- Dokumentasi: File ini
- Code: Lihat comment di Controller & Service
- Auto-detect: Cek `KeuanganSantriCategory::detectCategory()`

---

## ✅ CHECKLIST IMPLEMENTASI

- [x] Migration & Database
- [x] Models & Relationships
- [x] Seeder Kategori Default
- [x] Service Layer (Auto-detect & CRUD)
- [x] Controller dengan semua method
- [x] Views (Dashboard, Form, Laporan, Import, PDF)
- [x] Routes & Middleware
- [x] Auto-kategorisasi Algorithm
- [x] Export PDF (Bank Style)
- [x] Export Excel
- [x] Import Excel dengan validasi
- [x] Menu Navigasi
- [x] Template Import CSV
- [ ] Permissions di database (Manual)
- [ ] Testing dengan data dummy

---

## 🎉 SELAMAT!

Sistem **Keuangan Santri** telah berhasil diimplementasikan dengan fitur-fitur canggih:
- ✅ Auto-Kategorisasi Transaksi
- ✅ Laporan Bergaya Bank
- ✅ Import Bulk Data
- ✅ Tracking Saldo Real-time

**Enjoy managing your santri finances! 💰📊**
