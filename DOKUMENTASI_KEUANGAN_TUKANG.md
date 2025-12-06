# DOKUMENTASI KEUANGAN TUKANG

## 📋 Overview

Modul **Keuangan Tukang** adalah sistem manajemen keuangan yang terpisah dari sistem kehadiran, dirancang untuk mengelola seluruh aspek keuangan tukang meliputi:
- Upah harian
- Lembur (full day, setengah hari, cash)
- Pinjaman dan cicilan
- Potongan gaji
- Laporan keuangan

## 🏗️ Struktur Database

### 1. Tabel `keuangan_tukangs`
**Tabel utama untuk mencatat semua transaksi keuangan tukang**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tukang_id | bigint | Foreign key ke tabel tukangs |
| tanggal | date | Tanggal transaksi |
| jenis_transaksi | enum | upah_harian, lembur_full, lembur_setengah, lembur_cash, pinjaman, pembayaran_pinjaman, potongan, bonus, lain_lain |
| jumlah | decimal(15,2) | Nominal transaksi |
| tipe | enum | debit (masuk) atau kredit (keluar) |
| kehadiran_tukang_id | bigint nullable | Relasi ke tabel kehadiran_tukangs |
| pinjaman_tukang_id | bigint nullable | Relasi ke tabel pinjaman_tukangs |
| potongan_tukang_id | bigint nullable | Relasi ke tabel potongan_tukangs |
| keterangan | string nullable | Catatan tambahan |
| dicatat_oleh | string nullable | User yang mencatat |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### 2. Tabel `pinjaman_tukangs`
**Tabel untuk mencatat pinjaman tukang**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tukang_id | bigint | Foreign key ke tabel tukangs |
| tanggal_pinjaman | date | Tanggal pinjaman dibuat |
| jumlah_pinjaman | decimal(15,2) | Total pinjaman |
| jumlah_terbayar | decimal(15,2) | Jumlah yang sudah dibayar |
| sisa_pinjaman | decimal(15,2) | Sisa yang harus dibayar |
| status | enum | aktif, lunas, dibatalkan |
| cicilan_per_minggu | decimal(15,2) nullable | Jumlah cicilan per minggu |
| keterangan | text nullable | Catatan pinjaman |
| tanggal_lunas | date nullable | Tanggal pelunasan |
| dicatat_oleh | string nullable | User yang mencatat |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### 3. Tabel `potongan_tukangs`
**Tabel untuk mencatat potongan gaji**

| Kolom | Tipe | Keterangan |
|-------|------|------------|
| id | bigint | Primary key |
| tukang_id | bigint | Foreign key ke tabel tukangs |
| tanggal | date | Tanggal potongan |
| jenis_potongan | enum | keterlambatan, tidak_hadir, kerusakan_alat, pinjaman, denda, lain_lain |
| jumlah | decimal(15,2) | Nominal potongan |
| keterangan | text nullable | Detail potongan |
| dicatat_oleh | string nullable | User yang mencatat |
| created_at | timestamp | - |
| updated_at | timestamp | - |

## 📁 Struktur File

```
app/
├── Http/Controllers/
│   └── KeuanganTukangController.php
├── Models/
│   ├── KeuanganTukang.php
│   ├── PinjamanTukang.php
│   ├── PotonganTukang.php
│   └── Tukang.php (updated)
database/migrations/
├── 2025_11_10_214020_create_keuangan_tukangs_table.php
├── 2025_11_10_215227_create_pinjaman_tukangs_table.php
└── 2025_11_10_215853_create_potongan_tukangs_table.php
resources/views/
└── keuangan-tukang/
    ├── index.blade.php (Dashboard)
    ├── detail.blade.php (Detail per tukang)
    ├── lembur-cash.blade.php (Pembayaran lembur cash)
    ├── laporan.blade.php (Laporan keuangan)
    ├── laporan-pdf.blade.php (Export PDF)
    ├── pinjaman/
    │   └── index.blade.php
    └── potongan/
        └── index.blade.php
routes/
└── web.php (updated)
setup_permissions_keuangan_tukang.php
```

## 🛣️ Routes

