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
        Schema::create('cooperative_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->string('product_name');
            $table->string('category')->nullable();
            $table->decimal('quantity_in_stock', 12, 2);
            $table->string('unit');
            $table->string('storage_location')->nullable();
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_inventory');
    }
};
