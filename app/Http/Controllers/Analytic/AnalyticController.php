<?php


namespace App\Http\Controllers\Analytic;

use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Order;
use Illuminate\Support\Facades\DB;

class AnalyticController extends Controller
{
    public function attendance()
    {
        return view('analytic.attendance', [
        ]);
    }

    // подсчет данных по посещаемости
    public function attendanceData(Request $request)
    {
        $res = [];
        if ($request->has('monthData')) {
            $monthData = $request->monthData;
            foreach ($monthData as $monthDatum) {

                $count_order_bars = Order::whereYear('start', '=', $monthDatum['year'])
                    ->whereMonth('start', '=', (int)$monthDatum['month'] + 1)
                    ->where('type_bar', '=', 1)
                    ->count();

                $count_order_billiards = Order::whereYear('start', '=', $monthDatum['year'])
                    ->whereMonth('start', '=', (int)$monthDatum['month'] + 1)
                    ->where('type_billiards', '=', 1)
                    ->count();

                $count_orders = $count_order_bars + $count_order_billiards;

                $res[] = [
                    "month" => $monthDatum['month'],
                    "year" => $monthDatum['year'],
                    "count" => $count_orders,
                    "count_order_bars" => $count_order_bars,
                    "count_order_billiards" => $count_order_billiards
                ];
            }
        }
        return response()->json($res);
    }

    public function attendanceDate(Request $request)
    {
        $count_order_bars = 0;
        $count_order_billiards = 0;
        $res = [];
        if ($request->has('date')) {
            $date = $request->date;
            $dateStart = $date['date_start'];
            $dateEnd = $date['date_end'];
            $begin = new \DateTime($dateStart);
            $end = new \DateTime($dateEnd);

            for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
                $dateDay = $i->format("Y-m-d");

                $count_order_bars = Order::whereDate('start', '=', $dateDay)
                    ->where('type_bar', '=', 1)
                    ->count();
                $count_order_billiards = Order::whereDate('start', '=', $dateDay)
                    ->where('type_billiards', '=', 1)
                    ->count();
                $count_orders = $count_order_bars + $count_order_billiards;
                $res[] = [
                    "date" => $i->format('d'),
                    "month" => ((int)$i->format('n') - 1),
                    "year" => $i->format('Y'),
                    "count" => $count_orders,
                    "count_order_bars" => $count_order_bars,
                    "count_order_billiards" => $count_order_billiards
                ];

            };
        }
        return response()->json($res);
    }

}