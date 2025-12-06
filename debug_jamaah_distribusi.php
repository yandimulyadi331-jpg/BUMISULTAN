<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JamaahMasar;
use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== DATA JAMAAH MASAR ===\n";
$jamaah = JamaahMasar::where('status_aktif', 1)->get();
echo "Total Jamaah MASAR aktif: " . $jamaah->count() . "\n";
foreach ($jamaah->take(5) as $j) {
    echo "- {$j->nama_jamaah} (ID: {$j->id}, Kode: {$j->nomor_jamaah})\n";
}

echo "\n=== DATA YAYASAN MASAR ===\n";
$yayasan = YayasanMasar::where('status_aktif', 1)->get();
echo "Total Yayasan MASAR aktif: " . $yayasan->count() . "\n";
foreach ($yayasan->take(5) as $y) {
    echo "- {$y->nama_jamaah} (Kode: {$y->kode_yayasan})\n";
}

echo "\n=== CEK TABEL DATABASE ===\n";
$tables = DB::select("SHOW TABLES");
foreach ($tables as $table) {
    foreach ($table as $key => $value) {
        if (strpos($value, 'jamaah') !== false || strpos($value, 'yayasan') !== false) {
            echo "- $value\n";
        }
    }
}

echo "\n=== STRUKTUR JAMAAH_MASAR ===\n";
$columns = DB::select("DESCRIBE jamaah_masar");
foreach ($columns as $col) {
    echo "{$col->Field} ({$col->Type})\n";
}
