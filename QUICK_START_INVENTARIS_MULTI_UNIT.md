# 🚀 QUICK START: Implementasi Sistem Inventaris Multi-Unit

## 📋 Checklist Implementasi

### ✅ Phase 1: Database & Models (COMPLETED)
- [x] Migration untuk update tabel `inventaris`
- [x] Migration untuk tabel `inventaris_units`
- [x] Migration untuk tabel `inventaris_detail_units`
- [x] Migration untuk tabel `inventaris_unit_history`
- [x] Migration untuk update `peminjaman_inventaris`
- [x] Migration untuk update `pengembalian_inventaris`
- [x] Model `InventarisUnit`
- [x] Model `InventarisDetailUnit`
- [x] Model `InventarisUnitHistory`
- [x] Update Model `Inventaris` dengan relasi baru

### 🔄 Phase 2: Jalankan Migration
```bash
# Di terminal, jalankan:
php artisan migrate

# Jika ada error, rollback dan coba lagi:
php artisan migrate:rollback
php artisan migrate
```

### 🎯 Phase 3: Update Controllers (IN PROGRESS)

#### File yang perlu dibuat/dimodifikasi:

**1. Update `InventarisController.php`**
- [ ] Tambah method `showDetail($id)` untuk halaman detail lengkap
- [ ] Update method `index()` untuk load relasi detailUnits
- [ ] Update method `store()` untuk handle tracking_per_unit

**2. Buat `InventarisDetailUnitController.php`** (NEW)
- [ ] Method `store()` - Tambah unit baru
- [ ] Method `update()` - Edit unit
- [ ] Method `destroy()` - Hapus unit
- [ ] Method `showHistory()` - Lihat history per unit

**3. Update `PeminjamanInventarisController.php`**
- [ ] Update method `create()` - Tambah pilihan unit spesifik
- [ ] Update method `store()` - Save inventaris_detail_unit_id
- [ ] Update logic untuk set unit status jadi 'dipinjam'

**4. Update `PengembalianInventarisController.php`**
- [ ] Update method `create()` - Tampilkan unit yang dipinjam
- [ ] Update method `store()` - Handle kondisi_saat_kembali & kerusakan
- [ ] Update logic untuk set unit kembali ke 'tersedia' atau 'maintenance'

### 🎨 Phase 4: Update Views

#### File yang perlu dimodifikasi:

**1. `resources/views/inventaris/index.blade.php`**
```php
// Simplify tombol aksi di tabel:
// SEBELUM: Detail | Edit | Pinjam | Kembali | History | Hapus
// SESUDAH: Detail | Edit | Hapus

<td>
    <div class="d-flex">
        <a href="{{ route('inventaris.detail', $item->id) }}" class="me-2" title="Detail">
            <i class="ti ti-eye text-info"></i>
        </a>
        <a href="#" class="me-2 btnEdit" data-id="{{ $item->id }}" title="Edit">
            <i class="ti ti-edit text-success"></i>
        </a>
        <form action="{{ route('inventaris.destroy', $item->id) }}" method="POST" class="deleteform" style="display:inline;">
            @csrf
            @method('DELETE')
            <a href="#" class="delete-confirm" title="Hapus">
                <i class="ti ti-trash text-danger"></i>
            </a>
        </form>
    </div>
</td>
```

**2. Buat `resources/views/inventaris/show-detail.blade.php`** (NEW)
Halaman detail dengan:
- Info Master Inventaris
- Tab Navigation (Units, Pinjam, Kembali, History)
- Statistics Cards (Total, Tersedia, Dipinjam, Rusak, Maintenance)

**3. Buat Partial Views:**
- [ ] `partials/tab-units.blade.php` - List semua detail units
- [ ] `partials/tab-peminjaman.blade.php` - Form & list peminjaman
- [ ] `partials/tab-pengembalian.blade.php` - Form & list pengembalian
- [ ] `partials/tab-history.blade.php` - Timeline history

**4. Buat Unit Management Modals:**
- [ ] `units/create-modal.blade.php` - Form tambah unit
- [ ] `units/edit-modal.blade.php` - Form edit unit
- [ ] `units/show-history-modal.blade.php` - History per unit

