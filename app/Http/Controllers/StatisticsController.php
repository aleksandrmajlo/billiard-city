<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\Pause;
use App\Http\Controllers\Auth;
class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $ordersSum = 0;
        $user = \Auth::user();
        $req_user = false;
        $isAdmin = false;
        $isBarmen = false;
        $isManager = false;
        $orders = Order::orderBy('created_at', 'desc');

        if ($user->hasRole('admin') || $user->hasRole('manager')) {

            if ($user->hasRole('admin')) {
                $isAdmin = true;
                if (isset($request->user_id) && $request->user_id !== "0") {
                    $req_user = $request->user_id;
                    $orders = $orders->where('user_id', '=', $request->user_id);
                }
                if (isset($request->type) && $request->type == 2) {
                    $orders = $orders->where('type_billiards', 1);
                }
                if (isset($request->type) && $request->type == 1) {
                    $orders = $orders->where('type_bar', 1);
                }

            }
            if ($user->hasRole('manager')) {
                $isManager = true;
                $orders = $orders->where('user_id', $user->id);
            }
            if ($request->has('time_start') && !empty($request->time_start) && $request->has('time_end') && !empty($request->time_end)) {

                $time_start_ar = explode(':', $request->time_start, 2);
                $time_end_ar = explode(':', $request->time_end, 2);
                $orderpoisk = Order::orderBy('created_at', 'desc');

                if ((int)$time_start_ar[0] >= (int)$time_end_ar[0]) {
                    // промежуток от типа 22:00 from 04:00
                    $orderpoisk->whereTime('start', '>=', $request->time_start . ':00')
                        ->orWhereTime('start', '<=', '23:59:59');
                    $orderpoisk->whereTime('closed', '>=', '00:00:00')
                        ->orWhereTime('closed', '<=', $request->time_end . ':00');

                } else {
                    // промежуток от типа 22:00 from 23:00
                    $orderpoisk->whereTime('start', '>=', $request->time_start . ':00')
                        ->whereTime('start', '<=', $request->time_end . ':00');
                    $orderpoisk->whereTime('closed', '>=', $request->time_start . ':00')
                        ->whereTime('closed', '<=', $request->time_end . ':00');
                }
                $res = $orderpoisk->get();
                $ids = [];
                foreach ($res as $item) {
                    $ids[] = $item->id;
                }
                $orders = $orders->whereIn('id', $ids);
            }
            if ($request->has('date_start') && !empty($request->date_start)) {
                $orders->whereDate('start', '>=', $request->date_start);
            }
            if ($request->has('date_end') && !empty($request->date_end)) {
                $orders->whereDate('closed', '<=', $request->date_end);
            }

        } elseif ($user->hasRole('barmen')) {

            $isBarmen = true;
            $orders = $orders->where('user_id', $user->id);
            if ($request->has('date_start') && !empty($request->date_start)) {
                $time_start = ' 00:00:00';
                if ($request->time_start && !empty($request->time_start)) {
                    $time_start = ' ' . $request->time_start . ':00';
                }
                $orders = $orders->where('start', '>=', $request->date_start . $time_start);
            }
            if ($request->has('date_end') && !empty($request->date_end)) {
                $time_end = ' 00:00:00';
                if ($request->time_end && !empty($request->time_end)) {
                    $time_end = ' ' . $request->time_end . ':00';
                }
                $orders = $orders->where('closed', '<=', $request->date_end . $time_end);
            }

        }
        $orders = $orders->paginate(20)->appends(request()->query());

        $ordersSum = $orders->sum('amount');
        $workers = User::all();
        $customers = Customer::all();
        $tables = Table::all();

        return view('stat', compact('orders', 'workers', 'ordersSum', 'customers', 'tables', 'req_user', 'isAdmin', 'isManager'));
    }

    // /info о заказе
    public function OrderInfo($id)
    {
        $order = Order::findOrfail($id);
        $user = \Illuminate\Support\Facades\Auth::user();
        $isAdmin=false;
        if ($user->hasRole('admin')){
            $isAdmin=true;
        }
        $billing = config('billing');
        if (!$order->isbar&&$user->hasRole('barmen')){
            return redirect('/noaccess');
        }
        if ($order->isbar) {
            return view('order.order_info_bar',
                compact('id', 'order', 'billing','isAdmin')
            );
        }
        else{
            $pauses_s = Pause::where('order_id', $id)->get();
            $pauses=[];
            if($pauses_s){
                foreach ($pauses_s as $item){
                    $end="";
                    if($item->end_pause){
                        $end=Carbon::createFromFormat('Y-m-d H:i:s', $item->end_pause)->format('H:i');
                    }
                    $pauses[]=[
                        'start'=>Carbon::createFromFormat('Y-m-d H:i:s', $item->start_pause)->format('H:i'),
                        'end'=>$end,
                    ] ;
                }
            }
            return view('order.order_info_table',
                compact('id', 'order', 'billing','isAdmin','pauses')
            );
        }
    }
}
