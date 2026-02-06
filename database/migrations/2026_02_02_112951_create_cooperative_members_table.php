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
        Schema::create('cooperative_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cooperative_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('farmer_id')->constrained('users')->onDelete('cascade');
            $table->string('membership_number')->nullable();
            $table->date('join_date')->nullable();
            $table->decimal('contribution_amount', 12, 2)->default(0);
            $table->string('role')->default('member'); // member/leader
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_members');
    }
};
