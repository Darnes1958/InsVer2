<?php

namespace App\Models\aksat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class main_trans_view2 extends Model
{
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'main_trans_view2';
    protected $primaryKey =null;

    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
