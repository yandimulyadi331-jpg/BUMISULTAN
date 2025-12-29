# PANDUAN DEPLOY TRACKING BARANG KELUAR KE HOSTING

## 🚀 Langkah Deploy via Termius/SSH

### 1️⃣ Connect ke Server
```bash
# Login via Termius ke server hosting
ssh user@your-server.com
# atau
ssh user@ip-address
```

### 2️⃣ Masuk ke Folder Project
```bash
cd /path/to/your/bumisultanAPP
# Contoh: cd /home/username/public_html/bumisultanAPP
```

### 3️⃣ Backup Database (PENTING!)
```bash
# Backup database sebelum migration
php artisan backup:run
# atau manual:
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
```

### 4️⃣ Upload File yang Diubah

**File yang perlu diupload:**

**A. Migration Files:**
```
database/migrations/2025_12_29_100000_create_barang_keluar_table.php
database/migrations/2025_12_29_150000_remove_unique_kode_transaksi.php
```

**B. Model Files:**
```
app/Models/BarangKeluar.php
app/Models/BarangKeluarHistory.php
app/Models/BarangKeluarReminder.php
```

**C. Controller:**
```
app/Http/Controllers/BarangKeluarController.php
```

**D. Views:**
```
resources/views/barang-keluar/index.blade.php
resources/views/barang-keluar/create.blade.php
resources/views/barang-keluar/edit.blade.php
resources/views/barang-keluar/show.blade.php
resources/views/barang-keluar/pdf.blade.php
```

**E. Routes:**
```
routes/web.php (updated)
```

**F. Sidebar:**
```
resources/views/layouts/sidebar.blade.php (updated)
```

### 5️⃣ Upload via Termius/SFTP

**Cara 1: Via Termius SFTP**
1. Buka Termius
2. Klik tombol SFTP
3. Navigate ke folder local: `d:\bumisultanAPP\bumisultanAPP`
4. Navigate ke folder remote: `/path/to/your/project`
5. Drag & drop file yang disebutkan di atas

**Cara 2: Via SCP Command (dari local Windows)**
```powershell
# Upload migration
scp "d:\bumisultanAPP\bumisultanAPP\database\migrations\2025_12_29_100000_create_barang_keluar_table.php" user@server:/path/to/project/database/migrations/

# Upload models
scp "d:\bumisultanAPP\bumisultanAPP\app\Models\BarangKeluar.php" user@server:/path/to/project/app/Models/
scp "d:\bumisultanAPP\bumisultanAPP\app\Models\BarangKeluarHistory.php" user@server:/path/to/project/app/Models/
scp "d:\bumisultanAPP\bumisultanAPP\app\Models\BarangKeluarReminder.php" user@server:/path/to/project/app/Models/

# Upload controller
scp "d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\BarangKeluarController.php" user@server:/path/to/project/app/Http/Controllers/

# Upload views folder (semua file sekaligus)
scp -r "d:\bumisultanAPP\bumisultanAPP\resources\views\barang-keluar" user@server:/path/to/project/resources/views/

# Upload routes
scp "d:\bumisultanAPP\bumisultanAPP\routes\web.php" user@server:/path/to/project/routes/

# Upload sidebar
scp "d:\bumisultanAPP\bumisultanAPP\resources\views\layouts\sidebar.blade.php" user@server:/path/to/project/resources/views/layouts/
```

**Cara 3: Via Git (Recommended)**
```bash
# Di local, commit semua perubahan
git add .
git commit -m "Add tracking barang keluar system"
git push origin main

# Di server via SSH
cd /path/to/project
git pull origin main
```

### 6️⃣ Jalankan Migration di Server

```bash
# Masuk ke folder project
cd /path/to/project

# Jalankan migration
php artisan migrate --path=database/migrations/2025_12_29_100000_create_barang_keluar_table.php

# Jalankan migration kedua
php artisan migrate --path=database/migrations/2025_12_29_150000_remove_unique_kode_transaksi.php

# Atau jalankan semua pending migrations
php artisan migrate
```

