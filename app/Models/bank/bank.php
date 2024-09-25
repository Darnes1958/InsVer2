<?php

namespace App\Models\bank;

use App\Models\aksat\main;
use App\Models\NewModel\Nmain;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class bank extends Model
{


    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'bank';
    protected $primaryKey ='bank_no';
    public $incrementing = false;
    public $timestamps = false;

    public function Nmain()
    {
        return $this->hasMany(Nmain::class, 'bank', 'bank_no');
    }
    public function main()
    {
        return $this->hasMany(main::class, 'bank', 'bank_no');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }


}

