<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('crop_farm_profile_plot', function (Blueprint $table) {
            $table->foreignId('crop_id')->constrained('crops')->onDelete('cascade');
            $table->foreignId('farm_profile_plot_id')->constrained('farm_profile_plots')->onDelete('cascade');
            $table->primary(['crop_id', 'farm_profile_plot_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crop_farm_profile_plot');
    }
};
