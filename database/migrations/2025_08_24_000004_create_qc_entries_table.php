<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qc_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->time('time');
            $table->string('operator', 50);
            $table->unsignedInteger('analyte_id');
            $table->unsignedInteger('control_id');
            $table->decimal('measured_value', 10, 4);
            $table->text('comment')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_entries');
    }
};

