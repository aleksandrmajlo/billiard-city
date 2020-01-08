<?php

namespace App\Bars;

use Illuminate\Database\Eloquent\Model;

class Kofeinyiapparat extends Model
{

    public function act(){
        return $this->belongsTo('App\Acts\Act');
    }
}
