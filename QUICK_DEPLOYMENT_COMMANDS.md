# ⚡ QUICK DEPLOYMENT COMMANDS

## 🚀 DEPLOYMENT CHECKLIST - Copy & Paste

### Step 1: Clear Cache & Config
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Step 2: Verify Routes Registered
```bash
php artisan route:list | grep pinjaman
```

Expected Output (should see these 4 routes):
```
GET|HEAD  /pinjaman/api/laporan-pinjaman
GET|HEAD  /pinjaman/api/verifikasi-akurasi-pinjaman/{pinjaman}
GET|HEAD  /pinjaman/api/rincian-pelunasan-awal/{pinjaman}
GET|HEAD  /pinjaman/api/detail-cicilan/{cicilan}
```

### Step 3: Restart Server
```bash
# Press Ctrl+C to stop current server
# Then run:
php artisan serve --host=127.0.0.1 --port=8000
```

### Step 4: Test All Endpoints
```bash
# Open browser and test these URLs:

# Test 1: Real-time laporan (replace 1 with actual pinjaman_id)
http://localhost:8000/pinjaman/api/laporan-pinjaman

# Test 2: Verifikasi akurasi (replace 1 with actual pinjaman_id)
http://localhost:8000/pinjaman/api/verifikasi-akurasi-pinjaman/1

# Test 3: Rincian pelunasan awal (replace 1 with actual pinjaman_id)
http://localhost:8000/pinjaman/api/rincian-pelunasan-awal/1

# Test 4: Detail cicilan (replace 1 with actual cicilan_id)
http://localhost:8000/pinjaman/api/detail-cicilan/1
```

### Step 5: Monitor Logs
```bash
# Keep watching logs in separate terminal
tail -f storage/logs/laravel.log
```

---

## 📋 VERIFICATION COMMANDS

### Check EventServiceProvider Status
```bash
# Check if listener is registered
php artisan event:list
```

### Check Database
```bash
# If you're using MySQL:
mysql -u root -p

# Then run:
SELECT * FROM pinjaman LIMIT 1;
SELECT * FROM pinjaman_cicilan WHERE pinjaman_id = 1 LIMIT 1;
SELECT * FROM pinjaman_history WHERE pinjaman_id = 1 ORDER BY tanggal_aksi DESC LIMIT 1;
```

### Check File Permissions
```bash
# Ensure storage is writable
chmod -R 777 storage/
chmod -R 777 bootstrap/cache/
```

---

## 🧪 QUICK TEST SCENARIO

### Setup Test Pinjaman:
```sql
-- Create test pinjaman via MySQL:
INSERT INTO pinjaman (
  nomor_pinjaman, 
  peminjam_id, 
  total_pinjaman, 
  total_terbayar, 
  sisa_pinjaman, 
  tenor_bulan, 
  cicilan_ke, 
  status, 
  created_at
) VALUES (
  'TEST-001',
  1,
  6000000,
  0,
  6000000,
  3,
  0,
  'approved',
  NOW()
);

-- Get last inserted ID and note it
SELECT LAST_INSERT_ID();
```

### Create Cicilan Manually (if needed):
```sql
-- Replace {pinjaman_id} with actual ID
INSERT INTO pinjaman_cicilan (
  pinjaman_id,
  cicilan_ke,
  jumlah_cicilan,
  jumlah_dibayar,
  sisa_cicilan,
  status,
  tanggal_jatuh_tempo,
  created_at
) VALUES 
  ({pinjaman_id}, 1, 2000000, 0, 2000000, 'belum_bayar', DATE_ADD(NOW(), INTERVAL 1 MONTH), NOW()),
  ({pinjaman_id}, 2, 2000000, 0, 2000000, 'belum_bayar', DATE_ADD(NOW(), INTERVAL 2 MONTH), NOW()),
  ({pinjaman_id}, 3, 2000000, 0, 2000000, 'belum_bayar', DATE_ADD(NOW(), INTERVAL 3 MONTH), NOW());
```

### Test Payment Processing:
```bash
# Via Laravel tinker:
php artisan tinker

# Then run:
$cicilan = App\Models\PinjamanCicilan::find(1);
$cicilan->prosesPembayaran(3000000, 'transfer', 'TEST-REF');

# Then verify:
App\Models\PinjamanCicilan::where('pinjaman_id', 1)->get();
```

---

## 🔍 TROUBLESHOOTING COMMANDS

### If Routes Not Found (404):
```bash
# 1. Clear routes cache
php artisan route:clear

# 2. Verify routes file syntax
php artisan route:list | head -20

# 3. Check if middleware is correct
grep -n "middleware('auth')" routes/web.php
```

### If Event Not Triggering:
```bash
# 1. Check EventServiceProvider
cat app/Providers/EventServiceProvider.php

# 2. Check if listener exists
ls -la app/Listeners/UpdateLaporanPinjaman.php

# 3. Check if event exists
ls -la app/Events/PinjamanPaymentUpdated.php
```

### If Cache Issues:
```bash
# 1. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Restart queue workers (if using queue)
php artisan queue:restart

# 3. Verify cache is working
php artisan tinker
cache()->put('test', 'value');
cache()->get('test');
```

### If Database Issues:
```bash
# 1. Check connection
php artisan tinker
DB::connection()->getPdo();

# 2. Check migrations
php artisan migrate:status

# 3. Check table structure
php artisan tinker
DB::table('pinjaman_cicilan')->first();
```

