# 🚀 QUICK START - MANAJEMEN PERAWATAN GEDUNG

## ⚡ Setup Cepat (5 Menit)

### 1. Migration & Seeder
```bash
# Jalankan migration
php artisan migrate

# Isi data contoh (opsional)
php artisan db:seed --class=MasterPerawatanSeeder
```

### 2. Akses Menu
1. Login sebagai **Super Admin**
2. Buka sidebar → **Manajemen Perawatan** (di bawah Manajemen Pinjaman)
3. Done! ✅

---

## 📋 Workflow Sederhana

### ADMIN: Buat Template Checklist
```
1. Buka: Manajemen Perawatan → Master Checklist
2. Klik: Tambah Checklist
3. Isi:
   - Nama: "Buang Sampah Ruang Tamu"
   - Periode: Harian
   - Kategori: Kebersihan
4. Simpan
```

### KARYAWAN: Kerjakan Checklist
```
1. Buka: Manajemen Perawatan → Checklist Harian
2. Centang setiap kegiatan yang sudah selesai
3. Tunggu sampai semua checklist selesai (100%)
4. Klik: Generate Laporan
5. Download PDF otomatis
```

### HASIL:
- ✅ Semua kegiatan tercatat dengan timestamp
- ✅ History tersimpan permanent
- ✅ Laporan PDF tersedia untuk download
- ✅ Besok checklist reset otomatis, siap dikerjakan lagi

---

## 🔑 Fitur Utama

| Fitur | Deskripsi |
|-------|-----------|
| **4 Periode** | Harian, Mingguan, Bulanan, Tahunan |
| **Auto-Reset** | Checklist reset otomatis sesuai periode |
| **History Lengkap** | Data TIDAK PERNAH dihapus |
| **Validasi Ketat** | Semua harus selesai sebelum generate laporan |
| **PDF Report** | Laporan detail dengan tanda tangan digital |
| **Kategori** | Kebersihan, Perawatan Rutin, Pengecekan, Lainnya |
| **Optional** | Bisa tambah catatan & foto bukti |

---

## 🎯 Reset Schedule

| Periode | Reset Kapan | Contoh Key |
|---------|-------------|------------|
| Harian | Setiap 00:00 | harian_2024-11-14 |
| Mingguan | Setiap Senin 00:00 | mingguan_2024-W46 |
| Bulanan | Setiap tanggal 1, 00:00 | bulanan_2024-11 |
| Tahunan | Setiap 1 Jan, 00:00 | tahunan_2024 |

**⚠️ PENTING:** Data lama tetap tersimpan, hanya status yang di-reset!

---

## 📁 File Struktur

```
app/
├── Http/Controllers/
│   └── ManajemenPerawatanController.php
├── Models/
│   ├── MasterPerawatan.php
│   ├── PerawatanLog.php
│   ├── PerawatanLaporan.php
│   └── PerawatanStatusPeriode.php

database/
├── migrations/
│   └── 2024_11_14_create_manajemen_perawatan_tables.php
└── seeders/
    └── MasterPerawatanSeeder.php

resources/views/perawatan/
├── index.blade.php (Dashboard)
├── checklist.blade.php (Eksekusi)
├── master/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
└── laporan/
    ├── index.blade.php
    └── pdf.blade.php
```

---

## 🔗 Routes

```php
// Dashboard
GET /perawatan

// Master Checklist (Admin)
GET /perawatan/master
GET /perawatan/master/create
POST /perawatan/master/store
GET /perawatan/master/{id}/edit
PUT /perawatan/master/{id}
DELETE /perawatan/master/{id}

// Eksekusi Checklist
GET /perawatan/checklist/harian
GET /perawatan/checklist/mingguan
GET /perawatan/checklist/bulanan
GET /perawatan/checklist/tahunan
POST /perawatan/checklist/execute
POST /perawatan/checklist/uncheck

// Laporan
GET /perawatan/laporan
POST /perawatan/laporan/generate
GET /perawatan/laporan/{id}/download
```

---

## 💡 Tips

### Admin:
- Buat checklist yang spesifik & mudah dipahami
- Gunakan urutan untuk sorting (1, 2, 3...)
- Nonaktifkan checklist yang tidak relevan (jangan hapus)

### Karyawan:
- Centang SETELAH kegiatan selesai
- Tambah catatan jika ada hal penting
- Generate laporan sebelum pulang kerja

### Developer:
- Data di `perawatan_log` PERMANENT (jangan hapus!)
- Index ada di `periode_key` untuk query cepat
- PDF disimpan di `storage/app/public/perawatan/laporan/`

---

## ❓ FAQ

**Q: Checklist hilang setelah ganti hari?**  
A: BUKAN bug! Data lama di `perawatan_log`, checklist baru untuk hari baru.

**Q: Bisa edit checklist setelah dicentang?**  
A: Bisa uncheck (batalkan), tapi sebaiknya tidak jika sudah generate laporan.

**Q: Laporan bisa di-generate ulang?**  
A: 1 periode = 1 laporan (unique). Tapi bisa download berkali-kali.

**Q: Data aman?**  
A: 100% aman! History lengkap tersimpan permanent.

---

## 📞 Butuh Bantuan?

1. Baca **DOKUMENTASI_MANAJEMEN_PERAWATAN.md** (dokumentasi lengkap)
2. Cek code di `ManajemenPerawatanController.php`
3. Hubungi developer

---

**Bismillah! Semoga gedung selalu bersih dan terawat! 🏢✨**

*Last Updated: 14 Nov 2024*
