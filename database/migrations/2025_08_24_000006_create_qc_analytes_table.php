<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('qc_analytes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 15);
            $table->string('unit', 6);
            $table->tinyInteger('decimals')->default(0);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qc_analytes');
    }
};

