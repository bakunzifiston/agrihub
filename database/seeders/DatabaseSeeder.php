<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create super admin user (pre-approved)
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'tenant_type' => User::TENANT_SUPER_ADMIN,
                'is_approved' => true,
            ]
        );

        // Seed default feature settings per tenant type
        $this->call(FeatureSettingSeeder::class);
    }
}
