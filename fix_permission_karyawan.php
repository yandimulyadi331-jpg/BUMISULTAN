<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

echo "=== CEK & FIX PERMISSION SUPER ADMIN ===\n\n";

try {
    // Cari role super admin
    $superAdmin = Role::where('name', 'super admin')->first();
    
    if (!$superAdmin) {
        echo "❌ Role 'super admin' tidak ditemukan!\n";
        exit;
    }
    
    echo "✅ Role super admin ditemukan\n";
    
    // Cek permission karyawan.index
    $permission = Permission::where('name', 'karyawan.index')->first();
    
    if (!$permission) {
        echo "⚠️  Permission 'karyawan.index' tidak ditemukan, membuat...\n";
        $permission = Permission::create(['name' => 'karyawan.index']);
    } else {
        echo "✅ Permission 'karyawan.index' ada\n";
    }
    
    // Cek apakah super admin punya permission ini
    if (!$superAdmin->hasPermissionTo('karyawan.index')) {
        echo "⚠️  Super admin belum punya permission 'karyawan.index', menambahkan...\n";
        $superAdmin->givePermissionTo('karyawan.index');
        echo "✅ Permission berhasil ditambahkan!\n";
    } else {
        echo "✅ Super admin sudah punya permission 'karyawan.index'\n";
    }
    
    // List semua permission karyawan
    echo "\n📋 Semua permission karyawan:\n";
    $karyawanPermissions = Permission::where('name', 'like', 'karyawan.%')->get();
    foreach ($karyawanPermissions as $perm) {
        $hasIt = $superAdmin->hasPermissionTo($perm->name) ? '✅' : '❌';
        echo "   $hasIt {$perm->name}\n";
    }
    
    // Pastikan super admin punya semua permission karyawan
    echo "\n🔧 Memberikan semua permission karyawan ke super admin...\n";
    foreach ($karyawanPermissions as $perm) {
        if (!$superAdmin->hasPermissionTo($perm->name)) {
            $superAdmin->givePermissionTo($perm->name);
            echo "   ✅ Ditambahkan: {$perm->name}\n";
        }
    }
    
    echo "\n✅ SELESAI! Silakan logout dan login kembali.\n";
    echo "   Atau clear cache dengan: php artisan cache:clear\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
