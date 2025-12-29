# 📚 PANDUAN PENGGUNAAN SISTEM POTONGAN PINJAMAN PAYROLL (BARU)

## 🎯 Overview

Sistem baru untuk mengelola potongan pinjaman karyawan dengan pendekatan **manual input** seperti BPJS. Sistem ini memberikan fleksibilitas penuh untuk mengatur:
- Total pinjaman dan cicilan per bulan
- Periode berlaku (bulan mulai dan selesai)
- Monitoring progress pembayaran
- Auto-generate detail bulanan

---

## 🚀 CARA AKSES

### URL Sistem Baru:
```
http://manajemen.bumisultan.site/payroll/potongan-pinjaman-master
```

### Menu Navigation:
```
Payroll > Potongan Pinjaman Payroll (NEW)
```

---

## 📋 FITUR UTAMA

### 1. **Halaman Master (Index)**
Menampilkan daftar semua potongan pinjaman yang pernah dibuat.

**Fitur:**
- ✅ Filter pencarian (Nama, Cabang, Departemen, Status)
- ✅ Progress bar untuk tracking pembayaran
- ✅ Status: Aktif, Selesai, Ditunda, Dibatalkan
- ✅ Tombol Tambah, Edit, Hapus
- ✅ Link ke Proses Bulanan

**Kolom Tabel:**
- Kode: PPM250001, PPM250002, dst
- NIK & Nama Karyawan
- Dept & Cabang
- Total Pinjaman
- Cicilan per Bulan
- Periode: Jan 2025 - Mei 2025
- Progress: 2/5 (40%)
- Status & Aksi

---

### 2. **Tambah Potongan Pinjaman**

**Langkah-langkah:**

1. Klik tombol **"+ Tambah Potongan Pinjaman"**
2. Isi form:
   - **Karyawan**: Pilih karyawan (wajib)
   - **Referensi Pinjaman**: Pilih pinjaman yang ada (opsional)
     - Jika dipilih, akan auto-fill total & cicilan
   - **Total Pinjaman**: Masukkan jumlah total (Rp 5.000.000)
   - **Cicilan per Bulan**: Masukkan cicilan (Rp 1.000.000)
   - **Jumlah Bulan**: Otomatis dihitung (5 bulan)
   - **Bulan Mulai**: Pilih bulan mulai potongan
   - **Tahun Mulai**: Pilih tahun mulai
   - **Periode Selesai**: Otomatis dihitung (Mei 2025)
   - **Keterangan**: Opsional

3. Klik **"Simpan"**

**Validasi:**
- Total pinjaman harus > 0
- Cicilan per bulan harus > 0 dan <= total pinjaman
- Karyawan tidak boleh punya potongan aktif di periode yang sama

**Hasil:**
- Sistem generate kode unik: PPM250001
- Status otomatis: Aktif
- Data tersimpan di tabel master

---

### 3. **Edit Potongan Pinjaman**

**Yang Bisa Diedit:**
- ✅ Cicilan per bulan (akan recalculate periode selesai)
- ✅ Status (Aktif, Selesai, Ditunda, Dibatalkan)
- ✅ Keterangan

**Yang Tidak Bisa Diedit:**
- ❌ Karyawan
- ❌ Total pinjaman
- ❌ Periode mulai

**Langkah:**
1. Klik icon **Edit** (pensil hijau)
2. Update data yang diperlukan
3. Klik **"Update"**

---

### 4. **Proses Potongan Bulanan**

**URL:**
```
http://manajemen.bumisultan.site/payroll/potongan-pinjaman-master/proses
```

**Langkah-langkah:**

#### A. Generate Detail untuk Periode Tertentu

1. Pilih **Bulan** dan **Tahun** (misal: Desember 2025)
2. Klik **"Tampilkan"**
3. Jika belum ada detail, klik **"Generate Detail"**

**Proses Generate:**
- Sistem cari semua master dengan status **Aktif**
- Filter yang periode-nya mencakup bulan/tahun dipilih
- Buat detail untuk setiap master:
  - Hitung cicilan ke berapa
  - Set jumlah potongan (normal atau sisa di cicilan terakhir)
  - Status: Pending
  
**Hasil:**
- Detail potongan dibuat untuk periode tersebut
- Tampil di tabel dengan status **Pending**

