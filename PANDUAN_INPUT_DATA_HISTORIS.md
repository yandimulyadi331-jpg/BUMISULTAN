# 📋 PANDUAN INPUT DATA TRANSAKSI HISTORIS (BACKDATE)

## 🎯 TUJUAN
Memasukkan transaksi yang tanggalnya sudah lewat (data historis dari Januari - November 2025) ke dalam sistem yang baru dibuat.

---

## ✅ CARA INPUT DATA HISTORIS

### **METODE 1: Input Manual via Form Tambah Transaksi**

1. **Buka Halaman Dana Operasional**
   - URL: `/dana-operasional`
   - Klik tombol **"+ Tambah Transaksi"**

2. **Pilih Tanggal Historis**
   - Field **"Tanggal Transaksi"** sekarang bisa diubah
   - Klik pada field tanggal
   - Pilih tanggal dari bulan sebelumnya (Januari - November 2025)
   - Sistem mengizinkan tanggal hingga maksimal hari ini

3. **Isi Data Transaksi Lengkap**
   ```
   Tanggal Transaksi    : [Pilih tanggal historis, contoh: 2025-01-15]
   Tipe Transaksi       : Pemasukan / Pengeluaran
   Kategori            : Pilih kategori yang sesuai
   Uraian              : Keterangan detail transaksi
   Nominal             : Jumlah uang (Rp)
   ```

4. **Klik "Simpan"**
   - Transaksi akan tersimpan dengan tanggal yang dipilih
   - Sistem akan menghitung ulang saldo sesuai tanggal transaksi

---

### **METODE 2: Import Excel (Bulk Insert)**

Untuk input banyak data sekaligus, gunakan import Excel:

1. **Download Template Excel**
   - Buat file Excel dengan kolom:
     ```
     | tanggal_realisasi | tipe_transaksi | kategori | uraian | nominal |
     ```

2. **Isi Data Historis**
   ```excel
   2025-01-05  |  pemasukan   |  Operasional  |  Dana masuk awal     |  10000000
   2025-01-10  |  pengeluaran |  Konsumsi     |  Belanja kebutuhan   |  500000
   2025-02-01  |  pemasukan   |  Donasi       |  Donatur A           |  2000000
   ```

3. **Import via Fitur Import Excel**
   - Fitur ini perlu dibuat jika belum ada (opsional)

---

### **METODE 3: Input via Database (Manual SQL)**

Jika data sudah ada di Excel/CSV dan banyak:

```sql
INSERT INTO realisasi_dana_operasional 
(pengajuan_id, tanggal_realisasi, tipe_transaksi, kategori, uraian, nominal, created_by, created_at, updated_at)
VALUES
(NULL, '2025-01-05', 'pemasukan', 'Operasional', 'Dana masuk awal bulan', 10000000, 1, NOW(), NOW()),
(NULL, '2025-01-10', 'pengeluaran', 'Konsumsi', 'Belanja bulanan', 500000, 1, NOW(), NOW()),
(NULL, '2025-01-15', 'pemasukan', 'Donasi', 'Dari donatur A', 2000000, 1, NOW(), NOW());
```

**Note:** Ganti `created_by` dengan ID user yang login.

---

## 🔄 SISTEM AKAN OTOMATIS MENGHITUNG

Setelah input data historis, sistem akan otomatis:

1. ✅ **Mengurutkan transaksi berdasarkan tanggal**
2. ✅ **Menghitung saldo harian per tanggal**
3. ✅ **Update saldo akhir secara akurat**
4. ✅ **Menampilkan di laporan PDF sesuai urutan tanggal**

---

## 📊 VERIFIKASI DATA

Setelah input selesai, lakukan verifikasi:

### 1. **Cek Tampilan Data**
   - Buka halaman `/dana-operasional`
   - Filter berdasarkan bulan/periode
   - Pastikan semua transaksi muncul dengan tanggal yang benar

### 2. **Download PDF Laporan**
   - Klik tombol **"Download PDF"**
   - Pilih filter **"Tahun Ini"** untuk melihat semua data 2025
   - Atau pilih range tanggal: **1 Januari 2025 - Hari Ini**
   - Cek di PDF:
     - ✅ Semua transaksi tampil lengkap
     - ✅ Urutan berdasarkan tanggal
     - ✅ Total Credit (CR) dan Debit (DB) benar
     - ✅ Saldo akhir sesuai perhitungan

