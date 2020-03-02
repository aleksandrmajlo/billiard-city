<?php

namespace App\Acts;

use App\Customer;
use Illuminate\Database\Eloquent\Model;

class Consumableinvoice extends Model
{

    public function change()
    {
        return $this->belongsTo('App\Change');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getTotalAttribute()
    {
        $total = 0;
        $products = [];
        $change = $this->change;
        if ($change && $change->orders) {
            $countDiscount = [];
            foreach ($change->orders as $order) {
                if ($order->type_bar == 1 && $order->bars) {
                    $customer_id = $order->customer_id;
                    $skidka = 0;
                    if ($customer_id) {
                        $customer = Customer::find($customer_id);
                        if ($customer->skidka_bar) {
                            $skidka = $customer->skidka_bar;
                        }
                    }
                    foreach ($order->bars as $bar) {
                        if($bar->stock&&$bar->stock->id){
                            $products[] = [
                                'id' => $bar->stock->id,
                                'count' => $bar->count,
                                'price' => $bar->stock->price,
                                'discount' => $skidka
                            ];
                        }
                    }

                }
            }

            foreach ($products as $k => $product) {
                $thTotal = $product['price'] * $product['count'];
                $summSk = $product["discount"] * $thTotal / 100;

                $thTotal = $thTotal - $summSk;
                $total += $thTotal;
            }

        }
        return round($total,2);

    }

    public function getCountthAttribute()
    {
        $total = 0;
        $change = $this->change;
        if ($change && $change->orders) {
            foreach ($change->orders as $order) {
                if ($order->type_bar == 1 && $order->bars) {
                    $total += count($order->bars);
                }
            }
        }
        return $total;
    }
}
