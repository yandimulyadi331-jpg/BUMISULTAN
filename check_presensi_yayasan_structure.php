<?php
/**
 * Script untuk memeriksa struktur tabel presensi_yayasan
 */

require 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

try {
    echo "=== Struktur Tabel presensi_yayasan ===\n";
    
    if (Schema::hasTable('presensi_yayasan')) {
        $columns = Schema::getColumns('presensi_yayasan');
        
        echo "\nKolom yang ada:\n";
        foreach ($columns as $column) {
            echo "- " . $column['name'] . " (" . $column['type'] . ")\n";
        }
        
        // Show sample data
        $sample = DB::table('presensi_yayasan')->limit(1)->get();
        if (count($sample) > 0) {
            echo "\nSample data:\n";
            echo json_encode($sample[0], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            echo "\nTabel kosong\n";
        }
    } else {
        echo "❌ Tabel presensi_yayasan tidak ditemukan\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
?>
