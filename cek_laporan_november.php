<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK LAPORAN NOVEMBER 2025 ===\n\n";

// Cek semua laporan November
$laporanNovember = DB::table('laporan_keuangan')
    ->where('nama_laporan', 'like', '%November%')
    ->orWhere('nama_laporan', 'like', '%Januari%')
    ->orWhere('tanggal_mulai', '>=', '2025-01-01')
    ->orderBy('created_at', 'desc')
    ->get();

if ($laporanNovember->isEmpty()) {
    echo "❌ Tidak ada laporan ditemukan\n";
    echo "\nSemua laporan:\n";
    $all = DB::table('laporan_keuangan')->orderBy('created_at', 'desc')->get();
    foreach ($all as $l) {
        echo "- {$l->nama_laporan} ({$l->jenis_laporan}) - {$l->created_at}\n";
    }
} else {
    foreach ($laporanNovember as $laporan) {
        echo "ID: {$laporan->id}\n";
        echo "Nomor: {$laporan->nomor_laporan}\n";
        echo "Jenis: {$laporan->jenis_laporan}\n";
        echo "Nama: {$laporan->nama_laporan}\n";
        echo "Periode: {$laporan->periode}\n";
        echo "Tanggal: {$laporan->tanggal_mulai} s/d {$laporan->tanggal_selesai}\n";
        echo "Status: {$laporan->status}\n";
        echo "Published: " . ($laporan->is_published ? 'YA' : 'TIDAK') . "\n";
        echo "File PDF: {$laporan->file_pdf}\n";
        
        // Cek file
        if ($laporan->file_pdf) {
            $path = storage_path('app/public/' . $laporan->file_pdf);
            echo "File exists: " . (file_exists($path) ? 'YA' : 'TIDAK') . "\n";
        }
        
        echo "Created: {$laporan->created_at}\n";
        echo "Updated: {$laporan->updated_at}\n";
        echo "\n---\n\n";
    }
}
