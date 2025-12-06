# QUICK START - MANAJEMEN KEUANGAN

## 🚀 Panduan Cepat Setup & Penggunaan

### STEP 1: Setup Permissions & Database

```bash
# 1. Jalankan setup permissions
php setup_permissions_manajemen_keuangan.php

# 2. Jalankan migrasi database
php artisan migrate

# 3. Seed data awal Chart of Accounts
php artisan db:seed --class=AkunKeuanganSeeder
```

**Output yang diharapkan**:
```
✓ Setup permissions berhasil
✓ 35+ permissions dibuat
✓ Permissions assigned ke Super Admin
✓ 8 tabel database berhasil dibuat
✓ Chart of Accounts standar berhasil di-seed
```

---

### STEP 2: Akses Menu

1. Login sebagai **Super Admin**
2. Buka sidebar, cari menu **"Manajemen Keuangan"** (icon: 💰)
3. Klik untuk expand submenu:
   - Dashboard Keuangan
   - Chart of Accounts
   - Kas & Bank
   - Transaksi Keuangan
   - Anggaran (Budgeting)
   - Monitoring Anggaran
   - Rekonsiliasi Bank
   - Laporan Keuangan

---

### STEP 3: Setup Awal Data

#### A. Setup Kas & Bank
1. Buka **Kas & Bank**
2. Tambah data kas/bank yang digunakan perusahaan:

**Contoh 1 - Kas Kecil**:
- Kode: KAS-001
- Nama: Kas Kecil Kantor
- Tipe: Kas
- Akun Keuangan: 1-1001 Kas
- Saldo Awal: Rp 5.000.000

**Contoh 2 - Bank BCA**:
- Kode: BANK-BCA-001
- Nama: Bank BCA - Operasional
- Tipe: Bank
- Nama Bank: BCA
- Nomor Rekening: 1234567890
- Atas Nama: PT. Perusahaan ABC
- Cabang: Sudirman
- Akun Keuangan: 1-1002 Bank
- Saldo Awal: Rp 100.000.000

#### B. (Opsional) Tambah Akun Custom
Jika akun yang di-seed tidak cukup, tambahkan akun baru:
1. Buka **Chart of Accounts**
2. Klik "Tambah Akun Baru"
3. Sesuaikan dengan kebutuhan

---

### STEP 4: Buat Transaksi Pertama

#### Contoh: Transaksi Penerimaan Pendapatan

1. Buka **Transaksi Keuangan** → **Buat Transaksi Baru**
2. Isi form:
   - Jenis Transaksi: **Masuk**
   - Tanggal: **Hari ini**
   - Kas/Bank: **Bank BCA - Operasional**
   - Akun Debit: **1-1002 Bank** (kas/bank bertambah)
   - Akun Kredit: **4-1001 Pendapatan Jasa** (pendapatan bertambah)
   - Jumlah: **Rp 15.000.000**
   - Kategori: **Operasional**
   - Keterangan: "Pembayaran dari klien PT. XYZ untuk jasa konsultasi"
   - Referensi: INV-2024-001
3. Upload bukti transfer (opsional)
4. **Simpan**

**Status**: Draft → Pending (menunggu approval)

#### Approve & Post Transaksi

1. Buka detail transaksi yang baru dibuat
2. Klik **"Approve"**
3. Klik **"Post to Ledger"**

**Hasil**:
- Saldo Bank BCA bertambah Rp 15.000.000
- Pendapatan Jasa bertambah Rp 15.000.000
- Jurnal entries otomatis dibuat

---

### STEP 5: Monitoring Dashboard

1. Buka **Dashboard Keuangan**
2. Lihat ringkasan:
   - Total Kas & Bank saat ini
   - Transaksi masuk/keluar bulan ini
   - Cash flow bulan ini
   - Grafik trend 6 bulan
   - Top pengeluaran
   - Transaksi terbaru

---

## 📋 CHECKLIST SETUP LENGKAP