### Dashboard & Overview
- `GET /keuangan-tukang` → Dashboard keuangan
- `GET /keuangan-tukang/detail/{tukang_id}` → Detail transaksi per tukang

### Lembur Cash
- `GET /keuangan-tukang/lembur-cash` → Halaman pembayaran lembur cash
- `POST /keuangan-tukang/lembur-cash/toggle` → Toggle on/off lembur cash

### Pinjaman
- `GET /keuangan-tukang/pinjaman` → Daftar pinjaman
- `POST /keuangan-tukang/pinjaman` → Tambah pinjaman baru
- `POST /keuangan-tukang/pinjaman/{id}/bayar` → Bayar cicilan

### Potongan
- `GET /keuangan-tukang/potongan` → Daftar potongan
- `POST /keuangan-tukang/potongan` → Tambah potongan baru
- `DELETE /keuangan-tukang/potongan/{id}` → Hapus potongan

### Laporan
- `GET /keuangan-tukang/laporan` → Laporan keuangan
- `GET /keuangan-tukang/laporan/export-pdf` → Export PDF

### Redirect Lama
- `GET /cash-lembur` → Redirect ke `/keuangan-tukang/lembur-cash`
- `POST /cash-lembur/toggle` → Redirect ke `/keuangan-tukang/lembur-cash/toggle`

## 🔐 Permissions

| Permission | Keterangan |
|-----------|------------|
| keuangan-tukang.index | Melihat dashboard keuangan tukang |
| keuangan-tukang.lembur-cash | Kelola pembayaran lembur cash |
| keuangan-tukang.pinjaman | Kelola pinjaman tukang |
| keuangan-tukang.potongan | Kelola potongan tukang |
| keuangan-tukang.laporan | Melihat & export laporan keuangan |

## 🔧 Fitur Utama

### 1. Dashboard Keuangan Tukang
Menampilkan overview keuangan semua tukang per bulan:
- Total upah harian
- Total lembur (full + setengah + cash)
- Total potongan
- Pinjaman aktif
- Gaji bersih (upah + lembur - potongan)

### 2. Detail Transaksi Per Tukang
Menampilkan semua transaksi keuangan tukang:
- Riwayat transaksi lengkap dengan tanggal
- Summary debit, kredit, dan saldo bersih
- Daftar pinjaman aktif
- Filter berdasarkan bulan dan tahun

### 3. Lembur Cash
**DIPINDAHKAN DARI KEHADIRAN TUKANG**
- Daftar tukang yang lembur hari ini
- Toggle on/off untuk bayar lembur cash hari ini
- Otomatis mencatat transaksi keuangan
- Jika OFF, lembur dibayar hari Kamis (sistem normal)

### 4. Manajemen Pinjaman
- Input pinjaman baru dengan nominal dan cicilan per minggu
- Bayar cicilan pinjaman
- Tracking status pinjaman (aktif/lunas)
- Otomatis update sisa pinjaman
- Otomatis mencatat transaksi pembayaran

### 5. Manajemen Potongan
- Input potongan dengan berbagai jenis:
  - Keterlambatan
  - Tidak hadir
  - Kerusakan alat
  - Pinjaman
  - Denda
  - Lain-lain
- Otomatis mencatat transaksi keuangan
- Hapus potongan jika ada kesalahan

### 6. Laporan Keuangan
- Rekap keuangan per tukang per bulan
- Total debit (pemasukan)
- Total kredit (potongan)
- Gaji bersih
- Pinjaman aktif
- Export ke PDF

## 💡 Alur Kerja

### Proses Pencatatan Upah Harian
1. Admin mencatat kehadiran tukang di menu **Kehadiran Tukang**
2. Sistem otomatis menghitung upah berdasarkan status:
   - Hadir: 100% tarif harian
   - Setengah hari: 50% tarif harian
   - Tidak hadir: 0
3. **Transaksi keuangan BELUM otomatis tercatat** (akan diimplementasi)

### Proses Lembur Cash
1. Admin buka menu **Keuangan Tukang > Lembur Cash**
2. Pilih tanggal (default hari ini)
3. Klik tombol **Cash** untuk toggle on/off
4. Jika **ON**:
   - Lembur dibayar cash hari ini
   - Transaksi tercatat sebagai `lembur_cash`
   - Tidak masuk ke gaji hari Kamis
