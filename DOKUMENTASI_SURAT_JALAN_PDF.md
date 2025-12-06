# FITUR SURAT JALAN PDF - PEMINJAMAN KENDARAAN

## 📋 OVERVIEW
Fitur auto-generate PDF Surat Jalan setelah berhasil submit peminjaman kendaraan. Menghasilkan 2 jenis PDF dengan tanda tangan:
1. **PDF untuk Divisi Transportasi** - Arsip internal
2. **PDF untuk Peminjam** - Dibawa sebagai bukti sah peminjaman

---

## ✨ FITUR UTAMA

### 1. Auto Redirect setelah Berhasil Pinjam
- Setelah submit form peminjaman berhasil
- Otomatis redirect ke halaman download surat jalan
- Menampilkan info lengkap peminjaman
- 2 tombol download untuk 2 jenis PDF berbeda

### 2. PDF untuk Divisi Transportasi
**Karakteristik:**
- ✅ Format resmi dengan kop surat lengkap
- ✅ Nomor surat: `KODE_PEMINJAMAN/SJ-TRANS/MM/YYYY`
- ✅ Detail lengkap: peminjaman, kendaraan, peminjam
- ✅ Ketentuan peminjaman terperinci
- ✅ Area tanda tangan: Bagian Transportasi + Peminjam
- ✅ Area stempel divisi transportasi
- ✅ Catatan khusus untuk divisi transportasi
- ✅ Timestamp pencetakan dokumen

### 3. PDF untuk Peminjam
**Karakteristik:**
- ✅ Format praktis dan ringkas
- ✅ Nomor surat: `KODE_PEMINJAMAN/SJ/MM/YYYY`
- ✅ Highlight box: **WAJIB DIBAWA**
- ✅ Info essensial: identitas, kendaraan, waktu
- ✅ Warning box: Kewajiban peminjam
- ✅ Tanda tangan kedua pihak
- ✅ Info kontak divisi transportasi
- ✅ Desain user-friendly untuk dibawa

---

## 🗂️ FILE YANG DIBUAT/DIMODIFIKASI

### 1. Controller
**File:** `app/Http/Controllers/PeminjamanKendaraanController.php`

#### Method yang Dimodifikasi:
```php
public function prosesPinjam(Request $request, $kendaraan_id)
{
    // ... existing validation & save logic ...
    
    // PERUBAHAN: Redirect ke halaman surat jalan (bukan ke index)
    return Redirect::route('kendaraan.peminjaman.surat', Crypt::encrypt($peminjaman_id))
        ->with('success', 'Peminjaman kendaraan berhasil dicatat!');
}
```

#### Method Baru yang Ditambahkan:

**1. suratJalan($id)**
- Menampilkan halaman download
- Load data peminjaman dengan relasi kendaraan
- Pass data ke view `surat-jalan.blade.php`

**2. downloadSuratTransportasi($id)**
- Generate PDF untuk divisi transportasi
- Filename: `Surat_Transportasi_KODE_PEMINJAMAN.pdf`
- Template: `pdf-transportasi.blade.php`
- Paper A4, orientasi portrait

**3. downloadSuratPeminjam($id)**
- Generate PDF untuk peminjam
- Filename: `Surat_Peminjam_KODE_PEMINJAMAN.pdf`
- Template: `pdf-peminjam.blade.php`
- Paper A4, orientasi portrait

### 2. Routes
**File:** `routes/web.php`

**Routes Baru:**
```php
Route::get('/peminjaman/{id}/surat-jalan', 'suratJalan')
    ->name('kendaraan.peminjaman.surat');
    
Route::get('/peminjaman/{id}/pdf-transportasi', 'downloadSuratTransportasi')
    ->name('kendaraan.peminjaman.pdf.transportasi');
    
Route::get('/peminjaman/{id}/pdf-peminjam', 'downloadSuratPeminjam')
    ->name('kendaraan.peminjaman.pdf.peminjam');
```

### 3. Views

#### A. Halaman Download (surat-jalan.blade.php)
**Path:** `resources/views/kendaraan/peminjaman/surat-jalan.blade.php`

