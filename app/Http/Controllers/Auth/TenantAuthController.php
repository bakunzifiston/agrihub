<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TenantOrganization;
use App\Models\User;
use App\Services\TenantRoleService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class TenantAuthController extends Controller
{
    protected const TENANT_TYPES = ['farmer', 'cooperative', 'agribusiness'];

    /**
     * Display the tenant login view.
     */
    public function showLoginForm(?string $tenantType = null): View
    {
        $tenantType = $tenantType ?? request()->segment(1);
        $this->validateTenantType($tenantType);

        return view('auth.tenant-login', [
            'tenantType' => $tenantType,
            'tenantLabel' => $this->getTenantLabel($tenantType),
        ]);
    }

    /**
     * Display the tenant registration view.
     */
    public function showRegisterForm(?string $tenantType = null): View
    {
        $tenantType = $tenantType ?? request()->segment(1);
        $this->validateTenantType($tenantType);

        return view('auth.tenant-register', [
            'tenantType' => $tenantType,
            'tenantLabel' => $this->getTenantLabel($tenantType),
        ]);
    }

    /**
     * Handle tenant registration.
     */
    public function register(Request $request, ?string $tenantType = null): RedirectResponse
    {
        $tenantType = $tenantType ?? $request->segment(1);
        $this->validateTenantType($tenantType);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request, $tenantType) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_type' => $tenantType,
                'is_approved' => false,
            ]);

            $org = TenantOrganization::create([
                'owner_id' => $user->id,
                'tenant_type' => $tenantType,
                'name' => $request->name . "'s " . ucfirst($tenantType),
            ]);

            $user->update(['organization_id' => $org->id]);

            TenantRoleService::setupDefaultRolesForOrganization($org);

            setPermissionsTeamId($org->id);
            $user->assignRole('Admin');
            setPermissionsTeamId(null);

            return $user->fresh();
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('approval.pending');
    }

    /**
     * Handle tenant login.
     */
    public function login(Request $request, ?string $tenantType = null): RedirectResponse
    {
        $tenantType = $tenantType ?? $request->segment(1);
        $this->validateTenantType($tenantType);

        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => __('auth.failed'),
            ])->onlyInput('email');
        }

        $user = Auth::user();

        if ($user->tenant_type !== $tenantType) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'This account is not registered as a ' . $this->getTenantLabel($tenantType) . '. Please use the correct portal.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (! $user->isApproved()) {
            return redirect(route('approval.pending'));
        }

        return redirect()->intended($this->getDashboardRouteForTenant($tenantType));
    }

    protected function validateTenantType(string $tenantType): void
    {
        if (! in_array($tenantType, self::TENANT_TYPES)) {
            abort(404);
        }
    }

    protected function getTenantLabel(string $tenantType): string
    {
        return match ($tenantType) {
            'farmer' => 'Farmer',
            'cooperative' => 'Cooperative',
            'agribusiness' => 'Agribusiness',
            default => ucfirst($tenantType),
        };
    }

    protected function getDashboardRouteForTenant(string $tenantType): string
    {
        return match ($tenantType) {
            'farmer' => route('farmer.dashboard'),
            'cooperative' => route('cooperative.dashboard'),
            'agribusiness' => route('agribusiness.dashboard'),
            default => route('home'),
        };
    }
}
