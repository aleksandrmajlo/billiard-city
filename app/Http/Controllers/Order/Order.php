<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 17.12.2019
 * Time: 19:56
 */

namespace App\Http\Controllers\Order;

use App\Bars\Ingredient;
use App\Change;
use App\Customer;
use App\Money;
use App\Reserve;
use App\Services\ActService;
use App\Services\Kofeinyiapparatcount;
use App\Services\OrderService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Stock;
use Illuminate\Support\Facades\Auth;
use App\Reservation;


use Illuminate\Support\Facades\DB;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;


class Order extends Controller
{

    public function CategoriesGet()
    {
        $categoryStocks = \App\CategoryStock::all();
        return response()->json([
            'success' => true,
            'categories' => $categoryStocks,
        ], 200);
    }

    public function ProductCategoryGet(Request $request)
    {
        $cat_id = $request->cat_id;
        $stocks = Stock::where('published', 1)
            ->where('categorystock_id', $cat_id)
            ->orderBy('title', 'ASC')
            ->get();
        $results = [];
        foreach ($stocks as $stock) {
            if ($stock->count > 0 || $stock->unlimited == 1) {
                $results[] = [
                    'title' => $stock->title,
                    'id' => intval($stock->id),
                    'price' => $stock->price,
                    'image' => $stock->image,
                    'count' => $stock->count,
                    'unlimited' => $stock->unlimited
                ];
            }
        }
        return response()->json([
            'success' => true,
            'products' => $results,
        ], 200);

    }

    public function SearchProduts(Request $request)
    {
        $val = $request->val;
        $stocks = Stock::query()
            ->where('title', 'LIKE', "%{$val}%")
            ->get();
        $results = [];
        foreach ($stocks as $stock) {
            if ($stock->count > 0 || $stock->unlimited == 1) {
                $results[] = [
                    'title' => $stock->title,
                    'id' => intval($stock->id),
                    'price' => $stock->price,
                    'image' => $stock->image,
                    'count' => $stock->count,
                    'unlimited' => $stock->unlimited
                ];
            }
        }
        return response()->json([
            'success' => true,
            'products' => $results,
        ], 200);

    }

    //оплата заказа бара
    /*
     * billing  тип оплат (1 наличкой)
     * info   коммент
     */
    public function Pay(Request $request)
    {

        if (\Auth::user()) {
            $user = \Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }

        $orderUpdate = \App\Order::find($request->order_id);
        $total = self::AddProduct($orderUpdate, $request->cart, $request->skidka);
        // полная сумма
        $orderUpdate->fullamount = $total['fullamount'];
        // cумма со скидкой
        $orderUpdate->amount = $total['total'];
        // процент скидки
        $orderUpdate->procent = (int)$request->skidka;
        if ($request->user) {
            $orderUpdate->customer_id = $request->user['value'];
        }

        $orderUpdate->status = 1;
        $orderUpdate->billing = $request->billing;
        if ($change) {
            $orderUpdate->changes_id = $change->id;
        }
        $orderUpdate->reservation_id = 0;
        $orderUpdate->closed = Carbon::now();
        $orderUpdate->save();

        //**************************************************** ?????????????????????????????????????
        $money = Money::where('order', $orderUpdate->id)->first();
        if ($money) {
            $money->sum_bar = $orderUpdate->amount;
            $money->save();
        }
        else {
            $money = new Money;
            $money->sum_bar = $orderUpdate->amount;;
            $money->order = $orderUpdate->id;
            $money->user_id = Auth::user()->id;
            $money->smena = $orderUpdate->changes_id;
            $money->save();
        }
        // печать !!!!!!!!!!!!!!!!!
        if (1 == intval($request->print)) {
            self::getOrderPrint($orderUpdate->id);
        }
        // очищаем резерв
        Reserve::where('order_id', $orderUpdate->id)->delete();
        return response()->json([
            'success' => true,
        ], 200);
    }

