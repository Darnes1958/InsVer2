<?php

namespace App\Models\aksat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class TransArc extends Model
{
    use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'TransArc';
  protected $primaryKey =false;
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
