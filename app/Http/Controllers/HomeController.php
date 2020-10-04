<?php

namespace App\Http\Controllers;

use App\Change;
use App\Customer;
use App\Money;
use App\Order;
use App\Reservation;
use App\Stock;
use App\Table;
use App\User;
use ConsoleTVs\Charts\Builder\Chart;
use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;
use MaddHatter\LaravelFullcalendar\Event;
use MaddHatter\LaravelFullcalendar\Calendar;
use Charts;
use DB;
use App\Charts\SampleChart;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use http\Env\Response;

class HomeController extends Controller
{
    public function sessionLng(Request $request)
    {
        session(['lng' => $request->lng]);
        return Redirect::back();
    }

    public function index(Request $request)
    {
        if (empty(Session::get('lng'))) {
            if (\Illuminate\Support\Facades\Auth::user()) {
                $userLang = Auth::user()->language;
                session(['lng' => $userLang]);
                App::setLocale($userLang);
            }
        }
        $openChange=null;
        if (Auth::user()) {
            $userAuthId = Auth::user()->id;
            $userAuthName = Auth::user()->name;
            $userAuthAvatar = Auth::user()->avatar;
            $openChange = Change::where('stop', '=', null)
                ->where('user_id', '=', $userAuthId)
                ->orderBy('created_at', 'desc')
                ->first();
            if (Auth::user()->hasRole('admin')) {
                $openChange = Change::where('stop', '=', null)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        $changes = Change::orderBy('created_at', 'desc')
            ->paginate(10);
        $openChangeStart = '';
        $timOld = '';
        $sumBar = '';
        $sumBill = '';
        $countOrders = '';
        if (isset($openChange)) {
            $openChangeId = $openChange->id;
            $openChangeSumStart = $openChange->summa_start;
            $openChangeStart = date_create($openChange->start);
            $time = Carbon::now();
            $interval = date_diff($openChangeStart, $time);
            $timOld = $interval->format('%H:%I:%S');
            $countOrders = Order::where('start', '>', $openChange->start)->count('amount');

        } else {
            $openChangeId = null;
            $openChangeSumStart = null;
        }

        // открытые заказы .....
        $orders = Order::where('type_bar', 1)
            ->where('closed', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();
        $change_stat = \App\Services\ChangeService::change_this();

        // открытые столы
        $open_tables = Order::where('type_billiards', 1)
            ->where('closed', '=', null)
            ->orderBy('created_at', 'desc')
            ->paginate(10000);



        // резерв
        $thisData = Carbon::now();
        $thisData7 = Carbon::now();
        $thisData7 = $thisData7->addDays(7);
        $reservs = Reservation::where('book', '!=', null)
                               ->where('booking_from', '>=',$thisData->format('Y-m-d H:i:s'))
                               ->where('booking_from', '<=',$thisData7->format('Y-m-d H:i:s'))
                               ->orderBy('booking_from')
                               ->get();

        // пользователь
        $barmen=false;
        if(Auth::user()->hasRole('barmen')){
            $barmen=true;
        }
        $manager=false;
        if(Auth::user()->hasRole('manager')){
            $manager=true;
        }

        //cтатистика по людям
        return view('home', [
            'orders' => $orders,
            'change_stat' => $change_stat,
            'openChangeId' => $openChangeId,
            'openChangeSumStart'=>$openChangeSumStart,
            'open_tables'=>$open_tables,
            'reservs'=>$reservs,
            'countOrders'=>$countOrders,
            'barmen'     =>$barmen,
            'manager'     =>$manager
        ]);
    }

    public function noAccess()
    {
        return view('no-access');
    }

}
