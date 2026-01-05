<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OurCompany extends Model
{
   protected $connection='erp';
   protected $table='OurCompany';

   public function transable()
   {
       return $this->morphMany(CusTrans::class,'transable');
   }
}
