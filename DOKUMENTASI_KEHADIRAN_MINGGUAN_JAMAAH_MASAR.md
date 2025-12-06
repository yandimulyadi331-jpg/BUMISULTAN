# DOKUMENTASI FITUR KEHADIRAN MINGGUAN JAMAAH MASAR

## 📋 Ringkasan Fitur

Sistem **Kehadiran Mingguan Jamaah Masar** mencatat jumlah kehadiran jamaah berdasarkan jadwal tetap pengajian **setiap hari Jumat**. Sistem hanya mencatat kehadiran mingguan ketika operator melakukan proses **"Get Data Kehadiran Jamaah"** pada hari Jumat.

---

## 🔄 Alur Kerja

### 1. **Scan Fingerprint di Mesin**
- Jamaah datang pada hari Jumat
- Melakukan scan sidik jari di mesin fingerprint
- Data mentah tersimpan di mesin

### 2. **Operator Klik "Get Data Kehadiran Jamaah"**
- Aplikasi otomatis menarik log attendance dari mesin
- Untuk setiap log yang masuk:
  - ✅ Sistem cek apakah hari ini adalah **HARI JUMAT**
  - ✅ Jika YA, sistem memproses kehadiran
  - ✅ Jika TIDAK, ditampilkan warning

### 3. **Proses Kehadiran Mingguan**
- Sistem cek minggu ke berapa dalam tahun berjalan (ISO 8601: minggu 1-52)
- Cek apakah jamaah sudah punya record untuk minggu & tahun itu
- **Jika belum ada:**
  - Buat record baru di tabel `jumlah_kehadiran_mingguan`
  - Tambah counter `jumlah_kehadiran` = 1
  - Increment `jumlah_kehadiran` di tabel `jamaah_masar`
- **Jika sudah ada:**
  - Skip (sudah tercatat sebelumnya, hanya 1 scan per minggu)

### 4. **Multiple Scan di Hari Jumat**
- Meskipun jamaah scan **berkali-kali** pada hari Jumat yang sama
- Sistem tetap hanya mencatat **1 kehadiran** untuk minggu itu
- Tidak ada increment counter tambahan

---

## 📊 Database Schema

### Tabel: `jumlah_kehadiran_mingguan`

```sql
CREATE TABLE jumlah_kehadiran_mingguan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    jamaah_id BIGINT NOT NULL FOREIGN KEY (jamaah_masar.id),
    tahun SMALLINT NOT NULL,                    -- 2025
    minggu_ke SMALLINT NOT NULL,                -- 1-52
    jumlah_kehadiran SMALLINT DEFAULT 1,        -- Biasanya 1 atau 0
    tanggal_kehadiran DATE NULL,                -- Tanggal hari Jumat
    last_updated TIMESTAMP NULL,                -- Terakhir diupdate kapan
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    UNIQUE KEY unique_jamaah_year_week (jamaah_id, tahun, minggu_ke),
    INDEX (jamaah_id),
    INDEX (tahun, minggu_ke),
    INDEX (tanggal_kehadiran)
);
```

---

## 🔧 Model & Helper Methods

### Class: `JumlahKehadiranMingguan`

**Location:** `app/Models/JumlahKehadiranMingguan.php`

#### Static Methods:

```php
// Dapatkan minggu ke berapa tanggal tertentu (ISO 8601)
$minggu = JumlahKehadiranMingguan::getMingguKe($date);

// Cek apakah tanggal adalah hari Jumat
$is_friday = JumlahKehadiranMingguan::isJumat($date);

// Dapatkan nama hari dalam Bahasa Indonesia
$hari = JumlahKehadiranMingguan::getNamaHari($date);

// Total kehadiran jamaah dalam tahun tertentu
$total = JumlahKehadiranMingguan::getTotalKehadiranTahun($jamaah_id, $tahun);
```

#### Scope Methods:

```php
// Filter by tahun
$kehadiran = JumlahKehadiranMingguan::ofYear(2025)->get();

// Filter by jamaah
$kehadiran = JumlahKehadiranMingguan::ofJamaah($jamaah_id)->get();
```

---

## 📱 Controller Flow

### File: `app/Http/Controllers/JamaahMasarController.php`

#### Method: `updatefrommachine()`

**Location:** Line 687-803

**Flow:**
1. Receive fingerprint data dari mesin
2. Encrypt/decrypt PIN
3. **CEK HARI JUMAT** ← NEW!
4. Create/update kehadiran harian
5. **CREATE/CHECK kehadiran mingguan** ← NEW!
6. Increment total kehadiran

**Contoh Response:**

```
✓ Berhasil simpan JAM MASUK untuk YANDI MULYADI | Kehadiran mingguan (Minggu ke-49) tercatat.
```

atau jika sudah tercatat:

```
✓ Berhasil simpan JAM MASUK untuk YANDI MULYADI | Kehadiran minggu ini sudah tercatat sebelumnya.
```

---

## 👁️ View/UI

### File: `resources/views/masar/jamaah/show.blade.php`

**Bagian Baru: "Rekapitulasi Kehadiran Mingguan Tahun 2025"**

Menampilkan:

| Minggu Ke | Tanggal Jumat | Kehadiran | Status |
|-----------|---------------|-----------|--------|
| Minggu 1  | 03 Jan 2025   | ✓ 1x Hadir | Updated: 03 Jan 09:30 |
| Minggu 2  | 10 Jan 2025   | ✓ 1x Hadir | Updated: 10 Jan 08:15 |
| ...       | ...           | ...       | ... |

