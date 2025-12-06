<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;

echo "========================================\n";
echo "QUICK FIX - AKSES WAGATEWAY\n";
echo "========================================\n\n";

// Cari user yang sudah punya role super admin
$superAdmins = User::role('super admin')->get();

if ($superAdmins->isNotEmpty()) {
    echo "✅ User dengan role 'super admin' sudah ada:\n\n";
    foreach ($superAdmins as $admin) {
        echo "   📧 Email: {$admin->email}\n";
        echo "   🆔 ID: {$admin->id}\n";
        echo "   👤 Nama: {$admin->name}\n";
        echo "   " . str_repeat("-", 60) . "\n";
    }
    
    echo "\n🎯 SOLUSI ERROR 404:\n";
    echo "   1. Logout dari aplikasi (jika sudah login)\n";
    echo "   2. Login kembali dengan salah satu email di atas\n";
    echo "   3. Akses: http://127.0.0.1:8000/wagateway\n";
    echo "   4. Seharusnya sekarang bisa akses tanpa error!\n";
    
    echo "\n💡 CATATAN:\n";
    echo "   - Error 404 terjadi karena middleware 'role:super admin'\n";
    echo "   - Hanya user dengan role 'super admin' yang bisa akses\n";
    echo "   - Security fix sudah diterapkan untuk tidak tampilkan HTML error\n";
    
} else {
    echo "❌ TIDAK ADA user dengan role 'super admin'!\n\n";
    echo "Untuk assign role, jalankan:\n";
    echo "   php assign_superadmin_aman.php\n";
}

echo "\n✅ AMAN: Script ini HANYA MEMBACA data, tidak mengubah apapun!\n";
echo "========================================\n";
