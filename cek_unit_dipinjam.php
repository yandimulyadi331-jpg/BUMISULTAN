<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== CEK UNIT YANG SEDANG DIPINJAM ===\n\n";

$units = DB::table('inventaris_detail_units')
    ->where('status', 'dipinjam')
    ->get();

echo "Jumlah unit dipinjam: " . $units->count() . "\n\n";

foreach ($units as $unit) {
    echo "Kode Unit: {$unit->kode_unit}\n";
    echo "Status: {$unit->status}\n";
    echo "Dipinjam Oleh: " . ($unit->dipinjam_oleh ?? 'NULL') . "\n";
    echo "Tanggal Pinjam: " . ($unit->tanggal_pinjam ?? 'NULL') . "\n";
    echo "Peminjaman ID: " . ($unit->peminjaman_inventaris_id ?? 'NULL') . "\n";
    
    if ($unit->peminjaman_inventaris_id) {
        $peminjaman = DB::table('peminjaman_inventaris')
            ->where('id', $unit->peminjaman_inventaris_id)
            ->first();
        
        if ($peminjaman) {
            echo "  -> Data Peminjaman ditemukan:\n";
            echo "     Nama Peminjam: " . ($peminjaman->nama_peminjam ?? 'NULL') . "\n";
            echo "     Tanggal Pinjam: {$peminjaman->tanggal_pinjam}\n";
            echo "     Status: {$peminjaman->status_peminjaman}\n";
        } else {
            echo "  -> Data Peminjaman TIDAK DITEMUKAN!\n";
        }
    }
    
    echo "---\n\n";
}

echo "\n=== CEK SEMUA UNIT (LIMIT 10) ===\n\n";

$allUnits = DB::table('inventaris_detail_units')
    ->orderBy('id', 'desc')
    ->limit(10)
    ->get();

foreach ($allUnits as $unit) {
    echo "ID: {$unit->id} | Kode: {$unit->kode_unit} | Status: {$unit->status} | Dipinjam Oleh: " . ($unit->dipinjam_oleh ?? 'NULL') . "\n";
}
