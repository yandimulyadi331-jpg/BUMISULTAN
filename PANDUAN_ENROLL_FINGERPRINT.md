# 📋 Panduan Enroll Fingerprint Manual di Mesin Solution X601

## ✅ STATUS SISTEM
- ✅ Auto-sync berjalan sempurna (cek mesin setiap 60 detik)
- ✅ PIN unique sudah di-assign ke database
- ✅ Koneksi mesin stabil

## 🎯 LANGKAH ENROLL USER DI MESIN

### 1. **Masuk Menu User Management**
Di mesin fingerprint:
```
Menu → User Mgt → New Enroll
```

### 2. **Input Data User**
- **User ID/PIN**: Masukkan PIN dari list di bawah (misal: `1001`)
- **Name**: Nama jamaah (opsional, bisa skip)
- **Password**: Kosongkan
- **Card No**: Kosongkan atau 0
- **Role**: Pilih "User" (bukan Admin)

### 3. **Enroll Fingerprint**
- Pilih **"Enroll FP"** (Enroll Fingerprint)
- Scan jari yang sama **3-5 kali** sampai muncul "Success" atau bunyi beep
- Simpan (OK/Save)

### 4. **Test Scan**
- Keluar dari menu
- **Scan fingerprint** yang baru di-enroll
- Jika berhasil, mesin akan bunyi beep dan tampilkan nama/PIN
- **TUNGGU 1-2 MENIT** → data otomatis masuk ke database! 🎉

---

## 📊 DAFTAR PIN JAMAAH (Database)

| PIN  | Nama Jamaah    | Nomor Jamaah       |
|------|----------------|--------------------|
| 1001 | hm             | JA-0010-30-10-25   |
| 1002 | YANDI MULYADI  | JA-0002-10-2-19    |
| 1003 | h engkus       | JA-0003-18-3-19    |
| 1004 | YANDI MULYADI  | JA-0004-21-4-19    |
| 1005 | h engkus       | JA-0005-23-5-19    |
| 1006 | YANDI MULYADI  | JA-0006-43-6-19    |
| 1007 | YANDI MULYADI  | JA-0007-45-7-19    |
| 1008 | h engkus       | JA-0008-76-8-19    |
| 1009 | h engkus       | JA-0009-75-9-19    |
| 1010 | YANDI MULYADI  | JA-0011-07-11-19   |
| 1011 | tes            | JA-0012-31-12-25   |
| 2762 | TESTY          | JA-0013-36-13-25   |

---

## 🔍 CEK STATUS

### Cek user terdaftar di mesin:
```bash
php artisan fingerprint:check-users
```

### Cek auto-sync berjalan:
Auto-sync sudah berjalan di background, cek terminal yang running.

### Cek data kehadiran di database:
```sql
SELECT * FROM kehadiran_jamaah ORDER BY tanggal DESC, jam DESC LIMIT 10;
```

---

## ⚡ TROUBLESHOOTING

### ❓ Scan fingerprint tapi tidak masuk database?
1. **Cek mesin punya attendance log:**
   ```bash
   php artisan fingerprint:check-users
   ```
   Lihat "Attendance Logs" ada berapa records
   
2. **Pastikan auto-sync running:**
   Lihat terminal PowerShell, seharusnya loop running setiap 60 detik

3. **Cek PIN di mesin match dengan database:**
   PIN di mesin harus sama dengan PIN di tabel `jamaah_majlis_taklim`

### ❓ User tidak muncul di mesin setelah enroll?
- Restart mesin
- Atau cek di Menu → User Mgt → User List

### ❓ Scan fingerprint rejected?
- Enroll ulang fingerprint dengan scan lebih banyak (5-7 kali)
- Pastikan jari bersih dan kering
- Coba jari yang lain

---

## 🎯 QUICK START

**Test sekarang:**
1. Enroll 1 user dengan PIN **1001** di mesin
2. Scan fingerprint user tersebut
3. Tunggu 1-2 menit
4. Cek database atau refresh page "Ambil Data Dari Mesin"
5. Data akan otomatis muncul! ✅

---

## 📞 COMMAND REFERENCE

| Command | Fungsi |
|---------|--------|
| `php artisan fingerprint:sync-realtime` | Manual sync 1x |
| `php artisan fingerprint:check-users` | Cek user di mesin |
| `php artisan fingerprint:assign-pins` | Assign PIN unique |
| `php artisan fingerprint:push-users` | Push user ke mesin (sering gagal) |

---

## ✅ SISTEM SUDAH SIAP!

**Auto-sync aktif** → Setiap 60 detik cek mesin otomatis
**PIN unique** → Sudah di-assign ke semua jamaah
**Tinggal:** Enroll fingerprint di mesin dan test scan! 🚀
