<?php

namespace App\Models\aksat;

use App\Enums\KsmType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class kst_trans extends Model
{

  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'kst_trans';
  protected $primaryKey ='wrec_no';

  public $timestamps = false;

    protected $casts =[
        'ksm_type' => KsmType::class,
        ];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }


}
