# 📊 DOKUMENTASI: LAPORAN KEUANGAN ANNUAL REPORT

## Overview
Fitur **Laporan Keuangan Annual Report** adalah sistem download PDF profesional untuk laporan keuangan perusahaan dengan format dan layout seperti **perusahaan besar** (Astra Agro, Bank, dll).

## 🎯 Fitur Utama

### 1. **Cover Page Profesional**
- Logo perusahaan
- Judul laporan
- Periode laporan
- Tanggal cetak

### 2. **Table of Contents (Daftar Isi)**
- Financial Highlights
- Income Statement
- Balance Sheet
- Cash Flow Statement
- Top Transactions
- Monthly Performance Chart (untuk laporan tahunan)
- Notes to Financial Statements

### 3. **Financial Highlights (Ikhtisar Keuangan)**
- Ringkasan pendapatan, pengeluaran, laba/rugi
- Perbandingan dengan periode sebelumnya (%)
- Key Performance Indicators (KPI)
- Visual boxes dengan warna berbeda

### 4. **Income Statement (Laporan Laba Rugi)**
- Detail pendapatan per kategori
- Detail pengeluaran per kategori
- Perhitungan laba/rugi bersih
- Persentase per kategori

### 5. **Balance Sheet (Neraca)**
- Saldo awal periode
- Kas masuk & keluar
- Saldo akhir periode
- Perubahan posisi keuangan

### 6. **Cash Flow Statement (Laporan Arus Kas)**
- Arus kas masuk (jumlah & nilai)
- Arus kas keluar (jumlah & nilai)
- Arus kas bersih
- Rekonsiliasi dengan laba bersih

### 7. **Top Transactions**
- 10 transaksi dengan nilai terbesar
- Detail tanggal, tipe, kategori, keterangan

### 8. **Monthly Performance Chart**
- Grafik performa bulanan (hanya untuk laporan tahunan)
- Tabel data bulanan
- Tren pendapatan vs pengeluaran

### 9. **Notes to Financial Statements**
- Dasar penyusunan laporan
- Kebijakan akuntansi
- Informasi tambahan

## 📁 Struktur File

```
app/Http/Controllers/
└── LaporanKeuanganController.php

resources/views/
├── laporan-keuangan/
│   ├── index.blade.php
│   └── pdf-annual-report.blade.php

routes/
└── web.php (route definitions)

resources/views/layouts/
└── sidebar.blade.php (menu)
```

## 🛠️ Teknologi yang Digunakan

- **Laravel 10**
- **Barryvdh/Laravel-DomPDF** untuk generate PDF
- **Bootstrap 5** untuk styling
- **Blade Template Engine**
- **Carbon** untuk manipulasi tanggal
- **Eloquent ORM** untuk database

## 📊 Jenis Periode Laporan

### 1. Bulanan
- Pilih bulan dan tahun
- Contoh: Januari 2025

### 2. Triwulan (Quarter)
- Q1: Januari - Maret
- Q2: April - Juni
- Q3: Juli - September
- Q4: Oktober - Desember

### 3. Semester
- Semester 1: Januari - Juni
- Semester 2: Juli - Desember

### 4. Tahunan (Annual Report)
- Laporan 1 tahun penuh
- Termasuk grafik performa bulanan

## 🔧 Cara Penggunaan

### 1. Akses Menu
```
Sidebar → Laporan Keuangan
```

### 2. Pilih Periode
- Pilih jenis periode (Bulanan/Triwulan/Semester/Tahunan)
- Pilih tahun
- Pilih bulan/triwulan/semester (sesuai jenis periode)

### 3. Preview (Opsional)
- Klik tombol "Preview Laporan" untuk melihat sebelum download
- Preview akan terbuka di tab baru

### 4. Download PDF
- Klik tombol "Download Laporan PDF"
- PDF akan otomatis terdownload dengan nama:
  ```
  Laporan_Keuangan_{Type}_{Periode}_{Timestamp}.pdf
  ```

## 📝 Contoh Output

### Nama File
```
Laporan_Keuangan_Tahunan_Tahun_2025_20251114123045.pdf
Laporan_Keuangan_Bulanan_Januari_2025_20251114123045.pdf
Laporan_Keuangan_Triwulan_Triwulan_1_Tahun_2025_20251114123045.pdf
```

### Struktur PDF
```
1. Cover Page (Full Page)
2. Table of Contents
3. Financial Highlights
4. Income Statement
5. Balance Sheet
6. Cash Flow Statement
7. Top Transactions
8. Monthly Chart (jika tahunan)
9. Notes
```

## 🎨 Design & Layout

### Color Scheme
- **Primary**: #1e3c72 (Navy Blue)
- **Secondary**: #2a5298 (Light Blue)
- **Success**: Green (untuk pendapatan/laba)
- **Danger**: Red (untuk pengeluaran/rugi)

### Typography
- **Font**: Arial, Helvetica, sans-serif
- **Title**: 20pt - 36pt (bold)
- **Body**: 10pt
- **Footer**: 9pt

### Page Layout
- **Paper**: A4 Portrait
- **Margins**: 40px
- **Header**: Fixed (50px)
- **Footer**: Fixed (30px)

## 🔐 Permission & Role

- **Role Required**: `super admin`
- **Middleware**: `role:super admin`

