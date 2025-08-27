<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qc_control_analyte_assignments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('analyte_id');
            $table->unsignedInteger('control_id');
            $table->unique(['analyte_id','control_id'], 'unique_assignment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_control_analyte_assignments');
    }
};

