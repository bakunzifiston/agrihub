<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooperative_orders', function (Blueprint $table) {
            $table->foreignId('inventory_id')->nullable()->after('client_id')->constrained('cooperative_inventory')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cooperative_orders', function (Blueprint $table) {
            $table->dropForeign(['inventory_id']);
        });
    }
};
