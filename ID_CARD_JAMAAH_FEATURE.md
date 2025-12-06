# Feature: ID Card Jamaah Premium

## 📋 Overview
Fitur baru untuk generate dan download ID Card Jamaah dengan desain premium dan kualitas tinggi (300 DPI / International Standard).

## 🎨 Desain Spesifikasi

### Ukuran Kartu
- **Standar Internasional**: 85.6 x 53.98 mm
- **DPI**: 300 (High Resolution)
- **Pixel Size**: 1012 x 638 pixels
- **Format Output**: JPG (Quality 95%)

### Layout Desain

```
┌─────────────────────────────────────────────────┐
│  [LOGO]    YAYASAN MASAR                        │  <- Header (Blue #0047AB)
│            ID CARD JAMAAH                       │     Orange Accent Line
├─────────────────────────────────────────────────┤
│ ┌─────────┐  NIK:     25120000XX               │
│ │         │  NAMA:    NAMA JAMAAH              │  <- Data Section
│ │  FOTO   │  ALAMAT:  Jl. Contoh...           │
│ │         │  TTL:     Medan, 01-01-1990      │
│ │ 140x140 │  STATUS:  UMROH (Green)           │
│ │         │  TAHUN:   2025                     │
│ │ px      │  PIN FP:  1234 (Bold Blue)         │
│ └─────────┘                                    │
├─────────────────────────────────────────────────┤
│  Berlaku hingga: 02/12/2030                    │  <- Footer (Light Gray)
└─────────────────────────────────────────────────┘
```

