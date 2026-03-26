<?php

namespace App\Models\masr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Masrofat extends Model
{
    use HasFactory;

    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'Masrofat';
    protected $primaryKey ='MasNo';

    public $timestamps = false;

    public function MasTypeTable(): BelongsTo
    {
        return $this->belongsTo(MasTypes::class,'MasType','MasTypeNo');
    }
    public function MasTypeDetailTable(): BelongsTo
    {
       return $this->belongsTo(MasTypeDetails::class,'MasTypeDetail','DetailNo');
    }
    public function MasCenterTable(): BelongsTo
    {
        return $this->belongsTo(MasCenters::class,'MasCenter','CenterNo');
    }
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