    // оформление открытого стола
    public function PayTable(Request $request)
    {
        $change = null;
        if (Auth::user()) {
            $user = Auth::user()->id;
            $change = Change::where('user_id', $user)
                ->where('stop', null)
                ->first();
        }
        // проверка на паузы
        // если активна то выключить свет
        $pauses = \App\Pause::where('order_id', $request->order_id)->get();
        $pause_active=false;
        if ($pauses) {
            foreach ($pauses as $pause) {
                if (is_null($pause->end_pause)) {
                    $pause_active=true;
                }
            }
        }
        //цена в одном месте
        $prices = OrderService::priceOrder($request->order_id);

        $orderUpdate = \App\Order::find($request->order_id);
        $orderUpdate->status = 1;
        $orderUpdate->billing = $request->billing;

        $orderUpdate->amount = $prices['priceOrderTotal'];
        $orderUpdate->procent = $prices['priceProcentOrder'];
        $orderUpdate->fullamount = $prices['priceOrder'];

        if ($change) {
            $orderUpdate->changes_id = $change->id;
        }
        $orderUpdate->closed = Carbon::now();
        $orderUpdate->save();

        if(!$pause_active){
            // выключаем свет
            try {
                app()->call('App\Http\Controllers\SocketController@turnOn', ['id_table' => $orderUpdate->table_id]);
            }catch (Exception $e){

            }
        }

        $money = new Money;
        $money->sum_bil = $prices['priceOrderTotal'];
        $money->order = $request->order_id;
        $money->user_id = Auth::user()->id;
        $money->smena = $orderUpdate->changes_id;
        $money->save();

        // резерв
        $reserv = Reservation::find($orderUpdate->reservation_id);
        if ($reserv) {
            $reserv->booking_before = Carbon::now();
            $reserv->closed = 1;
            $reserv->sum_booking = $prices['priceOrderTotal'];
            $reserv->min = $prices['minutes'];
            $reserv->save();
        }
        // печать !!!!!!!!!!!!!!!!!
        if (1 == intval($request->print)) {
            self::getOrderPrint($orderUpdate->id);
        }
        return response()->json([
            'success' => true,
        ], 200);

    }

    // добавить продукты в заказ
    public static function AddProduct($order, $products, $skidka = false)
    {
        // удалим старые записи
        if ($order->bars) {
            foreach ($order->bars as $bar) {
                $bar->delete();
            }
        }
        $total = 0;
        $fullamount=0;
        foreach ($products as $product) {
            $bar = new \App\Bar();
            $bar->product_id = $product['id'];
            $bar->order_id = $order->id;
            $bar->count = $product['count'];
            $bar->save();
            $total += $product['price'] * $product['count'];
        }
        $fullamount=$total;
        if ($skidka) {
            $sumSkidka = $skidka * $total / 100;
            $total = $total - $sumSkidka;
        }
        // обновляем кол-во продуктов с инградиентами  ??????????????????
        ActService::UpdateProductCount();

        $total = OrderService::roundFloat($total);
        return ['fullamount'=>$fullamount,'total'=>$total];
    }

    // получить всех клиентов
    public function GetUsers(Request $request)
    {
        $customers = \App\Customer::all();
        $results = [];
        foreach ($customers as $customer) {

            $skidka_bar = 0;
            if ($customer->skidka_bar) {
                $skidka_bar = $customer->skidka_bar;
            }

            $skidka = 0;
            if ($customer->skidka) {
                $skidka = $customer->skidka;
            }
            $text=$customer->phone;
            if($request->has('name')){
                $text=$customer->fullname.' ('.$customer->phone.')';
            }
            $results[] = [
                'text' => $text,
                'value' => $customer->id,
                'skidka_bar' => $skidka_bar,
                'skidka' => $skidka
            ];
        }
        return response()->json([
            'success' => true,
            'users' => $results
        ], 200);

    }

    //отправить на печать при нажатии кнопки
    public function SendPrint(Request $request)
    {
        $order_id = $request->order_id;
        self::getOrderPrint($order_id);
        return response()->json([
            'success' => true,
        ], 200);

    }