#### B. Proses Potongan (Pending → Dipotong)

1. Setelah detail ter-generate, klik **"Proses Potongan"**
2. Konfirmasi: "Ya, Proses!"

**Proses:**
- Semua detail dengan status **Pending** berubah jadi **Dipotong**
- Tanggal dipotong = hari ini
- Update master:
  - Jumlah terbayar bertambah
  - Sisa pinjaman berkurang
  - Cicilan terbayar bertambah
  - Progress di-update

**Hasil:**
- Potongan akan muncul di slip gaji karyawan
- Master ter-update progressnya
- Jika sudah cicilan terakhir → status master = **Selesai**

#### C. Hapus Periode

Jika salah generate, bisa hapus semua detail di periode tersebut:
1. Klik **"Hapus Periode Ini"**
2. Konfirmasi: "Ya, Hapus!"

**Hasil:**
- Semua detail periode tersebut dihapus
- Master progress di-reset

---

### 5. **Summary Cards**

Di halaman Proses Bulanan, ada 4 card summary:

```
┌─────────────┐  ┌─────────────┐  ┌─────────────┐  ┌─────────────┐
│  Pending    │  │  Dipotong   │  │    Batal    │  │   Total     │
│     5       │  │      3      │  │      0      │  │  Karyawan   │
│ Rp 5.000.000│  │ Rp 3.000.000│  │  Rp 0       │  │      8      │
└─────────────┘  └─────────────┘  └─────────────┘  └─────────────┘
```

---

## 🤖 AUTO-GENERATE DENGAN COMMAND

Untuk automasi bulanan, jalankan command ini setiap awal bulan:

```bash
php artisan potongan-pinjaman:generate
```

**Atau untuk bulan/tahun spesifik:**
```bash
php artisan potongan-pinjaman:generate 12 2025
```

**Proses:**
- Cari semua master aktif yang periode-nya mencakup bulan tersebut
- Generate detail otomatis
- Skip jika detail sudah ada
- Update status master jika sudah selesai

**Setup Cron (Opsional):**
```bash
# Jalankan setiap tanggal 1 jam 00:00
0 0 1 * * cd /path/to/project && php artisan potongan-pinjaman:generate
```

---

## 💼 CONTOH KASUS PENGGUNAAN

### Kasus 1: Pinjaman Karyawan Baru

**Scenario:**
- Karyawan: Yandi Mulyadi (NIK: 12345678)
- Pinjaman: Rp 5.000.000
- Cicilan: Rp 1.000.000 per bulan
- Mulai: Januari 2025

**Langkah:**

1. **Tambah Master:**
   - Pilih Yandi Mulyadi
   - Total: 5.000.000
   - Cicilan: 1.000.000
   - Mulai: Januari 2025
   - Save → Kode: PPM250001

2. **Generate Detail Januari 2025:**
   - Masuk ke Proses Bulanan
   - Pilih Januari 2025
   - Klik Generate
   - Detail cicilan ke-1 dibuat (Pending)

3. **Proses Potongan:**
   - Klik "Proses Potongan"
   - Status jadi Dipotong
   - Muncul di slip gaji Januari

4. **Bulan Berikutnya (Februari):**
   - Generate lagi untuk Februari
   - Detail cicilan ke-2 dibuat
   - Proses → Dipotong
   - Dan seterusnya sampai Mei

5. **Mei 2025 (Cicilan Terakhir):**
   - Generate detail cicilan ke-5
   - Proses → Dipotong
   - Master status otomatis jadi **Selesai**
   - Tanggal selesai ter-set

---

### Kasus 2: Update Cicilan di Tengah Jalan

**Scenario:**
- Karyawan punya kesulitan, minta cicilan diturunkan
- Dari 1jt/bulan jadi 500rb/bulan

**Langkah:**

1. **Edit Master:**
   - Klik Edit pada master karyawan
   - Ubah cicilan per bulan: 1.000.000 → 500.000
   - Save

2. **Efek:**
   - Jumlah bulan otomatis bertambah (5 → 10 bulan)
   - Periode selesai berubah (Mei → Oktober)
   - Cicilan yang belum ter-generate akan pakai nilai baru

3. **Generate Detail Bulan Depan:**
   - Detail baru akan pakai cicilan 500.000
   - Detail yang sudah dipotong tidak berubah

