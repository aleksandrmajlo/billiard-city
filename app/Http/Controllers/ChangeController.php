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
    protected $countPage = 20;

    public function index(Request $request)
    {
        $user = Auth::user();
        $isAdmin = false;
        $req_user = false;
        if ($user->hasRole('admin')) {
            $isAdmin = true;
            if ($request->all()) {
                $changes = new Change();
                if (isset($request->start)) {
                    $start = explode('.', $request->start);
                    $changes = $changes->where('start', '>=',$request->start . ' 00:00:00');
                }
                if (isset($request->stop)) {
                    $changes = $changes->where('stop', '<=', $request->stop . ' 23:59:59');
                }
                if (isset($request->user_id) && $request->user_id !== "0") {
                    $req_user = $request->user_id;
                    $changes = $changes->where('user_id', '=', $request->user_id);
                }
                $changes = $changes->orderBy('created_at', 'desc')->paginate($this->countPage);

            } else {
                $changes = Change::orderBy('created_at', 'desc')->paginate($this->countPage);
//                $changes = Change::paginate($this->countPage);
            }
        } else {

            $changes = Change::orderBy('created_at', 'desc')
                ->where('user_id', '=', $user->id)
                ->paginate($this->countPage);

        }
        $workers = User::all();
        return view('change.index',[
            'changes'=>$changes->appends($request->except('page')),
            'workers'=>$workers,
            'req_user'=>$req_user,
            'isAdmin' =>$isAdmin
        ]);

    }

    // открытие смены !!!!!!!!!!!!!нерабочее для бармена точно !!!!!!!!!!!!!!!
    public function store(Request $request)
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
            $user_id=Auth::user()->id;

            $change = new Change;
            $change->user_id = $user_id;
            $change->summa_start = $request->summa_start;
            $change->summa_end = null;
            $change->start = Carbon::now()->format('Y-m-d H:i:s');
            $change->stop = null;
            $change->save();
            return redirect('/')->with('status', 'зміна відкрита!');

        }


    }

    public function show($id)
    {
        $change = Change::findOrFail($id);
        $orders = Order::where('user_id', $change->user_id)
            ->where('changes_id', $change->id)
            ->paginate(10);
        return view('change.show', compact(
            'change',
            'orders'
        ));
    }

    public function change_open(Request $request)
    {

        if (Auth::user()->hasRole('manager')) {
            return view('change.open_manager', [
            ]);
        } elseif (Auth::user()->hasRole('barmen')) {
            return view('change.open_barmen');
        } else {
            abort('404');
        }

    }

    public function closeBarmenFormView(Request $request)
    {
        if ($request->has('id')) {
            $change = \App\Change::find($request->id);
            if (!is_null($change->stop)) {
                return redirect('/')->with('status', 'Cмена уже закрыта');
            }
            if (Auth::user()->hasRole('manager')) {
                return view('change.close_manager', [
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

    public function closeChangeManagerView(Request $request)
    {
        return view('change.close_manager', [
            'id' => $request->id
        ]);
    }

    public function closeChangeManager(Request $request)
    {

        $change = Change::find($request->id);
        $change->summa_end = $request->summa_end;
        $change->stop = Carbon::now()->format('Y-m-d H:i:s');
        $change->save();
        return redirect('/')->with('status', 'Close change!');
    }


    /*
    // закрытие смены !!!!!!!!!!!!!нерабочее для бармена точно !!!!!!!!!!!!!!!
    public function closeChange(Request $request)
    {

        $change = Change::find($request->id);
        $change->summa_end = $request->summa_end;
        $change->stop = Carbon::now()->format('Y-m-d H:i:s');
        $change->save();
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
    */


}
