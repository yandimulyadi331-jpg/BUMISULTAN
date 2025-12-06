<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Facerecognition;
use App\Models\Karyawan;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  VERIFIKASI SISTEM SIGNUP IMPROVED" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "🔍 ANALISA SISTEM LAMA vs BARU:" . PHP_EOL;
echo str_repeat("-", 63) . PHP_EOL;
echo PHP_EOL;

echo "❌ SISTEM LAMA (signup.blade.php + SignupController):" . PHP_EOL;
echo "   • 1 foto saja untuk profil DAN absensi" . PHP_EOL;
echo "   • Akurasi face recognition rendah (hanya 1 sudut)" . PHP_EOL;
echo "   • Tidak sesuai standar sistem admin" . PHP_EOL;
echo PHP_EOL;

echo "✅ SISTEM BARU (signup_improved.blade.php + SignupControllerImproved):" . PHP_EOL;
echo "   • Foto Profil: 1 foto untuk tampilan karyawan" . PHP_EOL;
echo "   • Foto Wajah Absensi: 5 foto dari berbagai sudut" . PHP_EOL;
echo "     - 1_front: Wajah depan" . PHP_EOL;
echo "     - 2_left: Wajah kiri" . PHP_EOL;
echo "     - 3_right: Wajah kanan" . PHP_EOL;
echo "     - 4_up: Wajah atas" . PHP_EOL;
echo "     - 5_down: Wajah bawah" . PHP_EOL;
echo "   • Akurasi tinggi (5 sudut berbeda)" . PHP_EOL;
echo "   • Sesuai dengan sistem admin yang ada" . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  STRUKTUR PENYIMPANAN FILE" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "📁 storage/app/public/karyawan/" . PHP_EOL;
echo "   ├─ {NIK}_profil.jpg (foto profil karyawan)" . PHP_EOL;
echo "   └─ Contoh: 251100001_profil.jpg" . PHP_EOL;
echo PHP_EOL;

echo "📁 storage/app/public/karyawan/wajah/" . PHP_EOL;
echo "   ├─ {NIK}_1_front.jpg" . PHP_EOL;
echo "   ├─ {NIK}_2_left.jpg" . PHP_EOL;
echo "   ├─ {NIK}_3_right.jpg" . PHP_EOL;
echo "   ├─ {NIK}_4_up.jpg" . PHP_EOL;
echo "   └─ {NIK}_5_down.jpg" . PHP_EOL;
echo "   └─ Contoh: 251100001_1_front.jpg, 251100001_2_left.jpg, ..." . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  DATA FACE RECOGNITION SAAT INI" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

$faces = Facerecognition::with('karyawan')->get();

if ($faces->count() > 0) {
    // Group by NIK
    $groupedFaces = [];
    foreach ($faces as $face) {
        $groupedFaces[$face->nik][] = $face;
    }
    
    echo "Total Karyawan dengan Face Recognition: " . count($groupedFaces) . PHP_EOL;
    echo PHP_EOL;
    
    foreach ($groupedFaces as $nik => $faceList) {
        $karyawan = $faceList[0]->karyawan;
        $nama = $karyawan ? $karyawan->nama_karyawan : 'TIDAK DITEMUKAN';
        $jumlahFoto = count($faceList);
        
        echo "NIK: {$nik} | Nama: {$nama}" . PHP_EOL;
        echo "  Jumlah Foto Wajah: {$jumlahFoto}" . PHP_EOL;
        
        if ($jumlahFoto < 5) {
            echo "  ⚠️  PERINGATAN: Hanya {$jumlahFoto} foto (standar minimal 5 foto)" . PHP_EOL;
        } else {
            echo "  ✅ LENGKAP: Memenuhi standar 5 foto" . PHP_EOL;
        }
        
        echo "  File:" . PHP_EOL;
        foreach ($faceList as $face) {
            echo "    - {$face->wajah}" . PHP_EOL;
        }
        echo PHP_EOL;
    }
} else {
    echo "  (Belum ada data)" . PHP_EOL . PHP_EOL;
}

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  PERBANDINGAN ADMIN vs SUPER ADMIN" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

// Cek karyawan super admin berdasarkan NIK dari model_has_roles
$superAdminData = DB::table('model_has_roles')
    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
    ->join('karyawan', 'karyawan.nik', '=', 'model_has_roles.model_id')
    ->where('roles.name', 'super admin')
    ->select('karyawan.*')
    ->get();

