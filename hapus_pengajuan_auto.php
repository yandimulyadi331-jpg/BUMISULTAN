<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🗑️  HAPUS PENGAJUAN AUTO-GENERATED\n";
echo "========================================\n\n";

// Hapus pengajuan auto-generated dari import
$deleted = DB::table('pengajuan_dana_operasional')
    ->where('catatan_pencairan', 'Auto-generated dari import Excel')
    ->delete();

echo "✅ Dihapus: $deleted pengajuan auto-generated\n\n";

// Set pengajuan_id = NULL untuk realisasi yang berasal dari import
$updated = DB::table('realisasi_dana_operasional')
    ->whereIn('pengajuan_id', function($query) {
        $query->select('id')
            ->from('pengajuan_dana_operasional')
            ->where('catatan_pencairan', 'Auto-generated dari import Excel');
    })
    ->update(['pengajuan_id' => null]);

echo "✅ Update realisasi: $updated baris (pengajuan_id = NULL)\n\n";

// Cek hasil
$countRealisasi = DB::table('realisasi_dana_operasional')->count();
$countPengajuan = DB::table('pengajuan_dana_operasional')->count();

echo "📊 HASIL AKHIR:\n";
echo "• Realisasi: $countRealisasi baris\n";
echo "• Pengajuan: $countPengajuan baris\n";

$realisasiNoAjuan = DB::table('realisasi_dana_operasional')
    ->whereNull('pengajuan_id')
    ->count();
echo "• Realisasi tanpa pengajuan: $realisasiNoAjuan baris\n\n";

echo "✅ Selesai! Pengajuan auto-generated sudah dihapus.\n";
