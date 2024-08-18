<?php

namespace App\Models\aksat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class main_kst_count extends Model
{
  use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'main_kst_count';
  protected $primaryKey =null;

  public $timestamps = false;
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
