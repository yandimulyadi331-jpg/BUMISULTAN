# IMPLEMENTASI KODE OTOMATIS GEDUNG, RUANGAN, DAN BARANG

## Overview
Sistem ini mengimplementasikan auto-generate kode yang saling berkaitan antara Gedung, Ruangan, dan Barang dengan format hierarkis.

## Format Kode

### 1. Kode Gedung
- **Format**: `GD01`, `GD02`, `GD03`, ...
- **Prefix**: `GD` (Gedung)
- **Nomor**: 2 digit dengan zero-padding
- **Contoh**: 
  - Gedung pertama: `GD01`
  - Gedung kedua: `GD02`
  - Gedung kesepuluh: `GD10`

### 2. Kode Ruangan
- **Format**: `GD01-RU01`, `GD01-RU02`, `GD02-RU01`, ...
- **Struktur**: `[KODE_GEDUNG]-RU[NOMOR]`
- **Prefix**: `RU` (Ruangan)
- **Nomor**: 2 digit dengan zero-padding, increment per gedung
- **Contoh**:
  - Ruangan pertama di gedung GD01: `GD01-RU01`
  - Ruangan kedua di gedung GD01: `GD01-RU02`
  - Ruangan pertama di gedung GD02: `GD02-RU01`

### 3. Kode Barang
- **Format**: `GD01-RU01-BR01`, `GD01-RU01-BR02`, ...
- **Struktur**: `[KODE_RUANGAN]-BR[NOMOR]`
- **Prefix**: `BR` (Barang)
- **Nomor**: 2 digit dengan zero-padding, increment per ruangan
- **Contoh**:
  - Barang pertama di ruangan GD01-RU01: `GD01-RU01-BR01`
  - Barang kedua di ruangan GD01-RU01: `GD01-RU01-BR02`
  - Barang pertama di ruangan GD01-RU02: `GD01-RU02-BR01`

## Keuntungan Sistem Kode Hierarkis

### 1. Traceability
- Dari kode barang bisa langsung tahu lokasinya:
  - `GD01-RU02-BR05` → Barang ke-5 di Ruangan ke-2 di Gedung 1

### 2. Organisasi Data
- Mudah filtering dan sorting
- Mudah melacak inventaris per gedung atau ruangan
- Mudah identifikasi lokasi barang

### 3. Consistency
- Tidak perlu input manual kode
- Menghindari duplikasi kode
- Format konsisten di seluruh sistem

### 4. Scalability
- Support hingga 99 gedung (GD01 - GD99)
- Support hingga 99 ruangan per gedung
- Support hingga 99 barang per ruangan
- Bisa diperluas jika diperlukan (3 digit, 4 digit, dst)

## Implementasi Teknis

### Model Methods

#### Gedung Model (`app/Models/Gedung.php`)
```php
public static function generateKodeGedung()
{
    $lastGedung = self::orderBy('kode_gedung', 'desc')->first();
    
    if (!$lastGedung) {
        return 'GD01';
    }
    
    $lastNumber = (int) substr($lastGedung->kode_gedung, 2);
    $newNumber = $lastNumber + 1;
    
    return 'GD' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
}
```

#### Ruangan Model (`app/Models/Ruangan.php`)
```php
public static function generateKodeRuangan($gedung_id)
{
    $gedung = Gedung::findOrFail($gedung_id);
    
    $lastRuangan = self::where('gedung_id', $gedung_id)
        ->orderBy('kode_ruangan', 'desc')
        ->first();
    
    if (!$lastRuangan) {
        return $gedung->kode_gedung . '-RU01';
    }
    
    $parts = explode('-RU', $lastRuangan->kode_ruangan);
    $lastNumber = (int) end($parts);
    $newNumber = $lastNumber + 1;
    
    return $gedung->kode_gedung . '-RU' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
}
```

#### Barang Model (`app/Models/Barang.php`)
```php
public static function generateKodeBarang($ruangan_id)
{
    $ruangan = Ruangan::with('gedung')->findOrFail($ruangan_id);
    
    $lastBarang = self::where('ruangan_id', $ruangan_id)
        ->orderBy('kode_barang', 'desc')
        ->first();
    
    if (!$lastBarang) {
        return $ruangan->kode_ruangan . '-BR01';
    }
    
    $parts = explode('-BR', $lastBarang->kode_barang);
    $lastNumber = (int) end($parts);
    $newNumber = $lastNumber + 1;
    
    return $ruangan->kode_ruangan . '-BR' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
}
```

### Controller Implementation

