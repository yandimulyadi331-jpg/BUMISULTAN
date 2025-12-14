# STEP-BY-STEP DEPLOY KE HOSTINGER

## **CARA PALING MUDAH: Via FTP (Tanpa Ribet) ✅**

### Yang Kamu Butuhkan:
1. **FileZilla** (free) - download dari: https://filezilla-project.org/
2. **FTP Credentials dari Hostinger**

### Langkah-Langkah:

#### **1. Dapatkan FTP Credentials**
- Login ke Hostinger Control Panel
- Cari **File Manager** atau **FTP Accounts**
- Biasanya:
  - Host: `ftp.yourdomain.com` atau `IP_ADDRESS`
  - Username: `cp-username` atau `u12345678`
  - Password: Password yang kamu set
  - Port: `21`

#### **2. Buka FileZilla**
- Klik `File` → `Site Manager`
- Klik `New site`
- Isi:
  ```
  Protocol: FTP
  Host: ftp.yourdomain.com
  Port: 21
  Encryption: Only use plain FTP
  User: username dari Hostinger
  Password: password dari Hostinger
  ```
- Klik `Connect`

#### **3. Upload File yang Berubah**

**Navigate ke folder:**
```
Remote: public_html/bumisultan/
```

**Upload file-file berikut (drag & drop):**
```
app/Http/Controllers/YayasanMasarController.php
resources/views/datamaster/yayasan_masar/index.blade.php
resources/views/absensi-santri/laporan.blade.php
.github/workflows/deploy.yml (opsional)
```

**Atau lebih gampang:**
```
1. Select semua file yang berubah di lokal
2. Drag ke FileZilla
3. Tunggu selesai
```

#### **4. Clear Cache di Server (Opsional tapi Penting)**

Jika punya akses **Terminal/SSH**:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

Atau via **cPanel Terminal** (jika tersedia di Hostinger):
```
cPanel → Terminal → Execute
```

---

## **CARA LEBIH CANGGIH: Via Git + SSH**

### Prasyarat:
- Hostinger harus support Git (cek di control panel)
- Atau minimal ada SSH access

### Langkah:

#### **1. Di Lokal (PowerShell/CMD)**
```bash
cd D:\bumisultanAPP\bumisultanAPP

# Stage semua perubahan
git add .

# Commit
git commit -m "Tambah PIN search di Yayasan Masar"

# Push ke GitHub
git push origin main
```

#### **2. Di Server Hostinger (Via SSH)**
```bash
# Buka Terminal di cPanel atau SSH client
ssh username@hostinger_domain.com

# Navigate ke project
cd public_html/bumisultan

# Pull dari GitHub
git pull origin main

# Install dependencies (jika ada)
composer install --no-dev

# Clear cache
php artisan cache:clear
php artisan config:clear

# Done!
```

---

## **CARA PALING OTOMATIS: GitHub Actions (Recommended) 🚀**

Setelah push ke GitHub, otomatis deploy ke Hostinger!

### Setup (One time only):

#### **Step 1: Setup SSH di Hostinger**

**Via Hostinger cPanel → SSH Access:**
1. Generate SSH keys
2. Atau gunakan existing key

**Via cPanel Terminal:**
```bash
# Cek authorized_keys
cat ~/.ssh/authorized_keys

# Jika tidak ada, buat folder
mkdir -p ~/.ssh
touch ~/.ssh/authorized_keys
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

#### **Step 2: Dapatkan Private Key**

**Di Hostinger Terminal:**
```bash
# Lihat private key
cat ~/.ssh/id_rsa

# Copy semua output (dari -----BEGIN sampai END-----)
```

#### **Step 3: Setup Secrets di GitHub**

**Buka:** https://github.com/yandimulyadi331-jpg/BUMISULTAN/settings/secrets/actions

**Tambah secrets:**
```
HOSTINGER_HOST          = ftp.yourdomain.com
HOSTINGER_USERNAME      = cp_user_anda
HOSTINGER_PASSWORD      = password_ssh
HOSTINGER_PORT          = 22
DEPLOY_PATH             = ~/public_html/bumisultan
```

**Done!** Sekarang setiap kali push ke GitHub, otomatis deploy ke Hostinger! ✨

---

## **TROUBLESHOOTING**

### File tidak ter-upload?
- Pastikan path folder benar di Hostinger
- Check file permissions (755 untuk folder, 644 untuk file)

### Cache masih lama berubah?
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Atau nuclear option:
rm -rf storage/framework/cache/*
rm -rf bootstrap/cache/*
```

### Git pull fail?
```bash
# Check status
git status

# Bisa juga pull dengan force
git reset --hard
git pull origin main
```

### Composer timeout?
```bash
composer install --no-dev -vvv

# Atau set timeout lebih lama
composer install --no-dev --no-interaction --prefer-dist
```

---

## **REKOMENDASI WORKFLOW:**

```
1. Code di lokal ✏️
   ↓
2. git add . && git commit ✅
   ↓
3. git push origin main 🚀
   ↓
4. GitHub Actions auto-deploy (atau manual upload FTP)
   ↓
5. Testing di hosting 🎯
```

Mana yang mau kamu pilih? Saya bisa help setup step-by-step! 💪
