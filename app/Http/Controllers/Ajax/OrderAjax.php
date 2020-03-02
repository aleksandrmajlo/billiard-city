<?php

namespace App\Http\Controllers\Ajax;

use App\Bars\Kofeinyiapparat;
use App\Change;
use App\Services\ActService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Order;
use App\Services\OrderService;
use Carbon\Carbon;
use App\Acts\Act;
use App\Bars\Ingredient;
use App\Stock;
use App\Services\Kofeinyiapparatcount;


class OrderAjax extends Controller
{
    public function priceorder(Request $request)
    {
        $results = [
            'priceOrder' => 0,
            'priceOrderDiscount' => 0,
            'priceOrderTotal' => 0,
        ];
        if ($request->has('order_id')) {
            $order_id = $request->input('order_id');
            $results = OrderService::priceOrder($order_id);

        }
        return response()->json([
            'success' => true,
            'results' => $results,
        ], 200);

    }

    // закрытие смены барменом
    public function orderClose(Request $request)
    {

        $change = Change::find($request->id);
        $change->summa_end = $request->summa_end;
        $change->stop = Carbon::now()->format('Y-m-d H:i:s');
        $change->save();
        /*
        * создаем акты ********************************
        */
        $act=new Act;
        $act->user_id=$change->user_id;
        $act->change_id=$change->id;
        $act->type=2;
        $act->save();


        if($request->has('ingredients')){
            foreach ($request->ingredients as $k=>$ingredient){
                $ing=Ingredient::find($ingredient);
                $act->ingredients()->save($ing,['count'=>$request->count_ingredients[$k]]);
            }
        }
        if($request->has('stocks')){
            foreach ($request->stocks as $k=>$stock){
                $st=Stock::find($stock);
                $act->stocks()->save($st,['count'=>$request->count_stocks[$k]]);
            }
        }

        // если принудительно то отправлем письмо
        if($request->has('Forced')){
            ActService::CloseEmailForced($request->all(),$act->id,$change->id);
        }
        ActService::UpdateStockIngr($act->id);

        $kofeinyiapparat=new Kofeinyiapparat();
        $kofeinyiapparat->count=$request->kofeinyi_apparat;
        $kofeinyiapparat->act_id=$act->id;
        $kofeinyiapparat->save();
        //обновление общего кол-ва кофе
        Kofeinyiapparatcount::add($request->kofeinyi_apparat);
        return response()->json([
            'success' => true,
            'url'  =>env('APP_URL'),
        ], 200);

    }
    // валидация при закрытии смены барменом
    public function orderCloseValidate(Request $request)
    {
        $results = ActService::CloseValidate($request->all());
        if (!empty($results['ingredients']) || !empty($results['stocks'])||!empty($results['coffee'])) {
            return response()->json([
                'success' => false,
                'results' => $results,
            ], 200);

        } else {
            return response()->json([
                'success' => true,
            ], 200);
        }
    }
}