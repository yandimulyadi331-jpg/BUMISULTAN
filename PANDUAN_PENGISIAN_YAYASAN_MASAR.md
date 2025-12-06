# 📋 PANDUAN PENGISIAN FORM YAYASAN MASAR

## 🎯 Tujuan Modul
Modul Yayasan Masar adalah untuk mengelola data anggota atau peserta dari organisasi Yayasan. Sistem ini dirancang identik dengan modul Karyawan, sehingga semua fitur dan cara penggunaannya sama persis.

---

## 📝 PENJELASAN SETIAP FIELD

### **1. IDENTITAS DASAR**

#### **Kode Yayasan Masar** 🤖 (AUTO-GENERATED)
- **Tipe Data:** Teks (dihasilkan otomatis oleh sistem)
- **Format:** YYMM + 5 digit nomor urut
- **Contoh:** 2512 + 00001 = **251200001** (Desember 2025, entry ke-1)
- **Keterangan:** 
  - **TIDAK perlu diisi manual**
  - Sistem otomatis generate berdasarkan bulan dan tahun saat pembuatan
  - Setiap anggota mendapat kode unik otomatis
  - Tidak bisa diubah setelah dibuat (untuk menjaga integritas data)
- **Cara Kerja:**
  - YYMM = Tahun Bulan (contoh: 2512 = Tahun 25, Bulan 12 = Desember 2025)
  - 00001, 00002, 00003 = Nomor urut otomatis setiap bulan
  - Jika bulan berganti, penghitungan nomor urut di-reset

---

### **2. IDENTITAS DASAR (MANUAL)**

#### **No. Identitas** ⭐ (WAJIB ISI)
- **Tipe Data:** Angka (maksimal 16 digit)
- **Contoh:** 1234567890123456 (KTP), 1234567890 (SIM), dll
- **Keterangan:**
  - Bisa KTP, SIM, Paspor, atau dokumen identitas lainnya
  - Digunakan untuk verifikasi data
  - Bisa diubah jika ada kesalahan input

#### **Nama Yayasan Masar** ⭐ (WAJIB ISI)
- **Tipe Data:** Teks (maksimal 100 karakter)
- **Contoh:** Muhammad Amin, Siti Nurhaliza, Budi Santoso
- **Keterangan:**
  - Nama lengkap anggota Yayasan
  - Gunakan nama sesuai dokumen resmi

---

### **2. DATA PRIBADI**

#### **Tempat Lahir** (Opsional)
- **Tipe Data:** Teks (maksimal 20 karakter)
- **Contoh:** Jakarta, Bandung, Surabaya
- **Keterangan:** Nama kota/daerah tempat lahir

#### **Tanggal Lahir** ⭐ (WAJIB ISI)
- **Tipe Data:** Tanggal
- **Format:** DD/MM/YYYY (gunakan date picker yang tersedia)
- **Contoh:** 15/03/1990
- **Cara Input:**
  - Klik pada field untuk membuka date picker
  - Pilih bulan dan tahun di atas
  - Klik tanggal yang diinginkan

#### **Alamat** ⭐ (WAJIB ISI)
- **Tipe Data:** Teks panjang (text area)
- **Contoh:** Jl. Merdeka No. 123, RT 05/RW 02, Kelurahan Cipete, Kecamatan Cilandak, Jakarta Selatan
- **Keterangan:** 
  - Alamat lengkap sesuai KTP
  - Bisa diisi dengan lebih detail

#### **Jenis Kelamin** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown)
- **Opsi:** 
  - Laki-Laki
  - Perempuan
- **Cara Input:** Klik dropdown dan pilih salah satu

#### **No. HP** ⭐ (WAJIB ISI)
- **Tipe Data:** Angka (maksimal 15 digit)
- **Contoh:** 081234567890, 08123456789
- **Keterangan:**
  - Nomor telepon aktif
  - Gunakan format 0812... atau 6281...

#### **Email** (Opsional)
- **Tipe Data:** Email
- **Contoh:** nama@example.com, user@yayasan.org
- **Keterangan:**
  - Email harus valid (format: nama@domain)
  - Bisa untuk notifikasi dan komunikasi

---

### **3. STATUS KELUARGA**

#### **Status Perkawinan** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown)
- **Opsi Umum:**
  - Belum Kawin
  - Kawin
  - Cerai Hidup
  - Cerai Mati
- **Cara Input:** Klik dropdown dan pilih status sesuai data

#### **Pendidikan Terakhir** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown)
- **Opsi:**
  - SD (Sekolah Dasar)
  - SMP (Sekolah Menengah Pertama)
  - SMA (Sekolah Menengah Atas)
  - SMK (Sekolah Menengah Kejuruan)
  - D1, D2, D3, D4 (Diploma)
  - S1, S2, S3 (Sarjana, Magister, Doktor)
