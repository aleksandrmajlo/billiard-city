<?php

namespace App\Http\Controllers;

use App\Bar;
use App\Bars\Kofeinyiapparat;
use App\Change;
use App\Customer;
use App\Order;
use App\Pause;
use App\Reservation;

use App\Services\Kofeinyiapparatcount;
use App\Socket;
use App\Stock;
use App\Table;
use App\Tariff;
use App\User;

use Exception;
use Illuminate\Http\Request;
//use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use App\Money;
use DateTime;
use \Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use PHPUnit\Util\Printer;
use App\Services\OrderService;
use App\Services\ActService;
use App\Bars\Ingredient;




class OrderController extends Controller
{


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        /*

        $stocks = Stock::where('published', 1)
            ->where('count', '>', 0)
            ->orWhere('unlimited', '=', 1)
            ->orderBy('title', 'ASC')
            ->get();
        $products=[];
        $kofeinyi_apparat_category_id=config('category.kofeinyi_apparat_category_id');
        foreach ($stocks as $stock){
            if(count($stock->ingredients)>0){
                $ar=[];
                foreach ($stock->ingredients as $ingredient){
                    $ar[]=(int)floor($ingredient->count/$ingredient->pivot->count);
                }
                $min=min($ar);
                if($min>0){
                    $products[]=(object) [
                        'title'=>$stock->title,
                        'id'=>$stock->id,
                        'count'=>$min,
                    ];
                }
            }
            else{
                $count=$stock->count;
                if($stock->categorySee->id==$kofeinyi_apparat_category_id){
                    $count="кава";
                }
                $products[]=(object) [
                    'title'=>$stock->title,
                    'id'=>$stock->id,
                    'count'=>$count,
                ];
            }
        }
        $order = '';
        $customers = Customer::all();
        return view('admin.order.create', compact(
            'order',
            'products', 'customers'));

        */



        //******************* новый код *******************************************

        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderCreate = new Order();
        $orderCreate->user_id = Auth::user()->id;
        $orderCreate->customer_id = null;
        $orderCreate->reservation_id = 0;
        $orderCreate->amount = 0;
        $orderCreate->type_billiards = 0;
        $orderCreate->type_bar = 1;
        if ($change) {
            $orderCreate->changes_id = $change->id;
        }
        $orderCreate->start = date("y-m-d H:i:s");
        $orderCreate->closed = null;
        $orderCreate->save();

