<?php

namespace App\Http\Controllers;

use App\Models\FeatureSetting;
use App\Models\User;
use App\Services\FeatureService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function __construct(
        protected FeatureService $featureService
    ) {}
    /**
     * Display the super admin dashboard with pending tenant approvals.
     */
    public function dashboard(): View
    {
        $pendingTenants = User::whereIn('tenant_type', [
            User::TENANT_FARMER,
            User::TENANT_COOPERATIVE,
            User::TENANT_AGRIBUSINESS,
        ])
            ->where('is_approved', false)
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedTenants = User::whereIn('tenant_type', [
            User::TENANT_FARMER,
            User::TENANT_COOPERATIVE,
            User::TENANT_AGRIBUSINESS,
        ])
            ->where('is_approved', true)
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        $tenantTypes = [
            'farmer' => ['label' => 'Farmer', 'features' => FeatureService::FARMER_FEATURES],
            'cooperative' => ['label' => 'Cooperative', 'features' => FeatureService::COOPERATIVE_FEATURES],
            'agribusiness' => ['label' => 'Agribusiness', 'features' => FeatureService::AGRIBUSINESS_FEATURES],
        ];

        $featureSettings = FeatureSetting::whereNull('user_id')->get()->keyBy(fn ($s) => "{$s->feature_key}:{$s->tenant_type}");

        return view('admin.dashboard', [
            'pendingTenants' => $pendingTenants,
            'approvedTenants' => $approvedTenants,
            'tenantTypes' => $tenantTypes,
            'featureSettings' => $featureSettings,
        ]);
    }

    /**
     * Feature toggles - per tenant type and per individual tenant.
     */
    public function featureToggles(): View
    {
        $tenantTypes = [
            'farmer' => ['label' => 'Farmer', 'features' => FeatureService::FARMER_FEATURES],
            'cooperative' => ['label' => 'Cooperative', 'features' => FeatureService::COOPERATIVE_FEATURES],
            'agribusiness' => ['label' => 'Agribusiness', 'features' => FeatureService::AGRIBUSINESS_FEATURES],
        ];

        $settings = FeatureSetting::all()->keyBy(fn ($s) => "{$s->feature_key}:{$s->tenant_type}:" . ($s->user_id ?? 'default'));

        return view('admin.feature-toggles', [
            'tenantTypes' => $tenantTypes,
            'settings' => $settings,
        ]);
    }

    /**
     * Update feature toggle (tenant type default).
     */
    public function updateFeatureToggle(Request $request): RedirectResponse
    {
        $request->validate([
            'feature_key' => 'required|string',
            'tenant_type' => 'required|in:farmer,cooperative,agribusiness',
            'enabled' => 'required|boolean',
        ]);

        $this->featureService->setTenantTypeDefault(
            $request->tenant_type,
            $request->feature_key,
            (bool) $request->enabled
        );

        return back()->with('status', 'Feature updated.');
    }

    /**
     * Update feature for specific tenant.
     */
    public function updateTenantFeature(Request $request, User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'feature_key' => 'required|string',
            'enabled' => 'required|boolean',
        ]);

        $features = $this->featureService->getFeaturesForTenantType($user->tenant_type);
        if (! isset($features[$request->feature_key])) {
            abort(404, 'Invalid feature for this tenant type.');
        }

        $this->featureService->setUserOverride($user, $request->feature_key, (bool) $request->enabled);

        return back()->with('status', "Feature updated for {$user->name}.");
    }

    /**
     * Manage features for a specific tenant.
     */
    public function tenantFeatures(User $user): View
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }

        $features = $this->featureService->getFeaturesForTenantType($user->tenant_type);
        $overrides = FeatureSetting::where('user_id', $user->id)->get()->keyBy('feature_key');

        return view('admin.tenant-features', [
            'tenant' => $user,
            'features' => $features,
            'overrides' => $overrides,
        ]);
    }

    /**
     * Approve a tenant account.
     */
    public function approve(User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot approve super admin accounts.');
        }

        $user->update(['is_approved' => true]);

        return back()->with('status', "{$user->name} has been approved.");
    }

    /**
     * Reject/revoke approval of a tenant account.
     */
    public function reject(User $user): RedirectResponse
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Cannot reject super admin accounts.');
        }

        $user->update(['is_approved' => false]);

        return back()->with('status', "{$user->name} has been rejected.");
    }
}
