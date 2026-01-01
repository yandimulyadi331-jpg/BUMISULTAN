# 🚀 PANDUAN DEPLOYMENT KE HOSTING VIA GIT & TERMIUS

**Tanggal:** 1 Januari 2026  
**Fitur:** Keterangan Ijin Dinas, Ijin Sakit & Tidak Absen di Laporan Presensi

---

## 📋 FILE YANG DIUBAH

### Backend:
- `app/Http/Controllers/LaporanController.php`

### Frontend:
- `resources/views/laporan/presensi_cetak.blade.php`

### Dokumentasi:
- `IMPLEMENTASI_KETERANGAN_IJIN_LAPORAN_PRESENSI.md`
- `QUICK_GUIDE_KETERANGAN_IJIN_LAPORAN.md`

---

## 🔄 LANGKAH 1: COMMIT & PUSH KE GIT (Local)

### 1.1 Buka Terminal di VS Code

Tekan **Ctrl + `** atau buka terminal baru

### 1.2 Check Status Git

```bash
git status
```

**Output yang diharapkan:**
```
modified:   app/Http/Controllers/LaporanController.php
modified:   resources/views/laporan/presensi_cetak.blade.php
new file:   IMPLEMENTASI_KETERANGAN_IJIN_LAPORAN_PRESENSI.md
new file:   QUICK_GUIDE_KETERANGAN_IJIN_LAPORAN.md
```

### 1.3 Add Files ke Git

```bash
# Add semua perubahan
git add .

# Atau add file spesifik
git add app/Http/Controllers/LaporanController.php
git add resources/views/laporan/presensi_cetak.blade.php
git add IMPLEMENTASI_KETERANGAN_IJIN_LAPORAN_PRESENSI.md
git add QUICK_GUIDE_KETERANGAN_IJIN_LAPORAN.md
```

### 1.4 Commit dengan Pesan Jelas

```bash
git commit -m "feat: Tambah keterangan ijin dinas, sakit & tidak absen di laporan presensi

- Add LEFT JOIN presensi_izindinas di LaporanController
- Add status 'd' (Ijin Dinas) dengan warna purple
- Update keterangan ijin, sakit, cuti dengan fallback
- Add kolom rekap Dinas di laporan
- Update legend dengan 7 status lengkap
- Add dokumentasi lengkap"
```

### 1.5 Push ke Repository

```bash
# Push ke branch utama (biasanya main atau master)
git push origin main

# Atau jika branch master
git push origin master

# Jika ada error, coba:
git push -u origin main --force
```

---

## 🖥️ LANGKAH 2: DEPLOY KE HOSTING VIA TERMIUS

### 2.1 Buka Termius & Connect ke Server

1. Buka aplikasi **Termius**
2. Pilih koneksi server hosting Anda
3. Klik **Connect**
4. Masukkan password jika diminta

**Contoh:**
```
Host: 123.45.67.89
User: bumisultan
Port: 22
```

### 2.2 Navigasi ke Folder Aplikasi

```bash
# Masuk ke folder aplikasi (sesuaikan path Anda)
cd /home/bumisultan/public_html
# atau
cd /var/www/html/bumisultanAPP
```

**Verifikasi:**
```bash
pwd
# Output: /home/bumisultan/public_html
```

### 2.3 Backup Sebelum Pull (PENTING!)

```bash
# Backup file yang diubah
cp app/Http/Controllers/LaporanController.php app/Http/Controllers/LaporanController.php.backup
cp resources/views/laporan/presensi_cetak.blade.php resources/views/laporan/presensi_cetak.blade.php.backup

# Atau backup full folder
tar -czf backup-$(date +%Y%m%d-%H%M%S).tar.gz app/ resources/
```

### 2.4 Check Status Git di Server

```bash
git status
```

**Jika ada perubahan local:**
```bash
# Stash perubahan local (simpan sementara)
git stash

# Atau reset jika tidak penting
git reset --hard HEAD
```

### 2.5 Pull Perubahan dari Repository

```bash
# Pull dari repository
git pull origin main

