<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('crops', function (Blueprint $table) {
            $table->dropForeign(['farm_profile_plot_id']);
            $table->dropColumn('farm_profile_plot_id');
        });
    }

    public function down(): void
    {
        Schema::table('crops', function (Blueprint $table) {
            $table->foreignId('farm_profile_plot_id')->nullable()->after('farmer_id')->constrained('farm_profile_plots')->nullOnDelete();
        });
    }
};
