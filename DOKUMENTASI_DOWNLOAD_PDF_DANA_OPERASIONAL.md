# 📄 DOKUMENTASI DOWNLOAD PDF - DANA OPERASIONAL

## 🎯 Ringkasan Fitur
Sistem download PDF untuk laporan keuangan Dana Operasional dengan format bank-grade professional.

---

## ✨ Fitur Utama

### 1. **Download PDF Sesuai Filter**
- PDF yang didownload akan mengikuti filter yang sedang aktif
- Mendukung semua jenis filter:
  - 📅 **Per Bulan** (default)
  - 📆 **Per Tahun**
  - 🗓️ **Per Minggu**
  - 📊 **Range Tanggal**

### 2. **Cara Akses Download PDF**

#### A. Tombol di Bagian Filter
- Klik tombol **"Download PDF"** (warna merah) di sebelah tombol Filter
- PDF akan otomatis download sesuai filter yang aktif

#### B. Floating Action Button (FAB)
- Klik tombol **FAB (lingkaran hijau)** di kanan bawah layar
- Pilih menu **"Download PDF"**
- PDF akan otomatis download sesuai filter yang aktif

---

## 📋 Format PDF

### Header Laporan
```
BUMI SULTAN
Excellence in Financial Management & Transparency
Alamat: Jl. Raya Jonggol No.37, RT.02/RW.02, Jonggol
```

### Informasi Laporan
- **Periode Laporan**: Sesuai filter yang dipilih
- **Tanggal Cetak**: Waktu saat PDF dibuat
- **Total Transaksi**: Jumlah transaksi dalam periode
- **Nomor Dokumen**: Kode unik untuk tracking

### Tabel Transaksi Detail
Kolom yang ditampilkan:
1. **No** - Nomor urut
2. **Kode Transaksi** - Nomor realisasi unik
3. **Tanggal & Jam** - Tanggal transaksi + timestamp
4. **Kategori** - Kategori transaksi
5. **Keterangan Lengkap** - Uraian detail
6. **CR (Credit)** - Dana Masuk (warna hijau)
7. **DB (Debit)** - Dana Keluar (warna merah)

### Ringkasan Keuangan
- **Saldo Awal Periode**
- **Total Pemasukan (Credit)**
- **Total Pengeluaran (Debit)**
- **Selisih** (Pemasukan - Pengeluaran)
- **SALDO AKHIR PERIODE** (Final Balance)

### Footer
- Informasi pencetakan
- Copyright & confidential statement
- Total halaman dan transaksi

---

## 🚀 Cara Penggunaan

### Contoh 1: Download Laporan Bulan Ini
```
1. Pilih Filter: "Per Bulan"
2. Pilih Bulan: November 2025 (default bulan sekarang)
3. Klik "Tampilkan"
4. Klik "Download PDF"
✅ PDF laporan November 2025 akan terdownload
```

### Contoh 2: Download Laporan Range Tanggal
```
1. Pilih Filter: "Range Tanggal"
2. Dari Tanggal: 01-11-2025
3. Sampai Tanggal: 15-11-2025
4. Klik "Tampilkan"
5. Klik "Download PDF"
✅ PDF laporan 1-15 Nov 2025 akan terdownload
```

### Contoh 3: Download Laporan Tahun 2025
```
1. Pilih Filter: "Per Tahun"
2. Pilih Tahun: 2025
3. Klik "Tampilkan"
4. Klik "Download PDF"
✅ PDF laporan seluruh tahun 2025 akan terdownload
```

---

## 📊 Contoh Output

### Nama File
Format: `Laporan_Keuangan_[TanggalAwal]_[TanggalAkhir].pdf`

Contoh:
- `Laporan_Keuangan_20251101_20251130.pdf` (Bulan Nov 2025)
- `Laporan_Keuangan_20250101_20251231.pdf` (Tahun 2025)
- `Laporan_Keuangan_20251101_20251107.pdf` (Minggu)