- [ ] Setup permissions berhasil
- [ ] Migrasi database berhasil
- [ ] Seed Chart of Accounts
- [ ] Buat minimal 1 Kas
- [ ] Buat minimal 1 Bank
- [ ] Buat transaksi test
- [ ] Approve & post transaksi
- [ ] Cek dashboard update

---

## 🎯 SKENARIO PENGGUNAAN UMUM

### Skenario 1: Pembayaran Gaji Karyawan
**Jenis**: Transaksi Keluar

```
Kas/Bank: Bank BCA
Debit: 5-1001 Beban Gaji (beban bertambah)
Kredit: 1-1002 Bank BCA (bank berkurang)
Jumlah: Rp 50.000.000
Kategori: Operasional
Keterangan: Pembayaran gaji karyawan bulan November 2024
```

### Skenario 2: Pembelian Aset (Laptop)
**Jenis**: Transaksi Keluar

```
Kas/Bank: Bank BCA
Debit: 1-2004 Peralatan (aset bertambah)
Kredit: 1-1002 Bank BCA (bank berkurang)
Jumlah: Rp 15.000.000
Kategori: Investasi
Keterangan: Pembelian 3 unit laptop untuk karyawan baru
Referensi: PO-2024-089
```

### Skenario 3: Pembayaran Listrik
**Jenis**: Transaksi Keluar

```
Kas/Bank: Kas Kecil
Debit: 5-1002 Beban Listrik (beban bertambah)
Kredit: 1-1001 Kas (kas berkurang)
Jumlah: Rp 2.500.000
Kategori: Operasional
Keterangan: Pembayaran tagihan listrik November 2024
```

### Skenario 4: Transfer Antar Bank
**Jenis**: Transfer

```
Kas/Bank Sumber: Bank BCA
Kas/Bank Tujuan: Bank Mandiri
Debit: 1-1003 Bank Mandiri (bank tujuan bertambah)
Kredit: 1-1002 Bank BCA (bank sumber berkurang)
Jumlah: Rp 20.000.000
Kategori: Operasional
Keterangan: Transfer dana ke rekening payroll Bank Mandiri
```

---

## 🔄 WORKFLOW HARIAN

### Pagi (08:00 - 10:00)
1. Cek dashboard untuk overview
2. Review transaksi pending approval
3. Approve transaksi yang valid

### Siang (13:00 - 15:00)
1. Input transaksi hari ini
2. Upload bukti transaksi
3. Submit untuk approval

### Sore (16:00 - 17:00)
1. Post transaksi yang sudah approved
2. Cek saldo kas & bank
3. Review anomali jika ada

### Akhir Bulan
1. Tutup semua transaksi bulan ini
2. Rekonsiliasi bank
3. Generate laporan keuangan
4. Review anggaran vs realisasi

---

## ⚡ TIPS & TRICKS

### 1. Gunakan Nomor Referensi
Selalu isi nomor referensi (invoice, PO, dll) untuk memudahkan tracking

### 2. Upload Bukti Transaksi
Upload bukti transfer/struk untuk audit trail yang baik

### 3. Konsisten dengan Kategori
Gunakan kategori yang sama untuk transaksi sejenis agar laporan lebih akurat

### 4. Approval Tepat Waktu
Jangan tunda approval transaksi agar laporan real-time

### 5. Backup Berkala
Export data ke Excel/PDF secara berkala sebagai backup

---

## 🆘 BANTUAN CEPAT

### Error: "Permission denied"
**Solusi**: Pastikan user memiliki permission yang sesuai

### Transaksi tidak muncul di laporan
**Solusi**: Pastikan transaksi sudah di-post (status: Posted)

### Saldo tidak balance
**Solusi**: Cek jurnal entries, pastikan debit = kredit

### Tidak bisa edit transaksi
**Solusi**: Hanya transaksi dengan status Draft/Rejected yang bisa diedit

---

## 📚 DOKUMENTASI LENGKAP

Baca dokumentasi lengkap di: `DOKUMENTASI_MANAJEMEN_KEUANGAN.md`

---

## ✅ SELESAI!

Sistem Manajemen Keuangan siap digunakan! 🎉

Untuk pertanyaan lebih lanjut, hubungi Tim IT Support.
