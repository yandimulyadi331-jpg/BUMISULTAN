# 🔥 QUICK REFERENCE: FITUR FINGERPRINT JAMAAH MASAR

## 📍 LOKASI KODE

| Komponen | Path |
|----------|------|
| **Controller** | `app/Http/Controllers/JamaahMasarController.php` |
| **Model Jamaah** | `app/Models/JamaahMasar.php` |
| **Model Kehadiran** | `app/Models/KehadiranJamaahMasar.php` |
| **Index View** | `resources/views/masar/jamaah/index.blade.php` |
| **Get Data Mesin** | `resources/views/masar/jamaah/getdatamesin.blade.php` |
| **Error View** | `resources/views/masar/jamaah/getdatamesin_error.blade.php` |
| **Routes** | `routes/web.php` (Line 1325-1365) |

---

## 🎯 FLOW SINGKAT

```
Klik "Get Data Mesin"
        ↓
AJAX POST ke getdatamesin()
        ↓
CURL ke Fingerspot API
        ↓
Filter by PIN
        ↓
Tampil Modal dengan Data
        ↓
Klik "Simpan MASUK/PULANG"
        ↓
POST ke updatefrommachine()
        ↓
Update DB + Increment Counter
        ↓
Success Message ✅
```

---

## 🔧 MAIN METHODS

### 1. getdatamesin()
```php
// Input: pin_fingerprint, tanggal
// Output: View dengan array data absensi
// Route: POST /masar/jamaah/getdatamesin
// CURL: https://developer.fingerspot.io/api/get_attlog
```

### 2. updatefrommachine()
```php
// Input: pin (encrypted), status_scan, scan_date
// Output: Redirect dengan success/error message
// Route: POST /masar/jamaah/{pin}/{status_scan}/updatefrommachine
// DB Update: kehadiran_jamaah_masar (INSERT/UPDATE)
```

---

## 🎨 UI ELEMENTS

| Element | Type | Location |
|---------|------|----------|
| **Button** | <i class="ti ti-device-desktop"></i> | Di table, sebelah PIN |
| **Modal** | Bootstrap Modal | `id="modal"` |
| **Loading** | Animated Wave | Show saat AJAX process |
| **Badge** | Success/Danger | Untuk status MASUK/PULANG |
| **Alert** | Info/Warning | Error handling |

---

## 📊 DATABASE FIELDS

### jamaah_masar
```
✅ pin_fingerprint (VARCHAR 10) - MAIN FIELD
✅ jumlah_kehadiran (INT) - AUTO INCREMENT
- status_aktif (ENUM)
- status_umroh (BOOLEAN)
```

### kehadiran_jamaah_masar
```
✅ jamaah_id (INT FK)
✅ tanggal_kehadiran (DATE)
✅ jam_masuk (TIME) - FROM API
✅ jam_pulang (TIME) - FROM API
- status (VARCHAR) = 'hadir'
- keterangan (TEXT)
```

---

## ⚙️ REQUIREMENTS

✅ Cloud ID & API Key di Pengaturan Umum
✅ Jamaah dengan PIN tidak kosong
✅ Mesin sudah sync ke Fingerspot Cloud
✅ Koneksi internet stabil

---

## 🚨 ERROR MESSAGES

| Error | Cause | Fix |
|-------|-------|-----|
| "Cloud ID atau API Key belum diatur" | Setting kosong | Fill Pengaturan Umum |
| "Gagal mengambil data dari mesin" | API error | Check credentials |
| "Jamaah dengan PIN tidak ditemukan" | PIN salah | Verify PIN di database |
| "Tidak ada data absensi" | No scan on date | Check scan history |
| "Jamaah sudah absen MASUK/PULANG" | Already recorded | Edit manually jika perlu |

---

## 🔒 KEY SECURITY FEATURES

✅ CSRF Token protection
✅ PIN Encryption (Crypt::encrypt/decrypt)
✅ Role-based access (super admin only)
✅ Soft Delete for data protection
✅ SQL parameterized queries

---

## 📱 STATUS: PRODUCTION READY ✅

- No refresh needed (AJAX)
- Comprehensive error handling
- Mobile responsive
- Dark mode supported
- Fully documented

---

## 💡 TIPS & TRICKS

1. **Test tanpa API Key:** Upload dummy data via Import Excel
2. **Bulk import:** Use template download feature
3. **Check history:** Lihat riwayat di halaman detail jamaah
4. **Badge warna:** Indicator kehadiran (hijau/kuning/merah)
5. **Quick stats:** Dashboard shows kehadiran count

---

**VERSION:** 1.0 | **LAST UPDATE:** December 2025 | **STATUS:** ✅ LIVE

