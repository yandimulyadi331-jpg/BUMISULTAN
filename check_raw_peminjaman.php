<?php
/**
 * Check raw database data for waktu_kembali column
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECK RAW DATABASE DATA ===\n\n";

// Get raw data from database
$peminjaman = DB::table('peminjaman_kendaraans')
    ->select('id', 'kode_peminjaman', 'waktu_pinjam', 'waktu_kembali', 'status')
    ->limit(10)
    ->get();

if ($peminjaman->count() > 0) {
    echo "Found " . $peminjaman->count() . " records\n\n";
    
    foreach ($peminjaman as $p) {
        echo "ID: {$p->id}\n";
        echo "Kode: {$p->kode_peminjaman}\n";
        echo "waktu_kembali RAW value: ";
        var_dump($p->waktu_kembali);
        echo "waktu_kembali type: " . gettype($p->waktu_kembali) . "\n";
        
        if (is_string($p->waktu_kembali)) {
            echo "String length: " . strlen($p->waktu_kembali) . "\n";
            echo "First 100 chars: " . substr($p->waktu_kembali, 0, 100) . "\n";
        }
        echo str_repeat("-", 50) . "\n\n";
    }
} else {
    echo "No data found in peminjaman_kendaraans table\n";
}

// Also check kendaraans table
echo "\n=== CHECK KENDARAAN TABLE ===\n\n";
$kendaraan = DB::table('kendaraans')
    ->select('id', 'kode_kendaraan', 'nama_kendaraan')
    ->limit(5)
    ->get();

if ($kendaraan->count() > 0) {
    echo "Found " . $kendaraan->count() . " kendaraan\n\n";
    
    foreach ($kendaraan as $k) {
        echo "ID: {$k->id} - {$k->kode_kendaraan} - {$k->nama_kendaraan}\n";
        
        // Check if this kendaraan has peminjaman
        $count = DB::table('peminjaman_kendaraans')
            ->where('kendaraan_id', $k->id)
            ->count();
        echo "  Peminjaman count: {$count}\n\n";
    }
} else {
    echo "No kendaraan found\n";
}

echo "\n=== END CHECK ===\n";
