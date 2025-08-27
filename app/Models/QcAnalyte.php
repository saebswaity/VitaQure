<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QcAnalyte extends Model
{
    public $table = 'qc_analytes';
    public $timestamps = false;
    protected $fillable = [
        'name',
        'unit',
        'decimals',
        'position',
        'created_at',
    ];

    public function controlMaterials()
    {
        return $this->belongsToMany(QcControlMaterial::class, 'qc_control_analyte_assignments', 'analyte_id', 'control_id');
    }
}

