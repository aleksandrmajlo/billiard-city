<?php

namespace App\Bars;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{

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
