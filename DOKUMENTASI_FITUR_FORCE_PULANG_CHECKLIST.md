# 📋 FITUR CHECKLIST DENGAN FORCE PULANG - DOKUMENTASI IMPLEMENTASI

## 🎯 Ringkasan Perubahan

**Sebelumnya:** Karyawan yang ingin absen pulang tapi masih ada checklist yang belum selesai akan ditampilkan notifikasi BLOCKING (tidak bisa pulang).

**Sekarang:** Notifikasi tetap ada tapi karyawan punya 2 pilihan:
1. **Selesaikan** → Redirect ke halaman checklist untuk selesaikan tugas
2. **Pulang** → Bypass checklist dan langsung bisa absen pulang

---

## 📝 File-File Yang Diubah

### 1. **app/Http/Controllers/Api/ChecklistController.php** ✅
**Tambahan:** Method baru `forcePulang()`
```php
public function forcePulang(Request $request)
{
    // Validasi user adalah karyawan
    // Return success dengan flag forcePulangAllowed = true
    // Memungkinkan aplikasi mobile bypass checklist requirement
}
```

**Tujuan:** Endpoint untuk handle request saat user klik tombol "Pulang"

---

### 2. **routes/api.php** ✅
**Tambahan:** Route baru untuk force-pulang
```php
Route::post('/checklist/force-pulang', [...ChecklistController::class, 'forcePulang'])
```

---

### 3. **resources/views/dashboard/karyawan.blade.php** ✅

#### A. **Update Modal HTML** (Line 1013-1040)
**Perubahan:**
- Title: "Oops..." → "Selesaikan Checklist Dulu?"
- Message: "Tidak dapat absen pulang!" → "Anda dapat melanjutkan absen pulang atau menyelesaikan checklist"
- Tombol: Urutan diubah → "Selesaikan" | "Pulang" (hijau)
- Styling: Tombol Pulang sekarang hijau (#27ae60) menunjukkan aksi yang allowed

#### B. **Update JavaScript** (Line 1650-1727)
**Perubahan pada `btnPulang.addEventListener`:**

**Sebelumnya:**
```javascript
btnPulang.addEventListener('click', function() {
    hideChecklistModal();
    sessionStorage.setItem('checklistNotificationShown', 'true');
});
```

**Sekarang:**
```javascript
btnPulang.addEventListener('click', function() {
    // Disable button + loading state
    btnPulang.disabled = true;
    btnPulang.innerHTML = '<i class="ti ti-loader"></i> Loading...';

    // Call API force-pulang
    fetch('{{ route('api.checklist.force-pulang') }}', {
        method: 'POST',
        headers: { ... },
        body: JSON.stringify({ date: todayDate })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.forcePulangAllowed) {
            // Store flag
            sessionStorage.setItem('forcePulangAllowed', 'true');
            hideChecklistModal();
            // Proceed dengan checkout
        } else {
            alert('Error: ' + data.message);
            // Re-enable button
        }
    })
    .catch(error => { ... });
});
```

**Tujuan:** API call untuk validasi sebelum bypass checklist

---

## 🔧 Cara Kerja

### Flow 1: User Klik Tombol "Selesaikan"
```
User klik "Selesaikan"
    ↓
Redirect ke: perawatan.karyawan.checklist('harian')
    ↓
User bisa lihat & complete checklist
    ↓
Kembali ke dashboard → Notifikasi hilang (jika semua selesai)
```

### Flow 2: User Klik Tombol "Pulang"
```
User klik "Pulang"
    ↓
POST /api/checklist/force-pulang (dengan date)
    ↓
ChecklistController@forcePulang()
    ├─ Validasi user adalah karyawan ✓
    ├─ Validasi ada presensi hari ini ✓
    └─ Return { success: true, forcePulangAllowed: true }
    ↓
JavaScript: sessionStorage.setItem('forcePulangAllowed', 'true')
    ↓
Notifikasi modal tutup
    ↓
Karyawan bisa melanjutkan absen pulang (checkout)
    ↓
**PENTING:** Aplikasi mobile/API absen pulang HARUS cek flag ini
```

---

## ⚠️ CATATAN IMPLEMENTASI - JANGAN LUPA!

### 1. **Update Aplikasi Mobile (React Native)**
Jika ada logika di aplikasi mobile yang block checkout saat ada incomplete checklist, HARUS diubah:

```javascript
// SEBELUM:
if (checklistIncomplete) {
    // Block checkout
    throw new Error('Selesaikan checklist dulu');
}

// SESUDAH:
if (checklistIncomplete && !sessionStorage.getItem('forcePulangAllowed')) {
    // Block checkout
    throw new Error('Selesaikan checklist dulu');
}

// Jika force pulang allowed, clear flag setelah checkout selesai
sessionStorage.removeItem('forcePulangAllowed');
```

### 2. **Update Proses Checkout/Absen Pulang**
Ada kemungkinan logika checkout di:
- **Pre-checkout validation** - HARUS update untuk allow jika flag ada
- **API POST /checkout atau /jam-out** - Cek apakah ada parameter/header yang menunjukkan force pulang

---

## 📊 Testing Checklist

- [ ] Akses dashboard karyawan
- [ ] Pastikan ada incomplete checklist (harian)
- [ ] Notifikasi muncul dengan pesan baru
- [ ] Klik tombol "Selesaikan" → Redirect ke checklist halaman
- [ ] Klik tombol "Pulang" → Modal tutup + sessionStorage updated
- [ ] Verifikasi `forcePulangAllowed` di console: `sessionStorage.getItem('forcePulangAllowed')`
- [ ] Proses checkout/absen pulang berfungsi normal

---

## 🐛 Error Handling

### Kemungkinan Error:
1. **API call fail** → Button re-enable, show error alert
2. **User bukan karyawan** → API return 403, show error
3. **No presensi today** → API return 404, show error

Semua error akan di-catch dan ditampilkan ke user dengan error message yang jelas.

---

## 📱 UI/UX Improvement

### Sebelumnya:
- ❌ Tombol "Pulang" merah (danger) = terasa seperti error
- ❌ Notifikasi blocking (memaksa selesai checklist)
- ❌ User frustrated

### Sekarang:
- ✅ Tombol "Pulang" hijau (success) = allowed action
- ✅ Notifikasi informatif (ada pilihan)
- ✅ User lebih satisfied

---

## 🚀 Deploy ke Hosting

Di Termius SSH hosting:

```bash
cd /home/u722741035/domains/bumisultan.site/BUMISULTAN

# Pull latest code
git pull origin main

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

echo "✅ Deploy complete!"
```

---

## 📌 Penting: Cek API Response

Saat testing, buka **Browser DevTools → Network Tab** saat klik tombol "Pulang":

**Expected Response:**
```json
{
    "success": true,
    "forcePulangAllowed": true,
    "message": "Anda dapat melanjutkan absen pulang"
}
```

Jika response berbeda, ada bug pada endpoint atau logic.

---

**Status:** ✅ Ready for Deployment  
**Created:** 2026-01-21  
**Last Updated:** 2026-01-21
