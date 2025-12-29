<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap/app.php';

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

echo "\n=== ASSIGN ALL PERMISSIONS TO SUPER ADMIN ===\n\n";

// Get Super Admin role
$superAdmin = Role::where('name', 'super admin')->first();
if (!$superAdmin) {
    echo "ERROR: Super Admin role not found!\n";
    exit(1);
}

// Get all permissions
$allPermissions = Permission::all();
$count = $allPermissions->count();

echo "Found {$count} permissions in database.\n";
echo "Assigning to Super Admin...\n\n";

// Assign all permissions
$superAdmin->syncPermissions($allPermissions);

echo "✅ SUCCESS!\n";
echo "Super Admin now has {$superAdmin->permissions()->count()} permissions!\n";
echo "\nStatus: READY - Login as Super Admin to see all menus.\n";
echo "==============================================\n\n";