### Elemen Desain
- **Header**: Background biru premium dengan aksen oranye
- **Logo**: Dari `public/assets/template/img/logo/logoyayasan.png`
- **Foto**: Square 140x140px dari folder `public/yayasan_masar/`
- **Data Section**: NIK, Nama, Alamat, TTL, Status Umroh, Tahun Masuk, PIN FP
- **Status Umroh**: 
  - "UMROH" = Warna Hijau (#27AE60)
  - "TIDAK UMROH" = Warna Merah (#E74C3C)
- **PIN Fingerprint**: Ditampilkan dalam warna biru bold
- **Footer**: "Berlaku hingga" + 5 tahun dari tanggal masuk
- **Border**: Frame premium dengan outline biru 2px

### Warna Palet
| Elemen | Warna | Hex Code |
|--------|-------|----------|
| Primary (Header) | Biru | #0047AB |
| Accent (Line) | Oranye | #FF6B35 |
| Text Utama | Hitam Gelap | #1a1a1a |
| Background Footer | Abu-abu Terang | #f5f5f5 |
| Status Umroh | Hijau | #27AE60 |
| Status Tidak Umroh | Merah | #E74C3C |

## 🔧 Implementasi Teknis

### File yang Dimodifikasi

#### 1. **app/Http/Controllers/YayasanMasarController.php**
Menambahkan method baru `generateIdCard($kode_yayasan)`:
```php
public function generateIdCard($kode_yayasan)
{
    // Generate JPG ID Card dengan Intervention Image
    // Return download response dengan filename unik
}
```

**Features**:
- Query jamaah berdasarkan `kode_yayasan`
- Create canvas 1012x638px (300 DPI)
- Insert logo dari public storage
- Draw design elements (header, footer, border)
- Place foto dari database
- Render semua data text
- Return JPG download dengan nama file: `ID_CARD_[NAMA]_[NIK].jpg`

#### 2. **routes/web.php**
Menambahkan route baru:
```php
Route::get('/yayasan-masar/{kode_yayasan}/generate-idcard', 'generateIdCard')
    ->name('yayasan_masar.generateIdCard');
```

#### 3. **resources/views/datamaster/yayasan_masar/index.blade.php**
Menambahkan tombol ID Card di action column:
```blade
<a href="{{ route('yayasan_masar.generateIdCard', $d->kode_yayasan) }}" 
   class="me-2" title="Download ID Card">
    <i class="ti ti-id text-warning"></i>
</a>
```

### Dependencies
- **Intervention/Image**: v3.11 (Sudah terinstall)
- **ImageMagick/GD**: PHP Extension untuk image processing

## 📥 Data Sumber

### Dari Model YayasanMasar:
| Field | Tampil Sebagai | Format |
|-------|---|---|
| `no_identitas` | NIK | Plain text |
| `nama` | NAMA | Plain text (max 25 char) |
| `alamat` | ALAMAT | Plain text (max 22 char) |
| `tempat_lahir` | TTL | Format: "Kota, DD-MM-YYYY" |
| `tanggal_lahir` | TTL | Diformat DD-MM-YYYY |
| `status_umroh` | STATUS | "UMROH" (1) / "TIDAK UMROH" (0) |
| `tanggal_masuk` | TAHUN | Extract year only |
| `pin` | PIN FP | Bold text, Biru |
| `foto` | Foto | Dari `public/yayasan_masar/` |
| `created_at` | Berlaku Hingga | +5 tahun, Format: DD/MM/YYYY |

## 🎯 Cara Penggunaan

### Dari Interface
1. Buka halaman **Data Master > Yayasan Masar**
2. Lihat tabel data jamaah
3. Di kolom aksi, klik tombol **ID Card** (ikon ID card oranye)
4. Otomatis download file JPG dengan nama: `ID_CARD_[NAMA]_[NIK].jpg`

### Output File
- **Format**: JPG (JPEG)
- **Quality**: 95% (High Quality)
- **Nama File**: `ID_CARD_DANI_25120000XX.jpg`
- **Ukuran File**: ~50-100 KB (tergantung kualitas foto)

## 🔒 Validasi & Error Handling

- ✅ Cek keberadaan record jamaah (404 jika tidak ada)
- ✅ Cek keberadaan foto (fallback "NO PHOTO" jika tidak ada)
- ✅ Cek keberadaan logo (skip jika tidak ada)
- ✅ Handle null values di data jamaah
- ✅ Format date dengan timezone support

## 🎨 Personalisasi

Untuk mengubah desain ID Card, edit di `YayasanMasarController::generateIdCard()`:

### Ubah Warna
```php
$primaryColor = '#0047AB';    // Biru header
$accentColor = '#FF6B35';     // Orange accent
$darkText = '#1a1a1a';        // Text color
```

### Ubah Font Size
```php
$text->size(18);  // Change ke ukuran yang diinginkan
```

### Ubah Posisi Elemen
```php
$dataX = $photoX + $photoSize + 30;  // Jarak dari foto
$dataY = $photoY + 10;                // Posisi vertikal
$lineHeight = 25;                     // Spasi antar baris
```

### Ubah Format Footer
```php
$footerText = 'Custom Text: ' . date('d/m/Y', strtotime($jamaah->created_at->addYears(5)));
```

## 📊 Fitur Bonus

### Print Ready
- ID Card sudah siap print dengan ukuran internasional
- Recommended: Print ke kertas 10x15cm dengan printer berkualitas
- Setting printer: Borderless print, Quality: High

### Batch Download
Anda bisa membuat script PHP untuk download semua ID Card:
```php
// Contoh: Generate ID Card untuk semua jamaah
$jamahs = YayasanMasar::all();
foreach($jamahs as $jamaah) {
    // Generate dan simpan ke folder
}
```

## 🔄 Future Enhancements

- [ ] Tambah QR Code untuk security
- [ ] Tambah barcode NIK
- [ ] Pilihan template design (modern, classic, dll)
- [ ] Tambah signature area
- [ ] Support background custom per yayasan
- [ ] Batch print/download multiple cards
- [ ] Preview sebelum download
- [ ] Email ID Card langsung ke jamaah

## 📝 Troubleshooting

### ID Card tidak download
✓ Pastikan user punya permission `yayasan_masar.show`
✓ Pastikan path logo dan foto benar
✓ Check PHP Memory Limit (minimum 256MB)

### Foto tidak muncul di ID Card
✓ Pastikan foto ada di `public/yayasan_masar/[filename]`
✓ Pastikan nama file tersimpan di database
✓ Check folder permissions (755)

### Warna tidak sesuai
✓ Update hex color di method generateIdCard()
✓ Use online color picker untuk menemukan warna yang tepat
✓ Test print terlebih dahulu

### Teks terpotong
✓ Kurangi ukuran text (size property)
✓ Kurangi jumlah karakter di data input
✓ Sesuaikan posisi $dataX dan $dataY

## 📞 Support
Jika ada error atau pertanyaan, check Laravel logs:
```
storage/logs/laravel.log
```

---
**Created**: December 2, 2025
**Status**: ✅ Production Ready
**Version**: 1.0
