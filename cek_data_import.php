<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "📊 CEK DATA SETELAH IMPORT\n";
echo "========================================\n\n";

$countRealisasi = DB::table('realisasi_dana_operasional')->count();
$countPengajuan = DB::table('pengajuan_dana_operasional')->count();
$countSaldo = DB::table('saldo_harian_operasional')->count();

echo "✅ Realisasi: $countRealisasi baris\n";
echo "✅ Pengajuan: $countPengajuan baris\n";
echo "✅ Saldo Harian: $countSaldo baris\n\n";

if ($countRealisasi > 0) {
    echo "📝 DATA REALISASI TERAKHIR:\n";
    echo "========================================\n";
    $data = DB::table('realisasi_dana_operasional')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($data as $item) {
        echo "• {$item->tanggal_realisasi} | {$item->uraian} | Rp " . number_format($item->nominal, 0, ',', '.') . " ({$item->tipe_transaksi})\n";
    }
    
    echo "\n📅 FILTER TANGGAL:\n";
    echo "========================================\n";
    $dataJanuari = DB::table('realisasi_dana_operasional')
        ->whereBetween('tanggal_realisasi', ['2025-01-01', '2025-01-31'])
        ->count();
    echo "• Januari 2025: $dataJanuari transaksi\n";
    
    $dataAll = DB::table('realisasi_dana_operasional')->get();
    echo "\n• Semua tanggal dalam database:\n";
    $dates = [];
    foreach ($dataAll as $r) {
        $month = date('Y-m', strtotime($r->tanggal_realisasi));
        if (!isset($dates[$month])) {
            $dates[$month] = 0;
        }
        $dates[$month]++;
    }
    foreach ($dates as $month => $count) {
        echo "  - $month: $count transaksi\n";
    }
}

if ($countPengajuan > 0) {
    echo "\n📋 DATA PENGAJUAN:\n";
    echo "========================================\n";
    $pengajuan = DB::table('pengajuan_dana_operasional')
        ->orderBy('id', 'desc')
        ->limit(5)
        ->get();
    
    foreach ($pengajuan as $item) {
        echo "• {$item->tanggal_pengajuan} | Status: {$item->status}\n";
    }
}

echo "\n✅ Selesai!\n";
