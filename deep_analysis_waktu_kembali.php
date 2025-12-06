<?php
/**
 * Deep Analysis Script - Find waktu_kembali Error
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;

echo "=== DEEP ANALYSIS: waktu_kembali Error ===\n\n";

// Test 1: Check if there's any data with problematic waktu_kembali
echo "TEST 1: Raw Database Check\n";
echo str_repeat("-", 60) . "\n";

$rawPeminjaman = DB::table('peminjaman_kendaraans')
    ->select('id', 'kode_peminjaman', 'waktu_pinjam', 'waktu_kembali', 'estimasi_kembali')
    ->get();

echo "Total records: " . $rawPeminjaman->count() . "\n";

if ($rawPeminjaman->count() > 0) {
    foreach ($rawPeminjaman as $row) {
        echo "\nID: {$row->id}\n";
        echo "  waktu_kembali RAW: ";
        var_dump($row->waktu_kembali);
        echo "  Type: " . gettype($row->waktu_kembali) . "\n";
        
        if (is_string($row->waktu_kembali) && !empty($row->waktu_kembali)) {
            // Check if it's valid date format
            try {
                $test = \Carbon\Carbon::parse($row->waktu_kembali);
                echo "  ✓ Can be parsed as date\n";
            } catch (\Exception $e) {
                echo "  ✗ CANNOT parse as date: " . $e->getMessage() . "\n";
            }
        }
    }
}

echo "\n\n";

// Test 2: Check with Eloquent
echo "TEST 2: Eloquent Model Access\n";
echo str_repeat("-", 60) . "\n";

try {
    $peminjaman = PeminjamanKendaraan::first();
    
    if ($peminjaman) {
        echo "Found peminjaman ID: {$peminjaman->id}\n";
        echo "Accessing waktu_kembali...\n";
        
        try {
            $waktu = $peminjaman->waktu_kembali;
            echo "✓ Success! Type: " . gettype($waktu) . "\n";
            if (is_object($waktu)) {
                echo "  Class: " . get_class($waktu) . "\n";
                echo "  Value: " . $waktu . "\n";
            } else {
                echo "  Value: " . var_export($waktu, true) . "\n";
            }
        } catch (\Exception $e) {
            echo "✗ ERROR: " . $e->getMessage() . "\n";
            echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        }
    } else {
        echo "No peminjaman records found\n";
    }
} catch (\Exception $e) {
    echo "ERROR querying peminjaman: " . $e->getMessage() . "\n";
}

echo "\n\n";

// Test 3: Check Kendaraan with peminjaman relation
echo "TEST 3: Kendaraan->peminjaman Relation\n";
echo str_repeat("-", 60) . "\n";

try {
    $kendaraan = Kendaraan::first();
    
    if ($kendaraan) {
        echo "Kendaraan ID: {$kendaraan->id} - {$kendaraan->nama_kendaraan}\n";
        echo "Loading peminjaman relation...\n";
        
        try {
            $kendaraan->load('peminjaman');
            echo "✓ Relation loaded successfully\n";
            echo "  Count: " . $kendaraan->peminjaman->count() . "\n";
            
            if ($kendaraan->peminjaman->count() > 0) {
                foreach ($kendaraan->peminjaman as $index => $p) {
                    echo "\n  Peminjaman #{$index}:\n";
                    echo "    Type: " . gettype($p) . "\n";
                    
                    if (is_object($p)) {
                        echo "    Class: " . get_class($p) . "\n";
                        echo "    ID: {$p->id}\n";
                        
                        try {
                            $wk = $p->waktu_kembali;
                            echo "    waktu_kembali: ✓ " . ($wk ? $wk : 'null') . "\n";
                        } catch (\Exception $e) {
                            echo "    waktu_kembali: ✗ ERROR - " . $e->getMessage() . "\n";
                        }
                    } else {
                        echo "    ⚠ WARNING: Not an object!\n";
                        var_dump($p);
                    }
                }
            }
        } catch (\Exception $e) {
            echo "✗ ERROR loading relation: " . $e->getMessage() . "\n";
            echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            echo "  Trace:\n";
            foreach ($e->getTrace() as $trace) {
                if (isset($trace['file']) && isset($trace['line'])) {
                    echo "    {$trace['file']}:{$trace['line']}\n";
                }
            }
        }
    } else {
        echo "No kendaraan found\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n\n";

// Test 4: Check Model Configuration
echo "TEST 4: Model Configuration Check\n";
echo str_repeat("-", 60) . "\n";

$model = new PeminjamanKendaraan();

echo "Table: " . $model->getTable() . "\n";
echo "Connection: " . $model->getConnectionName() . "\n";

echo "\nCasts:\n";
foreach ($model->getCasts() as $key => $cast) {
    echo "  {$key} => {$cast}\n";
}

echo "\nDates (deprecated but might interfere):\n";
try {
    $dates = $model->getDates();
    foreach ($dates as $date) {
        echo "  - {$date}\n";
    }
} catch (\Exception $e) {
    echo "  (none or error: " . $e->getMessage() . ")\n";
}

echo "\n\n";

// Test 5: Check for Global Scopes or Observers
echo "TEST 5: Global Scopes and Observers\n";
echo str_repeat("-", 60) . "\n";

$reflection = new \ReflectionClass(PeminjamanKendaraan::class);
echo "Class: " . $reflection->getName() . "\n";
echo "File: " . $reflection->getFileName() . "\n";

echo "\nMethods that might affect attributes:\n";
$methods = $reflection->getMethods();
foreach ($methods as $method) {
    $name = $method->getName();
    if (strpos($name, 'get') === 0 && strpos($name, 'Attribute') !== false) {
        echo "  - {$name}\n";
    }
}

echo "\n\n";

// Test 6: Simulate what happens in controller
echo "TEST 6: Simulate Controller Behavior\n";
echo str_repeat("-", 60) . "\n";

try {
    $kendaraan = Kendaraan::with([
        'cabang', 
        'aktivitas' => function($q) { $q->latest()->limit(10); },
        'peminjaman' => function($q) { $q->latest()->limit(10); },
    ])->first();
    
    if ($kendaraan) {
        echo "✓ Controller-style query successful\n";
        echo "  Kendaraan: {$kendaraan->nama_kendaraan}\n";
        echo "  Peminjaman count: " . $kendaraan->peminjaman->count() . "\n";
        
        if ($kendaraan->peminjaman->count() > 0) {
            echo "\n  Testing access to each peminjaman:\n";
            foreach ($kendaraan->peminjaman as $p) {
                echo "    ID {$p->id}: ";
                try {
                    $wk = $p->waktu_kembali;
                    echo "✓ OK (" . ($wk ? 'has value' : 'null') . ")\n";
                } catch (\Exception $e) {
                    echo "✗ ERROR: " . $e->getMessage() . "\n";
                }
            }
        }
    }
} catch (\Exception $e) {
    echo "✗ ERROR: " . $e->getMessage() . "\n";
    echo "This is likely where the error occurs in the actual request!\n";
    echo "\nFull error:\n";
    echo $e->__toString();
}

echo "\n\n=== END ANALYSIS ===\n";
