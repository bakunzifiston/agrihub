<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE cooperative_members MODIFY farmer_id BIGINT UNSIGNED NULL');
        }
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE cooperative_members MODIFY farmer_id BIGINT UNSIGNED NOT NULL');
        }
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
