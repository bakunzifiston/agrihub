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
        Schema::create('agribusiness_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agribusiness_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->string('category')->nullable();
            $table->decimal('quantity_in_stock', 12, 2)->default(0);
            $table->string('unit');
            $table->string('storage_location')->nullable();
            $table->string('batch_number')->nullable();
            $table->date('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agribusiness_inventory');
    }
};
