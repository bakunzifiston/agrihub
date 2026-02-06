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
        Schema::create('processing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agribusiness_id')->constrained('users')->onDelete('cascade');
            $table->string('raw_material');
            $table->decimal('quantity_input', 12, 2);
            $table->string('input_unit');
            $table->decimal('quantity_output', 12, 2);
            $table->string('output_unit');
            $table->date('processing_date');
            $table->decimal('processing_cost', 12, 2)->nullable();
            $table->decimal('wastage_quantity', 12, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('processing_records');
    }
};
