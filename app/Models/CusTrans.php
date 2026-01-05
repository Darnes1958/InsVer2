<?php

namespace App\Models;

use App\Enums\CusValType;
use App\Enums\SysType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CusTrans extends Model
{

  protected $table = 'CusTrans';

  public function transable(): MorphTo{
      return $this->morphTo();
  }

  protected $casts=['ValType'=>CusValType::class,'transable_type'=>SysType::class];

}
