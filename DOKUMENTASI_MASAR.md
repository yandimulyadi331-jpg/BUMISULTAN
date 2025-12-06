# DOKUMENTASI MASAR

## Deskripsi
MASAR adalah fitur manajemen pengajian untuk jamaah bapak-bapak di Yayasan. Fitur ini memiliki struktur yang identik dengan Majlis Ta'lim Al-Ikhlas namun dengan konteks yang berbeda.

**Perbedaan:**
- Majlis Ta'lim Al-Ikhlas: Pengajian Jamaah Ibu-Ibu
- MASAR: Pengajian Jamaah Bapak-Bapak

## Struktur Database

### 1. Tabel jamaah_masar
Menyimpan data jamaah bapak-bapak
- **Kolom:**
  - `id` - Primary key
  - `nomor_jamaah` - Format: MA-URUT-NIK2DIGIT-ID-TAHUN2DIGIT
  - `nama_jamaah` - Nama lengkap jamaah
  - `nik` - NIK (unique)
  - `alamat` - Alamat lengkap
  - `tanggal_lahir` - Tanggal lahir
  - `tahun_masuk` - Tahun masuk sebagai jamaah
  - `no_telepon` - Nomor telepon
  - `email` - Email
  - `jenis_kelamin` - L/P (default: L)
  - `pin_fingerprint` - PIN untuk mesin fingerprint
  - `jumlah_kehadiran` - Total kehadiran
  - `status_umroh` - Badge sudah umroh
  - `tanggal_umroh` - Tanggal berangkat umroh
  - `foto` - Foto jamaah
  - `status_aktif` - aktif/non_aktif
  - `keterangan` - Keterangan tambahan
  - `timestamps` & `softDeletes`

### 2. Tabel hadiah_masar
Menyimpan data hadiah untuk jamaah MASAR
- **Kolom:**
  - `id` - Primary key
  - `kode_hadiah` - Format: HM-JENIS-TAHUN-URUT
  - `nama_hadiah` - Nama hadiah
  - `jenis_hadiah` - sarung, peci, gamis, mukena, tasbih, sajadah, al_quran, buku, lainnya
  - `ukuran` - S, M, L, XL, XXL, atau nomor
  - `warna` - Warna hadiah
  - `deskripsi` - Deskripsi hadiah
  - `stok_awal` - Stok awal
  - `stok_tersedia` - Stok tersedia
  - `stok_terbagikan` - Stok terbagikan
  - `stok_ukuran` - JSON stok per ukuran
  - `nilai_hadiah` - Nilai/harga hadiah
  - `tanggal_pengadaan` - Tanggal pengadaan
  - `supplier` - Nama supplier
  - `foto` - Foto hadiah
  - `status` - tersedia/habis/tidak_aktif
  - `keterangan` - Keterangan tambahan
  - `timestamps` & `softDeletes`

### 3. Tabel distribusi_hadiah_masar
Menyimpan data distribusi hadiah ke jamaah
- **Kolom:**
  - `id` - Primary key
  - `nomor_distribusi` - Format: DM-TAHUN-BULAN-URUT
  - `jamaah_id` - Foreign key ke jamaah_masar
  - `hadiah_id` - Foreign key ke hadiah_masar
  - `tanggal_distribusi` - Tanggal distribusi
  - `jumlah` - Jumlah hadiah
  - `ukuran` - Ukuran yang diminta
  - `ukuran_diterima` - Ukuran yang diterima
  - `warna_diterima` - Warna yang diterima
  - `penerima` - Nama penerima (jika bukan jamaah langsung)
  - `foto_bukti` - Foto bukti distribusi
  - `tanda_tangan` - Tanda tangan penerima
  - `status_distribusi` - diterima/pending/dikembalikan
  - `keterangan` - Keterangan tambahan
  - `petugas_distribusi` - Nama petugas
  - `timestamps` & `softDeletes`

## Fitur Utama

### 1. Manajemen Jamaah
- **CRUD Jamaah Bapak-Bapak**
  - Create, Read, Update, Delete data jamaah
  - Upload foto jamaah
  - Generate nomor jamaah otomatis (MA-xxxx-xx-xx-xx)
  
- **ID Card Jamaah**
  - Download ID card dalam format PDF
  - Berisi foto, nama, nomor jamaah, dan informasi lainnya

- **Status Umroh**
  - Toggle status umroh dengan switch button
  - Menampilkan badge sudah/belum umroh
  - Input tanggal berangkat umroh

