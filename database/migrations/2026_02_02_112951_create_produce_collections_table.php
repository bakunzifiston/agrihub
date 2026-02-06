<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produce_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->date('collection_date');
            $table->decimal('quantity_collected', 12, 2);
            $table->string('unit');
            $table->string('quality_grade')->nullable();
            $table->string('collection_point')->nullable();
            $table->decimal('price_per_unit', 12, 2)->nullable();
            $table->decimal('total_value', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produce_collections');
    }
};
