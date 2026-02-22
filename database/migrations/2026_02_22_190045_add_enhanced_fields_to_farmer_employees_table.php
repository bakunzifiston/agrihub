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
        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->foreignId('farm_profile_id')->nullable()->after('farmer_id')->constrained('farm_profiles')->nullOnDelete();

            $table->renameColumn('name', 'first_name');
        });

        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->string('first_name', 100)->change();
            $table->string('last_name', 100)->after('first_name');
            $table->string('national_id', 50)->nullable()->after('last_name');

            $table->renameColumn('phone', 'phone_number');
        });

        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->string('phone_number', 20)->nullable()->change();
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('email');
            $table->date('date_of_birth')->nullable()->after('gender');
            $table->string('photo')->nullable()->after('date_of_birth');

            $table->renameColumn('role', 'job_title');
        });

        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->string('job_title', 100)->nullable()->change();
            $table->string('department', 100)->nullable()->after('job_title');
            $table->date('end_date')->nullable()->after('hire_date');
            $table->decimal('salary', 12, 2)->nullable()->after('end_date');
            $table->enum('salary_period', ['hourly', 'daily', 'weekly', 'monthly', 'yearly'])->default('monthly')->after('salary');
            $table->string('country', 100)->nullable()->after('salary_period');
            $table->string('district', 100)->nullable()->after('country');
            $table->string('sector', 100)->nullable()->after('district');
            $table->string('cell', 100)->nullable()->after('sector');
            $table->string('village', 100)->nullable()->after('cell');
            $table->string('emergency_contact_name')->nullable()->after('address');
            $table->string('emergency_contact_phone', 20)->nullable()->after('emergency_contact_name');
            $table->text('skills')->nullable()->after('emergency_contact_phone');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active')->after('notes');

            $table->dropColumn('address');
            $table->dropColumn('id_number');

            $table->index(['farmer_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->dropIndex(['farmer_id', 'status']);
            $table->dropForeign(['farm_profile_id']);
            $table->dropColumn([
                'farm_profile_id',
                'last_name',
                'national_id',
                'gender',
                'date_of_birth',
                'photo',
                'department',
                'end_date',
                'salary',
                'salary_period',
                'country',
                'district',
                'sector',
                'cell',
                'village',
                'emergency_contact_name',
                'emergency_contact_phone',
                'skills',
                'status',
            ]);
            $table->string('address')->nullable();
            $table->string('id_number')->nullable();
        });

        Schema::table('farmer_employees', function (Blueprint $table) {
            $table->renameColumn('first_name', 'name');
            $table->renameColumn('phone_number', 'phone');
            $table->renameColumn('job_title', 'role');
        });
    }
};
