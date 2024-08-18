<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class DownModel extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasFactory;

    protected $table = 'DownModel';

    public static function last(){
        return static::all()->last();
    }
}
