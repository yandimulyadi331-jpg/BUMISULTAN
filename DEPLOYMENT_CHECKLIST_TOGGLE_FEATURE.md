# ✅ DEPLOYMENT CHECKLIST: Toggle Checklist Feature

## Pre-Deployment

### Database
- [ ] Migrate database (jika belum ada tabel `checklist_periode_config`)
  ```bash
  php artisan migrate
  ```
  
- [ ] Seed initial data untuk 4 periode
  ```php
  // Jalankan di tinker atau seeder
  foreach(['harian', 'mingguan', 'bulanan', 'tahunan'] as $tipe) {
      ChecklistPeriodeConfig::updateOrCreate(
          ['tipe_periode' => $tipe],
          ['is_enabled' => true, 'is_mandatory' => false]
      );
  }
  ```

### Code Changes
- [ ] Review blade template changes (`perawatan/master/index.blade.php`)
- [ ] Review controller changes (`ManajemenPerawatanController.php`)
- [ ] Review route changes (`routes/web.php`)
- [ ] Check Model exists (`ChecklistPeriodeConfig.php`)
- [ ] Verify import statements in controller
- [ ] Check CSRF token in meta tag (for AJAX)

### Dependencies
- [ ] Laravel Echo installed (untuk WebSocket broadcast)
- [ ] Pusher or WebSocket server configured
- [ ] SweetAlert2 library available (untuk toast)
- [ ] Bootstrap 5+ for toggle switch styling

### Testing
- [ ] Test toggle ON action
- [ ] Test toggle OFF action
- [ ] Test all 4 periods (Harian, Mingguan, Bulanan, Tahunan)
- [ ] Test error handling (network error)
- [ ] Test CSRF protection
- [ ] Test role-based access (non-admin should not access)
- [ ] Test real-time sync (multiple browser tabs)
- [ ] Test mobile responsiveness

---

## Deployment Steps

### 1. Code Deploy
```bash
# Backup current code
git stash

# Pull latest changes
git pull origin main

# Install dependencies (if needed)
composer install

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### 2. Database Migration
```bash
# Check pending migrations
php artisan migrate:status

# Run migrations
php artisan migrate

# Seed config data
php artisan tinker
# Paste seeding code
```

### 3. Verification
```bash
# Check routes
php artisan route:list | grep perawatan

# Check model
php artisan tinker
> ChecklistPeriodeConfig::all()

# Check view compile
php artisan view:cache
```

### 4. Cache Warming
```bash
php artisan config:cache
php artisan route:cache
```

---

## Post-Deployment Testing

### Manual Testing
- [ ] Login as super admin
- [ ] Navigate to `/perawatan/master`
- [ ] Verify toggle switches appear for all 4 periods
- [ ] Click toggle Harian ON → Verify all updates
  - [ ] Badge changes: ❌ → ✅
  - [ ] Count updates: (0) → (18)
  - [ ] Toast notification shows
  - [ ] Database updated (check DB)
- [ ] Click toggle Harian OFF → Verify all updates
  - [ ] Badge changes: ✅ → ❌
  - [ ] Count updates: (18) → (0)
  - [ ] Toast notification shows
  - [ ] Database updated (check DB)
- [ ] Test all 4 periods individually
- [ ] Test multiple browser tabs (real-time sync)
- [ ] Open karyawan checklist page and verify items appear/disappear

### Browser Testing
- [ ] Chrome
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile browsers (iOS Safari, Chrome Mobile)

### Performance Testing
- [ ] Check database query count
- [ ] Verify AJAX response time < 500ms
- [ ] Check WebSocket broadcast latency

---

## Monitoring

### After Deployment
- [ ] Monitor error logs for 24 hours
  ```bash
  tail -f storage/logs/laravel.log
  ```
  
- [ ] Check database for toggle records
  ```sql
  SELECT * FROM checklist_periode_config;
  ```
  
- [ ] Monitor broadcast events
  ```bash
  # If using Pusher, check Pusher debug console
  ```
  
- [ ] Check admin user activity
  ```sql
  SELECT * FROM checklist_periode_config 
  ORDER BY updated_at DESC 
  LIMIT 5;
  ```

---

## Rollback Plan

### If Issues Occur
```bash
# Revert code changes
git revert HEAD~1

# Clear all cache
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Restart PHP-FPM (if using)
sudo systemctl restart php8.2-fpm

