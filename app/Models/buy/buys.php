<?php

namespace App\Models\buy;

use App\Models\jeha\jeha;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class buys extends Model
{

    protected $table = 'buys';
    protected $primaryKey ='order_no';
    public $incrementing = false;
    public $timestamps = false;

    public function Jehatable(): BelongsTo
    {
        return $this->belongsTo(jeha::class,'jeha','jeha_no');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
