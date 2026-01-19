# 🚀 DEPLOYMENT GUIDE - Migration Perawatan ke Production

**Issue:** SQLSTATE[42S22] - Column not found `ruangan_id` di online  
**Root Cause:** Migration belum dijalankan di database production  
**Status:** Ready to deploy  

---

## 📋 STEP-BY-STEP DEPLOYMENT

### **Option A: Via SSH Terminal (RECOMMENDED)**

#### 1️⃣ Connect ke Server
```bash
ssh your_user@your_host
cd /path/to/bumisultanAPP/bumisultanAPP
```

#### 2️⃣ Verify Current State
```bash
# Check migration status
php artisan migrate:status

# Cek apakah column ruangan_id ada
mysql -u username -p database_name -e "DESCRIBE master_perawatan;"
```

**Expected Output:**
```
Pending:
- 2026_01_19_142559_add_ruangan_id_to_master_perawatan_table
- 2026_01_19_add_points_to_master_perawatan
```

#### 3️⃣ Run Migration
```bash
# Jalankan semua pending migrations
php artisan migrate

# Output success akan terlihat:
# Migrating: 2026_01_19_142559_add_ruangan_id_to_master_perawatan_table
# Migrated: 2026_01_19_142559_add_ruangan_id_to_master_perawatan_table (35ms)
# Migrating: 2026_01_19_add_points_to_master_perawatan
# Migrated: 2026_01_19_add_points_to_master_perawatan (45ms)
```

#### 4️⃣ Verify Database Columns
```bash
# Check columns sudah ada
mysql -u username -p database_name -e "DESCRIBE master_perawatan;"

# Harusnya muncul:
# - ruangan_id
# - points
# - point_description
```

```bash
# Check perawatan_log juga
mysql -u username -p database_name -e "DESCRIBE perawatan_log;"

# Harusnya muncul:
# - points_earned
```

#### 5️⃣ Clear Cache
```bash
php artisan cache:clear
php artisan config:cache
```

#### 6️⃣ Test Update
```bash
# Test via tinker
php artisan tinker

# Coba query
> $master = App\Models\MasterPerawatan::find(320);
> $master->ruangan_id = null;
> $master->points = 5;
> $master->save();
> exit
```

---

### **Option B: Via cPanel / Hosting Dashboard**

#### 1️⃣ Access File Manager
- Login ke cPanel
- Buka Terminal atau File Manager

#### 2️⃣ Navigate & Run
```bash
cd public_html/bumisultanAPP  # atau path yang sesuai
php artisan migrate
```

#### 3️⃣ Check Log
```bash
# Lihat error log jika ada
tail -f storage/logs/laravel.log
```

---

### **Option C: Via Laravel Artisan Commands (Safety Mode)**

Jalankan command ini untuk minimal risk:

```bash
# Dry run - lihat apa yang akan dilakukan tanpa apply
php artisan migrate --pretend

# Jalankan dengan force flag jika di production
php artisan migrate --force

# Specific migration saja
php artisan migrate --path=/database/migrations/2026_01_19_142559_add_ruangan_id_to_master_perawatan_table.php
```

---

## 🔍 VERIFICATION CHECKLIST

Setelah migration, verify dengan commands ini:

```bash
# 1. Check migration status
php artisan migrate:status

# Expected: 2026_01_19 migrations should show as [206] Ran dan [207] Ran

# 2. Database structure
mysql -u user -p database -e "SHOW COLUMNS FROM master_perawatan WHERE Field IN ('ruangan_id', 'points', 'point_description');"

# Output expected:
# Field              | Type             | Null | Key | Default | Extra
# ruangan_id         | bigint unsigned  | YES  |     | NULL    |
# points             | int              | NO   |     | 1       |
# point_description  | longtext         | YES  |     | NULL    |

# 3. Test update operation
php artisan tinker
> $master = \App\Models\MasterPerawatan::find(320);
> $master->update(['ruangan_id' => null, 'points' => 5, 'point_description' => 'Test']);
> exit

# 4. Clear cache & restart
php artisan cache:clear
php artisan config:cache
```

---

## 📊 MIGRATION DETAILS

### Migration 1: Add Ruangan ID
**File:** `2026_01_19_142559_add_ruangan_id_to_master_perawatan_table.php`

