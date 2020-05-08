<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Order extends Model
{
   
    public function tableReservation()
    {
        return $this->hasMany('App\Reservation', 'id', 'reservation_id');
    }

    public function tablePause()
    {
        return $this->hasOne('App\Pause', 'order_id')->orderBy('id', 'DESC');
    }


    public function tableId()
    {
        return $this->hasOne('App\Table', 'id', 'table_id');
    }

    /*
     * корректные методы
     * те что выше удалить потом
     */
    public function table()
    {
        return $this->hasOne('App\Table', 'id', 'table_id');
    }

    public function pauses()
    {
        return $this->hasMany('App\Pause');
    }

    public function bars()
    {
        return $this->hasMany('App\Bar');
    }

    public function getActivepauseAttribute()
    {
        $pauses = DB::table('pauses')
            ->where('order_id', $this->id)
            ->WhereNull('end_pause')
            ->count();
        if ($pauses === 0) {
            return false;
        } else {
            return true;
        }
    }

    /*
    // сумма для даного заказа общая
    public function getBarpriceAttribute()
    {
        $total = 0;
        $bar_products = \App\Bar::where('order_id', '=', $this->id)
            ->get();
        if ($this->type_bar == 1) {
            if ($bar_products) {
                foreach ($bar_products as $bar_product) {
                    $total += $bar_product->count * $bar_product->stock->price;
                }
                if (!is_null($this->customer_id)) {
                    $customer = \App\Customer::where('id', $this->customer_id)->first();
                    if ($customer && !is_null($customer->skidka_bar)) {
                        $m1 = $total * $customer->skidka_bar / 100;
                        $total = round(($total -  $m1), 2);
                    }
                }
            }
        }
        if ($this->type_billiards == 1) {
            return $this->amount;
        }
        return $total;
    }
    */

    public function change()
    {
        return $this->belongsTo('App\Change', 'changes_id');
    }

    public function getIsbarAttribute(){
        if($this->type_bar == 1){
            return true;
        }
        return false;
    }

    public function getTypeAttribute(){
        $locale = app()->getLocale();
        $type=1;
        if($this->type_billiards == 1){
            $type=2;
        }
        if($locale=="ru"){
            if($type==1){
                return 'Бар';
            }
            if($type==2){
                return 'Бильярд';
            }
        }else{
            if($type==1){
                return 'Бар';
            }
            if($type==2){
                return 'Більярд';
            }
        }
        
    }

    public   function user()
    {
        return $this->belongsTo('App\User');
    }

    public   function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    
}
