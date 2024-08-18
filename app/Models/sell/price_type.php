<?php

namespace App\Models\sell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class price_type extends Model
{
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'price_type';
  protected $primaryKey ='type_no';
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
