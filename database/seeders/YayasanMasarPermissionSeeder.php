<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class YayasanMasarPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari atau buat permission group Yayasan Masar
        $permissiongroup = Permission_group::where('name', 'Yayasan Masar')->first();

        if (!$permissiongroup) {
            $permissiongroup = Permission_group::create([
                'name' => 'Yayasan Masar'
            ]);
            $this->command->info('Permission group "Yayasan Masar" berhasil dibuat');
        }

        // Define permissions
        $permissions = [
            'yayasan_masar.index',
            'yayasan_masar.create',
            'yayasan_masar.edit',
            'yayasan_masar.delete',
            'yayasan_masar.show',
            'yayasan_masar.setjamkerja',
            'yayasan_masar.setcabang',
        ];

        // Create permissions
        foreach ($permissions as $permissionName) {
            $existingPermission = Permission::where('name', $permissionName)->first();

            if (!$existingPermission) {
                Permission::create([
                    'name' => $permissionName,
                    'id_permission_group' => $permissiongroup->id
                ]);
                $this->command->info("Permission {$permissionName} berhasil ditambahkan");

                // Give permission to role super admin (ID 1)
                $role = Role::findById(1);
                if ($role) {
                    $role->givePermissionTo($permissionName);
                }
            } else {
                $this->command->info("Permission {$permissionName} sudah ada");
            }
        }

        $this->command->info('Semua permission Yayasan Masar berhasil dibuat');
    }
}
