<?php

namespace App\Models\masr;

use App\Models\bank\Companies;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class MasCenters extends Model
{
    use HasFactory;

    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'MasCenters';
    protected $primaryKey ='CenterNo';
    public $incrementing = false;
    public $timestamps = false;

    public function Company():BelongsTo {
        return $this->belongsTo(Companies::class,'company_id','CompNo');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
