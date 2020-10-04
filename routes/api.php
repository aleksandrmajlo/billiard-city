<?php

use App\Reservation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\BookingService;
use Illuminate\Auth;
use App\Table;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});


$services = DB::table('api_tokens')->pluck('service')->toArray();
$services_str = implode(",", $services);
Route::group(['middleware' => 'apitoken:' . $services_str], function () {

    Route::get('/listtables', function () {
        $tables = Table::orderBy('posiiton', 'desc')
            ->select('id', 'title')
            ->get();
        $res_tables = [];
        foreach ($tables as $table) {
            $res_tables[] = [
                'table' => $table->id,
                'title' => $table->title,
            ];
        }
        return response()->json($res_tables);
    });
    // бронирование за 7 дней
    Route::get('/busytables', function (Request $request) {

        $results = [
            'error' => [],
            'succes' => [],
        ];


        $date_start = Carbon::now()->format('Y-m-d h:i:s');
        $date_end_7 = Carbon::now()->addDays(7);
        $date_end = $date_end_7->format('Y-m-d h:i:s');
        $bookings = Reservation::where('book', '!=', null)->whereBetween('booking_from', [$date_start, $date_end])
            ->select('id', 'id_table', 'id_customers', 'booking_from', 'booking_before', 'name', 'lastname', 'phone', 'email', 'status', 'note', 'source', 'table_number')
            ->orderBy('booking_from')
            ->get();
        if ($bookings) {
            foreach ($bookings as $booking) {
                $results['succes'][] = [
                    'date_start' => $booking->booking_from,
                    'date_end' => $booking->booking_before,
                    'table' => $booking->id_table
                ];
            }
        }
        return response()->json($results);


    });

    Route::post('/tobooktables', function (Request $request) {
        $result = [
            'error' => [],
            'succes' => [],
        ];
        if (!$request->has('date_start')) {
            $result['error']['date_start'] = false;
        }

        if (!$request->has('date_end')) {
            $result['error']['date_end'] = false;
        }

        if (!$request->has('phone')) {
            $result['error']['phone'] = false;
        }

        if (!$request->has('table')) {
            $result['error']['table'] = false;
        }
        if (!$request->has('table')) {
            $result['error']['table'] = false;
        }
        if (!$request->has('status')) {
            $result['error']['status'] = false;
        }
        if (empty($result['error'])) {
            $result['succes'] = BookingService::add($request->all());
        }
        return response()->json($result);


    });
});
