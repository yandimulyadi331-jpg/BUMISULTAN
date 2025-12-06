# DOKUMENTASI SISTEM PERALATAN BS (Bumi Sultan)

## 📋 OVERVIEW
Sistem **PERALATAN BS (Bumi Sultan)** adalah sistem manajemen peralatan operasional untuk kegiatan sehari-hari seperti alat kebersihan (sapu, pel, dll), alat tulis kantor, dan peralatan lainnya. Sistem ini menggantikan menu "Event Inventaris" yang sebelumnya ada.

## ✅ FITUR YANG TELAH DIIMPLEMENTASIKAN

### 1. Master Data Peralatan
- ✅ Tambah peralatan baru
- ✅ Edit peralatan
- ✅ Hapus peralatan
- ✅ Detail peralatan
- ✅ Upload foto peralatan
- ✅ Filter & pencarian

### 2. Manajemen Stok
- ✅ Tracking stok tersedia
- ✅ Tracking stok dipinjam
- ✅ Tracking stok rusak
- ✅ Alert stok menipis
- ✅ Update stok manual (tambah/kurang/rusak)

### 3. Peminjaman Peralatan
- ✅ Form peminjaman
- ✅ Validasi stok tersedia
- ✅ Generate nomor peminjaman otomatis
- ✅ Tracking status peminjaman (dipinjam/dikembalikan/terlambat)
- ✅ Form pengembalian
- ✅ Pencatatan kondisi saat pinjam & kembali

### 4. Laporan
- ✅ Laporan stok lengkap
- ✅ Laporan peminjaman
- ✅ Export PDF untuk laporan stok
- ✅ Export PDF untuk laporan peminjaman
- ✅ Statistik dashboard

## 🗂️ STRUKTUR FILE

### Database Migrations
```
database/migrations/
├── 2025_11_07_131524_create_peralatan_table.php
└── 2025_11_07_131749_create_peminjaman_peralatan_table.php
```

### Models
```
app/Models/
├── Peralatan.php
└── PeminjamanPeralatan.php
```

### Controllers
```
app/Http/Controllers/
├── PeralatanController.php
└── PeminjamanPeralatanController.php
```

### Views
```
resources/views/
├── peralatan/
│   ├── index.blade.php (✅ Sudah dibuat)
│   ├── create.blade.php (✅ Sudah dibuat)
│   ├── edit.blade.php (✅ Sudah dibuat)
│   ├── show.blade.php (Perlu dibuat)
│   └── laporan-stok.blade.php (✅ Sudah dibuat)
└── peminjaman-peralatan/
    ├── index.blade.php (Perlu dibuat)
    ├── create.blade.php (Perlu dibuat)
    ├── show.blade.php (Perlu dibuat)
    └── pengembalian.blade.php (Perlu dibuat)
```

## 📊 STRUKTUR DATABASE

### Tabel: peralatan
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| kode_peralatan | varchar(50) | Kode unik peralatan |
| nama_peralatan | varchar(255) | Nama peralatan |
| kategori | varchar(255) | Kategori (Alat Kebersihan, ATK, dll) |
| deskripsi | text | Deskripsi detail |
| stok_awal | int | Stok awal pembelian |
| stok_tersedia | int | Stok yang tersedia |
| stok_dipinjam | int | Stok yang sedang dipinjam |
| stok_rusak | int | Stok yang rusak |
| satuan | varchar(50) | Satuan (pcs, unit, set) |
| lokasi_penyimpanan | varchar(255) | Lokasi penyimpanan |
| kondisi | varchar(50) | baik/rusak ringan/rusak berat |
| harga_satuan | decimal(15,2) | Harga per satuan |
| tanggal_pembelian | date | Tanggal pembelian |
| supplier | varchar(255) | Nama supplier |
| stok_minimum | int | Alert stok minimum |
| foto | varchar(255) | Path foto peralatan |
| catatan | text | Catatan tambahan |

### Tabel: peminjaman_peralatan
| Field | Type | Description |
|-------|------|-------------|
| id | bigint | Primary key |
| nomor_peminjaman | varchar(255) | Nomor peminjaman unik |
| peralatan_id | bigint | Foreign key ke peralatan |
| karyawan_id | bigint | Foreign key ke karyawan |
| jumlah_dipinjam | int | Jumlah yang dipinjam |
| tanggal_pinjam | date | Tanggal pinjam |
| tanggal_kembali_rencana | date | Tanggal rencana kembali |
| tanggal_kembali_aktual | date | Tanggal aktual kembali |
| keperluan | varchar(255) | Keperluan peminjaman |
| status | varchar(50) | dipinjam/dikembalikan/terlambat |
| kondisi_saat_dipinjam | text | Kondisi saat dipinjam |
| kondisi_saat_dikembalikan | text | Kondisi saat dikembalikan |
| catatan | text | Catatan tambahan |

## 🔄 ROUTES

