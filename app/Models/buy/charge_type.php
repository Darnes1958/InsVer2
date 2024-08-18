<?php

namespace App\Models\buy;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class charge_type extends Model
{
  use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'charge_type';
  protected $primaryKey ='type_no';

  public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
