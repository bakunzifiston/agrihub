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
        if (Schema::hasTable('cooperative_profiles')) {
            return;
        }

        Schema::create('cooperative_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('district')->nullable();
            $table->string('sector')->nullable();
            $table->string('country')->nullable();
            $table->text('description')->nullable();
            $table->string('focus')->nullable(); // e.g. crop, livestock, dairy
            $table->string('status')->default('active');
            $table->date('registration_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_profiles');
    }
};