- **Cara Input:** Pilih pendidikan tertinggi yang pernah ditempuh

---

### **4. ORGANISASI**

#### **Kantor Cabang** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown - diambil dari database)
- **Contoh:** Cabang Jakarta, Cabang Bandung, Pusat
- **Cara Input:**
  - Klik dropdown untuk melihat daftar cabang
  - Pilih cabang tempat anggota terdaftar
  - Jika cabang tidak ada, hubungi admin untuk menambahkan

#### **Departemen** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown - diambil dari database)
- **Contoh:** ADMINISTRASI, KEUANGAN, OPERASIONAL, PENDIDIKAN
- **Cara Input:**
  - Klik dropdown untuk melihat daftar departemen
  - Pilih departemen sesuai fungsi/tugas anggota

#### **Jabatan** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown - diambil dari database)
- **Contoh:** ADMIN, KETUA, SEKRETARIS, BENDAHARA, ANGGOTA
- **Cara Input:**
  - Klik dropdown untuk melihat daftar jabatan
  - Pilih jabatan sesuai posisi anggota

#### **Tanggal Masuk** ⭐ (WAJIB ISI)
- **Tipe Data:** Tanggal
- **Format:** DD/MM/YYYY
- **Contoh:** 01/01/2024
- **Keterangan:** Tanggal anggota mulai bergabung dengan Yayasan

#### **Status Yayasan Masar** ⭐ (WAJIB ISI)
- **Tipe Data:** Pilihan (Dropdown)
- **Opsi:**
  - K = Kontrak (waktu terbatas)
  - T = Tetap (permanen)
- **Cara Input:** Pilih status kepegawaian/keanggotaan

---

### **5. DOKUMEN**

#### **Foto** (Opsional)
- **Tipe Data:** File gambar
- **Format Terima:** JPG, PNG, GIF, BMP (ukuran max: 2MB)
- **Rekomendasi:**
  - Foto ukuran 3x4 atau 4x6
  - Latar belakang netral (putih atau biru)
  - Wajah jelas dan terlihat
  - File size 50KB - 500KB
- **Cara Upload:**
  - Klik tombol "Pilih File"
  - Cari file foto di komputer Anda
  - Klik "Buka" untuk memilih file
  - Foto akan ditampilkan di preview
- **Keterangan:**
  - Foto akan disimpan untuk keperluan identifikasi
  - Bisa diganti kapan saja saat edit

---

## 🔐 VALIDASI & PERSYARATAN

### **FIELD WAJIB DIISI (REQUIRED):**
1. ✅ No. Identitas
2. ✅ Nama Yayasan Masar
3. ✅ Tempat Lahir
4. ✅ Tanggal Lahir
5. ✅ Alamat
6. ✅ Jenis Kelamin
7. ✅ No. HP
8. ✅ Status Perkawinan
9. ✅ Pendidikan Terakhir
10. ✅ Kantor Cabang
11. ✅ Departemen
12. ✅ Jabatan
13. ✅ Tanggal Masuk
14. ✅ Status Yayasan Masar

### **FIELD YANG AUTO-GENERATED OLEH SISTEM:**
- 🤖 Kode Yayasan Masar (format: YYMM + 5 digit)

### **Field yang OPSIONAL:**
- Tempat Lahir (jika tidak tahu, bisa dikosongkan)
- Email
- Foto

---

## 📋 CONTOH PENGISIAN LENGKAP

| Field | Contoh Isi |
|-------|-----------|
| **Kode Yayasan Masar** | 251200001 (Auto-generated) |
| **No. Identitas** | 3174123456789012 |
| **Nama Yayasan Masar** | Muhammad Rizki Pratama |
| **Tempat Lahir** | Jakarta Pusat |
| **Tanggal Lahir** | 15/06/1998 |
| **Alamat** | Jl. Gatot Subroto No. 45, Jakarta Pusat 12345 |
| **Jenis Kelamin** | Laki-Laki |
| **No. HP** | 081234567890 |
| **Email** | rizki.pratama@email.com |
| **Status Perkawinan** | Belum Kawin |
| **Pendidikan Terakhir** | S1 |
| **Kantor Cabang** | Pusat |
| **Departemen** | ADMINISTRASI |
| **Jabatan** | ADMIN |
| **Tanggal Masuk** | 01/01/2024 |
| **Status Yayasan Masar** | Tetap |
| **Foto** | rizki.jpg (upload) |

---

## 🎬 LANGKAH-LANGKAH PENGISIAN

