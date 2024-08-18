<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Lines extends Model
{
  use HasFactory;

  protected $guarded = [];
  protected $table = 'Lines';
  protected $primaryKey = null;
  public $incrementing = false;
  public $timestamps = false;

}
