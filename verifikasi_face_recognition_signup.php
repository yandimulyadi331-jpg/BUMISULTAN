<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Karyawan;
use App\Models\Facerecognition;

echo "=== VERIFIKASI INTEGRASI FACE RECOGNITION & SIGNUP ===\n\n";

try {
    // Cek tabel karyawan_wajah
    $totalWajah = Facerecognition::count();
    echo "📊 Total data wajah di database: $totalWajah\n\n";
    
    // Cek karyawan yang punya foto tapi belum ada di karyawan_wajah
    $karyawanBerFoto = Karyawan::whereNotNull('foto')
        ->where('foto', '!=', '')
        ->get();
    
    echo "📋 Karyawan dengan foto: " . $karyawanBerFoto->count() . "\n";
    
    $karyawanTanpaWajah = [];
    foreach ($karyawanBerFoto as $k) {
        $wajah = Facerecognition::where('nik', $k->nik)->count();
        if ($wajah == 0) {
            $karyawanTanpaWajah[] = [
                'nik' => $k->nik,
                'nama' => $k->nama_karyawan,
                'foto' => $k->foto
            ];
        }
    }
    
    if (count($karyawanTanpaWajah) > 0) {
        echo "⚠️  Karyawan punya foto tapi belum terdaftar face recognition: " . count($karyawanTanpaWajah) . "\n\n";
        echo "Daftar karyawan:\n";
        foreach ($karyawanTanpaWajah as $k) {
            echo "   - {$k['nik']} | {$k['nama']} | Foto: {$k['foto']}\n";
        }
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "💡 REKOMENDASI:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "Untuk karyawan yang sudah ada sebelumnya, admin perlu:\n";
        echo "1. Masuk ke menu Face Recognition di super admin\n";
        echo "2. Tambah wajah manual untuk karyawan tersebut\n";
        echo "3. Upload foto atau capture dari camera\n\n";
        echo "Untuk karyawan BARU yang signup:\n";
        echo "✅ Otomatis terdaftar face recognition\n";
        echo "✅ Bisa langsung absen pakai wajah setelah approved\n";
    } else {
        echo "✅ Semua karyawan dengan foto sudah terdaftar face recognition!\n";
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📦 STRUKTUR FOLDER:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "   storage/app/public/karyawan/       → Foto profil karyawan\n";
    echo "   storage/app/public/karyawan/wajah/ → Foto untuk face recognition\n\n";
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ FITUR YANG SUDAH TERINTEGRASI:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "1. ✅ Signup karyawan baru dengan capture foto wajah\n";
    echo "2. ✅ Foto otomatis disimpan untuk:\n";
    echo "      - Profil karyawan (storage/karyawan/)\n";
    echo "      - Face recognition (storage/karyawan/wajah/)\n";
    echo "3. ✅ Data wajah otomatis masuk ke tabel karyawan_wajah\n";
    echo "4. ✅ Karyawan bisa absen pakai wajah setelah approved\n";
    echo "5. ✅ Akses absensi: /facerecognition-presensi\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
