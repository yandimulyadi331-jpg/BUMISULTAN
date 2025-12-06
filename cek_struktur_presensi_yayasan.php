<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== STRUKTUR TABEL presensi_yayasan ===\n\n";

// Cek struktur
$columns = DB::select("DESCRIBE presensi_yayasan");
echo "Kolom-kolom:\n";
foreach ($columns as $col) {
    printf("%-20s | %-15s | %-20s | %s\n",
        $col->Field,
        $col->Type,
        ($col->Null == 'NO' ? 'NOT NULL' : 'NULL'),
        ($col->Key ?: 'none')
    );
}

echo "\n";

// Sample data
echo "Sample Data presensi_yayasan:\n";
$samples = DB::table('presensi_yayasan')->limit(5)->get();
echo "Total records: " . DB::table('presensi_yayasan')->count() . "\n";

if ($samples->count() > 0) {
    foreach ($samples as $row) {
        echo "\nRecord: " . json_encode($row, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
    }
}

// Cek Model
echo "\n\nModel PresensiiYayasan:\n";
if (file_exists(__DIR__ . '/app/Models/PresensiYayasan.php')) {
    echo "✅ File ada\n";
} else {
    echo "❌ File tidak ada\n";
}

?>