**What it does:**
```sql
ALTER TABLE master_perawatan 
ADD COLUMN ruangan_id BIGINT UNSIGNED NULL 
    COMMENT 'Ruangan yang mempunyai perawatan ini'
    AFTER kategori;

ALTER TABLE master_perawatan 
ADD FOREIGN KEY (ruangan_id) 
REFERENCES ruangans(id) 
ON DELETE SET NULL;
```

**Rollback:**
```bash
php artisan migrate:rollback --step=1
# Removes ruangan_id column and foreign key
```

---

### Migration 2: Add Points System
**File:** `2026_01_19_add_points_to_master_perawatan.php`

**What it does:**
```sql
-- master_perawatan table
ALTER TABLE master_perawatan 
ADD COLUMN points INT DEFAULT 1 
    COMMENT 'Poin untuk pekerjaan, 1=ringan, 5=sedang, 10+=berat';

ALTER TABLE master_perawatan 
ADD COLUMN point_description LONGTEXT NULL 
    COMMENT 'Deskripsi alasan pemberian point ini';

-- perawatan_log table
ALTER TABLE perawatan_log 
ADD COLUMN points_earned INT DEFAULT 0 
    COMMENT 'Points yang didapat saat melakukan checklist ini';
```

**Rollback:**
```bash
php artisan migrate:rollback --step=1
# Removes points columns
```

---

## ⚠️ CRITICAL POINTS

| Item | Status | Notes |
|------|--------|-------|
| **Backward Compatible** | ✅ YES | All columns nullable/has default |
| **Data Loss Risk** | ✅ NONE | Existing data not affected |
| **Rollback Safe** | ✅ YES | Migration reversible |
| **Performance Impact** | ✅ MINIMAL | No table restructuring |

---

## 🐛 TROUBLESHOOTING

### Error: "Foreign key constraint fails"
**Solution:**
```bash
# Disable foreign key checks temporarily
mysql -u user -p database
SET FOREIGN_KEY_CHECKS = 0;
-- Run migration manually
SET FOREIGN_KEY_CHECKS = 1;
EXIT;
```

### Error: "Syntax error in SQL statement"
**Solution:**
```bash
# Check MySQL version compatibility
php artisan tinker
> DB::select('SELECT VERSION();')
exit

# Ensure Laravel version supports this syntax
php artisan --version
```

### Error: "Unknown column in 'on clause'"
**Solution:**
```bash
# Check if ruangans table exists
mysql -u user -p database -e "SHOW TABLES LIKE 'ruangans';"

# If not exist, run migration untuk ruangan terlebih dahulu
php artisan migrate --path=/database/migrations/*ruangan*
```

### Update masih error setelah migration
**Solution:**
```bash
# Clear model cache
php artisan model:cache
php artisan view:clear
php artisan cache:clear

# Restart PHP
# (Contact hosting untuk restart PHP-FPM)
```

---

## 📱 PUSH & DEPLOYMENT SEQUENCE

```bash
# 1. Local: Commit all changes
git add .
git commit -m "feat: Add ruangan_id and points system to perawatan"
git push origin main

# 2. Server: Pull latest code
cd /path/to/app
git pull origin main

# 3. Server: Run migrations
php artisan migrate

# 4. Server: Verify
php artisan migrate:status
php artisan tinker  # Test update

# 5. Server: Clear cache
php artisan cache:clear
php artisan config:cache
```

---

## 🔐 PRODUCTION CHECKLIST

Before marking as DONE:

- [ ] SSH into server
- [ ] Verify MySQL version >= 5.7
- [ ] Backup database: `mysqldump -u user -p database > backup_$(date +%Y%m%d_%H%M%S).sql`
- [ ] Run `php artisan migrate`
- [ ] Check migration status
- [ ] Test update operation on master_perawatan with ruangan_id
- [ ] Verify columns exist in DB
- [ ] Clear all caches
- [ ] Test form submission untuk master perawatan
- [ ] Monitor error logs untuk 24 jam pertama
- [ ] Update documentation jika ada breaking changes

---

## 📞 SUPPORT

Jika error masih terjadi:
1. Share error message lengkap
2. Cek `storage/logs/laravel.log` 
3. Jalankan: `php artisan tinker` dan test query manual
4. Contact hosting untuk SSH access jika needed

---

**Last Updated:** 19 Januari 2026  
**Author:** System Analysis  
**Status:** Ready for Production Deployment
