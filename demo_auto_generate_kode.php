<?php

/**
 * Script Demo Testing Auto-Generate Kode Gedung, Ruangan, Barang
 * 
 * Script ini untuk testing implementasi auto-generate kode
 * dengan format hierarkis: GD01 -> GD01-RU01 -> GD01-RU01-BR01
 * 
 * Jalankan: php demo_auto_generate_kode.php
 */

require __DIR__ . '/vendor/autoload.php';

use App\Models\Gedung;
use App\Models\Ruangan;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n";
echo "=======================================================\n";
echo "  DEMO AUTO-GENERATE KODE GEDUNG, RUANGAN, BARANG\n";
echo "=======================================================\n";
echo "\n";

// ====================================
// TEST 1: Generate Kode Gedung
// ====================================
echo "TEST 1: Generate Kode Gedung\n";
echo "-----------------------------\n";

$kodeGedung1 = Gedung::generateKodeGedung();
echo "Kode Gedung Pertama: " . $kodeGedung1 . "\n";
echo "Expected: GD01\n";
echo "Status: " . ($kodeGedung1 === 'GD01' ? '✅ PASS' : '❌ FAIL') . "\n\n";

// Simulasi sudah ada 5 gedung
echo "Simulasi: Sudah ada 5 gedung (GD01 - GD05)\n";
$existingCount = Gedung::count();
echo "Jumlah gedung saat ini: " . $existingCount . "\n";

if ($existingCount > 0) {
    $lastGedung = Gedung::orderBy('kode_gedung', 'desc')->first();
    echo "Kode gedung terakhir: " . $lastGedung->kode_gedung . "\n";
    
    $nextKode = Gedung::generateKodeGedung();
    echo "Kode gedung berikutnya: " . $nextKode . "\n";
    
    // Parse number dari kode terakhir
    $lastNumber = (int) substr($lastGedung->kode_gedung, 2);
    $expectedKode = 'GD' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
    echo "Expected: " . $expectedKode . "\n";
    echo "Status: " . ($nextKode === $expectedKode ? '✅ PASS' : '❌ FAIL') . "\n\n";
} else {
    echo "⚠️  Belum ada data gedung di database\n\n";
}

// ====================================
// TEST 2: Generate Kode Ruangan
// ====================================
echo "TEST 2: Generate Kode Ruangan\n";
echo "-----------------------------\n";

$gedung = Gedung::first();
if ($gedung) {
    echo "Testing dengan Gedung: " . $gedung->kode_gedung . " - " . $gedung->nama_gedung . "\n";
    
    $kodeRuangan = Ruangan::generateKodeRuangan($gedung->id);
    echo "Kode Ruangan yang akan digenerate: " . $kodeRuangan . "\n";
    
    // Check format
    $isValidFormat = preg_match('/^' . $gedung->kode_gedung . '-RU\d{2}$/', $kodeRuangan);
    echo "Format Valid: " . ($isValidFormat ? '✅ YES' : '❌ NO') . "\n";
    
    // Check jumlah ruangan existing
    $existingRuanganCount = Ruangan::where('gedung_id', $gedung->id)->count();
    echo "Jumlah ruangan existing di gedung ini: " . $existingRuanganCount . "\n";
    
    if ($existingRuanganCount > 0) {
        $lastRuangan = Ruangan::where('gedung_id', $gedung->id)
            ->orderBy('kode_ruangan', 'desc')
            ->first();
        echo "Kode ruangan terakhir: " . $lastRuangan->kode_ruangan . "\n";
        
        $parts = explode('-RU', $lastRuangan->kode_ruangan);
        $lastNumber = (int) end($parts);
        $expectedKode = $gedung->kode_gedung . '-RU' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        echo "Expected: " . $expectedKode . "\n";
        echo "Status: " . ($kodeRuangan === $expectedKode ? '✅ PASS' : '❌ FAIL') . "\n";
    } else {
        $expectedKode = $gedung->kode_gedung . '-RU01';
        echo "Expected (first ruangan): " . $expectedKode . "\n";
        echo "Status: " . ($kodeRuangan === $expectedKode ? '✅ PASS' : '❌ FAIL') . "\n";
    }
    echo "\n";
} else {
    echo "⚠️  Belum ada data gedung di database\n\n";
}

// ====================================
// TEST 3: Generate Kode Barang
// ====================================
echo "TEST 3: Generate Kode Barang\n";
echo "-----------------------------\n";

$ruangan = Ruangan::first();
if ($ruangan) {
    echo "Testing dengan Ruangan: " . $ruangan->kode_ruangan . " - " . $ruangan->nama_ruangan . "\n";
    
    $kodeBarang = Barang::generateKodeBarang($ruangan->id);
    echo "Kode Barang yang akan digenerate: " . $kodeBarang . "\n";
    
    // Check format
    $isValidFormat = preg_match('/^' . preg_quote($ruangan->kode_ruangan, '/') . '-BR\d{2}$/', $kodeBarang);
    echo "Format Valid: " . ($isValidFormat ? '✅ YES' : '❌ NO') . "\n";
    
    // Check jumlah barang existing
    $existingBarangCount = Barang::where('ruangan_id', $ruangan->id)->count();
    echo "Jumlah barang existing di ruangan ini: " . $existingBarangCount . "\n";
    
    if ($existingBarangCount > 0) {
        $lastBarang = Barang::where('ruangan_id', $ruangan->id)
            ->orderBy('kode_barang', 'desc')
            ->first();
        echo "Kode barang terakhir: " . $lastBarang->kode_barang . "\n";
        
        $parts = explode('-BR', $lastBarang->kode_barang);
        $lastNumber = (int) end($parts);
        $expectedKode = $ruangan->kode_ruangan . '-BR' . str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        echo "Expected: " . $expectedKode . "\n";
        echo "Status: " . ($kodeBarang === $expectedKode ? '✅ PASS' : '❌ FAIL') . "\n";
    } else {
        $expectedKode = $ruangan->kode_ruangan . '-BR01';
        echo "Expected (first barang): " . $expectedKode . "\n";
        echo "Status: " . ($kodeBarang === $expectedKode ? '✅ PASS' : '❌ FAIL') . "\n";
    }
    echo "\n";
} else {
    echo "⚠️  Belum ada data ruangan di database\n\n";
}

