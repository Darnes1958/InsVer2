<?php

namespace App\Models\OverTar;

use App\Models\bank\bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class stop_kst extends Model
{
  use HasFactory;
  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'stop_kst';
  protected $primaryKey ='rec_no';

  public $timestamps = false;

  public function  bankname() :BelongsTo {
      return $this->belongsTo(Bank::class, 'bank', 'bank_no');
  }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
