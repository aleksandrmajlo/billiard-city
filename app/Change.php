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


        $user_id=$this->user_id;
        $userChange = DB::select('select * from users_roles where user_id = ?', array($user_id));



       if (isset($userChange[0]->role_id) && $userChange[0]->role_id == 2) {

           if(isset($this->start) && isset($this->stop)){
               $total = \App\Order::where('created_at', '>=', $this->start)
                   ->where('created_at', '<=', $this->stop)
                   ->where('user_id', '=', $this->user_id)
                   ->sum('amount');
           }elseif (isset($this->start)){

               $total = \App\Order::where('created_at', '>=', $this->start)
                   ->where('created_at', '<=', \Carbon\Carbon::now())
                   ->where('user_id', '=', $this->user_id)
                   ->sum('amount');
           }

       }
       else{

           $products = [];
           if ($this->orders) {
               $countDiscount = [];
               foreach ($this->orders as $order) {
                   if ($order->type_bar == 1 && $order->bars) {
                       $customer_id = $order->customer_id;
                       $skidka = 0;
                       if ($customer_id) {
                           $customer = Customer::find($customer_id);
                           if ($customer->skidka_bar) {
                               $skidka = $customer->skidka_bar;
                           }
                       }
                       if($order->bars){
                           foreach ($order->bars as $bar) {
                               if($bar->stock){
                                   $products[] = [
                                       'id' => $bar->stock->id,
                                       'count' => $bar->count,
                                       'price' => $bar->stock->price,
                                       'discount' => $skidka
                                   ];
                               }
                           }
                       }
                   }

               }
               foreach ($products as $k => $product){
                   $thTotal = $product['price'] * $product['count'];
                   $summSk = $product["discount"]* $thTotal / 100;
                   $thTotal = $thTotal - $summSk;
                   $total += $thTotal;
               }

           }

       }


        return round($total,2);
    }
  


}
