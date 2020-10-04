<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 31.10.2019
 * Time: 17:35
 */

namespace App\Services;

use Carbon\Carbon;

use App\Bar;
use App\Change;
use App\Customer;
use App\Order;
use App\Pause;
use App\Reservation;
use App\Tariff;

class OrderService
{
    // для незакритого заказа получить время
    static public function validateTime($order, $endTest = false)
    {
        $start = $order->start;
        $min = $order->table->min_min;
        if (empty($min)) {
            $min = 5;
        } else {
            $min = (int)$min;
        }
        $tz = config('app.timezone');

        $startCarbon = new Carbon($start, $tz);
        $startCarbonReturn = new Carbon($start, $tz);

        $dateEnd = Carbon::now($tz);

        $result = [
            'endDate' => $dateEnd,
            'endDateReal' => $dateEnd,
            'endDateBol' => false,
            'free' => false,  //заказ меньше 5 минут
            'startCarbon' => $startCarbonReturn,
            'setTariffDay' => false,// установить тариф принудительно для таких промежутков 23,00 и 23,45
        ];
        $result['Total_minutes'] = $result['minutes'] = (int)$startCarbon->diffInMinutes($dateEnd, false);
        //вычесть паузы
        $pauseMinutes = 0;
        if ($order->pauses) {
            foreach ($order->pauses as $pause) {
                $startPause = new Carbon($pause->start_pause, $tz);
                if (empty($pause->end_pause)) {
                    $endPause = Carbon::now($tz);
                } else {
                    $endPause = new Carbon($pause->end_pause, $tz);
                }
                $pauseMinutes += (int)$startPause->diffInMinutes($endPause, false);
            }
        }
        $result['pauseMinutes'] = $pauseMinutes;
        $result['minutes'] = $result['minutes'] - $pauseMinutes;
        if ($result['minutes'] <= $min) {
            $result['free'] = true;
        } elseif ($result['minutes'] <= 60) {
            $result['endDate'] = $startCarbon->addHour(1);
            // тут добавляем минуты паузы
            $result['endDate'] = $result['endDate']->addMinutes($pauseMinutes);
            if ($dateEnd->hour == 23 && $dateEnd->minute < 49) {
                $result['setTariffDay'] = true;
            }
            $result['endDateBol'] = true;
        }
        return $result;
    }

    // подсчет цены
    static public function getOrderProductPrice($order, $count, $countPause, $endValidate, $minStart = 0)
    {

        $priceTotal = 0;

        $priceOrderTotal = 0;
        $priceOrder = 0;
        $priceOrderWithout = 0;
        $priceProcentOrder = 0;
        $priceOrderDiscount = 0;
        $priceOrderMinutes = 0;

        $priceProduct = 0;
        $priceProductsTotal = 0;
        $priceProductsDiscount = 0;
        $priceProcentProducts = 0;


        /*
        * Пользователь если есть
        */
        $customer = null;
        if ($order->customer_id) {
            $customer = Customer::where('id', $order->customer_id)->first();
        }
        if (!$endValidate['free']) {
            //*********** Цена продуктов *********************
            if (!$order->bars->isEmpty()) {
                foreach ($order->bars as $k => $bar) {
                    $priceProduct += $bar->count * $bar->stock->price;
                }
                $priceProductsTotal = $priceProduct;
                if ($customer && $customer->skidka_bar) {
                    $priceProcentProducts = $customer->skidka_bar;
                    $priceProductsDiscount = self::roundFloat($priceProduct * $customer->skidka_bar / 100);
                    $priceProductsTotal = self::roundFloat($priceProduct - $priceProductsDiscount);

                }
            }
            // цена заказа
            if ($endValidate['setTariffDay'] && $endValidate['minutes'] < 60) {
                // если промежуток с 23,00  по 23,45
                $priceOrderTotal = $priceOrder = $minStart;
            } else {
                $priceOrderTotal = $priceOrder = self::roundFloat(array_sum($count));
            }
            // ******************пауза**************************
            // если есть пауза и цена заказа  больше минимальной
            if (!empty($countPause) && $priceOrderTotal > $minStart) {
                $priceOrderTotal = $priceOrder = self::roundFloat($priceOrder - array_sum($countPause));
            }

            //******************************** скидка ********************
            $skidkaAll = false;
            if ($customer && $customer->skidka == 100) {
                $skidkaAll = true;
                $priceOrderTotal = $priceOrder = $priceOrderDiscount = 0;
                $priceProcentOrder = 100;
            } elseif ($customer && $endValidate['minutes'] > 60 && $customer->skidka > 0) {
                // получить сумму от 60 минут
                $priceOrderWithout = $priceOrder - array_sum(array_slice($count, 0, 60));
                $priceProcentOrder = $customer->skidka;
                $priceOrderDiscount = self::roundFloat($priceOrderWithout * $customer->skidka / 100);
                $priceOrderTotal = self::roundFloat($priceOrder - $priceOrderDiscount);
            }
            // проверим на минимум еще раз
            if ($priceOrderTotal < $minStart && !$skidkaAll) {
                $priceOrderTotal = $priceOrder = $minStart;
            }

            $priceTotal = self::roundFloat($priceProductsTotal + $priceOrderTotal);
            $priceOrderMinutes = self::roundFloat(array_sum($count) / count($count));
        }

        return $relust = [
            'priceOrderTotal' => $priceOrderTotal,// сумма к оплате
            'priceOrder' => $priceOrder,          // сумма за заказ стода без скидок
            'priceOrderWithout' => $priceOrderWithout,// сумма от какой скидка
            'priceOrderDiscount' => $priceOrderDiscount,// сумма  скидки
            'priceProcentOrder' => $priceProcentOrder,// процент  скидки
            'priceOrderMinutes' => $priceOrderMinutes,// цена за минуту

            'priceProductsTotal' => $priceProductsTotal, // цена продуктов к оплате
            'priceProduct' => $priceProduct, // цена продуктов
            'priceProductsDiscount' => $priceProductsDiscount,// скидки на продукты
            'priceProcentProducts' => $priceProcentProducts,// процент на продукты

            'priceTotal' => $priceTotal,//цена за весь заказ
            'customer' => $customer
        ];
    }

