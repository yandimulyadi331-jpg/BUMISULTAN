<?php
require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Facerecognition;
use App\Models\Karyawan;

echo "\n╔══════════════════════════════════════════════════════════════╗\n";
echo "║  MIGRASI FOTO WAJAH KE STRUKTUR FOLDER PER-KARYAWAN         ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Ambil semua data karyawan yang punya foto wajah
$data_wajah = Facerecognition::select('nik')->distinct()->get();

$total_success = 0;
$total_failed = 0;
$total_karyawan = 0;

foreach ($data_wajah as $item) {
    $nik = $item->nik;
    
    // Ambil data karyawan
    $karyawan = Karyawan::where('nik', $nik)->first();
    
    if (!$karyawan) {
        echo "⚠️  NIK $nik tidak ditemukan di tabel karyawan, skip...\n";
        continue;
    }
    
    $total_karyawan++;
    
    // Buat folder name: {NIK}-{NAMADEPAN}
    $nama_depan = strtolower(explode(' ', trim($karyawan->nama_karyawan))[0]);
    $folder_name = $nik . '-' . $nama_depan;
    
    echo "📁 Processing: $nik - {$karyawan->nama_karyawan}\n";
    echo "   Folder: $folder_name\n";
    
    // Path tujuan
    $target_folder = storage_path('app/public/uploads/facerecognition/' . $folder_name);
    
    // Buat folder jika belum ada
    if (!file_exists($target_folder)) {
        mkdir($target_folder, 0777, true);
        echo "   ✅ Folder created\n";
    } else {
        echo "   ℹ️  Folder already exists\n";
    }
    
    // Ambil semua foto wajah karyawan ini
    $foto_list = Facerecognition::where('nik', $nik)->get();
    
    foreach ($foto_list as $foto) {
        $filename = $foto->wajah;
        
        // Lokasi file lama (berbagai kemungkinan)
        $old_locations = [
            storage_path('app/public/facerecognition/' . $filename),
            storage_path('app/public/karyawan/wajah/' . $filename),
            public_path('storage/facerecognition/' . $filename),
        ];
        
        $file_found = false;
        $source_file = null;
        
        foreach ($old_locations as $loc) {
            if (file_exists($loc)) {
                $source_file = $loc;
                $file_found = true;
                break;
            }
        }
        
        // Lokasi file baru
        $target_file = $target_folder . '/' . $filename;
        
        if ($file_found) {
            // Copy file
            if (copy($source_file, $target_file)) {
                echo "   ✅ $filename → COPIED\n";
                $total_success++;
            } else {
                echo "   ❌ $filename → FAILED TO COPY\n";
                $total_failed++;
            }
        } else {
            echo "   ⚠️  $filename → FILE NOT FOUND\n";
            $total_failed++;
        }
    }
    
    echo "\n";
}

echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  SUMMARY                                                     ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "Total Karyawan: $total_karyawan\n";
echo "✅ Berhasil: $total_success file\n";
echo "❌ Gagal: $total_failed file\n";
echo "\n";
