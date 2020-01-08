<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 18.11.2019
 * Time: 14:02
 */

namespace App\Services;

use App\Acts\Act;
use App\Acts\Consumableinvoice;
Use App\Bars\Ingredient;
use App\Mail\AdminOrder;
use App\Mail\AdmincloseOrder;

use App\Stock;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;


class ActService
{

    // обновление продуктов и ингадиентов
    static public function UpdateStockIngr($act_id)
    {


        $act = Act::find($act_id);
        if ($act->ingredients) {
            foreach ($act->ingredients as $ingredient) {
                $ing = Ingredient::find($ingredient->id);
                $ing->count = $ingredient->pivot->count;
                $ing->save();
            }
        }
        if ($act->stocks) {
            foreach ($act->stocks as $stock) {
                $st = Stock::find($stock->id);
                $st->count = $stock->pivot->count;
                $st->save();
            }
        }
        self::UpdateProductCount();
    }

    // создание акта расходные накладные
    static public function CreateConsumableinvoice($change)
    {

        $count = 0;
        if ($change->orders) {
            foreach ($change->orders as $order) {
                if ($order->bars) {
                    foreach ($order->bars as $bar) {
                        $count += $bar->count;
                    }
                }
            }
        }
        $consumableinvoice = new Consumableinvoice;
        $consumableinvoice->summa = $change->summa_end - $change->summa_start;
        $consumableinvoice->change_id = $change->id;
        $consumableinvoice->user_id = $change->user_id;
        $consumableinvoice->count = $count;
        $consumableinvoice->save();
    }


    // валидация при открытии смены
    static public function CreateValidate($request, $act_id,$change_id)
    {
        // получаем последний акт
        $act = Act::where('id', '!=', $act_id)->orderBy('created_at', 'DESC')->first();
        $actOldId = null;
        if ($act) {
            $actOldId = $act->id;
        }
        $results = [
            'ingredients' => [],
            'stocks' => [],
            'act_id' => $act_id,
            'old_act_id' => $actOldId
        ];
        if ($act) {
            foreach ($act->stocks as $stock) {
                $ind = array_search($stock->id, $request['stocks']);
                $thisCount = (float)$request['count_stocks'][$ind];
                if ((float)$stock->pivot->count !== $thisCount) {
                    $results['stocks'][] = [
                        'thisCount' => round($thisCount,2),
                        'oldCount' => round( $stock->pivot->count,2),
                        'title' => $stock->title
                    ];
                }
            }
            foreach ($act->ingredients as $ingredient) {
                $ind = array_search($ingredient->id, $request['ingredients']);
                $thisCount = (float)$request['count_ingredients'][$ind];
                if ((float)$ingredient->pivot->count !== $thisCount) {
                    $results['ingredients'][] = [
                        'thisCount' => round($thisCount,2),
                        'oldCount' => round($ingredient->pivot->count,2),
                        'title' => $ingredient->title
                    ];
                }
            }
        }

        if (!empty($results['ingredients']) || !empty($results['stocks'])) {
            $admin=Auth::user();
            $results['admin']=$admin->name;
            $results['change_id']=$change_id;

            $MAIL_TO_ADMIN = env('MAIL_TO_ADMIN');
            Mail::to($MAIL_TO_ADMIN)->send(new AdminOrder($results));
            Mail::to('alekslv74@yandex.ua')->send(new AdminOrder($results));
            if ("http://billiard-city.local" !== env('APP_URL')) {
                Mail::to('alex.stepanov100@gmail.com')->send(new AdminOrder($results));
            }
        }
    }

