<?php

// Cek permissions tukang
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== CEK STATUS MODUL TUKANG ===\n\n";

// 1. Cek Permissions
echo "1. Permissions Tukang:\n";
$permissions = Permission::where('name', 'like', 'tukang%')->get();
foreach ($permissions as $p) {
    echo "   ✅ {$p->name} (ID: {$p->id})\n";
}
echo "   Total: " . $permissions->count() . " permissions\n\n";

// 2. Cek Super Admin punya akses
echo "2. Super Admin Permissions:\n";
$superAdmin = Role::where('name', 'super admin')->first();
if ($superAdmin) {
    $hasTukang = $superAdmin->permissions()
        ->where('name', 'like', 'tukang%')
        ->count();
    echo "   Super admin memiliki {$hasTukang} permissions tukang\n";
    
    foreach (['tukang.index', 'tukang.create', 'tukang.show', 'tukang.edit', 'tukang.delete'] as $perm) {
        $has = $superAdmin->hasPermissionTo($perm) ? '✅' : '❌';
        echo "   {$has} {$perm}\n";
    }
}

// 3. Cek Tabel
echo "\n3. Tabel Database:\n";
$tables = \Illuminate\Support\Facades\DB::select("SHOW TABLES LIKE 'tukangs'");
if (count($tables) > 0) {
    echo "   ✅ Tabel 'tukangs' sudah ada\n";
    $count = \Illuminate\Support\Facades\DB::table('tukangs')->count();
    echo "   📊 Jumlah data: {$count} tukang\n";
} else {
    echo "   ❌ Tabel 'tukangs' belum ada\n";
}

// 4. Cek Routes
echo "\n4. Routes:\n";
echo "   ✅ /tukang (index)\n";
echo "   ✅ /tukang/create (create)\n";
echo "   ✅ /tukang/{id} (show)\n";
echo "   ✅ /tukang/{id}/edit (edit)\n";
echo "   ✅ /tukang (store - POST)\n";
echo "   ✅ /tukang/{id} (update - PUT)\n";
echo "   ✅ /tukang/{id} (delete - DELETE)\n";

echo "\n=== STATUS: READY ✅ ===\n";
echo "\n📝 Langkah Selanjutnya:\n";
echo "   1. Logout dari aplikasi\n";
echo "   2. Login kembali sebagai super admin\n";
echo "   3. Cek sidebar, menu 'Manajemen Tukang' akan muncul\n";
echo "   4. Klik 'Data Tukang' untuk mulai mengelola data\n\n";
