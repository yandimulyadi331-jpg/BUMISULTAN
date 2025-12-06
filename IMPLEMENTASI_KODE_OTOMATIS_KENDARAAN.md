# IMPLEMENTASI KODE OTOMATIS KENDARAAN

## Overview
Sistem auto-generate kode kendaraan dengan format yang mudah diingat berdasarkan jenis kendaraan. Setiap jenis kendaraan memiliki prefix unik yang menunjukkan kategorinya.

## Format Kode Kendaraan

### Prefix Berdasarkan Jenis

| Jenis Kendaraan | Prefix | Contoh Kode | Deskripsi |
|----------------|--------|-------------|-----------|
| **Mobil** | `MB` | MB01, MB02, MB03 | Kendaraan roda 4 (Mobil) |
| **Motor** | `MT` | MT01, MT02, MT03 | Kendaraan roda 2 (Motor) |
| **Truk** | `TK` | TK01, TK02, TK03 | Kendaraan angkut barang (Truk) |
| **Bus** | `BS` | BS01, BS02, BS03 | Kendaraan angkut penumpang (Bus) |
| **Lainnya** | `LN` | LN01, LN02, LN03 | Kendaraan lain-lain |

### Struktur Kode
- **Format**: `[PREFIX][NOMOR]`
- **Prefix**: 2 karakter (singkatan jenis kendaraan)
- **Nomor**: 2 digit dengan zero-padding (01-99)
- **Total**: 4 karakter

### Contoh Implementasi

```
MOBIL:
├── MB01 - Toyota Avanza (Mobil)
├── MB02 - Honda CRV (Mobil)
├── MB03 - Suzuki Ertiga (Mobil)
└── MB04 - Daihatsu Xenia (Mobil)

MOTOR:
├── MT01 - Honda Beat (Motor)
├── MT02 - Yamaha Mio (Motor)
├── MT03 - Suzuki Satria (Motor)
└── MT04 - Kawasaki Ninja (Motor)

TRUK:
├── TK01 - Isuzu Elf (Truk)
├── TK02 - Mitsubishi Colt Diesel (Truk)
└── TK03 - Hino Dutro (Truk)

BUS:
├── BS01 - Mercedes-Benz OH 1526 (Bus)
└── BS02 - Hino RK8 (Bus)
```

## Keuntungan Sistem Kode Ini

### 1. Mudah Diingat
- Prefix intuitif: MB = Mobil, MT = Motor, TK = Truk, BS = Bus
- Hanya 4 karakter, mudah dihapal
- Langsung mencerminkan jenis kendaraan

### 2. Kategorisasi Jelas
- Identifikasi jenis kendaraan dari kode
- Memudahkan sorting dan filtering
- Membantu dalam reporting dan analisis

### 3. Scalability
- Support 99 kendaraan per jenis (01-99)
- Bisa diperluas ke 3 digit jika diperlukan (001-999)

### 4. User Friendly
- Tidak perlu input manual kode
- Sistem otomatis generate
- Konsisten dan terhindar dari error input

## Implementasi Teknis

### Model Method

#### Kendaraan Model (`app/Models/Kendaraan.php`)

```php
/**
 * Generate kode kendaraan otomatis berdasarkan jenis kendaraan
 * Format: 
 * - Mobil: MB01, MB02, MB03, ...
 * - Motor: MT01, MT02, MT03, ...
 * - Truk: TK01, TK02, TK03, ...
 * - Bus: BS01, BS02, BS03, ...
 * - Lainnya: LN01, LN02, LN03, ...
 * 
 * @param string $jenis_kendaraan
 * @return string
 */
public static function generateKodeKendaraan($jenis_kendaraan)
{
    // Mapping jenis kendaraan ke prefix
    $prefixMap = [
        'Mobil' => 'MB',
        'Motor' => 'MT',
        'Truk' => 'TK',
        'Bus' => 'BS',
        'Lainnya' => 'LN'
    ];
    
    $prefix = $prefixMap[$jenis_kendaraan] ?? 'XX';
    
    // Ambil kendaraan terakhir dengan jenis yang sama
    $lastKendaraan = self::where('jenis_kendaraan', $jenis_kendaraan)
        ->where('kode_kendaraan', 'LIKE', $prefix . '%')
        ->orderBy('kode_kendaraan', 'desc')
        ->first();
    
    if (!$lastKendaraan) {
        return $prefix . '01';
    }
    
    // Extract nomor dari kode terakhir (MB01 -> 01)
    $lastNumber = (int) substr($lastKendaraan->kode_kendaraan, 2);
    $newNumber = $lastNumber + 1;
    
    // Format menjadi MB01, MB02, dst dengan 2 digit
    return $prefix . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
}
```

