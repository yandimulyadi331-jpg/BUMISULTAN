# QUICK START - FITUR TEMUAN

## ⚡ Setup Cepat (5 menit)

### Step 1: Jalankan Migration
```bash
php artisan migrate
```

### Step 2: Setup Storage
```bash
php artisan storage:link
```

### Step 3: Clear Cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### Step 4: Buka di Browser
✅ Admin: `http://localhost:8000/temuan`  
✅ Karyawan: `http://localhost:8000/temuan/karyawan/create`

---

## 📋 Akses Menu

### Untuk Admin (Super Admin)
1. Login sebagai super admin
2. Cari menu **Temuan** di sidebar (di bawah Manajemen Perawatan)
3. Klik untuk membuka dashboard temuan

### Untuk Karyawan
1. Login sebagai karyawan
2. Buka link: `/temuan/karyawan/create` untuk membuat laporan baru
3. Buka link: `/temuan/karyawan/list` untuk melihat daftar laporan

---

## 🔧 File-File Yang Ditambahkan

### Database
```
database/migrations/2025_12_03_000001_create_temuan_table.php
```

### Models
```
app/Models/Temuan.php
```

### Controllers
```
app/Http/Controllers/TemuanController.php
```

### Routes
```
routes/web.php (ditambahkan import + routes group)
```

### Views Admin
```
resources/views/temuan/
├── index.blade.php         (Dashboard admin)
├── show.blade.php          (Detail temuan)
└── pdf.blade.php           (Export PDF)
```

### Views Karyawan
```
resources/views/temuan/karyawan/
├── create.blade.php        (Form lapor)
├── list.blade.php          (Daftar laporan)
└── show.blade.php          (Detail laporan)
```

### Navigation
```
resources/views/layouts/sidebar.blade.php (ditambahkan menu item)
```

---

## 📱 Fitur Utama

### Admin Dashboard
- ✅ Statistik real-time (Total, Baru, Diproses, Selesai, Kritis)
- ✅ Tabel daftar dengan filter status/urgensi/search
- ✅ View detail temuan dengan foto
- ✅ Update status + catatan perbaikan
- ✅ Timeline riwayat
- ✅ Export PDF laporan
- ✅ Delete temuan

### Karyawan Form
- ✅ Input judul, deskripsi, lokasi
- ✅ Pilih urgensi (Rendah/Sedang/Tinggi/Kritis)
- ✅ Upload foto bukti
- ✅ Preview foto sebelum submit

### Karyawan Monitoring
- ✅ Lihat daftar laporan yang dibuat
- ✅ Filter berdasarkan status
- ✅ View detail laporan
- ✅ Lihat progress dengan timeline
- ✅ Baca catatan dari admin
- ✅ Delete laporan (hanya jika status "Baru")

---

## 🗄️ Database Schema

```sql
CREATE TABLE temuan (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    judul VARCHAR(255),
    deskripsi TEXT,
    lokasi VARCHAR(255),
    urgensi ENUM('rendah', 'sedang', 'tinggi', 'kritis'),
    status ENUM('baru', 'sedang_diproses', 'sudah_diperbaiki', 'tindaklanjuti', 'selesai'),
    foto_path TEXT,
    user_id BIGINT (FK users),
    admin_id BIGINT (FK users),
    catatan_admin TEXT,
    tanggal_temuan TIMESTAMP,
    tanggal_ditindaklanjuti TIMESTAMP,
    tanggal_selesai TIMESTAMP,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

---

## 🔐 Authorization

| Action | Role | Kondisi |
|--------|------|---------|
| Lihat daftar temuan | Super Admin | - |
| Lihat detail temuan | Super Admin | - |
| Update status | Super Admin | - |
| Delete temuan | Super Admin | - |
| Export PDF | Super Admin | - |
| Buat laporan | Semua User | Authenticated |
| Lihat laporan sendiri | Semua User | Milik user tersebut |
| Delete laporan | Semua User | Status = "Baru" |

---

## 🚀 Routes yang Tersedia

### Admin
```
GET  /temuan                  # Daftar temuan
GET  /temuan/{id}             # Detail temuan
PUT  /temuan/{id}/status      # Update status
DELETE /temuan/{id}           # Delete temuan
GET  /temuan/api/summary      # API statistics
GET  /temuan/export/pdf       # Export PDF
```

### Karyawan
```
GET  /temuan/karyawan/create           # Form buat laporan
POST /temuan/karyawan/store            # Submit laporan
GET  /temuan/karyawan/list             # Daftar laporan saya
GET  /temuan/karyawan/{id}             # Detail laporan saya
DELETE /temuan/karyawan/{id}           # Delete laporan saya
```

---

## 🧪 Test Scenarios

### Test 1: Karyawan Membuat Laporan
1. Login sebagai karyawan
2. Buka `/temuan/karyawan/create`
3. Isi form dengan data
4. Upload foto
5. Klik "Kirim Laporan"
6. ✅ Seharusnya redirect ke daftar dengan pesan sukses

### Test 2: Admin Memproses
1. Login sebagai admin
2. Buka `/temuan`
3. Lihat laporan dari step 1
4. Klik "Lihat Detail"
5. Update status ke "Sedang Diproses"
6. Tambah catatan
7. Klik "Simpan Perubahan"
8. ✅ Seharusnya tersimpan dan tanggal_ditindaklanjuti terupdate

### Test 3: Karyawan Monitor
1. Login sebagai karyawan (sama seperti step 1)
2. Buka `/temuan/karyawan/list`
3. Lihat laporan dengan status "Sedang Diproses"
4. Klik "Lihat Detail"
5. ✅ Seharusnya melihat timeline dan catatan dari admin

### Test 4: Export PDF
1. Login sebagai admin
2. Buka `/temuan`
3. Klik "Export PDF"
4. ✅ Seharusnya download file PDF dengan data laporan

---

## ⚙️ Konfigurasi Tambahan (Opsional)

### Setup Notifikasi Email (Future)
File: `app/Mail/TemuanNotification.php`

### Setup WhatsApp Notification (Future)
File: `app/Services/TemuanService.php`

### Customize Template (Optional)
- Edit views di `resources/views/temuan/`
- Edit CSS di blade files sesuai kebutuhan

---

## 🔍 Debugging Tips

### Foto tidak tersimpan?
```bash
# Check folder permissions
chmod 777 storage/app/public

# Verify storage link
php artisan storage:link

# Check .env FILE_DRIVER
# Pastikan: FILESYSTEM_DRIVER=public
```

### Route 404?
```bash
# Clear route cache
php artisan route:clear

# Verify routes
php artisan route:list | grep temuan
```

### SQL Error?
```bash
# Fresh migration (development only!)
php artisan migrate:refresh

# Check migration status
php artisan migrate:status
```

### AJAX Not Working?
```javascript
// Check browser console for errors
// Verify route: GET /temuan/api/summary
// Should return JSON with statistics
```

---

## 📞 Support

Jika ada pertanyaan atau error:
1. Check dokumentasi di `DOKUMENTASI_FITUR_TEMUAN.md`
2. Review file yang relevan di folder yang terdaftar
3. Check laravel logs di `storage/logs/`
4. Contact development team

---

**Quick Reference**
- Admin Dashboard: `/temuan`
- Karyawan Buat Laporan: `/temuan/karyawan/create`
- Karyawan Lihat Daftar: `/temuan/karyawan/list`
- Database: Table `temuan`
- Model: `App\Models\Temuan`
- Controller: `App\Http\Controllers\TemuanController`

---

**Setup Date**: 3 Desember 2025  
**Status**: ✅ Ready for Production
