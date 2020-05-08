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

class CloseBarmen extends Controller
{
    public function close_change(Request $request)
    {
        if ($request->has('id')) {
            $change = \App\Change::find($request->id);
            if (!is_null($change->stop)) {
                return redirect('/')->with('status', 'Cмена уже закрыта');
            }
            if (Auth::user()->hasRole('manager')) {
                return view('order.close_order_manager', [
                    'id' => $request->id
                ]);
            } elseif (Auth::user()->hasRole('barmen')) {
                return view('change.close_barmen');
            } else {
                abort('404');
            }

        } else {
            abort('404');
        }

    }

    public function category()
    {
        $products = [
            'ingredients' => \App\Bars\Ingredient::orderBy('title')->get()
        ];
        $kofeinyi_apparat_category_id = config('category.kofeinyi_apparat_category_id');
        $cats = CategoryStock::whereNotIn('id', [$kofeinyi_apparat_category_id])->get();
        foreach ($cats as $cat) {
                $products[$cat->id] = Stock::where('categorystock_id', $cat->id)->doesntHave('ingredients')->select('id', 'title', 'price', 'count')->get();
        }

        $summaChange = 0;
        $ChangeId = false;
        $openChange = Change::where('stop', '=', null)
            ->where('user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($openChange) {
            $summaChange = $openChange->total;
            $ChangeId = $openChange->id;
        }
        $kavaCount=Kofeinyiapparatcount::get();
        return response()->json([
            'summaChange' => $summaChange,
            'ChangeId' => $ChangeId,
            'products' => $products,
            'cats' => $cats,
            'kavaCount'=>$kavaCount,
            'success' => true,
        ], 200);
    }

    //cамо закрытие смены
    public function Submit(Request $request)
    {
        if ($request->has('products')) {

            $change = Change::find($request->ChangeId);
            $change->summa_end = $request->summa;
            $change->stop = Carbon::now()->format('Y-m-d H:i:s');
            $change->save();
            /*
            * создаем акты ********************************
            */
            $act = new Act;
            $act->user_id = $change->user_id;
            $act->change_id = $change->id;
            $act->type = 2;
            $act->save();


            $products = $request->products;
            $ingredients = [];
            $stocks=[];
            foreach ($products as $k => $product_cat) {
                if ($k == "ingredients") {
                    foreach ($product_cat as $product) {
                        $ing = Ingredient::find($product['id']);
                        if(floatval($product['result'])!==floatval($ing->count)){
                            $ingredients[]=[
                                'thisCount' => round($ing->count,2),
                                'oldCount' => round($product['result'],2),
                                'title' => $ing->title
                            ];
                        }
                        $act->ingredients()->save($ing, ['count' => $product['result']]);
                    }
                } else {
                    foreach ($product_cat as $product) {
                        $st = Stock::find($product['id']);
                        if(floatval($product['result'])!==floatval($st->count)){
                            $stocks[]=[
                                'thisCount' => round($st->count,2),
                                'oldCount' => round($product['result'],2),
                                'title' => $st->title
                            ];
                        }
                        $act->stocks()->save($st, ['count' => $product['result']]);
                    }
                }
            }

            // если  ecть расхождение то отправляем письмо
            if(!empty($ingredients)||empty(!$stocks)){
                ActService::CloseEmailForced($ingredients,$stocks, $act->id, $change->id);
            }

            //обновление
            ActService::UpdateStockIngr($act->id);

            $kofeinyiapparat = new Kofeinyiapparat();
            $kofeinyiapparat->count = $request->kofeinyi_apparat;
            $kofeinyiapparat->act_id = $act->id;
            $kofeinyiapparat->save();
            //обновление общего кол-ва кофе
            Kofeinyiapparatcount::add($request->kofeinyi_apparat);

            return response()->json([
                'success' => true,
                'url' => env('APP_URL'),
            ], 200);
        }

    }

}