<?php

namespace App\Models\buy;

use App\Models\stores\items;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class buy_tran extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'buy_tran';
    protected $primaryKey ='rec_no';

    public $timestamps = false;
    public function getSubtotAttribute(){

        return $this->price*$this->quant;
    }
    public function buys(): BelongsTo {
        return $this->belongsTo(buys::class, 'order_no', 'order_no');
    }
    public function Item(): BelongsTo
    {
        return $this->belongsTo(items::class, 'item_no', 'item_no');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
