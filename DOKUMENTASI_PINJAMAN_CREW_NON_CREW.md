# DOKUMENTASI SISTEM PINJAMAN (Crew & Non-Crew)

## 📋 OVERVIEW

Sistem Pinjaman adalah fitur manajemen pinjaman dana yang komprehensif dengan workflow approval seperti yang diterapkan di perusahaan dan perbankan. Sistem ini mendukung dua kategori peminjam:
- **Crew (Karyawan)**: Karyawan yang terdaftar dalam sistem
- **Non-Crew (Umum)**: Pihak eksternal/umum yang tidak terdaftar sebagai karyawan

## 🎯 FITUR UTAMA

### 1. **Pengajuan Pinjaman**
- Form pengajuan lengkap dengan validasi
- Support dua kategori: Crew & Non-Crew
- Upload dokumen pendukung (KTP, Slip Gaji, dll)
- Kalkulator cicilan otomatis
- Data penjamin (opsional)

### 2. **Workflow Approval**
Status pinjaman mengikuti alur:
```
Pengajuan → Review → Disetujui/Ditolak → Dicairkan → Berjalan → Lunas
```

**Detail Status:**
- **Pengajuan**: Baru diajukan, menunggu review
- **Review**: Sedang ditinjau oleh reviewer
- **Disetujui**: Sudah disetujui, menunggu pencairan
- **Ditolak**: Pengajuan ditolak dengan alasan
- **Dicairkan**: Dana sudah dicairkan
- **Berjalan**: Sedang dalam masa cicilan
- **Lunas**: Pinjaman sudah lunas
- **Dibatalkan**: Pinjaman dibatalkan

### 3. **Sistem Perhitungan Bunga**

#### A. **Bunga Flat**
- Cicilan tetap setiap bulan
- Rumus: `Cicilan = (Pokok + Total Bunga) / Tenor`
- Total Bunga = `(Pokok × Bunga% × Tenor/12) / 100`

#### B. **Bunga Efektif/Anuitas**
- Cicilan tetap, tapi komposisi pokok dan bunga berubah
- Bunga menurun, pokok meningkat setiap bulan
- Rumus Anuitas:
  ```
  Cicilan = Pokok × [r(1+r)^n] / [(1+r)^n - 1]
  r = bunga bulanan
  n = tenor bulan
  ```

### 4. **Jadwal Cicilan Otomatis**
- Generate otomatis saat pencairan
- Tracking jatuh tempo setiap cicilan
- Hitung denda keterlambatan otomatis (0.1% per hari)
- Status cicilan: Belum Bayar, Sebagian, Lunas, Terlambat

### 5. **Pembayaran Cicilan**
- Bayar per cicilan
- Support multiple metode: Tunai, Transfer, Potong Gaji
- Upload bukti pembayaran
- Update otomatis sisa pinjaman

### 6. **Integrasi dengan Transaksi Keuangan**
- Pencairan tercatat sebagai pengeluaran
- Pembayaran cicilan tercatat sebagai pemasukan
- Terintegrasi dengan sistem akuntansi

### 7. **History & Audit Trail**
- Log semua aktivitas pinjaman
- Timeline visual approval
- Tracking user yang melakukan aksi

## 🗄️ STRUKTUR DATABASE

### Tabel: `pinjaman`
Field utama:
- `nomor_pinjaman` (auto-generate: PNJ-YYYYMM-XXXX)
- `kategori_peminjam` (crew/non_crew)
- `karyawan_id` (untuk crew)
- `nama_peminjam`, `nik_peminjam`, dll (untuk non-crew)
- `jumlah_pengajuan`, `jumlah_disetujui`
- `tenor_bulan`, `bunga_persen`, `tipe_bunga`
- `status` (workflow)
- Approval fields: `diajukan_oleh`, `direview_oleh`, `disetujui_oleh`
- Pencairan fields: `tanggal_pencairan`, `dicairkan_oleh`
- Tracking: `total_terbayar`, `sisa_pinjaman`

### Tabel: `pinjaman_cicilan`
- `pinjaman_id`
- `cicilan_ke`
- `tanggal_jatuh_tempo`
- `jumlah_pokok`, `jumlah_bunga`, `jumlah_cicilan`
- `status`, `tanggal_bayar`, `jumlah_dibayar`
- `hari_terlambat`, `denda`

