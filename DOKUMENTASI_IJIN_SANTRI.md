# DOKUMENTASI SISTEM IJIN SANTRI
## Manajemen Saung Santri - Bumi Sultan Super App

---

## 📋 DESKRIPSI SISTEM

Sistem Ijin Santri adalah fitur untuk mengelola perijinan santri untuk pulang dari pesantren dengan alur verifikasi bertahap yang melibatkan Admin, Ustadz, dan Orang Tua.

---

## 🔄 ALUR PROSES IJIN SANTRI

### **1. SANTRI MINTA IJIN**
- Santri datang ke admin untuk mengajukan ijin pulang
- Admin mencatat data ijin di sistem

### **2. ADMIN MEMBUAT IJIN**
- Admin login ke sistem
- Masuk ke menu: **Manajemen Saung Santri → Ijin Santri**
- Klik **"Buat Ijin Santri"**
- Isi formulir:
  - Pilih Santri
  - Tanggal Ijin
  - Tanggal Rencana Kembali
  - Alasan Ijin
  - Catatan (opsional)
- Klik **"Simpan Ijin"**
- **Status: PENDING (Menunggu TTD Ustadz)**

### **3. DOWNLOAD PDF SURAT IJIN**
- Setelah ijin dibuat, klik tombol **"Download PDF"**
- PDF surat ijin akan terdownload dengan format resmi
- Surat berisi:
  - Kop surat Pondok Pesantren
  - Data santri lengkap
  - Tanggal ijin dan rencana kembali
  - Alasan ijin
  - Kolom TTD: Pengurus (sudah tertanda), Ustadz (kosong), Ortu (kosong)
- Serahkan surat ini ke santri untuk meminta TTD Ustadz

### **4. TTD USTADZ**
- Santri membawa surat ke Ustadz
- Ustadz menandatangani surat (secara fisik)
- Santri kembali ke admin untuk verifikasi

### **5. VERIFIKASI TTD USTADZ**
- Admin membuka detail ijin santri
- Klik tombol **"TTD Ustadz"**
- Konfirmasi verifikasi
- **Status: TTD USTADZ (Siap Pulang)**

### **6. VERIFIKASI KEPULANGAN**
- Setelah TTD Ustadz terverifikasi
- Admin klik tombol **"Pulangkan"**
- Konfirmasi kepulangan
- **Status: DIPULANGKAN (Sedang Pulang)**
- Santri membawa surat untuk di-TTD Orang Tua

### **7. SANTRI DI RUMAH**
- Santri menunjukkan surat ijin ke Orang Tua
- Orang Tua menandatangani surat (secara fisik)
- Santri membawa kembali surat yang sudah ber-TTD Ortu

### **8. SANTRI KEMBALI KE PESANTREN**
- Santri kembali ke pesantren
- Menyerahkan surat ijin yang sudah di-TTD Ortu ke admin

### **9. VERIFIKASI KEMBALI**
- Admin membuka detail ijin santri
- Klik tombol **"Sudah Kembali"**
- Upload foto surat yang sudah di-TTD Ortu
- Isi tanggal kembali aktual
- Klik **"Ya, Verifikasi Kembali"**
- **Status: KEMBALI (Sudah di Pesantren)**

---

## 🗂️ FILE-FILE YANG DIBUAT

### **1. Database Migration**
```
database/migrations/2025_11_08_031129_create_ijin_santri_table.php
```
**Struktur tabel ijin_santri:**
- `id`: Primary key
- `santri_id`: Foreign key ke tabel santri
- `tanggal_ijin`: Tanggal ijin
- `tanggal_kembali_rencana`: Tanggal rencana kembali
- `tanggal_kembali_aktual`: Tanggal kembali aktual (nullable)
- `alasan_ijin`: Alasan ijin (text)
- `nomor_surat`: Nomor surat (unique)
- `status`: enum (pending, ttd_ustadz, dipulangkan, kembali)
- `ttd_ustadz_at`: Timestamp verifikasi TTD Ustadz
- `ttd_ustadz_by`: User yang verifikasi TTD Ustadz
- `verifikasi_pulang_at`: Timestamp verifikasi kepulangan
- `verifikasi_pulang_by`: User yang verifikasi kepulangan
- `verifikasi_kembali_at`: Timestamp verifikasi kembali
- `verifikasi_kembali_by`: User yang verifikasi kembali
- `foto_surat_ttd_ortu`: File foto surat ber-TTD ortu
- `catatan`: Catatan tambahan
- `created_by`: User pembuat ijin

