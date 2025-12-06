# 🚀 QUICK START GUIDE - MANAJEMEN DOKUMEN

## ⚡ Setup Cepat

### 1. Migration & Seeder (Sudah Selesai ✅)
```bash
php artisan migrate
php artisan db:seed --class=DocumentCategorySeeder
php artisan storage:link
```

### 2. Akses Menu
```
Login → Fasilitas & Asset → Manajemen Dokumen
```

---

## 📝 Cheat Sheet - Kode Dokumen

### Format Kode
```
[KATEGORI]-[NOMOR]-[LOKER]

Contoh:
SK-001-L001     # Surat Keputusan #1 di Loker L001
PKS-023-L045    # Perjanjian #23 di Loker L045
SOP-005-L000    # SOP #5 tanpa loker fisik
```

### Kategori yang Tersedia
```
SK   - Surat Keputusan
PKS  - Perjanjian Kerja Sama
SOP  - Standard Operating Procedure
KTK  - Kontrak Karyawan
INV  - Invoice
LPR  - Laporan
SRT  - Surat Menyurat
IZN  - Perizinan
NDA  - Non-Disclosure Agreement
MOU  - Memorandum of Understanding
```

---

## 🎯 3 Level Akses

| Level | Icon | Bisa View? | Bisa Download? | User |
|-------|------|------------|----------------|------|
| **Public** | 🌍 | ✅ | ✅ | Semua |
| **View Only** | 👁️ | ✅ | ❌ | Semua kecuali download |
| **Restricted** | 🔒 | ❌ | ❌ | Admin Only |

---

## 🔍 Cara Cari Dokumen

### Di Sistem (Online):
```
1. Search box: ketik kode/nama/loker
2. Filter kategori: pilih dropdown
3. Filter status: aktif/arsip/kadaluarsa
4. Quick loker search: masukkan nomor loker
```

### Di Loker Fisik (Offline):
```
1. Buka sistem, cari dokumen
2. Catat kode: SK-001-L001
3. L001 = Nomor loker
4. Cek detail: Ruang Arsip Lt.2, Rak R1, Baris B1
5. Pergi ke lokasi dan ambil dokumen
```

---

## ⚡ Quick Actions

### Tambah Dokumen (Admin)
```
Klik "Tambah Dokumen" →
Isi nama & kategori →
Upload file ATAU masukkan link →
Isi lokasi loker (opsional) →
Pilih access level →
Simpan
```

### View Dokumen
```
Klik icon mata (👁️) →
Modal preview muncul →
Lihat detail & preview file →
Download (jika ada akses)
```

### Download Dokumen
```
Klik icon download (📥) →
File otomatis terdownload →
Log activity tercatat
```

### Edit/Hapus (Admin Only)
```
Edit: Klik icon pensil (✏️)
Hapus: Klik icon sampah (🗑️) + konfirmasi
```

---

## 🎨 File Type Support

### Upload File:
```
✅ PDF (.pdf)
✅ Word (.doc, .docx)
✅ Excel (.xls, .xlsx)
✅ Image (.jpg, .jpeg, .png, .gif)
✅ Archive (.zip, .rar)
Max: 10MB
```

### Link Eksternal:
```
✅ Google Drive
✅ Dropbox
✅ OneDrive
✅ URL lainnya
```

---

## 📋 Contoh Cepat

### Contoh 1: Upload SK
```
Nama: SK Pengangkatan Direktur 2024
Kategori: SK
Upload: SK_Direktur.pdf
Loker: L001
Access: Restricted
→ Generate: SK-001-L001
```

### Contoh 2: Link MOU
```
Nama: MOU PT XYZ
Kategori: MOU
Link: https://drive.google.com/...
Access: View Only
→ Generate: MOU-001-L000
```

### Contoh 3: Kontrak Karyawan
```
Nama: Kontrak John Doe 2024
Kategori: KTK
Upload: Kontrak_JohnDoe.pdf
Loker: L025
Rak: R3, Baris: B2
Access: Public
Tags: kontrak, 2024, john doe
→ Generate: KTK-001-L025
```

---

## 🚨 Troubleshooting Cepat

### File gagal upload?
```bash
# Check max upload size
php artisan config:clear
# Lihat di .env atau php.ini
```

### Preview tidak muncul?
```bash
php artisan storage:link
```

### Tidak bisa download?
```
Check access level dokumen
atau
Check role user (butuh admin?)
```

### Kode tidak generate?
```
Pastikan kategori dipilih
Pastikan nomor loker diisi (atau kosongkan untuk L000)
```

---

## 🎓 Tips & Tricks

1. **Gunakan Tags**: Pisah dengan koma untuk pencarian mudah
   ```
   Tags: kontrak, karyawan, 2024, penting
   ```

2. **Nomor Loker Konsisten**: 
   ```
   Format: L001, L002, L003...
   Jangan: Loker1, L-1, LOK001
   ```

3. **Metadata Lengkap**: Isi tanggal berlaku/berakhir untuk tracking expired

4. **Access Level Bijak**:
   - Dokumen sensitif: Restricted
   - Dokumen internal: View Only
   - Dokumen umum: Public

5. **Loker Terorganisir**:
   ```
   Loker → Rak → Baris
   L001 → R1 → B1
   ```

---

## 📊 Status Dokumen

```
✅ Aktif       - Dokumen masih berlaku
📦 Arsip       - Dokumen sudah tidak aktif tapi disimpan
⚠️ Kadaluarsa  - Dokumen sudah expired
```

---

## 🔐 Permission Matrix

| Aksi | Super Admin | User Biasa |
|------|-------------|------------|
| View Public | ✅ | ✅ |
| Download Public | ✅ | ✅ |
| View View-Only | ✅ | ✅ |
| Download View-Only | ✅ | ❌ |
| View Restricted | ✅ | ❌ |
| Download Restricted | ✅ | ❌ |
| Create/Edit/Delete | ✅ | ❌ |

---

## 📞 Butuh Bantuan?

1. Baca [DOKUMENTASI_MANAJEMEN_DOKUMEN.md](DOKUMENTASI_MANAJEMEN_DOKUMEN.md)
2. Hubungi IT Support
3. Check error log di sistem

---

**Happy Document Managing! 🎉**

*Last Updated: 7 Nov 2024*
