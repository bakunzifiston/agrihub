<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperative_livestock', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->string('livestock_type');
            $table->string('breed')->nullable();
            $table->integer('quantity')->default(0);
            $table->string('purpose')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->string('health_status')->nullable();
            $table->string('vaccination_status')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperative_livestock');
    }
};
