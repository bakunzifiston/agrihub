<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_trainings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('farmer_employees')->cascadeOnDelete();
            $table->string('training_name');
            $table->string('training_type')->nullable();
            $table->string('provider')->nullable();
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration_hours')->nullable();
            $table->string('status')->default('scheduled');
            $table->string('certificate_number')->nullable();
            $table->date('certificate_expiry')->nullable();
            $table->string('certificate_file')->nullable();
            $table->decimal('cost', 12, 2)->nullable();
            $table->string('location')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['farmer_id', 'employee_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_trainings');
    }
};
