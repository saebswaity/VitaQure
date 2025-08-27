<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcReferenceValue extends Model
{
    public $table = 'qc_reference_values';
    public $timestamps = true;
    protected $fillable = [
        'analyte_id','control_id','mean','sd','plus_1sd','plus_2sd','plus_3sd','minus_1sd','minus_2sd','minus_3sd'
    ];
}

