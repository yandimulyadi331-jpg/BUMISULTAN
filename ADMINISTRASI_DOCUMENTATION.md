# DOKUMENTASI SISTEM MANAJEMEN ADMINISTRASI

## 📋 DESKRIPSI SISTEM

Sistem Manajemen Administrasi adalah modul yang dibangun untuk mengelola seluruh administrasi perusahaan seperti:
- Surat Masuk & Keluar
- Undangan Masuk & Keluar  
- Proposal Masuk & Keluar
- Paket Masuk & Keluar
- Memo Internal
- SK Internal
- Surat Tugas
- Surat Keputusan
- Nota Dinas
- Berita Acara
- Kontrak & MoU
- Dokumen Lainnya

### 🎯 FITUR UTAMA

1. **CRUD Administrasi Lengkap**
   - Tambah, Edit, Hapus, Detail administrasi
   - Upload file dokumen (PDF, Word, Excel)
   - Upload foto dokumen
   - Auto-generate kode administrasi (ADM-00001)
   - Filter berdasarkan jenis, status, prioritas
   - Pencarian cepat

2. **Sistem Tindak Lanjut Dinamis**
   - Pencairan Dana (untuk proposal)
   - Disposisi (untuk surat masuk)
   - Konfirmasi Terima/Kirim (untuk paket)
   - Rapat Pembahasan (untuk undangan)
   - Penandatanganan & Penerbitan SK
   - Verifikasi & Approval
   - Revisi & Pengarsipan

3. **Tracking & History**
   - History semua tindak lanjut
   - Timeline aktivitas
   - Status real-time

4. **Export & Download**
   - Download file dokumen asli
   - Export PDF detail administrasi
   - Laporan lengkap dengan history tindak lanjut

## 📁 STRUKTUR FILE

### Database Migrations
```
database/migrations/
├── 2025_11_07_000001_create_administrasi_table.php
└── 2025_11_07_000002_create_tindak_lanjut_administrasi_table.php
```

### Models
```
app/Models/
├── Administrasi.php
└── TindakLanjutAdministrasi.php
```

### Controller
```
app/Http/Controllers/
└── AdministrasiController.php
```

### Views
```
resources/views/administrasi/
├── index.blade.php                    # Halaman utama daftar administrasi
├── create.blade.php                   # Form tambah administrasi
├── edit.blade.php                     # Form edit administrasi
├── show.blade.php                     # Detail administrasi
├── pdf.blade.php                      # Template export PDF
└── tindak-lanjut/
    └── create.blade.php               # Form tindak lanjut
```

### Routes
File: `routes/web.php`

## 🔧 INSTALASI & KONFIGURASI

