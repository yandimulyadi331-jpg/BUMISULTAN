<?php
/**
 * DEBUG: Cek data kehadiran Jamaah DANI
 * File ini untuk menganalisis mengapa jumlah_kehadiran belum bertambah
 */

// Setup Laravel
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\JamaahMasar;
use App\Models\KehadiranJamaahMasar;
use App\Models\JumlahKehadiranMingguan;
use Illuminate\Support\Facades\DB;

echo "\n=== DEBUG KEHADIRAN JAMAAH DANI ===\n\n";

// 1. Cari Jamaah Dani
$dani = JamaahMasar::where('nama_jamaah', 'like', '%DANI%')->first();

if (!$dani) {
    echo "❌ Jamaah dengan nama DANI tidak ditemukan!\n";
    die();
}

echo "✅ Jamaah Ditemukan:\n";
echo "   ID: " . $dani->id . "\n";
echo "   Nama: " . $dani->nama_jamaah . "\n";
echo "   NIK: " . $dani->nik . "\n";
echo "   PIN: " . $dani->pin_fingerprint . "\n";
echo "   Jumlah Kehadiran: " . $dani->jumlah_kehadiran . " kali\n\n";

// 2. Cek riwayat kehadiran harian
echo "📋 RIWAYAT KEHADIRAN HARIAN (kehadiran_jamaah_masar):\n";
echo str_repeat("-", 80) . "\n";

$kehadiran_harian = KehadiranJamaahMasar::where('jamaah_id', $dani->id)
    ->orderBy('tanggal_kehadiran', 'desc')
    ->limit(10)
    ->get();

if ($kehadiran_harian->count() == 0) {
    echo "❌ Tidak ada data kehadiran harian sama sekali!\n";
} else {
    printf("%-20s | %-15s | %-15s | %s\n", "Tanggal", "Jam Masuk", "Jam Pulang", "Keterangan");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($kehadiran_harian as $kh) {
        printf("%-20s | %-15s | %-15s | %s\n",
            $kh->tanggal_kehadiran,
            $kh->jam_masuk ?? '-',
            $kh->jam_pulang ?? '-',
            substr($kh->keterangan, 0, 30)
        );
    }
}

echo "\n";

// 3. Cek riwayat kehadiran mingguan
echo "📊 RIWAYAT KEHADIRAN MINGGUAN (jumlah_kehadiran_mingguan):\n";
echo str_repeat("-", 80) . "\n";

$kehadiran_mingguan = JumlahKehadiranMingguan::where('jamaah_id', $dani->id)
    ->orderBy('tahun', 'desc')
    ->orderBy('minggu_ke', 'desc')
    ->limit(10)
    ->get();

if ($kehadiran_mingguan->count() == 0) {
    echo "❌ Tidak ada data kehadiran mingguan sama sekali!\n";
} else {
    printf("%-8s | %-12s | %-12s | %-20s | %s\n", "Tahun", "Minggu Ke", "Jumlah", "Tgl Kehadiran", "Last Updated");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($kehadiran_mingguan as $km) {
        printf("%-8s | %-12s | %-12s | %-20s | %s\n",
            $km->tahun,
            $km->minggu_ke,
            $km->jumlah_kehadiran,
            $km->tanggal_kehadiran,
            $km->last_updated
        );
    }
}

echo "\n";

// 4. Cek data terkini (hari ini / kemarin)
echo "🔍 ANALISIS HARI INI:\n";
echo str_repeat("-", 80) . "\n";

$hari_ini = date('Y-m-d');
$kemarin = date('Y-m-d', strtotime('-1 day'));

$kehadiran_hari_ini = KehadiranJamaahMasar::where('jamaah_id', $dani->id)
    ->whereDate('tanggal_kehadiran', $hari_ini)
    ->first();

$kehadiran_kemarin = KehadiranJamaahMasar::where('jamaah_id', $dani->id)
    ->whereDate('tanggal_kehadiran', $kemarin)
    ->first();

echo "Hari Ini (" . $hari_ini . "):\n";
if ($kehadiran_hari_ini) {
    echo "   ✅ Ada record kehadiran\n";
    echo "   Jam Masuk: " . ($kehadiran_hari_ini->jam_masuk ?? 'Belum ada') . "\n";
    echo "   Jam Pulang: " . ($kehadiran_hari_ini->jam_pulang ?? 'Belum ada') . "\n";
} else {
    echo "   ❌ Tidak ada record kehadiran\n";
}

