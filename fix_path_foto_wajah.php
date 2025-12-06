<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Facerecognition;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  FIX PATH FOTO WAJAH ABSENSI" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "🔍 ANALISA MASALAH:" . PHP_EOL;
echo "─────────────────────────────────────────────────────────────" . PHP_EOL;
echo "❌ MASALAH:" . PHP_EOL;
echo "   • Signup menyimpan ke: storage/karyawan/wajah/" . PHP_EOL;
echo "   • Admin membaca dari: storage/facerecognition/" . PHP_EOL;
echo "   • Foto tidak tampil di halaman admin!" . PHP_EOL;
echo PHP_EOL;

echo "✅ SOLUSI:" . PHP_EOL;
echo "   • Pindahkan foto dari karyawan/wajah/ ke facerecognition/" . PHP_EOL;
echo "   • Update SignupController untuk simpan ke facerecognition/" . PHP_EOL;
echo PHP_EOL;

$sourceDir = storage_path('app/public/karyawan/wajah');
$targetDir = storage_path('app/public/facerecognition');

// Buat folder target jika belum ada
if (!file_exists($targetDir)) {
    mkdir($targetDir, 0777, true);
    echo "✅ Folder facerecognition berhasil dibuat" . PHP_EOL;
}

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  PROSES MIGRASI FOTO" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

$movedCount = 0;
$errorCount = 0;

// Cek apakah folder source ada
if (file_exists($sourceDir)) {
    $files = scandir($sourceDir);
    
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        
        $sourcePath = $sourceDir . '/' . $file;
        $targetPath = $targetDir . '/' . $file;
        
        if (is_file($sourcePath)) {
            // Copy file ke folder baru
            if (copy($sourcePath, $targetPath)) {
                echo "✅ Berhasil copy: {$file}" . PHP_EOL;
                $movedCount++;
                
                // Hapus file lama (optional - comment jika ingin backup)
                // unlink($sourcePath);
            } else {
                echo "❌ Gagal copy: {$file}" . PHP_EOL;
                $errorCount++;
            }
        }
    }
} else {
    echo "ℹ️  Folder source tidak ditemukan: {$sourceDir}" . PHP_EOL;
}

echo PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  HASIL MIGRASI" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "Total file yang berhasil dipindahkan: {$movedCount}" . PHP_EOL;
echo "Total file yang gagal: {$errorCount}" . PHP_EOL;
echo PHP_EOL;

// Cek data di database
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  CEK DATA DI DATABASE" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

$allFaces = Facerecognition::with('karyawan')->get();

$groupedFaces = [];
foreach ($allFaces as $face) {
    $groupedFaces[$face->nik][] = $face;
}

echo "Total Karyawan dengan Face Recognition: " . count($groupedFaces) . PHP_EOL;
echo PHP_EOL;

foreach ($groupedFaces as $nik => $faceList) {
    $karyawan = $faceList[0]->karyawan;
    $nama = $karyawan ? $karyawan->nama_karyawan : 'TIDAK DITEMUKAN';
    $jumlahFoto = count($faceList);
    
    echo "NIK: {$nik} | Nama: {$nama}" . PHP_EOL;
    echo "  Jumlah Foto: {$jumlahFoto}" . PHP_EOL;
    
    // Cek apakah file fisik ada
    $missingFiles = [];
    foreach ($faceList as $face) {
        $filePath = $targetDir . '/' . $face->wajah;
        if (!file_exists($filePath)) {
            $missingFiles[] = $face->wajah;
        }
    }
    
    if (count($missingFiles) > 0) {
        echo "  ⚠️  File tidak ditemukan:" . PHP_EOL;
        foreach ($missingFiles as $missing) {
            echo "     - {$missing}" . PHP_EOL;
        }
    } else {
        echo "  ✅ Semua file foto tersedia" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  TESTING" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "1. Login sebagai Admin" . PHP_EOL;
echo "2. Buka menu: Data Master > Face Recognition" . PHP_EOL;
echo "3. Foto seharusnya sudah tampil!" . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  PATH YANG BENAR" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "✅ Folder Storage: storage/app/public/facerecognition/" . PHP_EOL;
echo "✅ URL Public: public/storage/facerecognition/" . PHP_EOL;
echo "✅ Asset Helper: asset('storage/facerecognition/foto.jpg')" . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "✅ PROSES SELESAI!" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
