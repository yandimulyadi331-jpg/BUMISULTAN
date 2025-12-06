<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PotonganPinjamanPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek/buat permission group untuk Payroll
        $permissionGroup = DB::table('permission_groups')
            ->where('name', 'Payroll')
            ->first();
            
        if (!$permissionGroup) {
            $groupId = DB::table('permission_groups')->insertGetId([
                'name' => 'Payroll',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $groupId = $permissionGroup->id;
        }

        // Buat permissions untuk Potongan Pinjaman
        $permissions = [
            'potongan_pinjaman.index',
            'potongan_pinjaman.generate',
            'potongan_pinjaman.proses',
            'potongan_pinjaman.delete',
        ];

        foreach ($permissions as $permissionName) {
            // Cek apakah permission sudah ada
            $existingPermission = DB::table('permissions')
                ->where('name', $permissionName)
                ->first();
                
            if (!$existingPermission) {
                DB::table('permissions')->insert([
                    'name' => $permissionName,
                    'guard_name' => 'web',
                    'id_permission_group' => $groupId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Assign ke role super admin
        $superAdmin = Role::where('name', 'super admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        // Assign ke role admin jika ada
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }
    }
}