- **Import & Export**
  - Import data jamaah dari Excel
  - Export data jamaah ke Excel
  - Download template import
  - Import data kehadiran dari Excel

- **Badge Kehadiran**
  - Hijau: >= 25 kehadiran
  - Kuning: 10-24 kehadiran
  - Merah: < 10 kehadiran

### 2. Manajemen Hadiah
- **CRUD Hadiah**
  - Create, Read, Update, Delete data hadiah
  - Upload foto hadiah
  - Generate kode hadiah otomatis (HM-xxx-xxxx-xxxx)
  - Manajemen stok per ukuran (JSON format)

- **Jenis Hadiah**
  - Sarung, Peci, Gamis, Mukena
  - Tasbih, Sajadah, Al-Qur'an
  - Buku, Lainnya

- **Status Stok**
  - Tersedia (hijau): stok > 50%
  - Peringatan (kuning): stok 20-50%
  - Kritis (merah): stok < 20%

### 3. Distribusi Hadiah
- **Form Distribusi**
  - Pilih jamaah dari DataTables
  - Pilih hadiah yang tersedia
  - Input jumlah dan ukuran
  - Auto-generate nomor distribusi (DM-xxxx-xx-xxxx)

- **Update Stok Otomatis**
  - Mengurangi stok tersedia
  - Menambah stok terbagikan
  - Update status hadiah jika habis

- **History Distribusi**
  - Lihat riwayat distribusi per jamaah
  - Lihat riwayat distribusi per hadiah
  - Filter berdasarkan tanggal

### 4. Laporan
- **Laporan Stok Ukuran**
  - Stok per jenis hadiah
  - Breakdown per ukuran
  - Visualisasi chart

- **Rekap Distribusi**
  - Total distribusi per periode
  - Distribusi per jamaah
  - Distribusi per hadiah
  - Export ke PDF/Excel

## Routes

### Jamaah Routes
```
GET    /masar/jamaah                        - List jamaah
GET    /masar/jamaah/create                 - Form tambah jamaah
POST   /masar/jamaah                        - Simpan jamaah baru
GET    /masar/jamaah/{id}                   - Detail jamaah
GET    /masar/jamaah/{id}/edit              - Form edit jamaah
PUT    /masar/jamaah/{id}                   - Update jamaah
DELETE /masar/jamaah/{id}                   - Hapus jamaah
GET    /masar/jamaah/{id}/id-card           - Download ID card
POST   /masar/jamaah/{id}/toggle-umroh      - Toggle status umroh
POST   /masar/jamaah/import                 - Import dari Excel
GET    /masar/jamaah/export/excel           - Export ke Excel
GET    /masar/jamaah/download/template      - Download template import
GET    /masar/jamaah/kehadiran/template     - Template import kehadiran
POST   /masar/jamaah/kehadiran/import       - Import kehadiran
```

### Hadiah Routes
```
GET    /masar/hadiah                        - List hadiah
GET    /masar/hadiah/create                 - Form tambah hadiah
POST   /masar/hadiah                        - Simpan hadiah baru
GET    /masar/hadiah/{id}/edit              - Form edit hadiah
PUT    /masar/hadiah/{id}                   - Update hadiah
DELETE /masar/hadiah/{id}                   - Hapus hadiah
```

### Distribusi Routes
```
GET    /masar/distribusi                    - List distribusi
POST   /masar/distribusi                    - Simpan distribusi baru
GET    /masar/distribusi/{id}               - Detail distribusi
GET    /masar/distribusi/{id}/edit          - Form edit distribusi
PUT    /masar/distribusi/{id}               - Update distribusi
DELETE /masar/distribusi/{id}               - Hapus distribusi
```

### Laporan Routes
```
GET    /masar/laporan/stok-ukuran           - Laporan stok per ukuran
GET    /masar/laporan/rekap-distribusi      - Rekap distribusi hadiah
```

## Models

### JamaahMasar
- **Relationships:**
  - `kehadiran()` - hasMany ke KehadiranJamaahMasar
  - `distribusiHadiah()` - hasMany ke DistribusiHadiahMasar
  - `pemenangUndian()` - hasMany ke PemenangUndianUmrohMasar

- **Methods:**
  - `generateNomorJamaah($nik, $tahun_masuk, $id)` - Generate nomor jamaah
  - `getBadgeColorAttribute()` - Get badge color berdasarkan kehadiran
  - `getBadgeColorNameAttribute()` - Get badge color name

