<?php

namespace App\Http\Controllers;

use App\Change;
use App\Customer;
use App\Pause;
use App\Reservation;
use App\Stock;
use App\Tariff;
use Illuminate\Http\Request;
use App\Order;
use Carbon\Carbon;
use App\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservsTable = Reservation::all()->where('book', '!=', null);

        $events = [];
        foreach ($reservsTable as $reservTable) {
            $nameTitle = Table::where('id', '=', $reservTable->id_table)->first();
            $date1 = strtotime($reservTable->booking_from);
            $dateStart = date("Y-m-d H:i:s", $date1);
            $timeStart = date("H : i", $date1);
            $date2 = strtotime($reservTable->booking_before);
            $dateEnd = date("Y-m-d H:i:s", $date2);
            $timeEnd = date("H : i", $date2);

            $idReserv = '/booking/' . $reservTable->id;
            $events[] = \Calendar::event(
                "$nameTitle->title ($timeStart - $timeEnd)", //event title
                false, //full day event?
                new \DateTime($dateStart),
                new \DateTime($dateEnd),
                1,
                [
                    'url' => $idReserv
                ]
            );
        }
        $lang = '';
        $lang = Session::get('lng');

        if (isset($lang)) {
            if ($lang == "ru") {
                $dayWeek = ["Вск", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
                $mouns = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
                $mounsSm = ['Янв.', 'Фев.', 'Мар.', 'Апр.', 'Май.', 'Июн.', 'Июл.', 'Авг', 'Сен.', 'Окт.', 'Ноя.', 'Дек'];

                $today = 'Сгодня';
                $month = 'Месяц';
                $week = 'Неделя';
                $day = 'День';

            }
            if ($lang == "ua") {
                $dayWeek = ["Нд", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"];
                $mouns = ['Січень', 'Лютий', 'Березень', 'Квітень', 'Травень', 'Червень', 'Липень', 'Серпень', 'Вересень', 'Жовтень', 'Листопад', 'Грудень'];
                $mounsSm = ['Січ.', 'Лют.', 'Бер.', 'Кві.', 'Тра.', 'Черв.', 'Лип.', 'Серп', 'Вер.', 'Жовт.', 'Лист.', 'Груд'];

                $today = 'Cьогодні';
                $month = 'Місяць';
                $week = 'Тиждень';
                $day = 'День';
            }
        }

        $calendar = \Calendar::addEvents($events)
            ->setOptions([
                'lang' => 'uk',
                'firstDay' => 1,
                'axisFormat' => 'H:mm',
                'dayNamesShort' => $dayWeek,
                'monthNames' => $mouns,
                'monthNamesShort' => $mounsSm,
                'dayNames' => $dayWeek,
                'buttonText' => array(
                    'today' => $today,
                    'month' => $month,
                    'week' => $week,
                    'day' => $day
                ),

            ]);

        $dayWeek = '';

        return view('booking', compact('calendar', 'dayWeek'));
    }

    public function reservTableSee($id)
    {
        $reserv = Reservation::where('id', '=', $id)->firstOrFail();

        return view('bookingsee', compact('reserv'));
    }

    public function reservTable()
    {
        $tables = Table::all();
        $customers = Customer::all();
        return view('admin.booking.create', compact('tables', 'customers'));
    }

    public function reservTableCreate(Request $request)
    {
        $gustreserv = null;
        $customerreserv = null;
        if ($request->radiogroup == 1) {
            $gustreserv = $request->reserv_name . ' (' . $request->reserv_phone . ')';
        } else {
            $customerreserv = $request->customer;
        }

        $reservation = new Reservation();
        $reservation->id_table = $request->table;
        $reservation->id_user = Auth::user()->id;
        $reservation->id_customers = $customerreserv;
        $reservation->booking_from = $request->booking_from . ' ' . $request->booking_from_time;
        $reservation->booking_before = $request->booking_before . ' ' . $request->booking_before_time;
        $reservation->a_guest = $gustreserv;
        $reservation->book = 1;
        $reservation->save();

        return redirect('/booking')->with('status', 'Додано!');
    }

    /*
     * открытые cтолы
     */
    public function openTable()
    {
        $tz = config('app.timezone');
        $money = config('app.moneyClobal');
        $products = Stock::where('published', '=', 1)
            ->where('count', '>', 0)
            ->orWhere('unlimited', '=', 1)
            ->where('published', 1)
            ->orderBy('title', 'ASC')
            ->get();
        $userAuth = Auth::id();
        $orders = Order::where('type_billiards', 1)
            ->where('closed', '=', null)
            ->orderBy('created_at', 'desc')
            ->paginate(10000);

        return view('order.open_table', [
                'orders' => $orders,
                'products' => $products,
                'tz' => $tz
            ]
        );
    }

    public function openOrder(Request $request)
    {
        $userAuth = Auth::id();
        $products = Stock::where('published', '=', 1)
            ->where('count', '>', 0)
            ->orWhere('unlimited', '=', 1)
            ->where('published', 1)
            ->orderBy('title', 'ASC')
            ->get();
        $orders = Order::where('type_bar', 1)
            ->where('closed', '=', null)
            ->orderBy('created_at', 'desc')
            ->get();
        if ($request->has('status')){
            return redirect('/open-bar')->with('status', 'Замовлення створено!');
        }
        return view('open_order', compact('orders', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Reservation $reservation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function destroyBooking($id)
    {

        $stock = Reservation::find($id);
        $stock->delete();
        return redirect('/booking');
    }
}
