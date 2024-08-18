<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CusTrans extends Model
{
  use HasFactory;

  protected $guarded = [];
  protected $table = 'CusTrans';

  public $timestamps = false;
}