- **Scopes:**
  - `aktif()` - Filter jamaah aktif
  - `sudahUmroh()` - Filter jamaah yang sudah umroh
  - `tahunMasuk($tahun)` - Filter berdasarkan tahun masuk

### HadiahMasar
- **Relationships:**
  - `distribusi()` - hasMany ke DistribusiHadiahMasar
  - `distribusiHadiah()` - alias untuk distribusi()

- **Methods:**
  - `generateKodeHadiah($jenis)` - Generate kode hadiah
  - `updateStokSetelahDistribusi($jumlah)` - Update stok setelah distribusi

- **Scopes:**
  - `tersedia()` - Filter hadiah tersedia
  - `jenis($jenis)` - Filter berdasarkan jenis

### DistribusiHadiahMasar
- **Relationships:**
  - `jamaah()` - belongsTo ke JamaahMasar
  - `hadiah()` - belongsTo ke HadiahMasar

- **Methods:**
  - `generateNomorDistribusi()` - Generate nomor distribusi
  - `sudahMenerima($jamaah_id, $hadiah_id)` - Cek jamaah sudah menerima

- **Scopes:**
  - `hariIni()` - Filter distribusi hari ini
  - `bulanIni()` - Filter distribusi bulan ini

## Controllers

### JamaahMasarController
Mengelola CRUD jamaah, import/export, ID card, toggle umroh

### HadiahMasarController
Mengelola CRUD hadiah, distribusi hadiah, laporan stok dan distribusi

## Views

Semua views berada di folder `resources/views/masar/`:
- `jamaah/` - Views untuk manajemen jamaah
  - `index.blade.php` - List jamaah
  - `create.blade.php` - Form tambah jamaah
  - `edit.blade.php` - Form edit jamaah
  - `show.blade.php` - Detail jamaah
  - `id_card.blade.php` - Template ID card

- `hadiah/` - Views untuk manajemen hadiah
  - `index.blade.php` - List hadiah
  - `create.blade.php` - Form tambah hadiah
  - `edit.blade.php` - Form edit hadiah
  - `distribusi.blade.php` - Form distribusi hadiah
  - `edit_distribusi.blade.php` - Form edit distribusi

- `laporan/` - Views untuk laporan
  - `stok_ukuran.blade.php` - Laporan stok per ukuran
  - `rekap_distribusi.blade.php` - Rekap distribusi

- `partials/` - Components yang reusable
  - `navigation.blade.php` - Navigation menu

## Cara Penggunaan

### 1. Akses Menu
- Login sebagai admin/super admin
- Buka menu **Manajemen Yayasan**
- Pilih **MASAR**

### 2. Tambah Jamaah
- Klik **Jamaah** di navigation
- Klik tombol **Tambah Jamaah**
- Isi form data jamaah
- Upload foto (optional)
- Submit form

### 3. Tambah Hadiah
- Klik **Hadiah** di navigation
- Klik tombol **Tambah Hadiah**
- Isi data hadiah dan stok
- Upload foto (optional)
- Submit form

### 4. Distribusi Hadiah
- Klik **Distribusi** di navigation
- Pilih jamaah dari tabel
- Pilih hadiah yang tersedia
- Input jumlah dan ukuran
- Submit distribusi

### 5. Lihat Laporan
- Klik dropdown **Laporan**
- Pilih **Stok Ukuran** atau **Rekap Distribusi**
- Filter berdasarkan periode (optional)
- Export ke PDF/Excel

## Migration

Jalankan migration untuk membuat tabel:
```bash
php artisan migrate
```

Migration files:
- `2025_11_10_100001_create_jamaah_masar_table.php`
- `2025_11_10_100003_create_hadiah_masar_table.php`
- `2025_11_10_100005_create_distribusi_hadiah_masar_table.php`

## Catatan
- Semua data MASAR terpisah dari Majlis Ta'lim Al-Ikhlas
- Nomor otomatis menggunakan prefix yang berbeda (MA-, HM-, DM-)
- Struktur dan fitur identik dengan Majlis Ta'lim Al-Ikhlas
- Data dapat di-import/export menggunakan Excel
- Mendukung upload foto untuk jamaah dan hadiah
- Tersedia laporan lengkap untuk monitoring

---
**Created:** November 10, 2025
**Last Updated:** November 10, 2025
**Version:** 1.0.0
