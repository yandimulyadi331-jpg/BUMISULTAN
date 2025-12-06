# 📊 PANDUAN IMPORT EXCEL DATA HISTORIS

## 🎯 TUJUAN
Mengimport **semua data transaksi dari Excel laptop Anda** ke sistem dengan **urutan tanggal yang sesuai** dari Januari sampai hari ini.

---

## 📥 LANGKAH-LANGKAH IMPORT

### **STEP 1: Download Template Excel**

1. Login ke sistem → Buka halaman `/dana-operasional`
2. Klik tombol **"Download Template"** (warna biru)
3. File `Template_Import_Dana_Operasional.xlsx` akan terdownload
4. Buka file Excel tersebut

---

### **STEP 2: Siapkan Data Anda di Excel**

Template Excel memiliki kolom berikut:

| Kolom | Wajib? | Contoh | Keterangan |
|-------|--------|--------|------------|
| **tanggal** | ✅ Wajib | 2025-01-05 | Format: YYYY-MM-DD atau DD/MM/YYYY |
| **tipe** | ✅ Wajib | pemasukan | `pemasukan` atau `pengeluaran` |
| **kategori** | ⚠️ Opsional | Operasional | Kategori transaksi (default: Umum) |
| **uraian** | ✅ Wajib | Saldo awal kas | Keterangan lengkap transaksi |
| **nominal** | ✅ Wajib | 50000000 | Jumlah uang (bisa dengan/tanpa Rp) |
| **keterangan** | ⚠️ Opsional | Dana awal | Catatan tambahan |

#### **Format yang Didukung:**

**Tanggal:**
- ✅ `2025-01-05` (ISO format - RECOMMENDED)
- ✅ `05/01/2025` (DD/MM/YYYY)
- ✅ `05-01-2025` (DD-MM-YYYY)
- ✅ Excel date number (otomatis terdeteksi)

**Tipe:**
- ✅ `pemasukan`, `masuk`, `credit`, `cr`, `income` → Semua dianggap PEMASUKAN
- ✅ `pengeluaran`, `keluar`, `debit`, `db`, `expense` → Semua dianggap PENGELUARAN

**Nominal:**
- ✅ `50000000` (angka biasa)
- ✅ `Rp 50.000.000` (dengan format Rupiah - otomatis dibersihkan)
- ✅ `50,000,000` (dengan koma pemisah ribuan)

---

### **STEP 3: Isi Data dari Laporan Anda**

**Contoh data di Excel:**

```
| tanggal    | tipe        | kategori     | uraian                      | nominal    | keterangan              |
|------------|-------------|--------------|----------------------------|------------|------------------------|
| 2025-01-05 | pemasukan   | Operasional  | Saldo awal kas Januari     | 50000000   | Dana awal bulan        |
| 2025-01-10 | pengeluaran | Konsumsi     | Belanja kebutuhan          | 500000     | Belanja bulanan        |
| 2025-01-15 | pemasukan   | Donasi       | Donasi dari Bapak Ahmad    | 2000000    | Donasi operasional     |
| 2025-01-20 | pengeluaran | Transport    | BBM kendaraan              | 300000     | Isi bensin             |
| 2025-02-01 | pemasukan   | Operasional  | Saldo awal Februari        | 51200000   | Carry over Jan         |
| 2025-02-05 | pengeluaran | Gaji         | Gaji karyawan              | 5000000    | Gaji bulan Februari    |
```

**Tips:**
- Copy paste data dari Excel laporan lama Anda
- Pastikan tanggal diurutkan dari yang paling lama ke terbaru
- Gunakan kategori yang konsisten

---

### **STEP 4: Save Excel dan Import ke Sistem**

1. **Save file Excel** Anda (format .xlsx atau .xls)
2. **Buka halaman** `/dana-operasional`
3. **Klik tombol** "Import Excel" (warna hijau)
4. **Modal akan muncul:**
   - Pilih **"Import Data Historis (Support Tanggal Lampau)"**
   - Pastikan alert biru muncul (bukan yang hijau)
