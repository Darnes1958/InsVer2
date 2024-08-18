<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class bank extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'bank';
    protected $primaryKey ='bank_no';
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