### 1. Pastikan Database Sudah Ready
```bash
# Periksa koneksi database di .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### 2. Jalankan Migration
```bash
php artisan migrate
```

### 3. Buat Storage Link (jika belum)
```bash
php artisan storage:link
```

### 4. Set Permission Folder Storage
Pastikan folder `storage/app/public` dapat ditulis

## 📱 CARA PENGGUNAAN

### A. MENAMBAH ADMINISTRASI BARU

1. **Akses Menu**
   - Sidebar > Fasilitas & Asset > Manajemen Administrasi
   - Klik tombol "Tambah Administrasi"

2. **Isi Form**
   - **Jenis Administrasi*** (required): Pilih jenis dokumen
   - **Nomor Surat/Dokumen**: Opsional, nomor referensi
   - **Perihal*** (required): Judul/perihal dokumen
   - **Pengirim** (muncul jika jenis masuk): Nama pengirim
   - **Penerima** (muncul jika jenis keluar): Nama penerima
   - **Tanggal Surat**: Tanggal pada dokumen
   - **Tanggal Terima/Kirim**: Tanggal diterima/dikirim
   - **Prioritas*** (required): Rendah, Normal, Tinggi, URGENT
   - **Status*** (required): Pending, Proses, Selesai, Ditolak, Expired
   - **Ringkasan**: Ringkasan singkat isi dokumen
   - **Disposisi Ke**: Nama/bagian yang didisposisikan
   - **Cabang**: Cabang terkait (opsional)
   - **Upload Dokumen**: File PDF/Word/Excel (max 10MB)
   - **Upload Foto**: Foto dokumen (max 2MB)
   - **Catatan & Keterangan**: Informasi tambahan

3. **Simpan Data**
   - Klik "Simpan Data"
   - Sistem akan generate kode otomatis (ADM-00001)

### B. MENAMBAH TINDAK LANJUT

1. **Akses Tindak Lanjut**
   - Dari halaman index: Klik ikon "Tindak Lanjut" (panah hijau)
   - Dari halaman detail: Klik tombol "Tindak Lanjut"

2. **Pilih Jenis Tindak Lanjut**
   Sistem akan menampilkan form dinamis sesuai jenis:

   **A. Pencairan Dana** (untuk Proposal)
   - Nominal Pencairan*
   - Metode Pencairan (Transfer/Tunai/Cek)
   - Nama Penerima Dana
   - Nomor Rekening
   - Tanggal Pencairan
   - Upload Bukti Pencairan
   - Upload Tanda Tangan

   **B. Disposisi** (untuk Surat Masuk)
   - Disposisi Dari
   - Disposisi Kepada*
   - Instruksi Disposisi
   - Deadline Disposisi

   **C. Konfirmasi Terima/Kirim** (untuk Paket)
   - Nama Penerima Paket
   - Waktu Terima/Kirim
   - Kondisi Paket (Baik/Rusak/Tidak Lengkap)
   - Nomor Resi
   - Upload Foto Paket

   **D. Rapat Pembahasan** (untuk Undangan)
   - Waktu Rapat
   - Tempat Rapat
   - Hasil Rapat
   - Upload Notulen

   **E. Penandatanganan/Penerbitan SK**
   - Nama Penandatangan
   - Jabatan
   - Tanggal Tandatangan
   - Upload Dokumen TTD

   **F. Verifikasi/Approval**
   - Verifikator
   - Tanggal Verifikasi
   - Hasil Verifikasi (Disetujui/Ditolak/Revisi)
   - Catatan Verifikasi

3. **Simpan Tindak Lanjut**
   - Klik "Simpan Tindak Lanjut"
   - Sistem akan generate kode TLJ-00001
   - Status administrasi akan update otomatis

### C. MELIHAT DETAIL & HISTORY

1. **Akses Detail**
   - Klik ikon "Detail" (mata) pada tabel

2. **Informasi yang Ditampilkan**
   - Info lengkap administrasi
   - Badge prioritas dan status
   - File dokumen (jika ada)
   - Foto dokumen (jika ada)
   - Timeline history tindak lanjut
   - Quick Actions sidebar

### D. EXPORT & DOWNLOAD

1. **Download Dokumen Asli**
   - Klik tombol "Download Dokumen"
   - File asli akan terdownload

2. **Export PDF**
   - Klik tombol "Export PDF"
   - PDF lengkap dengan history tindak lanjut

## 🗃️ STRUKTUR DATABASE

### Tabel: administrasi
```sql
- id (primary key)
- kode_administrasi (unique, auto: ADM-00001)
- jenis_administrasi (enum: 17 jenis)
- nomor_surat
- pengirim
- penerima
- perihal
- ringkasan
- tanggal_surat
- tanggal_terima
- tanggal_kirim
- prioritas (enum: rendah, normal, tinggi, urgent)
- status (enum: pending, proses, selesai, ditolak, expired)
- file_dokumen (path ke storage)
- foto (path ke storage)
- lampiran (json array)
- divisi_id
- cabang_id
- disposisi_ke
- catatan
- keterangan
- created_by, updated_by
- timestamps, soft_deletes
```

### Tabel: tindak_lanjut_administrasi
```sql
- id (primary key)
- administrasi_id (foreign key)
- kode_tindak_lanjut (unique, auto: TLJ-00001)
- jenis_tindak_lanjut (enum: 12 jenis)
- judul_tindak_lanjut
- deskripsi_tindak_lanjut
- status_tindak_lanjut (pending, proses, selesai, ditolak)

# Fields untuk Pencairan Dana
- nominal_pencairan
- metode_pencairan
- nomor_rekening
- nama_penerima_dana
- tanggal_pencairan
- bukti_pencairan
- tandatangan_pencairan

# Fields untuk Disposisi
- disposisi_dari
- disposisi_kepada
- instruksi_disposisi
- deadline_disposisi

# Fields untuk Paket
- nama_penerima_paket
- foto_paket
- waktu_terima_paket
- kondisi_paket
- resi_pengiriman

