# ⚡ Quick Start - Export PDF Laporan Keuangan

## 🎯 Akses Cepat

```
URL: /transaksi-keuangan
Route: transaksi-keuangan.index
```

## 🚀 Penggunaan 3 Langkah

### 1️⃣ Buka Halaman
Navigasi ke: **Transaksi Keuangan** → **Download Laporan PDF**

### 2️⃣ Pilih Periode
- **Manual**: Pilih tanggal dari & tanggal sampai
- **Quick Filter**: Klik tombol: Hari Ini | Minggu Ini | Bulan Ini | Bulan Lalu | Tahun Ini

### 3️⃣ Download
Klik tombol **Download PDF** → File otomatis terunduh

---

## 📥 Hasil PDF

### ✨ Fitur Desain
- ✅ Header BUMI SULTAN profesional
- ✅ Alamat lengkap Jonggol
- ✅ Layout bergaya bank internasional
- ✅ Tabel transaksi detail
- ✅ Ringkasan keuangan (Pemasukan, Pengeluaran, Saldo)
- ✅ Watermark keamanan
- ✅ Area tanda tangan

### 📊 Isi Laporan
```
┌─────────────────────────────────────────┐
│         BUMI SULTAN                     │
│   Excellence in Financial Management    │
│                                         │
│ Jl. Raya Jonggol No.37, Jonggol        │
│ Kabupaten Bogor, Jawa Barat 16830      │
└─────────────────────────────────────────┘

LAPORAN TRANSAKSI KEUANGAN
Periode: 01 November 2025 - 30 November 2025

┌────┬────────┬────────┬────────────┬────────┐
│ No │ Tgl    │ Tukang │ Pemasukan  │ Keluar │
├────┼────────┼────────┼────────────┼────────┤
│ 1  │ 01/11  │ Ahmad  │ Rp 100.000 │ -      │
│ 2  │ 02/11  │ Budi   │ -          │ 50.000 │
└────┴────────┴────────┴────────────┴────────┘

RINGKASAN KEUANGAN:
├ Total Pemasukan:   Rp 1.500.000
├ Total Pengeluaran: Rp   500.000
└ Saldo Akhir:       Rp 1.000.000
```

---

## 🎨 Preview Features

| Feature | Description |
|---------|-------------|
| **Header** | Logo + Nama + Alamat BUMI SULTAN |
| **Period Info** | Tanggal dari - sampai |
| **Transaction Table** | No, Tanggal, Tukang, Keterangan, Tipe, Nominal |
| **Summary** | Total Credit/Debit + Net Balance |
| **Security** | Watermark + Document Number |
| **Footer** | Timestamp + Copyright |

---

## 🔗 Direct Links

### Dari Controller Lain
```php
return redirect()->route('transaksi-keuangan.index');
```

### Download Langsung
```php
return redirect()->route('transaksi-keuangan.export-pdf', [
    'tanggal_dari' => '2025-11-01',
    'tanggal_sampai' => '2025-11-30'
]);
```

### Dari Blade
```blade
<a href="{{ route('transaksi-keuangan.index') }}">
    Download Laporan PDF
</a>
```

---

## ⚙️ Instalasi (Jika Belum)

```bash
# 1. Install dependency
composer require barryvdh/laravel-dompdf

# 2. Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 3. Test akses
# Buka: /transaksi-keuangan
```

---

## 📋 Checklist Files

Pastikan file berikut ada:
- ✅ `app/Http/Controllers/TransaksiKeuanganController.php`
- ✅ `resources/views/transaksi-keuangan/index.blade.php`
- ✅ `resources/views/transaksi-keuangan/pdf.blade.php`
- ✅ Route di `routes/web.php`

---

## 🎯 Tips Penggunaan

### Filter Cepat
| Button | Period |
|--------|--------|
| Hari Ini | Today only |
| Minggu Ini | Monday - Today |
| Bulan Ini | 1st - Today |
| Bulan Lalu | Previous month (full) |
| Tahun Ini | Jan 1 - Today |

### Best Practices
- 📅 Gunakan periode maksimal 3 bulan untuk performa optimal
- 💾 Simpan PDF dengan penamaan yang jelas
- 🔒 Jangan share dokumen ke pihak tidak berwenang
- ✅ Validasi data sebelum export

---

## 🆘 Quick Troubleshooting

| Problem | Solution |
|---------|----------|
| PDF kosong | Tidak ada transaksi di periode tersebut |
| Error 500 | Clear cache: `php artisan cache:clear` |
| Layout berantakan | Re-install: `composer require barryvdh/laravel-dompdf` |
| Download gagal | Periksa permission folder storage |

---

## 📞 Need Help?

Lihat dokumentasi lengkap di: `DOKUMENTASI_EXPORT_PDF_KEUANGAN.md`

---

**🚀 Ready to Use!** Akses sekarang: `/transaksi-keuangan`
