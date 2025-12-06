# 🚀 QUICK START: Laporan Keuangan Terstruktur

## Instalasi & Setup (5 Menit)

### 1. Generate Data Demo
```bash
php generate_data_demo_laporan_terstruktur.php
```

### 2. Akses Menu
1. Login sebagai **Super Admin**
2. Sidebar → **Manajemen Keuangan** → **Laporan Terstruktur**

### 3. Filter Periode
- Pilih tanggal: **15 Maret 2025** s/d **17 Maret 2025**
- Klik **Tampilkan**

---

## Format Laporan yang Akan Terlihat

### 📆 Per Hari
```
┌─────────────────────────────────────────┐
│  15 Maret 2025                          │
├─────────────────────────────────────────┤
│ ⚠️ Kekurangan Sebelumnya: -38.856      │
│ 💰 Tambahan Dana: 2.200.000            │
│ 📝 10 Pengeluaran                       │
│ 💵 Saldo Akhir: -48.856                │
└─────────────────────────────────────────┘
```

### 💰 Ringkasan Periode
```
Total Dana Masuk  : Rp 7.400.000
Total Dana Keluar : Rp 4.178.212
Saldo Akhir       : Rp 3.270.644
```

---

## Penjelasan Singkat

| Istilah | Arti |
|---------|------|
| **Kekurangan sebelumnya** | Saldo negatif dari hari kemarin |
| **Tambahan dana** | Uang masuk hari ini |
| **Saldo akhir** | Sisa uang setelah semua transaksi |

---

## Fitur Utama

✅ **Auto Carry-Forward** - Saldo otomatis terus ke hari berikutnya  
✅ **Format Terstruktur** - Mudah dibaca seperti laporan manual  
✅ **Detail Lengkap** - Setiap pengeluaran tercatat dengan waktu  
✅ **Ringkasan Periode** - Total masuk/keluar otomatis  
✅ **Print Ready** - Bisa langsung dicetak  

---

## Cara Input Transaksi

### Via Dashboard
1. Menu **Dashboard Keuangan**
2. Klik **Import Excel** atau tambah manual
3. Data otomatis masuk ke laporan terstruktur

### Via Script
```php
App\Models\RealisasiDanaOperasional::create([
    'tanggal_realisasi' => now(),
    'keterangan' => 'BBM Mobil',
    'nominal' => 150000,
    'tipe_transaksi' => 'keluar',
]);
```

---

## Troubleshooting Cepat

### Laporan kosong?
```bash
# Generate data demo dulu
php generate_data_demo_laporan_terstruktur.php
```

### Saldo tidak sesuai?
```bash
php artisan tinker
>>> App\Models\SaldoHarianOperasional::recalculateAll()
```

---

## 🎯 Next Steps

1. ✅ Lihat laporan demo
2. ⏭️ Input transaksi real
3. 📊 Export ke PDF
4. 📧 Share ke tim

---

Dokumentasi lengkap: **DOKUMENTASI_LAPORAN_KEUANGAN_TERSTRUKTUR.md**
