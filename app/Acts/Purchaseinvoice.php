<?php

namespace App\Acts;

use Illuminate\Database\Eloquent\Model;

class Purchaseinvoice extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany('App\Bars\Ingredient')->withTrashed()->withPivot('count');
    }

    public function stocks()
    {
        return $this->belongsToMany('App\Stock')->withTrashed()->withPivot('count');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }
}
