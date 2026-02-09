<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_input_applications', function (Blueprint $table) {
            $table->foreignId('farmer_registered_product_id')->nullable()->after('crop_id')->constrained('farmer_registered_products')->nullOnDelete();
            $table->foreignId('farmer_supplier_id')->nullable()->after('supplier')->constrained('farmer_suppliers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('farm_input_applications', function (Blueprint $table) {
            $table->dropForeign(['farmer_registered_product_id']);
            $table->dropForeign(['farmer_supplier_id']);
        });
    }
};
