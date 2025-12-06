# 📄 SISTEM MANAJEMEN DOKUMEN - DOKUMENTASI LENGKAP

## 🎯 Overview

Sistem Manajemen Dokumen adalah sub-menu baru di **Fasilitas & Asset** yang dirancang untuk mengatur dan mengelola dokumen-dokumen perusahaan secara digital dengan integrasi ke penyimpanan fisik (loker).

---

## ✨ Fitur Utama

### 1. **Manajemen Dokumen Digital**
   - Upload dokumen berbagai format: PDF, Word, Excel, Image, ZIP
   - Atau simpan sebagai link eksternal (Google Drive, Dropbox, dll)
   - Preview langsung untuk PDF dan gambar
   - Download dokumen dengan tracking

### 2. **Sistem Kode Dokumen Otomatis**
   - Format: **[KATEGORI]-[NOMOR]-[LOKER]**
   - Contoh: `SK-001-L001`, `PKS-023-L045`
   - Auto-increment berdasarkan kategori
   - Memudahkan pencarian dan identifikasi

### 3. **Integrasi Loker Fisik**
   - Nomor loker untuk dokumen fisik
   - Lokasi loker (Ruang Arsip Lt.2, dll)
   - Nomor rak dan baris
   - Cari dokumen offline dengan mudah menggunakan kode

### 4. **Role-Based Access Control (3 Level)**
   - **Public**: Semua user bisa view & download
   - **View Only**: User bisa view, tidak bisa download
   - **Restricted**: Hanya admin yang bisa akses

### 5. **Kategori Dokumen**
   10 kategori pre-defined:
   - SK (Surat Keputusan)
   - PKS (Perjanjian Kerja Sama)
   - SOP (Standard Operating Procedure)
   - KTK (Kontrak Karyawan)
   - INV (Invoice)
   - LPR (Laporan)
   - SRT (Surat Menyurat)
   - IZN (Perizinan)
   - NDA (Non-Disclosure Agreement)
   - MOU (Memorandum of Understanding)

### 6. **Pencarian & Filter**
   - Search by: kode dokumen, nama, nomor loker, nomor referensi, tags
   - Filter by: kategori, status, access level
   - Quick search nomor loker

### 7. **Metadata Lengkap**
   - Tanggal dokumen
   - Tanggal berlaku & berakhir
   - Nomor referensi/surat
   - Penerbit/yang mengesahkan
   - Tags untuk pencarian
   - Status: Aktif, Arsip, Kadaluarsa

### 8. **Tracking & Logging**
   - Jumlah view & download
   - Log akses (siapa, kapan, aksi apa)
   - IP address & user agent tracking

---

## 🗂️ Struktur Database

### Tabel: `document_categories`
```sql
- id (primary key)
- kode_kategori (SK, PKS, SOP, dll) - unique
- nama_kategori
- deskripsi
- warna (untuk badge UI)
- last_number (auto-increment per kategori)
- is_active
- timestamps
```

### Tabel: `documents`
```sql
- id (primary key)
- kode_dokumen (format: KATEGORI-NOMOR-LOKER) - unique
- nama_dokumen
- document_category_id (foreign key)
- deskripsi

# Lokasi Fisik
- nomor_loker (L001, L002, dll)
- lokasi_loker (Ruang Arsip Lt.2)
- rak
- baris

# File/Link
- jenis_dokumen (file/link)
- jenis_file (pdf, excel, word, dll)
- file_path (path atau URL)
- file_size
- file_extension

# Access Control
- access_level (public/view_only/restricted)

# Metadata
- tanggal_dokumen
- tanggal_berlaku
- tanggal_berakhir
- nomor_referensi
- penerbit
- tags

# Status & Tracking
- status (aktif/arsip/kadaluarsa)
- jumlah_view
- jumlah_download
- uploaded_by (NIK)
- updated_by (NIK)
- timestamps
- soft deletes
```

### Tabel: `document_access_logs`
```sql
- id (primary key)
- document_id (foreign key)
- nik
- nama_user
- action (view/download/preview)
- ip_address
- user_agent
- timestamps
```

---

## 🚀 Cara Penggunaan

### 1. **Akses Menu**
   - Login sebagai admin
   - Buka menu **Fasilitas & Asset**
   - Klik **Manajemen Dokumen**

### 2. **Tambah Dokumen Baru**
   ```
   1. Klik tombol "Tambah Dokumen"
   2. Isi informasi dokumen:
      - Nama dokumen
      - Pilih kategori
      - Status (aktif/arsip/kadaluarsa)
      - Deskripsi
   
   3. Upload file atau masukkan link:
      - Pilih "Upload File" untuk file lokal
      - Pilih "Link Eksternal" untuk Google Drive, dll
   
   4. Isi lokasi penyimpanan fisik (opsional):
      - Nomor loker (L001, L002)
      - Lokasi loker
      - Nomor rak & baris
   
   5. Pilih level akses:
      - Public (semua bisa download)
      - View Only (hanya lihat)
      - Restricted (admin only)
   
   6. Isi metadata tambahan:
      - Tanggal dokumen
      - Tanggal berlaku & berakhir
      - Nomor referensi
      - Penerbit
      - Tags
   
   7. Klik "Simpan Dokumen"
   8. Sistem akan generate kode otomatis
   ```

### 3. **Melihat/Preview Dokumen**
   - Klik tombol **mata (👁️)** di kolom aksi
   - Modal akan muncul dengan preview (untuk PDF/gambar)
   - Detail lengkap dokumen
   - Tombol download (jika memiliki akses)

### 4. **Download Dokumen**
   - Klik tombol **download (📥)** di kolom aksi
   - Atau klik download di halaman detail
   - Sistem akan log activity dan increment counter

