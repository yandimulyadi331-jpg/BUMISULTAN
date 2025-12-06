# ⚠️ CARA AKSES YANG BENAR - MANAJEMEN DOKUMEN KARYAWAN

## 🚨 ERROR YANG ANDA ALAMI

**Error:** `UnauthorizedException: User does not have the right roles`  
**Penyebab:** Anda mengakses URL `/dokumen` (route ADMIN) sebagai karyawan

---

## ✅ CARA AKSES YANG BENAR

### **Opsi 1: Melalui Dashboard Karyawan (RECOMMENDED)**

1. Login sebagai **Karyawan**
2. Buka: `http://127.0.0.1:8000/fasilitas/dashboard-karyawan`
3. Klik menu **"Manajemen Dokumen"**
4. ✅ Otomatis redirect ke tampilan mobile karyawan

### **Opsi 2: Akses Langsung via URL**

Langsung akses URL karyawan:
```
http://127.0.0.1:8000/fasilitas/dokumen-karyawan
```

---

## 🔴 URL YANG SALAH (JANGAN DIAKSES KARYAWAN)

❌ `http://127.0.0.1:8000/dokumen`  
   → Ini untuk ADMIN, butuh role super admin!

---

## ✅ URL YANG BENAR (UNTUK KARYAWAN)

✅ `http://127.0.0.1:8000/fasilitas/dokumen-karyawan`  
   → Ini untuk KARYAWAN, tampilan mobile-friendly!

---

## 📊 PERBEDAAN ROUTES

| User | URL | Layout | Akses |
|------|-----|--------|-------|
| **ADMIN** | `/dokumen` | Desktop (sidebar) | Full CRUD |
| **KARYAWAN** | `/fasilitas/dokumen-karyawan` | Mobile (no sidebar) | Read Only |

---

## 🔧 SOLUSI ERROR ANDA

**Yang Anda lakukan (SALAH):**
```
Login sebagai karyawan → Akses /dokumen → ERROR ❌
```

**Yang HARUS dilakukan (BENAR):**
```
Login sebagai karyawan → Akses /fasilitas/dokumen-karyawan → SUCCESS ✅
```

ATAU

```
Login sebagai karyawan → Dashboard Karyawan → Klik menu "Manajemen Dokumen" → SUCCESS ✅
```

---

## 🎯 TESTING STEP-BY-STEP

1. **Logout** dari akun yang sekarang
2. **Login** sebagai karyawan (bukan super admin)
3. Pilih salah satu:
   - **A.** Klik menu **"Dashboard Karyawan"** dari sidebar
   - **B.** Atau langsung ke: `http://127.0.0.1:8000/fasilitas/dashboard-karyawan`
4. Di dashboard karyawan, klik menu **"Manajemen Dokumen"**
5. ✅ Sekarang Anda akan melihat tampilan mobile-friendly!

---

## 📱 YANG AKAN ANDA LIHAT (BENAR)

Jika akses dengan benar, Anda akan melihat:
- ✅ Header hijau dengan tombol back
- ✅ Filter card di atas
- ✅ Dokumen dalam bentuk card (bukan tabel)
- ✅ **TIDAK ADA SIDEBAR** (tampilan mobile)
- ✅ Tombol "Lihat" dan "Download" per dokumen

---

## 🔐 CATATAN KEAMANAN

- Routes `/dokumen/*` → Protected untuk **super admin only**
- Routes `/fasilitas/dokumen-karyawan/*` → Accessible untuk **semua user (termasuk karyawan)**
- Kontrol akses data dilakukan di level controller (filter access_level)

---

## 🆘 JIKA MASIH ERROR

1. **Clear cache browser:** Tekan `Ctrl + Shift + R`
2. **Clear route cache:**
   ```bash
   php artisan route:clear
   php artisan route:cache
   ```
3. **Pastikan login sebagai karyawan**, bukan admin!
4. **Akses URL yang benar:** `/fasilitas/dokumen-karyawan`

---

## ✅ KESIMPULAN

**JANGAN AKSES:**  
❌ `http://127.0.0.1:8000/dokumen`

**AKSES INI:**  
✅ `http://127.0.0.1:8000/fasilitas/dokumen-karyawan`

**ATAU:**  
✅ Dashboard Karyawan → Klik menu "Manajemen Dokumen"

---

**Status:** Implementasi sudah benar, Anda hanya perlu mengakses URL yang tepat! 🎉
