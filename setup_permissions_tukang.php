<?php

// File untuk setup permissions Manajemen Tukang
// AMAN - Tidak menghapus data apapun, hanya menambahkan permissions baru

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

echo "=== SETUP PERMISSIONS MANAJEMEN TUKANG ===\n\n";

// Step 1: Buat permission group untuk Manajemen Tukang
echo "Step 1: Membuat Permission Group...\n";

// Cek apakah group sudah ada
$existingGroup = DB::table('permission_groups')->where('name', 'Manajemen Tukang')->first();

if ($existingGroup) {
    $groupId = $existingGroup->id;
    echo "ℹ️  Permission Group 'Manajemen Tukang' sudah ada dengan ID: {$groupId}\n\n";
} else {
    $groupId = DB::table('permission_groups')->insertGetId([
        'name' => 'Manajemen Tukang',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "✅ Permission Group 'Manajemen Tukang' berhasil dibuat dengan ID: {$groupId}\n\n";
}

// Step 2: Permissions yang akan ditambahkan
$permissions = [
    'tukang.index',
    'tukang.create',
    'tukang.show',
    'tukang.edit',
    'tukang.delete',
];

echo "Step 2: Menambahkan permissions...\n";
$created = 0;
$exists = 0;

foreach ($permissions as $permissionName) {
    try {
        // Cek apakah sudah ada
        $existing = DB::table('permissions')->where('name', $permissionName)->first();
        
        if ($existing) {
            echo "ℹ️  Permission sudah ada: {$permissionName}\n";
            $exists++;
        } else {
            DB::table('permissions')->insert([
                'name' => $permissionName,
                'guard_name' => 'web',
                'id_permission_group' => $groupId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✅ Permission berhasil dibuat: {$permissionName}\n";
            $created++;
        }
    } catch (\Exception $e) {
        echo "❌ Error creating permission {$permissionName}: " . $e->getMessage() . "\n";
    }
}

echo "\n📊 Summary:\n";
echo "   - Permissions dibuat: {$created}\n";
echo "   - Permissions sudah ada: {$exists}\n";
echo "   - Total: " . count($permissions) . "\n\n";

// Step 3: Assign permissions ke role 'super admin'
echo "Step 3: Assign permissions ke role 'super admin'...\n";

try {
    $superAdminRole = Role::where('name', 'super admin')->first();
    
    if ($superAdminRole) {
        $assignedCount = 0;
        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission && !$superAdminRole->hasPermissionTo($permissionName)) {
                $superAdminRole->givePermissionTo($permissionName);
                $assignedCount++;
            }
        }
        echo "✅ {$assignedCount} permissions berhasil di-assign ke 'super admin'\n";
    } else {
        echo "⚠️  Role 'super admin' tidak ditemukan\n";
    }
} catch (\Exception $e) {
    echo "❌ Error assigning permissions: " . $e->getMessage() . "\n";
}

echo "\n=== SETUP SELESAI ===\n";
echo "\nPermissions yang tersedia:\n";
foreach ($permissions as $perm) {
    echo "   ✓ {$perm}\n";
}

echo "\n📝 Catatan:\n";
echo "   - Permission Group: Manajemen Tukang\n";
echo "   - Semua permissions sudah di-assign ke role 'super admin'\n";
echo "   - Untuk role lain, silakan assign manual melalui menu Roles di aplikasi\n";
echo "   - Data lama tetap aman, tidak ada yang dihapus\n\n";

echo "✨ Setup permissions Manajemen Tukang berhasil!\n";