**Statistik:**
- Total Kehadiran: 23 / 52 minggu
- Progress Bar: 44.2%

---

## 📝 Relasi Model

### JamaahMasar

```php
// Kehadiran harian
$jamaah->kehadiran()  // HasMany KehadiranJamaahMasar

// Kehadiran mingguan (NEW)
$jamaah->kehadiranMingguan()  // HasMany JumlahKehadiranMingguan
```

### JumlahKehadiranMingguan

```php
// Relation ke Jamaah
$kehadiran_mingguan->jamaah()  // BelongsTo JamaahMasar
```

---

## 🧪 Testing Scripts

### 1. Test Model Methods

**File:** `test_kehadiran_mingguan.php`

```bash
php test_kehadiran_mingguan.php
```

Output:
```
=== TEST KEHADIRAN MINGGUAN ===

1. Cek Hari Ini:
   Hari: Selasa
   Tanggal: 2025-12-02
   Minggu ke: 49
   Apakah Jumat? TIDAK

2. Cari Jumat Terdekat:
   Jumat Terdekat: 2025-12-05 (Fri)
   Minggu Ke: 49

3. Data Kehadiran Mingguan yang Ada:
   (Belum ada data)

✓ Test selesai
```

### 2. Simulasi Get Data

**File:** `simulasi_get_data.php`

```bash
php simulasi_get_data.php
```

Output:
```
=== SIMULASI GET DATA KEHADIRAN JAMAAH ===

1. Data Jamaah:
   ID: 2
   Nama: YANDI MULYADI
   PIN: 001
   Total Kehadiran: 23

2. Jumat Terdekat:
   Tanggal: 2025-12-05 (Fri)
   Minggu Ke: 49
   Tahun: 2025

3. Simulasi Fingerprint pada hari Jumat:
   ✓ Kehadiran harian dibuat: ID 1

4. Proses Kehadiran Mingguan:
   ✓ Kehadiran mingguan dibuat: ID 1
   ✓ Total kehadiran jamaah updated: 24

5. Hasil Akhir:
   Total Kehadiran Jamaah: 24

6. Kehadiran Mingguan Tahun 2025:
   Minggu 49: 05 Dec 2025 - Status: HADIR (1x)

✓ Simulasi selesai
```

---

## 🚨 Warning Messages

### Jika User Mencoba Get Data Pada Hari Bukan Jumat

```
⚠️ Kehadiran mingguan hanya dicatat pada hari Jumat. Hari ini adalah hari Selasa.
```

### Jika Jamaah Sudah Tercatat Minggu Ini

```
ℹ️ Kehadiran minggu ini sudah tercatat sebelumnya.
```

---

## 💾 Migration File

**File:** `database/migrations/2025_12_02_create_jumlah_kehadiran_mingguan_table.php`

- Membuat tabel `jumlah_kehadiran_mingguan`
- Unique constraint: `jamaah_id + tahun + minggu_ke`
- Soft delete enabled

---

## 🔍 Query Examples

### Get semua kehadiran jamaah tahun ini

```php
$kehadiran = $jamaah->kehadiranMingguan()
    ->where('tahun', date('Y'))
    ->orderBy('minggu_ke', 'asc')
    ->get();
```

### Get total kehadiran per bulan

```php
$perBulan = DB::table('jumlah_kehadiran_mingguan')
    ->select(DB::raw('MONTH(tanggal_kehadiran) as bulan, COUNT(*) as total'))
    ->where('jamaah_id', $jamaah_id)
    ->where('tahun', 2025)
    ->groupBy('bulan')
    ->get();
```

### Get kehadiran mingguan yang belum ada (untuk report)

```php
$mingguTanpaKehadiran = DB::table('jumlah_kehadiran_mingguan')
    ->where('jamaah_id', $jamaah_id)
    ->where('tahun', 2025)
    ->where('jumlah_kehadiran', 0)
    ->get();
```

---

## 📌 Catatan Penting

1. **ISO 8601 Week Format**: Minggu dimulai hari Senin, berakhir hari Minggu
2. **Unique Per Minggu**: Satu jamaah tidak bisa punya 2 record untuk minggu & tahun yang sama
3. **Soft Delete**: Data tidak dihapus, hanya di-flag sebagai deleted
4. **Auto Increment**: Field `jumlah_kehadiran` selalu 1 (karena 1 minggu = maksimal 1 kehadiran)
5. **Performance**: Ada index pada `jamaah_id`, `tahun`, `minggu_ke`, dan `tanggal_kehadiran`

---

## ✅ Checklist Implementasi

- ✅ Migration table dibuat
- ✅ Model `JumlahKehadiranMingguan` dibuat
- ✅ Relasi ke `JamaahMasar` ditambahkan
- ✅ Logic di `updatefrommachine()` dimodifikasi
- ✅ View detail jamaah ditambahkan section kehadiran mingguan
- ✅ Helper methods untuk cek hari, minggu ditambahkan
- ✅ Test scripts dibuat

---

## 🎯 Fitur Dapat Dikembangkan

1. **Laporan Kehadiran Mingguan**: Export ke Excel
2. **Notifikasi**: SMS/WA jika jamaah tidak hadir 3+ minggu berturut-turut
3. **Statistik Mingguan**: Dashboard visualisasi kehadiran
4. **Pemberian Hadiah**: Berdasarkan target kehadiran (misal: 48 minggu/tahun)
5. **Denda**: Untuk jamaah yang sering tidak hadir
6. **Target Kehadiran**: Setting target kehadiran mingguan per jamaah

---

**Terakhir Update:** 02 December 2025
**Status:** ✅ PRODUCTION READY
