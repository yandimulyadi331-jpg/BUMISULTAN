<?php

/**
 * Script Demo Testing Auto-Generate Kode Kendaraan
 * 
 * Script ini untuk testing implementasi auto-generate kode kendaraan
 * dengan format berdasarkan jenis: MB (Mobil), MT (Motor), TK (Truk), BS (Bus), LN (Lainnya)
 * 
 * Jalankan: php demo_auto_generate_kode_kendaraan.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Models\Kendaraan;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "=================================================================\n";
echo "       DEMO AUTO-GENERATE KODE KENDARAAN BERDASARKAN JENIS\n";
echo "=================================================================\n";
echo "\n";

// ====================================
// TEST 1: Generate Kode per Jenis
// ====================================
echo "TEST 1: Generate Kode per Jenis Kendaraan\n";
echo "-------------------------------------------\n";

$jenisKendaraan = ['Mobil', 'Motor', 'Truk', 'Bus', 'Lainnya'];
$expectedPrefix = [
    'Mobil' => 'MB',
    'Motor' => 'MT',
    'Truk' => 'TK',
    'Bus' => 'BS',
    'Lainnya' => 'LN'
];

foreach ($jenisKendaraan as $jenis) {
    $kode = Kendaraan::generateKodeKendaraan($jenis);
    $prefix = substr($kode, 0, 2);
    $expected = $expectedPrefix[$jenis];
    
    echo "$jenis:\n";
    echo "  Generated: $kode\n";
    echo "  Prefix: $prefix (Expected: $expected)\n";
    echo "  Status: " . ($prefix === $expected ? '✅ PASS' : '❌ FAIL') . "\n\n";
}

// ====================================
// TEST 2: Generate Kode Lanjutan
// ====================================
echo "TEST 2: Generate Kode Lanjutan per Jenis\n";
echo "-----------------------------------------\n";

foreach ($jenisKendaraan as $jenis) {
    $existing = Kendaraan::where('jenis_kendaraan', $jenis)->count();
    $last = Kendaraan::where('jenis_kendaraan', $jenis)
        ->orderBy('kode_kendaraan', 'desc')
        ->first();
    
    $next = Kendaraan::generateKodeKendaraan($jenis);
    
    echo "$jenis:\n";
    echo "  Jumlah existing: $existing\n";
    
    if ($last) {
        echo "  Kode terakhir: {$last->kode_kendaraan}\n";
        $lastNum = (int) substr($last->kode_kendaraan, 2);
        $expectedNext = $expectedPrefix[$jenis] . str_pad($lastNum + 1, 2, '0', STR_PAD_LEFT);
        echo "  Kode berikutnya: $next\n";
        echo "  Expected: $expectedNext\n";
        echo "  Status: " . ($next === $expectedNext ? '✅ PASS' : '❌ FAIL') . "\n";
    } else {
        $expectedFirst = $expectedPrefix[$jenis] . '01';
        echo "  Kode pertama: $next\n";
        echo "  Expected: $expectedFirst\n";
        echo "  Status: " . ($next === $expectedFirst ? '✅ PASS' : '❌ FAIL') . "\n";
    }
    echo "\n";
}

// ====================================
// TEST 3: Data Summary
// ====================================
echo "TEST 3: Summary Data Kendaraan\n";
echo "-------------------------------\n";

$totalKendaraan = Kendaraan::count();
echo "Total Kendaraan: $totalKendaraan\n\n";

if ($totalKendaraan > 0) {
    echo "Breakdown per Jenis:\n";
    
    foreach ($jenisKendaraan as $jenis) {
        $count = Kendaraan::where('jenis_kendaraan', $jenis)->count();
        $tersedia = Kendaraan::where('jenis_kendaraan', $jenis)
            ->where('status', 'tersedia')->count();
        $dipinjam = Kendaraan::where('jenis_kendaraan', $jenis)
            ->where('status', 'dipinjam')->count();
        $service = Kendaraan::where('jenis_kendaraan', $jenis)
            ->where('status', 'service')->count();
        $keluar = Kendaraan::where('jenis_kendaraan', $jenis)
            ->where('status', 'keluar')->count();
        
        $prefix = $expectedPrefix[$jenis];
        
        echo "\n$jenis ($prefix):\n";
        echo "  Total: $count kendaraan\n";
        echo "  - Tersedia: $tersedia\n";
        echo "  - Dipinjam: $dipinjam\n";
        echo "  - Service: $service\n";
        echo "  - Keluar: $keluar\n";
        
        // List 3 kendaraan pertama
        $kendaraans = Kendaraan::where('jenis_kendaraan', $jenis)
            ->orderBy('kode_kendaraan')
            ->take(3)
            ->get();
        
        if ($kendaraans->count() > 0) {
            echo "  \nSample:\n";
            foreach ($kendaraans as $k) {
                echo "    - {$k->kode_kendaraan}: {$k->nama_kendaraan} ({$k->no_polisi}) - {$k->status}\n";
            }
            
            if ($count > 3) {
                $remaining = $count - 3;
                echo "    ... dan $remaining kendaraan lainnya\n";
            }
        }
    }
}

echo "\n";

// ====================================
// TEST 4: Validasi Konsistensi Kode
// ====================================
echo "TEST 4: Validasi Konsistensi Kode\n";
echo "----------------------------------\n";

// Check duplikat
$duplicates = Kendaraan::select('kode_kendaraan', DB::raw('COUNT(*) as count'))
    ->groupBy('kode_kendaraan')
    ->havingRaw('COUNT(*) > 1')
    ->get();

if ($duplicates->count() > 0) {
    echo "❌ Ditemukan kode duplikat:\n";
    foreach ($duplicates as $dup) {
        echo "  - {$dup->kode_kendaraan} ({$dup->count}x)\n";
    }
} else {
    echo "✅ Tidak ada kode duplikat\n";
}

// Check prefix consistency
$inconsistent = Kendaraan::whereRaw("
    (jenis_kendaraan = 'Mobil' AND kode_kendaraan NOT LIKE 'MB%') OR
    (jenis_kendaraan = 'Motor' AND kode_kendaraan NOT LIKE 'MT%') OR
    (jenis_kendaraan = 'Truk' AND kode_kendaraan NOT LIKE 'TK%') OR
    (jenis_kendaraan = 'Bus' AND kode_kendaraan NOT LIKE 'BS%') OR
    (jenis_kendaraan = 'Lainnya' AND kode_kendaraan NOT LIKE 'LN%')
")->get();

if ($inconsistent->count() > 0) {
    echo "❌ Ditemukan inkonsistensi prefix:\n";
    foreach ($inconsistent as $item) {
        $expectedPrefix = $expectedPrefix[$item->jenis_kendaraan];
        echo "  - {$item->kode_kendaraan} ({$item->jenis_kendaraan}) - Seharusnya: {$expectedPrefix}xx\n";
    }
} else {
    echo "✅ Semua prefix konsisten dengan jenis kendaraan\n";
}

echo "\n";

// ====================================
// TEST 5: Query Pattern Examples
// ====================================
echo "TEST 5: Query Pattern Examples\n";
echo "-------------------------------\n";

// Query by prefix
$mobilCount = Kendaraan::where('kode_kendaraan', 'LIKE', 'MB%')->count();
$motorCount = Kendaraan::where('kode_kendaraan', 'LIKE', 'MT%')->count();
$trukCount = Kendaraan::where('kode_kendaraan', 'LIKE', 'TK%')->count();
$busCount = Kendaraan::where('kode_kendaraan', 'LIKE', 'BS%')->count();

echo "Query: WHERE kode_kendaraan LIKE 'MB%'\n";
echo "  Result: $mobilCount Mobil\n\n";

echo "Query: WHERE kode_kendaraan LIKE 'MT%'\n";
echo "  Result: $motorCount Motor\n\n";

echo "Query: WHERE kode_kendaraan LIKE 'TK%'\n";
echo "  Result: $trukCount Truk\n\n";

echo "Query: WHERE kode_kendaraan LIKE 'BS%'\n";
echo "  Result: $busCount Bus\n\n";

// ====================================
// TEST 6: Jenis Detection from Kode
// ====================================
echo "TEST 6: Deteksi Jenis dari Kode\n";
echo "--------------------------------\n";

$samples = Kendaraan::orderBy('kode_kendaraan')->take(5)->get();

if ($samples->count() > 0) {
    echo "Sample detection:\n";
    foreach ($samples as $sample) {
        $prefix = substr($sample->kode_kendaraan, 0, 2);
        $detectedJenis = array_search($prefix, $expectedPrefix) ?: 'Unknown';
        $actualJenis = $sample->jenis_kendaraan;
        $match = $detectedJenis === $actualJenis ? '✅' : '❌';
        
        echo "  $match {$sample->kode_kendaraan} -> Detected: $detectedJenis | Actual: $actualJenis\n";
    }
} else {
    echo "  ⚠️  Belum ada data kendaraan untuk testing\n";
}

echo "\n";

// ====================================
// Summary
// ====================================
echo "=================================================================\n";
echo "                         TESTING SELESAI\n";
echo "=================================================================\n";
echo "\n";

echo "FORMAT KODE KENDARAAN:\n";
echo "----------------------\n";
echo "Mobil   : MB01, MB02, MB03, ... (MB = Mobil)\n";
echo "Motor   : MT01, MT02, MT03, ... (MT = Motor)\n";
echo "Truk    : TK01, TK02, TK03, ... (TK = Truk)\n";
echo "Bus     : BS01, BS02, BS03, ... (BS = Bus)\n";
echo "Lainnya : LN01, LN02, LN03, ... (LN = Lainnya)\n";
echo "\n";

echo "KEUNTUNGAN:\n";
echo "-----------\n";
echo "✅ Mudah diingat (prefix intuitif)\n";
echo "✅ Kategorisasi jelas per jenis\n";
echo "✅ Auto-generate, tidak perlu input manual\n";
echo "✅ Konsisten dan terhindar dari error\n";
echo "✅ Mudah untuk filtering dan reporting\n";
echo "\n";

echo "CONTOH QUERY:\n";
echo "-------------\n";
echo "// Semua Mobil\n";
echo "\$mobils = Kendaraan::where('kode_kendaraan', 'LIKE', 'MB%')->get();\n\n";

echo "// Semua Motor\n";
echo "\$motors = Kendaraan::where('kode_kendaraan', 'LIKE', 'MT%')->get();\n\n";

echo "// Count by Jenis\n";
echo "\$total = Kendaraan::where('jenis_kendaraan', 'Mobil')->count();\n\n";

echo "Script demo selesai!\n";
echo "\n";
