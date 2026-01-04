<?php

namespace App\Models;

use App\Enums\CusValType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CusTrans extends Model
{

  protected $table = 'CusTrans';
  public function Customer():BelongsTo
  {
    return $this->belongsTo(Customer::class);
  }
  protected $casts=['ValType'=>CusValType::class];

}
