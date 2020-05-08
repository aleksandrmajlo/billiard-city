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



}