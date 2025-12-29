<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/**
 * COMPREHENSIVE PERMISSION SEEDER - BATCH 3
 * Creates permission groups for maintenance, quality, documents, and admin
 */
class ComprehensivePermissionSeederBatch3 extends Seeder
{
    public function run(): void
    {
        $superAdminRole = Role::where('name', 'super admin')->first();

        // ============================================
        // MAINTENANCE & QUALITY
        // ============================================

        // 1. PERAWATAN (Maintenance)
        $this->createPermissionGroup('Perawatan', [
            'perawatan.index',
            'perawatan.create',
            'perawatan.show',
            'perawatan.edit',
            'perawatan.delete',
            'perawatan.laporan'
        ], $superAdminRole);

        // 2. PERAWATAN KARYAWAN (Employee Maintenance)
        $this->createPermissionGroup('Perawatan Karyawan', [
            'perawatan-karyawan.index',
            'perawatan-karyawan.create',
            'perawatan-karyawan.show',
            'perawatan-karyawan.edit',
            'perawatan-karyawan.delete'
        ], $superAdminRole);

        // 3. TEMUAN (Findings/Issues)
        $this->createPermissionGroup('Temuan', [
            'temuan.index',
            'temuan.create',
            'temuan.show',
            'temuan.edit',
            'temuan.delete',
            'temuan.tindak-lanjut',
            'temuan.laporan'
        ], $superAdminRole);

        // 4. KPI CREW (Performance Indicators)
        $this->createPermissionGroup('KPI Crew', [
            'kpi-crew.index',
            'kpi-crew.create',
            'kpi-crew.show',
            'kpi-crew.edit',
            'kpi-crew.delete',
            'kpi-crew.laporan'
        ], $superAdminRole);

        // 5. TUGAS LUAR (External Tasks)
        $this->createPermissionGroup('Tugas Luar', [
            'tugas-luar.index',
            'tugas-luar.create',
            'tugas-luar.show',
            'tugas-luar.edit',
            'tugas-luar.delete',
            'tugas-luar.laporan'
        ], $superAdminRole);

        // ============================================
        // ADMINISTRATION & DOCUMENTS
        // ============================================

        // 6. ADMINISTRASI (Administration)
        $this->createPermissionGroup('Administrasi', [
            'administrasi.index',
            'administrasi.create',
            'administrasi.show',
            'administrasi.edit',
            'administrasi.delete'
        ], $superAdminRole);

        // 7. DOKUMEN (Documents)
        $this->createPermissionGroup('Dokumen', [
            'dokumen.index',
            'dokumen.create',
            'dokumen.show',
            'dokumen.edit',
            'dokumen.delete',
            'dokumen.download',
            'dokumen.kategorisasi'
        ], $superAdminRole);

        // 8. ADMINISTRASI DOKUMEN
        $this->createPermissionGroup('Administrasi Dokumen', [
            'administrasi-dokumen.index',
            'administrasi-dokumen.create',
            'administrasi-dokumen.show',
            'administrasi-dokumen.edit',
            'administrasi-dokumen.delete',
            'administrasi-dokumen.download'
        ], $superAdminRole);

        // ============================================
        // ADDITIONAL MANAGEMENT MODULES
        // ============================================

        // 9. PRESENSI ISTIRAHAT (Rest/Break Attendance)
        $this->createPermissionGroup('Presensi Istirahat', [
            'presensi-istirahat.index',
            'presensi-istirahat.create',
            'presensi-istirahat.show',
            'presensi-istirahat.edit',
            'presensi-istirahat.delete'
        ], $superAdminRole);

        // 10. PENGGUNA (Users)
        $this->createPermissionGroup('Pengguna', [
            'pengguna.index',
            'pengguna.create',
            'pengguna.show',
            'pengguna.edit',
            'pengguna.delete',
            'pengguna.reset-password'
        ], $superAdminRole);

        // 11. DEPARTEMEN (Departments)
        $this->createPermissionGroup('Departemen', [
            'departemen.index',
            'departemen.create',
            'departemen.show',
            'departemen.edit',
            'departemen.delete'
        ], $superAdminRole);

        // 12. BACKUP DATA (Data Backup)
        $this->createPermissionGroup('Backup Data', [
            'backup-data.index',
            'backup-data.create',
            'backup-data.restore',
            'backup-data.download'
        ], $superAdminRole);

        // 13. LOG SISTEM (System Logs)
        $this->createPermissionGroup('Log Sistem', [
            'log-sistem.index',
            'log-sistem.show',
            'log-sistem.clear'
        ], $superAdminRole);

        // 14. SETTING APLIKASI (Application Settings)
        $this->createPermissionGroup('Setting Aplikasi', [
            'setting-aplikasi.index',
            'setting-aplikasi.edit',
            'setting-aplikasi.view'
        ], $superAdminRole);

        // 15. NOTIFIKASI (Notifications)
        $this->createPermissionGroup('Notifikasi', [
            'notifikasi.index',
            'notifikasi.show',
            'notifikasi.delete',
            'notifikasi.mark-as-read'
        ], $superAdminRole);

        // ============================================
        // ADDITIONAL FINANCIAL/OPERATIONAL
        // ============================================

        // 16. REALISASI ANGGARAN (Budget Realization)
        $this->createPermissionGroup('Realisasi Anggaran', [
            'realisasi-anggaran.index',
            'realisasi-anggaran.create',
            'realisasi-anggaran.show',
            'realisasi-anggaran.edit',
            'realisasi-anggaran.delete',
            'realisasi-anggaran.laporan'
        ], $superAdminRole);

        // 17. VERIFIKASI ANGGARAN (Budget Verification)
        $this->createPermissionGroup('Verifikasi Anggaran', [
            'verifikasi-anggaran.index',
            'verifikasi-anggaran.show',
            'verifikasi-anggaran.approve',
            'verifikasi-anggaran.reject'
        ], $superAdminRole);

        // 18. POTONGAN GAJI (Salary Deductions)
        $this->createPermissionGroup('Potongan Gaji', [
            'potongan-gaji.index',
            'potongan-gaji.create',
            'potongan-gaji.show',
            'potongan-gaji.edit',
            'potongan-gaji.delete'
        ], $superAdminRole);

        // 19. REALISASI PINJAMAN (Loan Realization)
        $this->createPermissionGroup('Realisasi Pinjaman', [
            'realisasi-pinjaman.index',
            'realisasi-pinjaman.create',
            'realisasi-pinjaman.show',
            'realisasi-pinjaman.edit',
            'realisasi-pinjaman.delete',
            'realisasi-pinjaman.laporan'
        ], $superAdminRole);

        // 20. BANK ACCOUNT (Bank Accounts)
        $this->createPermissionGroup('Bank Account', [
            'bank-account.index',
            'bank-account.create',
            'bank-account.show',
            'bank-account.edit',
            'bank-account.delete'
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