5. Jika **OFF**:
   - Lembur dibayar hari Kamis (normal)
   - Transaksi tercatat sebagai `lembur_full` atau `lembur_setengah`

### Proses Pinjaman
1. Admin buka menu **Keuangan Tukang > Pinjaman**
2. Klik **Tambah Pinjaman**
3. Isi form:
   - Pilih tukang
   - Tanggal pinjaman
   - Jumlah pinjaman
   - Cicilan per minggu (opsional)
   - Keterangan
4. Sistem mencatat:
   - Data pinjaman di tabel `pinjaman_tukangs`
   - Transaksi keuangan tipe kredit di tabel `keuangan_tukangs`

### Proses Bayar Cicilan
1. Admin buka menu **Keuangan Tukang > Pinjaman**
2. Klik **Bayar Cicilan** pada pinjaman aktif
3. Input jumlah bayar dan tanggal
4. Sistem update:
   - `jumlah_terbayar` bertambah
   - `sisa_pinjaman` berkurang
   - Status berubah jadi `lunas` jika sisa = 0
   - Transaksi tercatat sebagai `pembayaran_pinjaman`

### Proses Potongan
1. Admin buka menu **Keuangan Tukang > Potongan**
2. Klik **Tambah Potongan**
3. Isi form:
   - Pilih tukang
   - Tanggal
   - Jenis potongan
   - Jumlah
   - Keterangan
4. Sistem mencatat:
   - Data potongan di tabel `potongan_tukangs`
   - Transaksi keuangan tipe kredit di tabel `keuangan_tukangs`

## 📊 Sistem Transaksi

### Tipe Transaksi: DEBIT (Pemasukan)
- `upah_harian`: Upah kehadiran harian
- `lembur_full`: Lembur full day (dibayar Kamis)
- `lembur_setengah`: Lembur setengah hari (dibayar Kamis)
- `lembur_cash`: Lembur dibayar cash hari ini
- `bonus`: Bonus tambahan
- `lain_lain`: Pemasukan lainnya

### Tipe Transaksi: KREDIT (Pengeluaran/Potongan)
- `pinjaman`: Pinjaman baru (mengurangi gaji)
- `pembayaran_pinjaman`: Bayar cicilan pinjaman
- `potongan`: Potongan gaji (berbagai jenis)

## 🚀 Instalasi & Setup

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Setup Permissions
```bash
php setup_permissions_keuangan_tukang.php
```

### 3. Cek Routes
```bash
php artisan route:list | grep "keuangan-tukang"
```

## 🔄 Perubahan dari Sistem Lama

### ❌ Dihapus dari Kehadiran Tukang
- Method `cashLembur()` → Pindah ke `KeuanganTukangController`
- Method `toggleLemburCash()` → Pindah ke `KeuanganTukangController`
- Route `/cash-lembur` → Redirect ke `/keuangan-tukang/lembur-cash`

### ✅ Ditambahkan di Keuangan Tukang
- Dashboard keuangan lengkap
- Manajemen pinjaman & cicilan
- Manajemen potongan
- Laporan keuangan detail
- Export PDF

### 🔀 Fokus Modul
- **Kehadiran Tukang**: Fokus hanya untuk absensi harian dan lembur
- **Keuangan Tukang**: Fokus untuk semua transaksi keuangan

## 📝 TODO / Enhancement

1. ✅ Buat model dan migration
2. ✅ Buat controller lengkap
3. ✅ Setup routes dan permissions
4. ⏳ Buat views (index, detail, pinjaman, potongan, laporan)
5. ⏳ Update sidebar menu
6. ⏳ Refactor KehadiranTukangController (hapus method keuangan)
7. ⏳ Auto-record transaksi upah harian dari kehadiran
8. ⏳ Integrasi dengan sistem penggajian
9. ⏳ Notifikasi pinjaman jatuh tempo
10. ⏳ Dashboard statistik keuangan

## 📞 Support

Untuk pertanyaan atau bantuan, hubungi tim development.

---

**Last Updated**: 10 November 2025  
**Version**: 1.0.0  
**Status**: Development Phase - Controllers & Models Complete
