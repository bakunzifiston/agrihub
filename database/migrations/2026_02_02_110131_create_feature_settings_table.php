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
        Schema::create('feature_settings', function (Blueprint $table) {
            $table->id();
            $table->string('feature_key');
            $table->string('tenant_type'); // farmer, cooperative, agribusiness
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('enabled')->default(true);
            $table->timestamps();
            $table->index(['feature_key', 'tenant_type', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_settings');
    }
};