### 5. **Edit Dokumen**
   - Klik tombol **edit (✏️)** (admin only)
   - Update informasi yang diperlukan
   - Upload file baru atau ubah link
   - Update lokasi fisik
   - Ubah access level
   - Simpan perubahan

### 6. **Hapus Dokumen**
   - Klik tombol **hapus (🗑️)** (admin only)
   - Konfirmasi penghapusan
   - File akan dihapus dari storage (soft delete)

### 7. **Pencarian Dokumen**
   
   **Pencarian Online (di sistem):**
   ```
   1. Gunakan search box: cari by kode, nama, loker, referensi
   2. Filter by kategori, status, access level
   3. Quick search nomor loker
   4. Kombinasi filter untuk hasil spesifik
   ```

   **Pencarian Offline (loker fisik):**
   ```
   1. Lihat kode dokumen di sistem: SK-001-L001
   2. L001 = Nomor loker fisik
   3. Cari loker dengan nomor tersebut
   4. Lihat detail lokasi (rak, baris) di sistem
   5. Temukan dokumen fisik dengan mudah
   ```

---

## 🔐 Access Control Matrix

| Role | View Public | Download Public | View View-Only | Download View-Only | View Restricted | Download Restricted | Create/Edit/Delete |
|------|------------|-----------------|----------------|-------------------|-----------------|--------------------|--------------------|
| **Super Admin** | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| **User Biasa** | ✅ | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |

---

## 📋 Contoh Use Case

### Case 1: Simpan Surat Keputusan
```
Nama: Surat Keputusan Pengangkatan Direktur 2024
Kategori: SK
Nomor Loker: L001
Lokasi: Ruang Arsip Lt.2, Rak R1, Baris B1
File: Upload PDF
Access: Restricted
Nomor Referensi: 001/SK/DIR/2024
Penerbit: Dewan Komisaris

Kode Generated: SK-001-L001
```

### Case 2: Link Eksternal MOU
```
Nama: MOU Kerja Sama dengan PT XYZ
Kategori: MOU
Link: https://drive.google.com/file/xxx
Access: View Only
Tanggal Berlaku: 01/01/2024
Tanggal Berakhir: 31/12/2024
Tags: mou, kerjasama, xyz

Kode Generated: MOU-001-L000
```

### Case 3: Cari Dokumen Fisik
```
Scenario: Butuh dokumen fisik PKS dengan PT ABC

1. Buka sistem, cari "PKS PT ABC"
2. Ditemukan: PKS-023-L045
3. Detail lokasi: Ruang Arsip Lt.3, Rak R5, Baris B3
4. Pergi ke lokasi tersebut
5. Temukan loker L045
6. Ambil dari Rak R5, Baris B3
```

---

## 🛠️ File Struktur

```
app/
├── Http/Controllers/
│   └── DokumenController.php          # Main controller
├── Models/
│   ├── Document.php                   # Model dokumen
│   ├── DocumentCategory.php           # Model kategori
│   └── DocumentAccessLog.php          # Model log akses

database/
├── migrations/
│   ├── 2024_11_07_000001_create_document_categories_table.php
│   ├── 2024_11_07_000002_create_documents_table.php
│   └── 2024_11_07_000003_create_document_access_logs_table.php
└── seeders/
    └── DocumentCategorySeeder.php     # Seeder 10 kategori

resources/views/dokumen/
├── index.blade.php                    # List dokumen
├── create.blade.php                   # Form tambah
├── edit.blade.php                     # Form edit
└── show.blade.php                     # Detail dokumen

routes/
└── web.php                            # Routes dokumen

storage/app/public/
└── documents/                         # Folder upload dokumen
```

---

## 🎨 Fitur UI/UX

1. **Preview Modal**: Preview dokumen tanpa perlu download
2. **Color-Coded Categories**: Setiap kategori punya warna berbeda
3. **Icon by File Type**: Icon berbeda untuk PDF, Word, Excel, dll
4. **Badge System**: Status dan access level dengan badge berwarna
5. **Statistics**: View count & download count
6. **Responsive Design**: Mobile-friendly
7. **Quick Actions**: Tombol aksi langsung di tabel
8. **Advanced Search**: Multiple filter kombinasi

---

## 🔄 Update & Migration

### Jika sudah jalan, untuk update:
```bash
# Backup database dulu
php artisan migrate

# Jalankan seeder kategori (jika belum)
php artisan db:seed --class=DocumentCategorySeeder

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## 📊 Reporting & Analytics (Future Enhancement)

Potensi fitur tambahan:
- Export list dokumen ke Excel/PDF
- Dashboard analytics: dokumen terbanyak diakses
- Notifikasi dokumen akan expired
- Approval workflow untuk dokumen restricted
- Version control dokumen
- QR Code untuk dokumen fisik
- Integration dengan e-signature

---

## 🐛 Troubleshooting

### Problem: File tidak bisa diupload
**Solution**: 
```php
// Check di php.ini:
upload_max_filesize = 10M
post_max_size = 10M

// Atau di .env:
MAX_FILE_SIZE=10240
```

### Problem: Preview PDF tidak muncul
**Solution**:
```bash
# Pastikan symbolic link storage sudah dibuat
php artisan storage:link
```

### Problem: Access denied
**Solution**: 
- Check role user (harus super admin untuk aksi tertentu)
- Check access_level dokumen

---

## 📞 Support

Untuk bantuan lebih lanjut:
- Email: support@company.com
- Dokumentasi lengkap: [Internal Wiki]
- Admin System: [Contact IT]

---

## 🎉 Selesai!

Sistem Manajemen Dokumen siap digunakan! 

**Happy Managing Documents! 📄✨**

---

*Dokumentasi ini dibuat pada: 7 November 2024*
*Version: 1.0.0*
