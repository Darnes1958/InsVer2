<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{

  protected $table = 'Customers';


  public $timestamps = false;
  public function CusTran() : HasMany
  {
     return $this->hasMany(CusTrans::class);
  }
}
