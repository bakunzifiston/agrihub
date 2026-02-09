<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('location')->nullable()->after('organization_id');
            $table->string('country')->nullable()->after('location');
            $table->string('district')->nullable()->after('country');
            // Farmer
            $table->string('farm_name')->nullable()->after('district');
            $table->string('farm_type')->nullable()->after('farm_name'); // Crop, Livestock, Mixed
            // Cooperative
            $table->string('cooperative_name')->nullable()->after('farm_type');
            $table->string('cooperative_focus')->nullable()->after('cooperative_name'); // Crops, Livestock, Mixed
            $table->string('members_range')->nullable()->after('cooperative_focus');
            // Agribusiness
            $table->string('business_name')->nullable()->after('members_range');
            $table->string('business_type')->nullable()->after('business_name'); // Buyer, Processor, Exporter, Retailer
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'location', 'country', 'district',
                'farm_name', 'farm_type',
                'cooperative_name', 'cooperative_focus', 'members_range',
                'business_name', 'business_type',
            ]);
        });
    }
};