### If API Returns 500 Error:
```bash
# 1. Check logs
tail -50 storage/logs/laravel.log

# 2. Enable debug mode (only for dev!)
# In .env:
APP_DEBUG=true

# 3. Check syntax
php -l app/Http/Controllers/PinjamanController.php
php -l app/Traits/PelunasanAwalHelper.php
php -l app/Traits/PinjamanAccuracyHelper.php
```

---

## 📊 DATABASE VERIFICATION QUERIES

### Check Pinjaman Table:
```sql
SELECT 
  id,
  nomor_pinjaman,
  total_pinjaman,
  total_terbayar,
  sisa_pinjaman,
  status,
  created_at
FROM pinjaman 
LIMIT 5;
```

### Check Cicilan Table:
```sql
SELECT 
  id,
  pinjaman_id,
  cicilan_ke,
  jumlah_cicilan,
  jumlah_dibayar,
  sisa_cicilan,
  status,
  tanggal_jatuh_tempo
FROM pinjaman_cicilan
WHERE pinjaman_id = 1
ORDER BY cicilan_ke;
```

### Verify Nominal Accuracy:
```sql
-- Check if Total = Dibayar + Sisa for all pinjaman
SELECT 
  p.id,
  p.total_pinjaman,
  p.total_terbayar,
  p.sisa_pinjaman,
  SUM(pc.jumlah_dibayar) AS sum_dibayar,
  SUM(pc.sisa_cicilan) AS sum_sisa,
  (p.total_terbayar + p.sisa_pinjaman) AS total_check,
  CASE 
    WHEN p.total_pinjaman = (p.total_terbayar + p.sisa_pinjaman) THEN 'AKURAT'
    ELSE 'ERROR'
  END AS status
FROM pinjaman p
LEFT JOIN pinjaman_cicilan pc ON p.id = pc.pinjaman_id
GROUP BY p.id;
```

### Check History/Audit Trail:
```sql
SELECT 
  id,
  pinjaman_id,
  aksi,
  keterangan,
  data_perubahan,
  tanggal_aksi
FROM pinjaman_history
WHERE pinjaman_id = 1
ORDER BY tanggal_aksi DESC
LIMIT 10;
```

---

## 🎯 QUICK TEST SCRIPT

Save this as `test-payment.php` and run: `php test-payment.php`

```php
<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Test payment processing
use App\Models\PinjamanCicilan;
use App\Models\Pinjaman;

$pinjaman = Pinjaman::first();
if ($pinjaman) {
    $cicilan = $pinjaman->cicilan()->first();
    if ($cicilan) {
        echo "Testing pelunasan awal...\n";
        echo "Pinjaman ID: " . $pinjaman->id . "\n";
        echo "Cicilan Ke: " . $cicilan->cicilan_ke . "\n";
        echo "Cicilan Normal: Rp " . number_format($cicilan->jumlah_cicilan) . "\n";
        
        try {
            $result = $cicilan->prosesPembayaran(3000000, 'transfer', 'TEST-REF');
            echo "✅ Payment processed successfully\n";
            echo "Result: " . json_encode($result) . "\n";
        } catch (\Exception $e) {
            echo "❌ Error: " . $e->getMessage() . "\n";
        }
    }
}
```

---

## 📝 CHECKLIST - Copy This!

```
DEPLOYMENT CHECKLIST:

1. [ ] php artisan cache:clear
2. [ ] php artisan config:clear
3. [ ] php artisan route:clear
4. [ ] Restart Laravel server
5. [ ] php artisan route:list | grep pinjaman (verify 4 routes)
6. [ ] Check EventServiceProvider.php (listener registered)
7. [ ] Browser test: /pinjaman/api/laporan-pinjaman
8. [ ] Browser test: /pinjaman/api/verifikasi-akurasi-pinjaman/1
9. [ ] Browser test: /pinjaman/api/rincian-pelunasan-awal/1
10. [ ] Browser test: /pinjaman/api/detail-cicilan/1
11. [ ] Create test pinjaman (Rp 6M, 3 cicilan)
12. [ ] Test payment: Rp 3M on Rp 2M cicilan
13. [ ] Verify cicilan 1 = LUNAS
14. [ ] Verify cicilan 2 = SEBAGIAN
15. [ ] Verify nominal accuracy (Total = Dibayar + Sisa)
16. [ ] Check audit trail: pinjaman_history
17. [ ] Monitor logs: tail -f storage/logs/laravel.log
18. [ ] All tests pass ✅
19. [ ] Ready for production ✅
```

---

## 🚨 EMERGENCY COMMANDS

### If Everything Broken:
```bash
# 1. Reset everything
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Clear compiled files
rm -rf bootstrap/cache/*

# 3. Reinstall composer (if needed)
composer install

# 4. Restart server
pkill -f "php artisan serve"
php artisan serve --host=127.0.0.1 --port=8000

# 5. Watch logs
tail -f storage/logs/laravel.log
```

### Database Reset (Development Only!):
```bash
# WARNING: This will DELETE all data!
php artisan migrate:refresh

# Then seed if available
php artisan db:seed
```

---

## 📞 SUCCESS INDICATORS

✅ All commands above execute without errors
✅ 4 routes appear in `php artisan route:list | grep pinjaman`
✅ API endpoints return 200 OK with JSON response
✅ Test payment processes without errors
✅ Cicilan status changes in database
✅ Audit trail record created
✅ No errors in `storage/logs/laravel.log`
✅ Nominal accuracy verified (Total = Dibayar + Sisa)

If all above ✅, **YOU'RE GOOD TO GO! DEPLOY!**

---

**Good Luck!** 🎉

Last Updated: 2026-01-20 15:45
