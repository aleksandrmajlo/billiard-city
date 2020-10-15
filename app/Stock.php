<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stock extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function ingredients()
    {
        return $this->belongsToMany('App\Bars\Ingredient')->withPivot('count');
    }

    public function categorySee()
    {
        return $this->hasOne('App\CategoryStock', 'id', 'categorystock_id')->withTrashed();
    }


    public function acts()
    {
        return $this->belongsToMany('App\Acts\Act')->withPivot('count');
    }

    public function writeofs()
    {
        return $this->belongsToMany('App\Acts\Writeof')->withPivot('count');
    }

    public function purchaseinvoices()
    {
        return $this->belongsToMany('App\Acts\Purchaseinvoice')->withPivot('count');
    }
}
