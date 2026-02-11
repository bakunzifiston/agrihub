<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('processing_raw_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processing_record_id')->constrained('processing_records')->onDelete('cascade');
            $table->string('raw_material');
            $table->decimal('quantity_input', 12, 2);
            $table->string('input_unit');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processing_raw_materials');
    }
};
