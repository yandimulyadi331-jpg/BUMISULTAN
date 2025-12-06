<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\JamaahMasar;
use App\Models\KehadiranJamaahMasar;
use App\Models\JumlahKehadiranMingguan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

echo "=== SIMULASI GET DATA KEHADIRAN JAMAAH ===\n\n";

// Simulasi: Ambil jamaah dengan PIN
$jamaah = JamaahMasar::whereNotNull('pin_fingerprint')->first();

if (!$jamaah) {
    echo "⚠️  Tidak ada jamaah dengan PIN fingerprint.\n";
    exit;
}

echo "1. Data Jamaah:\n";
echo "   ID: {$jamaah->id}\n";
echo "   Nama: {$jamaah->nama_jamaah}\n";
echo "   PIN: {$jamaah->pin_fingerprint}\n";
echo "   Total Kehadiran: {$jamaah->jumlah_kehadiran}\n\n";

// Simulasi: Ambil Jumat terdekat
$nextFriday = Carbon::now();
while (!JumlahKehadiranMingguan::isJumat($nextFriday)) {
    $nextFriday->addDay();
}

echo "2. Jumat Terdekat:\n";
echo "   Tanggal: " . $nextFriday->format('Y-m-d (D)') . "\n";
echo "   Minggu Ke: " . JumlahKehadiranMingguan::getMingguKe($nextFriday) . "\n";
echo "   Tahun: " . $nextFriday->year . "\n\n";

// Simulasi: Buat kehadiran pada Jumat
echo "3. Simulasi Fingerprint pada hari Jumat:\n";

$tanggal = $nextFriday->copy();
$jam_scan = '09:30:00';

// Cek apakah sudah ada kehadiran untuk hari itu
$kehadiran = KehadiranJamaahMasar::where('jamaah_id', $jamaah->id)
    ->whereDate('tanggal_kehadiran', $tanggal)
    ->first();

if ($kehadiran) {
    echo "   ✓ Kehadiran untuk tanggal ini sudah ada.\n";
} else {
    // Buat record kehadiran
    $kehadiran = KehadiranJamaahMasar::create([
        'jamaah_id' => $jamaah->id,
        'tanggal_kehadiran' => $tanggal,
        'jam_masuk' => $jam_scan,
        'keterangan' => 'Simulasi test kehadiran mingguan'
    ]);
    echo "   ✓ Kehadiran harian dibuat: ID " . $kehadiran->id . "\n";
}

// LOGIKA KEHADIRAN MINGGUAN
echo "\n4. Proses Kehadiran Mingguan:\n";

$tahun = $tanggal->year;
$minggu_ke = JumlahKehadiranMingguan::getMingguKe($tanggal);

$kehadiran_mingguan = JumlahKehadiranMingguan::where('jamaah_id', $jamaah->id)
    ->where('tahun', $tahun)
    ->where('minggu_ke', $minggu_ke)
    ->first();

if ($kehadiran_mingguan) {
    echo "   ⚠️  Kehadiran minggu ini sudah tercatat sebelumnya (ID: " . $kehadiran_mingguan->id . ")\n";
    echo "      Tidak akan menambah counter (hanya 1 scan per minggu)\n";
} else {
    // Buat record kehadiran mingguan
    $kehadiran_mingguan = JumlahKehadiranMingguan::create([
        'jamaah_id' => $jamaah->id,
        'tahun' => $tahun,
        'minggu_ke' => $minggu_ke,
        'jumlah_kehadiran' => 1,
        'tanggal_kehadiran' => $tanggal,
        'last_updated' => now()
    ]);
    echo "   ✓ Kehadiran mingguan dibuat: ID " . $kehadiran_mingguan->id . "\n";
    
    // Update total kehadiran jamaah
    $jamaah->increment('jumlah_kehadiran');
    $jamaah->refresh();
    echo "   ✓ Total kehadiran jamaah updated: " . $jamaah->jumlah_kehadiran . "\n";
}

// Tampilkan hasil akhir
echo "\n5. Hasil Akhir:\n";
echo "   Total Kehadiran Jamaah: " . $jamaah->jumlah_kehadiran . "\n";

// Tampilkan semua kehadiran mingguan jamaah tahun ini
$allKehadiranTahunIni = JumlahKehadiranMingguan::where('jamaah_id', $jamaah->id)
    ->where('tahun', now()->year)
    ->orderBy('minggu_ke')
    ->get();

echo "\n6. Kehadiran Mingguan Tahun " . now()->year . ":\n";
if ($allKehadiranTahunIni->count() > 0) {
    foreach ($allKehadiranTahunIni as $kh) {
        $tgl = $kh->tanggal_kehadiran ? $kh->tanggal_kehadiran->format('d M Y') : 'N/A';
        echo "   Minggu {$kh->minggu_ke}: {$tgl} - Status: HADIR ({$kh->jumlah_kehadiran}x)\n";
    }
} else {
    echo "   (Belum ada kehadiran)\n";
}

echo "\n✓ Simulasi selesai\n";
