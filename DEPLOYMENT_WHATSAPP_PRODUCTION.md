# 🚀 PANDUAN DEPLOYMENT WHATSAPP BAILEYS KE PRODUCTION

## 📋 CHECKLIST PERSIAPAN

### ✅ 1. Persiapan Server VPS
- **RAM**: Minimum 1GB (Rekomendasi: 2GB)
- **Storage**: Minimum 10GB free space
- **OS**: Ubuntu 20.04+ atau CentOS 7+
- **Node.js**: Version 18+ 
- **PHP**: Version 8.2+
- **MySQL**: Version 8.0+

### ✅ 2. Install Dependencies di VPS

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Node.js 18+
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Verify installation
node --version  # Harus 18+
npm --version

# Install PM2 globally
sudo npm install -g pm2

# Install build essentials (untuk native modules)
sudo apt install -y build-essential libcairo2-dev libpango1.0-dev libjpeg-dev libgif-dev librsvg2-dev
```

---

## 📦 UPLOAD FILES KE VPS

### 1. Upload Laravel Project
```bash
# Di lokal, zip project Laravel (tanpa node_modules & vendor)
cd C:\Users\user\Desktop\bumisultansuperapp_v2\presensigpsv2-main
# Exclude: node_modules, vendor, storage/logs/*, .env

# Upload via FTP/SFTP ke VPS
# Target path: /var/www/bumisultansuperapp
```

### 2. Upload Baileys Server
```bash
# Upload folder baileys-server ke VPS
# Target path: /var/www/baileys-server
```

### 3. Setup Permissions
```bash
# SSH ke VPS
ssh user@your-vps-ip

cd /var/www/bumisultansuperapp

# Set owner
sudo chown -R www-data:www-data /var/www/bumisultansuperapp
sudo chown -R www-data:www-data /var/www/baileys-server

# Set permissions
sudo chmod -R 755 /var/www/bumisultansuperapp
sudo chmod -R 775 /var/www/bumisultansuperapp/storage
sudo chmod -R 775 /var/www/bumisultansuperapp/bootstrap/cache
```

---

## ⚙️ KONFIGURASI LARAVEL

### 1. Install Dependencies
```bash
cd /var/www/bumisultansuperapp

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Copy .env
cp .env.example .env

# Generate key
php artisan key:generate
```

### 2. Edit .env untuk Production
```bash
nano .env
```

**WAJIB DIUBAH:**
```env
APP_NAME="Bumi Sultan Super App"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_db_user
DB_PASSWORD=your_strong_password

# BAILEYS API URL (jika di server yang sama)
BAILEYS_API_URL=http://localhost:3000

# Jika Laravel dan Baileys di server berbeda
# BAILEYS_API_URL=http://IP_VPS_BAILEYS:3000
```

### 3. Run Migrations
```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🤖 SETUP BAILEYS SERVER DENGAN SUPERVISOR (AUTO-START)

### 1. Install Supervisor (Linux Only - Auto-Restart)
```bash
# Install Supervisor
sudo apt install supervisor -y

# Check status
sudo systemctl status supervisor
```

### 2. Install Dependencies Baileys
```bash
cd /var/www/baileys-server

# Install packages
npm install --production
```

### 3. Copy Supervisor Config
```bash
# Copy config file
sudo cp /var/www/baileys-server/supervisor-baileys.conf /etc/supervisor/conf.d/

# Reload Supervisor
sudo supervisorctl reread
sudo supervisorctl update

# Start Baileys
sudo supervisorctl start baileys-whatsapp

# Check status
sudo supervisorctl status baileys-whatsapp
```

### 4. Logs Baileys (Auto-Saved)
```bash
# View logs real-time
sudo tail -f /var/www/baileys-server/logs/supervisor.log

# View errors
sudo tail -f /var/www/baileys-server/logs/supervisor-error.log
```

### 5. Scan QR Code (FIRST TIME ONLY)
```bash
# Lihat logs untuk QR code
sudo tail -f /var/www/baileys-server/logs/supervisor.log

# Atau stop, run manual untuk scan, lalu start lagi
sudo supervisorctl stop baileys-whatsapp
cd /var/www/baileys-server
node server.js
# Scan QR code dengan HP
# Ctrl+C setelah connected
sudo supervisorctl start baileys-whatsapp
```

### 6. Supervisor Commands (Management)
```bash
# Restart server
sudo supervisorctl restart baileys-whatsapp

# Stop server
sudo supervisorctl stop baileys-whatsapp

# Start server
sudo supervisorctl start baileys-whatsapp

# Check status
sudo supervisorctl status

# View all logs
sudo supervisorctl tail -f baileys-whatsapp
```

---

## 🚀 UNTUK WINDOWS (DEVELOPMENT) - OTOMATIS DARI LARAVEL

### 1. Start Server via Laravel Command
```powershell
# Start Baileys server
php artisan whatsapp:server start

# Check status
php artisan whatsapp:server status

# Stop server
php artisan whatsapp:server stop
```

