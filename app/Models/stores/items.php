<?php

namespace App\Models\stores;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class items extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'items';
    protected $primaryKey ='item_no';
    public $incrementing = false;
    public $timestamps = false;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }

    public function iteminstore()
    {
        return $this->hasMany(stores:: class, 'item_no', 'item_no');
    }
    public function iteminhall()
    {
        return $this->hasMany(halls:: class, 'item_no', 'item_no');
    }
    public static  function search($searchKey,$placetype,$place_no,$NotZero=true)
    {
        if ($placetype=='Makazen') {
            $data = items::on(Auth()->user()->company)
              ->whereHas('iteminstore', function ($q) use ($place_no){
                $q->where('st_no',$place_no)->where('raseed', '>', 0);
                });
        }
        if ($placetype=='Salat') {
            $data = items::on(Auth()->user()->company)->whereHas('iteminhall', function ($q)  use ($place_no){
                $q->where('hall_no',$place_no)->where('raseed', '>', 0);
            });
        }
        return $data->where('item_name', 'LIKE', '%' . $searchKey . '%')
          ->orderBy('item_name');
    }


}
