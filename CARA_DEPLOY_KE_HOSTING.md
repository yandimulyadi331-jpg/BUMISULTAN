# 🚀 CARA DEPLOY PERUBAHAN KE HOSTING

**Status:** ✅ Code sudah di-push ke Git  
**Commit:** `44c5bf7` - Fix export PDF Dana Operasional

---

## 📋 LANGKAH DEPLOY DI TERMIUS (SSH)

### **1. Connect ke Server Hosting**

```bash
# Di Termius, connect ke server Anda
ssh user@manajemen.bumisultan.site
# atau sesuai konfigurasi SSH Anda
```

---

### **2. Masuk ke Directory Aplikasi**

```bash
cd /home/bumisultan/public_html
# atau path aplikasi Laravel Anda, biasanya:
# cd /var/www/html/bumisultanAPP
# cd /home/username/htdocs
```

---

### **3. Backup Sebelum Update (PENTING!)**

```bash
# Backup controller lama
cp app/Http/Controllers/DanaOperasionalController.php app/Http/Controllers/DanaOperasionalController.php.backup

# Atau backup semua
tar -czf backup-$(date +%Y%m%d-%H%M%S).tar.gz app/ config/
```

---

### **4. Pull Perubahan dari Git**

```bash
# Stash perubahan lokal (jika ada)
git stash

# Pull dari repository
git pull origin main

# Atau jika ada conflict:
git fetch origin
git reset --hard origin/main
```

**Expected Output:**
```
Updating 1af6cbc..44c5bf7
Fast-forward
 app/Http/Controllers/DanaOperasionalController.php | 175 ++++++++++++++-------
 PERBAIKAN_ERROR_EXPORT_PDF_DANA_OPERASIONAL.md     | 456 +++++++++++++++++++
 SOLUSI_EXPORT_PDF_DATA_BESAR.md                    | 701 ++++++++++++++++++++++++++
 HELPER_EXPORT_KUARTAL.js                           |  89 ++++
 4 files changed, 1157 insertions(+), 18 deletions(-)
```

---

### **5. Clear Cache Laravel**

```bash
# Clear semua cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Rebuild cache (opsional)
php artisan config:cache
php artisan route:cache
```

---

### **6. Set Permissions (Jika Perlu)**

```bash
# Set permission storage dan bootstrap
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Atau sesuai user web server:
# chown -R nginx:nginx storage bootstrap/cache
# chown -R apache:apache storage bootstrap/cache
```

---

### **7. Restart PHP-FPM / Web Server (Opsional)**

```bash
# Untuk PHP-FPM:
sudo systemctl restart php8.2-fpm
# atau: sudo service php8.2-fpm restart

# Untuk Apache:
sudo systemctl restart apache2

# Untuk Nginx:
sudo systemctl restart nginx
```

---

### **8. Verify Update**

```bash
# Cek apakah file sudah terupdate
grep -n "2048M" app/Http/Controllers/DanaOperasionalController.php

# Expected output:
# 1250:    ini_set('memory_limit', '2048M');
```

---

### **9. Test Export PDF**

Buka browser, test URL ini:

**Test 1 - Export 1 Bulan:**
```
https://manajemen.bumisultan.site/dana-operasional/export-pdf?filter_type=bulan&bulan=2025-01
```

**Test 2 - Export 1 Tahun:**
```
https://manajemen.bumisultan.site/dana-operasional/export-pdf?filter_type=tahun&tahun=2025
```

**Expected Result:** PDF berhasil di-download tanpa HTTP 500 ✅

---

### **10. Monitor Log**

```bash
# Lihat log real-time
tail -f storage/logs/laravel.log

# Atau cari error spesifik:
tail -100 storage/logs/laravel.log | grep "Export PDF"
```

**Log SUCCESS yang diharapkan:**
```
[2026-01-07 XX:XX:XX] local.INFO: Export PDF - Preparing data 
    {"total_records": XXXX, "memory_usage": "XXX MB"}
    
[2026-01-07 XX:XX:XX] local.INFO: Export PDF - PDF generated successfully
```