    // получить заказ для печати
    public static function getOrderPrint($order_id)
    {
        $orderData = [];
        $order = \App\Order::find($order_id);
        $orderData['id'] = $order->id;
        $tz = config('app.timezone');

        $orderData['start'] = Carbon::parse($order->start)->format('Y-m-d H:i');
        if ($order->closed) {
            $orderData['end'] = Carbon::parse($order->closed)->format('Y-m-d H:i');
        } else {
            $orderData['end'] = Carbon::now()->format('Y-m-d H:i');
        }

        $orderData['products'] = '';
        if ($order->type_bar == 1) {
            if ($order->bars) {
                foreach ($order->bars as $bar) {
                    $orderData['products'] .= $bar->stock->title . " \n" . $bar->stock->price . " * " . $bar->count . " = " . round($bar->stock->price * $bar->count, 2) . "\n";
                }
            }
            if ($order->customer_id) {
                $customer = \App\Customer::find($order->customer_id);
                $orderData['customer'] = $customer->fullname;
                $orderData['discount'] = $customer->skidka_bar;
            } else {
                $orderData['customer'] = "Гість";
                $orderData['discount'] = "0";
            }
        }
        else {
            $startCarbon = new Carbon($order->start, $tz);
            $endCarbon = new Carbon($order->closed, $tz);
            $minutes = (int)$startCarbon->diffInMinutes($endCarbon, false);

            $orderData['products'] .= '' . $order->table->title . " \n з " . $startCarbon->format('Y-m-d H:i') . " \n по " . $endCarbon->format('Y-m-d H:i') . "\n";
//            $orderData['products'] .= $minutes . "хв. = " . $order->barprice . "\n";
            $orderData['products'] .= $minutes . "хв. = " . $order->amount . "\n";
            // паузы
            $pauses = \App\Pause::where('order_id', $order->id)->get();
            if ($pauses) {
                foreach ($pauses as $pause) {
                    if (isset($pause->end_pause)) {
                        $startPause = new Carbon($pause->start_pause, $tz);
                        $endPause = new Carbon($pause->end_pause, $tz);
                        $pauseMinutes = (int)$startPause->diffInMinutes($endPause, false);
                        $orderData['products'] .= '' . $order->table->title . " - пауза\n з " . Carbon::parse($pause->start_pause)->format('Y-m-d H:i') . " по " . Carbon::parse($pause->end_pause)->format('Y-m-d h:i') . "\n";
                        $orderData['products'] .= $pauseMinutes . " хв. =  0\n";
                    }else{
                        $startPause = new Carbon($pause->start_pause, $tz);
                        $pauseMinutes = (int)$startPause->diffInMinutes($endCarbon, false);
                        $orderData['products'] .= '' . $order->table->title . " - пауза\n з " . Carbon::parse($pause->start_pause)->format('Y-m-d H:i') . " по " . $endCarbon->format('Y-m-d H:i') . "\n";
                        $orderData['products'] .= $pauseMinutes . " хв. =  0\n";
                    }
                }
            }
            if ($order->customer_id) {
                $customer = \App\Customer::find($order->customer_id);
                $orderData['customer'] = $customer->fullname;
                $orderData['discount'] = $customer->skidka;
            } else {
                $orderData['customer'] = "Гість";
                $orderData['discount'] = "0";
            }
        }

//        $orderData['total'] = $order->barprice;
        $orderData['total'] = $order->amount;
        self::OrderPrint($orderData);
    }

    // печать заказа
    static public function OrderPrint($orderData, $close = true)
    {
        $pr = "**************\n";

        // /*
        $text = "Більярд Сіті\n";
        $text .= "+38 (067) 574 89 89 \n";
        $text .= "вул. Героїв УПА 77\n";
        $text .= "billiard-city.com\n";

        $text .= $pr;
        $text .= "Замовлення #" . $orderData['id'] . "\n";
        $text .= "Початок: " . $orderData['start'] . "\n";
        $text .= "Кінець: " . $orderData['end'] . "\n";
        $text .= $pr;
        $text .= $orderData['products'];
        $text .= $pr;
        $text .= "Клієнт: " . $orderData['customer'] . "\n";
        $text .= "Знижка: " . $orderData['discount'] . "%\n";
        $text .= "Разом: =" . $orderData['total'] . "\n";
        $text .= "Готівкою: " . $orderData['total'] . "\n";
        $text .= $pr;

        $text .= "\n";
        $text .= "\n";
        $text .= "\n";

        $text = str_replace("і", "i", $text);
        $text = str_replace("І", "I", $text);

        $printsetting = \App\Printsetting::find(1);

        $connector = new NetworkPrintConnector($printsetting->ip, intval($printsetting->port));
        $printer = new Printer($connector);
        $printer->text($text);
        $printer->cut();
        $printer->close();

    }

    // получить продукты в заказе
    public function getOrder(Request $request)
    {

        $order_id = $request->order_id;
        $order = \App\Order::find($order_id);
        $cart = [];
        $skidka = 0;
        $user = [
            'text' => "",
            'value' => "",
            'skidka_bar' => '',
            'skidka' => '',
        ];

        if ($order->customer_id) {
            $customer = Customer::find($order->customer_id);
            $user['text'] = $customer->phone;
            if ($order->type_bar == 1) {
                $skidka = $user['skidka_bar'] = $customer->skidka_bar;
            } else {
                $skidka = $user['skidka'] = $customer->skidka;
            }
            $user['value'] = $customer->id;
        }
        if ($order->bars) {
            foreach ($order->bars as $bar) {
                $stock = Stock::find($bar->product_id);
                $cart[] = [
                    "title" => $stock->title,
                    "id" => intval($bar->product_id),
                    "image" => $stock->image,
                    "price" => floatval($stock->price),
                    "count" => floatval($bar->count),
                    "total" => $stock->price * $bar->count,
                    "isOpen" => false,
                    "unlimited" => $stock->unlimited,
                    "countThis" => floatval($stock->count)
                ];
            }
        }
        if ($order->type_bar == 1) {
            $typeOrder = "type_bar";
        } else {
            $typeOrder = "type_billiards";
        }
        return response()->json([
            'success' => true,
            'cart' => $cart,
            'user' => $user,
            'skidka' => $skidka,
            'typeOrder' => $typeOrder,
            'info' => $order->info

        ], 200);
    }

