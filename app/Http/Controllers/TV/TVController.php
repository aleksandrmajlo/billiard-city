<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 02.03.2020
 * Time: 15:12
 */

namespace App\Http\Controllers\TV;
use App\Http\Controllers\Controller;
use App\Services\OrderService;
use Illuminate\Http\Request;

use Carbon\Carbon;
use App\Table;
use App\Order;
use App\Reservation;
use App\Pause;
use App\Change;
use Illuminate\Support\Facades\Auth;

class TVController  extends Controller
{

    // телевизор
    public function ScreenTable()
    {

        return view('ScreenTable');
    }

    // получение столов для телевизора
    public function GetScreenTables()
    {

        $tables = Table::orderBy('posiiton', 'desc')->get();
        $reservTable[] = 0;
        $reserv = Reservation::where('book', null)
            ->where('booking_before', null)
            ->get();
        $results = [];
        $reserv_table = [];
        $free_table = [];
        if (count($reserv) > 0) {
            foreach ($reserv as $reser) {
                $reservTable[] = $reser->id_table;
            }
            foreach ($tables as $table) {
                if (in_array($table->id, $reservTable)) {
                    // то есть стол в работе
                    $order = Order::where('table_id', $table->id)->where('closed', '=', null)->first();
                    $results_order = OrderService::priceOrder($order->id);
                    $reserv_table[] = [
                        'number' => $table->number,
                        'name' => $table->name,
                        'activePause' => $results_order['activePause'],
                        'minutes' => $results_order['minutes'],
                        'priceOrderTotal' => $results_order['priceOrderTotal'],
                    ];

                } else {
                    // свободен
                    $free_table[] = [
                        'number' => $table->number,
                        'name' => $table->name,
                    ];

                }
            }
            if (count($reserv_table) > count($free_table)) {
                foreach ($reserv_table as $key => $item) {
                    $free = false;
                    if (isset($free_table[$key])) {
                        $free = [
                            'number' => $free_table[$key]['number'],
                            'name' => $free_table[$key]['name'],
                        ];
                    }
                    $results[] = [
                        'reserv' => [
                            'number' => $item['number'],
                            'name' => $item['name'],
                            'activePause' => $item['activePause'],
                            'minutes' => $item['minutes'],
                            'priceOrderTotal' => $item['priceOrderTotal'],
                        ],
                        'free' => $free
                    ];
                }
            } else {
                foreach ($free_table as $key => $item) {
                    $res = false;
                    if (isset($reserv_table[$key])) {
                        $res = [
                            'number' => $reserv_table[$key]['number'],
                            'name' => $reserv_table[$key]['name'],
                            'activePause' => $reserv_table[$key]['activePause'],
                            'minutes' => $reserv_table[$key]['minutes'],
                            'priceOrderTotal' => $reserv_table[$key]['priceOrderTotal'],
                        ];
                    }

                    $results[] = [
                        'reserv' => $res,
                        'free' => ['number' => $item['number'], 'name' => $item['name']],
                    ];
                }
            }


        } else {

            foreach ($tables as $table) {
                $results[] = [
                    'reserv' => false,
                    'free' => ['number' => $table->number, 'name' => $table->name],
                ];
            }
        }
//        dump($results);

        return response()->json([
                'tables' => $results
            ]
        );
    }

}