**Jika ada ERROR:**
```
[2026-01-07 XX:XX:XX] local.ERROR: Export PDF Error: ...
```

---

## 🔧 TROUBLESHOOTING

### **Problem 1: "Permission Denied" saat git pull**

```bash
# Fix permission
sudo chown -R $USER:$USER /path/to/app

# Atau pakai sudo:
sudo git pull origin main
```

---

### **Problem 2: "Cannot pull with rebase: You have unstaged changes"**

```bash
# Lihat perubahan lokal
git status

# Opsi A: Stash changes
git stash
git pull origin main
git stash pop

# Opsi B: Discard local changes
git reset --hard HEAD
git pull origin main
```

---

### **Problem 3: Git tidak terinstall di server**

```bash
# Install git (Ubuntu/Debian)
sudo apt-get update
sudo apt-get install git

# Install git (CentOS/RHEL)
sudo yum install git
```

---

### **Problem 4: File tidak berubah setelah pull**

```bash
# Force clear opcache
php artisan opcache:clear
# atau restart PHP-FPM

# Verify file content
cat app/Http/Controllers/DanaOperasionalController.php | grep "2048M"
```

---

### **Problem 5: Masih HTTP 500 setelah deploy**

```bash
# Cek log error
tail -50 storage/logs/laravel.log

# Cek PHP error log
tail -50 /var/log/php8.2-fpm.log
# atau: tail -50 /var/log/apache2/error.log
```

**Kemungkinan:**
1. Memory limit server belum 2GB → Edit `php.ini`
2. Timeout masih 60 detik → Edit `php.ini` atau nginx config
3. Data terlalu besar (>10,000) → Export per kuartal

---

## ⚙️ KONFIGURASI SERVER (Jika Perlu)

### **Edit php.ini di Server**

```bash
# Cari php.ini
php --ini

# Edit file
sudo nano /etc/php/8.2/fpm/php.ini
# atau: sudo nano /etc/php.ini

# Ubah setting ini:
memory_limit = 2048M
max_execution_time = 300
post_max_size = 128M
upload_max_filesize = 128M

# Save (Ctrl+O, Enter, Ctrl+X)

# Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

---

### **Edit Nginx Timeout (Jika pakai Nginx)**

```bash
sudo nano /etc/nginx/sites-available/default
# atau: sudo nano /etc/nginx/nginx.conf

# Tambahkan di block location:
location / {
    fastcgi_read_timeout 300;
    fastcgi_send_timeout 300;
    proxy_connect_timeout 300;
    proxy_send_timeout 300;
    proxy_read_timeout 300;
}

# Save & restart
sudo systemctl restart nginx
```

---

## ✅ CHECKLIST DEPLOY

- [ ] Connect ke server via Termius/SSH
- [ ] Masuk ke directory aplikasi
- [ ] Backup file lama (DanaOperasionalController.php)
- [ ] `git pull origin main`
- [ ] `php artisan config:clear`
- [ ] `php artisan cache:clear`
- [ ] `php artisan view:clear`
- [ ] Set permissions (jika perlu)
- [ ] Restart PHP-FPM/Web Server (opsional)
- [ ] Test export PDF di browser
- [ ] Monitor log Laravel untuk error
- [ ] ✅ SELESAI!

---

## 📞 CONTACT SUPPORT

Jika masih error setelah deploy:

1. **Share log terbaru:**
   ```bash
   tail -100 storage/logs/laravel.log | grep "Export PDF"
   ```

2. **Share PHP info:**
   ```bash
   php -i | grep memory_limit
   php -i | grep max_execution_time
   ```

3. **Share jumlah data:**
   ```bash
   php artisan tinker --execute="echo \App\Models\RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)->count();"
   ```

---

**Status:** 🚀 Ready to Deploy  
**Last Update:** 7 Januari 2026  
**Commit Hash:** `44c5bf7`
