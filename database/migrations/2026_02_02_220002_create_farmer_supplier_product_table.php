<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_supplier_product', function (Blueprint $table) {
            $table->foreignId('farmer_supplier_id')->constrained('farmer_suppliers')->onDelete('cascade');
            $table->foreignId('farmer_registered_product_id')->constrained('farmer_registered_products')->onDelete('cascade');
            $table->primary(['farmer_supplier_id', 'farmer_registered_product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_supplier_product');
    }
};
