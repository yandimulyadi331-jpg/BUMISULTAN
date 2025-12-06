# 📄 Dokumentasi Export PDF Laporan Keuangan - Style Bank Internasional

## 🎯 Overview

Fitur Download PDF Laporan Transaksi Keuangan dengan desain profesional bergaya bank internasional untuk **BUMI SULTAN**. Laporan mencakup header perusahaan, alamat lengkap, detail transaksi, dan ringkasan keuangan dalam format yang elegan dan mudah dibaca.

---

## ✨ Fitur Utama

### 1. **Desain Bank Internasional**
- Layout profesional seperti bank statement dunia
- Typography clean dan modern (Helvetica Neue)
- Color scheme biru profesional (#1e3a8a)
- Grid system yang terstruktur

### 2. **Header BUMI SULTAN**
- Logo/Nama perusahaan yang menonjol
- Tagline: "Excellence in Financial Management"
- Alamat lengkap: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol, Kec. Jonggol, Kabupaten Bogor, Jawa Barat 16830
- Contact information placeholder

### 3. **Filter Tanggal Fleksibel**
- Custom date range picker
- Quick filters:
  - Hari Ini
  - Minggu Ini
  - Bulan Ini
  - Bulan Lalu
  - Tahun Ini

### 4. **Detail Transaksi**
- Tabel terstruktur dengan kolom:
  - Nomor urut
  - Tanggal transaksi
  - Nama & kode tukang
  - Keterangan lengkap
  - Tipe (Credit/Debit) dengan badge berwarna
  - Nominal pemasukan (hijau)
  - Nominal pengeluaran (merah)

### 5. **Ringkasan Keuangan**
- Total Pemasukan (Credit)
- Total Pengeluaran (Debit)
- Saldo Akhir (Net Balance)
- Format mata uang Indonesia (Rp)

### 6. **Security Features**
- Watermark "BUMI SULTAN" transparan
- Nomor dokumen unik
- Disclaimer & legal notice
- Area tanda tangan Finance Manager

---

## 🚀 Cara Penggunaan

### 1. **Akses Halaman**
```
URL: /transaksi-keuangan
```

### 2. **Pilih Periode**
- Pilih **Tanggal Dari** (start date)
- Pilih **Tanggal Sampai** (end date)
- Atau gunakan tombol **Filter Cepat**

### 3. **Download PDF**
- Klik tombol **Download PDF**
- PDF akan otomatis terunduh dengan nama:
  ```
  Laporan_Keuangan_YYYYMMDD_YYYYMMDD.pdf
  ```

---

## 📁 Struktur File

### 1. **Controller**
```
app/Http/Controllers/TransaksiKeuanganController.php
```

**Method utama:**
- `index()` - Menampilkan halaman download
- `exportPdf(Request $request)` - Generate dan download PDF

### 2. **View**
```
resources/views/transaksi-keuangan/
├── index.blade.php    # Halaman utama dengan form
└── pdf.blade.php      # Template PDF
```

### 3. **Routes**
```php
Route::prefix('transaksi-keuangan')->name('transaksi-keuangan.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/export-pdf', 'exportPdf')->name('export-pdf');
});
```

---

## 🎨 Desain PDF

### Color Palette
- **Primary Blue**: `#1e3a8a` (Header, borders, titles)
- **Success Green**: `#16a34a` (Pemasukan/Credit)
- **Danger Red**: `#dc2626` (Pengeluaran/Debit)
- **Neutral Gray**: `#64748b` (Text muted)
- **Background**: `#f8fafc` (Light backgrounds)

### Typography
- **Header**: 28pt, Bold, Uppercase
- **Title**: 16pt, Semibold
- **Body Text**: 10pt, Regular
- **Small Text**: 8pt, Regular
- **Numbers**: Courier New (Monospace)

### Layout Components
1. **Header Container** - Gradient background dengan border bawah
2. **Document Title** - Background biru dengan teks putih
3. **Statement Info Box** - Table display dengan label-value pairs
4. **Transaction Table** - Full-width dengan hover effects
5. **Summary Box** - Gradient dengan border biru
6. **Footer** - Metadata dan copyright

---

## 💡 Contoh Output PDF

### Header Section
```
╔═══════════════════════════════════════════╗
║          BUMI SULTAN                      ║
║   Excellence in Financial Management      ║
║                                           ║
║ Alamat: Jl. Raya Jonggol No.37, RT.02/   ║
║ RW.02, Jonggol, Kec. Jonggol,            ║
║ Kabupaten Bogor, Jawa Barat 16830        ║
╚═══════════════════════════════════════════╝
```

### Transaction Table
```
┌────┬────────────┬──────────┬─────────────┬────────┬──────────────┬──────────────┐
│ No │ Tanggal    │ Tukang   │ Keterangan  │ Tipe   │ Pemasukan    │ Pengeluaran  │
├────┼────────────┼──────────┼─────────────┼────────┼──────────────┼──────────────┤
│ 1  │ 01/11/2025 │ Ahmad    │ Gaji Harian │ Credit │ Rp 100.000   │ -            │
│ 2  │ 02/11/2025 │ Budi     │ Lembur      │ Debit  │ -            │ Rp 50.000    │
└────┴────────────┴──────────┴─────────────┴────────┴──────────────┴──────────────┘
```

### Summary Box
```
╔═══════════════════════════════════════════╗
║        RINGKASAN KEUANGAN                 ║
╠═══════════════════════════════════════════╣
║ Total Pemasukan:     Rp 1.500.000         ║
║ Total Pengeluaran:   Rp 500.000           ║
║                                           ║
║ SALDO AKHIR:         Rp 1.000.000         ║
╚═══════════════════════════════════════════╝
```

---

## 🔧 Konfigurasi Teknis

### Dependencies
```json
{
  "barryvdh/laravel-dompdf": "^3.1"
}
```

### Paper Settings
```php
$pdf->setPaper('a4', 'portrait');
```

### Data yang Digunakan
```php
- Model: KeuanganTukang
- Relations: tukang, user
- Filter: whereBetween('tanggal', [...])
- Order: tanggal ASC, created_at ASC
```

---

## 📊 Validasi & Error Handling

### Validasi Input
```php
$request->validate([
    'tanggal_dari' => 'required|date',
    'tanggal_sampai' => 'required|date|after_or_equal:tanggal_dari'
]);
```

### Error Messages
- ❌ Tanggal dari wajib diisi
- ❌ Tanggal sampai wajib diisi
- ❌ Tanggal sampai harus >= tanggal dari
- ℹ️ Tidak ada transaksi pada periode ini

---

## 🎯 Best Practices

### 1. **Performance**
- Query dengan eager loading (`with(['tukang', 'user'])`)
- Index pada kolom `tanggal` untuk faster filtering
- Limit jumlah transaksi jika terlalu banyak

### 2. **Security**
- Validasi input tanggal
- Authorization dengan gates/policies
- Sanitize data sebelum render PDF

### 3. **User Experience**
- Loading state saat generate PDF
- Quick filter buttons untuk kemudahan
- Responsive date picker
- Clear error messages

---

## 🚦 Testing

### Manual Testing Checklist
- [ ] Download dengan periode 1 hari
- [ ] Download dengan periode 1 minggu
- [ ] Download dengan periode 1 bulan
- [ ] Download dengan periode custom
- [ ] Test dengan data kosong
- [ ] Test dengan data banyak (>100 transaksi)
- [ ] Test validasi tanggal invalid
- [ ] Test quick filters
- [ ] Verifikasi format PDF
- [ ] Verifikasi watermark
- [ ] Verifikasi ringkasan perhitungan

---

## 📝 Catatan Penting

### 1. **Data Requirements**
- Minimal ada tabel `keuangan_tukang`
- Relasi ke tabel `tukang` dan `users`
- Kolom wajib: `id`, `tanggal`, `tukang_id`, `tipe_transaksi`, `jumlah`, `keterangan`

### 2. **Permissions**
Pastikan user memiliki permission untuk:
- Melihat transaksi keuangan
- Export/download laporan

### 3. **Customization**
Untuk mengubah desain, edit file:
```
resources/views/transaksi-keuangan/pdf.blade.php
```

Area yang bisa dikustomisasi:
- Logo perusahaan
- Color scheme
- Typography
- Layout sections
- Footer information

---

## 🆘 Troubleshooting

### Problem: PDF tidak ter-generate
**Solusi:**
```bash
# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Re-install dompdf
composer require barryvdh/laravel-dompdf --with-all-dependencies
```

### Problem: Font tidak muncul dengan benar
**Solusi:**
- Pastikan font Helvetica tersedia
- Atau gunakan font fallback: Arial, sans-serif

### Problem: Data tidak muncul
**Solusi:**
- Periksa relasi model `KeuanganTukang`
- Pastikan eager loading berjalan
- Debug dengan `dd($transaksi)` di controller

---

## 🎓 Contoh Penggunaan

### Dari Blade Template
```blade
<a href="{{ route('transaksi-keuangan.index') }}" class="btn btn-primary">
    Download Laporan PDF
</a>
```

### Direct Download Link
```blade
<a href="{{ route('transaksi-keuangan.export-pdf', [
    'tanggal_dari' => '2025-11-01',
    'tanggal_sampai' => '2025-11-30'
]) }}" target="_blank">
    Download November 2025
</a>
```

---

## 📞 Support

Untuk pertanyaan atau issue, hubungi:
- **Developer**: Development Team BUMI SULTAN
- **Documentation**: README.md
- **Version**: 1.0.0
- **Last Updated**: November 2025

---

## 🔄 Changelog

### Version 1.0.0 (November 2025)
- ✅ Initial release
- ✅ Bank-style PDF template
- ✅ Date range filter
- ✅ Quick filter buttons
- ✅ Transaction table
- ✅ Financial summary
- ✅ Watermark & security features
- ✅ Signature section

---

**© 2025 BUMI SULTAN - All Rights Reserved**