echo "\nKemarin (" . $kemarin . "):\n";
if ($kehadiran_kemarin) {
    echo "   ✅ Ada record kehadiran\n";
    echo "   Jam Masuk: " . ($kehadiran_kemarin->jam_masuk ?? 'Belum ada') . "\n";
    echo "   Jam Pulang: " . ($kehadiran_kemarin->jam_pulang ?? 'Belum ada') . "\n";
} else {
    echo "   ❌ Tidak ada record kehadiran\n";
}

echo "\n";

// 5. Cek minggu ini
echo "📅 MINGGU INI:\n";
echo str_repeat("-", 80) . "\n";

$minggu_ke = JumlahKehadiranMingguan::getMingguKe();
$tahun = date('Y');

$kehadiran_minggu_ini = JumlahKehadiranMingguan::where('jamaah_id', $dani->id)
    ->where('tahun', $tahun)
    ->where('minggu_ke', $minggu_ke)
    ->first();

echo "Minggu Ke: " . $minggu_ke . " / Tahun: " . $tahun . "\n";

if ($kehadiran_minggu_ini) {
    echo "   ✅ Ada record untuk minggu ini\n";
    echo "   Jumlah Kehadiran Minggu Ini: " . $kehadiran_minggu_ini->jumlah_kehadiran . "\n";
    echo "   Tanggal Kehadiran: " . $kehadiran_minggu_ini->tanggal_kehadiran . "\n";
    echo "   Last Updated: " . $kehadiran_minggu_ini->last_updated . "\n";
} else {
    echo "   ❌ Tidak ada record untuk minggu ini\n";
}

echo "\n";

// 6. Raw SQL Query untuk double check
echo "🔧 RAW SQL CHECK:\n";
echo str_repeat("-", 80) . "\n";

$sql_kehadiran = DB::table('kehadiran_jamaah_masar')
    ->where('jamaah_id', $dani->id)
    ->whereDate('tanggal_kehadiran', '>=', date('Y-m-d', strtotime('-7 days')))
    ->orderBy('tanggal_kehadiran', 'desc')
    ->get();

echo "Kehadiran dalam 7 hari terakhir: " . $sql_kehadiran->count() . " record\n";
foreach ($sql_kehadiran as $row) {
    echo "   - " . $row->tanggal_kehadiran . ": " . ($row->jam_masuk ?? 'X') . " / " . ($row->jam_pulang ?? 'X') . "\n";
}

echo "\n";

// 7. Rekomendasi
echo "💡 REKOMENDASI DEBUGGING:\n";
echo str_repeat("-", 80) . "\n";

if ($kehadiran_harian->count() == 0) {
    echo "❌ MASALAH UTAMA: Tidak ada data kehadiran harian di database\n";
    echo "   Kemungkinan:\n";
    echo "   1. Scan dari fingerprint tidak tercatat di database\n";
    echo "   2. Fingerspot API tidak terhubung dengan baik\n";
    echo "   3. PIN Fingerprint di database salah (" . $dani->pin_fingerprint . ")\n";
    echo "   4. Cloud ID atau API Key di pengaturan umum tidak valid\n";
    echo "\n   Solusi:\n";
    echo "   - Cek log error Laravel (storage/logs/)\n";
    echo "   - Verifikasi PIN di mesin dengan PIN di database\n";
    echo "   - Test koneksi Fingerspot API\n";
} else if ($kehadiran_mingguan->count() == 0) {
    echo "⚠️  Data kehadiran ada tapi kehadiran mingguan belum tercatat\n";
    echo "   Kemungkinan: Method updatefrommachine() tidak dipanggil dengan benar\n";
    echo "   Atau record kehadiran dibuat tanpa through updatefrommachine()\n";
} else {
    $total_minggu = $kehadiran_mingguan->sum('jumlah_kehadiran');
    echo "✅ Kehadiran sudah tercatat:\n";
    echo "   Total kehadiran mingguan: " . $total_minggu . "\n";
    echo "   Jumlah di tabel jamaah_masar: " . $dani->jumlah_kehadiran . "\n";
    
    if ($total_minggu != $dani->jumlah_kehadiran) {
        echo "   ⚠️  DATA TIDAK SINKRON! (Mingguan: $total_minggu vs Jamaah: " . $dani->jumlah_kehadiran . ")\n";
    }
}

echo "\n";

?>