### 2. Atau Jalankan Otomatis Saat Laravel Start
Tambahkan di `routes/web.php` atau middleware untuk auto-check dan start.

---

## 🤖 SETUP BAILEYS SERVER DENGAN PM2 (ALTERNATIVE - NOT RECOMMENDED FOR PRODUCTION)

### 1. Install Dependencies Baileys
```bash
cd /var/www/baileys-server

# Install packages
npm install --production

# Test run (jangan lama-lama, cukup 5 detik)
node server.js
# Tekan Ctrl+C untuk stop
```

### 2. Start dengan PM2
```bash
# Start Baileys server dengan PM2
pm2 start ecosystem.config.js

# Set auto-start on boot
pm2 startup
pm2 save

# Check status
pm2 status
pm2 logs baileys-whatsapp  # Lihat QR code di sini
```

### 3. Scan QR Code
```bash
# Lihat logs untuk QR code
pm2 logs baileys-whatsapp --lines 50

# Di HP WhatsApp:
# Settings → Linked Devices → Link a Device → Scan QR code

# Setelah tersambung, logs akan tampil:
# "🎉 WhatsApp connected successfully!"
```

### 4. PM2 Commands Penting
```bash
# Lihat status
pm2 status

# Restart jika error
pm2 restart baileys-whatsapp

# Stop sementara
pm2 stop baileys-whatsapp

# Start kembali
pm2 start baileys-whatsapp

# Lihat logs real-time
pm2 logs baileys-whatsapp

# Hapus dari PM2
pm2 delete baileys-whatsapp

# Monitor CPU & RAM
pm2 monit
```

---

## 🔐 FIREWALL & SECURITY

### 1. Jika Laravel dan Baileys di Server yang SAMA
```bash
# Port 3000 TIDAK perlu dibuka ke public
# Cukup gunakan localhost:3000
# Sudah aman by default
```

### 2. Jika Laravel dan Baileys di Server BERBEDA
```bash
# Di server Baileys, buka port 3000 hanya untuk IP Laravel
sudo ufw allow from IP_LARAVEL_SERVER to any port 3000

# Atau jika mau restrict ke IP tertentu
sudo ufw allow from 123.45.67.89 to any port 3000
```

### 3. SSL Certificate (Wajib untuk Production)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Generate SSL
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo certbot renew --dry-run
```

---

## 🧪 TESTING SETELAH DEPLOYMENT

### 1. Test Baileys API
```bash
# Test status endpoint
curl http://localhost:3000/status

# Expected output:
# {"status":"connected","hasQR":false}
```

### 2. Test dari Laravel
```bash
cd /var/www/bumisultansuperapp

# Test artisan tinker
php artisan tinker

# Di tinker:
$response = \Illuminate\Support\Facades\Http::get(config('services.baileys.url') . '/status');
dd($response->json());

# Expected: ['status' => 'connected', 'hasQR' => false]
```

### 3. Test Send Message
```bash
# Test kirim pesan ke nomor sendiri
curl -X POST http://localhost:3000/send-message \
  -H "Content-Type: application/json" \
  -d '{
    "number": "628123456789",
    "message": "Test dari VPS production"
  }'

# Check HP, harusnya ada pesan masuk
```

---

## 🆘 TROUBLESHOOTING

### ❌ Problem: PM2 restart terus-menerus
```bash
# Check logs
pm2 logs baileys-whatsapp --lines 100

# Biasanya karena:
# 1. auth_info folder corrupt → Hapus dan scan QR ulang
rm -rf /var/www/baileys-server/auth_info
pm2 restart baileys-whatsapp
pm2 logs baileys-whatsapp  # Scan QR baru

# 2. Port 3000 sudah dipakai
sudo lsof -i :3000
# Kill process lain yang pakai port 3000
```

### ❌ Problem: QR code tidak muncul
```bash
# Pastikan terminal printable
pm2 logs baileys-whatsapp --raw

# Atau lihat di file log
cat ~/.pm2/logs/baileys-whatsapp-out.log
```

### ❌ Problem: WhatsApp disconnect setelah reboot
```bash
# Check PM2 auto-start
pm2 startup
pm2 save

# Verify
systemctl status pm2-user

# Auth info masih ada?
ls -la /var/www/baileys-server/auth_info/
# Jika kosong, scan QR ulang
```

### ❌ Problem: Laravel tidak bisa hit Baileys API
```bash
# Test dari server Laravel
curl http://localhost:3000/status

# Jika error:
# 1. Check Baileys running
pm2 status

# 2. Check firewall (jika beda server)
sudo ufw status

# 3. Check .env Laravel
php artisan config:clear
php artisan tinker
echo config('services.baileys.url');  # Harus http://localhost:3000
```

---

## 💾 BACKUP PENTING

### 1. Backup auth_info (WhatsApp Session)
```bash
# Backup setiap hari
cd /var/www/baileys-server
tar -czf auth_info_backup_$(date +%Y%m%d).tar.gz auth_info/

