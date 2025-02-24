<?php

namespace App\Models\OverTar;

use App\Enums\TarType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class tar_kst extends Model
{
    use HasFactory;
    protected $connection = 'other';
    protected $guarded = [];
    protected $table = 'tar_kst';
    protected $primaryKey ='wrec_no';

    public $timestamps = false;
    protected $casts=['tar_type'=>TarType::class,];
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
