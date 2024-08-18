<?php

namespace App\Models\buy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class buys_view extends Model
{
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'buys_view';
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
