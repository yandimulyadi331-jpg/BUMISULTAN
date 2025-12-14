# AKSES FILE MANAGER HOSTINGER VIA SSH

## **Cara 1: Terminal di cPanel Hostinger (Paling Mudah) ✅**

### Langkah-Langkah:

1. **Login ke Hostinger**
   - Buka: https://hpanel.hostinger.com
   - Masuk dengan email & password

2. **Buka Terminal (cPanel)**
   - Di dashboard, cari **File Manager** atau **Terminal**
   - Atau buka langsung: **Advanced → Terminal**

3. **Akan muncul terminal di browser**
   - Sekarang kamu bisa jalankan command Linux

### Contoh Command:

```bash
# Lihat folder struktur
ls -la

# Navigate ke project
cd public_html/bumisultan

# Pull dari GitHub
git pull origin main

# Clear cache Laravel
php artisan cache:clear
php artisan config:clear

# Check status
git status
```

---

## **Cara 2: SSH Client (PuTTY / Windows Terminal) 🔌**

### Step 1: Dapatkan SSH Credentials

**Di Hostinger cPanel:**
1. Cari **SSH Access** atau **Advanced → SSH Keys**
2. Cek apakah SSH sudah enabled
3. Ambil:
   - **Hostname:** `ftp.yourdomain.com` atau IP address
   - **Port:** Biasanya `22`
   - **Username:** Cek di **Account Details**

### Step 2: Download PuTTY (Gratis)

**Windows:**
- Download: https://www.putty.org/
- Atau gunakan **Windows Terminal** (built-in)

### Step 3: Connect via PowerShell

**Buka PowerShell & ketik:**

```bash
# Replace USERNAME & HOSTINGER_DOMAIN sesuai milikmu
ssh username@ftp.yourdomain.com

# Or gunakan IP
ssh username@123.45.67.89
```

**First time connect:**
- Will ask: `Are you sure? (yes/no)` → ketik `yes`
- Masukkan SSH password

### Step 4: Navigasi File

```bash
# List files
ls -la

# Go to project folder
cd public_html/bumisultan

# Pull code
git pull origin main

# Check permission
ls -la app/Http/Controllers/YayasanMasarController.php

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## **Cara 3: File Manager GUI Hostinger (Jika ada) 📁**

### Di cPanel Hostinger:
1. Cari **File Manager** (bukan Terminal)
2. Akan muncul interface seperti Windows Explorer
3. Bisa drag-drop files
4. Right-click → Edit untuk lihat file code

---

## **PANDUAN DEPLOY PRAKTIS VIA SSH HOSTINGER:**

### **Skenario: Push code baru, langsung deploy ke Hostinger**

#### **1. Di Lokal (PowerShell):**
```bash
cd D:\bumisultanAPP\bumisultanAPP

git add .
git commit -m "Tambah PIN search di Yayasan Masar"
git push origin main
```

#### **2. Di Hostinger Terminal (SSH):**
```bash
# Connect via SSH
ssh username@ftp.yourdomain.com

# Navigate ke project
cd public_html/bumisultan

# Pull latest code
git pull origin main

# Clear cache (PENTING!)
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Verify
git log --oneline -3
```

**Done! Perubahan live di hosting! 🎉**

---

## **COMMAND YANG SERING DIPAKAI:**

```bash
# ==== NAVIGASI ====
cd public_html/bumisultan      # Masuk folder project
ls -la                         # Lihat isi folder
pwd                            # Lihat lokasi sekarang
cd ..                          # Naik satu folder
cd ~                           # Ke home directory

# ==== GIT OPERATIONS ====
git status                     # Lihat status
git log --oneline -5           # Lihat 5 commit terakhir
git pull origin main           # Pull dari GitHub
git branch                     # Lihat branch
git checkout main              # Switch ke main branch

# ==== LARAVEL COMMANDS ====
php artisan cache:clear       # Clear cache
php artisan config:clear      # Clear config
php artisan view:clear        # Clear views
php artisan migrate --force   # Run migrations
php artisan optimize          # Optimize app

# ==== FILE OPERATIONS ====
cp file.php file.backup.php   # Copy file
mv file.php newname.php       # Rename/move
rm file.php                   # Delete file
chmod 755 folder              # Change permission
chown user:group file         # Change owner

# ==== PERMISSIONS (PENTING!) ====
chmod -R 755 storage          # Folder writable
chmod -R 755 bootstrap/cache  # Cache writable
chmod 644 .env                # Config readable
```

---

## **TROUBLESHOOTING SSH:**

### "Permission denied (publickey)"
```bash
# Check SSH key
cat ~/.ssh/authorized_keys

# Atau set permissions
chmod 700 ~/.ssh
chmod 600 ~/.ssh/authorized_keys
```

### "git pull" tidak bisa karena permission
```bash
# Check git status
git status

# Fix permissions
git config --global user.name "Your Name"
git config --global user.email "your@email.com"

# Reset jika perlu
git reset --hard
git pull origin main
```

### Cache masih tidak berubah
```bash
# Nuclear option - hapus manual
rm -rf storage/framework/cache/*
rm -rf bootstrap/cache/*

# Atau
php artisan cache:clear --all
```

### Pagination atau file tidak ditemukan
```bash
# Bersihkan semua cache
php artisan optimize:clear

# Restart queue (jika ada)
php artisan queue:restart
```

---

## **REKOMENDASI WORKFLOW FINAL:**

```
Lokal (Edit code)
   ↓
git commit & push
   ↓
SSH ke Hostinger
   ↓
git pull origin main
   ↓
php artisan cache:clear
   ↓
Testing di live (hosting)
   ↓
✅ DONE!
```

---

## **QUICK REFERENCE CARD:**

| Kebutuhan | Command |
|-----------|---------|
| Connect SSH | `ssh user@domain.com` |
| Masuk folder | `cd public_html/bumisultan` |
| Update code | `git pull origin main` |
| Clear cache | `php artisan cache:clear` |
| Check perms | `ls -la file.php` |
| Edit file | `nano file.php` (ctrl+X = exit) |
| View log | `tail -f storage/logs/laravel.log` |

**Sudah paham? Mau coba praktik? 🚀**
