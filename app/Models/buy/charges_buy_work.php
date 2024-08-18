<?php

namespace App\Models\buy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class charges_buy_work extends Model
{
    use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'charges_buy_work';
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
