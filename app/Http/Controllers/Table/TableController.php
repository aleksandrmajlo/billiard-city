<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 25.02.2020
 * Time: 18:39
 */

namespace App\Http\Controllers\Table;

use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Table;
use App\Order;
use App\Reservation;
use App\Pause;
use App\Change;
use Illuminate\Support\Facades\Auth;

class TableController extends Controller
{

    public function index()
    {
        return view('table.tables', [
        ]);
    }

    // получение всех столов
    public function getTables()
    {
        $tables = Table::orderBy('posiiton', 'desc')->get();
        $reservTable[] = 0;
        $reserv = Reservation::where('book', null)
            ->where('booking_before', null)
            ->get();
        if (count($reserv) > 0) {
            foreach ($reserv as $reser) {
                $reservTable[] = $reser->id_table;
            }
        }
        $json_tables = [];

        foreach ($tables as $table) {
            if (in_array($table->id, $reservTable)) {
                // то есть стол в работе
                $order = Order::where('table_id', $table->id)->where('closed', '=', null)->first();
                if($order){
                    $json_tables[] = [
                        'id' => $table->id,
                        'number' => $table->number,
                        'name' => $table->name,
                        'image' => $table->image,
                        'free' => false,
                        'order_id' => $order->id,
                    ];
                }
            } else {
                // свободен
                $json_tables[] = [
                    'id' => $table->id,
                    'number' => $table->number,
                    'name' => $table->name,
                    'image' => $table->image,
                    'free' => true
                ];

            }
        }
        return response()->json([
                'tables' => $json_tables
            ]
        );
    }

    // получение цены стола прочее
    public function GetTablePrice(Request $request)
    {
        $id = $request->id;
        $results = OrderService::priceOrder($id);

        return response()->json(['results' => $results]);
    }

    // установка паузы
    public function SetPause(Request $request)
    {
        app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $request->table]);
        if ($request->pause) {
            $pause = Pause::find($request->pause);
            $pause->end_pause = Carbon::now();
            $pause->save();
        } else {
            $pause = new Pause();
            $pause->order_id = $request->order;
            $pause->start_pause = Carbon::now();
            $pause->save();
        }
        return response()->json([
                'suc' => true
            ]
        );
    }

    // открытие стола
    public function AddTable(Request $request)
    {
        app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $request->table]);
        $customer = null;
        if ($request->user == 'client') {
            $customer = $request->client;
        }
        $reservation = new Reservation();
        $reservation->id_table = $request->table;
        $reservation->id_user = Auth::user()->id;
        $reservation->id_customers = $customer;
        $reservation->booking_from = Carbon::now();
        $reservation->booking_before = null;
        $reservation->sum_booking = null;
        $reservation->save();

        $change=null;
        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderCreate = new Order();
        $orderCreate->table_id = $request->table;
        $orderCreate->user_id = Auth::user()->id;
        $orderCreate->customer_id = $customer;
        $orderCreate->reservation_id = $reservation->id;
        $orderCreate->amount = 0;
        if ($change) {
            $orderCreate->changes_id = $change->id;
        }
        $orderCreate->start = Carbon::now();
        $orderCreate->type_billiards = 1;
        $orderCreate->type_bar = 0;
        $orderCreate->save();
        return response()->json([
                'suc' => true,
            ]
        );


    }

    // проверить или менеджер открыл смену
    public function openChangeId(){
        $userAuthId = Auth::user()->id;
        $openChangeId=false;
        $isAdmin=false;
        if(Auth::user()->hasRole('manager')){
            $openChange = Change::where('stop', '=', null)
                ->where('user_id', '=', $userAuthId)
                ->orderBy('created_at', 'desc')
                ->first();
            if($openChange)$openChangeId=true;
        }
        if(Auth::user()->hasRole('admin')){
            $isAdmin=true;
        }

        return response()->json([
                'openChangeId' => $openChangeId,
                'isAdmin' => $isAdmin,
            ]
        );

    }



}