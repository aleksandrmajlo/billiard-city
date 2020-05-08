<?php

namespace App\Bars;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ingredient extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function stocks()
    {
        return $this->belongsToMany('App\Stock')->withPivot('count');
    }


    public function acts()
    {
        return $this->belongsToMany('App\Acts\Act')->withPivot('count');
    }


    public function purchaseinvoices()
    {
        return $this->belongsToMany('App\Acts\Purchaseinvoice')->withPivot('count');
    }

    public function writeofs()
    {
        return $this->belongsToMany('App\Acts\Writeof')->withPivot('count');
    }



}
