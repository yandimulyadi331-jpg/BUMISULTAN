<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;
use Illuminate\Support\Facades\DB;

echo "=== DEEP TRACE ERROR waktu_kembali ===\n\n";

// Enable query log
DB::enableQueryLog();

try {
    // Test 1: Load kendaraan dengan ID dari URL
    echo "--- Test 1: Load Kendaraan ---\n";
    $kendaraan = Kendaraan::first(); // Get first available
    
    if (!$kendaraan) {
        die("Kendaraan tidak ditemukan\n");
    }
    
    echo "✅ Kendaraan: {$kendaraan->kode_kendaraan} (ID: {$kendaraan->id})\n\n";
    
    // Test 2: Load peminjaman relation
    echo "--- Test 2: Load Peminjaman (Raw Query) ---\n";
    $peminjamanRaw = DB::table('peminjaman_kendaraans')
        ->where('kendaraan_id', $kendaraan->id)
        ->latest()
        ->limit(10)
        ->get();
    
    echo "Jumlah peminjaman (raw): " . $peminjamanRaw->count() . "\n";
    
    foreach ($peminjamanRaw as $index => $p) {
        echo "\nPeminjaman #{$index}:\n";
        echo "  Type: " . gettype($p) . "\n";
        echo "  Class: " . (is_object($p) ? get_class($p) : 'not object') . "\n";
        echo "  waktu_kembali type: " . gettype($p->waktu_kembali ?? 'null') . "\n";
        echo "  waktu_kembali value: ";
        var_dump($p->waktu_kembali ?? 'NULL');
    }
    
    // Test 3: Load dengan Eloquent eager loading
    echo "\n\n--- Test 3: Load dengan Eloquent Eager Loading ---\n";
    $kendaraanWithRelations = Kendaraan::with([
        'peminjaman' => function($q) { 
            $q->latest()->limit(10); 
        }
    ])->find($kendaraan->id);
    
    echo "Jumlah peminjaman (eloquent): " . $kendaraanWithRelations->peminjaman->count() . "\n";
    
    foreach ($kendaraanWithRelations->peminjaman as $index => $p) {
        echo "\nPeminjaman #{$index}:\n";
        echo "  Type: " . gettype($p) . "\n";
        echo "  Class: " . get_class($p) . "\n";
        echo "  Is Eloquent Model: " . ($p instanceof \Illuminate\Database\Eloquent\Model ? 'YES' : 'NO') . "\n";
        
        // Test akses waktu_kembali
        echo "  Testing waktu_kembali access...\n";
        
        try {
            // Method 1: Direct property access
            echo "    Direct access (\$p->waktu_kembali): ";
            $wk = $p->waktu_kembali;
            echo gettype($wk) . " | ";
            
            if (is_null($wk)) {
                echo "NULL\n";
            } elseif (is_array($wk)) {
                echo "❌ ARRAY! => " . json_encode($wk) . "\n";
            } elseif (is_object($wk)) {
                echo "Object (" . get_class($wk) . ") => " . $wk . "\n";
            } else {
                echo $wk . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
            echo "    File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
        
        // Method 2: getAttribute
        try {
            echo "    getAttribute('waktu_kembali'): ";
            $wk2 = $p->getAttribute('waktu_kembali');
            echo gettype($wk2) . " | ";
            if (is_array($wk2)) {
                echo "❌ ARRAY!\n";
            } else {
                echo "✅ " . ($wk2 ?? 'NULL') . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ ERROR: " . $e->getMessage() . "\n";
        }
        
        // Method 3: Raw attributes
        echo "    Raw attributes['waktu_kembali']: ";
        $raw = $p->getAttributes();
        echo gettype($raw['waktu_kembali'] ?? 'null') . " => ";
        var_dump($raw['waktu_kembali'] ?? 'NULL');
        
        // Check casts
        echo "    Model casts: ";
        var_dump($p->getCasts());
    }
    
    // Test 4: Simulate controller action
    echo "\n\n--- Test 4: Simulate Controller Show Action ---\n";
    $kendaraanController = Kendaraan::with([
        'cabang', 
        'aktivitas' => function($q) { $q->latest()->limit(10); },
        'peminjaman' => function($q) { $q->latest()->limit(10); },
        'services' => function($q) { $q->latest()->limit(10); },
        'jadwalServices',
        'aktivitasAktif',
        'peminjamanAktif',
        'serviceAktif'
    ])->find($kendaraan->id);
    
    echo "Loaded with all relations\n";
    echo "Peminjaman count: " . $kendaraanController->peminjaman->count() . "\n";
    
    if ($kendaraanController->peminjaman->count() > 0) {
        $firstPeminjaman = $kendaraanController->peminjaman->first();
        echo "\nFirst peminjaman:\n";
        echo "  Type: " . get_class($firstPeminjaman) . "\n";
        echo "  waktu_kembali: ";
        try {
            $wk = $firstPeminjaman->waktu_kembali;
            if (is_array($wk)) {
                echo "❌ ARRAY ERROR FOUND!\n";
                echo "  Array content: " . json_encode($wk) . "\n";
            } else {
                echo "✅ " . ($wk ?? 'NULL') . "\n";
            }
        } catch (\Exception $e) {
            echo "❌ EXCEPTION: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n\n--- Queries Executed ---\n";
    $queries = DB::getQueryLog();
    foreach ($queries as $query) {
        echo $query['query'] . "\n";
    }
    
    echo "\n✅ TEST SELESAI\n";
    
} catch (\Exception $e) {
    echo "\n\n❌ FATAL ERROR:\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "\nStack Trace:\n" . $e->getTraceAsString() . "\n";
}
