<?php

namespace App\Services;

use App\Models\User;

class TenantSidebarService
{
    /**
     * Sidebar menu structure: feature_key => [label, tables => [route => label]]
     */
    public const FARMER_SIDEBAR = [
        'farm_profile' => [
            'label' => 'Farm Profile',
            'tables' => [
                'farmer.farm-profile.index' => 'Farm Profiles',
            ],
        ],
        'crop_livestock_tracking' => [
            'label' => 'Crop & Livestock',
            'tables' => [
                'farmer.registered-crops.index' => 'Registered Crops',
                'farmer.crops.index' => 'Crops',
                'farmer.livestock.index' => 'Livestock',
            ],
        ],
        'production_records' => [
            'label' => 'Production Records',
            'tables' => [
                'farmer.production-records.index' => 'Production Records',
            ],
        ],
        'inventory' => [
            'label' => 'Inventory',
            'tables' => [
                'farmer.inputs.index' => 'Inputs',
                'farmer.input-applications.index' => 'Input Applications',
                'farmer.outputs.index' => 'Outputs',
            ],
        ],
        'sales_income' => [
            'label' => 'Sales & Income',
            'tables' => [
                'farmer.sales.index' => 'Sales',
            ],
        ],
        'reports' => [
            'label' => 'Reports',
            'tables' => [
                'farmer.reports.index' => 'Reports',
            ],
        ],
    ];

    public const COOPERATIVE_SIDEBAR = [
        'member_management' => [
            'label' => 'Members',
            'tables' => [
                'cooperative.members.index' => 'Members',
            ],
        ],
        'collection_aggregation' => [
            'label' => 'Collections',
            'tables' => [
                'cooperative.collections.index' => 'Collections',
            ],
        ],
        'cooperative_inventory' => [
            'label' => 'Inventory',
            'tables' => [
                'cooperative.inventory.index' => 'Inventory',
            ],
        ],
        'payments_to_farmers' => [
            'label' => 'Payments',
            'tables' => [
                'cooperative.payments.index' => 'Payments',
            ],
        ],
        'performance_analytics' => [
            'label' => 'Performance',
            'tables' => [
                'cooperative.performance.index' => 'Dashboards',
            ],
        ],
    ];

    public const AGRIBUSINESS_SIDEBAR = [
        'supplier_management' => [
            'label' => 'Suppliers',
            'tables' => [
                'agribusiness.suppliers.index' => 'Suppliers',
            ],
        ],
        'procurement_contracts' => [
            'label' => 'Procurement',
            'tables' => [
                'agribusiness.contracts.index' => 'Contracts',
            ],
        ],
        'processing_production' => [
            'label' => 'Processing',
            'tables' => [
                'agribusiness.processing.index' => 'Processing',
            ],
        ],
        'inventory_distribution' => [
            'label' => 'Inventory & Distribution',
            'tables' => [
                'agribusiness.inventory.index' => 'Inventory',
                'agribusiness.distributions.index' => 'Distributions',
            ],
        ],
        'sales_financial_reports' => [
            'label' => 'Reports',
            'tables' => [
                'agribusiness.reports.index' => 'Reports',
            ],
        ],
    ];

    public function getSidebarForUser(User $user): array
    {
        $sidebar = match ($user->tenant_type) {
            User::TENANT_FARMER => array_merge(self::FARMER_SIDEBAR, self::getUsersRolesSidebar('farmer')),
            User::TENANT_COOPERATIVE => array_merge(self::COOPERATIVE_SIDEBAR, self::getWarehousesSidebar('cooperative'), self::getUsersRolesSidebar('cooperative')),
            User::TENANT_AGRIBUSINESS => array_merge(self::AGRIBUSINESS_SIDEBAR, self::getWarehousesSidebar('agribusiness'), self::getUsersRolesSidebar('agribusiness')),
            default => [],
        };

        $featureService = app(FeatureService::class);
        $filtered = [];

        foreach ($sidebar as $featureKey => $config) {
            if ($featureKey === 'users_roles' || $featureKey === 'warehouses' || $featureService->isEnabled($user, $featureKey)) {
                $filtered[$featureKey] = $config;
            }
        }

        return $filtered;
    }

    /** Warehouses section - always visible for cooperative and agribusiness */
    private static function getWarehousesSidebar(string $tenantType): array
    {
        $routes = match ($tenantType) {
            'cooperative' => ['cooperative.warehouses.index' => 'Warehouses'],
            'agribusiness' => ['agribusiness.warehouses.index' => 'Warehouses'],
            default => [],
        };

        return empty($routes) ? [] : [
            'warehouses' => [
                'label' => 'Warehouses',
                'tables' => $routes,
            ],
        ];
    }

    private static function getUsersRolesSidebar(string $tenantType): array
    {
        $routes = match ($tenantType) {
            'farmer' => [
                'farmer.users.index' => 'Users',
                'farmer.roles.index' => 'Roles',
                'farmer.permissions.index' => 'Permissions',
            ],
            'cooperative' => [
                'cooperative.users.index' => 'Users',
                'cooperative.roles.index' => 'Roles',
                'cooperative.permissions.index' => 'Permissions',
            ],
            'agribusiness' => [
                'agribusiness.users.index' => 'Users',
                'agribusiness.roles.index' => 'Roles',
                'agribusiness.permissions.index' => 'Permissions',
            ],
            default => [],
        };

        return [
            'users_roles' => [
                'label' => 'Users & Roles',
                'tables' => $routes,
            ],
        ];
    }
}
