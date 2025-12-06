# 🧪 TESTING GUIDE - Fingerprint Integration Al-Ikhlas

## 📋 Pre-Testing Checklist

Sebelum mulai testing, pastikan:

- [x] ✅ Library `rats/zkteco` sudah terinstall
- [x] ✅ File `.env` sudah dikonfigurasi (ZKTECO_IP dan ZKTECO_PORT)
- [x] ✅ Server Laravel running (`php artisan serve`)
- [ ] ⏳ Mesin fingerprint Solution X601 dalam keadaan **ON**
- [ ] ⏳ Komputer server terhubung ke jaringan yang sama dengan mesin
- [ ] ⏳ IP mesin dapat di-ping: `ping 192.168.1.201`

---

## 🔌 STEP 1: Test Koneksi Mesin

### Cara Manual (via Terminal)
```bash
# Test ping ke mesin
ping 192.168.1.201

# Jika ping berhasil, lanjut test port
Test-NetConnection -ComputerName 192.168.1.201 -Port 4370
```

**Expected Result:**
```
TcpTestSucceeded : True
```

### Cara via UI (Recommended)

1. **Buka browser** → `http://127.0.0.1:8000/majlistaklim/getdatamesin`

2. **Login** dengan akun admin/super admin

3. **Klik button** "Test Koneksi" (warna biru)

4. **Tunggu hasil:**
   - ✅ **Success:** Muncul popup "Koneksi Berhasil!" dengan device info
   - ❌ **Failed:** Muncul popup "Koneksi Gagal!" dengan troubleshooting guide

**Screenshot Expected:**
```
┌────────────────────────────────────┐
│    Koneksi Berhasil!               │
├────────────────────────────────────┤
│ Mesin fingerprint 192.168.1.201    │
│ dapat diakses.                     │
│                                    │
│ Device Information:                │
│ • Serial: TES3243500221            │
│ • Platform: ZKTeco ZMM220_TFT      │
│ • Firmware: Ver 8.0.4.7-20230615   │
└────────────────────────────────────┘
```

---

## 📊 STEP 2: Registrasi PIN Jamaah (Persiapan Data)

Sebelum test ambil data, pastikan minimal 1 jamaah sudah punya PIN:

### Via UI:

1. **Navigasi:** Manajemen Yayasan → Majlis Ta'lim → **Data Jamaah**

2. **Pilih jamaah existing** atau **Tambah Jamaah Baru**

3. **Isi field PIN Fingerprint:** (contoh: `12345`)

4. **Simpan**

### Via Database (Quick Test):

```sql
-- Update jamaah existing dengan PIN
UPDATE jamaah_majlis_taklim 
SET pin_fingerprint = '12345' 
WHERE id = 1;

-- Atau insert jamaah test
INSERT INTO jamaah_majlis_taklim (
    nama_jamaah, nik, alamat, tanggal_lahir, tahun_masuk, 
    jenis_kelamin, pin_fingerprint, nomor_jamaah, status_aktif
) VALUES (
    'Test Jamaah', '3201010101010001', 'Test Address', '1990-01-01', 2025,
    'L', '12345', 'MT-2025-TEST', 'aktif'
);
```

**Catatan:** PIN harus **match** dengan PIN di mesin fingerprint!

---

## 👆 STEP 3: Absensi di Mesin Fingerprint

**Manual Test:**

1. Nyalakan mesin fingerprint Solution X601

2. Pastikan mesin sudah di-enroll dengan user (PIN: 12345)

3. Lakukan absensi:
   - Tekan jari di sensor
   - Tunggu bunyi beep
   - Layar menampilkan konfirmasi

4. Ulangi beberapa kali untuk test data multiple

**Expected di Mesin:**
- Attendance log tersimpan
- Bisa dicek via menu mesin: "Attendance → View Log"

---

## 📥 STEP 4: Ambil Data dari Mesin

### Via UI:

1. **Refresh halaman** fingerprint jika belum: `http://127.0.0.1:8000/majlistaklim/getdatamesin`

2. **Klik button** "Ambil Data Dari Mesin" (warna biru, besar)

3. **Tunggu loading** (spinner akan muncul)

4. **Hasil:**
   - ✅ **Success:** Data muncul di tabel dengan kolom:
     - Checkbox
     - No
     - PIN
     - Nama Jamaah
     - Nomor Jamaah
     - Tanggal
     - Jam
     - Status (Badge hijau "Terdaftar" atau merah "Tidak Terdaftar")
   
   - ❌ **Failed:** 
     - "Tidak ada data attendance di mesin" → Normal jika belum ada yang absen
     - "Gagal koneksi ke mesin" → Cek koneksi jaringan

**Expected Table:**