    // аякс в списке продуктов и вообще это используем!!!!!!
    static public function priceOrder($id)
    {
        $tz = config('app.timezone');
        $order = Order::where('id', '=', $id)->first();
        $s1 = $order->start;
        $endValidate = self::validateTime($order);
        $s2 = $endValidate['endDate'];
        $s1 = $endValidate['startCarbon'];

        $startDate = $s1->format("d-m-Y");
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
        $sum[] = 1;
        $count = [];
        // массивы цен перебираем
        $k = 0;
        $minStart = [];
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
                    $minStart[] = $minpay;
                    $sum[] = 1;
                    $count[] = 1 * round($minpay / 60, 2);
                }
            }
            $k++;
        }
        $minStartValue = min($minStart);
        // паузы
        $pauses = Pause::where('order_id', $order->id)->get();
        $countPause = [];
        $json_pauses = [];
        $activePause = false;

        foreach ($pauses as $pause) {
            if (isset($pause->end_pause)) {
                $minpause_this = floor((strtotime($pause->end_pause) - strtotime($pause->start_pause)) / 60);
                $minpause[] = $minpause_this;
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

                $json_pauses[] = [
                    'startM' => $startM,
                    'endM' => $endM,
                    'minpause_this' => $minpause_this
                ];
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
                $json_pauses[] = [
                    'startM' => $startM,
                    'minpause_this' => false
                ];
                $activePause = $pause->id;
            }
        }
        /*
         * цена продукта и стола  в одном месте
         */
        $PriceResults = OrderService::getOrderProductPrice($order, $count, $countPause, $endValidate, $minStartValue);
        if (is_null($PriceResults['customer'])) {
            $customer = ['name' => 'Гість'];
        } else {
            $customer = $PriceResults['customer'];
        }

        return [
            'priceOrder' => $PriceResults['priceOrder'],
            'priceOrderDiscount' => $PriceResults['priceOrderDiscount'],
            'priceProcentOrder' => $PriceResults['priceProcentOrder'],
            'priceOrderTotal' => $PriceResults['priceTotal'],
            'startDate' => $startDate,
            'startM' => $startM,
            'customer' => $customer,
            'minutes' => $endValidate['minutes'],
            'priceOrderMinutes' => $PriceResults['priceOrderMinutes'],
            'pauses' => $json_pauses,
            'activePause' => $activePause
        ];

    }

    /*
     * заокругление
     */
    static function roundFloat($float)
    {
        return round($float, 2);
    }

}