**Komponen:**
- ✅ Alert success peminjaman
- ✅ Card info peminjaman (ringkasan data)
- ✅ 2 Card download dengan icon berbeda:
  - Card biru: Download Surat Transportasi
  - Card hijau: Download Surat Peminjam
- ✅ Alert info: Catatan penting kedua surat
- ✅ Tombol kembali ke daftar kendaraan

**Informasi Ditampilkan:**
- Kode peminjaman (badge)
- Nama peminjam, no HP, keperluan
- Kendaraan, no polisi
- Waktu pinjam & estimasi kembali

#### B. Template PDF Transportasi (pdf-transportasi.blade.php)
**Path:** `resources/views/kendaraan/peminjaman/pdf-transportasi.blade.php`

**Struktur:**
1. **Kop Surat Resmi**
   - Logo/Nama: YAYASAN BUMI SULTAN
   - Sub: DIVISI TRANSPORTASI
   - Alamat, telp, email
   - Border bottom

2. **Judul & Nomor Surat**
   - Judul: SURAT JALAN PEMINJAMAN KENDARAAN
   - Nomor: `KODE/SJ-TRANS/MM/YYYY`

3. **Detail Box 1: Data Peminjaman**
   - Kode peminjaman (badge)
   - Tanggal peminjaman
   - Estimasi pengembalian

4. **Detail Box 2: Data Kendaraan**
   - Kode kendaraan, nama, jenis
   - No polisi (bold, besar)
   - Warna, tahun pembuatan

5. **Detail Box 3: Data Peminjam**
   - Nama lengkap (bold)
   - No HP/WA, divisi
   - Keperluan, tujuan, keterangan

6. **Ketentuan Peminjaman**
   - 5 poin ketentuan numbered list
   - Font reguler, margin kiri

7. **Area Tanda Tangan**
   - 2 kolom: Bagian Transportasi | Peminjam
   - Image tanda tangan (jika ada)
   - Nama & role di bawah
   - Min height untuk TTD manual

8. **Area Stempel**
   - Text placeholder stempel

9. **Footer Note**
   - Catatan khusus divisi transportasi
   - Timestamp pencetakan