### 🛣️ Phase 5: Update Routes

**File: `routes/web.php`**

```php
// Tambahkan di dalam group middleware auth:

// Detail Inventaris (Halaman lengkap dengan tabs)
Route::get('/inventaris/{id}/detail', [InventarisController::class, 'showDetail'])
    ->name('inventaris.detail');

// Unit Management Routes
Route::prefix('inventaris/{inventaris}/units')->name('inventaris.units.')->group(function () {
    Route::post('/', [InventarisDetailUnitController::class, 'store'])->name('store');
    Route::get('/{unit}/edit', [InventarisDetailUnitController::class, 'edit'])->name('edit');
    Route::put('/{unit}', [InventarisDetailUnitController::class, 'update'])->name('update');
    Route::delete('/{unit}', [InventarisDetailUnitController::class, 'destroy'])->name('destroy');
    Route::get('/{unit}/history', [InventarisDetailUnitController::class, 'showHistory'])->name('history');
});

// Get available units untuk dropdown peminjaman
Route::get('/inventaris/{inventaris}/units-tersedia', [InventarisDetailUnitController::class, 'getUnitsTersedia'])
    ->name('inventaris.units.tersedia');
```

---

## 🧪 Testing Flow

### Test Case 1: Tambah Inventaris Multi-Unit

1. **Masuk ke Master Data Inventaris**
2. **Klik "Tambah Inventaris"**
3. **Isi Form:**
   - Nama: SENTER
   - Merk: ASAS
   - Kategori: Camping & Outdoor
   - **Tracking Per Unit: ✓ (Aktifkan)**
   - Jumlah: 10
4. **Submit**
5. **Expected Result:**
   - Master inventaris tersimpan dengan `tracking_per_unit = true`
   - Otomatis redirect ke halaman detail
   - Tampil notifikasi: "Silakan tambahkan detail unit"

### Test Case 2: Tambah Detail Units

1. **Di Halaman Detail → Tab "UNITS"**
2. **Klik "Tambah Unit"**
3. **Isi Form Modal:**
   - Jumlah Unit yang Ditambahkan: 5
   - Kondisi: Baik
   - Lokasi: Gudang A
4. **Submit**
5. **Expected Result:**
   - 5 unit baru muncul di tabel
   - Kode otomatis: INV-00001-U001 s/d U005
   - Status semua: Tersedia
   - History tercatat untuk setiap unit

### Test Case 3: Peminjaman Unit Spesifik

1. **Di Halaman Detail → Tab "PINJAM"**
2. **Isi Form Peminjaman:**
   - Nama Peminjam: Ahmad
   - Pilih Unit: INV-00001-U002 (dropdown hanya tampil unit tersedia)
   - Tanggal Pinjam: 2025-11-26
   - Keperluan: Jelajah Malam
3. **Submit**
4. **Expected Result:**
   - Peminjaman tersimpan
   - Unit U002 status berubah → "Dipinjam"
   - Di Tab Units, U002 tampil badge "Dipinjam - Ahmad"
   - History unit U002 tercatat: "Dipinjam oleh Ahmad"

### Test Case 4: Pengembalian Unit

1. **Di Halaman Detail → Tab "KEMBALI"**
2. **Tampil List Unit Yang Sedang Dipinjam**
   - INV-00001-U002 (Dipinjam: Ahmad)
3. **Klik "Proses Pengembalian"**
4. **Isi Form Modal:**
   - Tanggal Kembali: 2025-11-27
   - Kondisi Saat Kembali: Rusak Ringan
   - Ada Kerusakan? ✓ Ya
   - Deskripsi: Lensa retak sedikit
5. **Submit**
6. **Expected Result:**
   - Unit U002 status → "Maintenance" (karena ada kerusakan)
   - Unit U002 kondisi → "Rusak Ringan"
   - History tercatat: "Dikembalikan dengan kerusakan: Lensa retak"
   - Peminjaman status → "Sudah Dikembalikan"

### Test Case 5: View History Unit

