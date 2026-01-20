# 🚀 QUICK START: LAPORAN PINJAMAN REAL-TIME AKURAT

## ⚡ 5 Menit Setup

### Step 1: Register EventServiceProvider
**File:** `app/Providers/EventServiceProvider.php`

Tambahkan di array `$listen`:
```php
protected $listen = [
    'App\Events\PinjamanPaymentUpdated' => [
        'App\Listeners\UpdateLaporanPinjaman',
    ],
];
```

Atau jalankan command:
```bash
php artisan make:listener UpdateLaporanPinjaman --event=PinjamanPaymentUpdated
```

### Step 2: Register API Routes
**File:** `routes/web.php` (dalam middleware auth)

```php
Route::middleware('auth')->group(function () {
    // ... existing routes ...
    
    // Real-time Laporan APIs
    Route::get('api/laporan-pinjaman', [PinjamanController::class, 'apiLaporanRealTime']);
    Route::get('api/verifikasi-akurasi-pinjaman/{pinjaman}', [PinjamanController::class, 'apiVerifikasiAkurasi']);
});
```

### Step 3: Clear Cache & Restart Queue
```bash
php artisan cache:clear
php artisan queue:work  # Jika using queue untuk listener
```

### Step 4: Test It!
Buka URL: `/pinjaman/laporan`

Expected:
- ✅ Cards menampilkan nominal akurat
- ✅ Tabel refresh otomatis setiap 30 detik
- ✅ Status badge menunjukkan "Update terakhir: ..."

---

## 📊 CARA KERJA (Singkat)

1. **User membayar cicilan** → Database update
2. **Event triggered** → `PinjamanPaymentUpdated` dispatched
3. **Listener proses** → Verifikasi akurasi + Update cache
4. **Browser auto-refresh** → AJAX GET `/api/laporan-pinjaman`
5. **Data terupdate** → Nominal akurat ditampilkan

---

## ✅ VERIFIKASI BERHASIL

Jalankan command:
```bash
php artisan tinker
```

Kemudian jalankan:
```php
// 1. Ambil pinjaman
$pinjaman = \App\Models\Pinjaman::first();

// 2. Verifikasi akurasi
$result = \App\Traits\PinjamanAccuracyHelper::verifikasiAkurasi($pinjaman);
dd($result);

// Expected: 'is_akurat' => true

// 3. Generate laporan akurat
$laporan = \App\Traits\PinjamanAccuracyHelper::generateLaporanAkurat(['bulan' => 1, 'tahun' => 2026]);
dd($laporan);
```

---

## 🔧 KONFIGURASI OPTIONAL

### Cache Duration
Edit `app/Listeners/UpdateLaporanPinjaman.php` line ~80:
```php
\Cache::put($cacheKey, $laporanStats, now()->addMinutes(5)); // Ubah dari 2 ke 5 menit
```

### Refresh Interval
Edit `resources/views/pinjaman/laporan-realtime.blade.php` line ~100:
```javascript
setInterval(refreshLaporanRealTime, 60000); // Ubah dari 30000 (30 detik) ke 60000 (60 detik)
```

---

## 🐛 ISSUE COMMON

### "404 Not Found" saat akses API
**Fix:** Pastikan routes sudah didaftarkan dan server di-restart

### "Laporan masih menunjukkan nominal lama"
**Fix:** Clear cache dengan `php artisan cache:clear`

### "Event tidak trigger"
**Fix:** 
1. Pastikan `EventServiceProvider` sudah register listener
2. Check log: `storage/logs/laravel.log`
3. Pastikan `prosesPembayaran()` di-call dengan benar

---

## 📈 SUMBER DATA AKURAT

Sistem menggunakan **"Sumber Kebenaran Tunggal"** = Tabel `pinjaman_cicilan`

Setiap perhitungan laporan mengambil dari:
```sql
SELECT 
    SUM(jumlah_cicilan) as total_nominal,
    SUM(jumlah_dibayar) as total_dibayar,
    SUM(sisa_cicilan) as total_sisa
FROM pinjaman_cicilan
WHERE pinjaman_id = ?
```

Bukan dari field `pinjaman.total_terbayar` atau `pinjaman.sisa_pinjaman` yang bisa ketinggalan update.

---

## 🎯 HASIL AKHIR

### Sebelum:
❌ Laporan statik (manual update)
❌ Nominal sering tidak akurat
❌ User harus refresh manual
❌ Tidak ada deteksi anomali

### Sesudah:
✅ Laporan real-time (otomatis update)
✅ Nominal 100% akurat (dari cicilan)
✅ Auto-refresh setiap 30 detik
✅ Auto-detect dan fix anomali
✅ Audit trail lengkap

---

**Status:** Ready to Use ✅

Silakan test dan enjoy real-time accurate reporting!
