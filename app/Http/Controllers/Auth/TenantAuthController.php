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

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'location' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
        ];

        if ($tenantType === 'farmer') {
            $rules['farm_name'] = ['required', 'string', 'max:255'];
            $rules['farm_type'] = ['required', 'string', 'in:Crop,Livestock,Mixed'];
        }
        if ($tenantType === 'cooperative') {
            $rules['cooperative_name'] = ['required', 'string', 'max:255'];
            $rules['cooperative_focus'] = ['required', 'string', 'in:Crops,Livestock,Mixed'];
            $rules['members_range'] = ['required', 'string', 'max:50'];
        }
        if ($tenantType === 'agribusiness') {
            $rules['business_name'] = ['required', 'string', 'max:255'];
            $rules['business_type'] = ['required', 'string', 'in:Buyer,Processor,Exporter,Retailer'];
        }

        $validated = $request->validate($rules);

        $user = DB::transaction(function () use ($request, $tenantType, $validated) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'tenant_type' => $tenantType,
                'is_approved' => false,
                'location' => $validated['location'] ?? null,
                'country' => $validated['country'] ?? null,
                'district' => $validated['district'] ?? null,
                'farm_name' => $validated['farm_name'] ?? null,
                'farm_type' => $validated['farm_type'] ?? null,
                'cooperative_name' => $validated['cooperative_name'] ?? null,
                'cooperative_focus' => $validated['cooperative_focus'] ?? null,
                'members_range' => $validated['members_range'] ?? null,
                'business_name' => $validated['business_name'] ?? null,
                'business_type' => $validated['business_type'] ?? null,
            ]);

            $orgName = match ($tenantType) {
                'farmer' => $validated['farm_name'] ?? $request->name,
                'cooperative' => $validated['cooperative_name'] ?? $request->name,
                'agribusiness' => $validated['business_name'] ?? $request->name,
                default => $request->name . "'s " . ucfirst($tenantType),
            };
            $org = TenantOrganization::create([
                'owner_id' => $user->id,
                'tenant_type' => $tenantType,
                'name' => $orgName,
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