#### GedungController
```php
public function store(Request $request)
{
    // Auto-generate kode gedung
    $kodeGedung = Gedung::generateKodeGedung();
    
    $data = [
        'kode_gedung' => $kodeGedung,
        'nama_gedung' => $request->nama_gedung,
        // ... field lainnya
    ];
    
    Gedung::create($data);
    return Redirect::back()->with(messageSuccess('Data Gedung Berhasil Disimpan dengan Kode: ' . $kodeGedung));
}
```

#### RuanganController
```php
public function store(Request $request, $gedung_id)
{
    // Auto-generate kode ruangan berdasarkan gedung
    $kodeRuangan = Ruangan::generateKodeRuangan($gedung_id);
    
    $data = [
        'kode_ruangan' => $kodeRuangan,
        'gedung_id' => $gedung_id,
        'nama_ruangan' => $request->nama_ruangan,
        // ... field lainnya
    ];
    
    Ruangan::create($data);
    return Redirect::back()->with(messageSuccess('Data Ruangan Berhasil Disimpan dengan Kode: ' . $kodeRuangan));
}
```

#### BarangController
```php
public function store(Request $request, $gedung_id, $ruangan_id)
{
    // Auto-generate kode barang berdasarkan ruangan
    $kodeBarang = Barang::generateKodeBarang($ruangan_id);
    
    $data = [
        'kode_barang' => $kodeBarang,
        'ruangan_id' => $ruangan_id,
        'nama_barang' => $request->nama_barang,
        // ... field lainnya
    ];
    
    Barang::create($data);
    return Redirect::back()->with(messageSuccess('Data Barang Berhasil Disimpan dengan Kode: ' . $kodeBarang));
}
```

## Perubahan pada Form

### Form Create Gedung
- ❌ **Dihapus**: Input manual `kode_gedung`
- ✅ **Ditambahkan**: Alert info menjelaskan kode auto-generate
- ✅ **Ditambahkan**: Pesan sukses menampilkan kode yang digenerate

### Form Create Ruangan
- ❌ **Dihapus**: Input manual `kode_ruangan`
- ✅ **Ditambahkan**: Alert info dengan contoh format kode
- ✅ **Ditambahkan**: Pesan sukses menampilkan kode yang digenerate

### Form Create Barang
- ❌ **Dihapus**: Input manual `kode_barang`
- ✅ **Ditambahkan**: Alert info dengan contoh format kode
- ✅ **Ditambahkan**: Pesan sukses menampilkan kode yang digenerate

## Validasi

### Perubahan Validasi
1. **Gedung**: Tidak perlu validasi `kode_gedung` (required & unique) karena auto-generate
2. **Ruangan**: Tidak perlu validasi `kode_ruangan` (required & unique) karena auto-generate
3. **Barang**: Tidak perlu validasi `kode_barang` (required & unique) karena auto-generate

### JavaScript Validation
- Dihapus validasi client-side untuk field kode
- Fokus validasi pada field lain yang user input

## Contoh Skenario Penggunaan

### Skenario 1: Setup Gedung Baru
1. **Buat Gedung**: 
   - Input: Nama "SAUNG SANTRI"
   - Generated: `GD01`

2. **Buat Ruangan di GD01**:
   - Ruangan 1: "MUSHOLA" → `GD01-RU01`
   - Ruangan 2: "AULA" → `GD01-RU02`
   - Ruangan 3: "KANTOR" → `GD01-RU03`

3. **Buat Barang di GD01-RU01 (MUSHOLA)**:
   - Barang 1: "Sajadah" → `GD01-RU01-BR01`
   - Barang 2: "Al-Quran" → `GD01-RU01-BR02`
   - Barang 3: "Mukena" → `GD01-RU01-BR03`

### Skenario 2: Multi Gedung
1. **Gedung 1**: SAUNG SANTRI → `GD01`
   - Ruangan: MUSHOLA → `GD01-RU01`
     - Barang: Sajadah → `GD01-RU01-BR01`

2. **Gedung 2**: ASRAMA PUTRA → `GD02`
   - Ruangan: KAMAR 1 → `GD02-RU01`
     - Barang: Kasur → `GD02-RU01-BR01`
     - Barang: Lemari → `GD02-RU01-BR02`
   - Ruangan: KAMAR 2 → `GD02-RU02`
     - Barang: Kasur → `GD02-RU02-BR01`

## Query Examples

### Mencari Semua Barang di Gedung Tertentu
```php
$barangs = Barang::whereHas('ruangan.gedung', function($query) {
    $query->where('kode_gedung', 'GD01');
})->get();
```

