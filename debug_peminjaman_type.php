<?php
/**
 * Debug Script - Cek Type Data Peminjaman Kendaraan
 * Jalankan: php debug_peminjaman_type.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;

echo "=== DEBUG TYPE DATA PEMINJAMAN KENDARAAN ===\n\n";

// Test 1: Query direct PeminjamanKendaraan
echo "TEST 1: Direct Query PeminjamanKendaraan\n";
echo str_repeat("-", 50) . "\n";

$peminjaman = PeminjamanKendaraan::first();
if ($peminjaman) {
    echo "ID: " . $peminjaman->id . "\n";
    echo "Kode: " . $peminjaman->kode_peminjaman . "\n";
    echo "Type of \$peminjaman: " . gettype($peminjaman) . "\n";
    echo "Class: " . get_class($peminjaman) . "\n";
    echo "waktu_kembali value: " . ($peminjaman->waktu_kembali ?? 'NULL') . "\n";
    echo "Type of waktu_kembali: " . gettype($peminjaman->waktu_kembali) . "\n";
    
    if (is_object($peminjaman->waktu_kembali)) {
        echo "waktu_kembali class: " . get_class($peminjaman->waktu_kembali) . "\n";
    }
    
    echo "\nAttribute access test:\n";
    try {
        $test = $peminjaman->waktu_kembali;
        echo "✓ Accessing waktu_kembali: SUCCESS\n";
        echo "  Value: " . ($test ?? 'NULL') . "\n";
    } catch (\Exception $e) {
        echo "✗ Accessing waktu_kembali: ERROR\n";
        echo "  Message: " . $e->getMessage() . "\n";
    }
} else {
    echo "No peminjaman data found\n";
}

echo "\n\n";

// Test 2: Query via Kendaraan relationship
echo "TEST 2: Query via Kendaraan->peminjaman\n";
echo str_repeat("-", 50) . "\n";

$kendaraan = Kendaraan::with('peminjaman')->first();
if ($kendaraan && $kendaraan->peminjaman->count() > 0) {
    echo "Kendaraan: " . $kendaraan->nama_kendaraan . "\n";
    echo "Jumlah peminjaman: " . $kendaraan->peminjaman->count() . "\n\n";
    
    foreach ($kendaraan->peminjaman->take(3) as $index => $p) {
        echo "Peminjaman #" . ($index + 1) . ":\n";
        echo "  Type: " . gettype($p) . "\n";
        
        if (is_object($p)) {
            echo "  Class: " . get_class($p) . "\n";
            echo "  ID: " . $p->id . "\n";
            echo "  Kode: " . $p->kode_peminjaman . "\n";
            
            try {
                $waktu = $p->waktu_kembali;
                echo "  waktu_kembali access: ✓ SUCCESS\n";
                echo "  waktu_kembali value: " . ($waktu ?? 'NULL') . "\n";
                echo "  waktu_kembali type: " . gettype($waktu) . "\n";
            } catch (\Exception $e) {
                echo "  waktu_kembali access: ✗ ERROR\n";
                echo "  Error: " . $e->getMessage() . "\n";
            }
        } else if (is_array($p)) {
            echo "  ⚠ WARNING: Data is ARRAY, not object!\n";
            echo "  Keys: " . implode(", ", array_keys($p)) . "\n";
            echo "  waktu_kembali: " . ($p['waktu_kembali'] ?? 'not set') . "\n";
        }
        
        echo "\n";
    }
} else {
    echo "No kendaraan with peminjaman found\n";
}

echo "\n\n";

// Test 3: Check model casts
echo "TEST 3: Model Configuration\n";
echo str_repeat("-", 50) . "\n";

$model = new PeminjamanKendaraan();
echo "Model: " . get_class($model) . "\n";
echo "Table: " . $model->getTable() . "\n";

$casts = $model->getCasts();
echo "\nCasts defined:\n";
foreach ($casts as $key => $cast) {
    echo "  - {$key}: {$cast}\n";
}

echo "\nFillable attributes:\n";
$fillable = $model->getFillable();
foreach ($fillable as $attr) {
    echo "  - {$attr}\n";
}

echo "\n=== END DEBUG ===\n";
