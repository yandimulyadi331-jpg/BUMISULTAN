<?php

/**
 * Script untuk mengintegrasikan notifikasi real-time ke semua controller
 * Jalankan script ini untuk menambahkan NotificationService ke controller utama
 */

require_once '../vendor/autoload.php';

$controllers = [
    // Controller dengan aktivitas utama yang perlu notifikasi
    'AktivitasKendaraanController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'kendaraan'
    ],
    'PinjamanController.php' => [
        'methods' => ['store', 'approve', 'cairkan'],
        'notification_type' => 'pinjaman'
    ],
    'IzinabsenController.php' => [
        'methods' => ['store', 'approve'],
        'notification_type' => 'izin'
    ],
    'IzinsakitController.php' => [
        'methods' => ['store', 'approve'],
        'notification_type' => 'izin'
    ],
    'IzincutiController.php' => [
        'methods' => ['store', 'approve'],
        'notification_type' => 'izin'
    ],
    'LemburController.php' => [
        'methods' => ['store', 'approve', 'checkin', 'checkout'],
        'notification_type' => 'lembur'
    ],
    'InventarisController.php' => [
        'methods' => ['store', 'update', 'transfer'],
        'notification_type' => 'inventaris'
    ],
    'PeminjamanInventarisController.php' => [
        'methods' => ['store', 'approve', 'return'],
        'notification_type' => 'inventaris'
    ],
    'KendaraanController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'kendaraan'
    ],
    'PeminjamanKendaraanController.php' => [
        'methods' => ['store', 'approve', 'return'],
        'notification_type' => 'kendaraan'
    ],
    'ServiceKendaraanController.php' => [
        'methods' => ['store', 'complete'],
        'notification_type' => 'kendaraan'
    ],
    'SantriController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'santri'
    ],
    'AbsensiSantriController.php' => [
        'methods' => ['store'],
        'notification_type' => 'santri'
    ],
    'PelanggaranSantriController.php' => [
        'methods' => ['store'],
        'notification_type' => 'santri'
    ],
    'KeuanganSantriController.php' => [
        'methods' => ['store', 'transfer'],
        'notification_type' => 'santri'
    ],
    'TukangController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'tukang'
    ],
    'KehadiranTukangController.php' => [
        'methods' => ['store'],
        'notification_type' => 'tukang'
    ],
    'PinjamanTukangController.php' => [
        'methods' => ['store', 'approve'],
        'notification_type' => 'tukang'
    ],
    'JamaahMajlisTaklimController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'jamaah'
    ],
    'KehadiranJamaahController.php' => [
        'methods' => ['store'],
        'notification_type' => 'jamaah'
    ],
    'DistribusiHadiahController.php' => [
        'methods' => ['store'],
        'notification_type' => 'jamaah'
    ],
    'JamaahMasarController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'jamaah'
    ],
    'AdministrasiController.php' => [
        'methods' => ['store', 'update', 'approve'],
        'notification_type' => 'administrasi'
    ],
    'DokumenController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'administrasi'
    ],
    'ManajemenKeuanganController.php' => [
        'methods' => ['store', 'approve', 'transfer'],
        'notification_type' => 'keuangan'
    ],
    'DanaOperasionalController.php' => [
        'methods' => ['store', 'approve', 'realisasi'],
        'notification_type' => 'keuangan'
    ],
    'KpiCrewController.php' => [
        'methods' => ['store', 'update'],
        'notification_type' => 'kpi'
    ],
    'ManajemenPerawatanController.php' => [
        'methods' => ['store', 'complete'],
        'notification_type' => 'perawatan'
    ]
];

echo "Script Integrasi Notifikasi Real-time\n";
echo "=====================================\n\n";

$basePath = '../app/Http/Controllers/';
$success = 0;
$failed = 0;

foreach ($controllers as $filename => $config) {
    $filePath = $basePath . $filename;
    
    if (!file_exists($filePath)) {
        echo "❌ File tidak ditemukan: {$filename}\n";
        $failed++;
        continue;
    }
    
    echo "🔄 Memproses: {$filename}\n";
    
    // Baca isi file
    $content = file_get_contents($filePath);
    
    // Check apakah sudah ada NotificationService import
    if (strpos($content, 'NotificationService') !== false) {
        echo "✅ Sudah ada NotificationService di {$filename}\n";
        $success++;
        continue;
    }
    
    // Tambahkan import NotificationService
    $pattern = '/use ([A-Za-z\\\\]+);\n(?!use)/';
    $replacement = '$0use App\\Services\\NotificationService;' . "\n";
    
    $content = preg_replace($pattern, $replacement, $content, 1);
    
    if ($content && file_put_contents($filePath, $content)) {
        echo "✅ Berhasil menambahkan import ke {$filename}\n";
        $success++;
    } else {
        echo "❌ Gagal mengupdate {$filename}\n";
        $failed++;
    }
}

echo "\n=====================================\n";
echo "📊 Ringkasan:\n";
echo "✅ Berhasil: {$success} controller\n";
echo "❌ Gagal: {$failed} controller\n";
echo "\n";

echo "🔧 Langkah selanjutnya:\n";
echo "1. Tambahkan panggilan NotificationService::xxxNotification() di setiap method yang relevan\n";
echo "2. Test dashboard admin untuk melihat notifikasi real-time\n";
echo "3. Sesuaikan pesan notifikasi sesuai kebutuhan\n";