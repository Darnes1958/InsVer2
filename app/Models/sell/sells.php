<?php

namespace App\Models\sell;

use App\Enums\SellType;
use App\Models\buy\buy_tran;
use App\Models\jeha\jeha;
use App\Models\stores\halls_names;
use App\Models\stores\stores_names;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class sells extends Model
{
    protected $casts=['sell_type'=>SellType::class];
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
    public function Jehatable(): BelongsTo
    {
        return $this->belongsTo(jeha::class,'jeha','jeha_no');
    }

    public function Storename(): BelongsTo
    {
        return $this->belongsTo(stores_names::class,'place_no','st_no');
    }
    public function Hallname(): BelongsTo
    {
        return $this->belongsTo(halls_names::class,'place_no','hall_no');
    }
    public function Sell_tran(): HasMany {
        return $this->hasMany(sell_tran::class,'order_no','order_no');
    }
}
