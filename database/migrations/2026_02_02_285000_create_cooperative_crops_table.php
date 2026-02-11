<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_crops', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->string('crop_name');
            $table->string('crop_type')->nullable();
            $table->string('season')->nullable();
            $table->date('planting_date')->nullable();
            $table->date('expected_harvest_date')->nullable();
            $table->decimal('land_area_used', 10, 2)->nullable();
            $table->string('area_unit')->nullable();
            $table->decimal('expected_yield', 10, 2)->nullable();
            $table->string('yield_unit')->nullable();
            $table->string('crop_status')->default('planted');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_crops');
    }
};