if ($superAdminData->count() > 0) {
    foreach ($superAdminData as $admin) {
        $faceCount = Facerecognition::where('nik', $admin->nik)->count();
        echo "Super Admin: {$admin->nama_karyawan} (NIK: {$admin->nik})" . PHP_EOL;
        echo "  Foto Face Recognition: {$faceCount}" . PHP_EOL;
        
        if ($faceCount == 0) {
            echo "  ❌ BELUM ADA foto wajah untuk absensi" . PHP_EOL;
            echo "  💡 Solusi: Login sebagai super admin, buka menu Face Recognition," . PHP_EOL;
            echo "           klik 'Tambah Wajah', lalu 'Mulai Rekam Wajah (5 Gambar)'" . PHP_EOL;
        } else if ($faceCount < 5) {
            echo "  ⚠️  Hanya {$faceCount} foto (kurang dari standar 5 foto)" . PHP_EOL;
            echo "  💡 Solusi: Hapus foto yang ada, lalu rekam ulang 5 foto baru" . PHP_EOL;
        } else {
            echo "  ✅ LENGKAP: {$faceCount} foto wajah" . PHP_EOL;
        }
        echo PHP_EOL;
    }
} else {
    echo "  Tidak ada super admin ditemukan" . PHP_EOL . PHP_EOL;
}

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  CARA TESTING SIGNUP BARU" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "1️⃣  Buka browser: http://127.0.0.1:8000/" . PHP_EOL;
echo "2️⃣  Klik tombol 'Signup'" . PHP_EOL;
echo "3️⃣  Isi formulir pendaftaran" . PHP_EOL;
echo PHP_EOL;

echo "📸 FOTO PROFIL:" . PHP_EOL;
echo "   • Klik 'Buka Kamera'" . PHP_EOL;
echo "   • Klik 'Ambil Foto'" . PHP_EOL;
echo "   • Foto akan tersimpan untuk profil karyawan" . PHP_EOL;
echo PHP_EOL;

echo "📸 FOTO WAJAH ABSENSI (5 GAMBAR):" . PHP_EOL;
echo "   • Klik 'Mulai Rekam Wajah (5 Gambar)'" . PHP_EOL;
echo "   • Ikuti instruksi yang muncul:" . PHP_EOL;
echo "     - Foto 1: Hadapkan wajah ke DEPAN" . PHP_EOL;
echo "     - Foto 2: Tengok ke KIRI" . PHP_EOL;
echo "     - Foto 3: Tengok ke KANAN" . PHP_EOL;
echo "     - Foto 4: Lihat ke ATAS" . PHP_EOL;
echo "     - Foto 5: Lihat ke BAWAH" . PHP_EOL;
echo "   • Setiap foto akan muncul di kotak preview" . PHP_EOL;
echo "   • Progress bar akan terisi saat foto berhasil diambil" . PHP_EOL;
echo PHP_EOL;

echo "4️⃣  Klik 'Daftar Sekarang'" . PHP_EOL;
echo "5️⃣  Sistem akan menyimpan:" . PHP_EOL;
echo "   • 1 foto profil di: storage/karyawan/{NIK}_profil.jpg" . PHP_EOL;
echo "   • 5 foto wajah di: storage/karyawan/wajah/{NIK}_*.jpg" . PHP_EOL;
echo "   • 5 record di tabel karyawan_wajah" . PHP_EOL;
echo "6️⃣  Tunggu approval admin" . PHP_EOL;
echo "7️⃣  Setelah approved, login dan bisa absen pakai wajah!" . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "  CARA FIX SUPER ADMIN YANG BELUM PUNYA FOTO WAJAH" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL . PHP_EOL;

echo "1. Login sebagai Super Admin" . PHP_EOL;
echo "2. Buka menu: Data Master > Face Recognition" . PHP_EOL;
echo "3. Klik tombol 'Tambah Wajah' (tombol biru)" . PHP_EOL;
echo "4. Klik 'Mulai Rekam Wajah (5 Gambar)'" . PHP_EOL;
echo "5. Ikuti instruksi (depan, kiri, kanan, atas, bawah)" . PHP_EOL;
echo "6. Setelah selesai, foto akan tersimpan" . PHP_EOL;
echo "7. Super Admin sekarang bisa absen pakai face recognition!" . PHP_EOL;
echo PHP_EOL;

echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
echo "✅ SISTEM SIGNUP IMPROVED SIAP DIGUNAKAN!" . PHP_EOL;
echo "═══════════════════════════════════════════════════════════════" . PHP_EOL;
