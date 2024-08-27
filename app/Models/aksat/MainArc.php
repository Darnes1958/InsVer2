<?php

namespace App\Models\aksat;

use App\Models\bank\bank;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class MainArc extends Model
{

  protected $connection = 'other';

  protected $table = 'MainArc';

  public $timestamps = false;
    public function bank()
    {
        return $this->belongsTo(bank::class, 'bank', 'bank_no');

    }
    public function place()
    {
        return $this->belongsTo(place::class, 'place', 'place_no');

    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
