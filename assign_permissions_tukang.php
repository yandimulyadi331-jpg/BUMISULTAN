<?php

// Script untuk assign permissions tukang ke super admin
require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== ASSIGN PERMISSIONS TUKANG KE SUPER ADMIN ===\n\n";

try {
    // Clear cache terlebih dahulu
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('permission:cache-reset');
    
    echo "✅ Cache cleared\n\n";
    
    // Ambil role super admin
    $superAdmin = Role::where('name', 'super admin')->first();
    
    if (!$superAdmin) {
        echo "❌ Role 'super admin' tidak ditemukan!\n";
        exit;
    }
    
    echo "✅ Role 'super admin' ditemukan (ID: {$superAdmin->id})\n\n";
    
    // Daftar permissions
    $permissions = [
        'tukang.index',
        'tukang.create',
        'tukang.show',
        'tukang.edit',
        'tukang.delete',
    ];
    
    echo "Mengassign permissions...\n";
    $assigned = 0;
    $already = 0;
    
    foreach ($permissions as $permName) {
        $permission = Permission::where('name', $permName)->first();
        
        if (!$permission) {
            echo "⚠️  Permission '{$permName}' tidak ditemukan di database\n";
            continue;
        }
        
        if ($superAdmin->hasPermissionTo($permName)) {
            echo "ℹ️  '{$permName}' sudah ter-assign\n";
            $already++;
        } else {
            $superAdmin->givePermissionTo($permission);
            echo "✅ '{$permName}' berhasil di-assign\n";
            $assigned++;
        }
    }
    
    echo "\n📊 Summary:\n";
    echo "   - Permissions baru di-assign: {$assigned}\n";
    echo "   - Permissions sudah ada: {$already}\n";
    echo "   - Total: " . count($permissions) . "\n\n";
    
    echo "✨ Selesai! Silakan logout dan login kembali untuk melihat menu.\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
