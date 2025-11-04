<?php

namespace App\Models\buy;

use App\Models\jeha\jeha;
use App\Models\stores\stores;
use App\Models\stores\stores_names;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    public function Storename(): BelongsTo
    {
        return $this->belongsTo(stores_names::class,'place_no','st_no');
    }
    public function Buy_tran(): HasMany {
        return $this->hasMany(buy_tran::class,'order_no','order_no');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
