<?php

namespace App\Http\Controllers\Analytic;

use App\Order;
use App\Table;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AnalytictableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('posiiton', 'desc')->get();
        return view('analytic.popularity-tables.index', [
            'tables' => $tables
        ]);
    }

    public function ajax(Request $request)
    {
        $res = [];
        if ($request->has('monthData')) {
            $monthData = $request->monthData;
            if ($request->table == '-1') {
                $tables = Table::orderBy('posiiton', 'desc')->get();
                foreach ($monthData as $monthDatum) {
                    $month = (int)$monthDatum['month'] + 1;
//                if ($request->table == '-1') {
                    foreach ($tables as $table) {
                        if (!isset($res[$table->id])) {
                            $res[$table->id] = [
                                'title' => $table->title . '(#' . $table->id . ')',
                                'amount' => []
                            ];
                        }
                        $amount = Order::whereYear('start', '=', $monthDatum['year'])
                            ->whereMonth('start', '=', $month)
                            ->where('table_id', $table->id)
                            ->sum('amount');
                        $count = Order::whereYear('start', '=', $monthDatum['year'])
                            ->whereMonth('start', '=', $month)
                            ->where('table_id', $table->id)
                            ->count();
                        $res[$table->id]['amount'][] = [
                            'year' => $monthDatum['year'],
                            'month' => $monthDatum['month'],
                            'sum' => ceil($amount),
                            'count' => $count
                        ];
                    }
//                }

                }
            } else {
                $table = Table::find($request->table);
                foreach ($monthData as $monthDatum) {
                    $month = (int)$monthDatum['month'] + 1;
                    if (!isset($res[$table->id])) {
                        $res[$table->id] = [
                            'title' => $table->title . '(#' . $table->id . ')',
                            'amount' => []
                        ];
                    }
                    $amount = Order::whereYear('start', '=', $monthDatum['year'])
                        ->whereMonth('start', '=', $month)
                        ->where('table_id', $table->id)
                        ->sum('amount');
                    $count = Order::whereYear('start', '=', $monthDatum['year'])
                        ->whereMonth('start', '=', $month)
                        ->where('table_id', $table->id)
                        ->count();
                    $res[$table->id]['amount'][] = [
                        'year' => $monthDatum['year'],
                        'month' => $monthDatum['month'],
                        'sum' => ceil($amount),
                        'count' => $count
                    ];
                }
            }
        }
        return response()->json($res);
    }

}
