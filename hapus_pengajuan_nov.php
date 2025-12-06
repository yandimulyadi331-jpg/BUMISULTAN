<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🗑️  HAPUS PENGAJUAN NOVEMBER 2025\n";
echo "========================================\n\n";

// Disable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=0;');

// Hapus pengajuan tanggal 12 November 2025
$deleted = DB::table('pengajuan_dana_operasional')
    ->whereDate('tanggal_pengajuan', '2025-11-12')
    ->delete();

// Enable foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "✅ Dihapus: $deleted pengajuan (12 November 2025)\n\n";

// Cek hasil
$countPengajuan = DB::table('pengajuan_dana_operasional')->count();
echo "📊 Total pengajuan tersisa: $countPengajuan\n\n";

echo "✅ Selesai!\n";
