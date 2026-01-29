<?php

namespace App\Models\aksat;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class LongPeriod extends Model
{
    protected $connection = 'Elmaleh';
    protected $guarded = [];

    public function main()
    {
        return $this->belongsTo(main::class);
    }
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check()) {

            $this->connection=Auth::user()->company;

        }
    }
}
