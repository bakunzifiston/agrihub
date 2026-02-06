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
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agribusiness_id')->constrained('users')->onDelete('cascade');
            $table->string('customer_name');
            $table->string('product_name');
            $table->decimal('quantity_dispatched', 12, 2);
            $table->string('unit');
            $table->date('dispatch_date');
            $table->string('delivery_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
