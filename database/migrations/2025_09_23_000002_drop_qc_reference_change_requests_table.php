<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropQcReferenceChangeRequestsTable extends Migration {
    public function up()
    {
        if (Schema::hasTable('qc_reference_change_requests')) {
            Schema::drop('qc_reference_change_requests');
        }
    }

    public function down()
    {
        // no-op
    }
}


