<?php
/**
 * Script untuk test toggle checklist perawatan
 * Jalankan: php test_toggle_checklist.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ChecklistPeriodeConfig;

echo "========================================\n";
echo "TEST TOGGLE CHECKLIST PERAWATAN\n";
echo "========================================\n\n";

// Tampilkan status config saat ini
echo "📊 Status Config Saat Ini:\n";
echo "----------------------------------------\n";

$configs = ChecklistPeriodeConfig::orderByRaw("
    CASE tipe_periode
        WHEN 'harian' THEN 1
        WHEN 'mingguan' THEN 2
        WHEN 'bulanan' THEN 3
        WHEN 'tahunan' THEN 4
    END
")->get();

foreach ($configs as $config) {
    $status = $config->is_enabled ? '✅ AKTIF' : '❌ NONAKTIF';
    $mandatory = $config->is_mandatory ? '(WAJIB)' : '(OPSIONAL)';
    
    echo sprintf(
        "%-12s : %s %s\n",
        strtoupper($config->tipe_periode),
        $status,
        $config->is_enabled ? $mandatory : ''
    );
}

echo "\n📋 Demonstrasi Toggle:\n";
echo "----------------------------------------\n";

// Test: Nonaktifkan TAHUNAN
echo "\n1️⃣ Menonaktifkan checklist TAHUNAN...\n";
$tahunan = ChecklistPeriodeConfig::where('tipe_periode', 'tahunan')->first();
$tahunan->is_enabled = false;
$tahunan->save();
echo "   ✅ Checklist TAHUNAN sekarang NONAKTIF\n";
echo "   ℹ️  Menu 'Tahunan' di aplikasi karyawan akan hilang\n";

// Test: Nonaktifkan BULANAN
echo "\n2️⃣ Menonaktifkan checklist BULANAN...\n";
$bulanan = ChecklistPeriodeConfig::where('tipe_periode', 'bulanan')->first();
$bulanan->is_enabled = false;
$bulanan->save();
echo "   ✅ Checklist BULANAN sekarang NONAKTIF\n";
echo "   ℹ️  Menu 'Bulanan' di aplikasi karyawan akan hilang\n";

// Tampilkan hasil akhir
echo "\n📊 Status Config Setelah Toggle:\n";
echo "----------------------------------------\n";

$configs = ChecklistPeriodeConfig::orderByRaw("
    CASE tipe_periode
        WHEN 'harian' THEN 1
        WHEN 'mingguan' THEN 2
        WHEN 'bulanan' THEN 3
        WHEN 'tahunan' THEN 4
    END
")->get();

foreach ($configs as $config) {
    $status = $config->is_enabled ? '✅ AKTIF' : '❌ NONAKTIF';
    $mandatory = $config->is_mandatory ? '(WAJIB)' : '(OPSIONAL)';
    
    echo sprintf(
        "%-12s : %s %s\n",
        strtoupper($config->tipe_periode),
        $status,
        $config->is_enabled ? $mandatory : ''
    );
}

echo "\n✅ TESTING SELESAI!\n";
echo "========================================\n\n";

echo "🔍 Yang Akan Terlihat di Aplikasi Karyawan:\n";
echo "   - Menu HARIAN    : ✅ MUNCUL (Aktif)\n";
echo "   - Menu MINGGUAN  : ✅ MUNCUL (Aktif)\n";
echo "   - Menu BULANAN   : ❌ HILANG (Nonaktif)\n";
echo "   - Menu TAHUNAN   : ❌ HILANG (Nonaktif)\n\n";

echo "💡 Untuk Mengembalikan:\n";
echo "   UPDATE checklist_periode_config SET is_enabled = 1 WHERE tipe_periode IN ('bulanan', 'tahunan');\n\n";