# Simpan di tempat aman
mv auth_info_backup_*.tar.gz ~/backups/

# Cron untuk auto backup (setiap hari jam 3 pagi)
crontab -e
# Tambahkan:
0 3 * * * cd /var/www/baileys-server && tar -czf ~/backups/auth_info_$(date +\%Y\%m\%d).tar.gz auth_info/
```

### 2. Restore auth_info
```bash
# Jika auth_info rusak/hilang
cd /var/www/baileys-server
rm -rf auth_info
tar -xzf ~/backups/auth_info_20250128.tar.gz
pm2 restart baileys-whatsapp
```

---

## 📊 MONITORING

### 1. Setup PM2 Monitoring
```bash
# Register ke PM2.io (optional, gratis)
pm2 link YOUR_SECRET_KEY YOUR_PUBLIC_KEY

# Atau cek via CLI
pm2 monit  # Real-time monitoring
```

### 2. Laravel Logs
```bash
# Check error logs
tail -f /var/www/bumisultansuperapp/storage/logs/laravel.log

# Filter error saja
grep "ERROR" /var/www/bumisultansuperapp/storage/logs/laravel.log
```

### 3. Baileys Logs
```bash
# Real-time logs
pm2 logs baileys-whatsapp --lines 100

# Error logs
cat ~/.pm2/logs/baileys-whatsapp-error.log
```

---

## ✅ CHECKLIST DEPLOYMENT SELESAI

- [ ] Node.js 18+ terinstall
- [ ] PM2 terinstall global
- [ ] Laravel project uploaded & composer install
- [ ] Baileys server uploaded & npm install
- [ ] .env Laravel sudah di-configure
- [ ] BAILEYS_API_URL sudah di set di .env
- [ ] Database migrations sudah di-run
- [ ] PM2 start baileys-whatsapp berhasil
- [ ] QR code sudah di-scan & connected
- [ ] PM2 startup & save sudah di-run
- [ ] Test send message berhasil
- [ ] Firewall configured (jika perlu)
- [ ] SSL certificate installed (production)
- [ ] Backup auth_info setup
- [ ] Monitoring aktif

---

## 📞 CARA PAKAI SETELAH LIVE

### Untuk User (Super Admin)

1. **Login ke aplikasi**
   - URL: https://yourdomain.com

2. **Buka menu WhatsApp**
   - Klik sidebar → WhatsApp

3. **Sync Contacts**
   - Klik "Contacts" → Tombol "Sync dari Karyawan"
   - Tunggu sampai selesai

4. **Sync Groups**
   - Klik "Devices" → Pilih device → Dropdown → "Sync Groups"
   - Semua grup WhatsApp yang Anda ikuti akan masuk

5. **Kirim Broadcast**
   - Klik "Broadcasts" → "Buat Broadcast Baru"
   - Pilih target (All / Departemen / Jabatan / Grup)
   - Tulis pesan
   - Klik "Kirim Sekarang" atau "Jadwalkan"

6. **Monitoring**
   - Dashboard akan tampilkan statistik
   - Total pesan terkirim, pending, failed
   - Recent messages

---

## 🔄 UPDATE APLIKASI (Jika Ada Perubahan)

```bash
# 1. Backup dulu
cd /var/www/bumisultansuperapp
php artisan down

# 2. Pull changes atau upload file baru
git pull origin main
# Atau upload via FTP

# 3. Update dependencies
composer install --optimize-autoloader --no-dev
npm install --production

# 4. Run migrations (jika ada)
php artisan migrate --force

# 5. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:cache
php artisan config:cache

# 6. Restart PM2 (jika ada update Baileys)
cd /var/www/baileys-server
npm install --production
pm2 restart baileys-whatsapp

# 7. Up aplikasi
cd /var/www/bumisultansuperapp
php artisan up
```

---

## 💰 ESTIMASI BIAYA BULANAN

| Item | Harga |
|------|-------|
| VPS 2GB RAM (Contoh: Vultr/DigitalOcean) | Rp 70.000 - 100.000/bulan |
| Domain .com | Rp 150.000/tahun ≈ Rp 12.500/bulan |
| SSL Certificate | GRATIS (Let's Encrypt) |
| **TOTAL** | **Rp 82.500 - 112.500/bulan** |

> ⚠️ **CATATAN**: Ini adalah estimasi. Baileys adalah **100% GRATIS**, tidak ada biaya API seperti Fonnte atau Wablas.

---

## 📚 REFERENSI

- **Baileys Documentation**: https://github.com/WhiskeySockets/Baileys
- **PM2 Documentation**: https://pm2.keymetrics.io/docs/usage/quick-start/
- **Laravel Deployment**: https://laravel.com/docs/10.x/deployment

---

**🎉 SELAMAT! SISTEM WHATSAPP BROADCAST ANDA SUDAH PRODUCTION-READY!**
