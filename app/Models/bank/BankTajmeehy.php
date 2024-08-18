<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankTajmeehy extends Model
{
    use HasFactory;


  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'BankTajmeehy';
  protected $primaryKey ='TajNo';

  public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
