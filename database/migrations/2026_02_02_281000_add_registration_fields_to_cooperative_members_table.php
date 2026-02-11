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
        if (Schema::hasColumn('cooperative_members', 'full_name')) {
            return;
        }

        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('farmer_id');
            $table->string('national_id')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('national_id');
            $table->string('email')->nullable()->after('phone');
            $table->text('address')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('cooperative_members', 'full_name')) {
            return;
        }
        Schema::table('cooperative_members', function (Blueprint $table) {
            $table->dropColumn(['full_name', 'national_id', 'phone', 'email', 'address']);
        });
    }
};
