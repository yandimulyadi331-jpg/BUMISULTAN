<?php

/**
 * VALIDASI PERMISSION SYSTEM - COMPLETE
 * Script untuk memverifikasi semua 59 permission groups dan 300+ permissions
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Permission_group;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "\n" . str_repeat("=", 80) . "\n";
echo "🔐 VALIDASI COMPREHENSIVE PERMISSION SYSTEM - 59 GROUPS & 300+ PERMISSIONS\n";
echo str_repeat("=", 80) . "\n\n";

// ============================================
// EXPECTED PERMISSION GROUPS (59 Total)
// ============================================

$expectedGroups = [
    // BATCH 1: Financial, Vehicle, Inventory (18)
    'Pinjaman', 'Pinjaman Tukang', 'Dana Operasional', 'Laporan Keuangan',
    'Laporan Keuangan Karyawan', 'Transaksi Keuangan', 'Keuangan Tukang', 'Keuangan Santri',
    'Kendaraan', 'Kendaraan Karyawan', 'Aktivitas Kendaraan', 'Peminjaman Kendaraan',
    'Service Kendaraan', 'Live Tracking',
    'Inventaris', 'Peminjaman Inventaris', 'Pengembalian Inventaris', 'History Inventaris',

    // BATCH 2: Facilities, Students, Religious (21)
    'Gedung', 'Ruangan', 'Barang', 'Peralatan', 'Peminjaman Peralatan',
    'Santri', 'Jadwal Santri', 'Absensi Santri', 'Izin Santri',
    'Majlis Taklim', 'Jamaah Majlis Taklim', 'Hadiah Majlis Taklim',
    'Jamaah Masar', 'Hadiah Masar', 'Distribusi Hadiah Masar', 'Undian Umroh',
    'Tukang', 'Kehadiran Tukang',
    'Pengunjung', 'Pengunjung Karyawan', 'Jadwal Pengunjung',

    // BATCH 3: Maintenance, Admin, Documents (20)
    'Perawatan', 'Perawatan Karyawan', 'Temuan', 'KPI Crew', 'Tugas Luar',
    'Administrasi', 'Dokumen', 'Administrasi Dokumen',
    'Presensi Istirahat', 'Pengguna', 'Departemen', 'Backup Data',
    'Log Sistem', 'Setting Aplikasi',
    'Notifikasi', 'Realisasi Anggaran', 'Verifikasi Anggaran',
    'Potongan Gaji', 'Realisasi Pinjaman', 'Bank Account'
];

// Count expected
$expectedCount = count($expectedGroups);
echo "📊 EXPECTED PERMISSION GROUPS: {$expectedCount}\n";
echo "   • Batch 1: 18 groups (Financial, Vehicle, Inventory)\n";
echo "   • Batch 2: 21 groups (Facilities, Students, Religious)\n";
echo "   • Batch 3: 20 groups (Maintenance, Admin, Documents)\n\n";

// ============================================
// VALIDASI DATABASE
// ============================================

echo str_repeat("-", 80) . "\n";
echo "VALIDASI DATABASE\n";
echo str_repeat("-", 80) . "\n\n";

// Count actual groups
$actualGroups = Permission_group::all();
$actualCount = $actualGroups->count();

echo "✓ Total Permission Groups di Database: {$actualCount}\n";
if ($actualCount >= $expectedCount) {
    echo "  [OK] VALID - Semua expected groups sudah ada\n";
} else {
    echo "  [ERROR] WARNING - Ada " . ($expectedCount - $actualCount) . " groups yang missing!\n";
}

// Count actual permissions
$totalPermissions = Permission::count();
echo "\n✓ Total Permissions di Database: {$totalPermissions}\n";
if ($totalPermissions >= 300) {
    echo "  ✅ VALID - Sudah memenuhi minimal 300 permissions\n";
} else {
    echo "  ⚠️  WARNING - Hanya {$totalPermissions} permissions (expected 300+)\n";
}

// ============================================
// VALIDASI PER GROUP
// ============================================

echo "\n" . str_repeat("-", 80) . "\n";
echo "VALIDASI PER GROUP DETAIL\n";
echo str_repeat("-", 80) . "\n\n";

$missing = [];
$found = [];

foreach ($expectedGroups as $groupName) {
    $group = Permission_group::where('name', $groupName)->first();
    
    if ($group) {
        $permCount = $group->permissions()->count();
        $found[] = $groupName;
        echo "✅ {$groupName} ({$permCount} permissions)\n";
    } else {
        $missing[] = $groupName;
        echo "❌ {$groupName} (TIDAK DITEMUKAN)\n";
    }
}

// ============================================
// VALIDASI SUPER ADMIN ROLE
// ============================================

echo "\n" . str_repeat("-", 80) . "\n";
echo "VALIDASI SUPER ADMIN ROLE\n";
echo str_repeat("-", 80) . "\n\n";

$superAdminRole = Role::where('name', 'super admin')->first();

if ($superAdminRole) {
    $superAdminPermCount = $superAdminRole->permissions()->count();
    echo "✅ Super Admin Role ditemukan\n";
    echo "   Permissions assigned: {$superAdminPermCount}\n";
    
    if ($superAdminPermCount >= 300) {
        echo "   ✅ VALID - Super admin punya 300+ permissions\n";
    } else {
        echo "   ⚠️  WARNING - Super admin hanya punya {$superAdminPermCount} permissions\n";
    }
} else {
    echo "❌ Super Admin Role TIDAK ditemukan\n";
}

// ============================================
// RINGKASAN HASIL
// ============================================

echo "\n" . str_repeat("=", 80) . "\n";
echo "RINGKASAN HASIL VALIDASI\n";
echo str_repeat("=", 80) . "\n\n";

$foundCount = count($found);
$missingCount = count($missing);

echo "📊 STATISTIK:\n";
echo "   Expected Groups: {$expectedCount}\n";
echo "   Found Groups: {$foundCount}\n";
echo "   Missing Groups: {$missingCount}\n";
echo "   Total Permissions: {$totalPermissions}\n";
echo "   Success Rate: " . round(($foundCount / $expectedCount) * 100, 2) . "%\n\n";

if ($missingCount === 0) {
    echo "✅ STATUS: SEMUA PERMISSION GROUPS VALID & LENGKAP!\n";
    echo "✅ Siap untuk production use!\n";
} elseif ($missingCount <= 5) {
    echo "⚠️  STATUS: Ada {$missingCount} groups yang masih missing\n";
    echo "   Jalankan seeder untuk groups berikut:\n";
    foreach ($missing as $m) {
        echo "   - {$m}\n";
    }
} else {
    echo "❌ STATUS: Banyak groups yang missing ({$missingCount})\n";
    echo "   Jalankan ulang semua seeders:\n";
    echo "   $ php artisan db:seed --class=PermissionSystemMasterSeeder\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "Validasi selesai - " . date('Y-m-d H:i:s') . "\n";
echo str_repeat("=", 80) . "\n\n";

// ============================================
// DETAIL GROUPS PER BATCH
// ============================================

if ($missingCount > 0) {
    echo "\n📋 GROUPS YANG MISSING:\n";
    echo str_repeat("-", 80) . "\n";
    
    foreach ($missing as $m) {
        echo "   ❌ {$m}\n";
    }
    
    echo "\n💡 SOLUSI:\n";
    echo "   1. Pastikan sudah menjalankan seeder:\n";
    echo "      php artisan db:seed --class=PermissionSystemMasterSeeder\n\n";
    echo "   2. Atau jalankan per batch:\n";
    echo "      php artisan db:seed --class=ComprehensivePermissionSeederBatch1\n";
    echo "      php artisan db:seed --class=ComprehensivePermissionSeederBatch2\n";
    echo "      php artisan db:seed --class=ComprehensivePermissionSeederBatch3\n\n";
    echo "   3. Jika sudah jalankan, cek database:\n";
    echo "      SELECT * FROM permission_groups ORDER BY name;\n";
}

?>
