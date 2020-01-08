<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tariff extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public static function minuts($time = null)
    {
        $part = explode(':', $time);
        $a=$part[0]*60+$part[1]*1;
        return $a;
    }
}
