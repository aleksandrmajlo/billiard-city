<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    public function getFullnameAttribute()
    {

         return $this->surname." ".$this->name;
    }
}
