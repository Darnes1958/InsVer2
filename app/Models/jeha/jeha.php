<?php

namespace App\Models\jeha;

use App\Models\buy\buys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class jeha extends Model
{
    use HasFactory;

    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'jeha';
    protected $primaryKey ='jeha_no';
    public $incrementing = false;
    public $timestamps = false;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }


    public function jehatype()
    {

        return $this->belongsTo(jeha_type:: class, 'jeha_type', 'type_no');

    }
    public function jehaorderbuy()
    {
        return $this->hasOne(buys::class,'jeha','jeha_no');
    }
    public static  function search($searchKey,$jeha_type=0)
    {
        if ($jeha_type==0)
         return self::on(Auth()->user()->company)
             ->where('available',1)
             ->where('jeha_name', 'LIKE', '%' . $searchKey . '%')

             ->orderBy('jeha_name');
        if ($jeha_type==3)
            return self::on(Auth()->user()->company)
                ->where('jeha_type','>',2)
                ->where('available',1)
                ->where('jeha_name', 'LIKE', '%' . $searchKey . '%')

                ->orderBy('jeha_name');
        if ($jeha_type==1 || $jeha_type==2)
            return self::on(Auth()->user()->company)
                ->where('jeha_type','=',$jeha_type)
                ->where('available',1)
                ->where('jeha_name', 'LIKE', '%' . $searchKey . '%')

                ->orderBy('jeha_name');
    }

}
