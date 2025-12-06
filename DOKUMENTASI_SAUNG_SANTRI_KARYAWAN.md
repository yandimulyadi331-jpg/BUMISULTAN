# DOKUMENTASI MENU SAUNG SANTRI UNTUK KARYAWAN

## 📋 Deskripsi
Sistem manajemen Saung Santri untuk karyawan dengan akses READ-ONLY (hanya dapat melihat data, tidak dapat membuat, mengedit, atau menghapus data santri).

## 🎯 Fitur Utama

### 1. Dashboard Saung Santri Karyawan
- **Route**: `/saungsantri/dashboard-karyawan`
- **View**: `resources/views/saungsantri/dashboard-karyawan.blade.php`
- **Akses**: Dari halaman "Fasilitas & Asset" karyawan
- **Fitur**:
  - Menu Data Santri (Aktif)
  - Menu Jadwal & Absensi (Coming Soon)
  - Menu Ijin Santri (Coming Soon)
  - Menu Keuangan Santri (Coming Soon)
  - Menu Pelanggaran Santri (Coming Soon)
  - Menu Khidmat (Coming Soon)

### 2. Data Santri (Mobile View)
- **Route**: `/saungsantri/santri-karyawan`
- **View**: `resources/views/santri/karyawan/index.blade.php`
- **Fitur**:
  - Tampilan mobile-friendly dengan card design
  - Filter data santri:
    - Pencarian (NIS/Nama/NIK)
    - Status Santri (Aktif, Cuti, Alumni, Keluar)
    - Jenis Kelamin (Laki-laki, Perempuan)
    - Tahun Masuk
  - Informasi yang ditampilkan:
    - Foto santri
    - Nama lengkap dan NIS
    - Jenis kelamin & status santri
    - Tahun masuk
    - Status ijin (Pulang/Di Pesantren)
    - Progress hafalan dengan progress bar
  - Pagination
  - Button "Lihat Detail" untuk setiap santri

### 3. Detail Santri (Mobile View)
- **Route**: `/saungsantri/santri-karyawan/{id}`
- **View**: `resources/views/santri/karyawan/show.blade.php`
- **Fitur**:
  - Foto profil santri (150x150px)
  - Nama lengkap dan nama panggilan
  - NIS
  - Status santri dengan badge warna
  - Progress hafalan (Juz & Halaman)
  - Tab navigasi dengan 5 kategori:

#### Tab 1: Data Pribadi
- NIS
- NIK
- Jenis Kelamin (dengan icon)
- Tempat & Tanggal Lahir
- Umur
- Alamat Lengkap
- Kelurahan/Kecamatan
- Kabupaten/Kota & Provinsi
- Kode Pos
- No. HP (clickable untuk telpon)
- Email (clickable untuk email)

#### Tab 2: Data Keluarga
**Data Orang Tua:**
- Nama Ayah
- Pekerjaan Ayah
- No. HP Ayah (clickable)
- Nama Ibu
- Pekerjaan Ibu
- No. HP Ibu (clickable)

**Data Wali (jika ada):**
- Nama Wali
- Hubungan Wali
- No. HP Wali (clickable)

#### Tab 3: Data Pendidikan
- Asal Sekolah
- Tingkat Pendidikan
- Tahun Masuk
- Tanggal Masuk
- Lama Mondok
- Status Santri (badge)
- Status Aktif (badge)

#### Tab 4: Data Hafalan
- Target Hafalan
- Tanggal Mulai Tahfidz
- Tanggal Khatam Terakhir
- Catatan Hafalan

#### Tab 5: Data Asrama
- Nama Asrama
- Nomor Kamar
- Nama Pembina
- Keterangan

## 🔗 Alur Navigasi

```
Dashboard Karyawan
  └─> Menu Fasilitas & Asset (fasilitas.dashboard.karyawan)
       └─> Menu Saung Santri (saungsantri.dashboard.karyawan)
            └─> Data Santri (santri.karyawan.index)
                 └─> Detail Santri (santri.karyawan.show)
                      └─> Kembali ke Data Santri
```

## 🛠️ Technical Implementation

### Routes (web.php)
```php
// Saung Santri untuk Karyawan (READ ONLY)
Route::controller(SantriController::class)->group(function () {
    Route::get('/saungsantri/dashboard-karyawan', 'dashboardKaryawan')
        ->name('saungsantri.dashboard.karyawan');
    Route::get('/saungsantri/santri-karyawan', 'indexKaryawan')
        ->name('santri.karyawan.index');
    Route::get('/saungsantri/santri-karyawan/{id}', 'showKaryawan')
        ->name('santri.karyawan.show');
});
```

### Controller Methods (SantriController.php)

#### 1. dashboardKaryawan()
- Menampilkan dashboard utama Saung Santri untuk karyawan
- Return: view `saungsantri.dashboard-karyawan`

#### 2. indexKaryawan(Request $request)
- Menampilkan list data santri dengan filter
- Support filter:
  - status_santri
  - jenis_kelamin
  - tahun_masuk
  - search (NIS/Nama/NIK)
- Load relasi ijin santri (jika tabel ada)
- Pagination: 10 items per page
- Return: view `santri.karyawan.index`

#### 3. showKaryawan($id)
- Menampilkan detail lengkap santri
- Return: view `santri.karyawan.show`

### Views

