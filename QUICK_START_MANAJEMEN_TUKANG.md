# QUICK START - MANAJEMEN TUKANG

## 🚀 Instalasi Cepat (3 Langkah)

### 1️⃣ Jalankan Migration
```bash
php artisan migrate
```

### 2️⃣ Setup Permissions
```bash
php setup_permissions_tukang.php
```

### 3️⃣ Akses Menu
Login sebagai **super admin** → Menu **Manajemen Tukang** → **Data Tukang**

---

## 📝 Cara Cepat Tambah Data Tukang

1. Klik **"Tambah Data Tukang"**
2. Isi minimal:
   - Kode Tukang (contoh: `TK001`)
   - Nama Tukang
   - Status (Aktif/Non Aktif)
3. Isi opsional: NIK, HP, Email, Keahlian, Tarif, Alamat, Foto
4. Klik **"Simpan"**

---

## 🔍 Fitur Pencarian

- **Search Box:** Cari berdasarkan nama, kode, keahlian, atau HP
- **Filter Status:** Tampilkan hanya tukang aktif atau non-aktif
- **Klik icon mata (👁️):** Lihat detail lengkap
- **Klik icon edit (✏️):** Edit data
- **Klik icon hapus (🗑️):** Hapus data

---

## ⚡ Command Penting

```bash
# Jika foto tidak muncul
php artisan storage:link

# Clear cache jika ada masalah
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cek routes tukang
php artisan route:list | grep tukang
```

---

## 🎯 Field Data Tukang

| Field | Wajib | Keterangan |
|-------|-------|------------|
| Kode Tukang | ✅ | Harus unique (TK001, TK002, dll) |
| Nama Tukang | ✅ | Nama lengkap |
| NIK | ❌ | Nomor KTP |
| No HP | ❌ | Nomor WhatsApp |
| Email | ❌ | Email valid |
| Keahlian | ❌ | Tukang Batu, Tukang Cat, dll |
| Status | ✅ | Aktif atau Non Aktif |
| Tarif Harian | ❌ | Dalam Rupiah |
| Alamat | ❌ | Alamat lengkap |
| Keterangan | ❌ | Catatan tambahan |
| Foto | ❌ | JPG/PNG, Max 2MB |

---

## ✅ Permissions

Assign permission ini ke role yang membutuhkan:

- `tukang.index` → Lihat daftar
- `tukang.create` → Tambah data
- `tukang.show` → Lihat detail
- `tukang.edit` → Edit data
- `tukang.delete` → Hapus data

---

## 🐛 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| Menu tidak muncul | Jalankan `setup_permissions_tukang.php` |
| Foto tidak muncul | Jalankan `php artisan storage:link` |
| Error 404 | Jalankan `php artisan route:clear` |
| Permission denied | Login sebagai super admin |

---

## 📍 Lokasi Menu

```
Sidebar → Manajemen Tukang → Data Tukang
```

Posisi menu: **Setelah "Manajemen Yayasan"**

---

## 💾 Backup Data

Tabel yang perlu di-backup: `tukangs`

```bash
# Export data
php artisan db:backup

# Atau manual via SQL
mysqldump -u username -p database_name tukangs > backup_tukangs.sql
```

---

## ✨ Selesai!

Modul Manajemen Tukang siap digunakan. Untuk panduan lengkap, baca **DOKUMENTASI_MANAJEMEN_TUKANG.md**

---

**Status:** ✅ Ready to Use