### **STEP 1: Buka Form Tambah Data**
```
1. Klik tombol "Tambah Data" (warna biru)
2. Form akan terbuka di modal/dialog baru
```

### **STEP 2: Isi Data Identitas Dasar**
```
1. Kode Yayasan Masar akan digenerate OTOMATIS (tidak perlu diisi manual)
2. Isi No. Identitas (nomor KTP/SIM)
3. Isi Nama Yayasan Masar (nama lengkap)
```

### **STEP 3: Isi Data Pribadi**
```
1. Isi Tempat Lahir
2. Klik field Tanggal Lahir → pilih tanggal dari calendar
3. Isi Alamat di text area
4. Pilih Jenis Kelamin dari dropdown
5. Isi No. HP
6. Isi Email (opsional)
```

### **STEP 4: Isi Status & Pendidikan**
```
1. Pilih Status Perkawinan dari dropdown
2. Pilih Pendidikan Terakhir dari dropdown
```

### **STEP 5: Isi Data Organisasi**
```
1. Pilih Kantor Cabang dari dropdown
2. Pilih Departemen dari dropdown
3. Pilih Jabatan dari dropdown
4. Klik field Tanggal Masuk → pilih tanggal dari calendar
5. Pilih Status Yayasan Masar (Kontrak/Tetap)
```

### **STEP 6: Upload Foto (Opsional)**
```
1. Klik tombol "Pilih File" di bagian Foto
2. Cari dan pilih file foto dari komputer
3. Foto akan ditampilkan dalam preview
```

### **STEP 7: Submit**
```
1. Klik tombol "Submit" (warna biru)
2. Tunggu proses penyimpanan (1-2 detik)
3. Jika berhasil, halaman akan kembali ke daftar
4. Data baru akan muncul di tabel
```

---

## ⚠️ PESAN ERROR UMUM & SOLUSI

### **"The [field] field is required"**
- **Penyebab:** Field wajib belum diisi
- **Solusi:** Isi semua field yang bertanda ⭐ (wajib)

### **"The kode_yayasan has already been taken"**
- **Penyebab:** Kode Yayasan yang diinput sudah terdaftar
- **Solusi:** Gunakan kode yang berbeda/unik

### **"The no_identitas field must be numeric"**
- **Penyebab:** No. Identitas berisi karakter huruf atau simbol
- **Solusi:** Isi hanya dengan angka (0-9)

### **"The email field must be a valid email"**
- **Penyebab:** Format email tidak sesuai
- **Solusi:** Gunakan format yang benar (nama@domain.com)

### **File size exceeds maximum allowed size**
- **Penyebab:** Ukuran file foto terlalu besar (>2MB)
- **Solusi:** Kompres atau pilih foto yang ukurannya lebih kecil

---

## 💡 TIPS & TRIK

1. **Gunakan Format Konsisten:** Untuk kode, gunakan format yang sama untuk semua entri (contoh: YM-2024-001, YM-2024-002, dst)

2. **Simpan Foto Terlebih Dahulu:** Sebelum upload, siapkan foto dengan ukuran dan kualitas yang baik

3. **Cek Data Sebelum Submit:** Periksa kembali semua data sebelum menekan tombol Submit

4. **Dropdown Cabang/Departemen/Jabatan:** Jika pilihan tidak ada, beritahu Admin untuk menambahkannya di Master Data

5. **Tanggal:** Gunakan calendar picker (klik field tanggal) untuk menghindari format salah

6. **Edit Data:** Untuk mengubah data, klik tombol Edit (icon pensil) pada baris data yang ingin diubah

7. **Hapus Data:** Untuk menghapus data, klik tombol Hapus (icon tempat sampah) dan konfirmasi

---

## 🔗 FITUR TAMBAHAN

### **Set Jam Kerja** (Icon Jam)
- Atur jam kerja untuk anggota Yayasan
- Bisa berbeda setiap hari

### **Set Cabang** (Icon Peta)
- Assign anggota ke cabang tambahan
- Anggota bisa terdaftar di lebih dari 1 cabang

### **Lihat Detail** (Icon Dokumen)
- Melihat semua data detail anggota
- Melihat foto dengan resolusi penuh

### **ID Card** (Icon Identitas)
- Generate kartu identitas digital
- Bisa di-download atau di-print

---

## 📞 BANTUAN

Jika ada pertanyaan atau masalah:
1. Hubungi **Administrator Sistem**
2. Cek dokumentasi modul Karyawan (fitur yang sama)
3. Lihat **Error Message** yang muncul untuk clue penyebab masalah

---

**Selamat menggunakan modul Yayasan Masar! 🎉**