1. **Di Tab "UNITS" → Klik Icon History pada unit tertentu**
2. **Expected Result:**
   - Modal muncul dengan timeline lengkap
   - Tampil semua aktivitas unit tersebut:
     * Input baru (2025-11-20)
     * Dipinjam oleh Ahmad (2025-11-26)
     * Dikembalikan dengan kerusakan (2025-11-27)
     * Status: tersedia → dipinjam → maintenance
     * Kondisi: baik → baik → rusak_ringan

---

## 🐛 Troubleshooting

### Error: "Class InventarisDetailUnit not found"
```bash
composer dump-autoload
```

### Error: "Table doesn't exist"
```bash
php artisan migrate:fresh  # HATI-HATI: Akan hapus semua data!
# Atau:
php artisan migrate
```

### Error: Foreign Key Constraint
```bash
# Pastikan migration dijalankan sesuai urutan:
# 1. Update inventaris
# 2. Create inventaris_units
# 3. Create inventaris_detail_units
# 4. Create inventaris_unit_history
# 5. Update peminjaman_inventaris
# 6. Update pengembalian_inventaris
```

### Units Tidak Muncul di Dropdown Peminjaman
- Pastikan ada unit dengan status 'tersedia'
- Check method `getUnitsTersedia()` di controller
- Check relasi `detailUnitsTersedia()` di model

---

## 📊 Monitoring & Maintenance

### Check Total Units vs Detail Units
```sql
SELECT 
    i.kode_inventaris,
    i.nama_barang,
    i.jumlah_unit,
    COUNT(du.id) as actual_units,
    (i.jumlah_unit - COUNT(du.id)) as selisih
FROM inventaris i
LEFT JOIN inventaris_detail_units du ON i.id = du.inventaris_id
WHERE i.tracking_per_unit = 1
GROUP BY i.id
HAVING selisih != 0;
```

### Check Unit Dengan Status Dipinjam Tapi Tidak Ada Peminjaman Aktif
```sql
SELECT * FROM inventaris_detail_units 
WHERE status = 'dipinjam' 
AND (peminjaman_inventaris_id IS NULL 
     OR peminjaman_inventaris_id NOT IN (
         SELECT id FROM peminjaman_inventaris 
         WHERE status_peminjaman = 'disetujui'
     ));
```

---

## 🎓 Training Manual untuk User

### Untuk Staff Admin Inventaris:

**1. Mengelola Barang Baru dengan Banyak Unit**
- Tambah Master Data dengan flag "Tracking Per Unit"
- Masuk ke Detail
- Tambah unit satu per satu atau bulk

**2. Proses Peminjaman**
- Tidak lagi dari halaman utama
- Masuk ke Detail Barang → Tab Pinjam
- Pilih unit spesifik yang tersedia
- Submit peminjaman

**3. Proses Pengembalian**
- Detail Barang → Tab Kembali
- Lihat daftar unit yang sedang dipinjam
- Proses pengembalian
- Catat kondisi saat kembali

**4. Monitoring Kondisi Unit**
- Tab Units menampilkan semua unit
- Badge warna menunjukkan status & kondisi
- Bisa edit kondisi unit kapan saja
- History unit bisa dilihat per item

---

## 📝 Next Features (Future Enhancement)

### V2.0 Ideas:
- [ ] QR Code per unit untuk scan cepat
- [ ] Notifikasi WhatsApp saat unit rusak
- [ ] Dashboard analytics kondisi unit
- [ ] Reminder maintenance berkala
- [ ] Export laporan per unit (Excel/PDF)
- [ ] Mobile app untuk peminjaman
- [ ] Foto kondisi unit saat pinjam & kembali
- [ ] Rating kondisi barang oleh peminjam
- [ ] Approval bertingkat untuk barang mahal
- [ ] Integration dengan sistem keuangan (aset)

---

## 🤝 Support & Contact

Jika ada pertanyaan atau butuh bantuan implementasi:

1. **Review Dokumentasi Lengkap**: `DOKUMENTASI_INVENTARIS_MULTI_UNIT_SYSTEM.md`
2. **Check Error Logs**: `storage/logs/laravel.log`
3. **Test di Development Environment Dulu** sebelum production

---

*Quick Start Guide - Version 1.0*
*Last Updated: 26 November 2025*