# OR restart Docker container
docker restart app
```

### Database Rollback
```bash
# If migration added unwanted tables
php artisan migrate:rollback --step=1

# OR manually drop table
DROP TABLE checklist_periode_config;
```

---

## Success Criteria

✅ All tests pass  
✅ Toggle feature works for all 4 periods  
✅ Real-time sync works across tabs/windows  
✅ Error handling works correctly  
✅ Database records correctly updated  
✅ No console errors  
✅ No server errors (HTTP 500)  
✅ CSRF protection working  
✅ Mobile responsive  
✅ Performance acceptable (< 500ms per request)  

---

## Documentation

- [ ] README updated
- [ ] API documentation updated
- [ ] User guide created/updated
- [ ] Team notified about new feature
- [ ] Changelog updated

---

## Team Communication

### Admin/SuperUser Notification
```
Subject: ✅ Toggle Checklist Feature Released

Dear Admins,

Fitur baru toggle checklist telah dirilis di halaman Master Checklist (/perawatan/master).

Fitur Baru:
- Toggle ON/OFF untuk setiap periode (Harian, Mingguan, Bulanan, Tahunan)
- Real-time update ketika toggle diubah
- Count items otomatis berkurang/bertambah
- Status badge menampilkan Aktif/Nonaktif

Cara Menggunakan:
1. Buka /perawatan/master
2. Lihat toggle switch di setiap tab periode
3. Klik toggle untuk ON/OFF
4. Sistem akan otomatis update count dan real-time sync

Testing:
- Toggle sudah ditest di semua browser
- Real-time sync verified
- Mobile responsive
- Production ready

Questions? Contact developer team.
```

### Employee Notification (Optional)
```
Subject: 📋 Perubahan Sistem Checklist Perawatan

Karyawan,

Sistem checklist perawatan akan berkembang dengan fitur baru:

Perubahan:
- Checklist dapat di-ON/OFF oleh admin per periode
- Jika OFF: Anda tidak perlu mengerjakan checklist
- Jika ON: Checklist akan wajib diselesaikan

Apa yang perlu Anda lakukan:
- Cek halaman checklist perawatan sebelum checkout
- Jika ada notifikasi checklist wajib, selesaikan terlebih dahulu

Terima kasih atas perhatian Anda.
```

---

## Metrics to Track (Post-Deployment)

- [ ] Admin toggles per period (harian/mingguan/bulanan/tahunan)
- [ ] Average response time for toggle request
- [ ] Number of errors/failures
- [ ] WebSocket broadcast latency
- [ ] Employee compliance with checklist after toggle enabled
- [ ] System uptime

---

## Troubleshooting

### Issue: Toggle tidak muncul
**Solution:**
```
1. Clear browser cache (Ctrl+F5)
2. php artisan view:clear
3. Check JS console for errors
4. Verify ChecklistPeriodeConfig table exists
```

### Issue: AJAX request fails
**Solution:**
```
1. Check CSRF token in HTML (meta tag)
2. Verify route exists: php artisan route:list
3. Check Laravel logs: tail -f storage/logs/laravel.log
4. Verify role authorization
```

### Issue: Count badge tidak update
**Solution:**
```
1. Check database query (SELECT count for active masters)
2. Verify MasterPerawatan model scope
3. Check response JSON from backend
4. Debug JS: console.log(data)
```

### Issue: Real-time sync tidak bekerja
**Solution:**
```
1. Check WebSocket connection (Browser DevTools → Network → WS)
2. Verify Laravel Echo setup
3. Check Pusher/WebSocket server status
4. Fallback polling if WebSocket unavailable
```

---

## Version Information

- **Feature Version**: 1.0.0
- **Release Date**: January 24, 2026
- **Laravel Version**: 11.x
- **PHP Version**: 8.2+
- **Database**: MySQL 8.0+

---

## Sign-off

- [ ] Requirement Approved
- [ ] Code Review Passed
- [ ] QA Testing Completed
- [ ] Production Deployment Approved
- [ ] Monitoring Confirmed

---

**Deployment Manager**: _______________  
**Date**: _______________  
**Notes**: 

_____________________________________________________________________________

_____________________________________________________________________________

---

**Status**: 🟢 **READY FOR PRODUCTION**  
**Last Updated**: January 24, 2026
