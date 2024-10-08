<?php

namespace App\Models\aksat;

use App\Models\bank\bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class main extends Model
{

  protected $connection = 'other';

  protected $table = 'main';
  protected $primaryKey ='no';
  public $incrementing = false;
  public $timestamps = false;
    public function bank(): BelongsTo
    {
        return $this->belongsTo(bank::class, 'bank', 'bank_no');

    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
