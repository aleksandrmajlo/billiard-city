<?php

namespace App\Acts;

use Illuminate\Database\Eloquent\Model;

class Act extends Model
{
    public function ingredients()
    {
        return $this->belongsToMany('App\Bars\Ingredient')->withTrashed()->withPivot('count')->orderBy('title');
    }

    public function stocks()
    {
        return $this->belongsToMany('App\Stock')->withTrashed()->withPivot('count')->orderBy('title');
    }

    public function change()
    {
        return $this->belongsTo('App\Change');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function kofeinyiapparat()
    {
        return $this->hasOne('App\Bars\Kofeinyiapparat');
    }
}
