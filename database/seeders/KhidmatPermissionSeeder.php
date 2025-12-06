<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class KhidmatPermissionSeeder extends Seeder
{
    public function run()
    {
        // Get or create permission group
        $permissionGroup = DB::table('permission_groups')
            ->where('name', 'Khidmat')
            ->first();

        if (!$permissionGroup) {
            $groupId = DB::table('permission_groups')->insertGetId([
                'name' => 'Khidmat',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } else {
            $groupId = $permissionGroup->id;
        }

        // Create permissions
        $permissions = [
            'khidmat.index',
            'khidmat.create',
            'khidmat.edit',
            'khidmat.delete',
            'khidmat.laporan'
        ];

        foreach ($permissions as $permissionName) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionName],
                ['id_permission_group' => $groupId]
            );
            
            // Update id_permission_group if permission already exists
            if ($permission->id_permission_group != $groupId) {
                $permission->update(['id_permission_group' => $groupId]);
            }
        }

        // Assign all permissions to super admin role
        $superAdmin = Role::where('name', 'super admin')->first();
        if ($superAdmin) {
            $superAdmin->givePermissionTo($permissions);
        }

        $this->command->info('Khidmat permissions created and assigned to super admin!');
    }
}
