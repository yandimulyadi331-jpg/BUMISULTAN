# QUICK REFERENCE - DISTRIBUSI HADIAH YAYASAN MASAR

## 🚀 Quick Start

### Akses Fitur
- **Admin**: `https://app.local/masar/distribusi`
- **Karyawan**: `https://app.local/masar-karyawan/distribusi`

### Fitur Utama
1. ✅ Daftar distribusi dengan filter
2. ✅ Tambah distribusi baru
3. ✅ Edit distribusi
4. ✅ Hapus distribusi
5. ✅ Export ke PDF
6. ✅ Lihat statistik
7. ✅ Management stok hadiah otomatis

---

## 📁 File Structure

```
App Structure:
├── app/Http/Controllers/
│   └── DistribusiHadiahMasarController.php      ← Main controller
├── app/Models/
│   ├── DistribusiHadiahYayasanMasar.php         ← Already exists
│   ├── JamaahMasar.php                           ← Modified
│   └── HadiahMasar.php                           ← Modified
├── routes/
│   └── web.php                                   ← Routes updated
└── resources/views/masar/distribusi/
    ├── index.blade.php                           ← List (Admin)
    ├── create.blade.php                          ← Create form
    ├── edit.blade.php                            ← Edit form
    ├── show.blade.php                            ← Detail (Admin)
    ├── karyawan-index.blade.php                  ← List (Karyawan)
    ├── karyawan-show.blade.php                   ← Detail (Karyawan)
    └── pdf.blade.php                             ← Export template
```

---

## 🔌 API Methods

### Controller Methods
```php
// Admin Methods
DistribusiHadiahMasarController::index()              // GET /masar/distribusi
DistribusiHadiahMasarController::create()             // GET /masar/distribusi/create
DistribusiHadiahMasarController::store()              // POST /masar/distribusi
DistribusiHadiahMasarController::show($id)           // GET /masar/distribusi/{id}
DistribusiHadiahMasarController::edit($id)           // GET /masar/distribusi/{id}/edit
DistribusiHadiahMasarController::update($id)         // PUT /masar/distribusi/{id}
DistribusiHadiahMasarController::destroy($id)        // DELETE /masar/distribusi/{id}
DistribusiHadiahMasarController::exportPDF()         // GET /masar/distribusi/export/pdf
DistribusiHadiahMasarController::getStatistik()      // GET /masar/distribusi/statistik/get

// Karyawan Methods
DistribusiHadiahMasarController::distribusiKaryawan()        // GET /masar-karyawan/distribusi
DistribusiHadiahMasarController::showDistribusiKaryawan()    // GET /masar-karyawan/distribusi/{id}
DistribusiHadiahMasarController::storeDistribusiKaryawan()   // POST /masar-karyawan/distribusi
```

---

## 🎯 Workflow Example

### Catat Distribusi (Admin)
```
1. Klik "Tambah Distribusi" di /masar/distribusi
2. Pilih hadiah dari dropdown
3. Lihat stok otomatis update di sidebar
4. Isi jumlah, tanggal, metode, penerima
5. Klik "Simpan Distribusi"
6. Sistem auto-generate nomor distribusi (DSY-021225-0001)
7. Stok hadiah otomatis berkurang
8. Data tersimpan dan bisa diedit/dihapus
```

### Catat Distribusi (Karyawan)
```
1. Klik "Catat Distribusi" di /masar-karyawan/distribusi
2. Isi form lengkap
3. Klik "Simpan"
4. Selesai - data terlihat di list
```

### Export Laporan
```
1. Di /masar/distribusi, ada "Export PDF" button
2. Opsional: set filter dahulu
3. Klik "Export PDF"
4. File PDF terdownload
5. Buka di PDF viewer
```

---

## 📊 Field Mapping

