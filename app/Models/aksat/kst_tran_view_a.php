<?php

namespace App\Models\aksat;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class kst_tran_view_a extends Model
{
    protected $connection = 'other';

    protected $table = 'kst_tran_view_a';

    public $incrementing = false;
    public $timestamps = false;
    protected $primaryKey =null;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
