<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

echo "🔄 GENERATE SALDO HARIAN DARI REALISASI\n";
echo "========================================\n\n";

// Ambil semua realisasi yang tidak punya pengajuan (dari import)
$realisasiTanpaPengajuan = DB::table('realisasi_dana_operasional')
    ->whereNull('pengajuan_id')
    ->orderBy('tanggal_realisasi')
    ->get();

echo "📊 Realisasi tanpa pengajuan: " . $realisasiTanpaPengajuan->count() . " transaksi\n\n";

// Group by tanggal
$perTanggal = [];
foreach ($realisasiTanpaPengajuan as $r) {
    $tanggal = $r->tanggal_realisasi;
    if (!isset($perTanggal[$tanggal])) {
        $perTanggal[$tanggal] = [
            'masuk' => 0,
            'keluar' => 0,
            'transaksi' => []
        ];
    }
    
    if ($r->tipe_transaksi == 'masuk') {
        $perTanggal[$tanggal]['masuk'] += $r->nominal;
    } else {
        $perTanggal[$tanggal]['keluar'] += $r->nominal;
    }
    $perTanggal[$tanggal]['transaksi'][] = $r;
}

echo "📅 Tanggal dengan transaksi: " . count($perTanggal) . "\n\n";

// Generate saldo harian
$saldoSebelumnya = 0;
$inserted = 0;

foreach ($perTanggal as $tanggal => $data) {
    // Cek apakah saldo untuk tanggal ini sudah ada
    $existing = DB::table('saldo_harian_operasional')
        ->whereDate('tanggal', $tanggal)
        ->first();
    
    if (!$existing) {
        $saldoAkhir = $saldoSebelumnya + $data['masuk'] - $data['keluar'];
        
        DB::table('saldo_harian_operasional')->insert([
            'tanggal' => $tanggal,
            'saldo_awal' => $saldoSebelumnya,
            'dana_masuk' => $data['masuk'],
            'total_realisasi' => $data['keluar'],
            'saldo_akhir' => $saldoAkhir,
            'pengajuan_id' => null,
            'status' => 'closed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        echo "✅ $tanggal | Masuk: " . number_format($data['masuk'], 0, ',', '.') . " | Keluar: " . number_format($data['keluar'], 0, ',', '.') . " | Saldo: " . number_format($saldoAkhir, 0, ',', '.') . "\n";
        
        $saldoSebelumnya = $saldoAkhir;
        $inserted++;
    } else {
        echo "⏭️  $tanggal | Sudah ada saldo\n";
    }
}

echo "\n✅ Selesai! $inserted saldo harian berhasil digenerate.\n";
