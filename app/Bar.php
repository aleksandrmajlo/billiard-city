<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bar extends Model
{
    public function stock()
    {
        return $this->hasOne('App\Stock', 'id', 'product_id');
    }
}
