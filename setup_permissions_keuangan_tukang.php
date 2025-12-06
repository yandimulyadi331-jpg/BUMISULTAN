<?php

/**
 * Script Setup Permissions untuk Keuangan Tukang
 * 
 * Menambahkan permissions untuk:
 * - Keuangan Tukang (Dashboard, Detail Transaksi)
 * - Lembur Cash
 * - Pinjaman Tukang
 * - Potongan Tukang  
 * - Laporan Keuangan
 * 
 * Cara menjalankan:
 * php setup_permissions_keuangan_tukang.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Permission_group;

try {
    echo "========================================\n";
    echo "Setup Permissions: Keuangan Tukang\n";
    echo "========================================\n\n";
    
    // Cari atau buat permission group
    echo "Mencari/Membuat Permission Group...\n";
    $permissionGroup = Permission_group::firstOrCreate(
        ['name' => 'Keuangan Tukang']
    );
    
    if ($permissionGroup->wasRecentlyCreated) {
        echo "  ✓ Created: Permission Group 'Keuangan Tukang'\n";
    } else {
        echo "  - Exists:  Permission Group 'Keuangan Tukang' (ID: {$permissionGroup->id})\n";
    }
    
    echo "\n";
    
    // Daftar permissions untuk Keuangan Tukang
    $permissions = [
        // Dashboard & Overview
        [
            'name' => 'keuangan-tukang.index',
            'guard_name' => 'web',
            'description' => 'Melihat Dashboard Keuangan Tukang'
        ],
        
        // Lembur Cash
        [
            'name' => 'keuangan-tukang.lembur-cash',
            'guard_name' => 'web',
            'description' => 'Kelola Pembayaran Lembur Cash'
        ],
        
        // Pinjaman Tukang
        [
            'name' => 'keuangan-tukang.pinjaman',
            'guard_name' => 'web',
            'description' => 'Kelola Pinjaman Tukang'
        ],
        
        // Potongan Tukang
        [
            'name' => 'keuangan-tukang.potongan',
            'guard_name' => 'web',
            'description' => 'Kelola Potongan Tukang'
        ],
        
        // Laporan Keuangan
        [
            'name' => 'keuangan-tukang.laporan',
            'guard_name' => 'web',
            'description' => 'Melihat & Export Laporan Keuangan Tukang'
        ],
    ];
    
    echo "Membuat permissions...\n";
    foreach ($permissions as $permissionData) {
        $permission = Permission::firstOrCreate(
            ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
            ['id_permission_group' => $permissionGroup->id]
        );
        
        if ($permission->wasRecentlyCreated) {
            echo "  ✓ Created: {$permissionData['name']}\n";
        } else {
            echo "  - Exists:  {$permissionData['name']}\n";
        }
    }
    
    echo "\n";
    echo "Assign permissions ke role 'super admin'...\n";
    
    $superAdmin = Role::where('name', 'super admin')->first();
    
    if ($superAdmin) {
        foreach ($permissions as $permissionData) {
            if (!$superAdmin->hasPermissionTo($permissionData['name'])) {
                $superAdmin->givePermissionTo($permissionData['name']);
                echo "  ✓ Assigned: {$permissionData['name']} to super admin\n";
            } else {
                echo "  - Already:  {$permissionData['name']} assigned to super admin\n";
            }
        }
    } else {
        echo "  ⚠ Warning: Role 'super admin' tidak ditemukan!\n";
    }
    
    echo "\n";
    echo "========================================\n";
    echo "✓ Setup Permissions Selesai!\n";
    echo "========================================\n\n";
    
    echo "Permissions yang dibuat:\n";
    foreach ($permissions as $perm) {
        echo "  - {$perm['name']}\n";
    }
    
    echo "\n";
    echo "CATATAN:\n";
    echo "- Route 'cash-lembur' lama sudah di-redirect ke 'keuangan-tukang.lembur-cash'\n";
    echo "- Fitur keuangan dipindahkan dari Kehadiran Tukang ke Keuangan Tukang\n";
    echo "- Kehadiran Tukang sekarang fokus hanya untuk absensi\n";
    echo "\n";
    
} catch (\Exception $e) {
    echo "\n";
    echo "========================================\n";
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "========================================\n";
    echo $e->getTraceAsString();
    exit(1);
}