5. **Klik "Pilih File"** → Pilih file Excel Anda
6. **Klik "Import Sekarang"**
7. **Tunggu proses** (bisa 5-30 detik tergantung jumlah data)
8. **Halaman akan reload otomatis** setelah selesai

---

### **STEP 5: Verifikasi Data**

Setelah import berhasil:

1. **Cek di tabel transaksi** → Apakah semua data masuk?
2. **Filter per bulan** → Pastikan transaksi januari ada di januari, dst
3. **Download PDF** → Pilih filter "Tahun Ini"
4. **Verifikasi saldo akhir** → Apakah sesuai dengan saldo real di kas/bank?

---

## 🔍 FORMAT EXCEL DETAIL

### **Template Lengkap:**

```excel
tanggal     | tipe        | kategori     | uraian                        | nominal    | keterangan
------------|-------------|--------------|-------------------------------|------------|------------------------
2025-01-05  | pemasukan   | Operasional  | Saldo awal kas                | 50000000   | Dana awal bulan Januari
2025-01-10  | pengeluaran | Konsumsi     | Belanja kebutuhan             | 500000     | Belanja bulanan
2025-01-15  | pemasukan   | Donasi       | Dari donatur A                | 2000000    | Donasi operasional
2025-01-20  | pengeluaran | Transport    | BBM kendaraan operasional     | 300000     | Isi bensin Avanza
2025-01-25  | pengeluaran | Utilitas     | Bayar listrik dan air         | 450000     | PLN + PDAM
2025-02-01  | pemasukan   | Operasional  | Saldo awal Februari           | 51250000   | Carry over dari Jan
2025-02-10  | pengeluaran | Gaji         | Gaji karyawan bulan Feb       | 5000000    | 3 karyawan
2025-02-15  | pemasukan   | Donasi       | Donasi Ibu Siti               | 1500000    | Untuk operasional
2025-03-01  | pemasukan   | Operasional  | Saldo awal Maret              | 47750000   | Carry over dari Feb
...
```

---

## ⚠️ ERROR & TROUBLESHOOTING

### **Error: "Kolom tanggal tidak boleh kosong"**
**Solusi:** Pastikan setiap baris ada tanggalnya, tidak ada yang kosong

### **Error: "Kolom tipe tidak boleh kosong"**
**Solusi:** Isi kolom tipe dengan `pemasukan` atau `pengeluaran`

### **Error: "Kolom nominal tidak boleh kosong"**
**Solusi:** Pastikan nominal terisi, tidak boleh 0 atau kosong

### **Error: "Import gagal: Baris 5..."**
**Solusi:** Cek baris yang disebutkan di Excel, biasanya ada data yang tidak sesuai format

### **Nominal jadi 0 atau salah**
**Solusi:** 
- Jangan pakai koma desimal (pakai titik jika perlu: `500.50`)
- Hapus semua format mata uang dari Excel
- Format cell Excel jadi "Number" atau "General"

### **Tanggal tidak sesuai**
**Solusi:**
- Gunakan format `2025-01-05` (YYYY-MM-DD)
- Atau pastikan Excel format cell-nya adalah "Date"
- Jangan pakai format text untuk tanggal

---

## 🚀 CARA CEPAT IMPORT DATA BANYAK

Jika Anda punya **ratusan atau ribuan transaksi**:

### **Metode 1: Copy Paste dari Excel Lama**

1. Buka Excel laporan lama Anda
2. Pilih semua data transaksi (tanggal, tipe, nominal, keterangan)
3. Copy (Ctrl + C)
4. Buka template yang sudah didownload
5. Paste (Ctrl + V) mulai dari baris 2 (baris 1 adalah header)
6. Sesuaikan nama kolom jika berbeda
7. Save dan import

### **Metode 2: Mapping Otomatis**

Jika kolom Excel lama berbeda:

