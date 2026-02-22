<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('sector')->nullable()->after('district');
            $table->string('cell')->nullable()->after('sector');
            $table->string('village')->nullable()->after('cell');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['sector', 'cell', 'village']);
        });
    }
};
