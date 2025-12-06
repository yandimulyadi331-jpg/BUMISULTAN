# 🚀 QUICK GUIDE - FITUR ABSENSI FINGERSPOT MASAR

## ⚡ 3 MENIT SETUP

### 1️⃣ Setup Cloud (1x saja)
```
1. Login: https://developer.fingerspot.io
2. Copy: Cloud ID & API Key
3. Buka: Pengaturan → Pengaturan Umum
4. Input: Cloud ID & API Key
5. Save ✅
```

### 2️⃣ Setup Jamaah (per jamaah)
```
1. Edit Jamaah
2. Isi: PIN Fingerprint (contoh: 2001)
3. Save ✅
4. Enroll jari di mesin dengan PIN yang sama
```

### 3️⃣ Ambil Data (setiap hari)
```
1. Jamaah absen di mesin
2. Buka: Data Jamaah MASAR
3. Klik: Icon Desktop (biru)
4. Klik: "Simpan MASUK" atau "Simpan PULANG"
5. Done ✅
```

---

## 📍 LOKASI FITUR

**Menu:** Manajemen Yayasan → Data Jamaah MASAR

**Kolom Baru:** PIN (badge biru)

**Tombol Baru:** Icon Desktop (biru) di kolom Aksi

**Tooltip:** "Ambil Data dari Mesin Fingerspot"

---

## 🎯 APA YANG TERJADI

### Saat Klik "Get Data Mesin":
1. ✅ Modal popup terbuka
2. ✅ Loading animation
3. ✅ Request ke Fingerspot Cloud API
4. ✅ Tampilkan data absensi
5. ✅ Button "Simpan MASUK" & "Simpan PULANG"

### Saat Klik "Simpan MASUK/PULANG":
1. ✅ Data masuk ke tabel `kehadiran_jamaah_masar`
2. ✅ Field: `jam_masuk` atau `jam_pulang`
3. ✅ Auto increment `jumlah_kehadiran`
4. ✅ Flash message success
5. ✅ Badge kehadiran update

---

## 🔑 STATUS SCAN

| Kode | Arti | Button |
|------|------|--------|
| 0, 2, 4, 6, 8 | **MASUK** | Hijau |
| 1, 3, 5, 7, 9 | **PULANG** | Merah |

---

## ❌ ERROR? CEK INI

| Error | Fix |
|-------|-----|
| Tombol tidak muncul | Isi PIN di edit jamaah |
| Modal kosong | Tunggu 2-5 menit (sync delay) |
| Cloud ID error | Input di Pengaturan Umum |
| PIN tidak ketemu | Cek PIN sama dengan mesin |

---

## 📱 CONTACT

**Problem?** Lihat: `IMPLEMENTASI_FINGERSPOT_MASAR.md`

**Troubleshooting:** Section "🐛 TROUBLESHOOTING"

**Full Doc:** 400+ baris lengkap!

---

**Status:** ✅ READY  
**Version:** 1.0
