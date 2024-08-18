<?php

namespace App\Models\bank;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class rep_bank extends Model
{
    use HasFactory;

    protected $connection = 'other';

    protected $table = 'main_view';
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
