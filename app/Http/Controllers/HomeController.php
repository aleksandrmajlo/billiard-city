<?php

namespace App\Http\Controllers;

use App\Change;
use App\Customer;
use App\Money;
use App\Order;
use App\Reservation;
use App\Stock;
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

        $customerCountNew = Customer::where('created_at', '>', Carbon::now()->subHour(24))->count();
        $amountCount24 = Order::where('created_at', '>', Carbon::now()->subHour(24))->sum('amount');
        $amountCountChange = 0;
        if (isset($openChange->start)) {
            if (\Illuminate\Support\Facades\Auth::user()->hasRole('manager')) {
                $amountCountChange = Order::where('created_at', '>', $openChange->start)
                    ->where('type_billiards', 1)
                    ->sum('amount');
            }
            if (\Illuminate\Support\Facades\Auth::user()->hasRole('barmen')) {
                $amountCountChange = Order::where('created_at', '>', $openChange->start)
                    ->where('type_bar', 1)
                    ->sum('amount');
            }

            $sumBar = $openChange->summa_start + $amountCountChange;
        }
        $sumBarAdmin2 = 0;
        $sumBillAdmin2 = 0;
        if (Auth::check()) {

            if (\Illuminate\Support\Facades\Auth::user()->hasRole('admin')) {
//                $changes2 = Change::all();
                $changes2 = Change::whereNull('stop')->get();
                if (count($changes2) > 0) {
                    foreach ($changes2 as $k => $change) {

                        $userChange = DB::select('select * from users_roles where user_id = ?', array($change->user_id));
                        if (isset($userChange[0]->role_id) && $userChange[0]->role_id == 2) {
                            $openChange = Change::where('user_id', '=', $userChange[0]->user_id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                            $amountCountChange2 = Order::where('created_at', '>', $openChange->start)
                                ->where('type_billiards', 1)
                                ->where('status', 1)
                                ->sum('amount');
                            $sumBillAdmin2 = round($amountCountChange2,2);
                        }

                        if (isset($userChange[0]->role_id) && $userChange[0]->role_id == 3) {
                            $openChange = Change::where('user_id', '=', $userChange[0]->user_id)
                                ->orderBy('created_at', 'desc')
                                ->first();
                            $orders = Order::where('created_at', '>', $openChange->start)
                                ->where('type_bar', 1)
                                ->where('status', 1)
                                ->get();
                            $sumBarAdmin2 = $openChange->total;
                        }
                    }
                }
            }
        }

        $ordersTables = Order::where('type_billiards', 1)
            ->where('status', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();

        $customerNew = '';
        $customerNew = Customer::take(10)->orderBy('created_at', 'desc')->get();
        $nowdate = Carbon::now();
        $booking = Reservation::orderBy('created_at', 'desc')
            ->where('booking_from', '>=', $nowdate)
            ->where('book', '=', 1)
            ->take(5)
            ->get();
        $sumBillMin = Money::all()->where('admin_type', 1)->sum('admin');
        $sumBarMin = Money::all()->where('admin_type', 2)->sum('admin');
        $sumBill = Money::all()->sum('sum_bil') + $sumBillMin;
        $sumBar = Money::all()->sum('sum_bar') + $sumBarMin;
        $sumBillAdmin = Money::all()->sum('sum_bil') + $sumBillMin;
        $sumBarAdmin = Money::all()->sum('sum_bar') + $sumBarMin;
        $amountCount24 = $sumBillAdmin + $sumBarAdmin;
        $amountCount24 = $sumBill + $sumBar;

        /*
         * получаем ингадиенты и продукты без инградиентов
         */
        $ingredients = \App\Bars\Ingredient::all();
        $stocks = Stock::has('ingredients', '<', 1)->get();

        return view('home', compact(
            'ingredients',
            'stocks',
            'userAuthName',
            'userAuthAvatar',
            'changes', 'openChangeId', 'openChangeSumStart', 'booking', 'ordersTables', 'customerNew', 'openChangeStart', 'timOld', 'countOrders', 'customerCountNew', 'amountCount24', 'sumBar', 'sumBill', 'chart', 'pie', 'chart2', 'sumBarAdmin', 'sumBillAdmin', 'sumBarAdmin2', 'sumBillAdmin2'));
    }

    public function noAccess()
    {
        return view('no-access');
    }

}
