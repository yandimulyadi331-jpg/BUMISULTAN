<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\YayasanMasar;
use Illuminate\Support\Facades\DB;

echo "=== ANALISIS LENGKAP KEHADIRAN DANI (Yayasan Masar) ===\n\n";

// Cari by kode_yayasan (bukan NIK)
$dani = YayasanMasar::where('kode_yayasan', '251200004')->first();

echo "✅ Data Dani:\n";
echo "   Kode Yayasan: " . $dani->kode_yayasan . "\n";
echo "   Nama: " . $dani->nama . "\n";
echo "   NIK: " . $dani->no_identitas . "\n";
echo "   Tanggal Masuk: " . $dani->tanggal_masuk . "\n";
echo "   Status: " . $dani->status . "\n";
echo "   Jumlah Kehadiran SEKARANG: " . $dani->jumlah_kehadiran . " ❌ (Seharusnya bertambah!)\n\n";

// Cek semua field
echo "📋 SEMUA FIELD DANI:\n";
$allFields = $dani->attributesToArray();
foreach ($allFields as $key => $value) {
    echo "   $key: " . ($value ?? '-') . "\n";
}

echo "\n";

// Cek tabel kehadiran untuk yayasan
echo "🔍 CEK TABEL KEHADIRAN:\n";
echo str_repeat("-", 80) . "\n";

// 1. Cek presensi_yayasan
if (DB::getSchemaBuilder()->hasTable('presensi_yayasan')) {
    echo "1️⃣  Tabel: presensi_yayasan\n";
    $presensi = DB::table('presensi_yayasan')
        ->where('kode_yayasan', $dani->kode_yayasan)
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
    
    if ($presensi->count() == 0) {
        echo "   ❌ Tidak ada data\n";
    } else {
        echo "   Total: " . $presensi->count() . " record terbaru:\n";
        foreach ($presensi as $p) {
            echo "      - " . substr($p->created_at, 0, 10) . ": ";
            foreach ((array)$p as $k => $v) {
                if ($k != 'id' && $k != 'kode_yayasan' && $v) {
                    echo $k . "=" . $v . " ";
                }
            }
            echo "\n";
        }
    }
    echo "\n";
} else {
    echo "❌ Tabel presensi_yayasan tidak ada\n\n";
}

// 2. Cek kehadiran_jamaah (untuk Jamaah Masar)
if (DB::getSchemaBuilder()->hasTable('kehadiran_jamaah_masar')) {
    echo "2️⃣  Tabel: kehadiran_jamaah_masar (untuk Jamaah, bukan Yayasan)\n";
    $kehadiran = DB::table('kehadiran_jamaah_masar')
        ->where('jamaah_id', $dani->kode_yayasan) // Try with kode_yayasan
        ->limit(5)
        ->get();
    echo "   (Tidak relevan - ini untuk jamaah)\n\n";
}

// 3. Cek log error
echo "📝 CEK LOG ERROR:\n";
echo str_repeat("-", 80) . "\n";

$logFile = __DIR__ . '/storage/logs/laravel.log';
if (file_exists($logFile)) {
    $lines = file($logFile);
    $recentErrors = array_slice($lines, -30); // Last 30 lines
    
    $hasError = false;
    foreach ($recentErrors as $line) {
        if (strpos($line, 'error') !== false || strpos($line, 'Error') !== false || 
            strpos($line, 'fingerprint') !== false || strpos($line, 'kehadiran') !== false) {
            echo $line;
            $hasError = true;
        }
    }
    
    if (!$hasError) {
        echo "Tidak ada error terkait di log (30 baris terakhir)\n";
    }
} else {
    echo "File log tidak ditemukan\n";
}

echo "\n";

// 4. Cek struktur tabel yayasan_masar
echo "📊 STRUKTUR TABEL yayasan_masar:\n";
echo str_repeat("-", 80) . "\n";

$columns = DB::select("DESCRIBE yayasan_masar");
$hasKehadiran = false;
foreach ($columns as $col) {
    if (strpos($col->Field, 'kehadiran') !== false) {
        echo "   ✅ " . $col->Field . " (" . $col->Type . ")\n";
        $hasKehadiran = true;
    }
}

if (!$hasKehadiran) {
    echo "   ❌ Tidak ada kolom 'jumlah_kehadiran'???\n";
}

echo "\n";

// 5. Rekomendasi
echo "💡 ANALISIS & REKOMENDASI:\n";
echo str_repeat("-", 80) . "\n";

echo "TEMUAN:\n";
echo "1. Dani (251200004) ada di tabel yayasan_masar (untuk karyawan)\n";
echo "2. jumlah_kehadiran = " . $dani->jumlah_kehadiran . " (TIDAK BERTAMBAH)\n";
echo "3. Tabel presensi_yayasan (untuk karyawan) - status: " . 
    (DB::getSchemaBuilder()->hasTable('presensi_yayasan') ? "ADA" : "TIDAK ADA") . "\n\n";

echo "MASALAH:\n";
if ($dani->jumlah_kehadiran == 0) {
    echo "❌ Dani belum pernah tercatat scan di sistem\n";
    echo "   Kemungkinan:\n";
    echo "   a) Proses sync dari fingerprint belum berjalan\n";
    echo "   b) Scan Dani belum diproses melalui sistem\n";
    echo "   c) Ada error dalam proses update\n\n";
    
    echo "SOLUSI:\n";
    echo "1. Cek apakah ada proses background untuk sync kehadiran yayasan\n";
    echo "2. Cek log untuk error saat scan\n";
    echo "3. Manual update untuk testing:\n";
    echo "   UPDATE yayasan_masar SET jumlah_kehadiran = 1 WHERE kode_yayasan = '251200004';\n";
} else {
    echo "✅ Dani sudah punya kehadiran: " . $dani->jumlah_kehadiran . "\n";
}

?>
