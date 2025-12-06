<?php

// Setup permissions untuk Kehadiran Tukang
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

echo "=== SETUP PERMISSIONS KEHADIRAN TUKANG ===\n\n";

// Clear cache
\Illuminate\Support\Facades\Artisan::call('cache:clear');
echo "✅ Cache cleared\n\n";

// Ambil permission group Manajemen Tukang
$groupId = DB::table('permission_groups')->where('name', 'Manajemen Tukang')->value('id');

if (!$groupId) {
    echo "❌ Permission group 'Manajemen Tukang' tidak ditemukan!\n";
    echo "   Jalankan setup_permissions_tukang.php terlebih dahulu.\n";
    exit;
}

echo "✅ Permission Group 'Manajemen Tukang' found (ID: {$groupId})\n\n";

// Permissions baru untuk kehadiran
$permissions = [
    'kehadiran-tukang.index',
    'kehadiran-tukang.absen',
    'kehadiran-tukang.rekap',
];

echo "Menambahkan permissions kehadiran...\n";
$created = 0;
$exists = 0;

foreach ($permissions as $permName) {
    $existing = DB::table('permissions')->where('name', $permName)->first();
    
    if ($existing) {
        echo "ℹ️  Permission sudah ada: {$permName}\n";
        $exists++;
    } else {
        DB::table('permissions')->insert([
            'name' => $permName,
            'guard_name' => 'web',
            'id_permission_group' => $groupId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Permission berhasil dibuat: {$permName}\n";
        $created++;
    }
}

echo "\n📊 Summary:\n";
echo "   - Permissions dibuat: {$created}\n";
echo "   - Permissions sudah ada: {$exists}\n";
echo "   - Total: " . count($permissions) . "\n\n";

// Assign ke super admin
echo "Assign permissions ke role 'super admin'...\n";

try {
    \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
    
    $superAdmin = Role::where('name', 'super admin')->first();
    
    if ($superAdmin) {
        $assignedCount = 0;
        foreach ($permissions as $permName) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission && !$superAdmin->hasPermissionTo($permName)) {
                $superAdmin->givePermissionTo($permName);
                $assignedCount++;
            }
        }
        echo "✅ {$assignedCount} permissions berhasil di-assign ke 'super admin'\n";
    } else {
        echo "⚠️  Role 'super admin' tidak ditemukan\n";
    }
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}

echo "\n=== SETUP SELESAI ===\n";
echo "\nPermissions Kehadiran Tukang:\n";
foreach ($permissions as $perm) {
    echo "   ✓ {$perm}\n";
}

echo "\n📝 Keterangan:\n";
echo "   - kehadiran-tukang.index  : Akses halaman absensi harian\n";
echo "   - kehadiran-tukang.absen  : Absen tukang (toggle status & lembur)\n";
echo "   - kehadiran-tukang.rekap  : Akses rekap & detail kehadiran\n\n";

echo "✨ Silakan logout dan login kembali untuk melihat menu baru!\n";
