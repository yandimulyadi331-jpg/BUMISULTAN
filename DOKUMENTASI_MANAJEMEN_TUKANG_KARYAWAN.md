# DOKUMENTASI MANAJEMEN TUKANG UNTUK KARYAWAN

## 📋 Ringkasan
Modul **Manajemen Tukang untuk Karyawan** adalah versi read-only dari sistem manajemen tukang yang dapat diakses oleh karyawan melalui tampilan mobile. Karyawan hanya dapat melihat data tukang tanpa bisa melakukan perubahan (Create, Update, Delete).

## 🎯 Fitur
- ✅ Melihat daftar tukang dengan tampilan mobile-friendly
- ✅ Pencarian berdasarkan nama, kode, keahlian, dan no HP
- ✅ Filter berdasarkan status (Aktif/Non Aktif)
- ✅ Melihat detail lengkap tukang
- ✅ Statistik tukang aktif dan total tukang
- ✅ Tampilan modern dengan gradient dan card design
- ✅ Link WhatsApp dan Email langsung dari detail tukang

## 📂 Struktur File

### 1. Controller Methods
**File:** `app/Http/Controllers/TukangController.php`

**Methods Baru:**
```php
- indexKaryawan()  // Menampilkan daftar tukang untuk karyawan
- showKaryawan()   // Menampilkan detail tukang untuk karyawan
```

### 2. Views
**Folder:** `resources/views/manajemen-tukang/karyawan/`

**File:**
- `index.blade.php` - Halaman daftar tukang (Read-Only)
- `show.blade.php` - Halaman detail tukang (Read-Only)

### 3. Routes
**File:** `routes/web.php`

**Endpoints:**
```
GET    /manajemen-tukang-karyawan        - Daftar tukang (karyawan)
GET    /manajemen-tukang-karyawan/{id}   - Detail tukang (karyawan)
```

### 4. Dashboard Integration
**File:** `resources/views/dashboard/karyawan.blade.php`

**Posisi:** Setelah menu "Yayasan" di grid menu dashboard
**Icon:** Activity icon (3d)
**Label:** Tukang

## 🚀 Cara Penggunaan

### 1. Akses Menu Manajemen Tukang
1. Login sebagai karyawan
2. Buka dashboard karyawan (`/dashboard`)
3. Klik menu **"Tukang"** di grid menu aplikasi
4. Anda akan diarahkan ke halaman daftar tukang

### 2. Melihat Daftar Tukang
1. Di halaman daftar, Anda dapat melihat:
   - **Statistik**: Jumlah tukang aktif dan total tukang
   - **Daftar Tukang**: Nama, kode, keahlian, no HP, status, dan tarif
2. Gunakan **search box** untuk mencari tukang:
   - Cari berdasarkan nama, kode tukang, keahlian, atau no HP
3. Gunakan **filter status** untuk menyaring:
   - Semua Status
   - Aktif saja
   - Non Aktif saja
4. Klik tombol **filter** untuk menerapkan pencarian
5. Klik tombol **refresh** untuk reset filter

### 3. Melihat Detail Tukang
1. Dari daftar tukang, klik pada **card tukang** yang ingin dilihat
2. Detail yang ditampilkan:
   - **Foto tukang** (jika ada)
   - **Informasi pribadi**: Nama, kode, NIK, no HP, email, alamat
   - **Keahlian**: Skill/keahlian tukang
   - **Tarif harian**: Tarif per hari (jika ada)
   - **Status**: Aktif atau Non Aktif
   - **Keterangan**: Catatan tambahan
   - **Timestamp**: Tanggal dibuat dan terakhir diupdate
3. Klik **no HP** untuk langsung chat WhatsApp
4. Klik **email** untuk langsung kirim email
5. Klik **tombol back** untuk kembali ke daftar

## 🎨 Tampilan Mobile

### Halaman Index (Daftar Tukang)
- **Header**: Gradient background dengan tombol back dan judul
- **Search Section**: Search box dengan icon dan filter dropdown
- **Stats Cards**: 2 card menampilkan statistik (Aktif & Total)
- **Tukang List**: Card-based list dengan foto, info, dan badge status
- **Pagination**: Bottom pagination jika data lebih dari 10

### Halaman Detail (Show Tukang)
- **Header**: Gradient background dengan tombol back
- **Photo Section**: Large circular photo dengan nama dan kode
- **Status Badge**: Badge status aktif/non aktif
- **Tarif Card**: Card gradient menampilkan tarif harian (jika ada)
- **Info Sections**: Grouped information cards
  - Informasi Pribadi (NIK, HP, Email, Alamat)
  - Keahlian (Badge)
  - Keterangan
- **Timestamp**: Footer dengan tanggal dibuat dan update

## 🔐 Hak Akses

