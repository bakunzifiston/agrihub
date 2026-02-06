<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooperative_inventory', function (Blueprint $table) {
            $table->foreignId('warehouse_id')->nullable()->after('cooperative_id')->constrained('cooperative_warehouses')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cooperative_inventory', function (Blueprint $table) {
            $table->dropForeign(['warehouse_id']);
        });
    }
};
