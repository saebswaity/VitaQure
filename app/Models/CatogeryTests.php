<?php

namespace App\Models;

use App\Models\Test;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatogeryTests extends Model
{
    //use HasFactory;

    protected $fillable=['catogery','description'];

    public function tests()
    {
        return $this->hasMany(Test::class, 'catogery_id');
    }
}
