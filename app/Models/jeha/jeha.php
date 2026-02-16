<?php

namespace App\Models\jeha;

use App\Models\buy\buys;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class jeha extends Model
{


    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'jeha';
    protected $primaryKey ='jeha_no';
    public $incrementing = false;
    public $timestamps = false;

    public function buys(): HasMany
    {
        return $this->hasMany(buys::class,'jeha_no','jeha');
    }
    public function jehatype()
    {

        return $this->belongsTo(jeha_type:: class, 'jeha_type', 'type_no');

    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }






}
