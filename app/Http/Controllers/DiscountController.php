<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Discount;
use App\User;
use App\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $disounts =  Discount::all();
        $usersWhewDiscounts = Customer::where('skidka', '>', 0)->paginate(10);
        return view('discount', compact('disounts', 'usersWhewDiscounts'));
    }

    public function storeDateDiscount(Request $request)
    {

        $dateDiscount = new Discount();
        $dateDiscount->day = $request->date;
        $dateDiscount->discount = $request->discount;
        $dateDiscount->add_register = $request->add_register;
        $dateDiscount->add_sum = $request->add_sum;
        $dateDiscount->add_type = $request->add_type;
        $dateDiscount->add_bill = $request->add_bill;
        $dateDiscount->add_bar= $request->add_bar;
        $dateDiscount->save();
        return redirect('/discount')->with('status', 'Додано!');
    }

    public function storeSummDiscount(Request $request)
    {
        $dateDiscount = new Discount();
        $dateDiscount->summ = $request->summ;
        $dateDiscount->discount = $request->discount;
        $dateDiscount->add_register = $request->add_register2;
        $dateDiscount->add_day = $request->add_day;
        $dateDiscount->add_type = $request->add_type;
        $dateDiscount->add_bill = $request->add_bill2;
        $dateDiscount->add_bar= $request->add_bar2;
        $dateDiscount->save();
        return redirect('/discount')->with('status', 'Add!');
    }

    public function destroy($id)
    {
        $stock = Discount::find($id);
        $stock->delete();
        return redirect('/discount')->with('status', 'Delete!');
    }

    public static function userDiscount($customerID, $sumBill, $sumBar) {
        $disounts =  Discount::orderBy('created_at', 'discount')->get();
        $customer = Customer::where('id', $customerID)->first();
        $sumClints = Order::where('customer_id', $customerID)
            ->where('closed', '!=', 'null')
            ->sum('amount');
        $customerCreated = $customer->created_at;

        foreach ($disounts as $disount) {
            if($disount->summ != null) {
                if($disount->summ <= $sumClints) {
                    $discountsumma[] = $disount->discount;
                    $dateType1[] = $disount->add_day;
                    $add_register1[] = $disount->add_register;
                    $bill1[] = $disount->add_bill;
                    $bar1[] = $disount->add_bar;
                }
            }
        }

        $dateSkidki = '';
        foreach ($disounts as $disount) {
            if ($disount->day != null) {
                $dateSkidki = $customerCreated->addDay($disount->day);

                if ($dateSkidki <= Carbon::now()) {
                    $dateSkidkis[] = $disount->discount;
                    $dateType2[] = $disount->add_sum;
                    $add_register2[] = $disount->add_register;
                    $bill2[] = $disount->add_bill;
                    $bar2[] = $disount->add_bar;

                }
            }
        }

        $skidkauser = $customer->skidka;
        $skidkaSum = 0;
        $skidkaDay = 0;

        if(isset($bill1) && end($bill1) == 'on') { $bill1 = 1;}
        if(isset($bill2) && end($bill2) == 'on') { $bill2 = 1;}
        if(isset($bar1) && end($bar1) == 'on') { $bar1 = 1;}
        if(isset($bar2) && end($bar2) == 'on') { $bar2 = 1;}

        if(isset($bill1) && $bill1 = $sumBill) {
            if( end($dateType1) == "on") {
                $skidkaSum = (end($discountsumma));
            } else {
                $skidkaSum = 0;
            }

            if( end($add_register1) == 'on') {
                $skidkaSum = $skidkaSum + $customer->skidka;
            }
        }

        if(isset($bar1) && $bar1 = $sumBar) {
            if( end($dateType1) == "on") {
                $skidkaSum = (end($discountsumma));
            } else {
                $skidkaSum = 0;
            }

            if( end($add_register1) == 'on') {
                $skidkaSum = $skidkaSum + $customer->skidka;
            }
        }


        if(isset($bill2) && $bill2 = $sumBill) {
            if (end($dateType2) == 'on') {
                $skidkaDay = (end($dateSkidkis));

            } else {
                $skidkaDay = 0;
            }

            if (end($add_register2) == 'on') {

                $skidkaDay = $skidkaDay + $customer->skidka;
            }
        }

        if(isset($bar2) &&  $bar2 = $sumBar) {
            if (end($dateType2) == 'on') {
                $skidkaDay = (end($dateSkidkis));

            } else {
                $skidkaDay = 0;
            }

            if (end($add_register2) == 'on') {
                $skidkaDay = $skidkaDay + $customer->skidka;

            }
        }

        $skidka = $skidkaDay + $skidkaSum;
        if($skidka == 0) {
            $skidka = $skidkauser;
        }

        if($skidka >= 100) {
            $skidka = 100;
        }

        return $skidka;
    }
}
