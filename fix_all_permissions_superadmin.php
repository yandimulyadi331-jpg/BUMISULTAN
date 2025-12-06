<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "=== ANALISIS & PERBAIKAN PERMISSION SUPER ADMIN ===\n\n";

try {
    // Cari role super admin
    $superAdmin = Role::where('name', 'super admin')->first();
    
    if (!$superAdmin) {
        echo "❌ Role 'super admin' tidak ditemukan!\n";
        exit;
    }
    
    echo "✅ Role 'super admin' ditemukan (ID: {$superAdmin->id})\n\n";
    
    // Ambil SEMUA permission yang ada di database
    $allPermissions = Permission::orderBy('name')->get();
    
    echo "📊 TOTAL PERMISSION DI SISTEM: " . $allPermissions->count() . "\n\n";
    
    // Cek permission super admin saat ini
    $currentPermissions = $superAdmin->permissions->pluck('name')->toArray();
    echo "📊 PERMISSION SUPER ADMIN SAAT INI: " . count($currentPermissions) . "\n\n";
    
    // Cari permission yang hilang
    $missingPermissions = [];
    $hasPermissions = [];
    
    foreach ($allPermissions as $permission) {
        if (!$superAdmin->hasPermissionTo($permission->name)) {
            $missingPermissions[] = $permission->name;
        } else {
            $hasPermissions[] = $permission->name;
        }
    }
    
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📋 ANALISIS PERMISSION:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    
    if (count($missingPermissions) > 0) {
        echo "⚠️  PERMISSION YANG HILANG (" . count($missingPermissions) . "):\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
        // Group by module
        $grouped = [];
        foreach ($missingPermissions as $perm) {
            $parts = explode('.', $perm);
            $module = $parts[0];
            if (!isset($grouped[$module])) {
                $grouped[$module] = [];
            }
            $grouped[$module][] = $perm;
        }
        
        foreach ($grouped as $module => $perms) {
            echo "\n📦 Module: " . strtoupper($module) . " (" . count($perms) . " permission hilang)\n";
            foreach ($perms as $p) {
                echo "   ❌ $p\n";
            }
        }
        
        echo "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "🔧 MEMPERBAIKI PERMISSION...\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        
        $fixed = 0;
        foreach ($missingPermissions as $permName) {
            try {
                $superAdmin->givePermissionTo($permName);
                echo "✅ Ditambahkan: $permName\n";
                $fixed++;
            } catch (\Exception $e) {
                echo "❌ Gagal menambahkan $permName: {$e->getMessage()}\n";
            }
        }
        
        echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        echo "✅ BERHASIL MENAMBAHKAN: $fixed permission\n";
        echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
        
    } else {
        echo "✅ SUPER ADMIN SUDAH MEMILIKI SEMUA PERMISSION!\n";
    }
    
    // Summary
    echo "\n\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "📊 RINGKASAN AKHIR:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "   Total Permission di Sistem : " . $allPermissions->count() . "\n";
    echo "   Permission Super Admin      : " . ($superAdmin->permissions->count()) . "\n";
    echo "   Status                      : " . ($allPermissions->count() == $superAdmin->permissions->count() ? "✅ LENGKAP" : "⚠️  ADA YANG KURANG") . "\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    // List all modules
    echo "\n📦 DAFTAR MODULE DAN PERMISSION:\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    
    $moduleGroups = [];
    foreach ($allPermissions as $perm) {
        $parts = explode('.', $perm->name);
        $module = $parts[0];
        if (!isset($moduleGroups[$module])) {
            $moduleGroups[$module] = [];
        }
        $moduleGroups[$module][] = $perm->name;
    }
    
    foreach ($moduleGroups as $module => $perms) {
        $hasAll = true;
        foreach ($perms as $p) {
            if (!$superAdmin->hasPermissionTo($p)) {
                $hasAll = false;
                break;
            }
        }
        $status = $hasAll ? "✅" : "⚠️ ";
        echo "\n$status " . strtoupper($module) . " (" . count($perms) . " permissions)\n";
        foreach ($perms as $p) {
            $has = $superAdmin->hasPermissionTo($p) ? "✅" : "❌";
            echo "   $has $p\n";
        }
    }
    
    echo "\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
    echo "✅ PROSES SELESAI!\n";
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
    echo "🔄 LANGKAH SELANJUTNYA:\n";
    echo "   1. Jalankan: php artisan cache:clear\n";
    echo "   2. Jalankan: php artisan config:clear\n";
    echo "   3. LOGOUT dari aplikasi\n";
    echo "   4. LOGIN kembali sebagai super admin\n";
    echo "   5. Cek semua menu yang sebelumnya error 403\n\n";
    
} catch (\Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
