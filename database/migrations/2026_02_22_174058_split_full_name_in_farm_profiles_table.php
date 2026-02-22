<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('tenant_id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        // Migrate existing full_name data
        DB::table('farm_profiles')->orderBy('id')->each(function ($profile) {
            $parts = explode(' ', $profile->full_name ?? '', 2);
            DB::table('farm_profiles')->where('id', $profile->id)->update([
                'first_name' => $parts[0] ?? '',
                'last_name' => $parts[1] ?? '',
            ]);
        });

        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->dropColumn('full_name');
        });
    }

    public function down(): void
    {
        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('tenant_id');
        });

        DB::table('farm_profiles')->orderBy('id')->each(function ($profile) {
            $fullName = trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? ''));
            DB::table('farm_profiles')->where('id', $profile->id)->update([
                'full_name' => $fullName,
            ]);
        });

        Schema::table('farm_profiles', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });
    }
};
