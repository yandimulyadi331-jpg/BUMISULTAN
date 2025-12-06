# 📋 CARA AKSES FITUR DOWNLOAD FORMULIR PINJAMAN TUKANG

## 🎯 Lokasi Fitur

Fitur download formulir pinjaman tukang berada di modul **Keuangan Tukang** → **Pinjaman**.

---

## 🚀 LANGKAH-LANGKAH AKSES

### 1️⃣ **Akses Menu Manajemen Tukang**
- Login ke sistem
- Di sidebar kiri, cari menu **"Manajemen Tukang"** 📊
- Klik untuk membuka submenu

### 2️⃣ **Masuk ke Keuangan Tukang**
- Klik submenu **"Keuangan Tukang"** 💰
- Atau akses langsung: `/keuangan-tukang`

### 3️⃣ **Buka Halaman Pinjaman**
- Di halaman Dashboard Keuangan Tukang
- Klik tombol **kuning** di header: **"💳 Pinjaman"**
- Atau akses langsung: `/keuangan-tukang/pinjaman`

---

## 📥 DUA JENIS FORMULIR YANG TERSEDIA

### ✅ **1. FORMULIR KOSONG (Template Blanko)**

**Lokasi:** Header halaman Pinjaman (bagian atas)

**Tombol:** 
```
🟢 Download Formulir Kosong
```
- Warna: **HIJAU**
- Icon: 📄 file-download
- Posisi: Di sebelah tombol "Tambah Pinjaman"

**Fungsi:**
- Template kosong untuk tukang yang ingin mengajukan pinjaman baru
- Bisa dicetak dan diisi manual
- Digunakan saat pengajuan pinjaman baru

**Route:**
```php
route('keuangan-tukang.pinjaman.download-formulir-kosong')
```

**Method Controller:**
```php
KeuanganTukangController@downloadFormulirKosong()
```

---

### ✅ **2. FORMULIR TERISI (Berdasarkan Data Pinjaman)**

**Lokasi:** Di tabel data pinjaman, kolom **AKSI**

**Tombol:** 
```
🟢 [icon download]
```
- Warna: **HIJAU** 
- Icon: 💾 download
- Posisi: Kolom Aksi setiap baris pinjaman (tombol paling kanan)

**Fungsi:**
- Formulir yang sudah terisi lengkap dengan data pinjaman
- Berisi informasi: Nama tukang, jumlah pinjaman, cicilan, dll
- Untuk dokumentasi dan arsip

**Route:**
```php
route('keuangan-tukang.pinjaman.download-formulir', $pinjaman_id)
```

**Method Controller:**
```php
KeuanganTukangController@downloadFormulirPinjaman($id)
```

---

## 🔐 PERMISSION YANG DIPERLUKAN

User harus memiliki salah satu permission berikut:

### Permission Utama:
- ✅ `keuangan-tukang.index` - Akses dashboard keuangan tukang
- ✅ `keuangan-tukang.pinjaman` - Akses halaman pinjaman

### Permission Tambahan (Opsional):
- `keuangan-tukang.pinjaman.create` - Tambah pinjaman baru
- `keuangan-tukang.pinjaman.bayar` - Bayar cicilan
- `keuangan-tukang.pinjaman.download` - Download formulir

### Role yang Memiliki Akses:
- 🔴 **Super Admin** - Full akses semua fitur
- 🟡 **Admin** - Akses dasar
- 🟢 **Keuangan** - Akses penuh keuangan

---

## 📍 STRUKTUR NAVIGASI LENGKAP

```
Dashboard
└── Manajemen Tukang 📊
    ├── Data Tukang
    ├── Kehadiran Tukang
    └── Keuangan Tukang 💰
        └── Header Buttons:
            ├── Gaji Kamis (TTD)
            ├── Lembur Cash
            ├── 💳 Pinjaman ← [KLIK DI SINI]
            └── Laporan
```

---

## 🎨 TAMPILAN HALAMAN PINJAMAN

### Header Section:
```
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
💳 Manajemen Pinjaman Tukang
Kelola pinjaman dan cicilan tukang

[🟢 Download Formulir Kosong]  [🔵 Tambah Pinjaman]  [⚪ Kembali]
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
```

### Info Alert (Penjelasan):
```
ℹ️ Informasi:
• Formulir Kosong (tombol hijau di atas) - Template blanko untuk 
  tukang yang ingin mengajukan pinjaman baru, bisa dicetak dan 
  diisi manual.
  
• Formulir Terisi (tombol hijau di tabel) - Formulir yang sudah 
  terisi dengan data pinjaman untuk dokumentasi.
```

