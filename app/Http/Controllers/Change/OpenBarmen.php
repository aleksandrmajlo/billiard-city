<?php


namespace App\Http\Controllers\Change;

use App\Change;
use App\Http\Controllers\Controller;

use App\CategoryStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Stock;
use Carbon\Carbon;
use App\Acts\Act;
use App\Bars\Ingredient;

use App\Bars\Kofeinyiapparat;
use App\Services\ActService;
use App\Order;
use App\Services\OrderService;
use App\Services\Kofeinyiapparatcount;
use Illuminate\Support\Facades\DB;


class OpenBarmen extends Controller
{
    public function open_change(Request $request)
    {
        if ($request->has('user_id')) {
            if (Auth::user()->hasRole('manager')) {
                return view('order.open_order_manager', [
                    'user_id' => $request->user_id
                ]);
            } elseif (Auth::user()->hasRole('barmen')) {
                return view('change.open_barmen');
            } else {
                abort('404');
            }
        } else {
            abort('404');
        }
    }

    //cамо открытие  смены
    public function Submit(Request $request)
    {
        if ($request->has('products')) {

            $user_id = Auth::user()->id;
            // это зачем непонятно
            // перестраховка от
            $changes = Change::where('stop', '=', null)->get();
            foreach ($changes as $change) {
                $userChange = DB::select('select * from users_roles where user_id = ?', array($change->user_id));
                if ($userChange[0]->role_id == 3) {
                    $change = Change::find($change->id);
                    $change->stop = Carbon::now()->format('Y-m-d H:i:s');
                    $change->save();
                }
            }

            $summa=0;
            if(!empty($request->summa)) $summa=$request->summa;
            // код открытие смены
            $change = new Change;
            $change->user_id = $user_id;
            $change->summa_start = $summa;
            $change->summa_end = null;
            $change->start = Carbon::now()->format('Y-m-d H:i:s');
            $change->stop = null;
            $change->save();

            // создаем акт
            $act = new Act;
            $act->user_id = $user_id;
            $act->change_id = $change->id;
            $act->save();

            // продукты
            $products = $request->products;
            $ingredients = [];
            $stocks = [];
            foreach ($products as $k => $product_cat) {
                if ($k == "ingredients") {
                    foreach ($product_cat as $product) {
                        $ing = Ingredient::find($product['id']);
                        if (floatval($product['result']) !== floatval($ing->count)) {
                            $ingredients[] = [
                                'thisCount' => round($ing->count, 2),
                                'oldCount' => round($product['result'], 2),
                                'title' => $ing->title
                            ];
                        }
                        $act->ingredients()->save($ing, ['count' => $product['result']]);
                    }
                } else {
                    foreach ($product_cat as $product) {
                        $st = Stock::find($product['id']);
                        if (floatval($product['result']) !== floatval($st->count)) {
                            $stocks[] = [
                                'thisCount' => round($st->count, 2),
                                'oldCount' => round($product['result'], 2),
                                'title' => $st->title
                            ];
                        }
                        $act->stocks()->save($st, ['count' => $product['result']]);
                    }
                }
            }

            ActService::UpdateStockIngr($act->id);

            $kofeinyiapparat = new Kofeinyiapparat();
            $kofeinyiapparat->count = $request->kofeinyi_apparat;
            $kofeinyiapparat->act_id = $act->id;
            $kofeinyiapparat->save();
            //обновление общего кол-ва кофе
            Kofeinyiapparatcount::add($request->kofeinyi_apparat);

            // создание акта расходные накладные
            ActService::CreateConsumableinvoice($change);

            // если  ecть расхождение то отправляем письмо
            if (!empty($ingredients) || empty(!$stocks)) {
                ActService::CreateValidate($ingredients, $stocks, $act->id, $change->id);
            }

            return response()->json([
                'success' => true,
                'url' => env('APP_URL'),
            ], 200);

        }
    }
}