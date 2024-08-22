<?php

namespace App\Models\sell;

use App\Models\stores\items;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class sell_tran extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'sell_tran';
    protected $primaryKey ='rec_no';

    public $timestamps = false;
    public function item()
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