### Controller Implementation

#### KendaraanController (`app/Http/Controllers/KendaraanController.php`)

```php
public function store(Request $request)
{
    $request->validate([
        'nama_kendaraan' => 'required|max:100',
        'jenis_kendaraan' => 'required|in:Mobil,Motor,Truk,Bus,Lainnya',
        'no_polisi' => 'required|max:20|unique:kendaraans,no_polisi',
        'foto' => 'nullable|image|mimes:jpeg,jpg,png|max:2048',
    ]);

    try {
        // Auto-generate kode kendaraan berdasarkan jenis
        $kodeKendaraan = Kendaraan::generateKodeKendaraan($request->jenis_kendaraan);
        
        $data = $request->all();
        $data['kode_kendaraan'] = $kodeKendaraan;
        
        // Handle foto upload
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = 'kendaraan_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/kendaraan'), $filename);
            $data['foto'] = $filename;
        }
        
        Kendaraan::create($data);
        return Redirect::back()->with(messageSuccess('Data Kendaraan Berhasil Disimpan dengan Kode: ' . $kodeKendaraan));
    } catch (\Exception $e) {
        return Redirect::back()->with(messageError($e->getMessage()));
    }
}
```

## Perubahan pada Form

### Form Create Kendaraan
- ❌ **Dihapus**: Input manual `kode_kendaraan`
- ✅ **Ditambahkan**: Alert info dengan daftar prefix kode
- ✅ **Ditambahkan**: Prefix di option select jenis kendaraan (Mobil (MB), Motor (MT), dll)
- ✅ **Ditambahkan**: Pesan sukses menampilkan kode yang digenerate

### Update Alert Info di Form
```html
<div class="alert alert-info mb-3">
    <i class="ti ti-info-circle me-2"></i>
    <strong>Kode Kendaraan akan digenerate otomatis</strong> berdasarkan jenis:
    <ul class="mb-0 mt-2">
        <li>Mobil: MB01, MB02, MB03, ...</li>
        <li>Motor: MT01, MT02, MT03, ...</li>
        <li>Truk: TK01, TK02, TK03, ...</li>
        <li>Bus: BS01, BS02, BS03, ...</li>
        <li>Lainnya: LN01, LN02, LN03, ...</li>
    </ul>
</div>
```

## Contoh Skenario Penggunaan

### Skenario 1: Input Kendaraan Baru

#### Step 1: Tambah Mobil Pertama
```
Input:
- Nama: Toyota Avanza
- Jenis: Mobil
- No Polisi: B 1234 XYZ

Generated:
- Kode: MB01 ✅
```

#### Step 2: Tambah Mobil Kedua
```
Input:
- Nama: Honda CRV
- Jenis: Mobil
- No Polisi: B 5678 ABC

Generated:
- Kode: MB02 ✅
```

#### Step 3: Tambah Motor Pertama
```
Input:
- Nama: Honda Beat
- Jenis: Motor
- No Polisi: B 9999 DEF

Generated:
- Kode: MT01 ✅ (mulai dari 01 karena jenis berbeda)
```

### Skenario 2: Multi Jenis Kendaraan

```
Database State:
- MB01: Toyota Avanza (Mobil)
- MB02: Honda CRV (Mobil)
- MT01: Honda Beat (Motor)
- MT02: Yamaha Mio (Motor)
- TK01: Isuzu Elf (Truk)
- BS01: Mercedes-Benz Bus (Bus)

Next Codes:
- Mobil berikutnya: MB03
- Motor berikutnya: MT03
- Truk berikutnya: TK02
- Bus berikutnya: BS02
- Lainnya pertama: LN01
```

## Query Examples

### 1. Cari Semua Mobil
```php
$mobils = Kendaraan::where('jenis_kendaraan', 'Mobil')
    ->orderBy('kode_kendaraan')
    ->get();
// MB01, MB02, MB03, ...
```

### 2. Cari Semua Motor
```php
$motors = Kendaraan::where('jenis_kendaraan', 'Motor')
    ->orderBy('kode_kendaraan')
    ->get();
// MT01, MT02, MT03, ...
```