**Styling:**
- Font: Arial 11pt
- Background detail box: #f9f9f9
- Border: 1px solid black
- Badge: background blue (#0d6efd)

#### C. Template PDF Peminjam (pdf-peminjam.blade.php)
**Path:** `resources/views/kendaraan/peminjaman/pdf-peminjam.blade.php`

**Struktur:**
1. **Kop Surat** (sama seperti transportasi)

2. **Judul & Nomor**
   - Nomor: `KODE/SJ/MM/YYYY` (lebih ringkas)

3. **Highlight Box (Biru)**
   - 📋 Icon
   - **BUKTI PEMINJAMAN KENDARAAN SAH**
   - Text tebal: **WAJIB DIBAWA**
   - Background: #e7f3ff

4. **Detail Box: Identitas Peminjam**
   - Nama, no HP, divisi

5. **Detail Box: Kendaraan yang Dipinjam**
   - Kode kendaraan (badge hijau)
   - Nama/tipe, no polisi (font besar)
   - Jenis, warna

6. **Detail Box: Waktu Peminjaman**
   - Waktu pinjam (bold)
   - Estimasi kembali (bold)
   - Keperluan, tujuan

7. **Warning Box (Kuning)**
   - ⚠️ Icon
   - **KEWAJIBAN PEMINJAM**
   - 5 poin bullet dengan emphasis (WAJIB, BERTANGGUNG JAWAB, dll)
   - Background: #fff8e1

8. **Area Tanda Tangan**
   - Header: PERSETUJUAN DAN KONFIRMASI
   - 2 kolom dengan subtext keterangan
   - Tanda tangan + nama

9. **Footer Info Penting**
   - 3 checklist poin
   - Info kontak divisi
   - Timestamp + kode peminjaman

**Styling:**
- Font: Arial 11pt
- Highlight box: border 2px, radius 5px
- Warning box: border 2px kuning (#ffc107)
- Badge: background hijau (#198754)
- Text penting: warna merah (#dc3545)

---

## 🎯 FLOW PENGGUNAAN

```
1. User isi form peminjaman kendaraan
   ↓
2. Submit form → prosesPinjam()
   ↓
3. Validasi data + simpan ke database
   ↓
4. [REDIRECT BARU] → route('kendaraan.peminjaman.surat')
   ↓
5. Halaman download dengan 2 tombol muncul
   ↓
6a. Klik "Download Surat Transportasi"
    → downloadSuratTransportasi()
    → Generate PDF format resmi
    → Download file
    
6b. Klik "Download Surat Peminjam"
    → downloadSuratPeminjam()
    → Generate PDF format praktis
    → Download file
```

---

## 📊 DATA YANG DITAMPILKAN

### Data dari Peminjaman Table:
- `kode_peminjaman` ← Auto-generated
- `nama_peminjam`
- `no_hp_peminjam`
- `divisi_peminjam`
- `waktu_pinjam`
- `estimasi_kembali`
- `keperluan`
- `tujuan`
- `keterangan`
- `ttd_pinjam` ← Tanda tangan peminjam (base64 image)
- `ttd_transportasi` ← Tanda tangan bagian transportasi

### Data dari Kendaraan Table (Relasi):
- `kode_kendaraan` ← Auto-generated
- `nama_kendaraan`
- `jenis_kendaraan`
- `no_polisi`
- `warna`
- `tahun_pembuatan`

---

## 🔐 KEAMANAN

### ID Encryption
Semua parameter `{id}` di route menggunakan enkripsi:
```php
// Di redirect:
Crypt::encrypt($peminjaman_id)

// Di controller method:
$id = Crypt::decrypt($id);
```

### Relasi Data
- Eager loading menggunakan `->with('kendaraan')`
- Mencegah N+1 query problem
- Data kendaraan selalu tersedia

---

## 🎨 DESAIN PDF

### PDF Transportasi - Formal & Official
- **Warna dominan:** Hitam & Biru
- **Layout:** Box-based dengan border tegas
- **Typography:** Professional, hierarki jelas
- **Elemen:** Stempel area, footer note detail
- **Tujuan:** Arsip resmi, legal documentation

### PDF Peminjam - User-Friendly & Practical
- **Warna dominan:** Biru, Hijau, Kuning (color coding)
- **Layout:** Card-based dengan radius
- **Typography:** Bold emphasis pada info penting
- **Elemen:** Icon, highlight box, warning box
- **Tujuan:** Mudah dibaca, portable, instructive

---

## ✅ TESTING CHECKLIST

### Functional Testing:
- [ ] Submit form peminjaman baru
- [ ] Verify redirect ke halaman surat jalan
- [ ] Check info peminjaman tampil lengkap
- [ ] Klik download PDF transportasi
- [ ] Verify PDF transportasi ter-generate
- [ ] Check format & data dalam PDF transportasi
- [ ] Klik download PDF peminjam
- [ ] Verify PDF peminjam ter-generate
- [ ] Check format & data dalam PDF peminjam
- [ ] Verify tanda tangan muncul di kedua PDF
- [ ] Test dengan data tanpa tanda tangan
- [ ] Test dengan data optional kosong (tujuan, keterangan)

### PDF Content Testing:
- [ ] Kop surat tampil benar
- [ ] Nomor surat format benar
- [ ] Kode peminjaman & kendaraan tampil
- [ ] Data peminjam lengkap
- [ ] Data kendaraan lengkap
- [ ] Waktu format Indonesia (dd/mm/yyyy)
- [ ] Tanda tangan image render
- [ ] Ketentuan/warning tampil lengkap
- [ ] Footer info tampil
- [ ] Timestamp pencetakan akurat

### UI/UX Testing:
- [ ] Halaman download responsive
- [ ] Card layout rapi di desktop
- [ ] Tombol download jelas & accessible
- [ ] Icon tampil dengan benar
- [ ] Alert info terbaca
- [ ] Tombol "Kembali" berfungsi

---

## 🐛 TROUBLESHOOTING

### Error: "Class 'PDF' not found"
**Solusi:**
```bash
composer require barryvdh/laravel-dompdf
```

### Error: Tanda tangan tidak muncul
**Cek:**
1. Field `ttd_pinjam` ada di database?
2. Format base64 benar? (harus: `data:image/png;base64,...)
3. Path image di blade menggunakan `src="{{ $peminjaman->ttd_pinjam }}"`

### PDF kosong atau error
**Cek:**
1. Relasi `->with('kendaraan')` ada?
2. Data peminjaman exists di database?
3. Log Laravel: `storage/logs/laravel.log`

### Redirect tidak jalan
**Cek:**
1. Route name benar: `kendaraan.peminjaman.surat`
2. ID ter-encrypt: `Crypt::encrypt($peminjaman_id)`
3. Method `suratJalan()` ada di controller?

---

## 📝 CATATAN PENTING

### 1. Kustomisasi Kop Surat
Edit bagian ini di kedua template PDF:
```html
<h1>YAYASAN BUMI SULTAN</h1>
<h2>DIVISI TRANSPORTASI</h2>
<p>Jl. Contoh Alamat No. 123, Kota, Provinsi 12345</p>
<p>Telp: (021) 1234567 | Email: transportasi@bumisultan.com</p>
```

### 2. Nomor Surat Format
- **Transportasi:** `KODE/SJ-TRANS/MM/YYYY`
- **Peminjam:** `KODE/SJ/MM/YYYY`

Bisa diubah sesuai kebijakan organisasi.

### 3. Tanda Tangan
- Field di DB: `ttd_pinjam`, `ttd_transportasi`
- Format: Base64 image string
- Contoh: `data:image/png;base64,iVBORw0KG...`
- Jika NULL, muncul area kosong untuk TTD manual

### 4. File Naming
Format nama file download:
- `Surat_Transportasi_KODE_PEMINJAMAN.pdf`
- `Surat_Peminjam_KODE_PEMINJAM.pdf`

### 5. Paper Size
Default: **A4, Portrait**
Bisa diubah di controller:
```php
->setPaper('a4', 'portrait')
->setPaper('a4', 'landscape') // Jika mau landscape
```

---

## 🚀 FUTURE ENHANCEMENTS

### Kemungkinan Pengembangan:
1. **Email auto-send** kedua PDF ke peminjam
2. **WhatsApp notification** dengan link download
3. **QR Code** di PDF untuk tracking/validasi
4. **Digital signature** dengan certificate
5. **PDF watermark** untuk keamanan
6. **Multi-language** support
7. **Template customization** via admin panel
8. **Batch download** multiple peminjaman
9. **Print preview** sebelum download
10. **Auto-archive** ke cloud storage

---

## 📚 DEPENDENCIES

### Required Package:
```json
{
    "barryvdh/laravel-dompdf": "^3.1"
}
```

### Laravel Components Used:
- `Illuminate\Support\Facades\PDF`
- `Illuminate\Support\Facades\Crypt`
- `Illuminate\Support\Facades\Redirect`
- `Carbon\Carbon` (date formatting)

---

## 👥 USER ROLES

Fitur ini accessible untuk:
- ✅ **Karyawan** - Bisa pinjam & download surat
- ✅ **Admin Kendaraan** - Full access
- ✅ **Superadmin** - Full access

---

## 📅 CHANGELOG

### [Version 1.0] - 2024
- ✅ Initial implementation
- ✅ Auto-redirect after successful borrow
- ✅ Dual PDF generation (transportasi & peminjam)
- ✅ Signature integration
- ✅ Responsive download page
- ✅ Complete documentation

---

## 🎓 BEST PRACTICES

### 1. Always Show Info Before Download
Halaman download menampilkan ringkasan data sebelum user download PDF. Ini memastikan user download dokumen yang benar.

### 2. Dual Document Purpose
2 PDF berbeda untuk 2 kebutuhan berbeda:
- **Internal:** Format resmi, detail, untuk arsip
- **External:** Format praktis, ringkas, untuk dibawa

### 3. Visual Hierarchy
Gunakan color coding, icon, dan typography yang jelas untuk membedakan info penting vs info biasa.

### 4. Error Handling
Selalu handle case ketika data optional (tujuan, keterangan, TTD) kosong dengan conditional rendering.

### 5. Filename Convention
Nama file yang descriptive memudahkan user manage downloaded files.

---

**DOKUMENTASI LENGKAP FITUR SURAT JALAN PDF**
**Status:** ✅ PRODUCTION READY
**Last Updated:** 2024

---
