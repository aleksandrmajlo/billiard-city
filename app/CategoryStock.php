<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CategoryStock extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function stocks(){
        return $this->hasMany('App\Stock','categorystock_id')->orderBy('title')->withTrashed();
    }
}
