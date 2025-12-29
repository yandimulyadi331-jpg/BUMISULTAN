<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * COMPREHENSIVE PERMISSION SEEDER - BATCH 1
 * Creates permission groups for ALL missing modules
 * Part of complete permission system implementation
 */
class ComprehensivePermissionSeederBatch1 extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super admin')->first();
        
        // ============================================
        // FINANCIAL SYSTEMS
        // ============================================
        
        // 1. PINJAMAN (Loans)
        $this->createPermissionGroup('Pinjaman', [
            'pinjaman.index',
            'pinjaman.create',
            'pinjaman.show',
            'pinjaman.edit',
            'pinjaman.delete',
            'pinjaman.approve',
            'pinjaman.laporan',
            'pinjaman.export'
        ], $superAdminRole);

        // 2. PINJAMAN TUKANG
        $this->createPermissionGroup('Pinjaman Tukang', [
            'pinjaman-tukang.index',
            'pinjaman-tukang.create',
            'pinjaman-tukang.show',
            'pinjaman-tukang.edit',
            'pinjaman-tukang.delete',
            'pinjaman-tukang.approve'
        ], $superAdminRole);

        // 3. DANA OPERASIONAL
        $this->createPermissionGroup('Dana Operasional', [
            'dana-operasional.index',
            'dana-operasional.create',
            'dana-operasional.show',
            'dana-operasional.edit',
            'dana-operasional.delete',
            'dana-operasional.approve',
            'dana-operasional.laporan'
        ], $superAdminRole);

        // 4. LAPORAN KEUANGAN
        $this->createPermissionGroup('Laporan Keuangan', [
            'laporan-keuangan.index',
            'laporan-keuangan.show',
            'laporan-keuangan.laporan',
            'laporan-keuangan.export',
            'laporan-keuangan.detail'
        ], $superAdminRole);

        // 5. LAPORAN KEUANGAN KARYAWAN
        $this->createPermissionGroup('Laporan Keuangan Karyawan', [
            'laporan-keuangan-karyawan.index',
            'laporan-keuangan-karyawan.show',
            'laporan-keuangan-karyawan.laporan'
        ], $superAdminRole);

        // 6. TRANSAKSI KEUANGAN
        $this->createPermissionGroup('Transaksi Keuangan', [
            'transaksi-keuangan.index',
            'transaksi-keuangan.create',
            'transaksi-keuangan.show',
            'transaksi-keuangan.edit',
            'transaksi-keuangan.delete',
            'transaksi-keuangan.laporan'
        ], $superAdminRole);

        // 7. KEUANGAN TUKANG
        $this->createPermissionGroup('Keuangan Tukang', [
            'keuangan-tukang.index',
            'keuangan-tukang.create',
            'keuangan-tukang.show',
            'keuangan-tukang.edit',
            'keuangan-tukang.delete',
            'keuangan-tukang.laporan'
        ], $superAdminRole);

        // 8. KEUANGAN SANTRI
        $this->createPermissionGroup('Keuangan Santri', [
            'keuangan-santri.index',
            'keuangan-santri.create',
            'keuangan-santri.show',
            'keuangan-santri.edit',
            'keuangan-santri.delete',
            'keuangan-santri.laporan'
        ], $superAdminRole);

        // ============================================
        // VEHICLE MANAGEMENT
        // ============================================

        // 9. KENDARAAN (Vehicle Master)
        $this->createPermissionGroup('Kendaraan', [
            'kendaraan.index',
            'kendaraan.create',
            'kendaraan.show',
            'kendaraan.edit',
            'kendaraan.delete',
            'kendaraan.status'
        ], $superAdminRole);

        // 10. KENDARAAN KARYAWAN
        $this->createPermissionGroup('Kendaraan Karyawan', [
            'kendaraan-karyawan.index',
            'kendaraan-karyawan.create',
            'kendaraan-karyawan.show',
            'kendaraan-karyawan.edit',
            'kendaraan-karyawan.delete'
        ], $superAdminRole);

        // 11. AKTIVITAS KENDARAAN
        $this->createPermissionGroup('Aktivitas Kendaraan', [
            'aktivitas-kendaraan.index',
            'aktivitas-kendaraan.create',
            'aktivitas-kendaraan.show',
            'aktivitas-kendaraan.edit',
            'aktivitas-kendaraan.delete',
            'aktivitas-kendaraan.laporan'
        ], $superAdminRole);

        // 12. PEMINJAMAN KENDARAAN
        $this->createPermissionGroup('Peminjaman Kendaraan', [
            'peminjaman-kendaraan.index',
            'peminjaman-kendaraan.create',
            'peminjaman-kendaraan.show',
            'peminjaman-kendaraan.edit',
            'peminjaman-kendaraan.delete',
            'peminjaman-kendaraan.approve',
            'peminjaman-kendaraan.return'
        ], $superAdminRole);

        // 13. SERVICE KENDARAAN
        $this->createPermissionGroup('Service Kendaraan', [
            'service-kendaraan.index',
            'service-kendaraan.create',
            'service-kendaraan.show',
            'service-kendaraan.edit',
            'service-kendaraan.delete',
            'service-kendaraan.laporan'
        ], $superAdminRole);

        // 14. LIVE TRACKING
        $this->createPermissionGroup('Live Tracking', [
            'live-tracking.index',
            'live-tracking.show',
            'live-tracking.laporan'
        ], $superAdminRole);

        // ============================================
        // INVENTORY MANAGEMENT
        // ============================================

        // 15. INVENTARIS (Master)
        $this->createPermissionGroup('Inventaris', [
            'inventaris.index',
            'inventaris.create',
            'inventaris.show',
            'inventaris.edit',
            'inventaris.delete',
            'inventaris.import'
        ], $superAdminRole);

        // 16. PEMINJAMAN INVENTARIS
        $this->createPermissionGroup('Peminjaman Inventaris', [
            'peminjaman-inventaris.index',
            'peminjaman-inventaris.create',
            'peminjaman-inventaris.show',
            'peminjaman-inventaris.edit',
            'peminjaman-inventaris.delete',
            'peminjaman-inventaris.approve'
        ], $superAdminRole);

        // 17. PENGEMBALIAN INVENTARIS
        $this->createPermissionGroup('Pengembalian Inventaris', [
            'pengembalian-inventaris.index',
            'pengembalian-inventaris.create',
            'pengembalian-inventaris.show',
            'pengembalian-inventaris.edit',
            'pengembalian-inventaris.delete'
        ], $superAdminRole);

        // 18. HISTORY INVENTARIS
        $this->createPermissionGroup('History Inventaris', [
            'history-inventaris.index',
            'history-inventaris.show',
            'history-inventaris.laporan'
        ], $superAdminRole);
    }

    /**
     * Helper function to create permission group with permissions
     */
    private function createPermissionGroup($groupName, $permissions, $superAdminRole = null)
    {
        // Create or get permission group
        $group = Permission_group::firstOrCreate(['name' => $groupName]);

        // Create permissions
        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['id_permission_group' => $group->id]
            );

            // Update group if not set
            if ($permission->id_permission_group != $group->id) {
                $permission->update(['id_permission_group' => $group->id]);
            }

            // Assign to super admin
            if ($superAdminRole) {
                $superAdminRole->givePermissionTo($permission);
            }
        }

        echo "✓ {$groupName}: " . count($permissions) . " permissions created\n";
    }
}
