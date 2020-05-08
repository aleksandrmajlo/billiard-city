<?php


namespace App\Http\Controllers\Order;


use App\Http\Controllers\Controller;
use App\User;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;


class OrderInfo extends Controller
{
     // получить работников  для выода в селекте
     public function InfoOrders(Request $request){
         $users=User::all();
         foreach ($users as $user){
             $results[] = [
                 'text' => $user->name,
                 'value' => $user->id,
             ];
         }
         $id=$request->id;
         $order=Order::findOrfail($id);

         $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $order->start);
         $date['start_time'] = $date_start->format('H:i');
         $date['start_date'] = $date_start->format('Y-m-d');

         $date['end_time']="";
         $date['end_date']="";
         if($order->closed){
             $date_closed = Carbon::createFromFormat('Y-m-d H:i:s', $order->closed);
             $date['end_time'] = $date_closed->format('H:i');
             $date['end_date'] = $date_closed->format('Y-m-d');
         }
         $order_res=[
             'customer_id'=>$order->customer_id,
             'user_id'=>$order->user_id,
             'date'=>$date,
             'amount'=>$order->amount,
             'info'=>$order->info,
         ] ;
         return response()->json(['users'=>$results,'order'=>$order_res]);
     }

     // cохранить отредактированный заказ
    public function ReadOrders(Request $request){
          $order=Order::find($request->id);
          $order->user_id=$request->user_id;
          $order->customer_id=$request->customer_id;
          $order->amount=$request->amount;
          $order->info=$request->info;
          $order->start=$request->start_date.' '.$request->start_time;
          $order->closed=$request->end_date.' '.$request->end_time;
          $order->save();
          return response()->json(['suc'=>true]);
    }

}