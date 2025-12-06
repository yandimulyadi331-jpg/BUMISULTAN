<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\RealisasiDanaOperasional;

echo "=== AI AUTO-DETECT KATEGORI TRANSAKSI ===\n\n";

// Ambil semua transaksi yang belum punya kategori
$transaksi = RealisasiDanaOperasional::whereNull('kategori')
    ->orWhere('kategori', '')
    ->get();

echo "Total transaksi tanpa kategori: " . $transaksi->count() . "\n\n";

if ($transaksi->count() == 0) {
    echo "✓ Semua transaksi sudah memiliki kategori!\n";
    exit;
}

echo "Memproses dengan AI System...\n\n";
echo str_pad("NOMOR", 18) . " | " . str_pad("KETERANGAN", 30) . " | KATEGORI AI\n";
echo str_repeat("-", 80) . "\n";

$updated = 0;

foreach ($transaksi as $t) {
    // Gunakan AI untuk detect kategori
    $kategori = RealisasiDanaOperasional::detectKategoriAI($t->keterangan ?? $t->uraian);
    
    // Update kategori
    $t->kategori = $kategori;
    $t->save();
    
    $keterangan = substr($t->keterangan ?? $t->uraian, 0, 30);
    
    echo str_pad($t->nomor_transaksi, 18) . " | ";
    echo str_pad($keterangan, 30) . " | ";
    echo $kategori . "\n";
    
    $updated++;
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "✓ Berhasil update {$updated} transaksi dengan AI!\n\n";

// Tampilkan ringkasan per kategori
echo "RINGKASAN PER KATEGORI:\n";
echo str_repeat("-", 50) . "\n";

$summary = RealisasiDanaOperasional::selectRaw('kategori, COUNT(*) as total')
    ->groupBy('kategori')
    ->orderBy('total', 'desc')
    ->get();

foreach ($summary as $s) {
    echo str_pad($s->kategori, 30) . " : " . $s->total . " transaksi\n";
}

echo "\n✓ AI System telah menganalisa dan mengkategorikan semua transaksi!\n";
echo "✓ Refresh halaman untuk melihat hasil.\n";
