<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farm_input_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farm_profile_id')->constrained('farm_profiles')->onDelete('cascade');
            $table->foreignId('farm_profile_plot_id')->nullable()->constrained('farm_profile_plots')->nullOnDelete();
            $table->foreignId('crop_id')->nullable()->constrained('crops')->nullOnDelete();
            $table->string('input_name');
            $table->string('input_type'); // fertilizer, pesticide, herbicide
            $table->string('batch_number')->nullable();
            $table->string('supplier')->nullable();
            $table->date('application_date');
            $table->decimal('quantity_used', 12, 2);
            $table->string('unit', 50); // Kg, L, Bag, Bottle
            $table->string('applied_by')->nullable();
            $table->unsignedSmallInteger('phi_days')->nullable(); // Pre-harvest interval
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farm_input_applications');
    }
};
