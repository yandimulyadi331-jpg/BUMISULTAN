<?php

// File untuk setup permissions Keuangan Santri
// AMAN - Tidak menghapus data apapun, hanya menambahkan permissions baru

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

echo "=== SETUP PERMISSIONS KEUANGAN SANTRI ===\n\n";

// Step 1: Buat permission group untuk Keuangan Santri
echo "Step 1: Membuat Permission Group...\n";
$groupId = DB::table('permission_groups')->insertGetId([
    'name' => 'Keuangan Santri',
    'created_at' => now(),
    'updated_at' => now(),
]);
echo "✅ Permission Group 'Keuangan Santri' created with ID: {$groupId}\n\n";

// Step 2: Permissions yang akan ditambahkan
$permissions = [
    'keuangan-santri.index',
    'keuangan-santri.create',
    'keuangan-santri.edit',
    'keuangan-santri.delete',
    'keuangan-santri.laporan',
    'keuangan-santri.import',
    'keuangan-santri.verify',
];

echo "Step 2: Menambahkan permissions...\n";
$created = 0;
$exists = 0;

foreach ($permissions as $permissionName) {
    try {
        // Cek apakah sudah ada
        $existing = DB::table('permissions')->where('name', $permissionName)->first();
        
        if ($existing) {
            echo "ℹ️  Already exists: {$permissionName}\n";
            $exists++;
        } else {
            DB::table('permissions')->insert([
                'name' => $permissionName,
                'guard_name' => 'web',
                'id_permission_group' => $groupId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            echo "✅ Created: {$permissionName}\n";
            $created++;
        }
    } catch (Exception $e) {
        echo "❌ Error creating {$permissionName}: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Step 3: Assign ke super admin
echo "Step 3: Assign permissions to 'super admin'...\n";
try {
    $role = Role::findByName('super admin');
    
    foreach ($permissions as $permissionName) {
        $permission = Permission::where('name', $permissionName)->first();
        if ($permission && !$role->hasPermissionTo($permission)) {
            $role->givePermissionTo($permission);
        }
    }
    
    echo "✅ Permissions assigned to 'super admin' role\n\n";
} catch (Exception $e) {
    echo "❌ Error assigning permissions: " . $e->getMessage() . "\n\n";
}

echo "=== SUMMARY ===\n";
echo "Permission Group ID: {$groupId}\n";
echo "Permissions created: {$created}\n";
echo "Already exists: {$exists}\n";
echo "Total permissions: " . count($permissions) . "\n\n";

echo "✅ SELESAI! Username, password, dan data lama tetap AMAN.\n";
echo "✅ Silakan refresh browser dan akses menu Keuangan Santri.\n";
echo "✅ Clear cache dengan: php artisan cache:clear\n";

