<?php

namespace Database\Seeders;

use App\Models\Permission_group;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PelanggaranSantriPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat permission group
        $permissiongroup = Permission_group::firstOrCreate([
            'name' => 'Pelanggaran Santri'
        ]);

        // Buat permissions untuk Pelanggaran Santri
        $permissions = [
            'pelanggaran-santri.index',
            'pelanggaran-santri.create',
            'pelanggaran-santri.edit',
            'pelanggaran-santri.delete',
            'pelanggaran-santri.laporan',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate([
                'name' => $permissionName
            ], [
                'id_permission_group' => $permissiongroup->id
            ]);
        }

        // Assign semua permissions ke role super admin
        $superAdmin = Role::where('name', 'super admin')->first();
        if ($superAdmin) {
            $permissionModels = Permission::whereIn('name', $permissions)->get();
            $superAdmin->givePermissionTo($permissionModels);
        }

        $this->command->info('Permissions untuk Pelanggaran Santri berhasil dibuat!');
    }
}
