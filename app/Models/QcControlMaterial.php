<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcControlMaterial extends Model
{
    public $table = 'qc_control_materials';
    public $timestamps = false;
    protected $fillable = [
        'name', 'lot_number', 'level', 'expiry_date', 'created_at'
    ];

    public function analytes()
    {
        return $this->belongsToMany(QcAnalyte::class, 'qc_control_analyte_assignments', 'control_id', 'analyte_id');
    }
}

