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
        Schema::create('farm_inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('input_name');
            $table->string('input_category'); // seed, fertilizer, feed, medicine
            $table->decimal('quantity', 12, 2);
            $table->string('unit');
            $table->date('purchase_date')->nullable();
            $table->string('supplier_name')->nullable();
            $table->decimal('cost_per_unit', 12, 2)->nullable();
            $table->decimal('total_cost', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farm_inputs');
    }
};
