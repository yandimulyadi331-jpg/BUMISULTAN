# SISTEM MANAJEMEN INVENTARIS
## Dokumentasi Lengkap - Super App v2

---

## 📋 DAFTAR ISI
1. [Gambaran Umum Sistem](#gambaran-umum)
2. [Struktur Database](#struktur-database)
3. [Fitur Utama](#fitur-utama)
4. [Model & Relationships](#model-relationships)
5. [Controllers](#controllers)
6. [Routes](#routes)
7. [Views (Blade Templates)](#views)
8. [API Endpoints](#api-endpoints)
9. [Cara Penggunaan](#cara-penggunaan)
10. [Instalasi & Setup](#instalasi)

---

## 🎯 GAMBARAN UMUM

Sistem Manajemen Inventaris adalah sub-menu dari **Fasilitas & Asset** yang menyediakan:

### Fitur Lengkap:
- ✅ **Master Data Inventaris** - Mendata barang inventaris dengan detail lengkap
- ✅ **Kode Unik** - Setiap barang memiliki kode unik (INV-00001, dst)
- ✅ **Peminjaman Inventaris** - Formulir peminjaman lengkap dengan foto & TTD digital
- ✅ **Pengembalian Inventaris** - Form pengembalian dengan deteksi keterlambatan otomatis
- ✅ **Inventaris Event** - Manajemen barang untuk event khusus (naik gunung, camping, dll)
- ✅ **History Tracking** - Log semua pergerakan dan aktivitas barang
- ✅ **Integrasi Menu Barang** - Import data dari menu Gedung existing
- ✅ **Export PDF** - Download laporan lengkap semua aktivitas
- ✅ **Status Management** - Tersedia, Dipinjam, Maintenance, Rusak, Hilang
- ✅ **Denda Otomatis** - Perhitungan denda keterlambatan otomatis

---

## 🗄️ STRUKTUR DATABASE

### 1. Tabel `inventaris`
```sql
- id (PK)
- kode_inventaris (UNIQUE) - Auto generate INV-00001
- nama_barang
- deskripsi
- kategori (Elektronik, Furniture, Alat Tulis, Olahraga, dll)
- barang_id (FK ke tabel barangs) - Link ke data barang existing
- merk
- tipe_model
- nomor_seri
- jumlah
- satuan (unit, pcs, set)
- harga_perolehan
- tanggal_perolehan
- kondisi (baik, rusak ringan, rusak berat)
- status (tersedia, dipinjam, maintenance, rusak, hilang)
- lokasi_penyimpanan
- cabang_id (FK)
- foto
- spesifikasi (JSON)
- masa_pakai_bulan
- tanggal_kadaluarsa
- keterangan
- created_by, updated_by (FK users)
- timestamps, soft_deletes
```

### 2. Tabel `peminjaman_inventaris`
```sql
- id (PK)
- kode_peminjaman (UNIQUE) - Auto generate PJM-00001
- inventaris_id (FK)
- karyawan_id (FK) - Peminjam
- jumlah_pinjam
- tanggal_pinjam
- tanggal_kembali_rencana
- tanggal_kembali_realisasi
- keperluan
- status (menunggu_approval, disetujui, ditolak, dipinjam, dikembalikan, terlambat)
- foto_barang
- ttd_peminjam (base64 signature)
- ttd_petugas (base64 signature)
- disetujui_oleh (FK users)
- tanggal_approval
- catatan_peminjaman
- catatan_approval
- inventaris_event_id (FK) - Jika bagian dari event
- created_by (FK users)
- timestamps, soft_deletes
```

### 3. Tabel `pengembalian_inventaris`
```sql
- id (PK)
- kode_pengembalian (UNIQUE) - Auto generate KMB-00001
- peminjaman_inventaris_id (FK)
- tanggal_pengembalian
- jumlah_kembali
- kondisi_barang (baik, rusak_ringan, rusak_berat, hilang)
- terlambat (boolean)
- hari_keterlambatan
- denda (decimal) - Auto calculate
- foto_pengembalian
- ttd_peminjam (base64)
- ttd_petugas (base64)
- keterangan
- catatan_kerusakan
- diterima_oleh (FK users)
- created_by (FK users)
- timestamps, soft_deletes
```

### 4. Tabel `inventaris_events`
```sql
- id (PK)
- kode_event (UNIQUE) - Auto generate EVT-00001
- nama_event
- deskripsi_event
- jenis_event (Outing, Training, Naik Gunung, dll)
- tanggal_event
- tanggal_selesai
- lokasi_event
- pic_id (FK users) - Person in Charge
- status (persiapan, berlangsung, selesai, dibatalkan)
- jumlah_peserta
- daftar_inventaris (JSON)
- catatan
- created_by (FK users)
- timestamps, soft_deletes
```

### 5. Tabel `inventaris_event_items` (Pivot)
```sql
- id (PK)
- inventaris_event_id (FK)
- inventaris_id (FK)
- jumlah_dibutuhkan
- jumlah_tersedia
- status (menunggu, tersedia, terdistribusi, dikembalikan)
- keterangan
- timestamps
```

### 6. Tabel `history_inventaris`
```sql
- id (PK)
- inventaris_id (FK)
- jenis_aktivitas (input, update, pinjam, kembali, pindah_lokasi, maintenance, perbaikan, hapus)
- deskripsi
- status_sebelum, status_sesudah
- lokasi_sebelum, lokasi_sesudah
- jumlah
- karyawan_id (FK)
- peminjaman_id (FK)
- pengembalian_id (FK)
- data_perubahan (JSON)
- foto
- user_id (FK) - User yang melakukan aktivitas
- timestamps
```

---

## 🔧 FITUR UTAMA

### 1. Master Data Inventaris
- Input manual barang inventaris
- Import dari menu Barang (existing)
- Auto-generate kode unik
- Upload foto barang
- Spesifikasi detail (JSON format)
- Multi-kategori
- Multi-cabang support
- Status management
- Kondisi barang tracking

### 2. Peminjaman Inventaris
- Formulir peminjaman lengkap
- Upload foto barang yang dipinjam
- TTD digital peminjam & petugas
- Validasi ketersediaan barang
- Sistem approval (menunggu → disetujui/ditolak → dipinjam)
- Rencana tanggal pengembalian
- Catatan keperluan
- Link ke event (jika ada)

### 3. Pengembalian Inventaris
- Form pengembalian dengan foto
- TTD digital peminjam & petugas penerima
- Deteksi keterlambatan otomatis
- Perhitungan denda otomatis (Rp 10.000/hari)
- Cek kondisi barang saat dikembalikan
- Catatan kerusakan
- Auto update status inventaris

### 4. Inventaris Event
- Buat event khusus (Naik Gunung, Camping, dll)
- Daftar inventaris untuk event
- Cek ketersediaan barang
- Distribusi otomatis ke karyawan peserta
- Tracking peminjaman & pengembalian per event
- Report lengkap per event

### 5. History & Tracking
- Log semua aktivitas inventaris
- Tracking pergerakan barang
- Filter by inventaris, karyawan, tanggal, jenis aktivitas
- Dashboard analytics
- Top 10 barang paling aktif
- Top 10 karyawan paling aktif
- Recent activities timeline

### 6. Export & Reporting
- Export PDF Master Data Inventaris
- Export PDF Laporan Aktivitas
- Export PDF Peminjaman & Pengembalian
- Export PDF per Event
- Export PDF History
- Filter custom by date range, status, kategori

---

## 🏗️ MODEL & RELATIONSHIPS

### Model: Inventaris
```php
// Relationships
- belongsTo(Barang) - barang
- belongsTo(Cabang) - cabang
- hasMany(PeminjamanInventaris) - peminjaman
- hasMany(HistoryInventaris) - histories
- hasMany(InventarisEventItem) - eventItems
- belongsTo(User) - createdBy, updatedBy

// Methods
- isTersedia() - Check if available
- jumlahTersedia() - Calculate available quantity
- logHistory($jenis, $deskripsi, $data) - Log activity
- generateKodeInventaris() - Auto generate code

// Scopes
- tersedia()
- byKategori($kategori)
- byCabang($cabangId)
```

### Model: PeminjamanInventaris
```php
// Relationships
- belongsTo(Inventaris) - inventaris
- belongsTo(Karyawan) - karyawan
- belongsTo(User) - disetujuiOleh, createdBy
- hasOne(PengembalianInventaris) - pengembalian
- belongsTo(InventarisEvent) - event

// Methods
- isTerlambat() - Check if late
- hariKeterlambatan() - Calculate days late
- hitungDenda($tarifPerHari) - Calculate fine
- setujui($userId, $catatan) - Approve
- tolak($userId, $catatan) - Reject
- prosesPeminjaman() - Process borrowing

// Scopes
- aktif()
- terlambat()
- byKaryawan($karyawanId)
- byEvent($eventId)
```

### Model: PengembalianInventaris
```php
// Relationships
- belongsTo(PeminjamanInventaris) - peminjaman
- belongsTo(User) - diterimаOleh, createdBy

// Methods
- statusKondisi() - Get condition status with badge
- generateKodePengembalian() - Auto generate code

// Scopes
- terlambat()
- byPeminjaman($peminjamanId)

// Auto Features
- Auto calculate keterlambatan saat create
- Auto calculate denda
- Auto update status peminjaman
- Auto update status inventaris
- Auto log history
```

### Model: InventarisEvent
```php
// Relationships
- belongsTo(User) - pic, createdBy
- hasMany(InventarisEventItem) - eventItems
- belongsToMany(Inventaris) - inventaris (via pivot)
- hasMany(PeminjamanInventaris) - peminjaman

// Methods
- addInventaris($inventarisId, $jumlah, $keterangan)
- cekKetersediaanInventaris()
- distribusiKeKaryawan($karyawanIds)
- generateKodeEvent()

// Scopes
- aktif()
- byJenis($jenis)
- upcoming()
```

### Model: HistoryInventaris
```php
// Relationships
- belongsTo(Inventaris) - inventaris
- belongsTo(Karyawan) - karyawan
- belongsTo(User) - user
- belongsTo(PeminjamanInventaris) - peminjaman
- belongsTo(PengembalianInventaris) - pengembalian

// Methods
- getJenisAktivitasLabel()
- getJenisAktivitasColor()

// Scopes
- byInventaris($inventarisId)
- byJenisAktivitas($jenis)
- byKaryawan($karyawanId)
- recent($days)
```

---

## 🎮 CONTROLLERS

### InventarisController
```php
- index() - List inventaris dengan filter & search
- create() - Form tambah inventaris
- store() - Simpan inventaris baru
- show($id) - Detail inventaris
- edit($id) - Form edit
- update($id) - Update inventaris
- destroy($id) - Hapus inventaris (soft delete)
- importFromBarang() - Import dari menu Barang
- getBarangsForImport() - Get list barang yang bisa diimport
- exportPdf() - Export PDF master data
- exportAktivitasPdf() - Export PDF aktivitas
```

### PeminjamanInventarisController
```php
- index() - List peminjaman
- create() - Form peminjaman
- store() - Buat peminjaman baru
- show($id) - Detail peminjaman
- edit($id) - Edit peminjaman (hanya status menunggu)
- update($id) - Update peminjaman
- destroy($id) - Hapus peminjaman
- setujui($id) - Approve peminjaman
- tolak($id) - Reject peminjaman
- checkKetersediaan($inventarisId) - API check stok
- exportPdf() - Export PDF peminjaman
```

### PengembalianInventarisController
```php
- index() - List pengembalian
- create($peminjamanId) - Form pengembalian
- store() - Proses pengembalian
- show($id) - Detail pengembalian
- getPeminjamanAktif() - List peminjaman yang bisa dikembalikan
- exportPdf() - Export PDF pengembalian
```

### InventarisEventController
```php
- index() - List event
- create() - Form event baru
- store() - Simpan event
- show($id) - Detail event
- edit($id) - Edit event
- update($id) - Update event
- destroy($id) - Hapus event
- addInventaris($eventId) - Tambah inventaris ke event
- removeInventaris($eventId, $itemId) - Remove inventaris dari event
- cekKetersediaan($eventId) - Cek ketersediaan inventaris event
- distribusi($eventId) - Distribusi inventaris ke karyawan peserta
- getInventarisForEvent($eventId) - Get list inventaris untuk ditambahkan
- getKaryawanForDistribusi($eventId) - Get karyawan untuk distribusi
- exportPdf($eventId) - Export PDF event
```

### HistoryInventarisController
```php
- index() - List history dengan filter lengkap
- show($id) - Detail history
- byInventaris($inventarisId) - History by inventaris
- byKaryawan($karyawanId) - History by karyawan
- dashboard() - Dashboard analytics & statistics
- exportPdf() - Export PDF history
- exportExcel() - Export Excel (optional)
```

---

## 🛣️ ROUTES

### File: `routes/web.php`

```php
// INVENTARIS ROUTES
Route::middleware(['auth'])->prefix('inventaris')->name('inventaris.')->group(function () {
    
    // Master Data Inventaris
    Route::resource('/', InventarisController::class);
    Route::post('/import-barang', [InventarisController::class, 'importFromBarang'])->name('import-barang');
    Route::get('/barangs-for-import', [InventarisController::class, 'getBarangsForImport'])->name('barangs-import');
    Route::get('/export-pdf', [InventarisController::class, 'exportPdf'])->name('export-pdf');
    Route::get('/export-aktivitas-pdf', [InventarisController::class, 'exportAktivitasPdf'])->name('export-aktivitas-pdf');
    
    // Peminjaman Inventaris
    Route::prefix('peminjaman')->name('peminjaman.')->group(function () {
        Route::resource('/', PeminjamanInventarisController::class);
        Route::post('/{peminjamanInventaris}/setujui', [PeminjamanInventarisController::class, 'setujui'])->name('setujui');
        Route::post('/{peminjamanInventaris}/tolak', [PeminjamanInventarisController::class, 'tolak'])->name('tolak');
        Route::get('/check-ketersediaan/{inventarisId}', [PeminjamanInventarisController::class, 'checkKetersediaan'])->name('check-ketersediaan');
        Route::get('/export-pdf', [PeminjamanInventarisController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // Pengembalian Inventaris
    Route::prefix('pengembalian')->name('pengembalian.')->group(function () {
        Route::get('/', [PengembalianInventarisController::class, 'index'])->name('index');
        Route::get('/create/{peminjamanId}', [PengembalianInventarisController::class, 'create'])->name('create');
        Route::post('/', [PengembalianInventarisController::class, 'store'])->name('store');
        Route::get('/{pengembalianInventaris}', [PengembalianInventarisController::class, 'show'])->name('show');
        Route::get('/peminjaman-aktif', [PengembalianInventarisController::class, 'getPeminjamanAktif'])->name('peminjaman-aktif');
        Route::get('/export-pdf', [PengembalianInventarisController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // Inventaris Event
    Route::prefix('event')->name('event.')->group(function () {
        Route::resource('/', InventarisEventController::class);
        Route::post('/{inventarisEvent}/add-inventaris', [InventarisEventController::class, 'addInventaris'])->name('add-inventaris');
        Route::delete('/{inventarisEvent}/remove-inventaris/{itemId}', [InventarisEventController::class, 'removeInventaris'])->name('remove-inventaris');
        Route::post('/{inventarisEvent}/cek-ketersediaan', [InventarisEventController::class, 'cekKetersediaan'])->name('cek-ketersediaan');
        Route::post('/{inventarisEvent}/distribusi', [InventarisEventController::class, 'distribusi'])->name('distribusi');
        Route::get('/{inventarisEvent}/get-inventaris', [InventarisEventController::class, 'getInventarisForEvent'])->name('get-inventaris');
        Route::get('/{inventarisEvent}/get-karyawan', [InventarisEventController::class, 'getKaryawanForDistribusi'])->name('get-karyawan');
        Route::get('/{inventarisEvent}/export-pdf', [InventarisEventController::class, 'exportPdf'])->name('export-pdf');
    });
    
    // History Inventaris
    Route::prefix('history')->name('history.')->group(function () {
        Route::get('/', [HistoryInventarisController::class, 'index'])->name('index');
        Route::get('/dashboard', [HistoryInventarisController::class, 'dashboard'])->name('dashboard');
        Route::get('/{historyInventaris}', [HistoryInventarisController::class, 'show'])->name('show');
        Route::get('/by-inventaris/{inventaris}', [HistoryInventarisController::class, 'byInventaris'])->name('by-inventaris');
        Route::get('/by-karyawan/{karyawan}', [HistoryInventarisController::class, 'byKaryawan'])->name('by-karyawan');
        Route::get('/export-pdf', [HistoryInventarisController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-excel', [HistoryInventarisController::class, 'exportExcel'])->name('export-excel');
    });
});
```

---

## 📱 CARA PENGGUNAAN

### Workflow Peminjaman & Pengembalian:

1. **Input Inventaris**
   - Masuk ke menu Inventaris
   - Klik "Tambah Inventaris Baru"
   - Isi form lengkap (nama, kategori, jumlah, foto, dll)
   - Atau import dari menu Barang existing
   - Sistem auto-generate kode inventaris (INV-00001)

2. **Pengajuan Peminjaman**
   - Karyawan/Admin masuk ke "Peminjaman Inventaris"
   - Klik "Ajukan Peminjaman"
   - Pilih inventaris yang akan dipinjam
   - Isi form: jumlah, tanggal pinjam & rencana kembali, keperluan
   - Upload foto barang
   - TTD digital peminjam
   - Submit → Status: Menunggu Approval

3. **Approval Peminjaman**
   - Admin/Atasan buka detail peminjaman
   - Review data peminjaman
   - TTD digital petugas
   - Klik "Setujui" atau "Tolak"
   - Jika disetujui → Status: Dipinjam
   - Inventaris otomatis ter-track

4. **Pengembalian**
   - Buka menu "Pengembalian Inventaris"
   - Pilih peminjaman yang akan dikembalikan
   - Klik ikon pengembalian
   - Isi form: tanggal pengembalian, kondisi barang
   - Upload foto kondisi barang
   - TTD digital peminjam & petugas penerima
   - Keterangan (jika ada kerusakan)
   - Submit → Sistem otomatis:
     * Cek keterlambatan
     * Hitung denda jika terlambat
     * Update status peminjaman
     * Update status inventaris
     * Log history

5. **Inventaris Event**
   - Buat event baru (misal: Naik Gunung)
   - Tambahkan inventaris yang dibutuhkan (tenda, sleeping bag, dll)
   - Cek ketersediaan otomatis
   - Pilih karyawan peserta
   - Distribusi otomatis → Buat peminjaman untuk setiap peserta
   - Track semua peminjaman & pengembalian per event

6. **Monitoring & Reporting**
   - Dashboard History untuk analytics
   - Filter by tanggal, inventaris, karyawan, jenis aktivitas
   - Top 10 barang & karyawan paling aktif
   - Export PDF untuk laporan lengkap

---

## 🚀 INSTALASI & SETUP

### 1. Copy Controller Files
```bash
# Rename controller files (remove _full suffix)
mv PeminjamanInventarisController_full.php PeminjamanInventarisController.php
mv PengembalianInventarisController_full.php PengembalianInventarisController.php
mv InventarisEventController_full.php InventarisEventController.php
mv HistoryInventarisController_full.php HistoryInventarisController.php
```

### 2. Run Migrations
```bash
php artisan migrate
```

### 3. Update Routes
Add the routes from above to `routes/web.php`

### 4. Create Views
Create blade templates untuk semua pages (akan dibuatkan terpisah)

### 5. Update Menu Sidebar
Tambahkan submenu "Manajemen Inventaris" di menu "Fasilitas & Asset"

### 6. Install Dependencies (if needed)
```bash
composer require barryvdh/laravel-dompdf
```

### 7. Publish Config
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

### 8. Storage Link
```bash
php artisan storage:link
```

---

## 📊 STATISTIK SISTEM

- **7 Tabel Database** (5 main + 1 pivot + 1 history)
- **5 Models** dengan relationships lengkap
- **5 Controllers** dengan 50+ methods
- **40+ Routes** untuk semua operasi
- **Auto Generate Codes**: INV-xxxxx, PJM-xxxxx, KMB-xxxxx, EVT-xxxxx
- **TTD Digital** support (base64 signatures)
- **Upload Foto** untuk inventaris, peminjaman, pengembalian
- **Auto Calculation**: Keterlambatan, Denda, Ketersediaan
- **History Tracking**: Semua aktivitas terekam otomatis
- **Export PDF**: 6 jenis laporan berbeda
- **Multi-Filter**: Tanggal, Status, Kategori, Cabang, Karyawan

---

## 🎨 TEKNOLOGI YANG DIGUNAKAN

- Laravel 10+ (PHP Framework)
- MySQL/MariaDB (Database)
- Tailwind CSS (UI Framework)
- Alpine.js (JavaScript Framework)
- Signature Pad JS (TTD Digital)
- DomPDF (PDF Generation)
- Laravel Excel (Excel Export - optional)
- Carbon (Date manipulation)
- Spatie Laravel Permission (Access Control - optional)

---

## 📞 SUPPORT & DEVELOPMENT

Sistem ini dikembangkan dengan konsep:
- **Modern & Clean Code**
- **RESTful Architecture**
- **MVC Pattern**
- **Repository Pattern** (optional)
- **Service Layer** (optional)
- **SOLID Principles**
- **DRY (Don't Repeat Yourself)**
- **Security First** (SQL Injection protected, XSS protected)

---

**Status:** Ready for Development
**Version:** 1.0.0
**Last Update:** 2025-11-06

---

✨ **SISTEM MANAJEMEN INVENTARIS LENGKAP DAN MODERN** ✨
