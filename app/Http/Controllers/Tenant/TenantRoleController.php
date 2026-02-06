<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class TenantRoleController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $teamId = $user->organization_id;

        if (! $teamId) {
            $roles = collect();
        } else {
            setPermissionsTeamId($teamId);
            $roles = Role::where('team_id', $teamId)->with('permissions')->get();
            setPermissionsTeamId(null);
        }

        return view('tenant.roles.index', compact('roles'));
    }
}
