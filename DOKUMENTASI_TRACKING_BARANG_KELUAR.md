# DOKUMENTASI SISTEM TRACKING BARANG KELUAR

## 📋 DESKRIPSI SISTEM

Sistem Tracking Barang Keluar adalah solusi komprehensif untuk mengelola dan melacak barang yang keluar dari perusahaan untuk dijasakan di luar, seperti:
- 🧺 Laundry pakaian/seragam
- 👟 Perbaikan sepatu
- 🔧 Perbaikan elektronik
- 🪑 Perbaikan furniture
- 👔 Jahit/servis pakaian
- 🚗 Reparasi kendaraan
- Dan jasa lainnya

## 🎯 FITUR UTAMA

### 1. **Tracking Lengkap**
- Kode transaksi unik untuk setiap barang
- Status tracking real-time (Pending → Dikirim → Proses → Selesai Vendor → Diambil)
- Timeline lengkap dari keluar hingga kembali
- Notifikasi terlambat otomatis

### 2. **Dokumentasi Foto**
- Foto sebelum barang keluar (multiple upload)
- Foto sesudah barang kembali (multiple upload)
- Foto nota/bukti pembayaran
- Foto dokumentasi setiap perubahan status

### 3. **Manajemen Vendor**
- Database vendor/tempat jasa
- Rating dan review vendor (1-5 bintang)
- Kontak dan alamat vendor lengkap
- History transaksi per vendor

### 4. **Manajemen Biaya**
- Estimasi biaya awal
- Biaya aktual setelah selesai
- Upload foto nota pembayaran
- Tracking selisih biaya

### 5. **System Prioritas**
- Rendah: Item non-urgent
- Normal: Item standar
- Tinggi: Item penting
- Urgent: Item sangat mendesak

### 6. **History & Audit Trail**
- Riwayat lengkap setiap perubahan status
- Catatan user yang melakukan update
- Timestamp setiap aktivitas
- Foto dokumentasi per perubahan

### 7. **Laporan & Statistik**
- Dashboard statistik real-time
- Filter berdasarkan status, jenis, vendor, tanggal
- Export PDF untuk laporan
- Top vendors by transaction

## 📊 DATABASE SCHEMA

### Tabel: `barang_keluar`
Tabel utama untuk menyimpan data barang keluar

**Kolom Utama:**
- `id` - Primary key
- `kode_transaksi` - Kode unik (BK20241229XXXX)
- `jenis_barang` - Jenis barang (laundry, perbaikan, dll)
- `nama_barang` - Nama/deskripsi barang
- `jumlah` & `satuan` - Quantity barang
- `pemilik_barang` - Nama pemilik
- `departemen` - Departemen pemilik
- `nama_vendor` - Vendor/tempat jasa
- `tanggal_keluar` - Kapan barang keluar
- `estimasi_kembali` - Estimasi tanggal kembali
- `tanggal_kembali` - Tanggal aktual kembali
- `status` - Status saat ini (enum)
- `prioritas` - Tingkat prioritas (enum)
- `estimasi_biaya` & `biaya_aktual` - Tracking biaya
- `foto_sebelum`, `foto_sesudah`, `foto_nota` - Dokumentasi
- `kondisi_keluar` & `kondisi_kembali` - Kondisi barang
- `rating_vendor` & `review_vendor` - Rating vendor
- `created_by`, `updated_by`, `diambil_by` - User tracking

### Tabel: `barang_keluar_history`
Tabel untuk menyimpan riwayat perubahan status

**Kolom:**
- `barang_keluar_id` - Foreign key ke barang_keluar
- `status_dari` - Status sebelumnya
- `status_ke` - Status baru
- `catatan` - Catatan perubahan
- `foto` - Foto pendukung (JSON array)
- `user_id` - User yang melakukan update

### Tabel: `barang_keluar_reminder`
Tabel untuk reminder/notifikasi

**Kolom:**
- `barang_keluar_id` - Foreign key
- `tanggal_reminder` - Kapan reminder dikirim
- `pesan_reminder` - Isi pesan
- `tipe_reminder` - Email/WA/Notifikasi
- `sudah_terkirim` - Status pengiriman

## 🛠️ STRUKTUR FILE

```
app/
├── Http/Controllers/
│   └── BarangKeluarController.php          # Main controller
├── Models/
│   ├── BarangKeluar.php                    # Main model
│   ├── BarangKeluarHistory.php             # History model
│   └── BarangKeluarReminder.php            # Reminder model

database/migrations/
└── 2025_12_29_100000_create_barang_keluar_table.php

resources/views/barang-keluar/
├── index.blade.php                          # List view
├── create.blade.php                         # Form tambah
├── edit.blade.php                           # Form edit
└── show.blade.php                           # Detail view

routes/
└── web.php                                  # Routes definition
```

## 🚀 CARA PENGGUNAAN

### 1. Instalasi & Setup

```bash
# Jalankan migration
php artisan migrate

# (Optional) Seed data sample
php artisan db:seed --class=BarangKeluarSeeder
```

### 2. Akses Menu
- Login sebagai Super Admin
- Buka sidebar menu
- Klik **"Tracking Barang Keluar"** (di bawah Manajemen Perawatan)

### 3. Menambah Barang Keluar Baru