### Karyawan (Read-Only)
- ✅ Melihat daftar tukang
- ✅ Mencari dan filter tukang
- ✅ Melihat detail tukang
- ❌ Menambah tukang baru
- ❌ Mengubah data tukang
- ❌ Menghapus data tukang

### Admin/Super Admin (Full Access)
- ✅ Semua akses karyawan +
- ✅ Menambah tukang baru
- ✅ Mengubah data tukang
- ✅ Menghapus data tukang
- Akses melalui menu sidebar "Manajemen Tukang"

## 📱 Fitur Mobile-Friendly

### Responsive Design
- Optimized untuk layar mobile (320px - 480px)
- Touch-friendly buttons dan cards
- Smooth scrolling dan transitions

### UX Features
- Gradient background yang menarik
- Card design dengan shadow effects
- Icon-based navigation
- Badge untuk status visual
- Quick action links (WhatsApp, Email)

### Performance
- Pagination untuk data banyak (10 per halaman)
- Lazy loading untuk foto
- Optimized query dengan search dan filter

## 🔗 Integrasi

### Dashboard Karyawan
Menu "Tukang" terintegrasi di dashboard karyawan bersama:
- Fasilitas
- Saung Santri
- Yayasan
- Kunjungan (jika ada permission)

### Route Structure
```
/dashboard                           -> Dashboard Karyawan
  └─ /manajemen-tukang-karyawan     -> Daftar Tukang (Karyawan)
       └─ /{id}                      -> Detail Tukang (Karyawan)
```

## 📊 Data yang Ditampilkan

### Di Halaman Index
- Foto tukang (thumbnail)
- Nama tukang
- Kode tukang
- Keahlian
- No HP
- Status (badge)
- Tarif harian (badge)

### Di Halaman Detail
- Foto tukang (large)
- Nama lengkap
- Kode tukang
- NIK
- No HP (link WhatsApp)
- Email (link mailto)
- Alamat lengkap
- Keahlian
- Tarif harian
- Status
- Keterangan
- Created at
- Updated at

## 🎯 Use Case

### Scenario 1: Karyawan mencari tukang untuk proyek
1. Karyawan buka menu "Tukang"
2. Search "tukang batu"
3. Filter status "Aktif"
4. Lihat daftar tukang batu yang aktif
5. Klik tukang yang sesuai untuk lihat detail
6. Klik no HP untuk chat via WhatsApp

### Scenario 2: Karyawan cek tarif tukang
1. Karyawan buka menu "Tukang"
2. Lihat daftar dengan badge tarif
3. Klik detail untuk info lengkap
4. Lihat tarif harian di card gradient
5. Screenshot untuk referensi

### Scenario 3: Karyawan cek ketersediaan tukang
1. Karyawan buka menu "Tukang"
2. Lihat statistik tukang aktif di card stats
3. Filter "Aktif" untuk lihat yang tersedia
4. Catat nama dan kontak tukang aktif

## 🔧 Technical Details

### Query Performance
```php
// Optimized query dengan search dan filter
$query->where('nama_tukang', 'like', '%' . $search . '%')
      ->orWhere('kode_tukang', 'like', '%' . $search . '%')
      ->orWhere('keahlian', 'like', '%' . $search . '%')
      ->orWhere('no_hp', 'like', '%' . $search . '%');
```

### Pagination
- 10 items per page
- Laravel default pagination links
- Preserves search and filter parameters

### Security
- ID encryption menggunakan `Crypt::encrypt()`
- No permission untuk Create/Update/Delete
- Read-only access untuk semua data

## ✅ Testing Checklist

- [x] Akses menu dari dashboard karyawan
- [x] Tampilan daftar tukang
- [x] Search functionality
- [x] Filter status
- [x] Pagination
- [x] Klik detail tukang
- [x] Tampilan detail lengkap
- [x] Link WhatsApp berfungsi
- [x] Link Email berfungsi
- [x] Back button navigation
- [x] Responsive di berbagai device
- [x] No access ke Create/Edit/Delete

## 🚨 Catatan Penting

1. **Read-Only Access**: Karyawan hanya bisa melihat, tidak bisa mengubah data
2. **Mobile Optimized**: Tampilan dioptimalkan untuk mobile, bukan desktop
3. **No Sidebar**: Menggunakan layout mobile tanpa sidebar
4. **Quick Actions**: Link langsung ke WhatsApp dan Email
5. **Visual Stats**: Card stats untuk quick overview
6. **Search Friendly**: Multiple field search untuk kemudahan

## 📞 Support

Jika ada masalah atau pertanyaan terkait fitur ini:
1. Cek apakah routes sudah terdaftar
2. Cek apakah method controller sudah ada
3. Cek apakah view files sudah dibuat
4. Cek apakah menu sudah muncul di dashboard
5. Clear cache jika perlu: `php artisan cache:clear`

---

**Status**: ✅ Fully Implemented
**Version**: 1.0
**Last Update**: November 2025