### Tabel Data:
```
┌────┬──────┬──────────────┬────────────┬────────────┬────────┐
│ No │ Kode │ Nama Tukang  │ Pinjaman   │ Sisa       │ Aksi   │
├────┼──────┼──────────────┼────────────┼────────────┼────────┤
│ 1  │ TK01 │ Ahmad        │ Rp 500.000 │ Rp 200.000 │[💰][👁][💾]│
│ 2  │ TK02 │ Budi         │ Rp 300.000 │ Rp 0       │[  ][👁][💾]│
└────┴──────┴──────────────┴────────────┴────────────┴────────┘
                                                       ↑
                                            Download Formulir Terisi
```

Legend tombol aksi:
- **💰** = Bayar Cicilan (hanya muncul jika status AKTIF)
- **👁** = Detail Pinjaman
- **💾** = Download Formulir (HIJAU) ← **INI YANG ANDA CARI!**

---

## 🔗 URL LANGSUNG

### Halaman Pinjaman:
```
http://your-domain.com/keuangan-tukang/pinjaman
```

### Download Formulir Kosong:
```
http://your-domain.com/keuangan-tukang/pinjaman/download-formulir-kosong
```

### Download Formulir Terisi (contoh ID: 1):
```
http://your-domain.com/keuangan-tukang/pinjaman/1/download-formulir
```

---

## 📁 FILE TERKAIT

### Views:
- `resources/views/keuangan-tukang/pinjaman/index.blade.php` - Halaman utama
- `resources/views/keuangan-tukang/pinjaman/formulir-kosong-pdf.blade.php` - Template kosong
- `resources/views/keuangan-tukang/pinjaman/formulir-pdf.blade.php` - Template terisi

### Controller:
- `app/Http/Controllers/KeuanganTukangController.php`
  - Method: `pinjaman()` - Tampilkan halaman
  - Method: `downloadFormulirKosong()` - Download blanko
  - Method: `downloadFormulirPinjaman($id)` - Download terisi

### Routes:
```php
// File: routes/web.php (line ~1318-1337)

Route::prefix('keuangan-tukang')->name('keuangan-tukang.')->group(function () {
    // Pinjaman Tukang
    Route::get('/pinjaman', 'pinjaman')->name('pinjaman');
    Route::get('/pinjaman/download-formulir-kosong', 'downloadFormulirKosong')
         ->name('pinjaman.download-formulir-kosong');
    Route::get('/pinjaman/{id}/download-formulir', 'downloadFormulirPinjaman')
         ->name('pinjaman.download-formulir');
});
```

---

## ⚠️ TROUBLESHOOTING

### ❌ Tidak Melihat Menu "Manajemen Tukang"
**Solusi:**
1. Pastikan user memiliki permission: `keuangan-tukang.index`
2. Jalankan: `php setup_permissions_keuangan_tukang.php`
3. Clear cache: `php artisan cache:clear`

### ❌ Tidak Melihat Tombol "Pinjaman"
**Solusi:**
1. Cek permission: `keuangan-tukang.pinjaman`
2. Pastikan sudah login dengan role yang tepat
3. Lihat di file: `resources/views/keuangan-tukang/index.blade.php` line 31-34

### ❌ Tombol Download Tidak Ada
**Solusi:**
1. Pastikan sudah ada data pinjaman
2. Tombol formulir terisi hanya muncul jika ada data di tabel
3. Tombol formulir kosong selalu ada di header

### ❌ Error 404 atau 403
**Solusi:**
1. Jalankan: `php artisan route:list | grep keuangan-tukang`
2. Cek permission di database: `SELECT * FROM permissions WHERE name LIKE '%keuangan-tukang%'`
3. Re-assign permission ke role

---

## ✅ CHECKLIST VERIFIKASI

Gunakan checklist ini untuk memastikan fitur dapat diakses:

- [ ] User sudah login
- [ ] User memiliki role: Super Admin / Admin / Keuangan
- [ ] Permission `keuangan-tukang.index` ada
- [ ] Permission `keuangan-tukang.pinjaman` ada
- [ ] Menu "Manajemen Tukang" muncul di sidebar
- [ ] Submenu "Keuangan Tukang" dapat diklik
- [ ] Tombol "💳 Pinjaman" terlihat di header
- [ ] Halaman `/keuangan-tukang/pinjaman` dapat diakses
- [ ] Tombol "Download Formulir Kosong" terlihat (hijau, di header)
- [ ] Tombol download terlihat di kolom Aksi tabel (hijau, icon download)

---

## 📞 BANTUAN LANJUTAN

Jika masih tidak dapat mengakses, cek:

1. **Database Permission:**
```sql
SELECT * FROM permissions 
WHERE name LIKE '%keuangan-tukang%';
```

2. **User Permission:**
```sql
SELECT p.name 
FROM permissions p
JOIN role_has_permissions rhp ON p.id = rhp.permission_id
JOIN roles r ON r.id = rhp.role_id
WHERE r.name = 'super admin';
```

3. **Route List:**
```bash
php artisan route:list | grep keuangan-tukang
```

---

**Dibuat:** November 2025  
**Versi:** 1.0  
**Modul:** Keuangan Tukang - Pinjaman  

