# DOKUMENTASI FITUR PUSAT INFORMASI KARYAWAN

## 📋 Overview

Fitur Pusat Informasi memungkinkan admin untuk mengelola dan menyebarkan informasi ke karyawan melalui banner popup yang muncul otomatis saat karyawan login.

## ✨ Fitur Utama

### Mode Admin
1. **CRUD Informasi**
   - Tambah, Edit, Hapus (Soft Delete) informasi
   - 3 Tipe informasi: Banner/Gambar, Text, dan Link
   - Upload banner/gambar (max 2MB, format: JPG, PNG, GIF)
   - Set prioritas informasi (angka lebih tinggi = lebih prioritas)
   - Set periode tampil (tanggal mulai & selesai)
   - Toggle status aktif/nonaktif
   
2. **Tracking & Statistik**
   - Melihat jumlah karyawan yang sudah membaca informasi
   - Tracking per informasi siapa saja yang sudah membaca

### Mode Karyawan
1. **Banner Popup Otomatis**
   - Muncul otomatis saat login dashboard
   - Menampilkan informasi yang belum dibaca
   - Bisa ditutup dengan klik di mana saja
   - Jika ada multiple informasi, muncul berurutan
   
2. **Tidak Ada Duplikasi**
   - Setelah ditutup, informasi tidak muncul lagi
   - System tracking otomatis menggunakan tabel `informasi_reads`

## 🗂️ Database Structure

### Tabel `informasi`
```sql
- id (bigint, primary key)
- judul (string)
- konten (text, nullable)
- tipe (enum: banner, link, text)
- banner_path (string, nullable) - path file upload
- link_url (string, nullable) - URL jika tipe link
- is_active (boolean, default: true)
- priority (integer, default: 0)
- tanggal_mulai (datetime, nullable)
- tanggal_selesai (datetime, nullable)
- deleted_at (soft delete)
- timestamps
```

### Tabel `informasi_reads`
```sql
- id (bigint, primary key)
- informasi_id (foreign key to informasi)
- user_id (foreign key to users)
- read_at (timestamp)
- timestamps
- UNIQUE constraint (informasi_id, user_id)
```

## 🎯 Cara Penggunaan

### Admin - Tambah Informasi Baru

1. Login sebagai Super Admin
2. Klik menu **"Pusat Informasi"** di sidebar
3. Klik tombol **"Tambah Informasi Baru"**
4. Isi form:
   - **Judul**: Judul informasi (wajib)
   - **Tipe**: Pilih Banner/Text/Link
   - **Konten**: Deskripsi detail (opsional)
   - **Prioritas**: Angka untuk urutan tampil (default: 0)
   - **Upload Banner**: Jika tipe Banner
   - **URL Link**: Jika tipe Link
   - **Tanggal Mulai/Selesai**: Periode tampil (opsional)
   - **Status Aktif**: Toggle on/off
5. Klik **"Simpan Informasi"**

### Admin - Edit Informasi

1. Di halaman index, klik tombol **Edit** (ikon pensil)
2. Update data yang diperlukan
3. Klik **"Update Informasi"**

### Admin - Toggle Status Aktif/Nonaktif

1. Di halaman index, klik tombol status (hijau = aktif, abu = nonaktif)
2. Konfirmasi perubahan

### Admin - Hapus Informasi

1. Di halaman index, klik tombol **Hapus** (ikon trash)
2. Konfirmasi penghapusan
3. Data akan di-soft delete (tidak benar-benar terhapus dari database)

### Karyawan - Melihat Informasi

1. Login ke dashboard karyawan
2. Banner informasi akan muncul otomatis jika ada informasi baru
3. Klik di mana saja pada banner untuk menutup
4. Jika ada informasi lain, akan muncul secara berurutan
5. Informasi yang sudah ditutup tidak akan muncul lagi

## 🔌 API Endpoints

