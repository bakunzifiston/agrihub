<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->createFarmerPermissions();
        $this->createCooperativePermissions();
        $this->createAgribusinessPermissions();
    }

    private function createFarmerPermissions(): void
    {
        $permissions = [
            'manage-farm-profile',
            'manage-crops',
            'manage-livestock',
            'manage-production-records',
            'manage-inputs',
            'manage-outputs',
            'manage-sales',
            'view-reports',
            'manage-users',
            'manage-roles',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }

    private function createCooperativePermissions(): void
    {
        $permissions = [
            'manage-members',
            'manage-collections',
            'manage-inventory',
            'manage-payments',
            'view-performance',
            'manage-users',
            'manage-roles',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }

    private function createAgribusinessPermissions(): void
    {
        $permissions = [
            'manage-suppliers',
            'manage-contracts',
            'manage-processing',
            'manage-inventory',
            'manage-distributions',
            'view-reports',
            'manage-users',
            'manage-roles',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}
