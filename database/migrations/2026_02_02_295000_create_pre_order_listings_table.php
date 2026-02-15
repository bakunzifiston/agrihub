<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pre_order_listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('crop_id')->nullable()->constrained('crops')->onDelete('cascade');
            $table->foreignId('farm_output_id')->nullable()->constrained('farm_outputs')->onDelete('cascade');
            $table->string('title');
            $table->decimal('quantity_available', 12, 2);
            $table->string('unit', 50);
            $table->decimal('price_per_unit', 12, 2)->nullable();
            $table->date('expected_harvest_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('woocommerce_product_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pre_order_listings');
    }
};
