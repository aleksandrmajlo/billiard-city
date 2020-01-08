<?php

namespace App\Http\Controllers\Docs;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Acts\Consumableinvoice;


class ConsumableinvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('start') || $request->has('end') || $request->has('user_id')) {
            $consumableinvoices = Consumableinvoice::orderBy('created_at', 'desc');
            if ($request->has('start') && !empty($request->start)) {
                $start = explode('.', $request->start);
                $consumableinvoices = $consumableinvoices->where('created_at', '>=', date($start[2] . '-' . $start[1] . '-' . $start[0]) . ' 00:00:00');
            }
            if ($request->has('end') && !empty($request->end)) {
                $end = explode('.', $request->end);
                $consumableinvoices = $consumableinvoices->where('created_at', '<', date($end[2] . '-' . $end[1] . '-' . $end[0]) . ' 23:59:59');
            }
            if ($request->has('user_id') && !empty($request->user_id)) {
                $consumableinvoices = $consumableinvoices->where('user_id', '=', $request->user_id);
            }
            $consumableinvoices = $consumableinvoices->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $consumableinvoices = Consumableinvoice::orderBy('created_at', 'desc')
                ->paginate(10);
        }
        $roleIds = [3];
        $users = \App\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->get();


        return view('doc.consumableinvoices',
            [
                'consumableinvoices' => $consumableinvoices,
                'users' => $users,
                'this_url' => '/doc/consumableinvoice'
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $products = [];
        $consumableinvoice = Consumableinvoice::findOrFail($id);
        $change = $consumableinvoice->change;
        $total=0;
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
                        $products[] = [
                            'id' => $bar->stock->id,
                            'title' => $bar->stock->title,
                            'count' => $bar->count,
                            'price' => $bar->stock->price,
                            'discount' => $skidka
                        ];
                    }

                }
            }
            $productRes = [];
            foreach ($products as $product) {
                if (isset($productRes[$product['id']])) {
                    if (isset($productRes[$product['id']][$product['discount']])) {
                        $productRes[$product['id']][$product['discount']]['count'] = $product['count'] + $productRes[$product['id']][$product['discount']]['count'];

                    } else {
                        $productRes[$product['id']][$product['discount']] = [
                            'count' => $product['count'],
                            'price' => $product['price'],
                            'title' => $product['title'],
                            'total'=>0
                        ];
                    }
                } else {
                    $productRes[$product['id']] = [];
                    $productRes[$product['id']][$product['discount']] = [
                        'count' => $product['count'],
                        'price' => $product['price'],
                        'title' => $product['title'],
                        'total'=>0
                    ];

                }
            }
            foreach ($productRes as $i=>$prs){
                  foreach ($prs as $k=>$pr){
                      $thTotal=$pr['price']*$pr['count'];
                      $summSk=$k*$thTotal/100;
                      $thTotal=$thTotal-$summSk;
                      $total+=$thTotal;
                      $productRes[$i][$k]['total']=$thTotal;
                  }
            }
        }


        return view('doc.consumableinvoice', [
            'consumableinvoice' => $consumableinvoice,
            'total'=>round($total,2),
            'productRes' => $productRes,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