# Atau jika branch master
git pull origin master
```

**Output yang diharapkan:**
```
remote: Enumerating objects: 8, done.
remote: Counting objects: 100% (8/8), done.
remote: Compressing objects: 100% (5/5), done.
remote: Total 5 (delta 3), reused 0 (delta 0)
Unpacking objects: 100% (5/5), done.
From https://github.com/yourusername/bumisultanAPP
   abc1234..def5678  main -> origin/main
Updating abc1234..def5678
Fast-forward
 app/Http/Controllers/LaporanController.php         | 12 +++++++++++-
 resources/views/laporan/presensi_cetak.blade.php   | 85 +++++++++++++++++---
 2 files changed, 87 insertions(+), 10 deletions(-)
```

### 2.6 Verifikasi File Berhasil Diupdate

```bash
# Check file controller
cat app/Http/Controllers/LaporanController.php | grep "keterangan_izin_dinas"

# Check file view
cat resources/views/laporan/presensi_cetak.blade.php | grep "jml_dinas"
```

**Output yang diharapkan:**
```
'presensi_izindinas.keterangan as keterangan_izin_dinas'
$jml_dinas = 0;
```

---

## 🧹 LANGKAH 3: CLEAR CACHE DI SERVER

### 3.1 Clear Laravel Cache

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Clear compiled
php artisan clear-compiled

# Optimize untuk production
php artisan optimize
```

**Output setiap command:**
```
Application cache cleared!
Configuration cache cleared!
Route cache cleared!
Compiled views cleared!
```

### 3.2 Set Permissions (Jika Perlu)

```bash
# Set permission storage
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set ownership (sesuaikan user server Anda)
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 3.3 Restart Services (Optional)

```bash
# Restart PHP-FPM (jika ada akses)
sudo systemctl restart php8.1-fpm
# atau
sudo service php8.1-fpm restart

# Restart Nginx (jika ada akses)
sudo systemctl restart nginx
# atau
sudo service nginx restart
```

---

## ✅ LANGKAH 4: TESTING DI SERVER

### 4.1 Akses Aplikasi via Browser

```
https://bumisultan.com/laporan/presensi
# atau sesuai domain Anda
```

### 4.2 Test Generate Laporan

1. **Login** ke aplikasi
2. **Menu:** Laporan → Presensi & Gaji
3. **Filter:** 
   - Periode: 21 Des 2025 - 20 Jan 2026
   - Pilih karyawan yang punya ijin dinas
4. **Klik:** CETAK

### 4.3 Verifikasi Output

Pastikan:
- [ ] Status **"IJIN DINAS"** muncul dengan warna **PURPLE**
- [ ] Keterangan ijin dinas tampil lengkap
- [ ] Kolom rekap ada **"Dinas: X"**
- [ ] Legend tampil **"ID = Ijin Dinas"**
- [ ] Keterangan ijin sakit tampil
- [ ] Tidak absen tampil "Tidak ada keterangan"

---

## 🆘 TROUBLESHOOTING

### Problem 1: Git Pull Error

**Error:**
```
error: Your local changes to the following files would be overwritten by merge
```

**Solution:**
```bash
# Stash changes
git stash
git pull origin main
git stash pop

# Atau reset
git reset --hard HEAD
git pull origin main
```

---

### Problem 2: Permission Denied

**Error:**
```
Permission denied (publickey)
```

**Solution:**
```bash
# Setup SSH key atau gunakan HTTPS
git remote set-url origin https://github.com/username/bumisultanAPP.git
git pull
```

---

### Problem 3: Cache Tidak Clear

**Error:** Perubahan tidak muncul di browser

**Solution:**
```bash
# Clear cache lebih agresif
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# Clear browser cache
# Tekan Ctrl + Shift + Delete atau Ctrl + F5
```

---

### Problem 4: Composer Error

**Error:**
```
Class 'Izindinas' not found
```

**Solution:**
```bash
# Regenerate autoload
composer dump-autoload

# Atau update composer
composer install --no-dev --optimize-autoloader
```

---

### Problem 5: Database Error

**Error:**
```
Table 'presensi_izindinas' doesn't exist
```

**Solution:**
```bash
# Cek apakah tabel ada
mysql -u username -p database_name -e "SHOW TABLES LIKE 'presensi_izindinas';"