    // отправляем письмо если смена принудительно закрыта
    static public function CloseEmailForced($request, $act_id,$change_id)
    {
        $results = [
            'ingredients' => [],
            'stocks' => [],
            'coffee' => false,
            'act_id' => $act_id
        ];
        $kofeinyi_apparat_category_id = config('category.kofeinyi_apparat_category_id');
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            if ($stock->categorySee->id == $kofeinyi_apparat_category_id) continue;
            if (count($stock->ingredients) < 1) {
                $ind = array_search($stock->id, $request['stocks']);
                $thisCount = (float)$request['count_stocks'][$ind];
                if ((float)$stock->count !== $thisCount) {
                    $results['stocks'][] = [
                        'thisCount' => round($stock->count,2),
                        'oldCount' => round($thisCount,2),
                        'title' => $stock->title
                    ];
                }
            }
        }
        $ingredients = Ingredient::all();
        foreach ($ingredients as $ingredient) {
            $ind = array_search($ingredient->id, $request['ingredients']);
            $thisCount = (float)$request['count_ingredients'][$ind];
            if ((float)$ingredient->count !== $thisCount) {
                $results['ingredients'][] = [
                    'thisCount' => round($ingredient->count,2),
                    'oldCount' => round($thisCount,2),
                    'title' => $ingredient->title
                ];
            }
        }
        $kavaCount = DB::table('count_this_Kofeinyiapparat')
            ->where('id', 1)
            ->first();
        $kavaRequest = $request['kofeinyi_apparat'];
        if ((int)$kavaCount->count != (int)$kavaRequest) {
            $results['coffee'] = [
                'thisCount' => $kavaCount->count,
                'oldCount' => $kavaRequest,
                'title' => 'Кавовий апарат'
            ];
        }

        $admin=Auth::user();
        $results['admin']=$admin->name;
        $results['change_id']=$change_id;

        $MAIL_TO_ADMIN = env('MAIL_TO_ADMIN');
        Mail::to($MAIL_TO_ADMIN)->send(new AdmincloseOrder($results));
        Mail::to('alekslv74@yandex.ua')->send(new AdmincloseOrder($results));
        if ("http://billiard-city.local" !== env('APP_URL')) {
            Mail::to('alex.stepanov100@gmail.com')->send(new AdmincloseOrder($results));
        }
    }

    // валидация при закрытии смены
    static public function CloseValidate($request)
    {
        $results = [
            'ingredients' => [],
            'stocks' => [],
            'coffee' => false
        ];

        $kofeinyi_apparat_category_id = config('category.kofeinyi_apparat_category_id');
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            // количество категории кава не уменьшаем
            if ($stock->categorySee->id == $kofeinyi_apparat_category_id) continue;
            if (count($stock->ingredients) < 1) {
                $ind = array_search($stock->id, $request['stocks']);
                $thisCount = (float)$request['count_stocks'][$ind];
                if ((float)$stock->count !== $thisCount) {
                    $results['stocks'][] = [
                        'thisCount' => $thisCount,
                        'oldCount' => $stock->count,
                        'title' => $stock->title
                    ];
                }
            }
        }
        $ingredients = Ingredient::all();
        foreach ($ingredients as $ingredient) {
            $ind = array_search($ingredient->id, $request['ingredients']);
            $thisCount = (float)$request['count_ingredients'][$ind];
            if ((float)$ingredient->count !== $thisCount) {
                $results['ingredients'][] = [
                    'thisCount' => $thisCount,
                    'oldCount' => $ingredient->count,
                    'title' => $ingredient->title
                ];
            }
        }

        $kavaCount = DB::table('count_this_Kofeinyiapparat')
            ->where('id', 1)
            ->first();
        $kavaRequest = $request['kofeinyi_apparat'];
        if ((int)$kavaCount->count != (int)$kavaRequest) {
            $results['coffee'] = ['Кавовий апарат', $kavaCount->count, $kavaRequest];
        }
        return $results;
    }


    // обновление продукта с инградиентами
    static public function UpdateProductCount()
    {
        // обновить количество товаров с ингадиентами
        $stocks = Stock::all();
        foreach ($stocks as $stock) {
            if (count($stock->ingredients) > 0) {
                $ar = [];
                foreach ($stock->ingredients as $ingredient) {
                    $ar[] = (int)floor($ingredient->count / $ingredient->pivot->count);
                }
                $min = min($ar);
                if ($min > 0) {
                    $stock->count = $min;
                } else {
                    $stock->count = 0;
                }
                $stock->save();
            }
        }

    }

}