### Tampilan PDF
- **Orientasi**: Landscape (horizontal) untuk kolom lebih luas
- **Ukuran**: A4
- **Warna**: 
  - Header: Biru (#1e3a8a)
  - Pemasukan: Hijau (#16a34a)
  - Pengeluaran: Merah (#dc2626)
  - Background: Abu-abu muda untuk alternating rows

---

## 🎨 Desain Profesional

### Style Bank-Grade
1. **Header**: Logo dan info perusahaan formal
2. **Typography**: Arial, font size konsisten
3. **Color Scheme**: Profesional blue, green, red
4. **Layout**: Rapi dengan spacing optimal
5. **Table**: Border jelas, header bold
6. **Summary Box**: Highlight dengan background
7. **Footer**: Info confidential & copyright

### Fitur Keamanan Dokumen
- Nomor dokumen unik
- Timestamp pencetakan
- Pernyataan transparansi
- Tanda tangan Finance Manager

---

## 🔧 Technical Details

### Route
```php
GET /dana-operasional/export-pdf
Route name: dana-operasional.export-pdf
```

### Controller Method
```php
DanaOperasionalController::exportPdf(Request $request)
```

### View Template
```
resources/views/dana-operasional/pdf-simple.blade.php
```

### Library
```php
use Barryvdh\DomPDF\Facade\Pdf;
```

### Parameters
- `filter_type`: bulan|tahun|minggu|range
- `bulan`: YYYY-MM (untuk filter bulan)
- `tahun`: YYYY (untuk filter tahun)
- `minggu`: YYYY-W[number] (untuk filter minggu)
- `start_date`: YYYY-MM-DD (untuk filter range)
- `end_date`: YYYY-MM-DD (untuk filter range)

---

## ✅ Keunggulan

1. ✅ **Sesuai Filter**: PDF mengikuti filter yang dipilih user
2. ✅ **Tampilan Profesional**: Desain bergaya bank internasional
3. ✅ **Detail Lengkap**: Semua transaksi ditampilkan
4. ✅ **Ringkasan Jelas**: Summary box dengan saldo akhir
5. ✅ **Easy Access**: 2 cara akses (tombol & FAB)
6. ✅ **Auto Download**: Langsung download, tidak perlu buka tab baru
7. ✅ **Unique Code**: Setiap transaksi dengan kode tracking
8. ✅ **Timestamp**: Tanggal dan jam lengkap
9. ✅ **Alternating Rows**: Mudah dibaca
10. ✅ **Landscape**: Kolom lebih luas

---

## 🎯 Use Case

### Untuk Admin Keuangan
- Download laporan bulanan untuk archive
- Cetak PDF untuk ditanda tangani
- Share ke atasan via email
- Dokumentasi audit trail

### Untuk Management
- Review laporan keuangan detail
- Monitoring cash flow
- Approval transaksi bulanan
- Analisa pengeluaran

### Untuk Audit
- Dokumentasi lengkap setiap transaksi
- Kode tracking untuk verifikasi
- Timestamp untuk audit trail
- Summary untuk quick review

---

## 🔍 Tips Penggunaan

1. **Filter Dulu, Baru Download**: Pastikan pilih filter yang benar sebelum download
2. **Check Preview**: Lihat data di layar dulu sebelum download PDF
3. **Naming Convention**: Nama file otomatis sesuai tanggal, mudah dicari
4. **Print Ready**: PDF siap cetak tanpa perlu edit
5. **Share Ready**: Ukuran file kecil, mudah dishare via email/WhatsApp

---

## 🆘 Troubleshooting

### PDF Tidak Terdownload
1. Check apakah ada data transaksi di periode tersebut
2. Clear browser cache
3. Coba browser lain
4. Check pop-up blocker browser

### PDF Kosong
1. Pastikan ada transaksi di periode yang dipilih
2. Check filter yang dipilih sudah benar
3. Refresh halaman dan coba lagi

### Error 500
1. Check log: `storage/logs/laravel.log`
2. Pastikan library dompdf terinstall
3. Run: `php artisan optimize:clear`
4. Check storage folder writable

---

## 📞 Support

Jika ada masalah dengan fitur download PDF:
1. Check dokumentasi ini terlebih dahulu
2. Check console browser (F12) untuk error
3. Check Laravel log file
4. Contact IT Support

---

**✨ Fitur download PDF siap digunakan!**

**Tombol PDF sudah ada di 2 lokasi:**
1. 🔴 Tombol merah "Download PDF" di bagian filter
2. 🟢 Menu "Download PDF" di Floating Action Button (kanan bawah)

**Happy reporting! 📊📄**