### GET `/api/informasi/unread`
Mengambil semua informasi yang belum dibaca oleh user saat ini.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "Pengumuman Libur Lebaran",
      "konten": "...",
      "tipe": "banner",
      "banner_url": "http://domain.com/storage/informasi/banners/xxx.jpg",
      "link_url": null,
      "priority": 10
    }
  ]
}
```

### GET `/api/informasi/all`
Mengambil semua informasi aktif (read & unread).

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "judul": "...",
      "konten": "...",
      "tipe": "banner",
      "banner_url": "...",
      "link_url": null,
      "priority": 10,
      "is_read": true
    }
  ]
}
```

### POST `/api/informasi/{id}/mark-read`
Menandai informasi sebagai sudah dibaca.

**Response:**
```json
{
  "success": true,
  "message": "Informasi ditandai sebagai sudah dibaca"
}
```

## 🛠️ Technical Details

### Files Created/Modified

**Models:**
- `app/Models/Informasi.php`
- `app/Models/InformasiRead.php`

**Controllers:**
- `app/Http/Controllers/Admin/InformasiController.php`
- `app/Http/Controllers/Api/InformasiApiController.php`

**Views:**
- `resources/views/admin/informasi/index.blade.php`
- `resources/views/admin/informasi/create.blade.php`
- `resources/views/admin/informasi/edit.blade.php`
- `resources/views/components/informasi-banner.blade.php`

**Migrations:**
- `database/migrations/2025_11_19_220633_create_informasi_table.php`
- `database/migrations/2025_11_19_220648_create_informasi_reads_table.php`

**Routes:**
- `routes/web.php` - Admin CRUD routes
- `routes/api.php` - API routes for karyawan

**Modified Files:**
- `resources/views/layouts/sidebar.blade.php` - Added menu
- `resources/views/layouts/mobile/app.blade.php` - Added CSRF token
- `resources/views/dashboard/karyawan.blade.php` - Included banner component

### Storage Directory
Upload banner disimpan di: `storage/app/public/informasi/banners/`

Accessible via: `public/storage/informasi/banners/`

## 🎨 Features

1. **Soft Delete**: Data tidak benar-benar dihapus
2. **Priority System**: Urutan tampil berdasarkan prioritas
3. **Period Control**: Set periode aktif informasi
4. **Read Tracking**: Track siapa saja yang sudah baca
5. **Multiple Types**: Support banner, text, dan link
6. **Responsive Design**: Mobile-friendly
7. **Auto-show Banner**: Otomatis muncul saat login
8. **Sequential Display**: Multiple banner muncul berurutan
9. **No Duplicate**: Sekali baca, tidak muncul lagi

## 🔒 Security & Permissions

- Menu admin hanya untuk role **Super Admin**
- API menggunakan auth middleware (session-based)
- File upload validation (type, size)
- CSRF protection
- XSS protection via Laravel Blade

## 📱 Mobile Compatibility

- Responsive design untuk mobile
- Touch-friendly UI
- Optimized banner size untuk mobile screen
- Smooth animations

## 🐛 Troubleshooting

### Banner tidak muncul di karyawan?
1. Pastikan informasi berstatus **Aktif**
2. Cek periode tanggal mulai/selesai
3. Pastikan user belum membaca informasi tersebut
4. Cek console browser untuk error JavaScript

### Upload banner gagal?
1. Pastikan file max 2MB
2. Format harus JPG, PNG, atau GIF
3. Pastikan storage link sudah dibuat: `php artisan storage:link`
4. Cek permission folder `storage/app/public`

### Error 419 (CSRF Token)?
1. Pastikan meta CSRF token ada di layout
2. Clear browser cache
3. Logout dan login ulang

## 📊 Future Enhancements (Optional)

- [ ] Filter karyawan per departemen/cabang
- [ ] Schedule publish otomatis
- [ ] Rich text editor untuk konten
- [ ] Multiple banner upload
- [ ] Video support
- [ ] Push notification integration
- [ ] Analytics dashboard
- [ ] Export report pembaca

## 📞 Support

Untuk bantuan atau pertanyaan, hubungi tim development.

---

**Version:** 1.0.0  
**Last Updated:** 19 November 2025  
**Created by:** AI Assistant
