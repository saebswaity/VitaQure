<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Drop any tables that start with qc_
        $tables = DB::select("SELECT table_name FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name LIKE 'qc\_%'");

        if (!empty($tables)) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            foreach ($tables as $t) {
                $name = $t->table_name ?? $t->TABLE_NAME ?? null;
                if ($name) {
                    DB::statement('DROP TABLE IF EXISTS `'.$name.'`');
                }
            }
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        // no-op
    }
};

