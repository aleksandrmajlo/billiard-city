<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function orders()
    {
        return $this->hasMany('App\Order')->orderByDesc('id');
    }

    public function getFullnameAttribute()
    {

        return $this->surname . " " . $this->name;
    }


    public function getSkidkaBarnumberAttribute()
    {

        if ($this->skidka_bar) {
            return $this->skidka_bar;
        } else {
            return 0;
        }
    }

    public function getSkidkaBilnumberAttribute()
    {

        if ($this->skidka) {
            return $this->skidka;
        } else {
            return 0;
        }
    }
}
