<?php

require __DIR__ . '/vendor/autoload.php';

use App\Services\ZKTecoService;

$zkService = new ZKTecoService('192.168.1.201', 4370);

echo "🔍 Checking ZKTeco Device Users...\n\n";

if (!$zkService->connect()) {
    echo "❌ Gagal koneksi ke mesin!\n";
    exit(1);
}

echo "✅ Berhasil koneksi ke mesin\n\n";

// Ambil semua user yang terdaftar
try {
    $users = $zkService->getUser();
    
    if (empty($users)) {
        echo "⚠️  TIDAK ADA USER TERDAFTAR DI MESIN!\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        echo "🔧 SOLUSI:\n";
        echo "1. Enroll fingerprint user di mesin\n";
        echo "2. Atau import user dari database ke mesin\n";
        echo "3. Pastikan ada minimal 1 user dengan PIN terdaftar\n\n";
    } else {
        echo "✅ Ditemukan " . count($users) . " user terdaftar:\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        foreach ($users as $index => $user) {
            echo ($index + 1) . ". PIN: {$user['uid']}\n";
            echo "   Name: {$user['name']}\n";
            echo "   Role: {$user['role']}\n";
            echo "   Password: {$user['password']}\n";
            echo "   Card Number: {$user['cardno']}\n";
            echo "───────────────────────────────────────\n";
        }
    }
    
    echo "\n";
    
    // Cek attendance logs
    $attendance = $zkService->getAttendance();
    echo "📊 Attendance Logs: " . count($attendance) . " records\n";
    
    if (!empty($attendance)) {
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        foreach (array_slice($attendance, 0, 5) as $att) {
            echo "PIN: {$att['uid']} | Time: {$att['timestamp']}\n";
        }
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

$zkService->disconnect();
echo "\n✅ Disconnect dari mesin\n";
