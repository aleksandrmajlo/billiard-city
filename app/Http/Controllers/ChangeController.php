<?php

namespace App\Http\Controllers;

use App\Bars\Kofeinyiapparat;
use App\Change;
use App\Acts\Act;
use App\Bars\Ingredient;
use App\Services\ActService;
use App\Services\Kofeinyiapparatcount;
use App\Stock;


use App\Order;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Table;
use DB;

class ChangeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $user = Auth::user();
        $changes = Change::orderBy('created_at', 'desc')
            ->where('user_id', '=', $user_id)
            ->paginate(10);
        if ($user->hasRole('admin')) {
            $changes = Change::orderBy('created_at', 'desc')
                ->paginate(10);
            if ($request->all()) {
                $changes = new Change();
                if (isset($request->ot)) {
                    $changes = $changes->where('start', '>=', $request->ot);
                }
                if (isset($request->do)) {
                    $changes = $changes->where('stop', '<=', $request->do);
                }
                if (isset($request->work) && $request->work != 0) {
                    $changes = $changes->where('user_id', $request->work);
                }
                $changes = $changes->paginate(20);
            }
        }
        $workers = User::all();
        $tables = Table::all();

        foreach ($changes as $change){
//            dump($change->id);
//            dump($change->total);

        }
//        dd();

        return view('change', compact('changes', 'workers', 'tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * создание смены
     */

    public function create(Request $request)
    {

        if (Auth::user()->hasRole('manager')) {
            $changes = Change::where('stop', '=', null)->get();
            foreach ($changes as $change) {
                $userChange = DB::select('select * from users_roles where user_id = ?', array($change->user_id));
                if ($userChange[0]->role_id == 2) {
                    $change = Change::find($change->id);
                    $change->stop = Carbon::now()->format('Y-m-d H:i:s');
                    $change->save();
                }
            }
        }
        if (Auth::user()->hasRole('barmen')) {

            $changes = Change::where('stop', '=', null)->get();
            foreach ($changes as $change) {
                $userChange = DB::select('select * from users_roles where user_id = ?', array($change->user_id));
                if ($userChange[0]->role_id == 3) {
                    $change = Change::find($change->id);
                    $change->stop = Carbon::now()->format('Y-m-d H:i:s');
                    $change->save();
                }
            }
        }

        $change = new Change;
        $change->user_id = $request->user_id;
        $change->summa_start = $request->summa_start;
        $change->summa_end = null;
        $change->start = Carbon::now()->format('Y-m-d H:i:s');
        $change->stop = null;
        $change->save();

        /*
         * создаем акты ********************************
         */
        if (Auth::user()->hasRole('barmen')) {
            $act = new Act;
            $act->user_id = $request->user_id;
            $act->change_id = $change->id;
            $act->save();
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $k => $ingredient) {
                    $ing = Ingredient::find($ingredient);
                    $act->ingredients()->save($ing, ['count' => $request->count_ingredients[$k]]);
                }
            }
            if ($request->has('stocks')) {
                foreach ($request->stocks as $k => $stock) {
                    $st = Stock::find($stock);
                    $act->stocks()->save($st, ['count' => $request->count_stocks[$k]]);
                }
            }
            ActService::UpdateStockIngr($act->id);
            ActService::CreateValidate($request->all(), $act->id,$change->id);

            $kofeinyiapparat = new Kofeinyiapparat();
            $kofeinyiapparat->count = $request->kofeinyi_apparat;
            $kofeinyiapparat->act_id = $act->id;
            $kofeinyiapparat->save();
            //обновление общего кол-ва кофе
            Kofeinyiapparatcount::add($request->kofeinyi_apparat);

            // создание акта расходные накладные
            ActService::CreateConsumableinvoice($change);
            /*
             * создаем акты end ************************************
            */
        }

        return redirect('/')->with('status', 'зміна відкрита!');
    }

    // закрытие смены !!!!!!!!!!!!!нерабочее для бармена точно !!!!!!!!!!!!!!!
    public function closeChange(Request $request)
    {

        $change = Change::find($request->id);
        $change->summa_end = $request->summa_end;
        $change->stop = Carbon::now()->format('Y-m-d H:i:s');
        $change->save();

        if (Auth::user()->hasRole('barmen')) {
            $act = new Act;
            $act->user_id = $change->user_id;
            $act->change_id = $change->id;
            $act->type = 2;
            $act->save();
            if ($request->has('ingredients')) {
                foreach ($request->ingredients as $k => $ingredient) {
                    $ing = Ingredient::find($ingredient);
                    $act->ingredients()->save($ing, ['count' => $request->count_ingredients[$k]]);
                }
            }
            if ($request->has('stocks')) {
                foreach ($request->stocks as $k => $stock) {
                    $st = Stock::find($stock);
                    $act->stocks()->save($st, ['count' => $request->count_stocks[$k]]);
                }
            }
            ActService::UpdateStockIngr($act->id);
            // создание акта расходные накладные
//            ActService::CreateConsumableinvoice($change);

            $kofeinyiapparat = new Kofeinyiapparat();
            $kofeinyiapparat->count = $request->kofeinyi_apparat;
            $kofeinyiapparat->act_id = $act->id;
            $kofeinyiapparat->save();
            //обновление общего кол-ва кофе
            Kofeinyiapparatcount::add($request->kofeinyi_apparat);

        }
        return redirect('/')->with('status', 'Close change!');
    }


    public function seeChange($id)
    {
        $change = Change::findOrFail($id);
        $orders = Order::where('user_id', $change->user_id)
            ->where('changes_id', $change->id)
            ->paginate(10);
        $all_orders=Order::where('user_id', $change->user_id)
            ->where('changes_id', $change->id)
            ->get();
        $amountCountClients=0;
        $amountCountChange=0;
        foreach ($all_orders as $order){
            $amountCountClients++;
            $amountCountChange+=$order->amount;
        }
        return view('see-change', compact(
            'change',
            'orders',
            'amountCountClients',
            'amountCountChange'
        ));
    }

}