1. Klik tombol **"Tambah Barang Keluar"**
2. Isi form:
   - **Informasi Barang**: Jenis, nama, jumlah, kondisi, prioritas
   - **Pemilik**: Nama, departemen, no telp
   - **Vendor**: Nama vendor, alamat, kontak
   - **Tanggal**: Kapan keluar, estimasi kembali
   - **Biaya**: Estimasi biaya
   - **Foto**: Upload foto kondisi barang sebelum
   - **Catatan**: Catatan tambahan
3. Klik **"Simpan Data"**

### 4. Update Status Barang

**Dari Halaman Detail:**
1. Buka detail barang keluar
2. Klik tombol **"Update Status"**
3. Pilih status baru:
   - **Pending** → Menunggu pengiriman
   - **Dikirim** → Sudah dikirim ke vendor
   - **Proses** → Sedang dikerjakan
   - **Selesai Vendor** → Sudah selesai, siap diambil
   - **Diambil** → Sudah kembali
   - **Batal** → Dibatalkan
4. Tambahkan catatan dan foto (opsional)
5. Klik **"Update Status"**

### 5. Melihat Riwayat & History

Di halaman detail, scroll ke bawah untuk melihat:
- Timeline tanggal
- Riwayat perubahan status lengkap
- Catatan setiap perubahan
- Foto dokumentasi
- User yang melakukan update

### 6. Filter & Search

Di halaman index, gunakan filter untuk:
- Status tertentu
- Jenis barang
- Prioritas
- Range tanggal
- Vendor
- Search by kode/nama

### 7. Export Laporan

Klik tombol **"Export PDF"** untuk download laporan dalam format PDF

## 📈 STATISTIK DASHBOARD

Dashboard menampilkan:
- **Total Barang**: Jumlah keseluruhan transaksi
- **Pending**: Barang yang belum dikirim
- **Dalam Proses**: Barang di vendor (dikirim + proses)
- **Selesai Vendor**: Barang siap diambil
- **Terlambat**: Barang melewati estimasi kembali

## 🔔 NOTIFIKASI TERLAMBAT

Sistem otomatis menandai barang yang:
- Sudah melewati `estimasi_kembali`
- Belum berstatus `diambil`
- Ditampilkan dengan highlight merah di list
- Badge "Terlambat X hari" ditampilkan

## 💡 TIPS PENGGUNAAN

### Best Practices:

1. **Selalu foto sebelum kirim**: Dokumentasi kondisi awal penting
2. **Set estimasi realistis**: Berdasarkan pengalaman vendor
3. **Update status real-time**: Segera update saat ada perubahan
4. **Catat nomor kontak**: Vendor dan pemilik barang
5. **Rate vendor**: Bantu evaluasi vendor terbaik
6. **Review berkala**: Cek barang terlambat setiap hari
7. **Simpan nota**: Upload foto nota untuk audit

### Shortcut Menu:

- **Filter Status**: Quick filter barang by status
- **Search**: Cari by kode/nama/vendor
- **Export**: Download laporan PDF
- **Badge Prioritas**: Warna badge indikasi prioritas
  - 🟢 Rendah
  - 🔵 Normal
  - 🟡 Tinggi
  - 🔴 Urgent

## 🔒 PERMISSION & SECURITY

### Permission Required:
- `barang-keluar.index` - View list
- `barang-keluar.create` - Tambah baru
- `barang-keluar.edit` - Edit data
- `barang-keluar.delete` - Hapus data

### Security Features:
- User tracking (created_by, updated_by)
- Soft delete (data bisa di-restore)
- Audit trail lengkap di history
- File upload validation
- CSRF protection

## 🎨 CUSTOMIZATION

### Menambah Jenis Barang Baru:

1. Edit form di `create.blade.php` dan `edit.blade.php`
2. Tambahkan option baru di dropdown `jenis_barang`

### Menambah Status Baru:

1. Edit migration `barang_keluar` table
2. Tambahkan status di enum
3. Update badge di Model `BarangKeluar`
4. Update form di views

### Custom Reminder:

Implementasi scheduler di `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Check barang terlambat setiap hari jam 9 pagi
    $schedule->call(function () {
        $terlambat = BarangKeluar::terlambat()->get();
        // Send notification...
    })->dailyAt('09:00');
}
```

## 📞 TROUBLESHOOTING

### Error Upload Foto:
- Pastikan folder `storage/app/public/barang_keluar` exists
- Jalankan `php artisan storage:link`
- Check file size < 2MB

### Tidak Bisa Akses Menu:
- Check role & permission user
- Pastikan user punya permission `barang-keluar.index`

### Kode Transaksi Duplicate:
- Auto-generated per hari
- Format: BK + YYYYMMDD + 0001
- Reset counter setiap hari

## 🔄 MAINTENANCE

### Backup Data:
```bash
# Backup database regular
php artisan backup:run

# Backup foto
zip -r barang_keluar_photos.zip storage/app/public/barang_keluar/
```

### Clean Old Files:
```bash
# Hapus foto barang yang sudah > 1 tahun
# Implement custom command
php artisan barang-keluar:cleanup-old-files
```

## 📝 CHANGELOG

### Version 1.0.0 (2024-12-29)
- ✅ Initial release
- ✅ CRUD operations
- ✅ Status tracking
- ✅ Photo upload
- ✅ History tracking
- ✅ Vendor rating
- ✅ Export PDF
- ✅ Dashboard statistics

## 👥 SUPPORT

Untuk bantuan lebih lanjut:
- Email: support@bumisultan.com
- WhatsApp: +62xxx-xxxx-xxxx
- Documentation: /docs/barang-keluar

---

**Developed by BumisultanAPP Development Team**
*Last Updated: 29 December 2024*
