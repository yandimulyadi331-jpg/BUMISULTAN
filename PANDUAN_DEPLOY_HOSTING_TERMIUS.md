# 📱 PANDUAN DEPLOY KE HOSTING VIA TERMIUS

## 🎯 Tujuan
Mensinkronisasi perubahan pop-up checklist dari local ke server hosting menggunakan Termius (SSH Client).

---

## 📋 Prerequisites Checklist

Pastikan Anda sudah siapkan:
- [ ] Termius sudah installed (download dari: https://www.termius.com/)
- [ ] SSH credentials (host, username, password/key)
- [ ] Git sudah installed di server hosting
- [ ] Access ke project folder di hosting

---

## 🔧 STEP-BY-STEP DEPLOYMENT

### STEP 1: Commit Perubahan di Local

Sebelum deploy, commit semua perubahan di local:

```bash
cd d:\bumisultanAPP\bumisultanAPP

# Cek status
git status

# Add semua perubahan
git add .

# Commit dengan pesan yang jelas
git commit -m "feat: Add checklist notification modal to dashboard

- Add modal popup for incomplete checklist
- Implement API endpoint /api/checklist/status
- Add CSS animations and styling
- Add JavaScript event handling
- Include comprehensive documentation
- Support dark mode and mobile responsive"

# Push ke repository (GitHub/GitLab)
git push origin main
```

**Output yang diharapkan:**
```
✓ Files changed: 2-3
✓ Insertions: 500+
✓ Pushed successfully
```

---

### STEP 2: Open Termius & Connect to Hosting

#### Option A: Jika sudah ada SSH key setup

```
1. Buka Termius
2. Click: "+ New Host"
3. Isi:
   - Title: "BumiSultan Production"
   - Hostname: [host.domain.com] (dari provider)
   - Port: 22 (default SSH)
   - Username: [username dari provider]
   - Password: [password dari provider]
     ATAU
   - Key: [pilih SSH key jika sudah ada]
4. Click: "Save"
5. Click: "Connect"
```

#### Option B: Jika menggunakan password

```
Termius akan meminta password saat connect pertama kali.
```

---

### STEP 3: Navigasi ke Project Folder

Setelah terhubung SSH:

```bash
# List directory
ls -la

# Navigate ke project folder (sesuaikan path hosting Anda)
cd /home/username/public_html/bumisultanAPP
# ATAU
cd /home/username/bumisultan.com
# ATAU path lain sesuai struktur hosting Anda

# Verifikasi lokasi
pwd
```

**Expected output:**
```
/home/username/public_html/bumisultanAPP
```

---

### STEP 4: Pull Perubahan dari Git

```bash
# Check status repo
git status

# Pull latest code dari repository
git pull origin main
```

**Expected output:**
```
Updating abc1234..def5678
Fast-forward
 resources/views/dashboard/karyawan.blade.php | 310 insertions(+)
 routes/api.php                                |   3 insertions(+)
 app/Http/Controllers/Api/ChecklistController.php | 88 insertions(+)
 3 files changed, 401 insertions(+)
```

---

### STEP 5: Cache Configuration

Laravel harus me-refresh cache configuration:

```bash
# Cache configuration
php artisan config:cache

# (Optional) Clear all cache
php artisan cache:clear

# (Optional) Clear view cache
php artisan view:clear
```

**Expected output:**
```
✓ Configuration cached successfully
✓ Application cache cleared
✓ Compiled views cleared
```

---

### STEP 6: Verify Routes

Pastikan route sudah terdaftar:

```bash
# List routes yang berhubungan dengan checklist
php artisan route:list | grep checklist
```

**Expected output:**
```
POST   api/checklist/status   api.checklist.status  App\Http\Controllers\Api\ChecklistController@checkStatus
GET    perawatan/karyawan/checklist/{tipe}  perawatan.karyawan.checklist
```

---

### STEP 7: Verify File Structure

Check apakah semua file sudah ada:

```bash
# Check API controller
ls -la app/Http/Controllers/Api/ChecklistController.php

# Check view file
ls -la resources/views/dashboard/karyawan.blade.php

# Check route file
ls -la routes/api.php

# List semua documentation files
ls -la | grep POPUP
ls -la | grep CHECKLIST
```

**Expected output:**
```
✓ ChecklistController.php exists (88 lines)
✓ karyawan.blade.php exists
✓ api.php exists
✓ Documentation files exists (7 files)
```

---

## 🧪 TESTING DEPLOYMENT

### Test 1: Check Dashboard Page

```bash
# Cek apakah blade file ada dan bisa diakses
curl -I https://bumisultan.com/dashboard
```

**Expected**: HTTP 200 OK

### Test 2: Test API Endpoint

```bash
# Get authentication token (replace dengan token Anda)
TOKEN="your_auth_token_here"

# Test API endpoint
curl -X POST https://bumisultan.com/api/checklist/status \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"date":"2026-01-15"}'
```

**Expected Response:**
```json
{
  "hasIncompleteChecklist": true,
  "shouldShowModal": true,
  "checklistInfo": {
    "total": 50,
    "completed": 34,
    "remaining": 16,
    "percentageRemaining": 32,
    "percentageCompleted": 68
  }
}
```

### Test 3: Test di Browser

```
1. Login ke aplikasi
2. Buka dashboard: https://bumisultan.com/dashboard
3. Lihat apakah modal muncul saat ada checklist belum selesai
4. Test button "Pulang" (harus close modal)
5. Test button "Selesaikan" (harus redirect ke /perawatan/karyawan/checklist/harian)
```

---

## 🔍 TROUBLESHOOTING DEPLOYMENT

### Problem 1: Git Pull Error "Permission Denied"

```bash
# Solution: Check git config
git config --list | grep user

# Set git user jika belum
git config --global user.name "Your Name"
git config --global user.email "your@email.com"

# Coba pull lagi
git pull origin main
```

### Problem 2: "No changes in git"

```bash
# Check if changes committed locally
git log --oneline | head -5

# If not yet pushed locally, check git status
git status

# Force sync with remote
git fetch origin
git reset --hard origin/main
```

### Problem 3: "Route not registered after pull"

```bash
# Clear ALL Laravel cache
php artisan cache:clear
php artisan config:cache
php artisan route:clear
php artisan view:clear

# Check route again
php artisan route:list | grep checklist
```

### Problem 4: "API returns error 500"

```bash
# Check Laravel logs
tail -f storage/logs/laravel.log

# Or lihat recent errors
tail -20 storage/logs/laravel.log

# If database issue, check migrations
php artisan migrate:status

# If no migration needed, restart web server
# (contact hosting provider untuk ini)
```

---

## ✅ VERIFICATION CHECKLIST

Setelah deployment, verify semua ini:

- [ ] `git pull` berhasil tanpa error
- [ ] `php artisan config:cache` berhasil
- [ ] Routes terdaftar: `php artisan route:list | grep checklist`
- [ ] File ada: `ls app/Http/Controllers/Api/ChecklistController.php`
- [ ] API endpoint accessible: curl test berhasil
- [ ] Dashboard bisa diakses di browser
- [ ] Modal muncul saat ada checklist belum selesai
- [ ] Button "Pulang" berfungsi (close modal)
- [ ] Button "Selesaikan" redirect ke checklist page
- [ ] Error logs tidak ada error baru

---

## 🆘 IF SOMETHING GOES WRONG - ROLLBACK

Jika deployment gagal dan ingin rollback ke versi sebelumnya:

```bash
# See commit history
git log --oneline | head -10

# Revert ke commit sebelumnya (ganti hash dengan commit lama)
git revert abc1234

# Or force reset
git reset --hard origin/main~1

# Clear cache
php artisan config:cache

# Verify
php artisan route:list | grep checklist
```

---

## 📊 SUMMARY COMMAND

Quick reference semua command:

```bash
# 1. Navigate to project
cd /path/to/bumisultanAPP

# 2. Check git status
git status

# 3. Pull latest code
git pull origin main

# 4. Cache config
php artisan config:cache

# 5. Verify routes
php artisan route:list | grep checklist

# 6. Check file
ls -la app/Http/Controllers/Api/ChecklistController.php

# 7. View logs if error
tail -20 storage/logs/laravel.log
```

---

## 💡 PRO TIPS

### Tip 1: Use SSH Key instead of Password
```bash
# More secure, no password needed setiap kali
# Contact hosting provider untuk setup SSH key
```

### Tip 2: Keep Terminal Session Open in Termius
```bash
# Di Termius, jangan close terminal window
# Tinggal minimize, bisa reconnect dengan cepat
```

### Tip 3: Monitor Logs in Real-time
```bash
# Buka 2 terminal: satu untuk command, satu untuk logs
# Terminal 1: tail -f storage/logs/laravel.log
# Terminal 2: untuk run command lainnya
```

### Tip 4: Test di Staging Dulu
```bash
# Jangan langsung ke production
# Test di staging environment dulu
# Baru deploy ke production setelah verified
```

---

## 📞 COMMON HOSTING PROVIDERS

**Path untuk berbagai provider:**

| Provider | Project Path | Database |
|----------|-------------|----------|
| Hostinger | `/home/user/public_html/` | cPanel |
| Niagahoster | `/home/user/public_html/` | cPanel |
| Domainesia | `/home/user/public_html/` | cPanel |
| Cloudways | `/home/master/applications/appname/public` | SSH |
| DigitalOcean | Tergantung setup | Custom |

**Cek path hosting Anda:**
```bash
# Setelah SSH connect
pwd
# Ini akan show current path
```

---

## 🎓 TERMIUS FEATURES

### Save SSH Connection
```
1. Connect ke host
2. Click "Save" (jika belum)
3. Next time, langsung click host dari list
4. Connect otomatis
```

### Copy-Paste in Termius
```
- Copy from Windows: Ctrl+C
- Paste in Termius: Ctrl+Shift+V (atau Click Paste)
- Copy from Termius: Select text, Ctrl+C
- Paste to Windows: Ctrl+V
```

### Run Multiple Commands
```bash
# Bisa run commands satu per satu atau piping
command1 | command2 | command3

# Atau gunakan &&
command1 && command2 && command3
```

---

## ✨ DEPLOYMENT AUTOMATION

Untuk future deployment, buat script:

**File: `deploy.sh` di hosting**
```bash
#!/bin/bash
cd /path/to/bumisultanAPP
git pull origin main
php artisan config:cache
php artisan cache:clear
echo "✓ Deployment successful!"
```

**Kemudian tinggal run:**
```bash
./deploy.sh
```

---

## 📈 MONITORING AFTER DEPLOYMENT

Monitor selama 1 minggu:

```bash
# Daily check logs
tail -100 storage/logs/laravel.log

# Check API usage
tail -f storage/logs/laravel.log | grep checklist

# Monitor errors
grep -i "error\|exception" storage/logs/laravel.log | tail -20
```

---

## 🎯 DEPLOYMENT CHECKLIST

Sebelum mulai:
- [ ] Code sudah committed & pushed ke git
- [ ] Termius sudah installed
- [ ] SSH credentials siap
- [ ] Tahu path project di hosting
- [ ] Bisa akses terminal hosting

Saat deployment:
- [ ] Connect via SSH
- [ ] Navigate ke project folder
- [ ] Git pull berhasil
- [ ] Config cache berhasil
- [ ] Routes registered
- [ ] Files ada

Setelah deployment:
- [ ] Test dashboard di browser
- [ ] Test API endpoint
- [ ] Test modal display
- [ ] Test button functionality
- [ ] Check error logs
- [ ] Monitor selama 1 minggu

---

**Selamat! Perubahan sudah ter-deploy ke hosting! 🚀**

Jika ada masalah, check logs atau contact hosting support Anda.
