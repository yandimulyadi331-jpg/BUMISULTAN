# Deployment Guide: Modal Checkout Feature

## Overview
Feature untuk menampilkan modal konfirmasi checkout dengan 2 button:
- **Pulang** - Absen pulang langsung (tanpa checklist 100%)
- **Kerjakan** - Lanjut ke halaman checklist

## Commit Info
- **Commit Hash**: `a8d656b`
- **Push Date**: Hari ini
- **Files Modified**: 3 files
- **Lines Added**: 202

## Modified Files

### 1. app/Http/Controllers/PresensiController.php (Line 484)
**Changes**: Modified response when checklist tidak 100% selesai
```php
// Sebelum:
return response()->json(['status' => false, 'message' => '...', ...], 400);

// Sesudah:
return response()->json([
    'status' => false, 
    'show_checkout_modal' => true,
    'message' => 'Checklist shift Anda belum 100% selesai',
    'detailed_message' => 'Selesaikan...',
    'notifikasi' => 'notifikasi_checklist_belum_lengkap'
], 400);
```

### 2. resources/views/qrpresensi/scan.blade.php (Line 595)
**Changes**: Add handler untuk redirect ke perawatan checklist dengan modal trigger
```javascript
if (result.show_checkout_modal) {
    window.location.href = '{{ route("perawatan.karyawan.checklist", "harian") }}?show_modal=checkout&msg=' + encodeURIComponent(result.message);
} else {
    showStatus(result.message, 'error');
}
```

### 3. resources/views/perawatan/karyawan/checklist.blade.php (Line 1198)
**Changes**: Auto-trigger modal dari URL parameter
```javascript
const urlParams = new URLSearchParams(window.location.search);
if (urlParams.get('show_modal') === 'checkout') {
    const msg = urlParams.get('msg');
    if (msg) {
        $('#checkoutMessageText').text(decodeURIComponent(msg));
    }
    $('#checkoutMessage').show();
    $('#modalCheckoutConfirm').modal('show');
}
```

## Server Deployment Steps

### Step 1: Git Pull
```bash
cd /home/bumisultan
git pull origin main
```
Expected output: Fast-forward 3 files, 202 insertions

### Step 2: Clear All Caches
```bash
# Clear config dan view cache
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Clear storage session
rm -rf storage/framework/sessions/*

# Clear storage views
rm -rf storage/framework/views/*
```

### Step 3: Restart Services
```bash
# Restart LiteSpeed
/usr/local/lsws/bin/lswsctrl restart

# If restart fails, try:
pkill -9 lsphp
sleep 2
/usr/local/lsws/bin/lswsctrl restart
```

### Step 4: Verify Files Updated
```bash
# Check PresensiController
grep -n "show_checkout_modal" app/Http/Controllers/PresensiController.php

# Check scan.blade.php
grep -n "show_checkout_modal" resources/views/qrpresensi/scan.blade.php

# Check checklist.blade.php
grep -n "show_modal=checkout" resources/views/perawatan/karyawan/checklist.blade.php
```

## Testing Checklist

### Test Case 1: Modal Appears
1. Login sebagai karyawan
2. Scan QR code untuk absen pulang
3. **Expected**: Modal dengan 2 button (Pulang & Kerjakan) muncul
4. **Actual**: [Document hasil testing]

### Test Case 2: Pulang Button
1. Dari modal checkout, click "Pulang" button
2. **Expected**: 
   - Modal closes
   - Absen pulang berhasil (message "Absen pulang berhasil")
   - Tidak perlu 100% checklist selesai
3. **Actual**: [Document hasil testing]

### Test Case 3: Kerjakan Button
1. Dari modal checkout, click "Kerjakan" button
2. **Expected**: 
   - Modal closes
   - User diarahkan ke halaman checklist
   - Checklist items ditampilkan
3. **Actual**: [Document hasil testing]

### Test Case 4: Upload Photo (No Size Limit)
1. Di halaman checklist, upload photo untuk task dengan ukuran > 2MB
2. **Expected**: Photo upload berhasil tanpa error size limit
3. **Actual**: [Document hasil testing]

## Troubleshooting

### Issue: Modal tidak muncul (masih error message)
**Possible causes:**
1. Cache belum clear
2. File belum terupdate di server
3. JavaScript tidak ter-load

**Solutions:**
```bash
# Clear more aggressively
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Check file permissions
ls -la resources/views/perawatan/karyawan/checklist.blade.php
ls -la app/Http/Controllers/PresensiController.php

# Restart LiteSpeed
/usr/local/lsws/bin/lswsctrl restart
```

### Issue: JavaScript error di console
**Check:**
1. Browser console (F12 > Console tab)
2. Server logs: `tail -f storage/logs/laravel.log`
3. LiteSpeed logs: `/usr/local/lsws/logs/error.log`

### Issue: Redirect tidak jalan
**Possibilities:**
1. Route "perawatan.karyawan.checklist" tidak ada
2. URL parameter tidak passing dengan benar

**Verify:**
```bash
# Check route exists
php artisan route:list | grep "perawatan.karyawan.checklist"

# Test URL encoding
# Make sure message parameter correctly encoded
```

## Rollback Plan (jika diperlukan)

Jika ada masalah setelah deployment:
```bash
cd /home/bumisultan
git reset --hard HEAD~1
php artisan config:clear
php artisan view:clear
/usr/local/lsws/bin/lswsctrl restart
```

## Success Indicators

✅ **Feature implemented successfully when:**
1. Modal appears saat user mencoba absen pulang dengan checklist belum 100%
2. "Pulang" button berfungsi - absen pulang tanpa perlu complete checklist
3. "Kerjakan" button berfungsi - redirect ke perawatan checklist page
4. Photo upload di checklist tidak ada batasan ukuran
5. Message dari error ditampilkan di modal

## Additional Notes

- Modal HTML sudah ada di checklist.blade.php (line 1135)
- Button handlers sudah ada (line 1407 untuk Pulang, line 1442 untuk Kerjakan)
- updateAbsenPulang() endpoint sudah ada di PresensiController (line 903)
- Route sudah terdaftar di routes/web.php

## Quick Reference

| Component | Status | Location |
|-----------|--------|----------|
| Modal HTML | ✅ Exists | checklist.blade.php:1135 |
| Modal Show Logic | ✅ Added | checklist.blade.php:1198 |
| Response Flag | ✅ Added | PresensiController.php:484 |
| Redirect Handler | ✅ Added | scan.blade.php:595 |
| Pulang Button Logic | ✅ Exists | checklist.blade.php:1407 |
| Kerjakan Button Logic | ✅ Exists | checklist.blade.php:1442 |
| updateAbsenPulang() | ✅ Exists | PresensiController.php:903 |
| Route | ✅ Exists | routes/web.php:547 |