### 7️⃣ Set Permission & Storage Link

```bash
# Set permission folders
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Buat storage link
php artisan storage:link

# Buat folder untuk foto barang keluar
mkdir -p storage/app/public/documents
chmod -R 775 storage/app/public/documents
```

### 8️⃣ Clear Cache

```bash
# Clear semua cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan optimize
```

### 9️⃣ Test di Browser

```
https://your-domain.com/barang-keluar
```

---

## 📝 CHECKLIST DEPLOYMENT

- [ ] Backup database
- [ ] Upload semua file yang dimodifikasi
- [ ] Jalankan migration
- [ ] Storage link dibuat
- [ ] Permission folder storage sudah benar
- [ ] Cache di-clear
- [ ] Test akses menu di browser
- [ ] Test tambah barang keluar dengan foto
- [ ] Test update status
- [ ] Test export PDF

---

## 🔧 TROUBLESHOOTING

### Error: "Class not found"
```bash
composer dump-autoload
php artisan optimize
```

### Error: "Permission denied"
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage
```

### Foto tidak muncul
```bash
# Pastikan storage link ada
php artisan storage:link

# Set permission
chmod -R 775 storage/app/public
```

### Migration error
```bash
# Rollback migration terakhir
php artisan migrate:rollback --step=1

# Jalankan ulang
php artisan migrate
```

### Menu tidak muncul
```bash
# Clear cache
php artisan cache:clear
php artisan view:clear

# Periksa permission role di database
```

---

## 📦 ALTERNATIVE: Upload via ZIP

Jika upload satu-satu ribet, bisa zip dulu:

**Di Local Windows:**
```powershell
# Buat folder temporary
New-Item -ItemType Directory -Path "d:\deploy-barang-keluar" -Force

# Copy file yang diperlukan
Copy-Item "d:\bumisultanAPP\bumisultanAPP\database\migrations\2025_12_29_*.php" "d:\deploy-barang-keluar\"
Copy-Item "d:\bumisultanAPP\bumisultanAPP\app\Models\BarangKeluar*.php" "d:\deploy-barang-keluar\"
Copy-Item "d:\bumisultanAPP\bumisultanAPP\app\Http\Controllers\BarangKeluarController.php" "d:\deploy-barang-keluar\"
Copy-Item -Recurse "d:\bumisultanAPP\bumisultanAPP\resources\views\barang-keluar" "d:\deploy-barang-keluar\"
Copy-Item "d:\bumisultanAPP\bumisultanAPP\routes\web.php" "d:\deploy-barang-keluar\"
Copy-Item "d:\bumisultanAPP\bumisultanAPP\resources\views\layouts\sidebar.blade.php" "d:\deploy-barang-keluar\"

# Zip folder
Compress-Archive -Path "d:\deploy-barang-keluar\*" -DestinationPath "d:\deploy-barang-keluar.zip"
```

**Di Server via SSH:**
```bash
# Upload zip via SFTP/Termius
# Extract
unzip deploy-barang-keluar.zip -d /tmp/deploy

# Copy ke folder yang tepat
cp /tmp/deploy/2025_12_29_*.php database/migrations/
cp /tmp/deploy/BarangKeluar*.php app/Models/
cp /tmp/deploy/BarangKeluarController.php app/Http/Controllers/
cp -r /tmp/deploy/barang-keluar resources/views/
cp /tmp/deploy/web.php routes/
cp /tmp/deploy/sidebar.blade.php resources/views/layouts/

# Jalankan migration
php artisan migrate
```

---

## 🎯 QUICK COMMAND SEQUENCE

Setelah file ter-upload, jalankan command ini secara berurutan:

```bash
cd /path/to/project
php artisan migrate
php artisan storage:link
chmod -R 775 storage
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize
```

---

## 📞 SUPPORT

Jika ada error, cek log:
```bash
tail -f storage/logs/laravel.log
```

---

**Good luck with deployment! 🚀**