    // добавить в резерв
    public function Reserve(Request $request)
    {

        $order_id = $request->order_id;
        $cart = $request->cart;
        // ********************************************************8
        // востанавливаем кол-во и очищаем
        $kofeinyi_apparat_category_id = config('category.kofeinyi_apparat_category_id');
        $kofeinyi_apparat_count = 0;
        $reserves = Reserve::where('order_id', $order_id)->get();
        foreach ($reserves as $reserve) {
            $stock = Stock::find($reserve->stock_id);
            if ($stock->categorySee->id == $kofeinyi_apparat_category_id) {
                $kofeinyi_apparat_count += $reserve["count"];
                // если категория кофе имеет  ингадиент
                if (count($stock->ingredients) > 0) {
                    foreach ($stock->ingredients as $ingredient) {
                        $ing = Ingredient::find($ingredient->id);
                        $newCount = $ing->count + ($ingredient->pivot->count * $reserve["count"]);
                        $ing->count = $newCount;
                        $ing->save();
                    }
                }
            } //  кол-во
            elseif (count($stock->ingredients) > 0) {
                foreach ($stock->ingredients as $ingredient) {
                    $ing = Ingredient::find($ingredient->id);
                    $newCount = $ing->count + ($ingredient->pivot->count * $reserve["count"]);
                    $ing->count = $newCount;
                    $ing->save();
                }

            } else {
                $newCount = $stock->count + $reserve["count"];
                $stock->count = $newCount;
                $stock->save();
            }
        }
        // кофе уменьшаем кол-во чашек
        Kofeinyiapparatcount::minusOrder((int)$kofeinyi_apparat_count);

        Reserve::where('order_id', $order_id)->delete();
        //********************************************************************

        // записуем наново продукты в резерв
        if ($cart && !empty($cart)) {
            $kofeinyi_apparat_count = 0;
            // уменьшаем кол-во реальное
            foreach ($cart as $k => $product) {
                $reserve = new Reserve;
                $reserve->order_id = $order_id;
                $reserve->stock_id = $product['id'];
                $reserve->count = $product['count'];
                $reserve->save();

                $stock = Stock::find($product['id']);
                // количество категории кава не уменьшаем
                if ($stock->categorySee->id == $kofeinyi_apparat_category_id) {
                    $kofeinyi_apparat_count += $product["count"];
                    if (count($stock->ingredients) > 0) {
                        foreach ($stock->ingredients as $ingredient) {
                            $newCount = $ingredient->count - ($ingredient->pivot->count * $product["count"]);
                            $ing = Ingredient::find($ingredient->id);
                            $newCount = $ing->count - ($ingredient->pivot->count * $product["count"]);
                            $ing->count = $newCount;
                            $ing->save();
                        }
                    }
                } //  кол-во -
                elseif (count($stock->ingredients) > 0) {
                    foreach ($stock->ingredients as $ingredient) {
                        $newCount = $ingredient->count - ($ingredient->pivot->count * $product["count"]);
                        $ing = Ingredient::find($ingredient->id);
                        $newCount = $ing->count - ($ingredient->pivot->count * $product["count"]);
                        $ing->count = $newCount;
                        $ing->save();
                    }
                } else {
                    $newCount = $stock->count - $product["count"];
                    $stock->count = $newCount;
                    $stock->save();
                }
            }
            Kofeinyiapparatcount::addOrder((int)$kofeinyi_apparat_count);
        }

        // обновляем продукты в заказе....
        $order = \App\Order::find($order_id);
        $total = self::AddProduct($order, $request->cart, $request->skidka);

        // полная сумма
        $order->fullamount = $total['fullamount'];
        // cумма со скидкой
        $order->amount = $total['total'];
        // процент скидки
        $order->procent = (int)$request->skidka;

        if ($request->user) {
            $order->customer_id = $request->user['value'];
        }
        $order->save();
        // обновляем кол-во продуктов с инградиентами
        ActService::UpdateProductCount();

        return response()->json([
            'success' => true,
        ], 200);
    }

    // запись инфы
    public function setInfo(Request $request)
    {
        $order_id = $request->order_id;
        $order = \App\Order::find($order_id);
        $order->info = $request->info;
        $order->save();
        return response()->json([
            'success' => true,
        ], 200);
    }

    // для тєста
    public function printTest()
    {
        self::getOrderPrint(4503);
    }


}