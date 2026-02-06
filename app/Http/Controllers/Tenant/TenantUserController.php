<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\TenantRoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TenantUserController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        if (! $user->organization_id) {
            $users = collect([$user]);
        } else {
            $users = User::where('organization_id', $user->organization_id)
                ->orderBy('name')
                ->get();
        }

        return view('tenant.users.index', compact('users'));
    }

    public function create(): View|RedirectResponse
    {
        $user = auth()->user();
        if (! $user->organization_id) {
            return redirect()->route($user->tenant_type . '.users.index')
                ->with('error', 'You must have an organization to add users.');
        }

        setPermissionsTeamId($user->organization_id);
        $roles = Role::where('team_id', $user->organization_id)->orderBy('name')->get();
        $permissions = Permission::whereIn('name', TenantRoleService::getDefaultPermissionsForTenantType($user->tenant_type))->orderBy('name')->get();
        setPermissionsTeamId(null);

        if ($roles->isEmpty()) {
            return redirect()->route($user->tenant_type . '.users.index')
                ->with('error', 'No roles found. Please contact support.');
        }

        return view('tenant.users.create', compact('roles', 'permissions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->organization_id) {
            return redirect()->route($user->tenant_type . '.users.index')
                ->with('error', 'You must have an organization to add users.');
        }

        setPermissionsTeamId($user->organization_id);
        $validRoles = Role::where('team_id', $user->organization_id)->pluck('name')->toArray();
        setPermissionsTeamId(null);

        $validPerms = TenantRoleService::getDefaultPermissionsForTenantType($user->tenant_type);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:'.implode(',', $validRoles)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:'.implode(',', $validPerms)],
        ]);

        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tenant_type' => $user->tenant_type,
            'organization_id' => $user->organization_id,
            'is_approved' => false,
        ]);

        setPermissionsTeamId($user->organization_id);
        $newUser->assignRole($request->role);
        $newUser->syncPermissions($request->permissions ?? []);
        setPermissionsTeamId(null);

        return redirect()->route($user->tenant_type . '.users.index')
            ->with('success', 'User created. They must be approved by admin before accessing the dashboard.');
    }

    public function edit(User $editUser): View|RedirectResponse
    {
        $user = auth()->user();
        if (! $user->organization_id || $editUser->organization_id !== $user->organization_id) {
            abort(403);
        }

        setPermissionsTeamId($user->organization_id);
        $roles = Role::where('team_id', $user->organization_id)->orderBy('name')->get();
        $permissions = Permission::whereIn('name', TenantRoleService::getDefaultPermissionsForTenantType($user->tenant_type))->orderBy('name')->get();
        $userPermissions = $editUser->getDirectPermissions()->pluck('name')->toArray();
        $userRole = $editUser->getRoleNames()->first();
        setPermissionsTeamId(null);

        return view('tenant.users.edit', compact('editUser', 'roles', 'permissions', 'userPermissions', 'userRole'));
    }

    public function update(Request $request, User $editUser): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->organization_id || $editUser->organization_id !== $user->organization_id) {
            abort(403);
        }

        setPermissionsTeamId($user->organization_id);
        $validRoles = Role::where('team_id', $user->organization_id)->pluck('name')->toArray();
        $validPerms = TenantRoleService::getDefaultPermissionsForTenantType($user->tenant_type);
        setPermissionsTeamId(null);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$editUser->id],
            'role' => ['required', 'string', 'in:'.implode(',', $validRoles)],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['string', 'in:'.implode(',', $validPerms)],
        ];
        if ($request->filled('password')) {
            $rules['password'] = ['confirmed', Rules\Password::defaults()];
        }
        $request->validate($rules);

        $editUser->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        if ($request->filled('password')) {
            $editUser->update(['password' => Hash::make($request->password)]);
        }

        setPermissionsTeamId($user->organization_id);
        $editUser->syncRoles([$request->role]);
        $editUser->syncPermissions($request->permissions ?? []);
        setPermissionsTeamId(null);

        return redirect()->route($user->tenant_type . '.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $editUser): RedirectResponse
    {
        $user = auth()->user();
        if (! $user->organization_id || $editUser->organization_id !== $user->organization_id) {
            abort(403);
        }
        if ($editUser->id === $user->id) {
            return redirect()->route($user->tenant_type . '.users.index')
                ->with('error', 'You cannot delete yourself.');
        }

        $editUser->delete();

        return redirect()->route($user->tenant_type . '.users.index')
            ->with('success', 'User deleted.');
    }
}
