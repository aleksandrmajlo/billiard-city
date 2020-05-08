<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 15.02.2020
 * Time: 16:45
 */

namespace App\Services;
use App\Change;
//use App\Customer;
//use App\Money;
use App\Order;
//use App\Reservation;
//use App\Stock;
use App\User;
use Carbon\Carbon;


class ChangeService
{
   public static function change_this(){
       $results=[
           'bar_summa'=>0,
           'bar_count'=>0,
           'bar_month'=>0,
           'table_summa'=>0,
           'table_count'=>0,
           'table_month'=>0,
       ];
       $openChanges = Change::where('stop', '=', null)
           ->orderBy('created_at', 'desc')
           ->get();
       if($openChanges){
           foreach ($openChanges as $openChange){
              if($openChange->user->hasRole('barmen')){
                  $results['bar_summa']=$openChange->total;
                  $results['bar_count']=count($openChange->orders);

              }
              if($openChange->user->hasRole('manager')){
                  $results['table_summa']=$openChange->total;
                  $results['table_count']=count($openChange->orders);
              }

           }
       }
       $start = Carbon::now()->startOfMonth();
       $bar_month=0;
       $table_month=0;
       $order_months=Order::where('start', '>=', $start)->get();
       if($order_months){
           foreach ($order_months as $order_month){
               if(!is_null($order_month->status)){
                   if($order_month->type_billiards==1){
//                       $table_month+=$order_month->barprice;
                       $table_month+=$order_month->amount;
                   }
                   if($order_month->type_bar==1){
//                       $bar_month+=$order_month->barprice;
                       $bar_month+=$order_month->amount;
                   }
               }
           }
       }
       $results['table_month']=round($table_month,2);
       $results['bar_month']=round($bar_month,2);
       return $results;

   }


}