| Field | Type | Rules | Notes |
|-------|------|-------|-------|
| nomor_distribusi | String | Auto-generated | Format: DSY-DDMMYY-XXXX |
| hadiah_id | FK | Required | Dari tabel hadiah_masar |
| jamaah_id | FK | Optional | Dari tabel yayasan_masar |
| tanggal_distribusi | Date | Required | Format: YYYY-MM-DD |
| jumlah | Integer | Required, min:1 | Jumlah hadiah |
| ukuran | String | Optional | S, M, L, XL, dll |
| metode_distribusi | Enum | Required | langsung, undian, prestasi, kehadiran |
| penerima | String | Required, max:100 | Nama penerima |
| petugas_distribusi | String | Optional | Nama petugas |
| status_distribusi | Enum | Required | pending, diterima, ditolak |
| keterangan | Text | Optional | Catatan tambahan |

---

## 🔒 Security Features

- ✅ CSRF Token Protection
- ✅ Encrypted ID Routing
- ✅ Form Validation (Server-side)
- ✅ Soft Delete (Data Preservation)
- ✅ Role-based Access Control
- ✅ Activity Logging

---

## ⚡ Performance Features

- ✅ DataTables Server-side Processing
- ✅ Database Indexing
- ✅ Eager Loading (with relationships)
- ✅ Lazy Loading Option
- ✅ Query Optimization

---

## 🎨 UI Components Used

- Bootstrap 5 Grid System
- Tabler CSS Framework
- DataTables jQuery Plugin
- SweetAlert2 for Confirmations
- jQuery for AJAX
- Font Awesome Icons (ti-*)

---

## 🐛 Troubleshooting

### Issue: Nomor distribusi tidak generate
**Solution**: Pastikan migration sudah dijalankan:
```bash
php artisan migrate
```

### Issue: Stok tidak berkurang
**Solution**: Pastikan status_distribusi = 'diterima'

### Issue: Form tidak submit
**Solution**: Check browser console untuk AJAX errors, pastikan CSRF token valid

### Issue: PDF tidak bisa didownload
**Solution**: Pastikan DomPDF installed:
```bash
composer require barryvdh/laravel-dompdf
```

---

## 📋 Database Tables

### Main Table: `distribusi_hadiah_yayasan_masar`
```sql
-- Fields
id, nomor_distribusi, hadiah_id, jamaah_id, tanggal_distribusi,
jumlah, ukuran, ukuran_breakdown, metode_distribusi, penerima,
petugas_distribusi, status_distribusi, keterangan,
created_at, updated_at, deleted_at

-- Indexes
PRIMARY KEY (id)
UNIQUE (nomor_distribusi)
INDEX (jamaah_id, hadiah_id)
INDEX (tanggal_distribusi)
INDEX (nomor_distribusi)
```

---

## 🔗 Related Models

```php
// DistribusiHadiahYayasanMasar.php
public function hadiah() 
  → belongsTo(HadiahMasar)
  
public function jamaah() 
  → belongsTo(YayasanMasar)

// HadiahMasar.php
public function distribusiYayasan() 
  → hasMany(DistribusiHadiahYayasanMasar)

// JamaahMasar.php
public function distribusiHadiahYayasan() 
  → hasMany(DistribusiHadiahYayasanMasar)
```

---

## 🧪 Test Endpoints

### List Distribusi (DataTables)
```
GET /masar/distribusi
Parameters: search, metode_distribusi, status_distribusi, tanggal_dari, tanggal_sampai
```

### Create Distribusi
```
POST /masar/distribusi
Body: {hadiah_id, jamaah_id, tanggal_distribusi, jumlah, metode_distribusi, 
       penerima, petugas_distribusi, status_distribusi, ukuran, keterangan}
```

### Get Statistik
```
GET /masar/distribusi/statistik/get
Response: {total_distribusi, total_diterima, total_pending, total_ditolak, 
           per_metode, per_bulan}
```

### Export PDF
```
GET /masar/distribusi/export/pdf
Parameters: hadiah_id, status_distribusi, tanggal_dari, tanggal_sampai
```

---

## 📞 Contact & Support

Untuk pertanyaan atau issue:
1. Check DOKUMENTASI_DISTRIBUSI_HADIAH_MASAR.md untuk penjelasan lengkap
2. Check RINGKASAN_PENGEMBANGAN_DISTRIBUSI_HADIAH.md untuk overview
3. Check code comments di controller untuk logic detail

---

**Last Updated**: 2 Desember 2025  
**Version**: 1.0 (Release)  
**Status**: ✅ Production Ready
