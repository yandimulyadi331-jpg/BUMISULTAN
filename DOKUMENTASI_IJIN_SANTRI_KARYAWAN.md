# DOKUMENTASI IJIN SANTRI KARYAWAN

## Ringkasan Implementasi
Fitur Ijin Santri telah berhasil diintegrasikan ke halaman karyawan dengan mode **READ ONLY** (hanya bisa lihat data).

## Fitur yang Diimplementasikan

### 1. **Routes Baru** (`routes/web.php`)
```php
// Ijin Santri untuk Karyawan (READ ONLY)
Route::prefix('ijin-santri-karyawan')->name('ijin-santri.karyawan.')->controller(\App\Http\Controllers\IjinSantriController::class)->group(function () {
    Route::get('/', 'indexKaryawan')->name('index');
    Route::get('/{id}', 'showKaryawan')->name('show');
    Route::get('/{id}/download-pdf', 'downloadPdfKaryawan')->name('download-pdf');
});
```

### 2. **Controller Methods** (`app/Http/Controllers/IjinSantriController.php`)
Ditambahkan 3 method baru untuk karyawan:
- `indexKaryawan()` - Menampilkan daftar ijin santri dengan filter
- `showKaryawan($id)` - Menampilkan detail ijin santri
- `downloadPdfKaryawan($id)` - Download PDF surat ijin

### 3. **View Files**
#### a. `resources/views/ijin_santri/karyawan/index.blade.php`
- Tampilan daftar ijin santri
- Filter berdasarkan: pencarian, tanggal, dan status
- Default menampilkan ijin hari ini
- Tombol aksi: Lihat Detail & Download PDF
- Responsive untuk mobile

#### b. `resources/views/ijin_santri/karyawan/show.blade.php`
- Timeline status proses ijin
- Informasi lengkap surat ijin
- Data santri
- Detail ijin (tanggal, alasan, catatan)
- Riwayat verifikasi
- Foto surat TTD orang tua (jika sudah kembali)
- Tombol download PDF

### 4. **Dashboard Karyawan**
Menu "Ijin Santri" di dashboard Saung Santri karyawan telah **diaktifkan** dan mengarah ke:
```
Route: ijin-santri.karyawan.index
URL: /ijin-santri-karyawan
```

## Fitur Lengkap

### Filter & Pencarian
- **Pencarian**: No. Surat, Nama Santri, NIS
- **Tanggal**: Dari & Sampai
- **Status**: 
  - Menunggu TTD Ustadz
  - Sudah TTD - Siap Pulang
  - Sedang Pulang
  - Sudah Kembali

### Informasi yang Ditampilkan
1. **Status Timeline**: Visual timeline proses ijin
2. **Informasi Surat**: Nomor surat, status, pembuat, tanggal
3. **Data Santri**: NIS, Nama, Jenis Kelamin, Telepon
4. **Detail Ijin**: Tanggal ijin, rencana kembali, tanggal kembali aktual, alasan, catatan
5. **Riwayat Verifikasi**: Semua tahap verifikasi dengan timestamp dan nama verifikator
6. **Foto Surat**: Foto surat yang sudah di-TTD orang tua (untuk santri yang sudah kembali)

### Akses Karyawan
✅ **Bisa dilakukan:**
- Melihat daftar ijin santri
- Filter dan pencarian data
- Melihat detail lengkap ijin
- Download PDF surat ijin

❌ **Tidak bisa dilakukan:**
- Membuat ijin baru
- Mengedit ijin
- Menghapus ijin
- Melakukan verifikasi (TTD Ustadz, Kepulangan, Kembali)

## Struktur Alur Ijin Santri

### Proses di Admin:
1. **Admin membuat ijin** → Status: `pending` (Menunggu TTD Ustadz)
2. **Download PDF surat** → Serahkan ke Ustadz untuk TTD fisik
3. **Verifikasi TTD Ustadz** → Status: `ttd_ustadz` (Siap Pulang)
4. **Verifikasi Kepulangan** → Status: `dipulangkan` (Sedang Pulang)
5. **Verifikasi Kembali** → Status: `kembali` (Sudah Kembali) + Upload foto surat TTD ortu

### Yang Bisa Dilihat Karyawan:
- Semua data di atas dalam mode READ ONLY
- Timeline visual proses ijin
- Download PDF surat ijin

## Keamanan & Validasi
- ✅ Karyawan hanya memiliki akses READ ONLY
- ✅ Semua action button (edit, delete, verifikasi) tidak tersedia untuk karyawan
- ✅ Routes terpisah untuk karyawan dan admin
- ✅ Method controller terpisah untuk memastikan tidak ada perubahan data
- ✅ Tidak ada modal konfirmasi atau form input untuk karyawan

## Lokasi File

### Routes
- `routes/web.php` (baris ~948-952)

### Controller
- `app/Http/Controllers/IjinSantriController.php` (method: indexKaryawan, showKaryawan, downloadPdfKaryawan)

### Views
- `resources/views/ijin_santri/karyawan/index.blade.php`
- `resources/views/ijin_santri/karyawan/show.blade.php`

### Dashboard
- `resources/views/saungsantri/dashboard-karyawan.blade.php` (menu Ijin Santri diaktifkan)

## Testing

### URL untuk Karyawan:
1. **Dashboard Saung Santri**: `http://127.0.0.1:8000/saungsantri/dashboard-karyawan`
2. **Daftar Ijin Santri**: `http://127.0.0.1:8000/ijin-santri-karyawan`
3. **Detail Ijin**: `http://127.0.0.1:8000/ijin-santri-karyawan/{id}`
4. **Download PDF**: `http://127.0.0.1:8000/ijin-santri-karyawan/{id}/download-pdf`

### Cara Testing:
1. Login sebagai karyawan
2. Buka Dashboard Saung Santri
3. Klik menu "Ijin Santri"
4. Coba filter dan pencarian
5. Klik "Lihat Detail" pada salah satu ijin
6. Download PDF surat ijin

## Catatan Penting
- ⚠️ Tidak ada data yang bisa diubah atau dihapus oleh karyawan
- ⚠️ Semua perubahan data hanya bisa dilakukan melalui halaman admin
- ✅ Karyawan bisa membantu santri melihat status ijin mereka
- ✅ Karyawan bisa membantu download ulang surat ijin jika diperlukan

## Status Badge Warna
- 🟡 **Menunggu TTD Ustadz** (Warning/Kuning)
- 🔵 **TTD Ustadz - Siap Pulang** (Info/Biru)
- 🟣 **Sedang Pulang** (Primary/Ungu)
- 🟢 **Sudah Kembali** (Success/Hijau)

---

**Implementasi Selesai** ✅
Semua fitur telah diimplementasikan dan siap digunakan tanpa mempengaruhi data atau fungsi lain yang sudah ada.