### Route Definition
```php
Route::middleware('role:super admin')
    ->prefix('laporan-keuangan')
    ->name('laporan-keuangan.')
    ->group(function () {
        Route::get('/', [LaporanKeuanganController::class, 'index'])
            ->name('index');
        Route::get('/download-annual-report', [LaporanKeuanganController::class, 'downloadAnnualReport'])
            ->name('download-annual-report');
        Route::get('/preview', [LaporanKeuanganController::class, 'preview'])
            ->name('preview');
    });
```

## 📊 Data Source

### Tabel Database
1. **realisasi_dana_operasional**
   - tanggal_realisasi
   - tipe_transaksi (Dana Masuk/Dana Keluar)
   - kategori
   - jumlah_dana
   - keterangan

2. **saldo_harian_operasional**
   - tanggal
   - saldo_awal
   - saldo_akhir

### Query Logic
```php
// Pendapatan
$pendapatan = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$dari, $sampai])
    ->where('tipe_transaksi', 'Dana Masuk')
    ->sum('jumlah_dana');

// Pengeluaran
$pengeluaran = RealisasiDanaOperasional::whereBetween('tanggal_realisasi', [$dari, $sampai])
    ->where('tipe_transaksi', 'Dana Keluar')
    ->sum('jumlah_dana');

// Laba/Rugi
$labaRugi = $pendapatan - $pengeluaran;
```

## 🚀 Fitur Canggih

### 1. Perbandingan Periode
- Otomatis hitung perubahan (%) dari periode sebelumnya
- Visual indicator (▲ naik / ▼ turun)
- Color coding (hijau positif / merah negatif)

### 2. Data Bulanan (Laporan Tahunan)
- Generate data 12 bulan otomatis
- Grafik bar chart simple
- Tabel detail per bulan

### 3. Professional Layout
- Page break otomatis per section
- Header & footer konsisten
- Page numbering
- Table of contents dengan nomor halaman

### 4. Responsive Data
- Otomatis handle data kosong
- Perhitungan persentase aman (avoid division by zero)
- Format angka Indonesia (titik untuk ribuan)

## 💡 Tips & Best Practices

### 1. Performa
- Gunakan chunk() untuk data besar
- Cache hasil perhitungan jika perlu
- Optimize query dengan index database

### 2. Validasi
- Selalu validasi input periode
- Check apakah ada data di periode tersebut
- Handle edge case (tahun kabisat, dll)

### 3. Error Handling
```php
try {
    // Generate PDF
} catch (\Exception $e) {
    return redirect()->back()
        ->with('error', 'Gagal generate PDF: ' . $e->getMessage());
}
```

### 4. Naming Convention
- File: PascalCase (LaporanKeuanganController.php)
- Method: camelCase (downloadAnnualReport)
- Route: kebab-case (laporan-keuangan.download-annual-report)
- View: kebab-case (laporan-keuangan/pdf-annual-report.blade.php)

## 🐛 Troubleshooting

### PDF Tidak Ter-generate
1. Check apakah package dompdf sudah terinstall
2. Pastikan permission folder storage/
3. Check log error di storage/logs/laravel.log

### Layout Berantakan
1. Pastikan CSS inline (tidak pakai external CSS)
2. Gunakan table untuk layout kompleks
3. Test di berbagai PDF viewer

### Data Tidak Muncul
1. Check query database
2. Pastikan periode sudah benar
3. Debug dengan dd() atau dump()

## 📈 Future Improvements

1. **Export Excel** selain PDF
2. **Email laporan** otomatis
3. **Dashboard visual** dengan chart.js
4. **Komparasi multi-periode** (YoY, QoQ)
5. **Custom logo** perusahaan
6. **Watermark** untuk draft
7. **Digital signature** untuk laporan final
8. **Multi-currency** support

## 🎓 Learning Resources

### Referensi Annual Report Perusahaan Besar
- Astra Agro Annual Report
- Bank Mandiri Annual Report
- Telkom Indonesia Annual Report
- Unilever Indonesia Annual Report

### Standar Akuntansi
- PSAK (Pernyataan Standar Akuntansi Keuangan)
- IFRS (International Financial Reporting Standards)
- Format laporan keuangan standar Indonesia

## 📞 Support

Untuk pertanyaan atau masalah:
1. Check dokumentasi ini terlebih dahulu
2. Lihat file DOKUMENTASI_DOWNLOAD_PDF_DANA_OPERASIONAL.md
3. Contact: development team

---

**Created**: 14 November 2025
**Version**: 1.0.0
**Author**: Development Team
**Last Updated**: 14 November 2025

---

## ✅ Checklist Implementasi

- [x] Controller (LaporanKeuanganController.php)
- [x] View Index (index.blade.php)
- [x] View PDF Template (pdf-annual-report.blade.php)
- [x] Routes (web.php)
- [x] Sidebar Menu
- [x] Dokumentasi lengkap
- [ ] Testing dengan data real
- [ ] Optimization query database
- [ ] User acceptance testing (UAT)

## 🎉 Kesimpulan

Fitur **Laporan Keuangan Annual Report** ini memberikan kemampuan untuk:
✅ Generate laporan keuangan profesional seperti perusahaan besar
✅ Format PDF dengan layout berkualitas tinggi
✅ Multiple periode (bulanan, triwulan, semester, tahunan)
✅ Perbandingan dengan periode sebelumnya
✅ Grafik dan visualisasi data
✅ Cover page, table of contents, dan notes profesional

**Style & Inspiration**: Terinspirasi dari Annual Report perusahaan-perusahaan besar seperti Astra Agro, Bank-bank, dan korporasi multinasional di Indonesia.
