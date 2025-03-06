<?php

namespace App\Models\bank;

use App\Models\aksat\main;
use App\Models\aksat\MainArc;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class BankTajmeehy extends Model
{
    use HasFactory;


  protected $connection = 'other';
  protected $guarded = [];
  protected $table = 'BankTajmeehy';
  protected $primaryKey ='TajNo';

  public $timestamps = false;

    public function main()
    {
        return $this->hasMany(main::class, 'taj_id', 'TajNo');
    }
    public function mainarc()
    {
        return $this->hasMany(MainArc::class, 'taj_id', 'TajNo');
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
