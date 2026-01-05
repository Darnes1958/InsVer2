<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Customer extends Model
{

  protected $table = 'Customers';


  public $timestamps = false;

  public function transable()
  {
      return $this->morphMany(CusTrans::class, 'transable');
  }

}
