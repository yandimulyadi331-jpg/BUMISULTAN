# 📊 DEMO IMPLEMENTASI GAJI MINGGUAN (SABTU-KAMIS)

## 🎯 Simulasi Hari Kamis - Waktu Gajian

**Tanggal Simulasi:** Kamis, 13 November 2025

## 📅 Periode Minggu Ini
```
Sabtu  : 8 November 2025
Minggu : 9 November 2025  
Senin  : 10 November 2025
Selasa : 11 November 2025
Rabu   : 12 November 2025
Kamis  : 13 November 2025 ✅ HARI GAJIAN
```

## 👷 Data Tukang yang Di-Generate

### 1️⃣ JAENUDIN (TK001) - DENGAN PINJAMAN
- **Tarif Harian:** Rp 150.000/hari
- **Kehadiran:** 6/6 hari (Sabtu-Kamis) ✅
- **Total Upah:** Rp 900.000
- **Total Lembur:** Rp 0
- **Pinjaman Aktif:** Rp 500.000
- **Cicilan/Minggu:** Rp 100.000
- **Toggle Auto Potong:** ON ✅
- **GAJI BERSIH:** Rp 800.000 (900rb - 100rb)

### 2️⃣ RATNA (TK002) - TANPA PINJAMAN
- **Tarif Harian:** Rp 120.000/hari
- **Kehadiran:** 6/6 hari (Sabtu-Kamis) ✅
- **Total Upah:** Rp 720.000
- **Total Lembur:** Rp 0
- **Pinjaman Aktif:** Tidak ada
- **Cicilan/Minggu:** -
- **Toggle Auto Potong:** -
- **GAJI BERSIH:** Rp 720.000

### 3️⃣ SARI (TK003) - ADA LEMBUR
- **Tarif Harian:** Rp 150.000/hari
- **Kehadiran:** 5/6 hari (1 hari tidak hadir Sabtu) ❌
- **Total Upah:** Rp 750.000 (5 hari)
- **Total Lembur:** 
  - Minggu: Rp 123.155
  - Senin: Rp 140.138
  - **Total:** Rp 263.293
- **Pinjaman Aktif:** Tidak ada
- **Cicilan/Minggu:** -
- **Toggle Auto Potong:** -
- **GAJI BERSIH:** Rp 1.013.293 (750rb + 263rb)

## 💡 Cara Kerja Sistem

### Perhitungan Otomatis
1. **Periode Dinamis:** Sistem otomatis menghitung periode Sabtu-Kamis terakhir
2. **Kehadiran:** Data diambil dari `kehadiran_tukangs` (6 hari kerja)
3. **Upah Harian:** Tarif × Jumlah hari hadir
4. **Lembur:** Semua jenis lembur dijumlahkan (full/setengah/cash)
5. **Cicilan:** Hanya dipotong jika toggle AUTO POTONG = ON ✅
6. **Gaji Bersih:** Upah + Lembur - Potongan - Cicilan (jika toggle ON)

### Toggle Auto Potong
- **ON (✅):** Cicilan **DIPOTONG** otomatis dari gaji mingguan
- **OFF (❌):** Cicilan **TIDAK DIPOTONG**, tukang dapat gaji penuh

## 🔄 Aksi Yang Tersedia

### Dashboard Keuangan
- ✅ Lihat ringkasan gaji minggu ini
- ✅ Toggle ON/OFF auto potong per tukang
- ✅ Lihat detail pinjaman aktif
- ✅ Export laporan

### Button Aksi
- **Gaji Kamis (TTD):** Pembagian gaji + tanda tangan digital
- **Lembur Cash:** Input lembur tunai manual
- **Pinjaman:** Kelola pinjaman tukang
- **Laporan:** Download PDF laporan lengkap

## 📊 Tampilan Dashboard

### Kolom-Kolom Tabel
| Kolom | Keterangan |
|-------|-----------|
| **No** | Nomor urut |
| **Kode** | Kode tukang (TK001, TK002, dst) |
| **Nama Tukang** | Nama + keahlian |
| **Upah** | Total upah harian minggu ini |
| **Lembur** | Total lembur minggu ini |
| **Potongan** | Potongan lain (jika ada) |
| **Cicilan** | Cicilan pinjaman yang dipotong (jika toggle ON) |
| **Gaji Bersih** | Total yang diterima tukang |
| **Potong Auto** | Toggle switch ON/OFF |
| **Aksi** | Button detail/aksi |

## 🎯 Contoh Kasus Nyata

### Kasus 1: Tukang FULL Hadir + Ada Pinjaman
```
Nama: JAENUDIN (TK001)
Kehadiran: 6 hari (Sabtu-Kamis)
Upah: 6 × Rp 150.000 = Rp 900.000
Lembur: Rp 0
Pinjaman: Rp 500.000 (cicilan Rp 100.000/minggu)
Toggle: ON ✅

PERHITUNGAN:
Rp 900.000 (upah) + Rp 0 (lembur) - Rp 100.000 (cicilan)
= Rp 800.000 ✅
```

### Kasus 2: Tukang Ada Lembur + Tidak Pinjaman
```
Nama: SARI (TK003)
Kehadiran: 5 hari (1 hari tidak hadir)
Upah: 5 × Rp 150.000 = Rp 750.000
Lembur: Rp 263.293
Pinjaman: Tidak ada
Toggle: -

PERHITUNGAN:
Rp 750.000 (upah) + Rp 263.293 (lembur)
= Rp 1.013.293 ✅
```

### Kasus 3: Toggle OFF - Tukang Tidak Dipotong
```
Jika JAENUDIN toggle di-OFF:
Rp 900.000 (upah) + Rp 0 (lembur) - Rp 0 (cicilan OFF)
= Rp 900.000 (dapat gaji penuh tanpa potongan)
```

## 🚀 Akses Dashboard

Buka browser dan akses:
```
http://localhost:8000/keuangan-tukang
```

Atau jika menggunakan `php artisan serve`:
```
php artisan serve
```

## 📝 Catatan Penting

1. **Tanggal Simulasi:** Controller menggunakan tanggal 13 Nov 2025 untuk demo
2. **Production:** Ganti `Carbon::parse('2025-11-13')` menjadi `Carbon::now()` di controller
3. **Data Real-time:** Dashboard selalu menampilkan minggu terakhir (Sabtu-Kamis)
4. **Auto Update:** Toggle switch akan refresh halaman otomatis setelah perubahan
5. **Periode Otomatis:** Sistem menghitung periode sendiri, tidak perlu input manual

## ✅ Fitur yang Sudah Berfungsi

- ✅ Perhitungan minggu otomatis (Sabtu-Kamis)
- ✅ Toggle auto potong pinjaman
- ✅ Cicilan dipotong 1× per minggu (bukan ×4)
- ✅ Tampilan periode minggu di header
- ✅ Kolom cicilan menampilkan jumlah yang dipotong
- ✅ Gaji bersih akurat sesuai toggle
- ✅ Auto-reload setelah toggle berubah
- ✅ SweetAlert2 notification modern

## 🎉 Hasil Akhir

Dashboard sekarang menampilkan **GAJI MINGGUAN** dengan perhitungan:
- **Periode:** Sabtu s/d Kamis (6 hari kerja)
- **Cicilan:** Dipotong 1× per minggu (jika toggle ON)
- **Contoh:** 150rb × 6 hari = 900rb, potong 100rb = **800rb bersih**

**Sistem siap digunakan untuk gajian Kamis!** 🎊
