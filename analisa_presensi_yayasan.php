<?php
// Analisa struktur presensi_yayasan

require_once 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== ANALISA: Struktur presensi_yayasan ===\n\n";

try {
    // 1. Cek struktur kolom
    echo "1️⃣  Struktur Kolom presensi_yayasan:\n";
    $columns = DB::select("SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
                           FROM INFORMATION_SCHEMA.COLUMNS 
                           WHERE TABLE_NAME='presensi_yayasan'
                           ORDER BY ORDINAL_POSITION");
    
    foreach ($columns as $col) {
        echo "   - {$col->COLUMN_NAME}: {$col->COLUMN_TYPE}";
        echo " (NULL: {$col->IS_NULLABLE})";
        echo " (Default: {$col->COLUMN_DEFAULT})\n";
    }

    // 2. Cek sample data
    echo "\n2️⃣  Sample Data presensi_yayasan:\n";
    $data = DB::select("SELECT * FROM presensi_yayasan LIMIT 5");
    
    if ($data) {
        foreach ($data as $row) {
            echo "   - jam_in: " . ($row->jam_in ?? 'NULL') . "\n";
        }
    } else {
        echo "   (No data)\n";
    }

    // 3. Cek constraints
    echo "\n3️⃣  Cek Constraints:\n";
    $constraints = DB::select("SELECT CONSTRAINT_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME 
                               FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                               WHERE TABLE_NAME='presensi_yayasan'");
    
    foreach ($constraints as $c) {
        echo "   - {$c->CONSTRAINT_NAME}: {$c->COLUMN_NAME}";
        if ($c->REFERENCED_TABLE_NAME) {
            echo " → {$c->REFERENCED_TABLE_NAME}";
        }
        echo "\n";
    }

    echo "\n✅ ANALISA SELESAI\n";

} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
?>
