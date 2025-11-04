<?php

namespace App\Models\OverTar;


use App\Enums\Mosahah;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class wrong_kst extends Model
{

  protected $connection = 'other';

  protected $table = 'wrong_kst';
  protected $primaryKey ='wrec_no';

  public $timestamps = false;
  protected $casts=['morahel'=>Mosahah::class,];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
