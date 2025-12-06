<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Kendaraan;
use App\Models\PeminjamanKendaraan;
use Illuminate\Support\Facades\DB;

echo "=== CREATE DUMMY PEMINJAMAN untuk TEST ===\n\n";

$kendaraan = Kendaraan::first();

if (!$kendaraan) {
    die("❌ Tidak ada kendaraan\n");
}

echo "Kendaraan: {$kendaraan->kode_kendaraan}\n\n";

// Create peminjaman dummy
$peminjaman = PeminjamanKendaraan::create([
    'kode_peminjaman' => 'PINJAM-TEST-' . time(),
    'kendaraan_id' => $kendaraan->id,
    'nama_peminjam' => 'Test User',
    'email_peminjam' => 'test@example.com',
    'no_hp_peminjam' => '08123456789',
    'keperluan' => 'Testing error waktu_kembali',
    'waktu_pinjam' => now(),
    'estimasi_kembali' => now()->addHours(2),
    'waktu_kembali' => null, // NULL dulu
    'status' => 'dipinjam',
    'km_awal' => 1000,
    'latitude_pinjam' => -6.200000,
    'longitude_pinjam' => 106.816666,
]);

echo "✅ Peminjaman created: {$peminjaman->kode_peminjaman}\n";
echo "ID: {$peminjaman->id}\n\n";

// Now test loading
echo "--- Test Load Peminjaman ---\n";
$loaded = PeminjamanKendaraan::find($peminjaman->id);

echo "waktu_kembali type: " . gettype($loaded->waktu_kembali) . "\n";
echo "waktu_kembali value: " . ($loaded->waktu_kembali ?? 'NULL') . "\n\n";

// Test via relation
echo "--- Test via Relation ---\n";
$kendaraanWithPeminjaman = Kendaraan::with('peminjaman')->find($kendaraan->id);

foreach ($kendaraanWithPeminjaman->peminjaman as $p) {
    echo "Peminjaman: {$p->kode_peminjaman}\n";
    echo "  waktu_kembali type: " . gettype($p->waktu_kembali ?? 'null') . "\n";
    echo "  waktu_kembali value: " . ($p->waktu_kembali ?? 'NULL') . "\n";
    
    // Try to access as object
    try {
        echo "  Trying property access: ";
        $wk = $p->waktu_kembali;
        if (is_array($wk)) {
            echo "❌ IS ARRAY!\n";
            echo "  Array content: " . json_encode($wk) . "\n";
        } else {
            echo "✅ OK - " . ($wk ?? 'NULL') . "\n";
        }
    } catch (\Exception $e) {
        echo "❌ ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ Test selesai. Peminjaman ID: {$peminjaman->id}\n";
echo "Decrypt ID untuk URL: " . encrypt($kendaraan->id) . "\n";