# Fields untuk Rapat
- waktu_rapat
- tempat_rapat
- peserta_rapat (json)
- hasil_rapat
- notulen_rapat

# Fields untuk Penandatanganan
- nama_penandatangan
- jabatan_penandatangan
- tanggal_tandatangan
- file_dokumen_ttd

# Fields untuk Verifikasi
- verifikator
- tanggal_verifikasi
- hasil_verifikasi
- catatan_verifikasi

# Generic
- catatan
- lampiran (json)
- pic_id
- created_by, updated_by
- timestamps, soft_deletes
```

## 🎨 FITUR UI/UX

1. **Dashboard Statistics**
   - Total Administrasi
   - Jumlah Pending
   - Jumlah Proses
   - Jumlah Selesai

2. **Dynamic Forms**
   - Form berubah otomatis sesuai jenis administrasi
   - Field pengirim muncul untuk dokumen masuk
   - Field penerima muncul untuk dokumen keluar
   - Preview image saat upload foto

3. **Color Coding**
   - Setiap jenis administrasi punya warna berbeda
   - Badge prioritas dengan animasi (URGENT berkedip)
   - Status dengan icon dan warna jelas

4. **Responsive Design**
   - Mobile friendly
   - Sticky sidebar di detail page
   - Scrollable history panel

## 🔐 KEAMANAN

1. **File Upload Validation**
   - Dokumen: PDF, DOC, DOCX, XLS, XLSX (max 10MB)
   - Foto: JPG, PNG (max 2MB)
   - Server-side validation

2. **Soft Delete**
   - Data tidak benar-benar terhapus
   - Dapat di-restore jika diperlukan

3. **User Tracking**
   - created_by & updated_by untuk audit trail
   - Timestamp otomatis

## 📊 LAPORAN & STATISTIK

### Filter yang Tersedia:
- Jenis Administrasi (17 pilihan)
- Status (5 pilihan)
- Prioritas (4 pilihan)
- Cabang
- Tanggal (range)
- Pencarian (kode, nomor, perihal, pengirim, penerima)

### Export:
- PDF per administrasi (lengkap dengan history)
- Download dokumen asli

## 🚀 TIPS PENGGUNAAN

1. **Gunakan Prioritas dengan Bijak**
   - URGENT: Untuk dokumen yang benar-benar mendesak
   - Tinggi: Perlu tindak lanjut segera
   - Normal: Dokumen standar
   - Rendah: Bisa ditunda

2. **Manfaatkan Disposisi**
   - Isi field "Disposisi Ke" untuk tracking
   - Gunakan tindak lanjut "Disposisi" untuk instruksi detail

3. **Upload File dengan Benar**
   - Dokumen penting: Upload file asli (PDF)
   - Dokumen fisik: Foto dengan kamera yang baik
   - Beri nama file yang jelas

4. **Update Status Berkala**
   - Pending → Proses (saat mulai ditangani)
   - Proses → Selesai (saat selesai ditindaklanjuti)
   - Update otomatis saat tindak lanjut selesai

5. **Gunakan Tindak Lanjut**
   - Setiap aksi penting dicatat di tindak lanjut
   - Upload bukti untuk dokumentasi

## 🛠️ TROUBLESHOOTING

### Problem: File tidak bisa diupload
**Solusi:**
```bash
# Periksa permission folder
chmod -R 775 storage/app/public

# Buat storage link ulang
php artisan storage:link

# Periksa php.ini
upload_max_filesize = 10M
post_max_size = 10M
```

### Problem: Kode administrasi tidak generate
**Solusi:**
- Periksa Model Administrasi method `generateKodeAdministrasi()`
- Pastikan tidak ada constraint di database

### Problem: Tindak lanjut tidak muncul
**Solusi:**
- Periksa relasi di Model
- Pastikan foreign key `administrasi_id` terisi

## 📞 SUPPORT

Untuk bantuan lebih lanjut:
- Check file README.md
- Lihat kode di Controller dan Model
- Review migration files untuk struktur database

## 🎉 SELESAI!

Sistem Manajemen Administrasi siap digunakan. Semua fitur telah lengkap:
✅ CRUD Administrasi
✅ Tindak Lanjut Dinamis
✅ Upload File & Foto
✅ History & Timeline
✅ Export PDF
✅ Filter & Search
✅ Responsive UI
✅ Auto-generate Kode
✅ Soft Delete
✅ User Tracking

Selamat menggunakan! 🚀
