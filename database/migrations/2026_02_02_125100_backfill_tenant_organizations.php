<?php

use App\Models\TenantOrganization;
use App\Models\User;
use App\Services\TenantRoleService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $tenantTypes = [User::TENANT_FARMER, User::TENANT_COOPERATIVE, User::TENANT_AGRIBUSINESS];

        User::whereIn('tenant_type', $tenantTypes)
            ->whereNull('organization_id')
            ->each(function (User $user) {
                $org = TenantOrganization::create([
                    'owner_id' => $user->id,
                    'tenant_type' => $user->tenant_type,
                    'name' => $user->name . "'s " . ucfirst($user->tenant_type),
                ]);

                $user->update(['organization_id' => $org->id]);

                TenantRoleService::setupDefaultRolesForOrganization($org);

                setPermissionsTeamId($org->id);
                $user->assignRole('Admin');
                setPermissionsTeamId(null);
            });
    }

    public function down(): void
    {
        // Cannot easily reverse - would need to remove org assignments
    }
};
