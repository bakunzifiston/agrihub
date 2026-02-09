<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmer_registered_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('product_type')->nullable(); // seed, fertilizer, pesticide, herbicide, other
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmer_registered_products');
    }
};
