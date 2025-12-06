<?php
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CEK TABEL LAPORAN_KEUANGAN ===\n\n";

try {
    // Cek apakah tabel ada
    $tables = DB::select("SHOW TABLES LIKE 'laporan_keuangan'");
    
    if (empty($tables)) {
        echo "❌ ERROR: Tabel 'laporan_keuangan' TIDAK DITEMUKAN!\n";
        echo "Silahkan buat migration dulu.\n";
        exit(1);
    }
    
    echo "✅ Tabel 'laporan_keuangan' ditemukan!\n\n";
    
    // Cek struktur kolom
    echo "Struktur kolom:\n";
    echo "================\n";
    $columns = DB::select('SHOW COLUMNS FROM laporan_keuangan');
    
    foreach($columns as $col) {
        $nullable = $col->Null === 'YES' ? 'NULL' : 'NOT NULL';
        $default = $col->Default !== null ? "DEFAULT: {$col->Default}" : '';
        echo sprintf("%-20s %-30s %-10s %s\n", 
            $col->Field, 
            $col->Type, 
            $nullable,
            $default
        );
    }
    
    echo "\n=== CEK DATA LAPORAN ===\n";
    $count = DB::table('laporan_keuangan')->count();
    echo "Total data: {$count}\n\n";
    
    if ($count > 0) {
        echo "Data terakhir:\n";
        $latest = DB::table('laporan_keuangan')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get(['id', 'periode', 'nama_laporan', 'is_published', 'file_pdf', 'created_at']);
        
        foreach($latest as $lap) {
            $status = $lap->is_published ? '✅ Published' : '⚪ Draft';
            $file = $lap->file_pdf ? '📄 PDF' : '❌ No PDF';
            echo "  ID {$lap->id}: [{$lap->periode}] {$lap->nama_laporan} [{$status}] [{$file}]\n";
        }
    } else {
        echo "⚠️  Belum ada data laporan di database.\n";
        echo "Coba download laporan dulu dari halaman Laporan Keuangan.\n";
    }
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
