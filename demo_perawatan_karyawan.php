<?php

/**
 * DEMO DATA - Menu Perawatan Karyawan
 * 
 * Script ini untuk populate data master perawatan sebagai contoh
 * Jalankan dengan: php demo_perawatan_karyawan.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\MasterPerawatan;
use App\Models\PerawatanLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=================================\n";
echo "DEMO MENU PERAWATAN KARYAWAN\n";
echo "=================================\n\n";

// 1. Cek apakah sudah ada data master
$jumlahMaster = MasterPerawatan::count();
echo "✓ Jumlah Master Checklist saat ini: $jumlahMaster\n\n";

if ($jumlahMaster == 0) {
    echo "📝 Membuat data master checklist contoh...\n\n";
    
    // Checklist Harian
    $harianData = [
        [
            'nama_kegiatan' => 'Membersihkan lantai ruang kantor',
            'deskripsi' => 'Sapu dan pel seluruh area lantai kantor, termasuk sudut-sudut ruangan',
            'tipe_periode' => 'harian',
            'kategori' => 'kebersihan',
            'urutan' => 1,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Membersihkan toilet dan kamar mandi',
            'deskripsi' => 'Bersihkan closet, wastafel, dan lantai toilet. Pastikan ketersediaan tissue dan sabun',
            'tipe_periode' => 'harian',
            'kategori' => 'kebersihan',
            'urutan' => 2,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Cek kebersihan area parkir',
            'deskripsi' => 'Pastikan area parkir bersih dari sampah dan rapi',
            'tipe_periode' => 'harian',
            'kategori' => 'kebersihan',
            'urutan' => 3,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Menyiram tanaman',
            'deskripsi' => 'Siram tanaman di area kantor dan taman',
            'tipe_periode' => 'harian',
            'kategori' => 'perawatan_rutin',
            'urutan' => 4,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Cek kondisi AC ruangan',
            'deskripsi' => 'Pastikan AC berfungsi normal di semua ruangan',
            'tipe_periode' => 'harian',
            'kategori' => 'pengecekan',
            'urutan' => 5,
            'is_active' => true
        ],
    ];
    
    // Checklist Mingguan
    $mingguanData = [
        [
            'nama_kegiatan' => 'Membersihkan kaca jendela',
            'deskripsi' => 'Bersihkan semua kaca jendela di gedung menggunakan cairan pembersih khusus',
            'tipe_periode' => 'mingguan',
            'kategori' => 'kebersihan',
            'urutan' => 1,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Cek lampu dan saklar',
            'deskripsi' => 'Periksa semua lampu dan saklar, ganti jika ada yang rusak',
            'tipe_periode' => 'mingguan',
            'kategori' => 'pengecekan',
            'urutan' => 2,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Membersihkan filter AC',
            'deskripsi' => 'Bersihkan filter AC di semua ruangan',
            'tipe_periode' => 'mingguan',
            'kategori' => 'perawatan_rutin',
            'urutan' => 3,
            'is_active' => true
        ],
    ];
    
    // Checklist Bulanan
    $bulananData = [
        [
            'nama_kegiatan' => 'Cek sistem kelistrikan gedung',
            'deskripsi' => 'Periksa panel listrik, MCB, dan instalasi kelistrikan',
            'tipe_periode' => 'bulanan',
            'kategori' => 'pengecekan',
            'urutan' => 1,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Service AC ruangan',
            'deskripsi' => 'Lakukan service AC di semua ruangan (cek freon, bersihkan komponen)',
            'tipe_periode' => 'bulanan',
            'kategori' => 'perawatan_rutin',
            'urutan' => 2,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Cek sistem plumbing',
            'deskripsi' => 'Periksa pipa air, keran, dan sistem pembuangan',
            'tipe_periode' => 'bulanan',
            'kategori' => 'pengecekan',
            'urutan' => 3,
            'is_active' => true
        ],
    ];
    
    // Checklist Tahunan
    $tahunanData = [
        [
            'nama_kegiatan' => 'Pengecatan ulang gedung',
            'deskripsi' => 'Cat ulang dinding gedung yang sudah pudar atau rusak',
            'tipe_periode' => 'tahunan',
            'kategori' => 'perawatan_rutin',
            'urutan' => 1,
            'is_active' => true
        ],
        [
            'nama_kegiatan' => 'Inspeksi struktur bangunan',
            'deskripsi' => 'Periksa struktur bangunan, cek atap, dinding, dan fondasi',
            'tipe_periode' => 'tahunan',
            'kategori' => 'pengecekan',
            'urutan' => 2,
            'is_active' => true
        ],
    ];
    
    // Insert data
    foreach ($harianData as $data) {
        MasterPerawatan::create($data);
        echo "  ✓ Created: {$data['nama_kegiatan']} (Harian)\n";
    }
    
    foreach ($mingguanData as $data) {
        MasterPerawatan::create($data);
        echo "  ✓ Created: {$data['nama_kegiatan']} (Mingguan)\n";
    }
    
    foreach ($bulananData as $data) {
        MasterPerawatan::create($data);
        echo "  ✓ Created: {$data['nama_kegiatan']} (Bulanan)\n";
    }
    
    foreach ($tahunanData as $data) {
        MasterPerawatan::create($data);
        echo "  ✓ Created: {$data['nama_kegiatan']} (Tahunan)\n";
    }
    
    echo "\n✅ Berhasil membuat " . count(array_merge($harianData, $mingguanData, $bulananData, $tahunanData)) . " checklist!\n\n";
} else {
    echo "ℹ️  Data master sudah ada, skip pembuatan data baru.\n\n";
}

// 2. Tampilkan statistik
echo "=================================\n";
echo "STATISTIK MASTER CHECKLIST\n";
echo "=================================\n";
echo "Harian   : " . MasterPerawatan::where('tipe_periode', 'harian')->count() . " checklist\n";
echo "Mingguan : " . MasterPerawatan::where('tipe_periode', 'mingguan')->count() . " checklist\n";
echo "Bulanan  : " . MasterPerawatan::where('tipe_periode', 'bulanan')->count() . " checklist\n";
echo "Tahunan  : " . MasterPerawatan::where('tipe_periode', 'tahunan')->count() . " checklist\n";
echo "=================================\n\n";

// 3. Cek log karyawan
$totalLog = PerawatanLog::count();
echo "📊 Total log aktivitas karyawan: $totalLog\n\n";

// 4. Info Akses
echo "=================================\n";
echo "CARA AKSES MENU\n";
echo "=================================\n";
echo "1. Login sebagai karyawan\n";
echo "2. Klik menu 'Perawatan' di dashboard\n";
echo "3. Pilih jenis checklist (Harian/Mingguan/Bulanan/Tahunan)\n";
echo "4. Centang checklist yang sudah dikerjakan\n";
echo "5. Lihat progress dan history\n";
echo "=================================\n\n";

echo "✅ Demo selesai!\n";
echo "🚀 Silakan akses aplikasi dan coba menu Perawatan\n\n";
