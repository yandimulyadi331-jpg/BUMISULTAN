# DOKUMENTASI FITUR TEMUAN

## Ringkasan
Fitur Temuan adalah sistem pelaporan terpusat untuk mengelola masalah atau kerusakan yang ditemukan oleh karyawan di lapangan. Admin dapat memantau, memproses, dan menindaklanjuti setiap laporan temuan secara real-time.

---

## Alur Sistem Temuan

### 1. Karyawan Membuat Laporan
- Karyawan membuka menu di mode karyawan: **Temuan** → **Lapor Temuan Baru**
- Mengisi form dengan:
  - **Judul Temuan**: Nama singkat masalah (contoh: "Kebocoran Plafon")
  - **Deskripsi Lengkap**: Detail kondisi kerusakan dan dampaknya
  - **Lokasi**: Tempat temuan ditemukan (contoh: "Gedung 2, Lantai 3, Ruang Rapat")
  - **Tingkat Urgensi**: Rendah / Sedang / Tinggi / Kritis
  - **Foto Bukti** (opsional): Upload bukti visual kerusakan
- Data otomatis disimpan dengan status **Baru** dan tanggal temuan dicatat

### 2. Admin Menerima & Memantau
- Admin masuk ke menu **Temuan** di sidebar
- Melihat dashboard dengan statistik:
  - Total Temuan
  - Temuan Baru
  - Sedang Diproses
  - Selesai
  - Kritis
- Daftar semua temuan ditampilkan dalam tabel dengan opsi filter

### 3. Admin Memproses Temuan
- Klik **Lihat Detail** untuk membuka laporan temuan
- Update status menjadi:
  - **Baru** → Awal laporan diterima
  - **Sedang Diproses** → Mulai ditangani (otomatis catat tanggal)
  - **Sudah Diperbaiki** → Perbaikan selesai
  - **Tindaklanjuti** → Perlu tindakan lebih lanjut
  - **Selesai** → Selesai penuh (otomatis catat tanggal)
- Tambahkan catatan perkembangan/hasil perbaikan
- Sistem otomatis mencatat siapa admin yang menangani

### 4. Karyawan Memantau Progress
- Karyawan bisa membuka **Daftar Laporan Saya** untuk melihat status
- Setiap laporan menampilkan:
  - Status saat ini dengan progress bar
  - Catatan dari admin
  - Timeline penanganan
  - Foto bukti temuan
- Status hanya bisa dihapus jika masih **Baru**

---

## Struktur Database

**Tabel: `temuan`**

| Kolom | Tipe | Keterangan |
|-------|------|-----------|
| id | bigint PK | ID unik temuan |
| judul | string | Judul temuan |
| deskripsi | text | Deskripsi lengkap |
| lokasi | string | Lokasi temuan |
| urgensi | enum | rendah, sedang, tinggi, kritis |
| status | enum | baru, sedang_diproses, sudah_diperbaiki, tindaklanjuti, selesai |
| foto_path | text | Path file foto |
| user_id | bigint FK | User yang melaporkan |
| admin_id | bigint FK | Admin yang menangani |
| catatan_admin | text | Catatan dari admin |
| tanggal_temuan | timestamp | Waktu temuan dilaporkan |
| tanggal_ditindaklanjuti | timestamp | Waktu mulai ditangani |
| tanggal_selesai | timestamp | Waktu perbaikan selesai |
| created_at | timestamp | Waktu dibuat |
| updated_at | timestamp | Waktu diupdate |

---

## File-File Implementasi

### Backend
- **Migration**: `database/migrations/2025_12_03_000001_create_temuan_table.php`
  - Membuat tabel temuan dengan indeks dan foreign keys
  
- **Model**: `app/Models/Temuan.php`
  - Eloquent model dengan relationships
  - Method helpers: `getStatusLabel()`, `getUrgensiLabel()`, badge colors
  - Scopes: `aktif()`, `selesai()`, `byStatus()`, `urgensi()`

- **Controller**: `app/Http/Controllers/TemuanController.php`
  - Admin: `index()`, `show()`, `updateStatus()`, `destroy()`
  - Karyawan: `create()`, `store()`, `karyawanList()`, `karyawanShow()`, `karyawanDestroy()`
  - API: `apiSummary()`, `exportPdf()`

### Frontend - Views Admin
- **Index**: `resources/views/temuan/index.blade.php`
  - Dashboard dengan statistik
  - Tabel daftar temuan
  - Filter berdasarkan status, urgensi, search
  - Pagination

- **Show**: `resources/views/temuan/show.blade.php`
  - Detail lengkap temuan
  - Form update status dengan catatan
  - Timeline riwayat penanganan
  - Info admin yang menangani

- **PDF**: `resources/views/temuan/pdf.blade.php`
  - Export laporan dalam format PDF
  - Termasuk statistik dan tabel lengkap

### Frontend - Views Karyawan
- **Create**: `resources/views/temuan/karyawan/create.blade.php`
  - Form lapor temuan
  - Upload foto dengan preview
  - Tips membuat laporan yang baik

- **List**: `resources/views/temuan/karyawan/list.blade.php`
  - Daftar laporan yang telah dibuat
  - Filter berdasarkan status
  - Card view dengan info ringkas

- **Show**: `resources/views/temuan/karyawan/show.blade.php`
  - Detail laporan yang dibuat
  - Status progress dengan timeline
  - Catatan dari admin
  - Info admin yang menangani

