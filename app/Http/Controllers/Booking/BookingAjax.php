<?php


namespace App\Http\Controllers\Booking;

use App\Customer;
use App\Http\Controllers\Controller;
use App\Reservation;
use App\Services\BookingService;
use App\Table;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingAjax extends Controller
{
    public function bookings(Request $request)
    {
        $date_start = $request->date_start;
        $date_end = $request->date_end;
        // ORDER BY `booking_from` DESC
        if (is_null($date_end)) {
            $bookings = Reservation::where('book', '!=', null)->whereDate('booking_from', '=', $date_start)
                ->select('id', 'id_table', 'id_customers', 'booking_from', 'booking_before', 'name', 'lastname', 'phone', 'email', 'status', 'note', 'source', 'table_number')
                ->orderBy('booking_from')
                ->get();
        } else {
            $bookings = Reservation::where('book', '!=', null)->whereBetween('booking_from', [$date_start . ' 00:00:00', $date_end . ' 23:59:59'])
                ->select('id', 'id_table', 'id_customers', 'booking_from', 'booking_before', 'name', 'lastname', 'phone', 'email', 'status', 'note', 'source', 'table_number')
                ->orderBy('booking_from')
                ->get();
        }

        if ($bookings) {
            foreach ($bookings as $key => $booking) {
                $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_from);
                $bookings[$key]['from_time'] = $date_start->format('H:i');
                $bookings[$key]['from'] = $date_start->format('Y-m-d');

                $booking_before = Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_before);
                $bookings[$key]['to_time'] = $booking_before->format('H:i');
                $bookings[$key]['to'] = $booking_before->format('Y-m-d');

                $bookings[$key]['table_number'] = $booking->table_number;


                if (!empty($booking->id_customers)) {
                    $bookings[$key]['name'] = $booking->customer->name;
                    $bookings[$key]['lastname'] = $booking->customer->surname;
                }

            }
        }
        return response()->json([
            'bookings' => $bookings
        ], 200);
    }

    // получение брони для календаря
    public function calendar_bookings()
    {
        $bookings = Reservation::where('book', '!=', null)->get();
        $res = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_from);
                if (isset($res[$date_start->year])) {
                    if (isset($res[$date_start->year][$date_start->month])) {
                        $res[$date_start->year][$date_start->month][] = $date_start->day;
                    } else {
                        $res[$date_start->year][$date_start->month] = [];
                        $res[$date_start->year][$date_start->month][] = $date_start->day;
                    }
                } else {
                    $res[$date_start->year] = [];
                    if (isset($res[$date_start->year][$date_start->month])) {
                        $res[$date_start->year][$date_start->month][] = $date_start->day;
                    } else {
                        $res[$date_start->year][$date_start->month] = [];
                        $res[$date_start->year][$date_start->month][] = $date_start->day;
                    }
                }
            }
        }
        return response()->json([
            'bookings' => $res
        ], 200);
    }

    public function searchBooking(Request $request)
    {
        $search = $request->search;
        $bookings = Reservation::where('name', 'LIKE', '%' . $search . '%')
            ->orWhere('lastname', 'LIKE', '%' . $search . '%')
            ->orWhere('phone', 'LIKE', '%' . $search . '%')
            ->orWhere('table_number', 'LIKE', '%' . $search . '%')
            ->select('id', 'id_table', 'id_customers', 'booking_from', 'booking_before', 'name', 'lastname', 'phone', 'email', 'status', 'note', 'source', 'table_number')
            ->get();
        if ($bookings) {
            foreach ($bookings as $key => $booking) {

                $date_start = Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_from);
                $bookings[$key]['from_time'] = $date_start->format('H:i');
                $bookings[$key]['from'] = $date_start->format('Y-m-d');

                $booking_before = Carbon::createFromFormat('Y-m-d H:i:s', $booking->booking_before);
                $bookings[$key]['to_time'] = $booking_before->format('H:i');
                $bookings[$key]['to'] = $booking_before->format('Y-m-d');

                $bookings[$key]['table_number'] = $booking->table_number;
                if (!empty($booking->id_customers)) {
                    $bookings[$key]['name'] = $booking->customer->name;
                    $bookings[$key]['lastname'] = $booking->customer->surname;
                }

            }
        }
        return response()->json([
            'bookings' => $bookings
        ], 200);
    }

    public function addNewbooking(Request $request)
    {
        $reservation = new Reservation();
        $reservation->id_table = $request->id_table;
        $reservation->id_user = Auth::user()->id;

        if (!empty($request->id_customers)) {
            $reservation->id_customers = $request->id_customers;
        } else {
            // поскольку телефон уникален-проверяем или существует такой пользователь
            $customerFind = BookingService::FindCustomerByPhone($request->phone);
            if ($customerFind) {
                $reservation->id_customers = $customerFind;
            } else {
                //добавляем пользователя
                $customer = new Customer;
                $customer->name = $request->name;
                $customer->surname = $request->lastname;
                $customer->phone = BookingService::ValidPhone($request->phone);
                $customer->email = $request->email;
                $customer->save();
                $reservation->id_customers = $customer->id;
            }

        }
        $reservation->name = $request->name;
        $reservation->lastname = $request->lastname;
        $reservation->phone = BookingService::ValidPhone($request->phone);

        $reservation->email = $request->email;
        $reservation->booking_from = $request->from . ' ' . $request->from_time;
        $reservation->booking_before = $request->to . ' ' . $request->to_time;
        $reservation->book = 1;
        $reservation->status = $request->status;
        $reservation->note = $request->note;
        $reservation->source = $request->source;

        $table = Table::find($request->id_table);
        $reservation->table_number = $table->number;
        $reservation->save();
        return response()->json([
            'suc' => true
        ], 200);
    }

    public function saveBooking(Request $request)
    {
        $id = $request->id;

        $reservation = Reservation::find($id);
        $reservation->id_table = $request->id_table;

        if (!empty($request->id_customers)) {
            $reservation->id_customers = $request->id_customers;
        }
        $reservation->name = $request->name;
        $reservation->lastname = $request->lastname;
        $reservation->phone = BookingService::ValidPhone($request->phone);
        $reservation->email = $request->email;
        $reservation->booking_from = $request->from . ' ' . $request->from_time;
        $reservation->booking_before = $request->to . ' ' . $request->to_time;
        $reservation->book = 1;
        $reservation->status = $request->status;
        $reservation->note = $request->note;
        $reservation->source = $request->source;
        $table = Table::find($request->id_table);
        $reservation->table_number = $table->number;
        $reservation->save();
        return response()->json([
            'suc' => true
        ], 200);
    }

    public function removeBooking(Request $request)
    {
        Reservation::destroy($request->id);
        return response()->json([
            'suc' => true
        ], 200);
    }

    public function tables()
    {

        $tables = Table::orderBy('posiiton', 'desc')
            ->select('id', 'title')
            ->get();
        $res_tables = [];
        foreach ($tables as $table) {
            $res_tables[] = [
                'value' => $table->id,
                'text' => $table->title,
            ];
        }
        $customers = Customer::select('id', 'name', 'surname', 'email', 'phone')->get();
        return response()->json([
            'tables' => $res_tables,
            'customers' => $customers
        ], 200);
    }

    //получение данных с лендинга
    public function Lending(Request $request)
    {

        $reservation = new Reservation();
        //        $reservation->id_table =29;//объязательное поле ставим тэст
        $reservation->id_user = 44; //объязательное поле

        $reservation->booking_from = $request->booking_from; //объзательное поле
        $reservation->booking_before = $request->booking_before; //объзательное поле

        $reservation->phone = BookingService::ValidPhone($request->phone);
        $customer_id = BookingService::FindCustomerByPhone($request->phone);
        if ($customer_id) {
            $reservation->id_customers = $customer_id;
        } else {
            //добавляем пользователя
            $customer = new Customer;
            $customer->phone = BookingService::ValidPhone($request->phone);
            $customer->email = $request->email;
            $customer->save();
            $reservation->id_customers = $customer->id;
        }
        $reservation->email = $request->email;
        $reservation->note = $request->note;

        $reservation->book = 1;
        $reservation->status = 1;
        $reservation->source = 'site';

        $reservation->save();
        return response()->json([
            'suc' => true
        ], 200);
    }
}
