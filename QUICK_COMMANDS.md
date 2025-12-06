# 🚀 QUICK START COMMANDS - MANAJEMEN INVENTARIS

## ✅ SYSTEM STATUS CHECK

### 1. Check Routes
```bash
php artisan route:list | findstr inventaris
# Expected: 40+ routes
```

### 2. Check Controllers
```bash
Get-ChildItem "app\Http\Controllers\*Inventaris*.php" | Select-Object Name
# Expected: 5 files (without _full suffix)
```

### 3. Check Views
```bash
Get-ChildItem "resources\views\inventaris*" -Directory
Get-ChildItem "resources\views\*inventaris*" -Directory
# Expected: 5 directories
```

### 4. Access System
```
http://localhost/inventaris
http://localhost/peminjaman-inventaris
http://localhost/pengembalian-inventaris
http://localhost/inventaris-event
http://localhost/history-inventaris
http://localhost/history-inventaris/dashboard
```

---

## 📝 DEVELOPMENT COMMANDS

### Run Development Server
```bash
php artisan serve
# Access: http://127.0.0.1:8000
```

### Clear All Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### Run Migrations (if needed)
```bash
# Check status
php artisan migrate:status

# Run migrations
php artisan migrate

# Rollback if needed
php artisan migrate:rollback

# Fresh migrate (WARNING: deletes all data)
php artisan migrate:fresh
```

### Database Seeding (if you create seeders)
```bash
php artisan db:seed --class=InventarisSeeder
```

---

## 🔧 TROUBLESHOOTING

### Error: Class not found
```bash
composer dump-autoload
php artisan config:clear
```

### Error: Route not found
```bash
php artisan route:clear
php artisan route:cache
```

### Error: View not found
```bash
php artisan view:clear
```

### Error: Storage link
```bash
php artisan storage:link
```

### Check Permissions (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### Check Permissions (Windows)
```powershell
# Run as Administrator if needed
icacls "storage" /grant Users:F /t
icacls "bootstrap\cache" /grant Users:F /t
```

---

## 📊 TESTING COMMANDS

### Test Single Route
```bash
# Test inventaris index
curl http://localhost/inventaris

# Test with authentication
curl http://localhost/inventaris -H "Cookie: laravel_session=YOUR_SESSION"
```

### Tinker Testing
```bash
php artisan tinker

# Test model
>>> App\Models\Inventaris::count()
>>> App\Models\Inventaris::latest()->first()
>>> App\Models\PeminjamanInventaris::where('status', 'pending')->count()
```

---

## 🎯 NEXT DEVELOPMENT TASKS

### Task 1: Create Form Views (Priority HIGH)
```bash
# Create these files:
resources/views/inventaris/create.blade.php
resources/views/inventaris/edit.blade.php
resources/views/inventaris/show.blade.php
resources/views/inventaris/import-barang.blade.php

# Repeat for:
- peminjaman-inventaris (4 files)
- pengembalian-inventaris (4 files)
- inventaris-event (5 files)
- history-inventaris (3 files)
```

### Task 2: Create PDF Templates
```bash
# Create these files:
resources/views/inventaris/pdf.blade.php
resources/views/inventaris/aktivitas-pdf.blade.php
resources/views/peminjaman-inventaris/pdf.blade.php
resources/views/pengembalian-inventaris/pdf.blade.php
resources/views/inventaris-event/pdf.blade.php
resources/views/history-inventaris/pdf.blade.php
```

### Task 3: Add Signature Pad
```javascript
// Add to layout or specific views
<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

<canvas id="signature-pad" width="400" height="200"></canvas>

<script>
var canvas = document.getElementById('signature-pad');
var signaturePad = new SignaturePad(canvas);

// Save signature
document.getElementById('save-btn').addEventListener('click', function() {
    if (!signaturePad.isEmpty()) {
        var dataURL = signaturePad.toDataURL();
        document.getElementById('signature-input').value = dataURL;
    }
});

// Clear signature
document.getElementById('clear-btn').addEventListener('click', function() {
    signaturePad.clear();
});
</script>
```

---

## 📦 PACKAGE MANAGEMENT

### Install New Package
```bash
composer require vendor/package
composer dump-autoload
php artisan config:clear
```

### Update Packages
```bash
composer update
```

### Check Installed Packages
```bash
composer show
```

---

## 🔍 DEBUGGING COMMANDS

### Check Logs
```bash
# View latest log
Get-Content storage\logs\laravel.log -Tail 50

# Watch log in real-time
Get-Content storage\logs\laravel.log -Wait
```

### Clear Logs
```bash
Remove-Item storage\logs\laravel.log
```

### Debug Route
```bash
php artisan route:list --name=inventaris
php artisan route:list --path=inventaris
```

### Debug Config
```bash
php artisan config:show database
php artisan config:show filesystems
```

---

## 💾 BACKUP COMMANDS

### Backup Database
```bash
# MySQL
mysqldump -u root -p database_name > backup.sql

# Import
mysql -u root -p database_name < backup.sql
```

### Backup Files
```bash
# Backup storage
Compress-Archive -Path storage -DestinationPath storage_backup.zip

# Backup uploads
Compress-Archive -Path public\storage -DestinationPath uploads_backup.zip
```

---

## 🌐 DEPLOYMENT COMMANDS (Production)

### Optimize for Production
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Clear Production Cache
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
```

### Set Permissions (Linux)
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## 🎨 FRONTEND ASSETS

### Compile Assets (if using Vite/Mix)
```bash
# Development
npm run dev

# Production
npm run build

# Watch for changes
npm run watch
```

### Clear Node Modules
```bash
Remove-Item -Recurse -Force node_modules
Remove-Item package-lock.json
npm install
```

---

## 📈 MONITORING

### Check System Status
```bash
# Database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Queue workers
php artisan queue:work --once

# Scheduled tasks
php artisan schedule:list
```

### Performance Monitoring
```bash
# Check query count
php artisan debugbar:clear

# Check memory usage
php -i | findstr memory_limit
```

---

## 🔐 SECURITY

### Generate New APP_KEY
```bash
php artisan key:generate
```

### Clear Sensitive Data
```bash
php artisan cache:clear
php artisan config:clear
```

---

## 📞 HELP COMMANDS

### Laravel Help
```bash
php artisan list
php artisan help migrate
php artisan help make:controller
```

### Composer Help
```bash
composer help
composer help require
composer help update
```

---

## ✅ QUICK VERIFICATION

Run these commands to verify everything is working:

```bash
# 1. Check routes
php artisan route:list | findstr inventaris

# 2. Check autoload
composer dump-autoload

# 3. Clear cache
php artisan config:clear && php artisan cache:clear && php artisan route:clear

# 4. Test database
php artisan tinker
>>> App\Models\Inventaris::count()

# 5. Start server
php artisan serve
```

Then open: http://127.0.0.1:8000/inventaris

---

**Created:** November 6, 2025  
**For:** Bumi Sultan Super App v2 - Manajemen Inventaris  
**Quick Reference:** Keep this file handy for daily development!