### Tabel: `pinjaman_history`
- `pinjaman_id`
- `aksi`, `status_lama`, `status_baru`
- `keterangan`, `data_perubahan`
- `user_id`, `user_name`

## 📊 FLOW PENGGUNAAN

### 1. **Pengajuan Pinjaman Baru**
```
Menu: Manajemen Pinjaman → Pengajuan Pinjaman Baru
```
1. Pilih kategori: Crew atau Non-Crew
2. Isi data peminjam
3. Tentukan jumlah pinjaman & tenor
4. Pilih tipe bunga (flat/efektif)
5. Isi tujuan pinjaman
6. Upload dokumen pendukung (opsional)
7. Isi data penjamin (opsional)
8. Submit pengajuan

**Auto Process:**
- Generate nomor pinjaman otomatis
- Hitung estimasi cicilan
- Set status: Pengajuan
- Log history

### 2. **Review & Approval**
```
Menu: Manajemen Pinjaman → Pilih Pinjaman → Detail
```

**A. Review (Opsional)**
- Tandai pinjaman sedang direview
- Tambah catatan review
- Status: Review

**B. Approve/Reject**
- **Approve**: 
  - Tentukan jumlah disetujui (bisa berbeda dari pengajuan)
  - Tambah catatan persetujuan
  - Sistem hitung ulang cicilan
  - Status: Disetujui
  
- **Reject**:
  - Isi alasan penolakan
  - Status: Ditolak

### 3. **Pencairan Dana**
```
Menu: Detail Pinjaman → Cairkan Dana
```
1. Tentukan tanggal pencairan
2. Pilih metode: Tunai / Transfer
3. Isi detail rekening (jika transfer)
4. Upload bukti pencairan
5. Submit pencairan

**Auto Process:**
- Generate jadwal cicilan lengkap
- Update tanggal jatuh tempo pertama & terakhir
- Catat transaksi keuangan (pengeluaran)
- Status: Dicairkan

### 4. **Pembayaran Cicilan**
```
Menu: Detail Pinjaman → Tab Jadwal Cicilan → Bayar
```
1. Pilih cicilan yang akan dibayar
2. Input jumlah bayar (default: total tagihan termasuk denda)
3. Pilih metode pembayaran
4. Input no. referensi (opsional)
5. Upload bukti bayar (opsional)
6. Submit pembayaran

**Auto Process:**
- Hitung denda jika terlambat
- Update status cicilan
- Update total terbayar & sisa pinjaman
- Catat transaksi keuangan (pemasukan)
- Jika lunas semua: Status pinjaman → Lunas

## 🔍 FITUR PENCARIAN & FILTER

**Filter tersedia:**
- Kategori: Crew / Non-Crew
- Status: Semua status workflow
- Bulan & Tahun pengajuan
- Search: Nomor pinjaman, nama peminjam

**Statistik Dashboard:**
- Total Pengajuan Baru
- Total Dalam Review (review + disetujui)
- Total Pinjaman Berjalan (dengan nominal)
- Total Lunas (dengan total dicairkan)

## 🎨 TAMPILAN UI

### 1. **Dashboard/Index**
- Card statistik berwarna
- Tabel daftar pinjaman
- Badge status dengan warna berbeda
- Progress bar pembayaran
- Tombol aksi sesuai permission

### 2. **Form Pengajuan**
- Toggle form crew/non-crew
- Kalkulator cicilan real-time
- Validasi form komprehensif
- Upload multiple dokumen
- Estimasi cicilan otomatis

### 3. **Detail Pinjaman**
- Layout 2 kolom:
  - Kiri: Info lengkap + jadwal cicilan + history
  - Kanan: Status card + tombol aksi + dokumen
- Timeline approval visual
- Tabel jadwal cicilan interaktif
- Modal untuk setiap aksi

## 🔐 PERMISSION & ROLE

**Required Role:** `super admin`

Semua fitur pinjaman hanya dapat diakses oleh super admin:
- Lihat daftar pinjaman
- Buat pengajuan baru
- Review & approval
- Pencairan dana
- Pembayaran cicilan
- Edit & hapus (sesuai status)

## 📱 INTEGRASI SISTEM

