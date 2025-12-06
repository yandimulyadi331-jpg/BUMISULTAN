<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "========================================\n";
echo "CEK KONFIGURASI WA GATEWAY\n";
echo "========================================\n\n";

// Cari tabel pengaturan
$tables = DB::select("SHOW TABLES LIKE '%pengaturan%'");

if (empty($tables)) {
    echo "❌ Tabel pengaturan tidak ditemukan!\n";
    exit(1);
}

echo "Tabel yang ditemukan:\n";
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    echo "- {$tableName}\n";
}

// Ambil data dari tabel pertama yang cocok
$tableName = array_values((array)$tables[0])[0];
echo "\nMenggunakan tabel: {$tableName}\n";
echo str_repeat("-", 80) . "\n";

$config = DB::table($tableName)->first();

if (!$config) {
    echo "❌ Data konfigurasi tidak ditemukan!\n";
    exit(1);
}

echo "\n📋 KONFIGURASI SAAT INI:\n\n";

if (isset($config->domain_wa_gateway)) {
    echo "🌐 Domain WA Gateway: " . ($config->domain_wa_gateway ?: '❌ KOSONG (INI MASALAHNYA!)') . "\n";
} else {
    echo "🌐 Domain WA Gateway: ❌ KOLOM TIDAK ADA\n";
}

if (isset($config->wa_api_key)) {
    $apiKey = $config->wa_api_key;
    echo "🔑 WA API Key: " . ($apiKey ? '***' . substr($apiKey, -4) : '❌ KOSONG (INI MASALAHNYA!)') . "\n";
} else {
    echo "🔑 WA API Key: ❌ KOLOM TIDAK ADA\n";
}

echo "\n" . str_repeat("-", 80) . "\n";

echo "\n🎯 KESIMPULAN:\n";
if (empty($config->domain_wa_gateway) || empty($config->wa_api_key)) {
    echo "\n❌ MASALAH DITEMUKAN!\n";
    echo "\nKonfigurasi WA Gateway belum di-set!\n";
    echo "Silakan isi di menu: General Setting\n";
    echo "\nYang perlu diisi:\n";
    echo "1. Domain WA Gateway (contoh: md.fonnte.com)\n";
    echo "2. WA API Key (dari Fonnte/provider WA Gateway)\n";
    echo "\nSetelah diisi, coba lagi tambah device.\n";
} else {
    echo "\n✅ Konfigurasi sudah ada!\n";
    echo "\nJika masih error, kemungkinan:\n";
    echo "1. Domain/API Key salah\n";
    echo "2. Service WA Gateway tidak bisa diakses\n";
    echo "3. API Key expired atau quota habis\n";
}

echo "\n========================================\n";
