<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryStock extends Model
{
    public function stocks(){
        return $this->hasMany('App\Stock','categorystock_id')->orderBy('title');
    }
}
