# FITUR LEMBUR SETENGAH HARI - DOKUMENTASI

## Fitur Baru ✨

Sistem absensi tukang sekarang memiliki **3 pilihan lembur** (sebelumnya hanya Ya/Tidak):

### Pilihan Lembur:

1. **TIDAK LEMBUR** (Tombol Abu-abu)
   - Tidak ada bonus lembur
   - Total upah = Upah harian saja
   - Contoh: Rp 150.000

2. **LEMBUR FULL 8 JAM** (Tombol Merah)
   - Bonus lembur: **100%** dari tarif harian
   - Total upah = Upah harian + Bonus lembur
   - Contoh: Rp 150.000 + Rp 150.000 = **Rp 300.000**

3. **LEMBUR SETENGAH HARI 4 JAM** (Tombol Orange)
   - Bonus lembur: **50%** dari tarif harian
   - Total upah = Upah harian + Bonus lembur
   - Contoh: Rp 150.000 + Rp 75.000 = **Rp 225.000**

---

## Cara Menggunakan

### Di Halaman Kehadiran Tukang:

1. Klik tombol **Status Kehadiran** sampai **HIJAU (Hadir)**
2. Klik tombol **Lembur** untuk cycle:
   - **Abu-abu** (Tidak) → **Merah** (Full 8 Jam) → **Orange** (Setengah 4 Jam) → kembali ke **Abu-abu**
3. Total upah akan **otomatis dihitung** setiap kali diklik

### Kombinasi Status & Lembur:

| Status Kehadiran | Lembur | Upah Harian | Bonus Lembur | Total |
|-----------------|--------|-------------|--------------|-------|
| Hadir | Tidak | 100% | 0% | 100% |
| Hadir | Full | 100% | +100% | 200% |
| Hadir | Setengah | 100% | +50% | 150% |
| Setengah Hari | Tidak | 50% | 0% | 50% |
| Setengah Hari | Full | 50% | +100% | 150% |
| Setengah Hari | Setengah | 50% | +50% | 100% |
| Tidak Hadir | - | 0% | 0% | 0% |

**Catatan:** Tombol lembur otomatis disabled jika status = Tidak Hadir

---

## Perubahan Teknis

### 1. Database Migration
```php
// Kolom lembur diubah dari boolean ke enum
enum('lembur', ['tidak', 'full', 'setengah_hari'])->default('tidak')
```

### 2. Model KehadiranTukang
```php
// Logika perhitungan lembur
switch ($this->lembur) {
    case 'full':
        $this->upah_lembur = $tarifHarian; // 100%
        break;
    case 'setengah_hari':
        $this->upah_lembur = $tarifHarian * 0.5; // 50%
        break;
    case 'tidak':
    default:
        $this->upah_lembur = 0;
        break;
}
```

### 3. Controller
Toggle cycle: `tidak` → `full` → `setengah_hari` → `tidak`

### 4. View
- Tombol dengan warna berbeda untuk setiap state
- Notifikasi toast menampilkan jumlah bonus lembur
- Update real-time tanpa refresh halaman

---

## Rekap Kehadiran

Halaman rekap sekarang menampilkan 2 kolom terpisah:
- **Lembur Full** (badge merah)
- **Lembur 1/2** (badge orange)

Halaman detail menampilkan badge:
- **Full (8 Jam)** - merah
- **Setengah (4 Jam)** - orange
- **Tidak** - abu-abu

---

## Testing

### Skenario Test:

1. **Test Lembur Full:**
   ```
   Hadir (Rp 150.000) + Lembur Full (Rp 150.000) = Rp 300.000 ✓
   ```

2. **Test Lembur Setengah:**
   ```
   Hadir (Rp 150.000) + Lembur Setengah (Rp 75.000) = Rp 225.000 ✓
   ```

3. **Test Setengah Hari + Lembur Full:**
   ```
   Setengah Hari (Rp 75.000) + Lembur Full (Rp 150.000) = Rp 225.000 ✓
   ```

4. **Test Setengah Hari + Lembur Setengah:**
   ```
   Setengah Hari (Rp 75.000) + Lembur Setengah (Rp 75.000) = Rp 150.000 ✓
   ```

---

## Update Date
- **Tanggal:** 10 November 2025
- **Migration:** `2025_11_10_100000_modify_lembur_in_kehadiran_tukangs.php`
- **Status:** ✅ Completed & Tested

---

## File yang Diubah

1. ✅ `database/migrations/2025_11_10_100000_modify_lembur_in_kehadiran_tukangs.php`
2. ✅ `app/Models/KehadiranTukang.php`
3. ✅ `app/Http/Controllers/KehadiranTukangController.php`
4. ✅ `resources/views/manajemen-tukang/kehadiran/index.blade.php`
5. ✅ `resources/views/manajemen-tukang/kehadiran/rekap.blade.php`
6. ✅ `resources/views/manajemen-tukang/kehadiran/detail.blade.php`

---

## 🎉 Fitur Siap Digunakan!

Silakan buka halaman **Kehadiran Tukang** dan coba klik tombol lembur untuk melihat 3 pilihan yang tersedia.
