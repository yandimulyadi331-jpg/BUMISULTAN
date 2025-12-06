<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\ZKTecoService;
use App\Models\JamaahMajlisTaklim;

echo "=== SYNC MACHINE USER MAPPING ===\n\n";

$zkService = new ZKTecoService('192.168.1.201', 4370);

echo "1. Koneksi ke mesin...\n";
if (!$zkService->connect()) {
    die("❌ Gagal koneksi ke mesin!\n");
}

echo "✅ Koneksi berhasil!\n\n";

echo "2. Ambil semua user dari mesin...\n";
$users = $zkService->getUsers();

if ($users === false || empty($users)) {
    $zkService->disconnect();
    die("❌ Tidak ada user di mesin atau gagal ambil data!\n");
}

echo "✅ Berhasil ambil " . count($users) . " user dari mesin\n\n";

$zkService->disconnect();

echo "3. Update mapping di database...\n\n";

$successCount = 0;
$notFoundCount = 0;

foreach ($users as $user) {
    $machineUserId = $user['uid'] ?? $user['userid'] ?? null;
    $userName = $user['name'] ?? '';

    if (!$machineUserId || !$userName) {
        continue;
    }

    echo "   Machine User ID: {$machineUserId} | Nama: {$userName}\n";

    // Cari jamaah berdasarkan nama (case-insensitive)
    $jamaah = JamaahMajlisTaklim::whereRaw('LOWER(nama_jamaah) = ?', [strtolower($userName)])->first();

    if ($jamaah) {
        $oldMachineId = $jamaah->machine_user_id;
        $jamaah->machine_user_id = $machineUserId;
        $jamaah->save();
        
        echo "   ✅ Mapped ke Database ID: {$jamaah->id}";
        if ($oldMachineId && $oldMachineId != $machineUserId) {
            echo " (Update dari {$oldMachineId} ke {$machineUserId})";
        }
        echo "\n";
        
        $successCount++;
    } else {
        echo "   ❌ Jamaah tidak ditemukan di database\n";
        $notFoundCount++;
    }
    
    echo "\n";
}

echo "\n=== HASIL MAPPING ===\n";
echo "Total user di mesin: " . count($users) . "\n";
echo "Berhasil mapping: {$successCount}\n";
echo "Tidak ditemukan: {$notFoundCount}\n\n";

echo "=== CEK HASIL MAPPING ===\n";
$mapped = JamaahMajlisTaklim::whereNotNull('machine_user_id')->get(['id', 'nama_jamaah', 'machine_user_id']);

foreach ($mapped as $m) {
    echo "DB ID: {$m->id} | Machine ID: {$m->machine_user_id} | Nama: {$m->nama_jamaah}\n";
}

echo "\n✅ Selesai!\n";
