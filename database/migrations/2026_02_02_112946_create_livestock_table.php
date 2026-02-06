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
        Schema::create('livestock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('livestock_type'); // cattle, poultry, goat, etc.
            $table->string('breed')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('purpose')->nullable(); // milk/meat/eggs/breeding
            $table->date('acquisition_date')->nullable();
            $table->string('health_status')->nullable();
            $table->string('vaccination_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('livestock');
    }
};