### Mencari Semua Barang di Ruangan Tertentu
```php
$barangs = Barang::whereHas('ruangan', function($query) {
    $query->where('kode_ruangan', 'GD01-RU01');
})->get();
```

### Filter Barang by Kode Pattern
```php
// Semua barang di Gedung GD01
$barangs = Barang::where('kode_barang', 'LIKE', 'GD01-%')->get();

// Semua barang di Ruangan GD01-RU01
$barangs = Barang::where('kode_barang', 'LIKE', 'GD01-RU01-%')->get();
```

## Maintenance & Troubleshooting

### Reset Kode (Jika Diperlukan)
Jika perlu reset atau ada kesalahan dalam generate kode:

```sql
-- Reset semua kode gedung
UPDATE gedungs SET kode_gedung = CONCAT('GD', LPAD(id, 2, '0'));

-- Reset semua kode ruangan
UPDATE ruangans r 
INNER JOIN gedungs g ON r.gedung_id = g.id
SET r.kode_ruangan = CONCAT(g.kode_gedung, '-RU', LPAD(
    (SELECT COUNT(*) FROM ruangans r2 WHERE r2.gedung_id = r.gedung_id AND r2.id <= r.id), 
    2, '0'
));

-- Reset semua kode barang
UPDATE barangs b 
INNER JOIN ruangans r ON b.ruangan_id = r.id
SET b.kode_barang = CONCAT(r.kode_ruangan, '-BR', LPAD(
    (SELECT COUNT(*) FROM barangs b2 WHERE b2.ruangan_id = b.ruangan_id AND b2.id <= b.id), 
    2, '0'
));
```

### Pengecekan Konsistensi
```php
// Check apakah ada kode duplikat
$duplicateGedung = Gedung::select('kode_gedung')
    ->groupBy('kode_gedung')
    ->havingRaw('COUNT(*) > 1')
    ->get();

$duplicateRuangan = Ruangan::select('kode_ruangan')
    ->groupBy('kode_ruangan')
    ->havingRaw('COUNT(*) > 1')
    ->get();

$duplicateBarang = Barang::select('kode_barang')
    ->groupBy('kode_barang')
    ->havingRaw('COUNT(*) > 1')
    ->get();
```

## Testing

### Test Case 1: Generate Kode Pertama
```php
// Test generate kode gedung pertama
$kode = Gedung::generateKodeGedung();
// Expected: GD01

// Test generate kode ruangan pertama di gedung
$kode = Ruangan::generateKodeRuangan($gedung_id);
// Expected: GD01-RU01

// Test generate kode barang pertama di ruangan
$kode = Barang::generateKodeBarang($ruangan_id);
// Expected: GD01-RU01-BR01
```

### Test Case 2: Generate Kode Lanjutan
```php
// Setelah ada 5 gedung (GD01 - GD05)
$kode = Gedung::generateKodeGedung();
// Expected: GD06

// Setelah ada 10 ruangan di GD01 (GD01-RU01 - GD01-RU10)
$kode = Ruangan::generateKodeRuangan($gedung_id);
// Expected: GD01-RU11

// Setelah ada 99 barang di GD01-RU01
$kode = Barang::generateKodeBarang($ruangan_id);
// Expected: GD01-RU01-BR100 (perlu extend jika mau 3 digit)
```

## Future Enhancements

### 1. Dynamic Digit Length
Jika diperlukan lebih dari 99 item:
```php
// 3 digit: GD001, GD002, ..., GD999
return 'GD' . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
```

### 2. Custom Prefix per Cabang
```php
// Jakarta: JKT-GD01, JKT-GD02
// Bandung: BDG-GD01, BDG-GD02
$prefix = $cabang->kode;
return $prefix . '-GD' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
```

### 3. QR Code Integration
Generate QR Code dari kode barang untuk scanning:
```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$qrCode = QrCode::size(200)->generate($barang->kode_barang);
```

### 4. Barcode Printing
Print barcode label untuk barang:
```php
use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($barang->kode_barang, $generator::TYPE_CODE_128);
```

## Kesimpulan

Sistem auto-generate kode ini memberikan:
- ✅ Konsistensi format kode
- ✅ Hierarki yang jelas (Gedung → Ruangan → Barang)
- ✅ Kemudahan tracking dan traceability
- ✅ Menghindari duplikasi dan error input manual
- ✅ User experience yang lebih baik (less input)

---
**Dibuat**: 29 November 2025
**Versi**: 1.0
**Status**: ✅ Implemented & Tested
