<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "🔄 RECALCULATE SALDO HARIAN DARI REALISASI\n";
echo "========================================\n\n";

// Ambil semua tanggal yang ada realisasi
$tanggalList = DB::table('realisasi_dana_operasional')
    ->selectRaw('DATE(tanggal_realisasi) as tanggal')
    ->groupBy('tanggal')
    ->orderBy('tanggal', 'asc')
    ->pluck('tanggal');

echo "📅 Tanggal dengan transaksi: " . $tanggalList->count() . "\n\n";

$saldoSebelumnya = 0;

foreach ($tanggalList as $tanggal) {
    // Hitung total masuk dan keluar per tanggal
    $realisasi = DB::table('realisasi_dana_operasional')
        ->whereDate('tanggal_realisasi', $tanggal)
        ->get();
    
    $danaMasuk = $realisasi->where('tipe_transaksi', 'masuk')->sum('nominal');
    $danaKeluar = $realisasi->where('tipe_transaksi', 'keluar')->sum('nominal');
    $saldoAkhir = $saldoSebelumnya + $danaMasuk - $danaKeluar;
    
    // Update atau insert saldo harian
    $existing = DB::table('saldo_harian_operasional')
        ->whereDate('tanggal', $tanggal)
        ->first();
    
    if ($existing) {
        // Update
        DB::table('saldo_harian_operasional')
            ->where('id', $existing->id)
            ->update([
                'saldo_awal' => $saldoSebelumnya,
                'dana_masuk' => $danaMasuk,
                'total_realisasi' => $danaKeluar,
                'saldo_akhir' => $saldoAkhir,
                'updated_at' => now(),
            ]);
        echo "✅ UPDATE: $tanggal | Awal: " . number_format($saldoSebelumnya, 0, ',', '.') . " | Masuk: " . number_format($danaMasuk, 0, ',', '.') . " | Keluar: " . number_format($danaKeluar, 0, ',', '.') . " | Akhir: " . number_format($saldoAkhir, 0, ',', '.') . "\n";
    } else {
        // Insert
        DB::table('saldo_harian_operasional')->insert([
            'tanggal' => $tanggal,
            'saldo_awal' => $saldoSebelumnya,
            'dana_masuk' => $danaMasuk,
            'total_realisasi' => $danaKeluar,
            'saldo_akhir' => $saldoAkhir,
            'pengajuan_id' => null,
            'status' => 'closed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ INSERT: $tanggal | Awal: " . number_format($saldoSebelumnya, 0, ',', '.') . " | Masuk: " . number_format($danaMasuk, 0, ',', '.') . " | Keluar: " . number_format($danaKeluar, 0, ',', '.') . " | Akhir: " . number_format($saldoAkhir, 0, ',', '.') . "\n";
    }
    
    // Saldo akhir hari ini jadi saldo awal besok
    $saldoSebelumnya = $saldoAkhir;
}

echo "\n✅ Selesai! Saldo harian sudah terintegrasi dengan transaksi.\n";
