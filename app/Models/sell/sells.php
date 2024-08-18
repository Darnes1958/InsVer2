<?php

namespace App\Models\sell;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class sells extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'sells';
    protected $primaryKey ='order_no';
    public $incrementing = false;
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
    public function orderselljeha()
    {
        return $this->belongsTo(jeha::class,'jeha','jeha_no');
    }
}
