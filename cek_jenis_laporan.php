<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== CEK SEMUA LAPORAN ===\n\n";

// Annual Report
$annualReport = DB::table('laporan_keuangan')
    ->where('jenis_laporan', 'LAPORAN_BUDGET')
    ->get();

echo "📊 ANNUAL REPORT: " . $annualReport->count() . " laporan\n";
foreach ($annualReport as $l) {
    echo "  - {$l->nama_laporan} (Published: " . ($l->is_published ? 'YA' : 'TIDAK') . ")\n";
}
echo "\n";

// Dana Operasional
$danaOps = DB::table('laporan_keuangan')
    ->whereIn('jenis_laporan', ['LAPORAN_MINGGUAN', 'LAPORAN_BULANAN', 'LAPORAN_TAHUNAN', 'LAPORAN_CUSTOM'])
    ->get();

echo "💰 DANA OPERASIONAL: " . $danaOps->count() . " laporan\n";
if ($danaOps->isEmpty()) {
    echo "  ❌ KOSONG - Belum ada download dari Dana Operasional\n\n";
    echo "💡 CARA DOWNLOAD:\n";
    echo "  1. Buka menu 'Dana Operasional' (BUKAN Laporan Keuangan)\n";
    echo "  2. Pilih filter bulan/tahun/minggu\n";
    echo "  3. Klik tombol 'Download PDF' (yang di atas tabel)\n";
    echo "  4. PDF akan terdownload DAN tersimpan ke database\n";
} else {
    foreach ($danaOps as $l) {
        echo "  - {$l->nama_laporan} ({$l->jenis_laporan}) - Published: " . ($l->is_published ? 'YA' : 'TIDAK') . "\n";
    }
}
