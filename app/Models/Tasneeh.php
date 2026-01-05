<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tasneeh extends Model
{
    protected $connection='tasneeh';
    protected $table='OurCompany';

    public function transable()
    {
        return $this->morphMany(CusTrans::class,'transable');
    }
}
