<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Permission_group;

/**
 * DEPARTEMEN ROLE SEEDER
 * 
 * Seeder ini membuat role untuk setiap departemen/divisi
 * dan assign permission yang sesuai dengan tugas departemen
 */
class DepartemenRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🏢 Creating Departemen Roles...');

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. DEPARTEMEN HRD (Human Resource Development)
        $this->createRoleWithPermissions('Departemen HRD', [
            // Karyawan Management
            'karyawan.index',
            'karyawan.create',
            'karyawan.show',
            'karyawan.edit',
            'karyawan.delete',
            
            // Presensi
            'presensi.index',
            'presensi.show',
            'presensi.approve',
            'presensi.export',
            
            // Slip Gaji
            'slipgaji.index',
            'slipgaji.create',
            'slipgaji.show',
            
            // Izin/Cuti
            'izin.index',
            'izin.approve',
            'lembur.index',
            'lembur.approve',
            
            // Laporan
            'laporan.presensi',
            'laporan.karyawan',
        ]);

        // 2. DEPARTEMEN KEUANGAN
        $this->createRoleWithPermissions('Departemen Keuangan', [
            // Dana Operasional
            'dana-operasional.index',
            'dana-operasional.create',
            'dana-operasional.edit',
            'dana-operasional.delete',
            'dana-operasional.approve',
            'dana-operasional.laporan',
            'dana-operasional.export',
            
            // Pinjaman
            'pinjaman.index',
            'pinjaman.show',
            'pinjaman.approve',
            'pinjaman.export',
            
            // Laporan Keuangan
            'laporan-keuangan.index',
            'laporan-keuangan.export',
            'laporan-keuangan.show',
            
            // Transaksi
            'transaksi-keuangan.index',
            'transaksi-keuangan.create',
            
            // Keuangan Santri
            'keuangan-santri.index',
            'keuangan-santri.show',
        ]);

        // 3. DEPARTEMEN OPERASIONAL
        $this->createRoleWithPermissions('Departemen Operasional', [
            // Dashboard
            'dashboard.index',
            
            // Inventaris
            'inventaris.index',
            'inventaris.create',
            'inventaris.show',
            'inventaris.edit',
            'inventaris.delete',
            
            // Peminjaman Inventaris
            'peminjaman-inventaris.index',
            'peminjaman-inventaris.create',
            'peminjaman-inventaris.approve',
            
            // Kendaraan
            'kendaraan.index',
            'kendaraan.show',
            
            // Peminjaman Kendaraan
            'peminjaman-kendaraan.index',
            'peminjaman-kendaraan.create',
            'peminjaman-kendaraan.approve',
        ]);

        // 4. DEPARTEMEN KEBERSIHAN
        $this->createRoleWithPermissions('Departemen Kebersihan', [
            // Dashboard
            'dashboard.index',
            
            // Perawatan
            'perawatan.index',
            'perawatan.create',
            'perawatan.show',
            'perawatan.edit',
            'perawatan.delete',
            'perawatan.export',
            
            // Inventaris (read only)
            'inventaris.index',
            'inventaris.show',
            
            // Checklist Perawatan
            'checklist-perawatan.index',
            'checklist-perawatan.create',
            'checklist-perawatan.edit',
        ]);

        // 5. DIVISI KEAGAMAAN
        $this->createRoleWithPermissions('Divisi Keagamaan', [
            // Dashboard
            'dashboard.index',
            
            // Saung Santri
            'saung-santri.index',
            'saung-santri.create',
            'saung-santri.show',
            'saung-santri.edit',
            'saung-santri.delete',
            'saung-santri.export',
            
            // Keuangan Santri
            'keuangan-santri.index',
            'keuangan-santri.create',
            'keuangan-santri.show',
            'keuangan-santri.edit',
            
            // Jamaah (jika ada)
            'jamaah.index',
            'jamaah.create',
            'jamaah.show',
            'jamaah.edit',
        ]);

        // 6. DEPARTEMEN MAINTENANCE
        $this->createRoleWithPermissions('Departemen Maintenance', [
            // Dashboard
            'dashboard.index',
            
            // Perawatan
            'perawatan.index',
            'perawatan.create',
            'perawatan.show',
            'perawatan.edit',
            'perawatan.delete',
            
            // Service Kendaraan
            'service-kendaraan.index',
            'service-kendaraan.create',
            'service-kendaraan.show',
            'service-kendaraan.edit',
            
            // Inventaris
            'inventaris.index',
            'inventaris.show',
            
            // Tukang
            'tukang.index',
            'tukang.create',
            'tukang.show',
            'tukang.edit',
        ]);

        // 7. ADMIN SANTRI
        $this->createRoleWithPermissions('Admin Santri', [
            // Dashboard
            'dashboard.index',
            
            // Saung Santri (Full Access)
            'saung-santri.index',
            'saung-santri.create',
            'saung-santri.show',
            'saung-santri.edit',
            'saung-santri.delete',
            
            // Keuangan Santri (Full Access)
            'keuangan-santri.index',
            'keuangan-santri.create',
            'keuangan-santri.show',
            'keuangan-santri.edit',
            'keuangan-santri.delete',
            
            // Jamaah
            'jamaah.index',
            'jamaah.create',
            'jamaah.show',
            'jamaah.edit',
            'jamaah.delete',
        ]);

        // 8. KARYAWAN (Default Role - Limited Access)
        $this->createRoleWithPermissions('Karyawan', [
            // Dashboard
            'dashboard.index',
            
            // Profile (own)
            'profile.index',
            'profile.edit',
            
            // Presensi (own)
            'presensi.index',
            'presensi.create',
            
            // Izin/Cuti (own)
            'izin.index',
            'izin.create',
            
            // Slip Gaji (own)
            'slipgaji.index',
            'slipgaji.show',
            
            // Pinjaman (own)
            'pinjaman.index',
            'pinjaman.create',
            'pinjaman.show',
        ]);

        $this->command->info('✅ All Departemen Roles created successfully!');
    }

    /**
     * Create role and assign permissions
     * Only assign if permission exists in database
     */
    private function createRoleWithPermissions(string $roleName, array $permissionNames): void
    {
        // Create or get role
        $role = Role::firstOrCreate(['name' => strtolower($roleName)]);
        $this->command->info("  → Role '{$roleName}' created/found");

        $assignedCount = 0;
        $skippedCount = 0;
        $skippedPermissions = [];

        foreach ($permissionNames as $permissionName) {
            // Check if permission exists
            $permission = Permission::where('name', $permissionName)->first();
            
            if ($permission) {
                // Only assign if not already assigned
                if (!$role->hasPermissionTo($permission)) {
                    $role->givePermissionTo($permission);
                    $assignedCount++;
                }
            } else {
                $skippedCount++;
                $skippedPermissions[] = $permissionName;
            }
        }

        $this->command->info("     ✓ Assigned: {$assignedCount} permissions");
        
        if ($skippedCount > 0) {
            $this->command->warn("     ⚠ Skipped: {$skippedCount} permissions (not found in DB)");
            if ($skippedCount <= 5) {
                foreach ($skippedPermissions as $skipped) {
                    $this->command->warn("       - {$skipped}");
                }
            }
        }
    }
}
