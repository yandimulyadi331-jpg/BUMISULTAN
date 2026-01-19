# DEBUG SCRIPT - Verifikasi Points di Halaman Karyawan

## Kemungkinan Masalah & Solusi

### 1️⃣ **Migration Belum Berjalan**
Cek apakah kolom `points` sudah ada di table `master_perawatan`:

```bash
# Di terminus:
php artisan tinker
>>> DB::table('master_perawatan')->first();
# Lihat apakah ada column: points, point_description
>>> exit
```

**Jika tidak ada, jalankan:**
```bash
php artisan migrate
```

---

### 2️⃣ **Cache Tidak Ter-Clear**
Laravel sering cache view lama:

```bash
php artisan optimize:clear
php artisan view:cache
php artisan config:cache
```

---

### 3️⃣ **File View Belum Terupdate**
Pastikan file sudah di-pull dari GitHub:

```bash
# Di terminus:
git status
# Seharusnya "working tree clean"

# Jika masih dirty, stash dan pull ulang:
git stash
git pull origin main
```

---

### 4️⃣ **Cek Data Master Perawatan**
Lihat apakah ada data dengan points yang sudah disimpan:

```bash
# Di terminus (php artisan tinker):
>>> DB::table('master_perawatan')->where('points', '>', 0)->get();
# Jika kosong, input data dengan points

>>> $master = App\Models\MasterPerawatan::first();
>>> $master->update(['points' => 5, 'point_description' => 'Test points']);
>>> exit
```

---

### 5️⃣ **Test di Browser**
1. Buka `/perawatan/karyawan/checklist`
2. Jika points tidak tampil:
   - Buka DevTools (F12)
   - Lihat Console untuk error
   - Cek Network untuk melihat apakah HTML berisi `⭐ X pts`

---

### 6️⃣ **Syntax Error di View**
Ada 1 character yang aneh di diff: `Γ¡É` seharusnya `⭐`

**Mari kita periksa dan fix jika perlu:**
```bash
# Di lokal (VSCode):
grep -n "Γ¡É" resources/views/perawatan/karyawan/checklist.blade.php
# Jika ada, replace dengan ⭐
```

---

## ✅ FULL DEBUGGING STEPS DI TERMINUS

```bash
# 1. SSH ke terminus
ssh u722741035@id-dci-web1986

# 2. Navigate ke project
cd domains/bumisultan.site/BUMISULTAN

# 3. Check git status
git status
git log --oneline -3

# 4. Pull latest (jika belum)
git pull origin main

# 5. Check migration
php artisan migrate --pretend
# atau
php artisan migrate

# 6. Verify database
php artisan tinker
DB::table('master_perawatan')->select('id', 'nama_kegiatan', 'points', 'point_description')->where('points', '>', 0)->get()
exit

# 7. Clear all cache
php artisan optimize:clear

# 8. Check file
grep "⭐" resources/views/perawatan/karyawan/checklist.blade.php
# Should show: ⭐ {{ $checklist->points }} pts

# 9. Rebuild cache
php artisan view:cache
php artisan config:cache

# 10. Check permissions
chmod -R 755 storage bootstrap/cache
```

---

## 🔍 JIKA MASIH TIDAK MUNCUL

Share output dari:
```bash
# Di terminus:
php artisan tinker
>>> DB::table('master_perawatan')->where('points', '>', 0)->first();
>>> exit

# Lihat output lengkap, terutama:
# - Apakah column "points" ada?
# - Apakah ada data dengan points > 0?
```

---

## 📝 File yang Berubah

Pastikan file-file ini sudah terupdate di hosting:
- ✅ `resources/views/perawatan/karyawan/checklist.blade.php` (2 lokasi, lines 1086 & 1183)
- ✅ `app/Models/MasterPerawatan.php` (tambah 'points' ke fillable)
- ✅ `app/Models/PerawatanLog.php` (tambah 'points_earned' ke fillable)
- ✅ `database/migrations/2026_01_19_add_points_to_master_perawatan.php`

---

**Last Updated:** 19 Januari 2026
**Status:** Ready untuk debugging
