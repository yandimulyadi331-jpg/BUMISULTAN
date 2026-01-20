# ⚡ QUICK DEBUG - CEK CICILAN KE-3

## 🔍 APA MASALAHNYA?

Dari screenshot:
- Pinjaman: Rp 5.000.000
- Tenor: 3 bulan
- Cicilan/bulan: Rp 2.000.000
- **Problem**: Cicilan ke-3 (Rp 1 juta) mungkin tidak terbuat

---

## 🚀 SOLUSI CEPAT

### Step 1: Buka Database

Gunakan **phpMyAdmin** atau **MySQL CLI**:

```bash
# Via Terminal
mysql -u root -p bumisultan_db
```

### Step 2: Cek Cicilan

Copy-paste query ini:

```sql
SELECT 
  cicilan_ke, 
  jumlah_cicilan, 
  jumlah_dibayar, 
  sisa_cicilan, 
  status
FROM pinjaman_cicilan 
WHERE pinjaman_id = 18 
ORDER BY cicilan_ke;
```

### Step 3: Lihat Hasilnya

**Jika output seperti ini (WRONG):**
```
cicilan_ke | jumlah_cicilan | jumlah_dibayar | sisa_cicilan | status
-----------|----------------|----------------|--------------|----------
1          | 2000000        | 2000000        | 0            | lunas
2          | 2000000        | 0              | 2000000      | belum_bayar
```

**Hanya ada 2 cicilan, yang Rp 1 juta hilang!** ❌

---

### Step 4: Fix - Regenerate Cicilan

Jalankan query ini untuk **hapus yang lama dan buat ulang**:

```sql
-- 1. Hapus cicilan lama
DELETE FROM pinjaman_cicilan WHERE pinjaman_id = 18;

-- 2. Buat ulang dengan generate jadwal dari aplikasi
-- (Atau manual insert di bawah)

-- MANUAL INSERT (jika perlu cepat):
INSERT INTO pinjaman_cicilan (
  pinjaman_id, cicilan_ke, tanggal_jatuh_tempo,
  jumlah_pokok, jumlah_bunga, jumlah_cicilan,
  sisa_cicilan, status, created_at, updated_at
) VALUES
(18, 1, '2026-01-20', 2000000, 0, 2000000, 0, 'lunas', NOW(), NOW()),
(18, 2, '2026-02-20', 2000000, 0, 2000000, 2000000, 'belum_bayar', NOW(), NOW()),
(18, 3, '2026-03-20', 1000000, 0, 1000000, 1000000, 'belum_bayar', NOW(), NOW());
```

### Step 5: Verify Fix

Query lagi untuk lihat:

```sql
SELECT cicilan_ke, jumlah_cicilan, status
FROM pinjaman_cicilan 
WHERE pinjaman_id = 18 
ORDER BY cicilan_ke;
```

**Expected Output (CORRECT):**
```
cicilan_ke | jumlah_cicilan | status
-----------|----------------|----------
1          | 2000000        | lunas
2          | 2000000        | belum_bayar
3          | 1000000        | belum_bayar
```

### Step 6: Refresh Browser

- Go back to pinjaman/18
- Refresh page (F5)
- Should now show 3 cicilan dengan:
  - Cicilan 1: LUNAS
  - Cicilan 2: Belum bayar (Rp 2M)
  - Cicilan 3: Belum bayar (Rp 1M)

---

## ✅ VERIFIKASI LENGKAP

Jalankan query ini untuk **full check**:

```sql
-- Check pinjaman
SELECT 
  total_pinjaman, 
  cicilan_per_bulan, 
  tenor_bulan,
  total_terbayar, 
  sisa_pinjaman 
FROM pinjaman WHERE id = 18;

-- Check cicilan breakdown
SELECT 
  SUM(jumlah_cicilan) as total_cicilan,
  SUM(jumlah_dibayar) as total_dibayar,
  SUM(sisa_cicilan) as total_sisa
FROM pinjaman_cicilan WHERE pinjaman_id = 18;

-- MUST MATCH:
-- total_cicilan = 5000000 ✓
-- total_terbayar = 2000000 ✓
-- total_sisa = 3000000 ✓
```

---

## 🎯 JIKA MASIH ERROR?

Check di file: `app/Models/Pinjaman.php` line 256

```php
for ($i = 1; $i <= $this->tenor_bulan; $i++) {
    // ...
    if ($i < $this->tenor_bulan) {
        $nominalCicilan = $cicilanNormal; // Rp 2M untuk cicilan 1-2
    } else {
        // ← Ini harus jadi cicilan terakhir
        $nominalCicilan = $cicilanTerakhir; // Rp 1M untuk cicilan 3
    }
}
```

Logika sudah benar, tapi jika ada bug, hubungi developer untuk fix di `generateJadwalCicilan()` method.

---

**Done!** Sekarang cicilan ke-3 harus ada dan sistem siap untuk pelunasan awal! ✅

