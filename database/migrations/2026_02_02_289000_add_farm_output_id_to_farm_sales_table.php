<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_sales', function (Blueprint $table) {
            $table->foreignId('farm_output_id')->nullable()->after('farmer_id')->constrained('farm_outputs')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('farm_sales', function (Blueprint $table) {
            $table->dropForeign(['farm_output_id']);
        });
    }
};