# Jika tidak ada, buat tabel atau run migration
php artisan migrate
```

---

## 📝 CHECKLIST DEPLOYMENT

### Pre-Deployment:
- [ ] Backup database
- [ ] Backup files yang diubah
- [ ] Test di local berjalan sempurna
- [ ] Commit & push ke Git

### Deployment:
- [ ] Connect ke server via Termius
- [ ] Navigasi ke folder aplikasi
- [ ] Backup file existing
- [ ] Pull perubahan dari Git
- [ ] Verifikasi file terupdate

### Post-Deployment:
- [ ] Clear semua cache Laravel
- [ ] Set permissions jika perlu
- [ ] Test di browser
- [ ] Verifikasi fitur berjalan
- [ ] Monitor error log (10-15 menit)

---

## 🔍 MONITORING AFTER DEPLOYMENT

### 1. Check Error Log

```bash
# Tail error log
tail -f storage/logs/laravel.log

# Atau lihat 50 baris terakhir
tail -n 50 storage/logs/laravel.log

# Cari error spesifik
grep -i "error" storage/logs/laravel.log | tail -n 20
```

### 2. Check Web Server Log

```bash
# Nginx error log
tail -f /var/log/nginx/error.log

# Apache error log
tail -f /var/log/apache2/error.log
```

### 3. Check PHP Log

```bash
# PHP error log
tail -f /var/log/php8.1-fpm.log
# atau
tail -f /var/log/php-fpm/error.log
```

---

## 📞 QUICK COMMANDS REFERENCE

### Git Commands:
```bash
git status                    # Check status
git add .                     # Add all changes
git commit -m "message"       # Commit
git push origin main          # Push to repository
git pull origin main          # Pull from repository
git stash                     # Stash local changes
git reset --hard HEAD         # Reset to last commit
```

### Laravel Commands:
```bash
php artisan cache:clear       # Clear cache
php artisan config:clear      # Clear config cache
php artisan route:clear       # Clear route cache
php artisan view:clear        # Clear view cache
php artisan optimize          # Optimize for production
composer dump-autoload        # Regenerate autoload
```

### Server Commands:
```bash
cd /path/to/app              # Navigate to app
pwd                          # Show current directory
ls -la                       # List files
cat filename                 # Show file content
tail -f log.txt              # Follow log file
chmod -R 775 folder          # Set permissions
chown -R user:group folder   # Set ownership
```

---

## 🎯 RINGKASAN SINGKAT

```bash
# === DI LOCAL (VS Code Terminal) ===
git add .
git commit -m "feat: Add keterangan ijin di laporan"
git push origin main

# === DI SERVER (Termius) ===
cd /home/bumisultan/public_html
git pull origin main
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# === VERIFIKASI (Browser) ===
# Buka: https://bumisultan.com/laporan/presensi
# Test generate & lihat hasilnya
```

---

## 📚 DOKUMENTASI TERKAIT

- [IMPLEMENTASI_KETERANGAN_IJIN_LAPORAN_PRESENSI.md](IMPLEMENTASI_KETERANGAN_IJIN_LAPORAN_PRESENSI.md)
- [QUICK_GUIDE_KETERANGAN_IJIN_LAPORAN.md](QUICK_GUIDE_KETERANGAN_IJIN_LAPORAN.md)
- [ANALISA_IJIN_DINAS_MULTIPLE_KARYAWAN.md](ANALISA_IJIN_DINAS_MULTIPLE_KARYAWAN.md)

---

## 🔒 SECURITY NOTES

1. **Jangan** expose `.env` file
2. **Jangan** commit credential ke Git
3. **Backup** database sebelum deployment
4. **Monitor** error log setelah deployment
5. **Test** di staging dulu jika ada

---

**✅ DEPLOYMENT READY**

**Next Steps:**
1. Commit & push ke Git ✓
2. Pull di server via Termius ✓
3. Clear cache ✓
4. Test di browser ✓
5. Monitor 24 jam ✓

**Support:** Jika ada masalah, cek section Troubleshooting di atas.

---

**Prepared by:** GitHub Copilot  
**Date:** January 1, 2026  
**Version:** 1.0.0