### **2. Model**
```
app/Models/IjinSantri.php
```
**Fitur:**
- Relasi ke Santri, User (creator, verifikasi)
- Status label accessor
- Generate nomor surat otomatis (Format: 001/IJIN-SANTRI/MM/YYYY)

### **3. Controller**
```
app/Http/Controllers/IjinSantriController.php
```
**Method:**
- `index()`: List semua ijin santri
- `create()`: Form tambah ijin
- `store()`: Simpan ijin baru
- `show($id)`: Detail ijin santri
- `downloadPdf($id)`: Download PDF surat ijin
- `verifikasiTtdUstadz($id)`: Verifikasi TTD Ustadz
- `verifikasiKepulangan($id)`: Verifikasi kepulangan
- `verifikasiKembali($id)`: Verifikasi kembali + upload foto
- `destroy($id)`: Hapus ijin

### **4. Routes**
```
routes/web.php
```
**Endpoint:**
- GET `/ijin-santri` - List
- GET `/ijin-santri/create` - Form create
- POST `/ijin-santri` - Store
- GET `/ijin-santri/{id}` - Detail
- GET `/ijin-santri/{id}/download-pdf` - Download PDF
- POST `/ijin-santri/{id}/verifikasi-ttd-ustadz` - Verifikasi TTD
- POST `/ijin-santri/{id}/verifikasi-kepulangan` - Verifikasi pulang
- POST `/ijin-santri/{id}/verifikasi-kembali` - Verifikasi kembali
- DELETE `/ijin-santri/{id}` - Hapus

### **5. Views**
```
resources/views/ijin_santri/
├── index.blade.php     (List data + modal verifikasi)
├── create.blade.php    (Form input ijin)
├── show.blade.php      (Detail ijin + timeline status)
└── pdf.blade.php       (Template PDF surat ijin)
```

### **6. Menu Sidebar**
```
resources/views/layouts/sidebar.blade.php
```
Sub menu baru: **Ijin Santri** di bawah **Manajemen Saung Santri**

---

## 🎯 FITUR-FITUR UTAMA

### **1. Pembuatan Ijin Santri**
- Form input data ijin
- Generate nomor surat otomatis
- Validasi tanggal kembali harus >= tanggal ijin

### **2. Download PDF Surat Ijin**
- Template profesional dengan kop surat
- Data santri lengkap
- Kolom TTD Pengurus, Ustadz, dan Ortu
- Catatan penting untuk santri

### **3. Verifikasi Bertahap**
- **Tahap 1**: TTD Ustadz (Admin verifikasi)
- **Tahap 2**: Kepulangan (Admin verifikasi + status PULANG)
- **Tahap 3**: Kembali (Admin upload foto surat + status KEMBALI)

### **4. Timeline Status**
- Visualisasi proses ijin dalam timeline
- Menampilkan tanggal & waktu setiap tahap
- Icon status yang jelas

### **5. Upload Foto Surat**
- Saat santri kembali, admin upload foto surat ber-TTD Ortu
- Tersimpan sebagai bukti kelengkapan dokumen
- Bisa dilihat di detail ijin

### **6. Riwayat Verifikasi**
- Mencatat siapa yang verifikasi setiap tahap
- Timestamp lengkap
- Audit trail yang jelas

---

## 📊 STATUS IJIN SANTRI

| Status | Keterangan | Aksi yang Tersedia |
|--------|------------|-------------------|
| **PENDING** | Menunggu TTD Ustadz | Download PDF, Verifikasi TTD, Hapus |
| **TTD_USTADZ** | TTD Ustadz - Siap Pulang | Verifikasi Kepulangan |
| **DIPULANGKAN** | Sedang Pulang | Verifikasi Kembali |
| **KEMBALI** | Sudah Kembali | Detail saja (selesai) |