| ☐ | No | PIN   | Nama Jamaah  | Nomor Jamaah | Tanggal      | Jam      | Status     |
|---|-----|-------|--------------|--------------|--------------|----------|------------|
| ☐ | 1   | 12345 | Test Jamaah  | MT-2025-TEST | 15 Jan 2025  | 08:30:45 | Terdaftar  |
| ☐ | 2   | 12345 | Test Jamaah  | MT-2025-TEST | 15 Jan 2025  | 16:45:20 | Terdaftar  |
| ⊗ | 3   | 99999 | PIN Tidak... | -            | 15 Jan 2025  | 10:15:00 | Tidak...   |

**Fitur DataTables:**
- ✅ Search box (cari berdasarkan nama, PIN, tanggal, dll)
- ✅ Pagination (10/25/50/100/All)
- ✅ Sort by column (klik header)
- ✅ Show entries (pilih jumlah baris)

---

## 💾 STEP 5: Sinkronisasi Data ke Database

### Skenario A: Sync Semua Data

1. **Setelah data muncul**, klik button **"Sinkronkan Semua Data"** (hijau)

2. **Konfirmasi** di popup: "Apakah Anda yakin...?"

3. **Tunggu proses** (loading di SweetAlert)

4. **Hasil:**
   ```
   ┌────────────────────────────────────┐
   │         Berhasil!                  │
   ├────────────────────────────────────┤
   │ Berhasil sinkronisasi 150 data    │
   │                                    │
   │ • Berhasil: 145                    │
   │ • Di-skip (sudah ada): 5           │
   │ • Error: 0                         │
   └────────────────────────────────────┘
   ```

### Skenario B: Sync Data Terpilih

1. **Centang checkbox** data yang ingin di-sync (atau centang "All")

2. **Klik button** "Sinkronkan Data Terpilih" (biru)

3. **Konfirmasi:** "Apakah Anda yakin menyinkronkan X data..."

4. **Tunggu & lihat hasil**

### Skenario C: Data Sudah Ada (Duplicate)

**Test:**
1. Sync data pertama kali → Success (145 data)
2. Klik "Ambil Data" lagi → Data sama muncul
3. Sync lagi → **Semua di-skip!**

**Expected Result:**
```
Berhasil sinkronisasi 0 data kehadiran

• Berhasil: 0
• Di-skip (sudah ada): 145
• Error: 0
```

**Penjelasan:** Ini normal! Sistem auto-skip berdasarkan `jamaah_id + tanggal_kehadiran`

---

## ✅ STEP 6: Verifikasi Data di Database

### Query untuk Cek:

```sql
-- Cek data kehadiran dari fingerprint (latest 10)
SELECT 
    kj.id,
    j.nama_jamaah,
    j.pin_fingerprint,
    kj.tanggal_kehadiran,
    kj.jam_kehadiran,
    kj.sumber_absen,
    kj.device_id,
    kj.keterangan
FROM kehadiran_jamaah kj
JOIN jamaah_majlis_taklim j ON kj.jamaah_id = j.id
WHERE kj.sumber_absen = 'fingerprint'
ORDER BY kj.created_at DESC
LIMIT 10;
```

**Expected Result:**

| nama_jamaah  | pin_fingerprint | tanggal_kehadiran | jam_kehadiran | sumber_absen | device_id       | keterangan                                |
|--------------|-----------------|-------------------|---------------|--------------|-----------------|-------------------------------------------|
| Test Jamaah  | 12345           | 2025-01-15        | 08:30:45      | fingerprint  | 192.168.1.201   | Import dari mesin fingerprint PIN: 12345  |

### Cek Jumlah Kehadiran:

```sql
-- Cek apakah jumlah_kehadiran ter-update
SELECT 
    nama_jamaah,
    pin_fingerprint,
    jumlah_kehadiran
FROM jamaah_majlis_taklim
WHERE pin_fingerprint = '12345';
```

**Expected:** `jumlah_kehadiran` bertambah sesuai data yang di-sync

---

## 🧪 Test Scenarios (Complete)

### ✅ Test Case 1: Happy Path (All Success)
1. Mesin online ✅
2. Jamaah sudah registered dengan PIN ✅
3. Sudah ada data attendance di mesin ✅
4. Ambil data → Success ✅
5. Sync data → Success ✅
6. Data tersimpan di database ✅

### ⚠️ Test Case 2: Mesin Offline
1. Matikan mesin atau disconnect network
2. Klik "Test Koneksi" → Expected: "Koneksi Gagal"
3. Klik "Ambil Data" → Expected: "Gagal koneksi ke mesin"

### ⚠️ Test Case 3: PIN Tidak Terdaftar
1. Di mesin, ada user dengan PIN `99999` (tidak ada di database)
2. User absen
3. Ambil data → Data muncul dengan badge "Tidak Terdaftar"
4. Checkbox tidak ada (tidak bisa di-sync)
5. Sync All → Data dengan PIN 99999 di-skip

### ⚠️ Test Case 4: Data Kosong
1. Clear attendance log di mesin (atau mesin baru)
2. Ambil data → Expected: "Tidak ada data attendance di mesin"
3. UI menampilkan empty state

