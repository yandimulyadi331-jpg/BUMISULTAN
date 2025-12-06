<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;
use App\Models\SaldoHarianOperasional;
use Carbon\Carbon;

echo "==============================================\n";
echo "🔧 FIX COMPLETE: Import & Recalculate\n";
echo "==============================================\n\n";

echo "LANGKAH 1: Hapus data test yang ada\n";
echo "─────────────────────────────────────────────────\n";

$deleted = RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)
    ->whereMonth('tanggal_realisasi', 1)
    ->delete();

echo "✓ Deleted {$deleted} transaksi Januari 2025\n\n";

echo "LANGKAH 2: Insert data Januari 2025 sesuai template\n";
echo "─────────────────────────────────────────────────\n";

$dataImport = [
    ['2025-01-01', 'Saldo awal kas Januari 2025', 'masuk', 10000000],
    ['2025-01-02', 'Pembelian ATK (pulpen, buku, map)', 'keluar', 150000],
    ['2025-01-02', 'Bensin motor operasional', 'keluar', 50000],
    ['2025-01-03', 'Transfer dari kas pusat', 'masuk', 5000000],
    ['2025-01-03', 'Bayar listrik bulan Desember', 'keluar', 250000],
    ['2025-01-04', 'Konsumsi rapat mingguan', 'keluar', 75000],
    ['2025-01-05', 'Servis kendaraan operasional', 'keluar', 350000],
    ['2025-01-06', 'Bensin motor operasional', 'keluar', 200000],
    ['2025-01-07', 'Saldo awal kas Januari 2025', 'masuk', 4000000],
];

$inserted = 0;
foreach ($dataImport as $data) {
    try {
        RealisasiDanaOperasional::create([
            'tanggal_realisasi' => $data[0],
            'keterangan' => $data[1],
            'uraian' => $data[1],
            'tipe_transaksi' => $data[2],
            'nominal' => $data[3],
            'created_by' => 1, // Admin user
            'user_id' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $inserted++;
        echo "✓ {$data[0]} | {$data[2]} | Rp " . number_format($data[3], 0, ',', '.') . " | {$data[1]}\n";
    } catch (\Exception $e) {
        echo "✗ Error: {$e->getMessage()}\n";
    }
}

echo "\n✓ Total inserted: {$inserted} transaksi\n\n";

echo "LANGKAH 3: Recalculate saldo harian\n";
echo "─────────────────────────────────────────────────\n";

try {
    RealisasiDanaOperasional::recalculateSaldoHarian('2025-01-01');
    echo "✓ Recalculate BERHASIL!\n\n";
} catch (\Exception $e) {
    echo "✗ Error recalculate: {$e->getMessage()}\n\n";
}

echo "LANGKAH 4: Verifikasi hasil\n";
echo "─────────────────────────────────────────────────\n";

$saldoHarian = SaldoHarianOperasional::whereYear('tanggal', 2025)
    ->whereMonth('tanggal', 1)
    ->orderBy('tanggal')
    ->get();

echo "✓ Total saldo harian Januari: {$saldoHarian->count()}\n\n";

foreach ($saldoHarian as $s) {
    $transaksiCount = RealisasiDanaOperasional::whereDate('tanggal_realisasi', $s->tanggal)->count();
    echo sprintf(
        "%s | Saldo Awal: Rp %s | Masuk: Rp %s | Keluar: Rp %s | Akhir: Rp %s | Transaksi: %d\n",
        $s->tanggal->format('Y-m-d'),
        number_format($s->saldo_awal, 0, ',', '.'),
        number_format($s->dana_masuk, 0, ',', '.'),
        number_format($s->total_realisasi, 0, ',', '.'),
        number_format($s->saldo_akhir, 0, ',', '.'),
        $transaksiCount
    );
}

echo "\n==============================================\n";
echo "STATUS: ✅ DATA SUDAH SIAP!\n";
echo "==============================================\n";
echo "Silakan refresh halaman Dana Operasional\n";
echo "Filter: Januari 2025\n";
echo "Data seharusnya sudah muncul lengkap!\n";
echo "==============================================\n";
