# PANDUAN DEPLOY KE HOSTINGER

## **Opsi 1: Menggunakan Git SSH (Paling Efisien) ✅**

### Step 1: Setup SSH Key di Hostinger
1. Buka cPanel Hostinger
2. Masuk ke **File Manager** atau **SSH Keys** (jika tersedia)
3. Generate SSH key atau gunakan key lokal

### Step 2: Di Lokal (PowerShell/CMD)
```bash
# Pastikan sudah di folder project
cd D:\bumisultanAPP\bumisultanAPP

# Check Git status
git status

# Commit perubahan
git add .
git commit -m "Tambah PIN search dan export excel"

# Push ke GitHub
git push origin main
```

### Step 3: Di Server Hostinger (Via SSH)
```bash
# SSH ke Hostinger
ssh username@hostinger_domain_anda

# Navigate ke public_html
cd ~/public_html/bumisultan
# atau
cd ~/bumisultan

# Pull perubahan dari GitHub
git pull origin main

# Clear cache Laravel
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Jika ada migration baru
php artisan migrate --force
```

---

## **Opsi 2: Deploy via FTP (Paling Mudah) 🎯**

### Langkah-Langkah:

1. **Buka FileZilla atau WinSCP**
   - Host: ftp.hostinger.com (atau domain anda)
   - Username: FTP username dari Hostinger
   - Password: FTP password

2. **Upload file yang berubah:**
   ```
   /app/Http/Controllers/YayasanMasarController.php
   /resources/views/datamaster/yayasan_masar/index.blade.php
   /resources/views/absensi-santri/laporan.blade.php
   /routes/web.php (jika ada perubahan)
   ```

3. **Jika ada database migration:**
   - SSH atau akses command line untuk jalankan:
   ```bash
   php artisan migrate --force
   ```

---

## **Opsi 3: Auto Deploy dengan GitHub Actions + Hostinger**

### Step 1: Setup SSH Key di Hostinger

**Via cPanel SSH:**
```bash
# Generate SSH key (jika belum ada)
ssh-keygen -t rsa -b 4096 -f ~/.ssh/id_rsa

# Copy public key
cat ~/.ssh/id_rsa.pub
# Simpan di authorized_keys

# Tampilkan private key
cat ~/.ssh/id_rsa
# Copy semua (mulai dari -----BEGIN sampai END-----)
```

### Step 2: Tambah Secrets di GitHub

1. Buka repo GitHub: https://github.com/yandimulyadi331-jpg/BUMISULTAN
2. Settings → Secrets and variables → Actions
3. Tambah secrets baru:

| Nama | Nilai |
|------|-------|
| `HOSTINGER_HOST` | ftp.hostinger.com atau IP server |
| `HOSTINGER_USERNAME` | Username SSH/FTP |
| `HOSTINGER_PASSWORD` | Password SSH/FTP |
| `HOSTINGER_PORT` | 22 (untuk SSH) atau 21 (FTP) |
| `DEPLOY_PATH` | ~/public_html/bumisultan (path di server) |
| `HOSTINGER_KEY` | Private SSH key (copy dari `~/.ssh/id_rsa`) |

### Step 3: Update GitHub Actions Workflow

File: `.github/workflows/deploy.yml`
