<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class rep_banks extends Model
{
    use HasFactory;
    protected $connection = 'other';

    protected $table = 'rep_banks';
    protected $primaryKey ='bank';
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
