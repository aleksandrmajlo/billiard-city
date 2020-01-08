<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Table;
use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Auth;

class StatisticsController extends Controller
{
    public function index(Request $request){
        $user = \Auth::user()->id;
        $orderpoisk = Order::where('type_billiards', 1)->get();
        foreach ($orderpoisk as $orderpois) {
            $rgTimes = array(
                '23:50', '23:59'
            );
            $fTime = date('G:i', strtotime($orderpois->start));
            if(strtotime($rgTimes[0]) <= strtotime($fTime) && strtotime($fTime) <= strtotime($rgTimes[1])){
                $serchGreen[] = $orderpois->id;
            }
            $rgTimes = array(
                '00:00', '09:59'
            );
            $fTime = date('G:i', strtotime($orderpois->start));
            if(strtotime($rgTimes[0]) <= strtotime($fTime) && strtotime($fTime) <= strtotime($rgTimes[1])){
                $serchGreen[] = $orderpois->id;
            }
            $rgTimes = array(
                '23:50', '23:59'
            );
            $fTime = date('G:i', strtotime($orderpois->closed));
            if(strtotime($rgTimes[0]) <= strtotime($fTime) && strtotime($fTime) <= strtotime($rgTimes[1])){
                $serchGreen[] = $orderpois->id;
            }
            $rgTimes = array(
                '00:00', '09:59'
            );
            $fTime = date('G:i', strtotime($orderpois->closed));
            if(strtotime($rgTimes[0]) <= strtotime($fTime) && strtotime($fTime) <= strtotime($rgTimes[1])){
                $serchGreen[] = $orderpois->id;
            }
        }
        $orders = new Order();
        $orders = $orders->orderBy('created_at', 'desc');
        if(!\Auth::user()->hasRole('admin')) {
            $orders = $orders->where('user_id', $user);
        }
        if($request->all()) {

            if(isset($request->type) && $request->type == 2) {
                $orders = $orders->where('type_billiards', 1);
                }
            if(isset($request->type) && $request->type == 1) {
                $orders = $orders->where('type_bar', 1);
            }
            if(isset($request->ot)) {
                $orders = $orders->where('start', '>=', $request->ot);
            }
            if(isset($request->do)) {
                $orders = $orders->where('closed', '<=', $request->do);
            }
            if(isset($request->work) && $request->work != 0) {
                $orders = $orders->where('user_id', '=', $request->work);
            }

            if(isset($request->customer) && $request->customer != 0) {
                $orders = $orders->where('customer_id', '=', $request->customer);
            }

            $orders = $orders->orderBy('created_at', 'desc');
            $ordersSum = $orders->sum('amount');

            if(empty($ordersSum)) {$ordersSum = '';}

            if(isset($request->stil) && $request->stil != 0) {
                $orders = $orders->where('table_id', $request->stil);
            }

            if(isset($request->billgreen) && $request->billgreen == 1) {
                $orders = $orders->whereIn('id', $serchGreen);
            }
        }
        $orders = $orders->paginate(20);
        $workers = User::all();
        $customers = Customer::all();
        $tables = Table::all();

        return view('stat', compact('orders', 'workers', 'ordersSum', 'customers', 'tables'));
    }




}
