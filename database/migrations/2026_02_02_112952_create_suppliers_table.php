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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agribusiness_id')->constrained('users')->onDelete('cascade');
            $table->string('supplier_type'); // farmer/cooperative
            $table->string('supplier_name');
            $table->string('contact_person')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('location')->nullable();
            $table->string('contract_status')->nullable();
            $table->decimal('rating', 3, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
