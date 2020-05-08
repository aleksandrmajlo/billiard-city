<?php

namespace App\Acts;

use Illuminate\Database\Eloquent\Model;

class Writeof extends Model
{
    
    public function stocks()
    {
        return $this->belongsToMany('App\Stock')->withTrashed()->withPivot('count')->withPivot('cause')->orderBy('title');
    }

    public function ingredients()
    {
        return $this->belongsToMany('App\Bars\Ingredient')->withTrashed()->withPivot('count')->withPivot('cause');
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

}
