<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Services\TenantRoleService;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;

class TenantPermissionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $tenantType = $user->tenant_type;

        $permissions = Permission::whereIn(
            'name',
            TenantRoleService::getDefaultPermissionsForTenantType($tenantType)
        )->orderBy('name')->get();

        return view('tenant.permissions.index', compact('permissions'));
    }
}
