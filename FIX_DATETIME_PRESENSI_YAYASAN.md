# Fix Datetime Format Error - PresensiYayasan

## Problem
Error: `SQLSTATE[22007]: Invalid datetime format: 1292 Incorrect datetime value: '05:27:31'`

Root cause: Method `submitallmasuk()` dan `submitallpulang()` di `YayasanPresensiController` format jam_in/jam_out hanya TIME saja (`H:i:s`), padahal column di database adalah DATETIME.

## Solution Applied

### 1. YayasanPresensiController - submitallmasuk() (Line 831, 847)
**Before:**
```php
$jam_in = date('H:i:s', strtotime($masuk->scan_date));  // Format: 05:27:31
PresensiYayasan::create([
    'jam_in' => $jam_in,  // ❌ Error: DATETIME menerima format 'Y-m-d H:i:s'
]);
```

**After:**
```php
$jam_in = $tanggal . ' ' . date('H:i:s', strtotime($masuk->scan_date));  // Format: 2025-12-03 05:27:31
PresensiYayasan::create([
    'jam_in' => $jam_in,  // ✅ Accepted: DATETIME format complete
]);
```

### 2. YayasanPresensiController - submitallpulang() (Line 939, 949)
Same fix applied untuk `jam_out` column.

### 3. Blade Form - presensi/edit.blade.php (Line 83+)
Added client-side datetime format detection:
```javascript
if (jam_in && jam_in.indexOf(' ') === -1) {
    jam_in = tanggal + ' ' + jam_in;
    $(this).find("#jam_in").val(jam_in);
}
```

### 4. PresensiController (karyawan) - update() Method (Line 590+)
Applied same format detection logic for both jam_in and jam_out.

## Test Results
✅ Insert dengan DATETIME format (2025-12-03 14:30:00): Accepted
✅ Insert dengan TIME format saja (05:27:31): Rejected (as expected)
✅ Update dengan DATETIME format: Accepted

## Files Modified
1. `app/Http/Controllers/YayasanPresensiController.php` - submitallmasuk(), submitallpulang()
2. `app/Http/Controllers/PresensiController.php` - update()
3. `resources/views/presensi/edit.blade.php` - Form submit handler
4. `resources/views/yayasan-presensi/edit.blade.php` - Form submit handler (already fixed)

## Defense-in-Depth Approach
- **Server-side (Controller)**: Smart format detection - if has space use as-is, else concat with date
- **Client-side (Blade)**: Form submit handler prepends date if missing
- **Result**: Both manual form input AND API data (get data mesin) now work correctly
