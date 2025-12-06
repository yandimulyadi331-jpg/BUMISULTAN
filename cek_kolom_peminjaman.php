<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

echo "=== CEK STRUKTUR TABEL peminjaman_inventaris ===\n\n";

// Get all columns
$columns = Schema::getColumnListing('peminjaman_inventaris');
echo "Kolom yang ada:\n";
foreach ($columns as $column) {
    echo "- $column\n";
}

echo "\n=== CEK DATA PEMINJAMAN ===\n\n";

// Check for records with null nama_peminjam
$nullCount = DB::table('peminjaman_inventaris')
    ->whereNull('nama_peminjam')
    ->count();

echo "Jumlah record dengan nama_peminjam = NULL: $nullCount\n\n";

// Check for records with empty nama_peminjam
$emptyCount = DB::table('peminjaman_inventaris')
    ->where('nama_peminjam', '')
    ->count();

echo "Jumlah record dengan nama_peminjam = '': $emptyCount\n\n";

// Show sample records
echo "=== 5 RECORD TERBARU ===\n\n";
$records = DB::table('peminjaman_inventaris')
    ->orderBy('created_at', 'desc')
    ->limit(5)
    ->get();

foreach ($records as $record) {
    echo "ID: {$record->id}\n";
    echo "Nama Peminjam: " . ($record->nama_peminjam ?? 'NULL') . "\n";
    echo "Tanggal Pinjam: {$record->tanggal_pinjam}\n";
    echo "Status: {$record->status_peminjaman}\n";
    echo "---\n\n";
}

echo "\n=== CEK PEMINJAMAN AKTIF (status = dipinjam) ===\n\n";
$aktif = DB::table('peminjaman_inventaris')
    ->where('status_peminjaman', 'dipinjam')
    ->get();

echo "Jumlah peminjaman aktif: " . $aktif->count() . "\n\n";

foreach ($aktif as $p) {
    echo "ID: {$p->id}\n";
    echo "Nama Peminjam: " . ($p->nama_peminjam ?? 'NULL') . "\n";
    echo "Inventaris ID: {$p->inventaris_id}\n";
    echo "Unit ID: " . ($p->inventaris_detail_unit_id ?? 'NULL') . "\n";
    echo "---\n\n";
}
