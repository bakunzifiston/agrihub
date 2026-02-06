<?php

namespace Database\Seeders;

use App\Models\FeatureSetting;
use App\Services\FeatureService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class FeatureSettingSeeder extends Seeder
{
    /**
     * Seed default feature settings per tenant type.
     * These define which features are enabled by default for each tenant type.
     */
    public function run(): void
    {
        foreach (FeatureService::TENANT_TYPE_DEFAULTS as $tenantType => $features) {
            foreach ($features as $featureKey => $enabled) {
                FeatureSetting::updateOrCreate(
                    [
                        'feature_key' => $featureKey,
                        'tenant_type' => $tenantType,
                        'user_id' => null,
                    ],
                    ['enabled' => $enabled]
                );
            }
        }

        // Clear feature cache so tenants see updated features immediately
        Cache::flush();
    }
}
