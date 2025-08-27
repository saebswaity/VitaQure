<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcEntry extends Model
{
    public $table = 'qc_entries';
    public $timestamps = false;
    protected $fillable = [
        'date','time','operator','analyte_id','control_id','measured_value','comment','created_at'
    ];
}

