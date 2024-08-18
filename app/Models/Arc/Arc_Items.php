<?php

namespace App\Models\Arc;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Arc_Items extends Model
{
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'ARc_Items';
  protected $primaryKey =false;
  public $incrementing = false;

  public $timestamps = false;
  public function __construct(array $attributes = [])
  {
    parent::__construct($attributes);

    if (Auth::check()) {

      $this->connection = Auth::user()->company;

    }
  }
}
