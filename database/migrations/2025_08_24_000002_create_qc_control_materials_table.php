<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qc_control_materials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('lot_number', 50)->unique();
            $table->enum('level', ['Low', 'Normal', 'High']);
            $table->date('expiry_date');
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('qc_control_material_analyte', function (Blueprint $table) {
            $table->unsignedInteger('control_material_id');
            $table->unsignedInteger('analyte_id');
            $table->primary(['control_material_id','analyte_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_control_material_analyte');
        Schema::dropIfExists('qc_control_materials');
    }
};

