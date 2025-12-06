<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== DEBUG KEHADIRAN YAYASAN MASAR - DANI ===\n\n";

// Cari by NIK
$dani = YayasanMasar::where('no_identitas', '251200004')->first();

if (!$dani) {
    echo "Mencari dengan NIK 251200004 - Tidak ditemukan\n";
    echo "\nSample data yayasan_masar:\n";
    $samples = YayasanMasar::limit(5)->get(['kode_yayasan', 'nama', 'no_identitas', 'jumlah_kehadiran']);
    foreach ($samples as $s) {
        echo "  " . $s->kode_yayasan . " - " . $s->nama . " (NIK: " . $s->no_identitas . ", Kehadiran: " . $s->jumlah_kehadiran . ")\n";
    }
    die();
}

echo "✅ Ditemukan:\n";
echo "   Kode: " . $dani->kode_yayasan . "\n";
echo "   Nama: " . $dani->nama . "\n";
echo "   NIK: " . $dani->no_identitas . "\n";
echo "   Jumlah Kehadiran: " . $dani->jumlah_kehadiran . "\n\n";

// Cek field-field yang ada di tabel
echo "📋 INFO TABEL yayasan_masar:\n";
$columns = DB::getSchemaBuilder()->getColumnListing('yayasan_masar');
echo "Jumlah kolom: " . count($columns) . "\n";
echo "Kolom-kolom:\n";
foreach ($columns as $col) {
    echo "  - " . $col . "\n";
}

echo "\n";

// Cek apakah ada tabel kehadiran khusus untuk yayasan_masar
echo "📊 CEK TABEL KEHADIRAN:\n";
$tables = DB::select('SHOW TABLES');
echo "Tabel yang ada:\n";
foreach ($tables as $t) {
    $tbl = get_object_vars($t);
    $tbl_name = reset($tbl);
    if (strpos($tbl_name, 'kehadiran') !== false || strpos($tbl_name, 'presensi') !== false) {
        echo "  ✓ " . $tbl_name . "\n";
    }
}

echo "\n";

// Cek presensi_yayasan jika ada
if (DB::getSchemaBuilder()->hasTable('presensi_yayasan')) {
    echo "📅 KEHADIRAN DI TABEL presensi_yayasan:\n";
    $presensi = DB::table('presensi_yayasan')
        ->where('kode_yayasan', $dani->kode_yayasan)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    if ($presensi->count() == 0) {
        echo "  ❌ Tidak ada data presensi\n";
    } else {
        foreach ($presensi as $p) {
            echo "  " . $p->created_at . ": " . (isset($p->jam_masuk) ? $p->jam_masuk : 'N/A') . "\n";
        }
    }
} else {
    echo "❌ Tabel presensi_yayasan tidak ditemukan\n";
}

?>