---

## 🔐 HAK AKSES

- Menu ini hanya untuk **Super Admin**
- Semua aksi verifikasi dicatat dengan user & timestamp
- Setiap perubahan status tercatat dalam database

---

## 📁 STORAGE

Folder penyimpanan foto surat:
```
storage/app/public/ijin_santri/
```

Format nama file:
```
ijin_santri_{id}_{timestamp}.{ext}
```

---

## 🚀 CARA MENGGUNAKAN

### **Langkah Setup:**
1. Migration sudah dijalankan ✅
2. Model sudah dibuat ✅
3. Controller sudah dibuat ✅
4. Routes sudah terdaftar ✅
5. Views sudah dibuat ✅
6. Menu sidebar sudah ditambahkan ✅

### **Akses Menu:**
1. Login sebagai Super Admin
2. Klik menu **Manajemen Saung Santri**
3. Klik sub menu **Ijin Santri**
4. Mulai kelola ijin santri

---

## 📝 CONTOH NOMOR SURAT

```
001/IJIN-SANTRI/11/2025
002/IJIN-SANTRI/11/2025
003/IJIN-SANTRI/11/2025
```

Format: `{sequence}/IJIN-SANTRI/{bulan}/{tahun}`
- Sequence auto-increment per bulan

---

## ⚠️ CATATAN PENTING

1. **PDF Surat:**
   - Template bisa dikustomisasi di `resources/views/ijin_santri/pdf.blade.php`
   - Sesuaikan kop surat dengan data pesantren Anda
   - Ubah alamat, telepon, email pesantren

2. **Status Flow:**
   - Status ijin tidak bisa kembali ke status sebelumnya
   - Sekali dipulangkan, harus diverifikasi kembali
   - Tidak bisa edit ijin yang sudah dalam proses

3. **Foto Surat:**
   - Wajib upload saat verifikasi kembali
   - Format: JPG, PNG
   - Maksimal 2MB

4. **Validasi:**
   - Tanggal kembali harus >= tanggal ijin
   - Santri harus terdaftar di sistem
   - Nomor surat unik per ijin

---

## 🎨 CUSTOMIZE

### **Ubah Kop Surat PDF:**
Edit file: `resources/views/ijin_santri/pdf.blade.php`

```php
<h1>PONDOK PESANTREN</h1>
<h2>SAUNG SANTRI</h2>
<p>Jl. Alamat Pondok Pesantren No. 123, Kota, Provinsi</p>
<p>Telp: (021) 12345678 | Email: info@saungsantri.com</p>
```

### **Ubah Warna Badge Status:**
Edit file: `app/Models/IjinSantri.php` method `getStatusLabelAttribute()`

---

## 📞 SUPPORT

Jika ada pertanyaan atau butuh bantuan:
1. Check dokumentasi ini
2. Review kode di controller & model
3. Test di environment development dulu

---

## ✅ CHECKLIST IMPLEMENTASI

- [x] Database migration
- [x] Model & relasi
- [x] Controller dengan semua method
- [x] Routes terdaftar
- [x] Views (index, create, show, pdf)
- [x] Menu sidebar
- [x] Status flow lengkap
- [x] Verifikasi bertahap
- [x] Upload foto surat
- [x] Download PDF
- [x] Timeline status
- [x] Riwayat verifikasi
- [x] Audit trail (created_by, verified_by)

---

## 🎉 SISTEM SIAP DIGUNAKAN!

**Sistem Ijin Santri sudah lengkap dan siap digunakan.**

Alur sudah sesuai dengan requirement:
1. ✅ Admin buat ijin
2. ✅ Download PDF surat
3. ✅ TTD Ustadz (fisik + verifikasi digital)
4. ✅ Verifikasi kepulangan
5. ✅ Status santri: PULANG
6. ✅ TTD Ortu (fisik)
7. ✅ Upload foto surat
8. ✅ Verifikasi kembali
9. ✅ Status santri: KEMBALI

---

**Dibuat:** 8 November 2025  
**Versi:** 1.0  
**Developer:** GitHub Copilot  
**Sistem:** Bumi Sultan Super App - Manajemen Saung Santri
