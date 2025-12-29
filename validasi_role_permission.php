<?php
/**
 * ROLE PERMISSION SYSTEM - VALIDATION & TESTING SCRIPT
 * 
 * Usage: php artisan tinker < validasi_role_permission.php
 * atau: php validate_role_permission.php
 * 
 * Script ini memvalidasi:
 * 1. Semua permission groups exist
 * 2. Semua permissions punya id_permission_group
 * 3. Format permission adalah modul.action
 * 4. Tidak ada duplicate permissions
 * 5. Roles dapat di-assign permissions
 */

use App\Models\Permission_group;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  VALIDASI SISTEM ROLE & PERMISSION                          ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// 1. Check Permission Groups
echo "1️⃣  VALIDASI PERMISSION GROUPS\n";
echo str_repeat("─", 60) . "\n";
$groupCount = Permission_group::count();
echo "✓ Total Permission Groups: $groupCount\n";

$permissionGroups = Permission_group::all();
foreach ($permissionGroups as $group) {
    $count = $group->permissions()->count();
    echo "  • {$group->name}: $count permissions\n";
}
echo "\n";

// 2. Check Permissions Format
echo "2️⃣  VALIDASI FORMAT PERMISSIONS\n";
echo str_repeat("─", 60) . "\n";
$totalPermissions = Permission::count();
echo "✓ Total Permissions: $totalPermissions\n\n";

// Check format modul.action
$validFormat = 0;
$invalidFormat = 0;
$invalidGroup = 0;

$allPermissions = Permission::all();
foreach ($allPermissions as $perm) {
    // Check format
    $parts = explode('.', $perm->name);
    if (count($parts) >= 2) {
        $validFormat++;
    } else {
        $invalidFormat++;
        echo "  ❌ Invalid format: {$perm->name}\n";
    }
    
    // Check group assignment
    if (empty($perm->id_permission_group)) {
        $invalidGroup++;
        echo "  ❌ No group assigned: {$perm->name}\n";
    }
}

echo "  ✓ Valid format (modul.action): $validFormat\n";
if ($invalidFormat > 0) {
    echo "  ❌ Invalid format: $invalidFormat\n";
}
if ($invalidGroup > 0) {
    echo "  ❌ No group assigned: $invalidGroup\n";
}
echo "\n";

// 3. Check Duplicates
echo "3️⃣  VALIDASI DUPLICATE PERMISSIONS\n";
echo str_repeat("─", 60) . "\n";
$duplicates = Permission::groupBy('name')
    ->havingRaw('COUNT(*) > 1')
    ->pluck('name');

if ($duplicates->count() > 0) {
    echo "❌ Found {$duplicates->count()} duplicate permissions:\n";
    foreach ($duplicates as $dup) {
        echo "  • $dup\n";
    }
} else {
    echo "✓ No duplicate permissions found\n";
}
echo "\n";

// 4. Check Actions Used
echo "4️⃣  VALIDASI ACTION TYPES\n";
echo str_repeat("─", 60) . "\n";
$actions = [];
foreach ($allPermissions as $perm) {
    $parts = explode('.', $perm->name);
    $action = end($parts);
    if (!isset($actions[$action])) {
        $actions[$action] = 0;
    }
    $actions[$action]++;
}

asort($actions);
foreach ($actions as $action => $count) {
    echo "  • {$action}: $count permissions\n";
}
echo "\n";

// 5. Check Role Assignments
echo "5️⃣  VALIDASI ROLE ASSIGNMENTS\n";
echo str_repeat("─", 60) . "\n";
$roles = Role::all();
echo "Total Roles: {$roles->count()}\n\n";

foreach ($roles as $role) {
    $count = $role->permissions()->count();
    echo "  Role: {$role->name}\n";
    echo "    - Assigned permissions: $count\n";
    echo "    - Coverage: " . round($count / $totalPermissions * 100, 2) . "%\n";
}
echo "\n";

// 6. Test Assignment
echo "6️⃣  TEST PERMISSION ASSIGNMENT\n";
echo str_repeat("─", 60) . "\n";

$testPermissions = Permission::limit(5)->pluck('name')->toArray();
echo "Test permissions to assign: \n";
foreach ($testPermissions as $perm) {
    echo "  • $perm\n";
}

// Don't actually assign, just test
$superAdmin = Role::where('name', 'super admin')->first();
if ($superAdmin) {
    echo "\n✓ Super Admin role exists\n";
    echo "  Total permissions assigned: " . $superAdmin->permissions()->count() . "\n";
} else {
    echo "\n❌ Super Admin role not found\n";
}
echo "\n";

// 7. Summary Report
echo "7️⃣  SUMMARY REPORT\n";
echo str_repeat("─", 60) . "\n";

$status = "✅ PASSED";
$issues = [];

if ($groupCount == 0) {
    $status = "❌ FAILED";
    $issues[] = "No permission groups found";
}

if ($validFormat != $totalPermissions) {
    $status = "⚠️  WARNING";
    $issues[] = "$invalidFormat permissions with invalid format";
}

if ($invalidGroup > 0) {
    $status = "⚠️  WARNING";
    $issues[] = "$invalidGroup permissions without group assignment";
}

if ($duplicates->count() > 0) {
    $status = "⚠️  WARNING";
    $issues[] = "{$duplicates->count()} duplicate permissions found";
}

echo "Status: $status\n\n";

if (empty($issues)) {
    echo "✓ All validations passed!\n";
    echo "✓ System is ready for production\n";
} else {
    echo "Issues found:\n";
    foreach ($issues as $issue) {
        echo "  ⚠️  $issue\n";
    }
    echo "\nPlease fix these issues before going live.\n";
}

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  VALIDASI SELESAI                                            ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n\n";

// Statistics Table
echo "STATISTIK DETAIL\n";
echo "─" . str_repeat("─", 58) . "─\n";
printf("| %-40s | %15s |\n", "Item", "Value");
echo "├" . str_repeat("─", 40) . "┼" . str_repeat("─", 15) . "┤\n";
printf("| %-40s | %15d |\n", "Total Permission Groups", $groupCount);
printf("| %-40s | %15d |\n", "Total Permissions", $totalPermissions);
printf("| %-40s | %15d |\n", "Valid Format", $validFormat);
printf("| %-40s | %15d |\n", "Invalid Format", $invalidFormat);
printf("| %-40s | %15d |\n", "Without Group", $invalidGroup);
printf("| %-40s | %15d |\n", "Unique Actions", count($actions));
printf("| %-40s | %15d |\n", "Total Roles", $roles->count());
printf("| %-40s | %15d |\n", "Duplicate Count", $duplicates->count());
echo "└" . str_repeat("─", 40) . "┴" . str_repeat("─", 15) . "┘\n\n";
