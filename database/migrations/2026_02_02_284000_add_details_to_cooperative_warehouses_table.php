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
        Schema::table('cooperative_warehouses', function (Blueprint $table) {
            $table->string('city')->nullable()->after('location');
            $table->string('district')->nullable()->after('city');
            $table->string('sector')->nullable()->after('district');
            $table->string('country')->nullable()->after('sector');
            $table->decimal('gps_latitude', 10, 7)->nullable()->after('country');
            $table->decimal('gps_longitude', 10, 7)->nullable()->after('gps_latitude');
            $table->string('phone')->nullable()->after('gps_longitude');
            $table->string('email')->nullable()->after('phone');
            $table->foreignId('manager_member_id')->nullable()->after('email')->constrained('cooperative_members')->onDelete('set null');
            $table->string('manager_name')->nullable()->after('manager_member_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cooperative_warehouses', function (Blueprint $table) {
            $table->dropForeign(['manager_member_id']);
            $table->dropColumn([
                'city', 'district', 'sector', 'country',
                'gps_latitude', 'gps_longitude', 'phone', 'email',
                'manager_member_id', 'manager_name',
            ]);
        });
    }
};
