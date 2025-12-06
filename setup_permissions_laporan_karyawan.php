<?php
/**
 * SETUP PERMISSIONS LAPORAN KEUANGAN KARYAWAN
 * 
 * Script ini membuat permission baru untuk fitur:
 * 1. laporan-keuangan-karyawan.index - Karyawan dapat melihat list laporan yang dipublish
 * 2. laporan-keuangan.publish - Admin dapat publish/unpublish laporan
 * 
 * Run: php setup_permissions_laporan_karyawan.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

try {
    DB::beginTransaction();

    echo "🚀 Setup Permissions Laporan Keuangan Karyawan\n";
    echo "================================================\n\n";

    // 0. Ambil atau buat permission group
    echo "📁 Getting/Creating permission group 'Laporan Keuangan'...\n";
    $permissionGroup = DB::table('permission_groups')
        ->where('name', 'Laporan Keuangan')
        ->first();
    
    if (!$permissionGroup) {
        echo "⚠️  Permission group not found, creating new one...\n";
        $groupId = DB::table('permission_groups')->insertGetId([
            'name' => 'Laporan Keuangan',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Permission group created with ID: {$groupId}\n\n";
    } else {
        $groupId = $permissionGroup->id;
        echo "✅ Permission group found with ID: {$groupId}\n\n";
    }

    // 1. Buat permission untuk karyawan view laporan
    echo "📋 Creating permission: laporan-keuangan-karyawan.index...\n";
    $permissionKaryawanView = Permission::firstOrCreate(
        ['name' => 'laporan-keuangan-karyawan.index'],
        [
            'guard_name' => 'web',
            'id_permission_group' => $groupId
        ]
    );
    echo "✅ Permission created: {$permissionKaryawanView->name}\n\n";

    // 2. Buat permission untuk admin publish laporan
    echo "📋 Creating permission: laporan-keuangan.publish...\n";
    $permissionAdminPublish = Permission::firstOrCreate(
        ['name' => 'laporan-keuangan.publish'],
        [
            'guard_name' => 'web',
            'id_permission_group' => $groupId
        ]
    );
    echo "✅ Permission created: {$permissionAdminPublish->name}\n\n";

    // 3. Assign ke role karyawan
    echo "👤 Assigning permission to role 'karyawan'...\n";
    $roleKaryawan = Role::where('name', 'karyawan')->first();
    if ($roleKaryawan) {
        if (!$roleKaryawan->hasPermissionTo('laporan-keuangan-karyawan.index')) {
            $roleKaryawan->givePermissionTo('laporan-keuangan-karyawan.index');
            echo "✅ Permission 'laporan-keuangan-karyawan.index' assigned to karyawan\n";
        } else {
            echo "ℹ️  Permission already assigned to karyawan\n";
        }
    } else {
        echo "⚠️  Role 'karyawan' not found!\n";
    }
    echo "\n";

    // 4. Assign ke role super admin
    echo "👑 Assigning permissions to role 'super admin'...\n";
    $roleAdmin = Role::where('name', 'super admin')->first();
    if ($roleAdmin) {
        $permissionsToAssign = [
            'laporan-keuangan-karyawan.index',
            'laporan-keuangan.publish'
        ];
        
        foreach ($permissionsToAssign as $permName) {
            if (!$roleAdmin->hasPermissionTo($permName)) {
                $roleAdmin->givePermissionTo($permName);
                echo "✅ Permission '{$permName}' assigned to super admin\n";
            } else {
                echo "ℹ️  Permission '{$permName}' already assigned to super admin\n";
            }
        }
    } else {
        echo "⚠️  Role 'super admin' not found!\n";
    }
    echo "\n";

    DB::commit();

    echo "================================================\n";
    echo "✨ Setup completed successfully!\n\n";
    echo "📝 Summary:\n";
    echo "   - Permission untuk karyawan melihat laporan: CREATED ✅\n";
    echo "   - Permission untuk admin publish laporan: CREATED ✅\n";
    echo "   - Role 'karyawan' dapat akses menu Laporan Keuangan ✅\n";
    echo "   - Role 'super admin' dapat publish/unpublish laporan ✅\n\n";
    echo "🎯 Next Steps:\n";
    echo "   1. Login sebagai karyawan → Cek menu 'Laporan' di dashboard\n";
    echo "   2. Login sebagai admin → Buka 'Laporan Keuangan' → Toggle publish button\n";
    echo "   3. Setelah dipublish, karyawan akan bisa melihat laporan tersebut\n\n";

} catch (\Exception $e) {
    DB::rollBack();
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
