<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK LOG ERROR ===\n\n";

$logFile = storage_path('logs/laravel.log');

if (file_exists($logFile)) {
    $lines = file($logFile);
    $errorLines = array_filter($lines, function($line) {
        return stripos($line, 'Dana Operasional') !== false || 
               stripos($line, 'saveDana') !== false ||
               stripos($line, 'laporan_keuangan') !== false;
    });
    
    if (empty($errorLines)) {
        echo "❌ Tidak ada error log terkait Dana Operasional\n\n";
        
        // Tampilkan 20 baris terakhir
        echo "20 BARIS TERAKHIR LOG:\n";
        echo str_repeat("-", 70) . "\n";
        $last20 = array_slice($lines, -20);
        foreach ($last20 as $line) {
            echo $line;
        }
    } else {
        echo "ERROR DITEMUKAN:\n";
        echo str_repeat("-", 70) . "\n";
        foreach ($errorLines as $line) {
            echo $line;
        }
    }
} else {
    echo "❌ File log tidak ditemukan: {$logFile}\n";
}

echo "\n\n=== CEK APAKAH METHOD DIPANGGIL ===\n\n";
echo "Coba cari di log apakah ada kata 'saveDana' atau 'Dana Operasional'\n";
