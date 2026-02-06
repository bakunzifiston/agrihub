<?php

namespace App\Services;

use App\Models\TenantOrganization;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantRoleService
{
    public static function getDefaultPermissionsForTenantType(string $tenantType): array
    {
        return match ($tenantType) {
            User::TENANT_FARMER => [
                'manage-farm-profile', 'manage-crops', 'manage-livestock',
                'manage-production-records', 'manage-inputs', 'manage-outputs',
                'manage-sales', 'view-reports', 'manage-users', 'manage-roles',
            ],
            User::TENANT_COOPERATIVE => [
                'manage-members', 'manage-collections', 'manage-inventory',
                'manage-payments', 'view-performance', 'manage-users', 'manage-roles',
            ],
            User::TENANT_AGRIBUSINESS => [
                'manage-suppliers', 'manage-contracts', 'manage-processing',
                'manage-inventory', 'manage-distributions', 'view-reports',
                'manage-users', 'manage-roles',
            ],
            default => [],
        };
    }

    public static function setupDefaultRolesForOrganization(TenantOrganization $org): void
    {
        $permissions = self::getDefaultPermissionsForTenantType($org->tenant_type);

        foreach ($permissions as $permName) {
            Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
        }

        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin', 'guard_name' => 'web', 'team_id' => $org->id],
            ['name' => 'Admin', 'guard_name' => 'web', 'team_id' => $org->id]
        );
        $adminRole->syncPermissions($permissions);

        $memberPerms = array_diff($permissions, ['manage-users', 'manage-roles']);
        $memberRole = Role::firstOrCreate(
            ['name' => 'Member', 'guard_name' => 'web', 'team_id' => $org->id],
            ['name' => 'Member', 'guard_name' => 'web', 'team_id' => $org->id]
        );
        $memberRole->syncPermissions($memberPerms);

        $viewerPerms = array_filter($permissions, fn ($p) => str_starts_with($p, 'view-'));
        $viewerRole = Role::firstOrCreate(
            ['name' => 'Viewer', 'guard_name' => 'web', 'team_id' => $org->id],
            ['name' => 'Viewer', 'guard_name' => 'web', 'team_id' => $org->id]
        );
        $viewerRole->syncPermissions($viewerPerms);
    }
}
