<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (DB::table('processing_records')->get() as $row) {
            DB::table('processing_raw_materials')->insert([
                'processing_record_id' => $row->id,
                'raw_material' => $row->raw_material,
                'quantity_input' => $row->quantity_input,
                'input_unit' => $row->input_unit,
                'supplier_id' => null,
                'created_at' => $row->created_at,
                'updated_at' => $row->updated_at,
            ]);
        }

        Schema::table('processing_records', function (Blueprint $table) {
            $table->dropColumn(['raw_material', 'quantity_input', 'input_unit']);
        });
    }

    public function down(): void
    {
        Schema::table('processing_records', function (Blueprint $table) {
            $table->string('raw_material')->nullable()->after('contract_id');
            $table->decimal('quantity_input', 12, 2)->nullable()->after('raw_material');
            $table->string('input_unit')->nullable()->after('quantity_input');
        });

        foreach (DB::table('processing_raw_materials')->orderBy('id')->get() as $rm) {
            DB::table('processing_records')->where('id', $rm->processing_record_id)->update([
                'raw_material' => $rm->raw_material,
                'quantity_input' => $rm->quantity_input,
                'input_unit' => $rm->input_unit,
            ]);
        }

        Schema::table('processing_records', function (Blueprint $table) {
            $table->string('raw_material')->nullable(false)->change();
            $table->decimal('quantity_input', 12, 2)->nullable(false)->change();
            $table->string('input_unit')->nullable(false)->change();
        });
    }
};
