<?php

namespace App\Models\excel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class KaemaModel extends Model
{
use HasFactory;

  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'Kaema';


  public $timestamps = false;
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection=Auth::user()->company;

    }
  }
}