### 3. Filter by Kode Pattern
```php
// Semua mobil
$mobils = Kendaraan::where('kode_kendaraan', 'LIKE', 'MB%')->get();

// Semua motor
$motors = Kendaraan::where('kode_kendaraan', 'LIKE', 'MT%')->get();

// Semua truk
$truks = Kendaraan::where('kode_kendaraan', 'LIKE', 'TK%')->get();
```

### 4. Count by Jenis
```php
$summary = [
    'mobil' => Kendaraan::where('jenis_kendaraan', 'Mobil')->count(),
    'motor' => Kendaraan::where('jenis_kendaraan', 'Motor')->count(),
    'truk' => Kendaraan::where('jenis_kendaraan', 'Truk')->count(),
    'bus' => Kendaraan::where('jenis_kendaraan', 'Bus')->count(),
];
```

### 5. Get Jenis dari Kode
```php
function getJenisFromKode($kode) {
    $prefix = substr($kode, 0, 2);
    $jenisMap = [
        'MB' => 'Mobil',
        'MT' => 'Motor',
        'TK' => 'Truk',
        'BS' => 'Bus',
        'LN' => 'Lainnya'
    ];
    return $jenisMap[$prefix] ?? 'Unknown';
}

// Contoh
$jenis = getJenisFromKode('MB05'); // Output: Mobil
$jenis = getJenisFromKode('MT10'); // Output: Motor
```

## Validasi

### Perubahan Validasi
1. **Kendaraan**: Tidak perlu validasi `kode_kendaraan` (required & unique) karena auto-generate
2. **Field Wajib**: `nama_kendaraan`, `jenis_kendaraan`, `no_polisi`

## Integrasi dengan Modul Lain

### 1. Peminjaman Kendaraan
```php
// Di form peminjaman, tampilkan kode dan jenis
$kendaraan = Kendaraan::find($id);
echo $kendaraan->kode_kendaraan . ' - ' . $kendaraan->nama_kendaraan;
// Output: MB01 - Toyota Avanza
```

### 2. Service Kendaraan
```php
// Filter service berdasarkan jenis kendaraan
$serviceMobil = ServiceKendaraan::whereHas('kendaraan', function($q) {
    $q->where('jenis_kendaraan', 'Mobil');
})->get();
```

### 3. Aktivitas Kendaraan
```php
// Tracking aktivitas per jenis
$aktivitasMobil = AktivitasKendaraan::whereHas('kendaraan', function($q) {
    $q->where('kode_kendaraan', 'LIKE', 'MB%');
})->get();
```

## Reporting & Analytics

### Dashboard Summary
```php
// Summary kendaraan by jenis
$summary = [
    'mobil' => [
        'total' => Kendaraan::where('jenis_kendaraan', 'Mobil')->count(),
        'tersedia' => Kendaraan::where('jenis_kendaraan', 'Mobil')
            ->where('status', 'tersedia')->count(),
        'dipinjam' => Kendaraan::where('jenis_kendaraan', 'Mobil')
            ->where('status', 'dipinjam')->count(),
        'service' => Kendaraan::where('jenis_kendaraan', 'Mobil')
            ->where('status', 'service')->count(),
    ],
    'motor' => [
        'total' => Kendaraan::where('jenis_kendaraan', 'Motor')->count(),
        'tersedia' => Kendaraan::where('jenis_kendaraan', 'Motor')
            ->where('status', 'tersedia')->count(),
        'dipinjam' => Kendaraan::where('jenis_kendaraan', 'Motor')
            ->where('status', 'dipinjam')->count(),
        'service' => Kendaraan::where('jenis_kendaraan', 'Motor')
            ->where('status', 'service')->count(),
    ],
    // ... dst untuk Truk, Bus, Lainnya
];
```

## Maintenance & Troubleshooting

### Reset Kode (Jika Diperlukan)
```sql
-- Reset kode mobil
UPDATE kendaraans 
SET kode_kendaraan = CONCAT('MB', LPAD(
    (SELECT COUNT(*) FROM (SELECT * FROM kendaraans) k2 
     WHERE k2.jenis_kendaraan = 'Mobil' AND k2.id <= kendaraans.id), 
    2, '0'
))
WHERE jenis_kendaraan = 'Mobil';

-- Reset kode motor
UPDATE kendaraans 
SET kode_kendaraan = CONCAT('MT', LPAD(
    (SELECT COUNT(*) FROM (SELECT * FROM kendaraans) k2 
     WHERE k2.jenis_kendaraan = 'Motor' AND k2.id <= kendaraans.id), 
    2, '0'
))
WHERE jenis_kendaraan = 'Motor';

-- Ulangi untuk Truk (TK), Bus (BS), Lainnya (LN)
```