### 1. **Transaksi Keuangan**
- **Pencairan**: Otomatis catat sebagai pengeluaran
- **Pembayaran**: Otomatis catat sebagai pemasukan
- Kategori: `pinjaman_karyawan`, `pembayaran_pinjaman`

### 2. **Data Karyawan**
- Relasi dengan tabel `karyawan` (via NIK)
- Auto-fill data karyawan untuk kategori crew

## 📈 LAPORAN

**Fitur Laporan** (route ready):
- Filter per periode (bulan/tahun)
- Filter per kategori
- Statistik:
  - Total pinjaman dicairkan
  - Total terbayar
  - Total sisa pinjaman
  - Total denda keterlambatan
- Export PDF

## 🛠️ TEKNIS IMPLEMENTASI

### Files Created:
```
database/migrations/
├── 2025_11_13_120000_create_pinjaman_table.php
├── 2025_11_13_120001_create_pinjaman_cicilan_table.php
└── 2025_11_13_120002_create_pinjaman_history_table.php

app/Models/
├── Pinjaman.php
├── PinjamanCicilan.php
└── PinjamanHistory.php

app/Http/Controllers/
└── PinjamanController.php

resources/views/pinjaman/
├── index.blade.php
├── create.blade.php
├── edit.blade.php
└── show.blade.php
```

### Routes:
```php
/pinjaman                    // List
/pinjaman/create             // Form pengajuan
/pinjaman/{id}               // Detail
/pinjaman/{id}/edit          // Edit
/pinjaman/{id}/review        // Review
/pinjaman/{id}/approve       // Approve/Reject
/pinjaman/{id}/cairkan       // Pencairan
/pinjaman/cicilan/{id}/bayar // Bayar cicilan
/pinjaman/laporan/index      // Laporan
```

## ⚠️ CATATAN PENTING

1. **Tidak Mengubah Data Lain**: 
   - Sistem pinjaman ini independen
   - Tidak mengubah menu/data keuangan tukang yang sudah ada
   - Transaksi keuangan terintegrasi tapi tidak merusak data existing

2. **Validasi Ketat**:
   - Minimal pinjaman: Rp 100.000
   - Tenor maksimal: 60 bulan
   - Bunga maksimal: 100% per tahun

3. **Automasi**:
   - Nomor pinjaman auto-generate
   - Jadwal cicilan auto-generate saat pencairan
   - Denda auto-calculate saat pembayaran
   - History auto-log setiap aksi

4. **Keamanan**:
   - Soft delete untuk pinjaman
   - File upload dengan validasi
   - Permission role-based
   - Audit trail lengkap

## 🚀 CARA AKSES

1. Login sebagai Super Admin
2. Lihat menu sidebar: **Manajemen Pinjaman** (di bawah Manajemen Keuangan)
3. Klik untuk masuk ke dashboard pinjaman

## 📞 CONTOH USE CASE

### Case 1: Pinjaman Karyawan
```
1. Karyawan A mengajukan pinjaman Rp 10.000.000
2. Tenor: 12 bulan, Bunga: 10% flat
3. Admin review & approve
4. Admin cairkan via transfer
5. Sistem generate 12 cicilan @ Rp 916.667/bulan
6. Setiap bulan karyawan bayar cicilan
7. Sistem auto-update sisa pinjaman
8. Setelah 12 kali bayar → Status Lunas
```

### Case 2: Pinjaman Non-Crew
```
1. Bapak B (non-karyawan) mengajukan pinjaman Rp 5.000.000
2. Upload KTP & data penjamin
3. Tenor: 6 bulan, Bunga: 12% efektif
4. Admin review, approve, cairkan
5. Setiap bulan bayar cicilan anuitas
6. Jika telat → Sistem hitung denda otomatis
```

## 🎓 BEST PRACTICE

1. **Selalu isi catatan** saat review/approval untuk audit trail
2. **Upload bukti** pencairan dan pembayaran untuk dokumentasi
3. **Monitor cicilan terlambat** secara berkala
4. **Backup dokumen** pinjaman secara rutin
5. **Review laporan** bulanan untuk analisis

---

**Status**: ✅ **FULLY IMPLEMENTED**  
**Version**: 1.0  
**Date**: November 13, 2025  
**Developer**: AI Assistant

Sistem siap digunakan! 🎉
