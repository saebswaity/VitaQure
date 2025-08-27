<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('DROP TABLE IF EXISTS qc_entries');
        DB::statement('DROP TABLE IF EXISTS qc_reference_values');
        DB::statement('DROP TABLE IF EXISTS qc_control_material_analyte');
        DB::statement('DROP TABLE IF EXISTS qc_control_materials');
        DB::statement('DROP TABLE IF EXISTS qc_analytes');
        DB::statement('DROP TABLE IF EXISTS qc_statistics');
        DB::statement('DROP TABLE IF EXISTS qc_control_analyte_assignments');
    }

    public function down(): void
    {
        // intentionally left blank - dropping tables is irreversible here
    }
};