#### 1. saungsantri/dashboard-karyawan.blade.php
- Layout: `layouts.mobile.app`
- Header gradient: Purple-Blue (#6a11cb to #2575fc)
- Menu cards dengan icon dan subtitle
- Responsive grid (col-6 untuk mobile)

#### 2. santri/karyawan/index.blade.php
- Layout: `layouts.mobile.app`
- Header gradient: Purple-Blue
- Search box dengan 3 filter dropdown
- Card-based design untuk setiap santri
- Progress bar untuk hafalan
- Badge untuk status
- Clickable phone numbers (tel: protocol)

#### 3. santri/karyawan/show.blade.php
- Layout: `layouts.mobile.app`
- Profile card dengan foto bulat
- Tab navigation dengan smooth transition
- JavaScript untuk tab switching
- Clickable contact info (tel: & mailto:)

## 🎨 Design Specifications

### Color Palette
- Primary: `#6a11cb` (Purple)
- Secondary: `#2575fc` (Blue)
- Success: `#10b981` (Green)
- Warning: `#f59e0b` (Orange)
- Danger: `#ef4444` (Red)
- Info: `#3b82f6` (Blue)
- Pink: `#ec4899` (Pink)

### Typography
- Header Title: Bold, 1.5rem+
- Body Text: 0.85-0.9rem
- Small Text: 0.7-0.75rem
- Font Weight: 600 for labels, 400 for regular

### Components
- Border Radius: 10-20px (rounded corners)
- Card Shadow: `0 2px 8px rgba(0, 0, 0, 0.08)`
- Hover Shadow: `0 4px 15px rgba(0, 0, 0, 0.15)`
- Transition: `all 0.3s ease`

## 📱 Mobile Optimization
- Responsive grid system (Bootstrap col-6, col-12)
- Touch-friendly buttons (min height 44px)
- Horizontal scrollable tabs
- Smooth transitions
- Bottom spacing (80px) untuk navbar

## 🔒 Security & Access Control
- **READ ONLY ACCESS**: Karyawan tidak dapat:
  - Tambah data santri baru
  - Edit data santri
  - Hapus data santri
  - Export PDF/Excel
  - Print QR Code
- Semua aksi hanya untuk melihat (view) data

## 📊 Data Flow

### Filter & Search
1. User input filter/search
2. Form submit via GET request
3. Query builder apply filters
4. Return filtered results
5. Display dengan pagination

### Detail View
1. User klik "Lihat Detail"
2. Get santri by ID
3. Load all relations
4. Display di tab-based interface
5. JavaScript handle tab switching

## ✅ Testing Checklist

### Menu Navigation
- [✓] Menu Saung Santri muncul di dashboard Fasilitas karyawan
- [✓] Klik menu redirect ke dashboard Saung Santri
- [✓] Menu Data Santri aktif dan clickable
- [✓] Menu lain disabled (Coming Soon)

### Data Santri List
- [✓] Data santri ditampilkan dalam card
- [✓] Search by NIS/Nama/NIK berfungsi
- [✓] Filter status santri berfungsi
- [✓] Filter jenis kelamin berfungsi
- [✓] Filter tahun masuk berfungsi
- [✓] Button reset filter berfungsi
- [✓] Progress hafalan ditampilkan
- [✓] Status ijin ditampilkan (Pulang/Di Pesantren)
- [✓] Pagination berfungsi
- [✓] Button "Lihat Detail" berfungsi

### Detail Santri
- [✓] Foto santri ditampilkan (atau placeholder)
- [✓] Semua data pribadi ditampilkan
- [✓] Data keluarga ditampilkan
- [✓] Data pendidikan ditampilkan
- [✓] Data hafalan ditampilkan
- [✓] Data asrama ditampilkan
- [✓] Tab switching berfungsi dengan smooth
- [✓] No. HP clickable untuk telpon
- [✓] Email clickable untuk email
- [✓] Button back berfungsi

### Responsive Design
- [✓] Tampilan baik di mobile (320px - 480px)
- [✓] Tampilan baik di tablet (481px - 768px)
- [✓] Card layout responsive
- [✓] Tab navigation scrollable horizontal
- [✓] Touch gestures friendly

## 🚀 Future Enhancements (Coming Soon)

### 1. Jadwal & Absensi Santri
- Lihat jadwal harian santri
- Cek kehadiran santri
- Riwayat absensi

### 2. Ijin Santri
- Lihat santri yang sedang ijin
- Status ijin (pulang/di pesantren)
- Tanggal rencana kembali

### 3. Keuangan Santri
- Lihat transaksi keuangan santri
- Status pembayaran
- Riwayat transaksi

### 4. Pelanggaran Santri
- Lihat catatan pelanggaran
- Tingkat pelanggaran
- Riwayat pelanggaran

### 5. Khidmat (Belanja Masak)
- Lihat data khidmat santri
- Jadwal khidmat
- Riwayat khidmat

## 📝 Notes
- Tidak ada perubahan database
- Tidak ada perubahan data
- Menggunakan data yang sama dengan mode admin
- Layout mobile-friendly untuk akses karyawan di lapangan
- Design konsisten dengan menu Fasilitas & Asset lainnya

## 👥 User Story
**Sebagai Karyawan:**
- Saya ingin melihat data santri tanpa perlu akses admin
- Saya ingin mencari santri dengan cepat menggunakan filter
- Saya ingin melihat detail lengkap santri
- Saya ingin menghubungi orang tua/wali santri langsung dari aplikasi
- Saya ingin tahu progress hafalan santri
- Saya ingin tahu status santri (aktif/cuti/alumni/keluar)
- Saya ingin tahu apakah santri sedang pulang atau di pesantren

## 🔧 Maintenance
- File views mudah di-maintain (terpisah dari admin)
- Controller method terpisah (indexKaryawan, showKaryawan)
- Route terpisah dengan prefix yang jelas
- Styling inline untuk kemudahan customization

---
**Version:** 1.0  
**Last Updated:** November 2025  
**Developer:** AI Assistant  
**Status:** ✅ Production Ready