---

### Kasus 3: Tunda Potongan

**Scenario:**
- Karyawan sakit, minta potongan ditunda beberapa bulan

**Langkah:**

1. **Update Status:**
   - Edit master
   - Ubah status: Aktif → Ditunda
   - Save

2. **Efek:**
   - Saat generate bulan berikutnya, tidak akan ter-generate
   - Potongan berhenti sementara

3. **Aktifkan Lagi:**
   - Edit master
   - Ubah status: Ditunda → Aktif
   - Save
   - Generate lagi untuk periode yang diinginkan

---

## 🔗 INTEGRASI DENGAN SLIP GAJI

Potongan pinjaman akan otomatis muncul di slip gaji jika:
- ✅ Status detail = **Dipotong**
- ✅ Periode detail = periode slip gaji

**Di Slip Gaji Tampil:**
```
POTONGAN:
- Potongan Pinjaman (PPM250001) - Cicilan 2/5: Rp 1.000.000
```

**Note:** Pastikan controller slip gaji sudah menggunakan method:
```php
PotonganPinjamanMasterController::getPotonganByNik($nik, $bulan, $tahun)
```

---

## ⚠️ TROUBLESHOOTING

### 1. "Karyawan sudah memiliki potongan aktif di periode yang sama"
**Penyebab:** Karyawan sudah punya master aktif yang periode-nya overlap.
**Solusi:**
- Edit master yang lama, ubah status jadi Selesai/Dibatalkan
- Atau tunggu sampai periode lama selesai

### 2. Detail tidak ter-generate
**Penyebab:**
- Master status bukan Aktif
- Periode master tidak mencakup bulan yang dipilih
- Detail sudah ada sebelumnya

**Solusi:**
- Cek status master (harus Aktif)
- Cek periode master
- Hapus detail lama jika perlu re-generate

### 3. Master tidak otomatis selesai
**Penyebab:** Progress belum 100%

**Solusi:**
- Cek apakah semua detail sudah Dipotong
- Jalankan: `$master->updateProgress()`
- Atau edit manual status jadi Selesai

---

## 📊 MONITORING & REPORTING

### Progress Tracking
Setiap master punya progress tracking:
- **Cicilan Terbayar**: Jumlah cicilan yang sudah dipotong
- **Jumlah Terbayar**: Total rupiah yang sudah dipotong
- **Sisa Pinjaman**: Sisa yang belum dipotong
- **Progress %**: Persentase pembayaran

### Laporan
Bisa export data dari:
- Halaman index (filter dulu, lalu export)
- Halaman proses bulanan (export per periode)

---

## 🎓 BEST PRACTICES

1. **Generate Detail di Awal Bulan**
   - Jalankan command atau manual generate setiap awal bulan
   - Cek summary, pastikan semua karyawan ter-cover

2. **Proses Potongan Sebelum Generate Slip Gaji**
   - Proses detail jadi Dipotong sebelum cetak slip
   - Pastikan tidak ada yang tertinggal

3. **Review Progress Berkala**
   - Cek master yang sudah mendekati selesai
   - Follow up karyawan yang progressnya lambat

4. **Backup Data Sebelum Hapus Periode**
   - Jika perlu hapus periode, backup dulu
   - Atau ubah status jadi Batal, jangan hapus

5. **Dokumentasi Perubahan**
   - Gunakan kolom Keterangan untuk catatan
   - Catat alasan jika ada perubahan cicilan/status

---

## 🔐 PERMISSIONS REQUIRED

Pastikan role Anda punya permission:
- `potongan_pinjaman.index` - Lihat list
- `potongan_pinjaman.create` - Tambah baru
- `potongan_pinjaman.edit` - Edit
- `potongan_pinjaman.delete` - Hapus
- `potongan_pinjaman.generate` - Generate detail
- `potongan_pinjaman.proses` - Proses potongan

---

## 📞 SUPPORT

Jika ada pertanyaan atau kendala:
1. Cek dokumentasi ini dulu
2. Lihat file analisa: `ANALISA_REDESIGN_POTONGAN_PINJAMAN_PAYROLL.md`
3. Contact IT Support

---

**Sistem ini sudah LIVE dan siap digunakan! 🎉**

Timestamp: 28 Desember 2025
