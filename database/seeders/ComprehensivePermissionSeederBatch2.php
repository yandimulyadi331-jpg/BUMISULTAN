<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * COMPREHENSIVE PERMISSION SEEDER - BATCH 2
 * Creates permission groups for facilities, students, and management
 */
class ComprehensivePermissionSeederBatch2 extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super admin')->first();

        // ============================================
        // FACILITY & ASSET MANAGEMENT
        // ============================================

        // 1. GEDUNG (Buildings)
        $this->createPermissionGroup('Gedung', [
            'gedung.index',
            'gedung.create',
            'gedung.show',
            'gedung.edit',
            'gedung.delete'
        ], $superAdminRole);

        // 2. RUANGAN (Rooms)
        $this->createPermissionGroup('Ruangan', [
            'ruangan.index',
            'ruangan.create',
            'ruangan.show',
            'ruangan.edit',
            'ruangan.delete'
        ], $superAdminRole);

        // 3. BARANG (Items/Assets)
        $this->createPermissionGroup('Barang', [
            'barang.index',
            'barang.create',
            'barang.show',
            'barang.edit',
            'barang.delete',
            'barang.qr-code'
        ], $superAdminRole);

        // 4. PERALATAN (Equipment)
        $this->createPermissionGroup('Peralatan', [
            'peralatan.index',
            'peralatan.create',
            'peralatan.show',
            'peralatan.edit',
            'peralatan.delete'
        ], $superAdminRole);

        // 5. PEMINJAMAN PERALATAN (Equipment Loans)
        $this->createPermissionGroup('Peminjaman Peralatan', [
            'peminjaman-peralatan.index',
            'peminjaman-peralatan.create',
            'peminjaman-peralatan.show',
            'peminjaman-peralatan.edit',
            'peminjaman-peralatan.delete',
            'peminjaman-peralatan.approve',
            'peminjaman-peralatan.return'
        ], $superAdminRole);

        // ============================================
        // STUDENT MANAGEMENT (SANTRI)
        // ============================================

        // 6. SANTRI (Students)
        $this->createPermissionGroup('Santri', [
            'santri.index',
            'santri.create',
            'santri.show',
            'santri.edit',
            'santri.delete',
            'santri.import'
        ], $superAdminRole);

        // 7. JADWAL SANTRI (Student Schedule)
        $this->createPermissionGroup('Jadwal Santri', [
            'jadwal-santri.index',
            'jadwal-santri.create',
            'jadwal-santri.show',
            'jadwal-santri.edit',
            'jadwal-santri.delete'
        ], $superAdminRole);

        // 8. ABSENSI SANTRI (Student Attendance)
        $this->createPermissionGroup('Absensi Santri', [
            'absensi-santri.index',
            'absensi-santri.create',
            'absensi-santri.show',
            'absensi-santri.edit',
            'absensi-santri.delete',
            'absensi-santri.laporan'
        ], $superAdminRole);

        // 9. IZIN SANTRI (Student Leave)
        $this->createPermissionGroup('Izin Santri', [
            'izin-santri.index',
            'izin-santri.create',
            'izin-santri.show',
            'izin-santri.edit',
            'izin-santri.delete',
            'izin-santri.approve'
        ], $superAdminRole);

        // ============================================
        // RELIGIOUS EVENTS
        // ============================================

        // 10. MAJLIS TAKLIM (Islamic Study Group)
        $this->createPermissionGroup('Majlis Taklim', [
            'majlis-taklim.index',
            'majlis-taklim.create',
            'majlis-taklim.show',
            'majlis-taklim.edit',
            'majlis-taklim.delete'
        ], $superAdminRole);

        // 11. JAMAAH MAJLIS TAKLIM
        $this->createPermissionGroup('Jamaah Majlis Taklim', [
            'jamaah-majlis-taklim.index',
            'jamaah-majlis-taklim.create',
            'jamaah-majlis-taklim.show',
            'jamaah-majlis-taklim.edit',
            'jamaah-majlis-taklim.delete',
            'jamaah-majlis-taklim.import'
        ], $superAdminRole);

        // 12. HADIAH MAJLIS TAKLIM
        $this->createPermissionGroup('Hadiah Majlis Taklim', [
            'hadiah-majlis-taklim.index',
            'hadiah-majlis-taklim.create',
            'hadiah-majlis-taklim.show',
            'hadiah-majlis-taklim.edit',
            'hadiah-majlis-taklim.delete',
            'hadiah-majlis-taklim.laporan'
        ], $superAdminRole);

        // 13. JAMAAH MASAR
        $this->createPermissionGroup('Jamaah Masar', [
            'jamaah-masar.index',
            'jamaah-masar.create',
            'jamaah-masar.show',
            'jamaah-masar.edit',
            'jamaah-masar.delete',
            'jamaah-masar.import',
            'jamaah-masar.export'
        ], $superAdminRole);

        // 14. HADIAH MASAR
        $this->createPermissionGroup('Hadiah Masar', [
            'hadiah-masar.index',
            'hadiah-masar.create',
            'hadiah-masar.show',
            'hadiah-masar.edit',
            'hadiah-masar.delete'
        ], $superAdminRole);

        // 15. DISTRIBUSI HADIAH MASAR
        $this->createPermissionGroup('Distribusi Hadiah Masar', [
            'distribusi-hadiah-masar.index',
            'distribusi-hadiah-masar.create',
            'distribusi-hadiah-masar.show',
            'distribusi-hadiah-masar.edit',
            'distribusi-hadiah-masar.delete',
            'distribusi-hadiah-masar.laporan'
        ], $superAdminRole);

        // 16. UNDIAN UMROH (Umrah Lottery)
        $this->createPermissionGroup('Undian Umroh', [
            'undian-umroh.index',
            'undian-umroh.create',
            'undian-umroh.show',
            'undian-umroh.edit',
            'undian-umroh.delete',
            'undian-umroh.laporan'
        ], $superAdminRole);

        // ============================================
        // CONTRACTOR MANAGEMENT
        // ============================================

        // 17. TUKANG (Contractors/Workers)
        $this->createPermissionGroup('Tukang', [
            'tukang.index',
            'tukang.create',
            'tukang.show',
            'tukang.edit',
            'tukang.delete',
            'tukang.import'
        ], $superAdminRole);

        // 18. KEHADIRAN TUKANG (Contractor Attendance)
        $this->createPermissionGroup('Kehadiran Tukang', [
            'kehadiran-tukang.index',
            'kehadiran-tukang.create',
            'kehadiran-tukang.show',
            'kehadiran-tukang.edit',
            'kehadiran-tukang.delete',
            'kehadiran-tukang.laporan'
        ], $superAdminRole);

        // ============================================
        // VISITOR MANAGEMENT
        // ============================================

        // 19. PENGUNJUNG (Visitors)
        $this->createPermissionGroup('Pengunjung', [
            'pengunjung.index',
            'pengunjung.create',
            'pengunjung.show',
            'pengunjung.edit',
            'pengunjung.delete',
            'pengunjung.laporan'
        ], $superAdminRole);

        // 20. PENGUNJUNG KARYAWAN (Employee Visitors)
        $this->createPermissionGroup('Pengunjung Karyawan', [
            'pengunjung-karyawan.index',
            'pengunjung-karyawan.create',
            'pengunjung-karyawan.show',
            'pengunjung-karyawan.edit',
            'pengunjung-karyawan.delete'
        ], $superAdminRole);

        // 21. JADWAL PENGUNJUNG (Visitor Schedule)
        $this->createPermissionGroup('Jadwal Pengunjung', [
            'jadwal-pengunjung.index',
            'jadwal-pengunjung.create',
            'jadwal-pengunjung.show',
            'jadwal-pengunjung.edit',
            'jadwal-pengunjung.delete'
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
