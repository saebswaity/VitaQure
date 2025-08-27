<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qc_reference_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('analyte_id');
            $table->unsignedInteger('control_id');
            $table->decimal('mean', 10, 4);
            $table->decimal('sd', 10, 4);
            $table->decimal('plus_1sd', 10, 4)->nullable();
            $table->decimal('plus_2sd', 10, 4)->nullable();
            $table->decimal('plus_3sd', 10, 4)->nullable();
            $table->decimal('minus_1sd', 10, 4)->nullable();
            $table->decimal('minus_2sd', 10, 4)->nullable();
            $table->decimal('minus_3sd', 10, 4)->nullable();
            $table->timestamps();
            $table->unique(['analyte_id','control_id'], 'unique_analyte_control');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_reference_values');
    }
};