### Routes
- **Admin Routes** (`routes/web.php` lines 1715-1722):
  ```php
  Route::middleware('role:super admin')->prefix('temuan')->name('temuan.')...
  - GET /temuan → index
  - GET /temuan/{id} → show
  - PUT /temuan/{id}/status → updateStatus
  - DELETE /temuan/{id} → destroy
  - GET /temuan/api/summary → apiSummary
  - GET /temuan/export/pdf → exportPdf
  ```

- **Karyawan Routes** (`routes/web.php` lines 1725-1733):
  ```php
  Route::middleware('auth')->prefix('temuan/karyawan')...
  - GET /temuan/karyawan/create → create
  - POST /temuan/karyawan/store → store
  - GET /temuan/karyawan/list → karyawanList
  - GET /temuan/karyawan/{id} → karyawanShow
  - DELETE /temuan/karyawan/{id} → karyawanDestroy
  ```

### Navigation
- **Sidebar**: `resources/views/layouts/sidebar.blade.php` (lines 491-497)
  - Menu "Temuan" ditambahkan setelah "Manajemen Perawatan"
  - Icon: `ti-alert-circle`
  - Link ke `temuan.index`

---

## Fitur Detail

### Status Management
1. **Baru** - Laporan baru diterima, menunggu ditinjau
2. **Sedang Diproses** - Admin mulai menangani, sistem otomatis mencatat waktu
3. **Sudah Diperbaiki** - Perbaikan telah dilakukan
4. **Tindaklanjuti** - Perlu tindakan lebih lanjut
5. **Selesai** - Perbaikan selesai, sistem otomatis mencatat waktu

### Urgensi Levels
- **Rendah** (hijau): Tidak mengganggu operasional
- **Sedang** (kuning): Sedikit mengganggu operasional
- **Tinggi** (merah): Sangat mengganggu operasional
- **Kritis** (gelap merah): Membahayakan keselamatan

### Fitur Tambahan
- **Real-time Statistics**: Dashboard update otomatis via AJAX
- **Filter & Search**: Filter berdasarkan status, urgensi, search judul/lokasi
- **Foto Management**: Upload dan display foto bukti
- **Export PDF**: Laporan dapat di-export dalam format PDF
- **Timeline Visualization**: Riwayat penanganan ditampilkan dalam timeline interaktif
- **User Tracking**: Sistem mencatat siapa yang melaporkan dan siapa yang menangani

---

## Instruksi Setup & Migrasi

### 1. Jalankan Migration
```bash
php artisan migrate
```

### 2. Pastikan Folder Penyimpanan Ada
```bash
php artisan storage:link
```

### 3. Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### 4. Akses di Browser
- **Admin**: `http://localhost:8000/temuan`
- **Karyawan**: `http://localhost:8000/temuan/karyawan/create`

---

## Permission & Authorization

### Admin Access
- Hanya user dengan role **super admin** yang bisa:
  - Melihat daftar semua temuan
  - Melihat detail temuan
  - Update status temuan
  - Delete temuan
  - Export PDF laporan

### Karyawan Access
- User yang authenticated bisa:
  - Membuat laporan temuan baru
  - Melihat laporan yang mereka buat
  - Melihat detail laporan mereka
  - Delete laporan jika masih status "Baru"
  - Tidak bisa lihat laporan orang lain

---

## Testing Checklist

- [ ] Karyawan bisa membuat laporan temuan
- [ ] Foto terupload dan tersimpan dengan baik
- [ ] Admin bisa melihat daftar semua temuan
- [ ] Admin bisa filter berdasarkan status dan urgensi
- [ ] Admin bisa update status temuan
- [ ] Tanggal otomatis tercatat ketika status berubah
- [ ] Karyawan bisa melihat progress laporan mereka
- [ ] Timeline menampilkan dengan benar
- [ ] PDF export berfungsi dengan baik
- [ ] Dashboard statistics update otomatis
- [ ] Delete hanya bekerja untuk status "Baru" di karyawan
- [ ] Sidebar menu muncul dan link bekerja

---

## Troubleshooting

### Foto tidak tersimpan
- Pastikan `php artisan storage:link` sudah dijalankan
- Check permission folder `storage/app/public`

### Route not found
- Jalankan `php artisan route:clear`
- Pastikan import TemuanController di routes/web.php

### AJAX dashboard tidak update
- Check browser console untuk error
- Pastikan route `temuan.apiSummary` accessible

### Foreign key constraint error saat delete
- Pastikan user yang dihapus tidak memiliki temuan
- Atau update kolom user_id terlebih dahulu

---

## Future Enhancements

1. **Notifikasi**: Email/WhatsApp notifikasi ke karyawan saat status berubah
2. **Assignment**: Admin assign temuan ke teknisi tertentu
3. **SLA Tracking**: Monitor SLA penyelesaian berdasarkan urgensi
4. **Analytics**: Dashboard analytics untuk KPI perawatan
5. **Mobile**: Fitur lapor dari mobile app dengan geolocation
6. **Attachment**: Support upload multiple photos/dokumen
7. **Comments**: Thread komentar antara karyawan dan admin
8. **Automation**: Auto-close setelah N hari jika tidak ada update

---

## Kontak & Support

Untuk pertanyaan atau issues, hubungi tim development.

---

**Versi**: 1.0  
**Last Updated**: 3 Desember 2025  
**Status**: Live Production
