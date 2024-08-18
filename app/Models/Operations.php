<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Operations extends Model
{
  use HasFactory;

  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'Operations';
  public $timestamps = true;



  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
