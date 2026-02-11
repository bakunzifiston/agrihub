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
        Schema::table('produce_collections', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE produce_collections MODIFY farmer_id BIGINT UNSIGNED NULL');
        }
        Schema::table('produce_collections', function (Blueprint $table) {
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreignId('member_id')->nullable()->after('farmer_id')->constrained('cooperative_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produce_collections', function (Blueprint $table) {
            $table->dropForeign(['member_id']);
        });
        Schema::table('produce_collections', function (Blueprint $table) {
            $table->dropForeign(['farmer_id']);
        });
        $driver = Schema::getConnection()->getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE produce_collections MODIFY farmer_id BIGINT UNSIGNED NOT NULL');
        }
        Schema::table('produce_collections', function (Blueprint $table) {
            $table->foreign('farmer_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