### Pengecekan Konsistensi
```php
// Check duplikat kode
$duplicates = Kendaraan::select('kode_kendaraan')
    ->groupBy('kode_kendaraan')
    ->havingRaw('COUNT(*) > 1')
    ->get();

// Check prefix tidak sesuai jenis
$inconsistent = Kendaraan::whereRaw("
    (jenis_kendaraan = 'Mobil' AND kode_kendaraan NOT LIKE 'MB%') OR
    (jenis_kendaraan = 'Motor' AND kode_kendaraan NOT LIKE 'MT%') OR
    (jenis_kendaraan = 'Truk' AND kode_kendaraan NOT LIKE 'TK%') OR
    (jenis_kendaraan = 'Bus' AND kode_kendaraan NOT LIKE 'BS%') OR
    (jenis_kendaraan = 'Lainnya' AND kode_kendaraan NOT LIKE 'LN%')
")->get();
```

## Testing

### Test Case 1: Generate Kode Pertama
```php
// Test mobil pertama
$kode = Kendaraan::generateKodeKendaraan('Mobil');
// Expected: MB01

// Test motor pertama
$kode = Kendaraan::generateKodeKendaraan('Motor');
// Expected: MT01

// Test truk pertama
$kode = Kendaraan::generateKodeKendaraan('Truk');
// Expected: TK01
```

### Test Case 2: Generate Kode Lanjutan
```php
// Setelah ada 5 mobil (MB01-MB05)
$kode = Kendaraan::generateKodeKendaraan('Mobil');
// Expected: MB06

// Setelah ada 10 motor (MT01-MT10)
$kode = Kendaraan::generateKodeKendaraan('Motor');
// Expected: MT11
```

### Test Case 3: Mixed Jenis
```php
// Data existing: MB01, MB02, MT01, TK01
// Next codes should be:
$nextMobil = Kendaraan::generateKodeKendaraan('Mobil');  // MB03
$nextMotor = Kendaraan::generateKodeKendaraan('Motor');  // MT02
$nextTruk = Kendaraan::generateKodeKendaraan('Truk');   // TK02
$nextBus = Kendaraan::generateKodeKendaraan('Bus');     // BS01
```

## Future Enhancements

### 1. Sub-Kategori Kendaraan
Jika diperlukan sub-kategori lebih detail:
```php
// Contoh: Mobil Sedan, Mobil SUV, Motor Matic, Motor Sport
$prefixMap = [
    'Mobil Sedan' => 'MS',
    'Mobil SUV' => 'MU',
    'Motor Matic' => 'MM',
    'Motor Sport' => 'MP',
];
```

### 2. Prefix per Cabang
```php
// Contoh: Jakarta Mobil, Bandung Mobil
$prefix = $cabangCode . '-MB';
// JKT-MB01, BDG-MB01
```

### 3. QR Code untuk Kendaraan
```php
use SimpleSoftwareIO\QrCode\Facades\QrCode;

$qrCode = QrCode::size(200)->generate($kendaraan->kode_kendaraan);
// Generate QR untuk scan cepat
```

### 4. Barcode Label
```php
use Picqer\Barcode\BarcodeGeneratorPNG;

$generator = new BarcodeGeneratorPNG();
$barcode = $generator->getBarcode($kendaraan->kode_kendaraan, $generator::TYPE_CODE_128);
// Print barcode label untuk kendaraan
```

## Kesimpulan

Sistem auto-generate kode kendaraan ini memberikan:
- ✅ **Mudah Diingat**: Prefix intuitif (MB, MT, TK, BS, LN)
- ✅ **Konsistensi**: Format seragam dan terhindar dari duplikasi
- ✅ **Kategorisasi**: Langsung tahu jenis kendaraan dari kode
- ✅ **Scalability**: Support 99 kendaraan per jenis
- ✅ **User Friendly**: Tidak perlu input manual
- ✅ **Integrasi**: Mudah digunakan di modul peminjaman, service, aktivitas

---
**Dibuat**: 29 November 2025
**Versi**: 1.0
**Status**: ✅ Implemented & Ready to Use
