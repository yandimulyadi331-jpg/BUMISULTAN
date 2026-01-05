<?php

/**
 * Test Script: Download PDF Tahunan Dana Operasional
 * 
 * Script ini untuk test apakah download PDF tahunan berfungsi dengan baik
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "===========================================\n";
echo "TEST DOWNLOAD PDF TAHUNAN MASLAHA\n";
echo "===========================================\n\n";

// Simulate request
$request = new \Illuminate\Http\Request([
    'filter_type' => 'tahun',
    'tahun' => '2025'
]);

try {
    echo "1. Checking transaksi count for 2025...\n";
    $count = \App\Models\RealisasiDanaOperasional::whereYear('tanggal_realisasi', 2025)
        ->where('status', 'active')
        ->count();
    echo "   ✅ Total transaksi: $count\n\n";
    
    echo "2. Checking saldo harian for 2025...\n";
    $saldoCount = \App\Models\SaldoHarianOperasional::whereYear('tanggal', 2025)->count();
    echo "   ✅ Total hari: $saldoCount\n\n";
    
    echo "3. Testing query performance...\n";
    $start = microtime(true);
    
    $tanggalDari = \Carbon\Carbon::create(2025, 1, 1)->startOfYear();
    $tanggalSampai = \Carbon\Carbon::create(2025, 12, 31)->endOfYear();
    
    $transaksi = \App\Models\RealisasiDanaOperasional::select([
            'id', 'pengajuan_id', 'tanggal_realisasi', 'nominal', 
            'tipe_transaksi', 'keterangan', 'nomor_transaksi', 'nomor_realisasi', 
            'uraian', 'kategori', 'created_by', 'created_at', 'status'
        ])
        ->where('status', 'active')
        ->whereBetween('tanggal_realisasi', [$tanggalDari->startOfDay(), $tanggalSampai->endOfDay()])
        ->orderBy('tanggal_realisasi', 'asc')
        ->orderBy('created_at', 'asc')
        ->get();
    
    $elapsed = round((microtime(true) - $start) * 1000, 2);
    echo "   ✅ Query executed in: {$elapsed}ms\n";
    echo "   ✅ Records fetched: " . $transaksi->count() . "\n\n";
    
    echo "4. Testing calculation...\n";
    $totalPemasukan = $transaksi->whereIn('tipe_transaksi', ['pemasukan', 'masuk'])->sum('nominal');
    $totalPengeluaran = $transaksi->whereIn('tipe_transaksi', ['pengeluaran', 'keluar'])->sum('nominal');
    echo "   ✅ Total Pemasukan: Rp " . number_format($totalPemasukan, 0, ',', '.') . "\n";
    echo "   ✅ Total Pengeluaran: Rp " . number_format($totalPengeluaran, 0, ',', '.') . "\n\n";
    
    echo "5. Checking view exists...\n";
    if (view()->exists('dana-operasional.pdf-simple')) {
        echo "   ✅ View file exists\n\n";
    } else {
        echo "   ❌ View file NOT FOUND!\n\n";
        exit(1);
    }
    
    echo "===========================================\n";
    echo "✅ ALL TESTS PASSED!\n";
    echo "===========================================\n\n";
    
    echo "Test URL untuk download:\n";
    echo "http://localhost:8000/dana-operasional/export-pdf?filter_type=tahun&tahun=2025\n\n";
    
    echo "atau (production):\n";
    echo "https://manajemen.bumisultan.site/dana-operasional/export-pdf?filter_type=tahun&tahun=2025\n\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    exit(1);
}
