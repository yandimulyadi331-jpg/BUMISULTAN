<?php

namespace App\Services;

use Spatie\Permission\Models\Permission;
use App\Models\Permission_group;
use Illuminate\Support\Collection;

class PermissionService
{
    /**
     * Get all permissions grouped by permission group
     * with standardized structure
     */
    public static function getAllPermissionsGrouped(): Collection
    {
        $permissionGroups = Permission_group::with('permissions')
            ->orderBy('name')
            ->get();

        $grouped = collect();

        foreach ($permissionGroups as $group) {
            $permissions = $group->permissions->pluck('name')->toArray();
            
            // Organize permissions by action type
            $organized = self::organizePermissionsByAction($permissions);
            
            $grouped->push([
                'id' => $group->id,
                'name' => $group->name,
                'permissions' => $permissions,
                'organized' => $organized,
                'count' => count($permissions),
            ]);
        }

        return $grouped->sortBy('name');
    }

    /**
     * Get all available actions/operations from permissions
     */
    public static function getAllAvailableActions(): array
    {
        $actions = Permission::selectRaw("REGEXP_SUBSTR(name, '[^.]+$') as action")
            ->distinct()
            ->orderBy('action')
            ->pluck('action')
            ->toArray();

        // Standard CRUD operations should appear first
        $standardCrud = ['index', 'create', 'show', 'edit', 'delete'];
        $sorted = [];

        foreach ($standardCrud as $action) {
            if (in_array($action, $actions)) {
                $sorted[] = $action;
            }
        }

        // Add remaining actions
        foreach ($actions as $action) {
            if (!in_array($action, $sorted)) {
                $sorted[] = $action;
            }
        }

        return $sorted;
    }

    /**
     * Organize permissions by action type (index, create, edit, etc)
     */
    private static function organizePermissionsByAction(array $permissions): array
    {
        $organized = [];
        
        foreach ($permissions as $permission) {
            $parts = explode('.', $permission);
            $action = end($parts);
            
            if (!isset($organized[$action])) {
                $organized[$action] = [];
            }
            
            $organized[$action][] = $permission;
        }

        // Sort by standard CRUD order
        $standardCrud = ['index', 'create', 'show', 'edit', 'delete', 'approve'];
        $sorted = [];

        foreach ($standardCrud as $action) {
            if (isset($organized[$action])) {
                $sorted[$action] = $organized[$action];
                unset($organized[$action]);
            }
        }

        // Add remaining actions
        foreach ($organized as $action => $perms) {
            $sorted[$action] = $perms;
        }

        return $sorted;
    }

    /**
     * Get permission groups with flattened permission structure
     * Format: [
     *   'group_name' => ['permission1', 'permission2', ...],
     *   ...
     * ]
     */
    public static function getPermissionsFlat(): array
    {
        $result = [];
        
        $permissionGroups = Permission_group::with('permissions')
            ->orderBy('name')
            ->get();

        foreach ($permissionGroups as $group) {
            $result[$group->name] = $group->permissions->pluck('name')->toArray();
        }

        return $result;
    }

    /**
     * Validate that all given permissions exist
     */
    public static function validatePermissions(array $permissionNames): array
    {
        $valid = [];
        $invalid = [];

        $existingPermissions = Permission::whereIn('name', $permissionNames)->pluck('name')->toArray();

        foreach ($permissionNames as $name) {
            if (in_array($name, $existingPermissions)) {
                $valid[] = $name;
            } else {
                $invalid[] = $name;
            }
        }

        return [
            'valid' => $valid,
            'invalid' => $invalid
        ];
    }

    /**
     * Get permission statistics
     */
    public static function getStatistics(): array
    {
        return [
            'total_permissions' => Permission::count(),
            'total_groups' => Permission_group::count(),
            'groups' => Permission_group::withCount('permissions')
                ->orderBy('name')
                ->get()
                ->map(fn($g) => [
                    'name' => $g->name,
                    'count' => $g->permissions_count
                ])
                ->toArray(),
        ];
    }
}