| Excel Lama Anda | Template Sistem | Cara Mapping |
|----------------|----------------|--------------|
| Tgl Transaksi | tanggal | Copy kolom "Tgl Transaksi" ke kolom "tanggal" |
| Jenis | tipe | Ganti "Masuk" → "pemasukan", "Keluar" → "pengeluaran" |
| Jumlah | nominal | Copy langsung (sistem auto clean format) |
| Keterangan | uraian | Copy langsung |

---

## 📈 SETELAH IMPORT

Sistem akan **otomatis**:

✅ **Mengurutkan transaksi** berdasarkan tanggal (dari lama ke baru)  
✅ **Menghitung saldo harian** untuk setiap tanggal  
✅ **Update saldo akhir** secara akurat  
✅ **Generate laporan PDF** dengan data lengkap  

Anda bisa langsung:
- Filter transaksi per bulan
- Download PDF laporan
- Cek saldo per periode
- Tambah transaksi baru untuk hari ini

---

## 💡 TIPS PROFESIONAL

### **Untuk Data 1 Tahun (Jan-Nov 2025):**

1. **Buat 1 file master Excel** dengan semua transaksi
2. **Urutkan berdasarkan tanggal** (ascending)
3. **Bagi per bulan** jika data terlalu banyak (opsional)
4. **Import bertahap:**
   - Import Januari (transaksi Jan)
   - Verifikasi saldo akhir Jan
   - Import Februari (transaksi Feb)
   - Dst sampai November

### **Backup Data:**
- Sebelum import, **backup database** dulu
- Simpan file Excel master sebagai **backup**
- Setelah import berhasil, **download PDF** sebagai arsip

---

## 🎯 CHECKLIST IMPORT

```
[ ] Download template dari sistem
[ ] Siapkan data Excel dengan 6 kolom (tanggal, tipe, kategori, uraian, nominal, keterangan)
[ ] Pastikan format tanggal YYYY-MM-DD atau DD/MM/YYYY
[ ] Pastikan tipe hanya "pemasukan" atau "pengeluaran"
[ ] Pastikan nominal tanpa format rupiah (atau biarkan sistem auto clean)
[ ] Urutkan data dari tanggal paling lama
[ ] Save file Excel
[ ] Buka sistem → Import Excel → Pilih "Data Historis"
[ ] Upload file → Import Sekarang
[ ] Tunggu proses selesai
[ ] Verifikasi data di tabel
[ ] Download PDF untuk cek akhir
[ ] DONE! ✅
```

---

## 📞 SUPPORT

Jika masih bingung atau ada error:
1. Screenshot error message
2. Cek format Excel (sesuai template?)
3. Coba import data sample dulu (5-10 baris)
4. Jika berhasil, baru import semua

---

**✨ SELAMAT! Dengan fitur ini, data historis 1 tahun bisa masuk ke sistem dalam hitungan menit!**

---

## 📌 CONTOH FILE EXCEL LENGKAP

Download template, lalu isi seperti ini:

```
| tanggal    | tipe        | kategori     | uraian                      | nominal    | keterangan              |
|------------|-------------|--------------|----------------------------|------------|------------------------|
| 2025-01-01 | pemasukan   | Operasional  | Saldo awal tahun 2025      | 100000000  | Modal awal            |
| 2025-01-05 | pengeluaran | Konsumsi     | Belanja kebutuhan          | 2000000    | Belanja bulanan        |
| 2025-01-10 | pemasukan   | Donasi       | Donasi Pak Ahmad           | 5000000    | Donasi operasional     |
| 2025-01-15 | pengeluaran | Gaji         | Gaji karyawan              | 10000000   | Gaji 5 karyawan        |
| 2025-01-20 | pengeluaran | Transport    | BBM kendaraan              | 500000     | Isi bensin             |
| 2025-02-01 | pemasukan   | Operasional  | Carry over Januari         | 92500000   | Saldo akhir Jan        |
```

Dan seterusnya sampai November 2025!

---

**Dokumentasi dibuat:** 12 November 2025  
**Status:** ✅ READY TO USE  
**Fitur:** Import Excel Data Historis dengan Auto Calculate Saldo
