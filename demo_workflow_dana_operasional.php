<?php

/**
 * Demo workflow Dana Operasional → Publish → Karyawan
 * 
 * Script ini menunjukkan alur lengkap dari download PDF hingga karyawan bisa lihat
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║   DEMO WORKFLOW: Dana Operasional → Publish → Karyawan Access       ║\n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n\n";

// STEP 1: Cek laporan Dana Operasional yang tersedia
echo "📊 STEP 1: CEK LAPORAN DANA OPERASIONAL\n";
echo str_repeat("-", 70) . "\n";

$laporanDanaOps = DB::table('laporan_keuangan')
    ->whereIn('jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
    ->orderBy('created_at', 'desc')
    ->get();

if ($laporanDanaOps->isEmpty()) {
    echo "❌ Belum ada laporan Dana Operasional.\n\n";
    echo "💡 CARA MEMBUAT LAPORAN:\n";
    echo "   1. Login sebagai admin\n";
    echo "   2. Buka menu 'Dana Operasional'\n";
    echo "   3. Pilih filter (Bulan/Tahun/Minggu/Range)\n";
    echo "   4. Klik tombol 'Download PDF'\n";
    echo "   5. PDF akan terdownload DAN tersimpan ke database\n\n";
    exit;
}

echo "✅ Ditemukan {$laporanDanaOps->count()} laporan Dana Operasional\n\n";

$draftCount = 0;
$publishedCount = 0;

foreach ($laporanDanaOps as $index => $laporan) {
    $num = $index + 1;
    $statusIcon = $laporan->is_published ? '✅' : '📝';
    $statusText = $laporan->is_published ? 'PUBLISHED' : 'DRAFT';
    
    echo "{$num}. {$statusIcon} [{$statusText}] {$laporan->nama_laporan}\n";
    echo "   Jenis: {$laporan->jenis_laporan}\n";
    echo "   Periode: {$laporan->tanggal_mulai} s/d {$laporan->tanggal_selesai}\n";
    
    if ($laporan->is_published) {
        $publishedCount++;
        echo "   Published: {$laporan->published_at}\n";
    } else {
        $draftCount++;
    }
    echo "\n";
}

// STEP 2: Analisa status publish
echo "\n📈 STEP 2: ANALISA STATUS PUBLISH\n";
echo str_repeat("-", 70) . "\n";
echo "Total Laporan: {$laporanDanaOps->count()}\n";
echo "📝 Draft: {$draftCount}\n";
echo "✅ Published: {$publishedCount}\n\n";

if ($draftCount > 0) {
    echo "⚠️  Ada {$draftCount} laporan yang masih DRAFT\n\n";
    echo "💡 CARA PUBLISH:\n";
    echo "   1. Login sebagai admin\n";
    echo "   2. Buka menu 'Laporan Keuangan'\n";
    echo "   3. Scroll ke section 'Kelola Publish Laporan untuk Karyawan'\n";
    echo "   4. Cari laporan yang ingin dipublish\n";
    echo "   5. Klik tombol 'Publish'\n";
    echo "   6. Status akan berubah menjadi 'Published'\n\n";
}

// STEP 3: Simulasi view karyawan
echo "\n👤 STEP 3: SIMULASI VIEW KARYAWAN\n";
echo str_repeat("-", 70) . "\n";

$publishedLaporan = DB::table('laporan_keuangan')
    ->leftJoin('users as publisher', 'laporan_keuangan.published_by', '=', 'publisher.id')
    ->select(
        'laporan_keuangan.*',
        'publisher.name as publisher_name'
    )
    ->where('laporan_keuangan.is_published', true)
    ->whereIn('laporan_keuangan.jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
    ->orderBy('laporan_keuangan.published_at', 'desc')
    ->get();

if ($publishedLaporan->isEmpty()) {
    echo "❌ Tidak ada laporan yang dipublish untuk karyawan.\n";
    echo "   Karyawan belum bisa melihat laporan apapun.\n\n";
} else {
    echo "✅ Karyawan dapat melihat {$publishedLaporan->count()} laporan:\n\n";
    
    foreach ($publishedLaporan as $index => $laporan) {
        $num = $index + 1;
        echo "{$num}. {$laporan->nama_laporan}\n";
        echo "   Periode: {$laporan->periode}\n";
        echo "   Dipublish oleh: {$laporan->publisher_name}\n";
        echo "   Dipublish pada: {$laporan->published_at}\n";
        
        // Cek file
        $filePath = storage_path('app/public/' . $laporan->file_pdf);
        if (file_exists($filePath)) {
            $fileSize = round(filesize($filePath) / 1024, 2);
            echo "   File PDF: ✅ {$fileSize} KB\n";
        } else {
            echo "   File PDF: ❌ NOT FOUND\n";
        }
        
        echo "\n";
    }
}

// STEP 4: Cek file storage
echo "\n💾 STEP 4: CEK FILE STORAGE\n";
echo str_repeat("-", 70) . "\n";

$storagePath = storage_path('app/public/laporan-keuangan');
if (!is_dir($storagePath)) {
    echo "❌ Folder storage tidak ditemukan: {$storagePath}\n";
    echo "💡 Run: php artisan storage:link\n\n";
} else {
    echo "✅ Folder storage ditemukan: {$storagePath}\n";
    
    $files = glob($storagePath . '/*.pdf');
    $fileCount = count($files);
    
    echo "📁 Total file PDF: {$fileCount}\n\n";
    
    if ($fileCount > 0) {
        echo "Daftar file:\n";
        foreach ($files as $index => $file) {
            $num = $index + 1;
            $filename = basename($file);
            $size = round(filesize($file) / 1024, 2);
            $date = date('Y-m-d H:i:s', filemtime($file));
            echo "{$num}. {$filename} ({$size} KB) - {$date}\n";
        }
    }
}

// STEP 5: Summary & Next Actions
echo "\n\n🎯 SUMMARY & NEXT ACTIONS\n";
echo str_repeat("=", 70) . "\n\n";

if ($laporanDanaOps->isEmpty()) {
    echo "❗ STATUS: Belum ada laporan\n";
    echo "📋 TODO:\n";
    echo "   [ ] 1. Download PDF dari Dana Operasional\n";
    echo "   [ ] 2. Publish laporan dari admin panel\n";
    echo "   [ ] 3. Karyawan bisa lihat di menu Laporan\n";
} elseif ($draftCount == $laporanDanaOps->count()) {
    echo "❗ STATUS: Semua laporan masih DRAFT\n";
    echo "📋 TODO:\n";
    echo "   [✓] 1. Download PDF dari Dana Operasional\n";
    echo "   [ ] 2. Publish laporan dari admin panel ← NEXT STEP\n";
    echo "   [ ] 3. Karyawan bisa lihat di menu Laporan\n";
} elseif ($publishedCount > 0 && $draftCount > 0) {
    echo "⚠️  STATUS: Sebagian laporan sudah dipublish\n";
    echo "📋 TODO:\n";
    echo "   [✓] 1. Download PDF dari Dana Operasional\n";
    echo "   [~] 2. Publish laporan dari admin panel ← IN PROGRESS\n";
    echo "   [✓] 3. Karyawan bisa lihat di menu Laporan\n";
} else {
    echo "✅ STATUS: Semua laporan sudah dipublish!\n";
    echo "📋 DONE:\n";
    echo "   [✓] 1. Download PDF dari Dana Operasional\n";
    echo "   [✓] 2. Publish laporan dari admin panel\n";
    echo "   [✓] 3. Karyawan bisa lihat di menu Laporan\n";
}

echo "\n";
echo "🔗 LINKS:\n";
echo "   Admin - Laporan Keuangan: http://localhost:8000/laporan-keuangan\n";
echo "   Admin - Dana Operasional: http://localhost:8000/dana-operasional\n";
echo "   Karyawan - Laporan: http://localhost:8000/laporan-keuangan-karyawan\n";
echo "\n";

echo "╔══════════════════════════════════════════════════════════════════════╗\n";
echo "║                          END OF DEMO                                 ║\n";
echo "╚══════════════════════════════════════════════════════════════════════╝\n";