        return redirect('/order-closed/'.$orderCreate->id);
    }

    public function orderBar($id)
    {
        $products = Bar::where('order_id', '=', $id)
            ->get();
        return view('order-bar', compact('products'));
    }


    public function createOrderTable()
    {
        $reservTable[] = 0;
        $reserv = Reservation::where('book', null)
            ->where('booking_before', null)
            ->get();

        if (count($reserv) > 0) {
            foreach ($reserv as $reser) {
                $reservTable[] = $reser->id_table;
            }
        }

        $tables = Table::whereNotIn('id', $reservTable)->get();


        if (count($tables) <= 0) {
            return redirect('/open-table')->with('status', 'Всі столи зайняті');
        }

        $customers = Customer::all();
        $order = '';
        return view('admin.order.create_table', compact('order', 'tables', 'customers'));
    }

    /**
     * сохранение заказа
     */
    public function store(Request $request)
    {

       
        foreach ($request->all() as $k => $item) {
            if (strpos($k, 'product') !== false) {
                $products[] = $item;
            }
            if (strpos($k, 'count') !== false) {
                $counts[] = $item;
            }
        }
        $kofeinyi_apparat_category_id=config('category.kofeinyi_apparat_category_id');

        // обновление количества продуктов или инградиентов при открытии бара
        foreach ($products as $k => $product) {
            $stock=Stock::find($product);
            // количество категории кава не уменьшаем
            if($stock->categorySee->id==$kofeinyi_apparat_category_id){
                Kofeinyiapparatcount::addOrder((int)$counts[$k]);
            }
            // проверка на кол-во - или доступно для создания
            if(count($stock->ingredients)>0){
                foreach ($stock->ingredients as $ingredient){
                    $newCount=$ingredient->count-($ingredient->pivot->count*$counts[$k]);
                    if($newCount<0){
                        return redirect('/orders-create')->with('status', 'Недостатньо товарів на складі!');
                    }
                    else{
                        $ing=Ingredient::find($ingredient->id);
                        $newCount=$ing->count-($ingredient->pivot->count*$counts[$k]);
                        $ing->count=$newCount;
                        $ing->save();
                    }
                }

            }else{
                $newCount = $stock->count - $counts[$k];
                if ($stock->unlimited == null&&$newCount<0) {
                    return redirect('/orders-create')->with('status', 'Недостатньо товарів на складі!');
                }
                $stock->count = $newCount;
                $stock->save();
            }
        }
        $pr[] = $stock->price * (int)$counts[$k];


        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderCreate = new Order();
        $orderCreate->user_id = Auth::user()->id;
        $orderCreate->customer_id = $request->customer;
        $orderCreate->reservation_id = 0;
        $orderCreate->amount = array_sum($pr);
        $orderCreate->type_billiards = 0;
        $orderCreate->type_bar = 1;
        $orderCreate->info = $request->info;
        if ($change) {
            $orderCreate->changes_id = $change->id;
        }
        $orderCreate->start = date("y-m-d H:i:s");
        $orderCreate->closed = null;
        $orderCreate->save();

        foreach ($products as $k => $product) {
            $bar = new Bar();
            $bar->product_id = $product;
            $bar->order_id = $orderCreate->id;
            $bar->count = $counts[$k];
            $bar->save();
        }

        // обновляем кол-во продуктов с инградиентами
        ActService::UpdateProductCount();

        return redirect('/open-bar')->with('status', 'Замовлення створено!');
    }


    public function storeSmall(Request $request)
    {
        foreach ($request->all() as $k => $item) {
            if (strpos($k, 'product') !== false) {
                $products[] = $item;
            }
            if (strpos($k, 'count') !== false) {
                $counts[] = $item;
            }
        }

        foreach ($products as $k => $product) {
            $price = Stock::where('id', '=', $product)->firstOrFail();
            $pr[] = $price->price * $counts[$k];

            // count -
            $newCount = $price->count - $counts[$k];


            if ($price->unlimited == null) {
                if ($newCount < 0) {
                    return redirect('/open-table')->with('status', 'Недостатньо товарів на складі!');
                }
            }

            if ($newCount < 0) {
                $newCount = 0;
            }

            $stock = Stock::find($price->id);
            $stock->count = $newCount;
            $stock->save();
        }

        $sumA = Order::where('id', '=', $request->orderid)->firstOrFail();
        $summaNew = $sumA->amount + array_sum($pr);

        $orderUpdate = Order::find($request->orderid);
        $orderUpdate->amount = $summaNew;
        $orderUpdate->save();

        foreach ($products as $k => $product) {
            $bar = new Bar();
            $bar->product_id = $product;
            $bar->order_id = $request->orderid;
            $bar->count = $counts[$k];
            $bar->save();
        }

        return Redirect::back()->with('status', 'Доданно!');
    }


    public function storeOrderTable(Request $request)
    {
        app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $request->table]);
        $tableReservCheck = Order::where('table_id', '=', $request->table)
            ->where('type_billiards', '=', 1)
            ->where('status', '=', null)
            ->first();
        if (isset($tableReservCheck)) {
            if ($tableReservCheck->close == null) {
                return redirect('/open-table')->with('status', 'стіл зайнятий!');
            }
        }

        $s1 = strtotime($request->booking_before);
        $s2 = strtotime($request->booking_from);

        $startM = date("H:i", $s1);
        $endM = date("H:i", $s2);

        $startB = Tariff::minuts($endM) + date("N", strtotime($request->booking_from)) * 24 * 60;
        $endB = Tariff::minuts($startM) + date("N", strtotime($request->booking_before)) * 24 * 60;


        $tablePrices = Tariff::where('table_id', '=', $request->table)
            ->get();

        $vars = range($startB, $endB);
        // массивы цен перебираем
        $k = 0;
        foreach ($tablePrices as $tablePrice) {
            $range[] = range($tablePrice->start, $tablePrice->end);
            $price[] = $tablePrice->price;
            foreach ($vars as $var) {
                if (in_array($var, $range[$k])) {
                    $sum[] = 1;
                    $count = round(array_sum($sum) * ($price[$k] / 60));
                }


            }
            $k++;
        }
        if (empty($count)) {
            dd('Сетка тарифов не заполнена');
        }

        $reservation = new Reservation();
        $reservation->id_table = $request->table;
        $reservation->id_user = Auth::user()->id;
        $reservation->id_customers = $request->customer;
        $reservation->booking_from = Carbon::now();
        $reservation->booking_before = null;
        $reservation->sum_booking = null;
        $reservation->save();

        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderCreate = new Order();
        $orderCreate->table_id = $request->table;
        $orderCreate->user_id = Auth::user()->id;
        $orderCreate->customer_id = $request->customer;
        $orderCreate->reservation_id = $reservation->id;
        $orderCreate->amount = 0;
        if ($change && count($change) > 0) {
            $orderCreate->changes_id = $change->id;
        }
        $orderCreate->start = Carbon::now();
        $orderCreate->type_billiards = 1;
        $orderCreate->type_bar = 0;
        $orderCreate->save();


        return redirect('/open-table')->with('status', 'Додано!');
    }

    public function closeBar(Request $request)
    {

        return redirect('/open-bar')->with('status', 'Закрито!');
    }

    // /info о заказе
    public function orderBarClosedAdmin($id)
    {

        $pauseId = Pause::where('order_id', $id)->get();
        $products = Bar::where('order_id', '=', $id)
            ->get();
        $orderId = Order::where('id', '=', $id)
            ->first();
        $customerid = $orderId->customer_id;
        $orderId = $id;
        $orderId2 = Order::where('id', '=', $id)
            ->first();
        $schet = 1;
        $products = Bar::where('order_id', '=', $id)
            ->get();
        $resrv = Reservation::where('id', '=', $orderId2->reservation_id)
            ->first();
        $summabill = '';
        $min = '';
        $schettable = 22;
        if ($resrv) {
            $summabill = $resrv->sum_booking;
            $min = $resrv->min;
            $schettable = 1;
        }
        $pricesum=[];
        return view('order-bar-closed', compact('products', 
                                                'orderId',
                                                'pricesum',
                                                'customerid',
                                                'orderId2',
                                                'schet', 'schettable',
                                                'min', 'summabill', 'pauseId'));
    }


    // закрытие заказа бара форма
    public function orderBarClosed($id)
    {




        $schettable = 2;
        $products = Bar::where('order_id', '=', $id)
            ->get();
        $orderId = Order::where('id', '=', $id)
            ->first();
        if ($orderId->status == 1) {
            return redirect('/open-bar')->with('status', 'Замовлення вже закрито!');
        }
        return view('order.order-bar-create',[
            'id'=>$id
        ]);

    }
    /*
     * закрытие заказа страница  столы
     */
    public function orderBillClosed($id)
    {
        $tz = config('app.timezone');
        $money = config('app.moneyClobal');
        $order = Order::where('id', '=', $id)->first();

        if ($order->status == 1) {
            return redirect('/open-table')->with('status', 'Замовлення вже закрито!');
        }

        $s1 = $order->start;
        $endValidate = OrderService::validateTime($order);

        $s2 = $endValidate['endDate'];
        $s1 = $endValidate['startCarbon'];


        $startM = $s1->format("H:i");
        $endM = $s2->format("H:i");
        $startB = ($s1->format("N") * 24 * 60) + Tariff::minuts($startM);
        $endB = ($s2->format("N") * 24 * 60) + Tariff::minuts($endM);
        $tablePrices = Tariff::withTrashed()
            ->where('table_id', '=', $order->table_id)
            ->get();

        // првоерка при переходе недели
        if ($s2->format("N") == 1 && $s1->format("N") == 7 && $endB < 2039) {
            $vars1 = range($startB, 11519);
            $vars2 = range(1440, $endB);
            $vars = array_merge($vars1, $vars2);
        } else {
            $vars = range($startB, $endB - 1);
        }
        foreach ($vars as $k => $var) {
            $varo[] = $var[$k];
        }
        $count = [];

        // массивы цен перебираем
        $k = 0;
        $minStart=0;
        foreach ($tablePrices as $tablePrice) {
            $range[] = range($tablePrice->start, $tablePrice->end);
            $price[] = $tablePrice->price;
            foreach ($vars as $var) {
                if (in_array($var, $range[$k])) {
                    $minpay = app()->call('App\Http\Controllers\OrderController@minPay', [
                            'min' => $var,
                            'orderId' => $order,
                            'var' => $vars
                        ]
                    );
                    if($minStart===0){
                        $minStart=$minpay;
                    }
                    $count[] = 1 * round($minpay / 60, 2);
                }
            }
            $k++;
        }
        // паузы
        $pauses = Pause::where('order_id', $order->id)->get();
        $countPause = [];
        foreach ($pauses as $pause) {
            if (isset($pause->end_pause)) {
                $minpause[] = floor((strtotime($pause->end_pause) - strtotime($pause->start_pause)) / 60);
                $s_pause1 = $pause->start_pause;
                $s_pause2 = $pause->end_pause;

                $s_pause1 = strtotime($s_pause1);
                $s_pause2 = strtotime($s_pause2);

                $startM = date("H:i", $s_pause1);
                $endM = date("H:i", $s_pause2);

                $startB = (date("N", $s_pause1) * 24 * 60) + Tariff::minuts($startM);
                $endB = (date("N", $s_pause2) * 24 * 60) + Tariff::minuts($endM);
                $tablePrices = Tariff::withTrashed()->where('table_id', '=', $order->table_id)
                    ->get();
                if (date("N", $s_pause2) == 1 && date("N", $s_pause1) == 7 && $endB < 2039) {
                    $vars1 = range($startB, 11519);
                    $vars2 = range(1440, $endB);
                    $vars = array_merge($vars1, $vars2);
                } else {
                    $vars = range($startB, $endB);
                }
                foreach ($tablePrices as $tablePrice) {
                    $range[] = range($tablePrice->start, $tablePrice->end);
                    $price[] = $tablePrice->price;
                    foreach ($vars as $var) {
                        if (in_array($var, $range[$k])) {
                            $minpay = app()->call('App\Http\Controllers\OrderController@minPay', ['min' => $var, 'orderId' => $order, 'var' => $vars]);
                            $sumPause[] = 1;
                            $countPause[] = 1 * round($minpay / 60, 2);

                        }
                    }
                    $k++;
                }
            } else {
                $minpause[] = floor((strtotime(Carbon::now()) - strtotime($pause->start_pause)) / 60);
                $s_pause1 = $pause->start_pause;
                $s_pause2 = Carbon::now();

                $s_pause1 = strtotime($s_pause1);
                $s_pause2 = strtotime($s2);

                $startM = date("H:i", $s_pause1);
                $endM = date("H:i", $s_pause2);

                $datanowCarbon = strtotime(Carbon::now());
                $startB = (date("N", $s_pause1) * 24 * 60) + Tariff::minuts($startM);
                $endB = (date("N", $s_pause2) * 24 * 60) + Tariff::minuts($endM);

                $tablePrices = Tariff::withTrashed()->where('table_id', '=', $order->table_id)
                    ->get();
                if (date("N", $s_pause2) == 1 && date("N", $s_pause1) == 7 && $endB < 2039) {
                    $vars1 = range($startB, 11519);
                    $vars2 = range(1440, $endB);
                    $vars = array_merge($vars1, $vars2);
                } else {
                    $vars = range($startB, $endB);
                }
                foreach ($tablePrices as $tablePrice) {
                    $range[] = range($tablePrice->start, $tablePrice->end);
                    $price[] = $tablePrice->price;
                    foreach ($vars as $var) {
                        if (in_array($var, $range[$k])) {
                            $minpay = app()->call('App\Http\Controllers\OrderController@minPay', ['min' => $var, 'orderId' => $order, 'var' => $vars]);
                            $sumPause[] = 1;
                            $countPause[] = 1 * round($minpay / 60, 2);
                        }
                    }
                    $k++;
                }
            }
        }

        $LogData = [
            'price' => $count,
            'pricePause' => $countPause,
        ];

        /*
         * цена продукта и стола  в одном месте
         */
        $PriceResults = OrderService::getOrderProductPrice($order, $count,$countPause, $endValidate,$minStart);

        $LogData['minutes'] = [];
        $tz = config('app.timezone');
        foreach ($LogData['price'] as $k => $item) {
            $startCarbon = new Carbon($s1, $tz);
            $thisTime = $startCarbon->addMinutes($k);
            $LogData['minutes'][] = $thisTime->format('H:i');
        }
        return view('order.orderClosed',
            [
                'order' => $order,
                's1' => $s1,
                's2' => $s2,
                'minutes' => $endValidate['minutes'],
                'Total_minutes' => $endValidate['Total_minutes'],
                'pauseMinutes' => $endValidate['pauseMinutes'],
                'customer' => $PriceResults['customer'],
                'price' => $PriceResults,
                'pauses' => $pauses,
                'money' => $money,
                'LogData' => $LogData,
                'tz'=>$tz
            ]
        );
    }

    public function minPay($min = null, $orderId = null)
    {
        $tablePrices = Tariff::withTrashed()->where('table_id', '=', $orderId->table_id)
            ->get();
        foreach ($tablePrices as $tablePrice2) {
            $range[] = range($tablePrice2->start, $tablePrice2->end);
            foreach ($range as $d => $rang) {
                $rang = $rang;
            }
            if (in_array($min, $rang)) {
                $pric = $tablePrice2->price;
            }
        }
        return $pric;
    }


    // само закрытие заказа
    public function orderBarClosedOrder(Request $request)
    {

        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderUpdate = Order::find($request->id);
        $orderUpdate->status = 1;
        $orderUpdate->info = $request->info;
        $orderUpdate->billing = $request->billing;
        if ($change) {
            $orderUpdate->changes_id = $change->id;
        }
        $orderUpdate->closed = Carbon::now();
        $orderUpdate->save();

        $money = new Money;
        $money->sum_bar = $request->priceAmount;
        $money->order = $request->id;
        $money->user_id = Auth::user()->id;
        $money->smena = $orderUpdate->changes_id;
        $money->save();

        return redirect('/open-bar')->with('status', 'Замовлення закрите!');
    }


    public function pauseBill(Request $request)
    {
        app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $request->table]);
        if ($request->pause != 0) {
            $pause = Pause::find($request->pause);
            $pause->end_pause = Carbon::now();
            $pause->save();
        } else {
            $pause = new Pause();
            $pause->order_id = $request->order;
            $pause->start_pause = Carbon::now();
            $pause->save();
        }
        return redirect('/open-table');
    }

    /*
     * закрытие заказа -обработка формы
     */
    public function orderBillClosedOrder(Request $request)
    {

        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $reserv = Reservation::find($request->idreserv);
        $reserv->booking_before = Carbon::now();
        $reserv->closed = 1;
        $reserv->sum_booking = $request->sum_booking;
        $reserv->min = $request->min;
        $reserv->save();

        $orderUpdate = Order::find($request->id);
        $orderUpdate->status = 1;
        $orderUpdate->info = $request->info;
        $orderUpdate->billing = $request->billing;
        $orderUpdate->amount = $request->priceAmount;

        if ($change && count($change) > 0) {
            $orderUpdate->changes_id = $change->id;
        }

        $orderUpdate->closed = Carbon::now();
        $orderUpdate->save();

        app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $orderUpdate->table_id]);

        $money = new Money;
        $money->sum_bil = $request->priceAmount;
        $money->order = $request->id;
        $money->user_id = Auth::user()->id;
        $money->smena = $orderUpdate->changes_id;
        $money->save();

        return redirect('/open-table')->with('status', 'Замовлення закрите!');
    }


}