### 3. **Validasi Perhitungan**
   ```
   Rumus Validasi:
   Saldo Akhir = Saldo Awal + Total Pemasukan - Total Pengeluaran
   ```

---

## ⚠️ TIPS & PERHATIAN

### ✅ **DO's (Yang Harus Dilakukan)**
- Input data secara **kronologis** (dari tanggal paling lama ke terbaru)
- Pastikan **kategori** sesuai dengan jenis transaksi
- Gunakan **uraian yang jelas** untuk audit trail
- Verifikasi **nominal** sebelum simpan
- Backup database sebelum input bulk data

### ❌ **DON'Ts (Yang Harus Dihindari)**
- ❌ Jangan input tanggal masa depan (sistem sudah dibatasi max=hari ini)
- ❌ Jangan lupa isi kategori dan uraian lengkap
- ❌ Jangan input nominal negatif
- ❌ Jangan mengubah tanggal transaksi yang sudah fix/final tanpa alasan

---

## 🎯 WORKFLOW REKOMENDASI

Untuk input 1 tahun data (Januari - November 2025):

```
STEP 1: Persiapan Data
├─ Kumpulkan semua bukti transaksi (nota, transfer, dll)
├─ Kelompokkan per bulan
└─ Siapkan Excel dengan format template

STEP 2: Input per Bulan
├─ Mulai dari Januari 2025
├─ Input semua transaksi bulan Januari
├─ Verifikasi total di akhir bulan
├─ Lanjut ke Februari, dst
└─ Sampai November 2025

STEP 3: Verifikasi Akhir
├─ Download PDF "Tahun Ini"
├─ Cek seluruh transaksi
├─ Pastikan saldo akhir = saldo real di bank/kas
└─ DONE! ✅
```

---

## 📱 CONTOH KASUS NYATA

**Situasi:** Anda punya data Excel transaksi Januari-November 2025

**Solusi:**

1. **Login ke sistem** → `/dana-operasional`
2. **Klik tombol "Tambah Transaksi"**
3. **Input transaksi pertama:**
   - Tanggal: `2025-01-05`
   - Tipe: `Pemasukan`
   - Kategori: `Operasional`
   - Uraian: `Saldo awal kas BUMI SULTAN`
   - Nominal: `Rp 50.000.000`
4. **Lanjutkan input satu per satu** sesuai urutan tanggal
5. **Atau buat script import** jika data >100 transaksi

---

## 🚀 FITUR TAMBAHAN YANG SUDAH DIBUAT

✅ **Field tanggal tidak lagi readonly** → Bisa pilih tanggal lampau  
✅ **Max date = hari ini** → Tidak bisa input tanggal masa depan  
✅ **Validasi controller sudah support backdate** → Tidak ada error "date must be before today"  
✅ **PDF Export support semua range tanggal** → Filter "Tahun Ini" menampilkan Jan-Dec 2025  

---

## 📞 BANTUAN

Jika ada kendala:
1. Cek error message di form
2. Pastikan format tanggal `YYYY-MM-DD`
3. Pastikan nominal tidak negatif
4. Pastikan kategori dan uraian terisi

---

**🎉 SELAMAT! Sistem sekarang sudah siap untuk input data historis!**

---

## 📌 CHANGELOG

| Tanggal | Perubahan |
|---------|-----------|
| 12 Nov 2025 | ✅ Field tanggal transaksi di form tambah dibuat editable (remove readonly) |
| 12 Nov 2025 | ✅ Tambahkan atribut max="today" untuk prevent tanggal masa depan |
| 12 Nov 2025 | ✅ Tambahkan helper text "Bisa pilih tanggal lampau untuk input data historis" |
| 12 Nov 2025 | ✅ Update JavaScript untuk preserve tanggal pilihan user (tidak force reset) |
| 12 Nov 2025 | ✅ Validasi controller sudah mendukung `date` tanpa batasan `before_or_equal:today` |

---

**Dokumentasi dibuat:** 12 November 2025  
**Status:** ✅ READY TO USE
