<?php

namespace App\Models\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RepMakzoon extends Model
{
  use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'rep_makzoon';
  protected $primaryKey =null;
  public $incrementing = false;
  public $timestamps = false;
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
