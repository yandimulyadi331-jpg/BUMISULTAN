# 🚀 CARA AKSES SISTEM INVENTARIS MULTI-UNIT

## 📍 URL Akses

### Halaman Utama Inventaris
```
/inventaris
```

### Halaman Detail Inventaris (dengan Tab Units)
```
/inventaris/{id}/detail
```

**Contoh**: 
- `/inventaris/1/detail` - Detail untuk inventaris ID #1 (KURSI)
- `/inventaris/2/detail` - Detail untuk inventaris ID #2 (SENTER)

---

## 🖱️ Navigasi dari Halaman Utama

1. Buka `/inventaris`
2. Pada tabel inventaris, klik tombol **"Detail"** (ikon mata)
3. Anda akan dibawa ke halaman detail dengan 4 tab:
   - **Units** - Daftar semua unit dengan kode unik
   - **Peminjaman** - Form & daftar peminjaman aktif
   - **Pengembalian** - Daftar pengembalian terbaru
   - **History** - Timeline aktivitas lengkap

---

## 📋 Fitur di Halaman Detail

### Tab 1: Units
**Fitur**:
- List semua unit dengan kode (INV-00001-U001, U002, dst)
- Status badge (Tersedia, Dipinjam, Maintenance)
- Kondisi badge (Baik, Rusak Ringan, Rusak Berat)
- Tombol "Tambah Unit" untuk bulk add
- Action per unit: History, Edit, Delete

**Cara Tambah Unit**:
1. Klik "Tambah Unit"
2. Isi jumlah unit yang ingin ditambahkan (contoh: 5)
3. (Opsional) Pilih/buat batch grouping
4. (Opsional) Isi lokasi awal & kondisi default
5. Klik "Simpan"
6. Sistem akan auto-generate kode untuk setiap unit

### Tab 2: Peminjaman
**Fitur**:
- Daftar peminjaman aktif (belum dikembalikan)
- Form peminjaman baru **dengan dropdown unit**
- Validasi unit harus status "tersedia"
- Auto update status unit ke "dipinjam"

**Cara Pinjam Unit Spesifik**:
1. Pilih tab "Peminjaman"
2. Scroll ke form "Peminjaman Baru"
3. Pilih unit dari dropdown (hanya unit tersedia yang muncul)
4. Isi data peminjam & keperluan
5. Tanda tangan digital
6. Klik "Proses Peminjaman"

### Tab 3: Pengembalian
**Fitur**:
- Daftar pengembalian terbaru
- Tracking kondisi saat dikembalikan
- Report kerusakan
- Auto update kondisi unit

### Tab 4: History
**Fitur**:
- Timeline lengkap semua aktivitas
- Filter by jenis aktivitas
- Pagination (20 per page)
- Badge warna sesuai jenis aktivitas

---

## 🧪 Test Data yang Sudah Ada

### Inventaris #1: KURSI
- **Total Unit**: 11 unit
- **Kode Unit**: INV-00001-U001 sampai U010
- **Status**:
  - Tersedia: 9 unit
  - Maintenance: 2 unit
  - Dipinjam: 0 unit

**URL Detail**: `/inventaris/1/detail`

### Inventaris #2: SENTER
- **Tracking per unit**: Belum aktif (masih mode lama)
- **Jumlah**: 1 unit

**URL Detail**: `/inventaris/2/detail`

---

## 🔑 Quick Access Routes

| Halaman | Route Name | URL |
|---------|-----------|-----|
| Index Inventaris | `inventaris.index` | `/inventaris` |
| Detail Inventaris | `inventaris.show-detail` | `/inventaris/{id}/detail` |
| List Units | `inventaris.units.index` | `/inventaris/{inventaris}/units` |
| Create Unit | `inventaris.units.create` | `/inventaris/{inventaris}/units/create` |
| Edit Unit | `inventaris.units.edit` | `/inventaris/{inventaris}/units/{unit}/edit` |
| History Unit | `inventaris.units.history` | `/inventaris/{inventaris}/units/{unit}/history` |

---

## 🎨 Shortcut untuk Developer

### Via Route Helper (Blade)
```blade
{{-- Link ke halaman detail --}}
<a href="{{ route('inventaris.show-detail', $inventaris->id) }}">
    Detail
</a>

{{-- Link ke daftar unit --}}
<a href="{{ route('inventaris.units.index', $inventaris->id) }}">
    Daftar Unit
</a>

{{-- Link ke history unit spesifik --}}
<a href="{{ route('inventaris.units.history', [$inventaris->id, $unit->id]) }}">
    History Unit
</a>
```

### Via Controller Redirect
```php
// Redirect ke halaman detail
return redirect()->route('inventaris.show-detail', $inventaris->id);

// Redirect dengan success message
return redirect()
    ->route('inventaris.show-detail', $inventaris->id)
    ->with('success', 'Unit berhasil ditambahkan');
```

---

## 📱 Testing Checklist

- [ ] Akses `/inventaris/1/detail`
- [ ] Klik tab "Units" - lihat daftar 11 unit
- [ ] Klik "Tambah Unit" - test form bulk add
- [ ] Klik tab "Peminjaman" - test unit selection dropdown
- [ ] Klik tab "History" - lihat timeline aktivitas
- [ ] Klik "History" pada salah satu unit - lihat detail history per unit
- [ ] Klik "Edit" pada salah satu unit - test update kondisi/status
- [ ] Test peminjaman unit spesifik
- [ ] Test pengembalian dengan tracking kondisi

---

## 🐛 Troubleshooting

### 1. Halaman Detail Tidak Muncul
**Penyebab**: Route belum registered  
**Solusi**: Pastikan routes sudah di-register di `routes/web.php`

### 2. Dropdown Unit Kosong di Form Peminjaman
**Penyebab**: Tidak ada unit dengan status "tersedia"  
**Solusi**: 
- Cek tab "Units" untuk melihat status unit
- Ubah status unit ke "tersedia" jika diperlukan

### 3. Error saat Generate Kode Unit
**Penyebab**: Kode manual 'AUTO' konflik dengan unique constraint  
**Solusi**: Jangan kirim field `kode_unit`, biarkan model auto-generate

### 4. History Tidak Muncul
**Penyebab**: Query error pada column `inventaris_id`  
**Solusi**: ✅ Sudah diperbaiki - gunakan `whereHas('detailUnit')`

---

## 📞 Support

Jika menemukan issue:
1. Check error log: `storage/logs/laravel.log`
2. Check browser console untuk AJAX errors
3. Run test script: `php test_inventaris_multi_unit.php`
4. Lihat `IMPLEMENTASI_SELESAI_INVENTARIS_MULTI_UNIT.md` untuk troubleshooting

---

**Quick Start**: Buka browser → `/inventaris/1/detail` → Explore tabs! 🚀
