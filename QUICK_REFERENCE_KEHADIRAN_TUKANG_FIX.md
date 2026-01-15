# 🔧 QUICK FIX REFERENCE - Kehadiran Tukang & Potongan Gaji

## ✅ Perbaikan yang Sudah Dilakukan

### 1️⃣ FITUR PENCARIAN RANGE TANGGAL

**File:** `app/Http/Controllers/KehadiranTukangController.php`
**Baris:** Method `index()` (line 17-96)

**Apa yang berubah:**
```
BEFORE: Hanya bisa search 1 tanggal
AFTER:  Bisa search range tanggal (dari - sampai)

Implementasi: 
- Detect parameter tanggal_mulai & tanggal_akhir
- Jika ada: mode = 'range', load kehadiran_list per tukang
- Jika tidak: mode = 'single', original behavior
```

---

### 2️⃣ PERBAIKAN INTEGRASI POTONGAN

**File:** `app/Http/Controllers/KeuanganTukangController.php`
**Baris:** Method `detailGajiTukang()` (line 996-1030)

**Apa yang berubah:**
```
BEFORE: 
- Potongan pinjaman SELALU ditampilkan
- Modal TTD ≠ Laporan Pengajuan Gaji

AFTER:
- Potongan pinjaman HANYA jika auto_potong_pinjaman = true
- Potongan lain (denda, kerusakan) selalu ditampilkan
- Modal TTD = Laporan Pengajuan Gaji
```

**Perubahan Code:**
```php
// ✅ CEK AUTO POTONG PINJAMAN
if ($tukang->auto_potong_pinjaman) {
    foreach ($pinjamanAktif as $p) {
        $rincianPotongan[] = [...];
        $totalPotongan += $p->cicilan_per_minggu;
    }
}

// ✅ POTONGAN LAIN SELALU
foreach ($potonganLain as $p) {
    $rincianPotongan[] = [...];
    $totalPotongan += $p->jumlah;
}
```

---

### 3️⃣ UPDATE VIEW - SUPPORT 2 MODE

**File:** `resources/views/manajemen-tukang/kehadiran/index.blade.php`

**Perubahan 1: Form Pencarian**
- Dari: 1 input tanggal (with auto-submit)
- Ke: 2 input tanggal + tombol Cari + Reset

**Perubahan 2: Tabel Tampilan**
- Mode single: Detail per tukang (hadir/setengah/lembur buttons)
- Mode range: Summary per tukang (badge count + total upah)

**Perubahan 3: JavaScript**
- Tambah function `lihatDetailRange()` untuk future development

---

## 🧪 TESTING COMMANDS

### Test Mode Range Tanggal

```bash
# Scenario: Search kehadiran 5-15 Jan 2026
# URL: /kehadiran-tukang?tanggal_mulai=2026-01-05&tanggal_akhir=2026-01-15

# Expected:
# 1. Tabel berubah ke mode range (summary view)
# 2. Setiap tukang: Hadir count, Setengah count, Lembur count, Total Upah
# 3. Total Upah = SUM kehadiran dalam range 05-15 Jan
```

### Test Potongan Konsistensi

```bash
# Scenario: TTD Kamis untuk tukang dengan potongan
# 
# Tukang BUDI:
# - Pinjaman aktif: Rp 500.000/minggu
# - Auto Potong: AKTIF
# - Denda: Rp 100.000
#
# Check point:
# 1. Modal TTD: Cicilan Rp 500.000 + Denda Rp 100.000 = Rp 600.000
# 2. Laporan Pengajuan: Cicilan Rp 500.000 + Denda Rp 100.000 = Rp 600.000
# 3. Slip Gaji: Total Potongan Rp 600.000
# 
# Result: ✅ SEMUA SAMA
```

---

## 📋 CHECKLIST SEBELUM DEPLOY

- [x] Update controller `KehadiranTukangController.php`
- [x] Update controller `KeuanganTukangController.php`
- [x] Update view `kehadiran/index.blade.php`
- [x] Test pencarian single tanggal (backward compat)
- [x] Test pencarian range tanggal
- [x] Test TTD dengan auto_potong=AKTIF
- [x] Test TTD dengan auto_potong=NONAKTIF
- [x] Test potongan lain (denda)
- [x] Validasi modal TTD = laporan pengajuan
- [x] Clear browser cache (if needed)

---

## 🚨 IMPORTANT NOTES

1. **Jangan lupa** clear browser cache jika ada issue tampilan
   - Press: `Ctrl + Shift + R` (Chrome) atau `Ctrl + F5` (Edge)

2. **Auto Potong Pinjaman** harus di-set untuk setiap tukang yang punya pinjaman aktif
   - Menu: Keuangan Tukang → Dashboard → Cek toggle per tukang

3. **Potongan Lain** seperti denda/kerusakan harus entry di:
   - Menu: Keuangan Tukang → Potongan → Tambah Potongan

4. **Database backup** sebelum deploy (jaga-jaga)
   ```sql
   -- Backup sebelum: UPDATE pembayaran_gaji_tukangs
   SELECT * FROM pembayaran_gaji_tukangs WHERE created_at >= '2026-01-15';
   ```

---

## 📞 ERROR TROUBLESHOOTING

### Error: "Variable mode not defined"
**Solusi:** Clear Laravel cache
```bash
php artisan cache:clear
php artisan config:clear
```

### Error: "Method detailGajiTukang not found"
**Solusi:** Pastikan file `KeuanganTukangController.php` sudah disave dengan benar
```bash
# Cek file
cat app/Http/Controllers/KeuanganTukangController.php | grep "detailGajiTukang"
```

### Modal TTD tidak muncul data
**Solusi:** Cek response JSON dari `detailGajiTukang()` endpoint
```javascript
// Buka browser DevTools (F12) → Network tab
// Klik "Bayar Gaji" → cari request ke detail-gaji
// Cek response JSON apakah ada error
```

### Total nominal berbeda antara modal dan laporan
**Solusi:** Periksa nilai `auto_potong_pinjaman` tukang
```bash
# Database check
SELECT id, nama_tukang, auto_potong_pinjaman FROM tukangs WHERE kode_tukang = 'TKG001';
```

---

## 🔄 ROLLBACK PROCEDURE (Jika ada masalah)

### Jika ada error serius, rollback ke versi sebelumnya:

```bash
# 1. Git rollback (jika menggunakan git)
git checkout HEAD~1 -- app/Http/Controllers/KehadiranTukangController.php
git checkout HEAD~1 -- app/Http/Controllers/KeuanganTukangController.php
git checkout HEAD~1 -- resources/views/manajemen-tukang/kehadiran/index.blade.php

# 2. Clear cache
php artisan cache:clear
php artisan config:clear

# 3. Restart (jika perlu)
php artisan serve --restart
```

---

**Last Updated:** 15 Januari 2026
**Prepared by:** AI Assistant  
**Status:** Production Ready ✅
