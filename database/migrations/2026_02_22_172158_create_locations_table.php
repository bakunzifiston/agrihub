<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // country, district, sector, cell, village
            $table->string('name');
            $table->foreignId('parent_id')->nullable()->constrained('locations')->onDelete('cascade');
            $table->string('code')->nullable(); // optional: ISO code for country, admin code for others
            $table->timestamps();

            $table->index(['type', 'parent_id']);
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
