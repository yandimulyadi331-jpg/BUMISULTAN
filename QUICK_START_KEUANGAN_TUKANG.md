# QUICK START: Keuangan Tukang

## 🚀 Setup Cepat

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Setup Permissions
```bash
php setup_permissions_keuangan_tukang.php
```

### 3. Akses Menu
- URL: `/keuangan-tukang`
- Menu: **Manajemen Tukang > Keuangan Tukang**

## 📋 Fitur Utama

### 1. Dashboard Keuangan (`/keuangan-tukang`)
- Overview keuangan semua tukang
- Filter per bulan/tahun
- Total upah, lembur, potongan, pinjaman

### 2. Lembur Cash (`/keuangan-tukang/lembur-cash`)
- **DIPINDAHKAN DARI /cash-lembur**
- Bayar lembur cash hari ini
- Toggle on/off per tukang

### 3. Pinjaman (`/keuangan-tukang/pinjaman`)
- Input pinjaman baru
- Bayar cicilan
- Tracking status (aktif/lunas)

### 4. Potongan (`/keuangan-tukang/potongan`)
- Input potongan gaji
- Jenis: keterlambatan, denda, kerusakan, dll
- Otomatis kurangi gaji

### 5. Laporan (`/keuangan-tukang/laporan`)
- Rekap keuangan per tukang
- Export PDF
- Filter bulan/tahun

## 🔐 Permissions

| Permission | Keterangan |
|-----------|------------|
| `keuangan-tukang.index` | Dashboard & detail |
| `keuangan-tukang.lembur-cash` | Lembur cash |
| `keuangan-tukang.pinjaman` | Pinjaman |
| `keuangan-tukang.potongan` | Potongan |
| `keuangan-tukang.laporan` | Laporan & PDF |

## 📊 Struktur Transaksi

### Debit (Pemasukan) ✅
- `upah_harian`: Upah harian
- `lembur_full`: Lembur full day
- `lembur_setengah`: Lembur setengah hari
- `lembur_cash`: Lembur bayar cash

### Kredit (Potongan) ❌
- `pinjaman`: Pinjaman baru
- `pembayaran_pinjaman`: Bayar cicilan
- `potongan`: Potongan gaji

## 🔄 Perubahan dari Sistem Lama

### Route Lama (Deprecated)
```
/cash-lembur → REDIRECT ke /keuangan-tukang/lembur-cash
```

### Fokus Modul
- **Kehadiran Tukang**: Absensi harian & lembur saja
- **Keuangan Tukang**: Semua transaksi keuangan

## 💡 Tips Penggunaan

### Input Pinjaman
```
1. Pilih tukang
2. Masukkan jumlah pinjaman
3. (Opsional) Set cicilan per minggu
4. Sistem auto-record transaksi
```

### Bayar Lembur Cash
```
1. Buka menu Lembur Cash
2. Lihat daftar tukang lembur hari ini
3. Klik tombol CASH untuk toggle
4. ON = bayar cash, OFF = bayar Kamis
```

### Input Potongan
```
1. Pilih tukang
2. Pilih jenis potongan
3. Masukkan nominal
4. Sistem auto-kurangi dari gaji
```

## 🐛 Troubleshooting

### Error: Permission not found
```bash
php setup_permissions_keuangan_tukang.php
```

### Error: Table not found
```bash
php artisan migrate
```

### Menu tidak muncul
Cek di sidebar menu configuration (belum diimplementasi)

## 📖 Dokumentasi Lengkap

Lihat `DOKUMENTASI_KEUANGAN_TUKANG.md` untuk detail lengkap.

---

**Version**: 1.0.0  
**Status**: Backend Ready - Views & Menu Pending