// ====================================
// TEST 4: Hierarki Kode
// ====================================
echo "TEST 4: Validasi Hierarki Kode\n";
echo "--------------------------------\n";

$barang = Barang::with('ruangan.gedung')->first();
if ($barang) {
    echo "Sample Barang: " . $barang->nama_barang . "\n";
    echo "Kode Barang: " . $barang->kode_barang . "\n";
    
    if ($barang->ruangan) {
        echo "Kode Ruangan (dari relasi): " . $barang->ruangan->kode_ruangan . "\n";
        
        // Check apakah kode barang mengandung kode ruangan
        $containsRuanganKode = strpos($barang->kode_barang, $barang->ruangan->kode_ruangan) !== false;
        echo "Barang kode contains Ruangan kode: " . ($containsRuanganKode ? '✅ YES' : '❌ NO') . "\n";
        
        if ($barang->ruangan->gedung) {
            echo "Kode Gedung (dari relasi): " . $barang->ruangan->gedung->kode_gedung . "\n";
            
            // Check apakah kode barang mengandung kode gedung
            $containsGedungKode = strpos($barang->kode_barang, $barang->ruangan->gedung->kode_gedung) !== false;
            echo "Barang kode contains Gedung kode: " . ($containsGedungKode ? '✅ YES' : '❌ NO') . "\n";
            
            // Validasi format hierarki lengkap
            $expectedPattern = $barang->ruangan->gedung->kode_gedung . '-RU\d{2}-BR\d{2}';
            $isHierarchyValid = preg_match('/^' . $expectedPattern . '$/', $barang->kode_barang);
            echo "Hierarki Valid (GDxx-RUxx-BRxx): " . ($isHierarchyValid ? '✅ YES' : '❌ NO') . "\n";
        }
    }
    echo "\n";
} else {
    echo "⚠️  Belum ada data barang di database\n\n";
}

// ====================================
// TEST 5: Summary Data
// ====================================
echo "TEST 5: Summary Data\n";
echo "--------------------\n";

$totalGedung = Gedung::count();
$totalRuangan = Ruangan::count();
$totalBarang = Barang::count();

echo "Total Gedung: " . $totalGedung . "\n";
echo "Total Ruangan: " . $totalRuangan . "\n";
echo "Total Barang: " . $totalBarang . "\n\n";

if ($totalGedung > 0) {
    echo "Daftar Gedung:\n";
    $gedungs = Gedung::orderBy('kode_gedung')->get();
    foreach ($gedungs as $g) {
        $jumlahRuangan = $g->ruangans()->count();
        $jumlahBarang = Barang::whereHas('ruangan', function($q) use ($g) {
            $q->where('gedung_id', $g->id);
        })->count();
        
        echo "  - {$g->kode_gedung}: {$g->nama_gedung} ({$jumlahRuangan} ruangan, {$jumlahBarang} barang)\n";
        
        // List ruangan
        foreach ($g->ruangans as $r) {
            $jumlahBarangRuangan = $r->barangs()->count();
            echo "    └─ {$r->kode_ruangan}: {$r->nama_ruangan} ({$jumlahBarangRuangan} barang)\n";
            
            // List sample 3 barang pertama
            $barangs = $r->barangs()->take(3)->get();
            foreach ($barangs as $b) {
                echo "       └─ {$b->kode_barang}: {$b->nama_barang}\n";
            }
            
            if ($r->barangs()->count() > 3) {
                $remaining = $r->barangs()->count() - 3;
                echo "       └─ ... dan {$remaining} barang lainnya\n";
            }
        }
    }
}

echo "\n";
echo "=======================================================\n";
echo "  TESTING SELESAI\n";
echo "=======================================================\n";
echo "\n";

// ====================================
// Contoh Query Pattern
// ====================================
echo "CONTOH QUERY PATTERN:\n";
echo "---------------------\n";
echo "\n";

echo "1. Cari semua barang di Gedung tertentu (misal GD01):\n";
echo "   Barang::where('kode_barang', 'LIKE', 'GD01-%')->get()\n\n";

echo "2. Cari semua barang di Ruangan tertentu (misal GD01-RU01):\n";
echo "   Barang::where('kode_barang', 'LIKE', 'GD01-RU01-%')->get()\n\n";

echo "3. Get gedung dari kode barang:\n";
echo "   \$gedungKode = substr(\$barang->kode_barang, 0, 4); // GD01\n\n";

echo "4. Get ruangan dari kode barang:\n";
echo "   \$ruanganKode = substr(\$barang->kode_barang, 0, 10); // GD01-RU01\n\n";

echo "\n";
echo "Script demo selesai!\n";
echo "\n";
