<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('processing_records', function (Blueprint $table) {
            $table->foreignId('contract_id')->nullable()->after('agribusiness_id')->constrained('contracts')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('processing_records', function (Blueprint $table) {
            $table->dropForeign(['contract_id']);
        });
    }
};
