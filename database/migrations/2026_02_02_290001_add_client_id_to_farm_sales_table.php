<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_sales', function (Blueprint $table) {
            $table->foreignId('client_id')->nullable()->after('farm_output_id')->constrained('farmer_clients')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('farm_sales', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
        });
    }
};
