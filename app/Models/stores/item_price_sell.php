<?php

namespace App\Models\stores;

use App\Enums\PriceType;
use App\Models\sell\price_type;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class item_price_sell extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'item_price_sell';
    protected $primaryKey ='rec_no';
    public $timestamps = false;

    protected $casts=[
      'price_type'=>PriceType::class,
    ];

    public function items()
    {

        return $this->belongsTo(items::class,'item_no','item_no');

    }
    public function price_type()
    {

        return $this->belongsTo(price_type::class,'type_no','price_type');

    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

}
