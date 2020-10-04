<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Customer;
use App\User;
use Illuminate\Support\Facades\DB;

class Change extends Model
{
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }

    public function act()
    {
        return $this->hasOne('App\Acts\Act');
    }

    public function orders()
    {
        return $this->hasMany('App\Order', 'changes_id');
    }

    // сумма для даного заказа общая
    public function getTotalAttribute()
    {
        $total = 0;
        if ($this->orders) {
            foreach ($this->orders as $order) {
                $total += $order->amount;
            }
        }
        return round($total, 2);
    }

    public function getNalAttribute()
    {
        $total = 0;
        if ($this->orders) {
            foreach ($this->orders as $order) {
                if(is_null($order->billing)||$order->billing==1){
                    $total += $order->amount;
                }
            }
        }
        return round($total, 2);
    }

    public function getCartAttribute()
    {
        $total = 0;
        if ($this->orders) {
            foreach ($this->orders as $order) {
                if($order->billing==2){
                    $total += $order->amount;
                }
            }
        }
        return round($total, 2);
    }


}
