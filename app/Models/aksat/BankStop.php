<?php

namespace App\Models\aksat;

use App\Models\bank\BankTajmeehy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankStop extends Model
{

    public function main()
    {
        return $this->belongsTo(main::class,'no','no');
    }
    public function BankTajmeehy()
    {
        return $this->belongsTo(BankTajmeehy::class,'taj_id','TajNo');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
