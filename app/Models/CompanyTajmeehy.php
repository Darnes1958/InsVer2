<?php

namespace App\Models;

use App\Models\bank\BankTajmeehy;
use Illuminate\Database\Eloquent\Model;

class CompanyTajmeehy extends Model
{
    public $timestamps = false;
    public function taj()
    {
        return $this->belongsTo(BankTajmeehy::class,'taj_id','TajNo');
    }
    public function bank(){
        return $this->belongsTo(ExcelSeting::class,'bank_id','id');
    }
}
