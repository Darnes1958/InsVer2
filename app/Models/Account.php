<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $connection='account';
    protected $table='OurCompany';

    public function transable()
    {
        return $this->morphMany(CusTrans::class,'transable');
    }
}
