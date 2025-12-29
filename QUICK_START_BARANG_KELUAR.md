# QUICK START - TRACKING BARANG KELUAR

## ⚡ Setup Cepat (5 Menit)

### 1️⃣ Jalankan Migration
```bash
cd d:\bumisultanAPP\bumisultanAPP
php artisan migrate
```

### 2️⃣ (Optional) Link Storage
```bash
php artisan storage:link
```

### 3️⃣ Set Permission
Login sebagai Super Admin, sistem sudah otomatis bisa diakses.

### 4️⃣ Akses Menu
1. Login ke aplikasi
2. Sidebar → **"Tracking Barang Keluar"** (di bawah Manajemen Perawatan)
3. Mulai gunakan! 🎉

---

## 🚀 CARA PAKAI SUPER CEPAT

### ➕ Tambah Barang Baru

1. **Klik "Tambah Barang Keluar"**
2. **Isi 4 Informasi Penting:**
   - 📦 Jenis & Nama Barang (misal: Laundry → Seragam Karyawan)
   - 👤 Pemilik (misal: Departemen Cleaning)
   - 🏪 Vendor (misal: Laundry Express)
   - 📅 Tanggal Keluar & Estimasi Kembali
3. **Upload Foto** (opsional tapi direkomendasikan)
4. **Simpan** ✅

**Contoh Real:**
```
Jenis: Laundry
Nama: 15 Set Seragam Security
Jumlah: 15 pcs
Pemilik: Dept. Security
Vendor: Laundry Express Jl. Raya No. 123
Keluar: 29 Dec 2024, 09:00
Estimasi: 30 Dec 2024
Biaya: Rp 150.000
Prioritas: Normal
```

---

### 🔄 Update Status

**Skenario Lengkap:**

1. **Hari Pertama (29 Dec)**
   - Status: **Pending** → Barang baru diinput, belum dikirim
   
2. **Beberapa Jam Kemudian**
   - Barang dikirim ke laundry
   - Update status: **Dikirim**
   - Tambah foto: Foto saat serah terima di vendor
   - Catatan: "Diterima Pak Budi, estimasi selesai besok sore"

3. **Hari Kedua Pagi (30 Dec)**
   - Vendor mulai kerjakan
   - Update status: **Proses**
   - Catatan: "Sedang dicuci dan disetrika"

4. **Hari Kedua Sore (30 Dec)**
   - Vendor selesai
   - Update status: **Selesai Vendor**
   - Foto: Upload foto hasil laundry
   - Catatan: "Sudah selesai, bisa diambil"

5. **Pengambilan**
   - Staff ambil barang
   - Update status: **Diambil** ✅
   - Upload foto nota pembayaran
   - Input biaya aktual: Rp 145.000
   - Sistem otomatis set tanggal_kembali = sekarang

---

### 🔍 Cari & Filter

**Quick Filters:**
- **Belum Kembali**: Filter status = Pending/Dikirim/Proses/Selesai Vendor
- **Terlambat**: Sistem auto-highlight merah 🔴
- **By Vendor**: Dropdown pilih vendor
- **By Tanggal**: Set range tanggal
- **Search**: Ketik kode transaksi/nama barang

**Contoh Use Case:**
- *"Lihat semua barang di laundry yang belum kembali"*
  → Filter: Status = Proses, Jenis = Laundry
  
- *"Cek barang urgent yang terlambat"*
  → Filter: Prioritas = Urgent, tambah cek badge merah

---

## 📊 Baca Dashboard

**5 Card Statistik:**
```
┌──────────────┬──────────────┬──────────────┬──────────────┬──────────────┐
│ Total Barang │   Pending    │ Dalam Proses │ Selesai Vendor│  Terlambat  │
│      45      │      8       │      12      │      5        │      3      │
└──────────────┴──────────────┴──────────────┴──────────────┴──────────────┘
```

**Interpretasi:**
- **Total 45**: Semua transaksi barang keluar
- **Pending 8**: Barang belum dikirim, segera action!
- **Proses 12**: Barang sedang di vendor
- **Selesai 5**: Siap diambil, jemput sekarang!
- **Terlambat 3**: ⚠️ Urgent! Hubungi vendor

---

## 💡 TIPS PENTING

### ✅ DO (Lakukan)
- ✅ Upload foto SEBELUM kirim (dokumentasi kondisi awal)
- ✅ Catat no telp vendor & pemilik
- ✅ Set estimasi realistis (tanya vendor)
- ✅ Update status segera saat ada perubahan
- ✅ Upload foto nota saat bayar
- ✅ Rate vendor (1-5 ⭐) untuk evaluasi
- ✅ Cek barang terlambat setiap pagi

### ❌ DON'T (Hindari)
- ❌ Skip foto dokumentasi
- ❌ Set estimasi terlalu optimis
- ❌ Lupa update status
- ❌ Tidak simpan nota
- ❌ Abaikan barang terlambat

---

## 🎯 USE CASES REAL

### Case 1: Laundry Seragam Rutin
```
Setiap Jumat:
1. Input 20 set seragam → Laundry Express
2. Status: Pending → Dikirim (Jumat sore)
3. Status: Proses (Sabtu pagi)
4. Status: Selesai Vendor (Sabtu sore)
5. Status: Diambil (Senin pagi)
Durasi: 3 hari, On-time! ✅
```

### Case 2: Perbaikan Sepatu Urgent
```
Senin pagi: Sepatu Direktur rusak
1. Input → Prioritas: URGENT 🔴
2. Estimasi: Besok (Selasa)
3. Follow up ketat setiap 2 jam
4. Vendor kerja lembur
5. Selesai Selasa pagi → On-time! ✅
Catatan: Rate vendor 5⭐ (service excellent!)
```

### Case 3: Perbaikan AC Terlambat
```
Input Senin:
- AC rusak → Service Center
- Estimasi: Kamis
- Status: Proses

Jumat: TERLAMBAT! 🔴
- Sistem auto-highlight merah
- Telepon vendor segera
- Vendor: Sparepart kosong
- Update estimasi → Senin depan
- Prioritas: Tinggi
```

---

## 🔔 NOTIFIKASI TERLAMBAT

Sistem otomatis tandai MERAH jika:
```
Kondisi Terlambat:
✗ Estimasi Kembali sudah lewat
✗ Status bukan "Diambil"
✗ Status bukan "Batal"

Tampilan:
🔴 Row background merah
🔴 Badge "Terlambat X hari"
```

**Action yang harus dilakukan:**
1. Telepon vendor segera
2. Tanya status terkini
3. Update estimasi baru jika perlu
4. Dokumentasi di catatan

---

## 📱 SHORTCUT KEYBOARD

*Coming soon in next version*

---

## 🆘 TROUBLESHOOTING CEPAT

### "Foto tidak muncul"
```bash
php artisan storage:link
```

### "Tidak bisa hapus data"
- Check permission user
- Atau gunakan Soft Delete (data masih ada di database)

### "Kode transaksi sama"
- Auto-generated per hari, impossible duplicate
- Jika terjadi, restart server

---

## 📞 KONTAK CEPAT

**Emergency?**
- 📱 WhatsApp Admin: [Number]
- 📧 Email: support@bumisultan.com
- 💬 Chat internal: /help

---

## 🎓 TRAINING VIDEO

*Coming soon: Video tutorial 5 menit*

---

**Happy Tracking! 🚀**

*BumisultanAPP Development Team*
*v1.0.0 - December 2024*
