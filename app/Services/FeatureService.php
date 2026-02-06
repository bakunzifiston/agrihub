<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FeatureService
{
    /** Base dashboard - always shown for all tenant types */
    public const DEFAULT_DASHBOARD = 'dashboard';

    /** Default features per tenant type (feature_key => enabled) */
    public const TENANT_TYPE_DEFAULTS = [
        'farmer' => [
            'dashboard' => true,
            'farm_profile' => true,
            'crop_livestock_tracking' => true,
            'production_records' => true,
            'inventory' => true,
            'sales_income' => true,
            'reports' => true,
        ],
        'cooperative' => [
            'dashboard' => true,
            'member_management' => true,
            'collection_aggregation' => true,
            'cooperative_inventory' => true,
            'payments_to_farmers' => true,
            'performance_analytics' => true,
        ],
        'agribusiness' => [
            'dashboard' => true,
            'supplier_management' => true,
            'procurement_contracts' => true,
            'processing_production' => true,
            'inventory_distribution' => true,
            'sales_financial_reports' => true,
        ],
    ];

    public const FARMER_FEATURES = [
        'dashboard' => 'Dashboard Overview',
        'farm_profile' => 'Farm Profile Management',
        'crop_livestock_tracking' => 'Crop & Livestock Tracking',
        'production_records' => 'Production Records',
        'inventory' => 'Inventory (Inputs & Outputs)',
        'sales_income' => 'Sales & Income Tracking',
        'reports' => 'Simple Reports',
    ];

    public const COOPERATIVE_FEATURES = [
        'dashboard' => 'Dashboard Overview',
        'member_management' => 'Member (Farmer) Management',
        'collection_aggregation' => 'Collection & Aggregation Tracking',
        'cooperative_inventory' => 'Cooperative Inventory',
        'payments_to_farmers' => 'Payments to Farmers',
        'performance_analytics' => 'Performance Analytics',
    ];

    public const AGRIBUSINESS_FEATURES = [
        'dashboard' => 'Dashboard Overview',
        'supplier_management' => 'Supplier Management',
        'procurement_contracts' => 'Procurement & Contracts',
        'processing_production' => 'Processing & Production Tracking',
        'inventory_distribution' => 'Inventory & Distribution',
        'sales_financial_reports' => 'Sales & Financial Reports',
    ];

    public const SUPER_ADMIN_FEATURES = [
        'manage_tenants' => 'Manage All Tenants',
        'feature_toggles' => 'Feature Toggles',
        'system_settings' => 'System-Wide Settings',
        'system_analytics' => 'System Analytics',
    ];

    public function getFeaturesForTenantType(string $tenantType): array
    {
        return match ($tenantType) {
            User::TENANT_FARMER => self::FARMER_FEATURES,
            User::TENANT_COOPERATIVE => self::COOPERATIVE_FEATURES,
            User::TENANT_AGRIBUSINESS => self::AGRIBUSINESS_FEATURES,
            User::TENANT_SUPER_ADMIN => self::SUPER_ADMIN_FEATURES,
            default => [],
        };
    }

    public function isEnabled(User $user, string $featureKey): bool
    {
        if ($user->isSuperAdmin()) {
            return true; // Super admin always has access
        }

        $tenantType = $user->tenant_type ?? 'farmer';
        $cacheKey = "feature:{$user->id}:{$featureKey}";

        return Cache::remember($cacheKey, 60, function () use ($user, $featureKey, $tenantType) {
            // Check per-user override first
            $override = \App\Models\FeatureSetting::where('feature_key', $featureKey)
                ->where('tenant_type', $tenantType)
                ->where('user_id', $user->id)
                ->first();

            if ($override !== null) {
                return (bool) $override->enabled;
            }

            // Check tenant-type default (from DB)
            $typeDefault = \App\Models\FeatureSetting::where('feature_key', $featureKey)
                ->where('tenant_type', $tenantType)
                ->whereNull('user_id')
                ->first();

            if ($typeDefault !== null) {
                return (bool) $typeDefault->enabled;
            }

            // Fallback: use TENANT_TYPE_DEFAULTS config
            $defaults = self::TENANT_TYPE_DEFAULTS[$tenantType] ?? [];
            if (array_key_exists($featureKey, $defaults)) {
                return (bool) $defaults[$featureKey];
            }

            return false;
        });
    }

    public function setTenantTypeDefault(string $tenantType, string $featureKey, bool $enabled): void
    {
        \App\Models\FeatureSetting::updateOrCreate(
            ['feature_key' => $featureKey, 'tenant_type' => $tenantType, 'user_id' => null],
            ['enabled' => $enabled]
        );
        $this->clearCacheForFeature($featureKey, $tenantType);
    }

    public function setUserOverride(User $user, string $featureKey, bool $enabled): void
    {
        \App\Models\FeatureSetting::updateOrCreate(
            ['feature_key' => $featureKey, 'tenant_type' => $user->tenant_type, 'user_id' => $user->id],
            ['enabled' => $enabled]
        );
        Cache::forget("feature:{$user->id}:{$featureKey}");
    }

    protected function clearCacheForFeature(string $featureKey, string $tenantType): void
    {
        $users = User::where('tenant_type', $tenantType)->where('tenant_type', '!=', 'super_admin')->get();
        foreach ($users as $user) {
            Cache::forget("feature:{$user->id}:{$featureKey}");
        }
    }
}
