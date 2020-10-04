<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Table extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function tariff()
    {
        return $this->hasMany('App\Tariff');
    }

    // номер стола
    /*
    public function getNumerAttribute()
    {
        $title = $this->title;
        $arr = explode(' ', $title, 2);
        return $arr[0];
    }
    */

    // название стола без номера
    public function getNameAttribute()
    {
        $title = $this->title;
        $arr = explode(' ', $title, 2);
        if (isset($arr[1])) return $arr[1];
        return $title;
    }


}
