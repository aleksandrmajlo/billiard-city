<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{

    public function customer()
    {
        return $this->belongsTo('App\Customer','id_customers');
    }
}
