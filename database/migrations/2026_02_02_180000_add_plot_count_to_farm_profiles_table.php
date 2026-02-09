<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->unsignedInteger('plot_count')->nullable()->after('land_unit');
        });
    }

    public function down(): void
    {
        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->dropColumn('plot_count');
        });
    }
};
