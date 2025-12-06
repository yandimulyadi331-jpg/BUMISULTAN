# DOKUMENTASI INVENTARIS DAN PERALATAN KARYAWAN

## 📋 Overview
Fitur ini mengintegrasikan menu **Inventaris** dan **Peralatan BS (Bumi Sultan)** menjadi satu halaman untuk mode karyawan dengan akses **READ-ONLY** (hanya bisa melihat data).

## ✅ Perubahan yang Dilakukan

### 1. **Controller Baru**
- **File**: `app/Http/Controllers/InventarisPeralatanKaryawanController.php`
- **Fungsi**:
  - `index()` - Menampilkan daftar inventaris dan peralatan dengan filter dan pagination terpisah
  - `showInventaris($id)` - Menampilkan detail inventaris
  - `showPeralatan($id)` - Menampilkan detail peralatan

### 2. **Routes**
- **File**: `routes/web.php`
- **Routes yang ditambahkan**:
  ```php
  Route::get('/fasilitas/inventaris-peralatan-karyawan', 'index')
      ->name('inventaris-peralatan.karyawan.index');
  Route::get('/fasilitas/inventaris-peralatan-karyawan/inventaris/{id}', 'showInventaris')
      ->name('inventaris-peralatan.karyawan.inventaris.show');
  Route::get('/fasilitas/inventaris-peralatan-karyawan/peralatan/{id}', 'showPeralatan')
      ->name('inventaris-peralatan.karyawan.peralatan.show');
  ```

### 3. **View**
- **File**: `resources/views/fasilitas/inventaris-peralatan/index-karyawan.blade.php`
- **Fitur**:
  - Tab switching antara Inventaris dan Peralatan
  - Filter untuk masing-masing tab (kategori, status, kondisi, search)
  - Statistik summary cards
  - View detail dengan modal SweetAlert2
  - Pagination terpisah untuk inventaris dan peralatan
  - Dark mode support
  - Responsive design
  - Neomorphic design style (sesuai dengan menu lainnya)

### 4. **Menu Dashboard**
- **File**: `resources/views/fasilitas/dashboard-karyawan.blade.php`
- **Perubahan**:
  - Menu "Peralatan Bumi Sultan" (Coming Soon) diganti menjadi "Inventaris & Peralatan"
  - Status diubah dari "Coming Soon" menjadi "Lihat Saja"
  - Link aktif mengarah ke route `inventaris-peralatan.karyawan.index`

## 🎨 Fitur Tampilan

### Summary Cards
- **Total Inventaris** - Jumlah total data inventaris
- **Total Peralatan** - Jumlah total data peralatan
- **Inventaris Tersedia** - Inventaris dengan status tersedia
- **Peralatan Baik** - Peralatan dengan kondisi baik

### Tab Inventaris
**Filter:**
- Pencarian (nama/kode)
- Kategori (elektronik, furnitur, kendaraan, alat_kantor, lainnya)
- Status (tersedia, dipinjam, rusak, maintenance, hilang)

**Data Ditampilkan:**
- Nama barang
- Kode inventaris
- Kategori
- Jumlah + satuan
- Status (badge warna)
- Kondisi

**Detail:**
- Kode
- Kategori
- Jumlah
- Status
- Kondisi
- Lokasi
- Foto (jika ada)

### Tab Peralatan
**Filter:**
- Pencarian (nama/kode)
- Kategori (Alat Kebersihan, Alat Tulis Kantor, Elektronik, dll)
- Kondisi (baik, rusak ringan, rusak berat)

**Data Ditampilkan:**
- Nama peralatan
- Kode peralatan
- Kategori
- Stok tersedia + satuan
- Kondisi (badge warna)

**Detail:**
- Kode
- Kategori
- Stok awal
- Stok tersedia
- Kondisi
- Lokasi
- Foto (jika ada)

## 🔐 Akses
- **Mode**: READ-ONLY untuk semua karyawan
- **Tidak ada fitur**: Tambah, Edit, Hapus, atau Update
- **Hanya bisa**: Melihat dan mencari data

## 🎯 Design Pattern
- **Neomorphic Design**: Sesuai dengan menu lainnya (Ruangan, Gedung, dll)
- **Dark Mode Support**: Otomatis mengikuti theme aplikasi
- **Responsive**: Mobile-first design
- **Animation**: Fade-in animations untuk cards
- **Color Coding**: Badge berwarna untuk status dan kondisi

## 📱 Navigasi
```
Dashboard Karyawan 
  └─> Fasilitas & Asset
      └─> Inventaris & Peralatan
          ├─> Tab Inventaris (List + Detail)
          └─> Tab Peralatan (List + Detail)
```

## 🔄 Data Flow
1. Karyawan klik menu "Inventaris & Peralatan" di Dashboard Fasilitas
2. Controller mengambil data dari model Inventaris dan Peralatan
3. Data ditampilkan dalam 2 tab dengan pagination terpisah
4. Karyawan bisa filter dan search di masing-masing tab
5. Karyawan bisa klik card untuk melihat detail dalam modal

## ⚡ Performance
- Pagination 10 items per tab
- Lazy loading untuk gambar
- Efficient query dengan eager loading (with relations)
- Separate pagination untuk inventaris dan peralatan

## 🎨 Color Scheme
- **Gradient 1** (Purple): Total Inventaris
- **Gradient 2** (Blue): Total Peralatan
- **Gradient 3** (Green): Inventaris Tersedia / Kondisi Baik
- **Gradient 4** (Orange): Peralatan Baik / Kondisi Normal

## ✨ Notes
- Semua karyawan bisa mengakses fitur ini
- Data real-time dari database
- Tidak perlu permission khusus
- Compatible dengan dark mode
- Mengikuti design pattern aplikasi yang sudah ada

---
**Created**: 21 November 2025
**Status**: ✅ Ready to Use