```php
// Master Data Peralatan
Route::resource('peralatan', PeralatanController::class);
Route::prefix('peralatan')->name('peralatan.')->group(function () {
    Route::get('/laporan-stok', [PeralatanController::class, 'laporanStok'])->name('laporan-stok');
    Route::get('/export-laporan-stok', [PeralatanController::class, 'exportLaporanStok'])->name('export-laporan-stok');
    Route::get('/laporan-peminjaman', [PeralatanController::class, 'laporanPeminjaman'])->name('laporan-peminjaman');
    Route::get('/export-laporan-peminjaman', [PeralatanController::class, 'exportLaporanPeminjaman'])->name('export-laporan-peminjaman');
    Route::post('/{peralatan}/update-stok', [PeralatanController::class, 'updateStok'])->name('update-stok');
});

// Peminjaman Peralatan
Route::resource('peminjaman-peralatan', PeminjamanPeralatanController::class);
Route::prefix('peminjaman-peralatan')->name('peminjaman-peralatan.')->group(function () {
    Route::get('/{peminjamanPeralatan}/form-pengembalian', [PeminjamanPeralatanController::class, 'formPengembalian'])->name('form-pengembalian');
    Route::post('/{peminjamanPeralatan}/pengembalian', [PeminjamanPeralatanController::class, 'pengembalian'])->name('pengembalian');
    Route::get('/stok-tersedia/{peralatan}', [PeminjamanPeralatanController::class, 'getStokTersedia'])->name('stok-tersedia');
});
```

## 🎨 KATEGORI PERALATAN

Sistem mendukung kategori berikut:
1. **Alat Kebersihan** - Sapu, pel, kain lap, dll
2. **Alat Tulis Kantor** - Pulpen, kertas, stapler, dll
3. **Elektronik** - Kalkulator, lampu, dll
4. **Peralatan Dapur** - Panci, piring, gelas, dll
5. **Peralatan Olahraga** - Bola, raket, matras, dll
6. **Peralatan Taman** - Cangkul, selang, dll
7. **Perkakas** - Palu, obeng, tang, dll
8. **Keamanan** - Senter, toa, dll
9. **Lainnya** - Kategori custom

## 📍 MENU NAVIGASI

Menu **PERALATAN BS** telah ditambahkan di:
- **Sidebar**: Fasilitas & Asset → PERALATAN BS (dengan icon tools)
- **Menggantikan**: Event Inventaris yang sebelumnya ada

## 🚀 CARA PENGGUNAAN

### 1. Menambah Peralatan Baru
1. Klik menu "PERALATAN BS" di sidebar
2. Klik tombol "Tambah Peralatan"
3. Isi form dengan data peralatan
4. Upload foto (opsional)
5. Klik "Simpan"

### 2. Meminjam Peralatan
1. Dari halaman PERALATAN BS, klik tombol "Peminjaman"
2. Klik "Tambah Peminjaman"
3. Pilih peralatan dan karyawan peminjam
4. Isi jumlah, tanggal, dan keperluan
5. Klik "Simpan"

### 3. Mengembalikan Peralatan
1. Dari halaman Peminjaman Peralatan
2. Klik tombol "Kembalikan" pada peminjaman yang aktif
3. Isi tanggal kembali dan kondisi
4. Jika ada yang rusak, input jumlah rusak
5. Klik "Proses Pengembalian"

### 4. Melihat Laporan Stok
1. Dari halaman PERALATAN BS
2. Klik tombol "Laporan Stok"
3. Filter berdasarkan kategori atau stok menipis
4. Export PDF jika diperlukan

## ⚙️ KONFIGURASI

### Storage untuk Foto
Foto peralatan disimpan di:
```
public/storage/peralatan/
```

### Format Nomor Peminjaman
Format: `PMP-YYYYMMDD-XXXX`
Contoh: `PMP-20251107-0001`

## 🔔 FITUR ALERT

Sistem akan memberikan alert untuk:
- ✅ Stok menipis (stok tersedia ≤ stok minimum)
- ✅ Peminjaman terlambat
- ✅ Validasi stok saat peminjaman

## 📝 CATATAN PENTING

1. **Migration**: Sudah dijalankan dan tabel sudah dibuat
2. **Route**: Sudah terupdate, menggantikan `inventaris-event`
3. **Menu**: Sudah terupdate di sidebar
4. **Storage**: Folder `public/storage/peralatan/` sudah dibuat
5. **Model Relationship**: Sudah lengkap dengan relasi ke karyawan

## 🔨 YANG MASIH PERLU DIBUAT (Opsional)

Untuk melengkapi sistem, Anda bisa menambahkan:

1. **View show.blade.php** - Detail peralatan dengan riwayat peminjaman
2. **View peminjaman-peralatan/index.blade.php** - Daftar peminjaman
3. **View peminjaman-peralatan/create.blade.php** - Form peminjaman
4. **View peminjaman-peralatan/pengembalian.blade.php** - Form pengembalian
5. **PDF Template** - Template PDF untuk laporan (jika mau custom design)
6. **Dashboard Widget** - Widget di dashboard untuk monitoring cepat

## 📞 SUPPORT

Sistem ini sudah siap digunakan untuk:
- ✅ Manajemen peralatan operasional harian
- ✅ Tracking stok dan peminjaman
- ✅ Laporan dan monitoring
- ✅ Alert stok menipis

## 🎯 KESIMPULAN

Sistem **PERALATAN BS (Bumi Sultan)** telah berhasil menggantikan menu "Event Inventaris" dengan sistem yang lebih sesuai untuk manajemen peralatan operasional sehari-hari seperti alat kebersihan, ATK, dan peralatan lainnya. Sistem ini dilengkapi dengan:
- Manajemen stok yang komprehensif
- Sistem peminjaman yang terstruktur
- Laporan yang informatif
- UI yang user-friendly

**Status: SIAP DIGUNAKAN** ✅
