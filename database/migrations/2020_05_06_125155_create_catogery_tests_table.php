<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCatogeryTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('catogery_tests', function (Blueprint $table) {
            $table->id();
            $table->string('catogery');
            $table->string('description');
            $table->timestamps();
        });
        Schema::table('tests', function (Blueprint $table) {
            $table->foreignId('catogery_id')->constrained('catogery_tests','id')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');

        Schema::dropIfExists('catogery_tests');
    }
}