### ⚠️ Test Case 5: Data Duplicate
1. Sync data hari ini
2. Ambil data lagi
3. Sync lagi → Expected: Semua di-skip (sudah ada)

### ⚠️ Test Case 6: Partial Success
1. Ada 10 data di mesin
2. 5 data sudah ada di database (hari sebelumnya)
3. Sync All → Expected:
   - Success: 5 (yang baru)
   - Skipped: 5 (yang lama)

---

## 📝 Log Monitoring

### Real-time Log (Terminal):
```bash
# Watch log file
tail -f storage/logs/laravel.log
```

### Check for Errors:
```bash
# Search for errors
grep "ERROR" storage/logs/laravel.log | tail -20
```

### Expected Log Entries:

**Success:**
```
[2025-01-15 08:30:45] local.INFO: Berhasil koneksi ke mesin ZKTeco di 192.168.1.201:4370
[2025-01-15 08:30:50] local.INFO: Berhasil ambil 150 data attendance dari mesin
```

**Error:**
```
[2025-01-15 08:30:45] local.ERROR: Error koneksi ke mesin ZKTeco: Connection refused
```

---

## 🔍 Troubleshooting

### Problem 1: "Gagal koneksi ke mesin"

**Diagnostic:**
```powershell
# Test ping
ping 192.168.1.201

# Test port
Test-NetConnection -ComputerName 192.168.1.201 -Port 4370
```

**Solutions:**
- Pastikan mesin ON
- Cek kabel network
- Restart mesin
- Cek firewall (allow port 4370)
- Pastikan IP tidak berubah (jika DHCP, set static IP di mesin)

### Problem 2: "PIN Tidak Terdaftar"

**Diagnostic:**
```sql
-- Cek PIN di database
SELECT * FROM jamaah_majlis_taklim WHERE pin_fingerprint = '12345';
```

**Solutions:**
- Update PIN di database (via UI atau SQL)
- Pastikan PIN exact match (case-sensitive)
- Re-enroll fingerprint di mesin dengan PIN yang benar

### Problem 3: Data tidak muncul di tabel

**Check:**
1. Browser console (F12) → Lihat error JavaScript
2. Network tab → Cek response API
3. Laravel log → Lihat error backend

**Common Issue:** JavaScript error, CSRF token expired, atau JSON parse error

**Solution:**
- Refresh halaman (Ctrl+F5)
- Clear browser cache
- Check `resources/views/majlistaklim/getdatamesin.blade.php` line ~200

### Problem 4: Data di-sync tapi tidak muncul di database

**Diagnostic:**
```sql
-- Cek apakah insert berhasil
SELECT COUNT(*) FROM kehadiran_jamaah 
WHERE sumber_absen = 'fingerprint' 
AND DATE(created_at) = CURDATE();
```

**Check:**
- Laravel log untuk SQL error
- Unique constraint violation (duplicate)
- Foreign key constraint (jamaah_id tidak ada)

### Problem 5: Timeout saat ambil data

**Jika data sangat banyak (>1000 records):**

Edit `.env`:
```env
MAX_EXECUTION_TIME=300
```

Or edit `php.ini`:
```ini
max_execution_time = 300
```

---

## 📊 Expected Performance

| Metric | Value |
|--------|-------|
| **Koneksi ke mesin** | < 2 detik |
| **Fetch 100 records** | < 5 detik |
| **Sync 100 records** | < 10 detik |
| **Page load time** | < 2 detik |

---

## ✅ Success Criteria

Testing dianggap **berhasil** jika:

- [x] ✅ Test koneksi berhasil (device info muncul)
- [x] ✅ Ambil data berhasil (data muncul di tabel)
- [x] ✅ Sync data berhasil (data masuk database)
- [x] ✅ Duplicate di-skip otomatis
- [x] ✅ Jumlah kehadiran jamaah ter-update
- [x] ✅ PIN tidak terdaftar ter-identifikasi
- [x] ✅ Error handling bekerja (mesin offline, data kosong, dll)
- [x] ✅ Log tercatat dengan baik
- [x] ✅ UI responsive dan user-friendly

---

## 🎯 Next Steps After Testing

Jika testing berhasil:

1. ✅ Deploy to production server
2. ✅ Train users
3. ✅ Setup auto-sync scheduler (cron job)
4. ✅ Create dashboard statistik
5. ✅ Implement bulk PIN registration
6. ✅ Add WhatsApp notification

---

## 📞 Support

**Jika ada masalah saat testing:**

1. Cek dokumentasi lengkap: `DOKUMENTASI_FINGERPRINT_ALIKHLAS.md`
2. Lihat log: `storage/logs/laravel.log`
3. Cek network connectivity
4. Restart mesin dan server
5. Hubungi developer/IT support

---

**Good luck with testing! 🚀**

**Last Updated:** January 2025  
**Version:** 1.0
