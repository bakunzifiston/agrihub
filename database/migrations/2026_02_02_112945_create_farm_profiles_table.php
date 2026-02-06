<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('farm_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('tenant_id')->nullable();
            $table->string('full_name');
            $table->string('national_id')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('farm_name');
            $table->string('farm_type'); // crop / livestock / mixed
            $table->decimal('total_land_size', 10, 2)->nullable();
            $table->string('land_unit')->nullable(); // hectares/acres
            $table->string('location_country')->nullable();
            $table->string('location_district')->nullable();
            $table->string('location_sector')->nullable();
            $table->string('location_cell')->nullable();
            $table->string('location_village')->nullable();
            $table->decimal('gps_latitude', 10, 7)->nullable();
            $table->decimal('gps_longitude', 10, 7)->nullable();
            $table->date('registration_date')->nullable();
            $table->string('status')->default('active'); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_profiles');
    }
};
