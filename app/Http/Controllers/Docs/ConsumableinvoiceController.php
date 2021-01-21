<?php

namespace App\Http\Controllers\Docs;

use App\Customer;
use App\Traits\UcfirstTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Acts\Consumableinvoice;
use Excel;

class ConsumableinvoiceController extends Controller
{
    use UcfirstTrait;
    protected $pageCount = 20;

    public function index(Request $request)
    {
        session()->forget('ActSortOrder');
        session()->forget('ActSortOrderType');

        if ($request->has('start') || $request->has('end') || $request->has('user_id')) {
            $consumableinvoices = Consumableinvoice::orderBy('created_at', 'desc');
            if ($request->has('start') && !empty($request->start)) {
                $consumableinvoices = $consumableinvoices->where('created_at', '>=', $request->start . ' 00:00:00');
            }
            if ($request->has('end') && !empty($request->end)) {
                $consumableinvoices = $consumableinvoices->where('created_at', '<', $request->end . ' 23:59:59');
            }
            if ($request->has('user_id') && !empty($request->user_id)) {
                $consumableinvoices = $consumableinvoices->where('user_id', '=', $request->user_id);
            }
            $consumableinvoices = $consumableinvoices->orderBy('created_at', 'desc')->paginate($this->pageCount);
        } else {
            $consumableinvoices = Consumableinvoice::orderBy('created_at', 'desc')
                ->paginate($this->pageCount);
        }
        $roleIds = [3, 2];
        $users = \App\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->get();
        return view(
            'doc.consumableinvoices',
            [
                'consumableinvoices' => $consumableinvoices->appends($request->except('page')),
                'users' => $users,
                'this_url' => '/doc/consumableinvoice'
            ]
        );
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $products = [];
        $consumableinvoice = Consumableinvoice::findOrFail($id);
        $change = $consumableinvoice->change;
        $total = 0;



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
                            'title' => $this->mb_ucfirst($bar->stock->title),
                            'count' => $bar->count,
                            'price' => $bar->stock->price,
                            'discount' => $skidka,
                            'cat' => $this->mb_ucfirst($bar->stock->categorySee->title)
                        ];
                    }
                }
            }
            $productRes = [];
            $title = null;
            if ($request->has('title')) {
                $title = mb_strtolower($request->title);
            }
            foreach ($products as $product) {

                if ($title) {
                    $pos = strpos(mb_strtolower($product['title']), $title);
                    if ($pos === false) continue;
                }

                if (isset($productRes[$product['id']])) {
                    if (isset($productRes[$product['id']][$product['discount']])) {
                        $productRes[$product['id']][$product['discount']]['count'] = $product['count'] + $productRes[$product['id']][$product['discount']]['count'];
                    } else {
                        $productRes[$product['id']][$product['discount']] = [
                            'count' => $product['count'],
                            'price' => $product['price'],
                            'title' => $product['title'],
                            'cat' => $product['cat'],
                            'total' => 0
                        ];
                    }
                } else {
                    $productRes[$product['id']] = [];
                    $productRes[$product['id']][$product['discount']] = [
                        'count' => $product['count'],
                        'price' => $product['price'],
                        'title' => $product['title'],
                        'cat' => $product['cat'],
                        'total' => 0
                    ];
                }

            }
            foreach ($productRes as $i => $prs) {
                foreach ($prs as $k => $pr) {
                    $thTotal = $pr['price'] * $pr['count'];
                    $summSk = $k * $thTotal / 100;
                    $thTotal = $thTotal - $summSk;
                    $total += $thTotal;
                    $productRes[$i][$k]['total'] = $thTotal;
                }
            }
        }


        $ARproducts = collect();
        foreach ($productRes as $products) {
            foreach ($products as $k => $product) {
                $ARproducts->push([
                    'title' => $product['title'],
                    'count' => $product['count'],
                    'price' => $product['price'],
                    'cat' => $product['cat'],
                    'skidka' => $k,
                    'total' => $product['total']
                ]);
            }
        }

        // тип отображение start **************************************
        $ActSortOrder = session('ActSortOrder', 'title');
        $ActSortOrderType = session('ActSortOrderType', 'asc');
        // тип отображение end **************************************


        if ($ActSortOrder == "total") {
            if ($ActSortOrderType == "desc") {
                $ARproducts = $ARproducts->sortByDesc('total');
            } else {
                $ARproducts = $ARproducts->sortBy('total');
            }
        }

        if ($ActSortOrder == "price") {
            if ($ActSortOrderType == "desc") {
                $ARproducts = $ARproducts->sortByDesc('price');
            } else {
                $ARproducts = $ARproducts->sortBy('price');
            }
        }

        if ($ActSortOrder == "title") {
            if ($ActSortOrderType == "desc") {
                $ARproducts = $ARproducts->sortByDesc('title', SORT_NATURAL|SORT_FLAG_CASE);
            } else {
                $ARproducts = $ARproducts->sortBy('title', SORT_NATURAL|SORT_FLAG_CASE);
            }
        }

        if ($ActSortOrder == "cat") {
            if ($ActSortOrderType == "desc") {
                $ARproducts = $ARproducts->sortByDesc('cat', SORT_FLAG_CASE);
            } else {
                $ARproducts = $ARproducts->sortBy('cat', SORT_FLAG_CASE);
            }
        }

        $ARproducts->values()->all();

        return view('doc.consumableinvoice', [
            'products' => $ARproducts,
            'consumableinvoice' => $consumableinvoice,
            'total' => round($total, 2),
            'productRes' => $productRes,
            'categories' => \App\CategoryStock::all(),
            'this_url' => '/doc/consumableinvoice/' . $id,
//            'category_id' => $category,
            'ActSortOrder' => $ActSortOrder,
            'ActSortOrderType' => $ActSortOrderType
        ]);
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function export($id)
    {
        $products = [];

        $consumableinvoice = Consumableinvoice::findOrFail($id);
        $change = $consumableinvoice->change;
        $total = 0;

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
                        if ($bar->stock && $bar->stock->id) {
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
                            'total' => 0
                        ];
                    }
                } else {
                    $productRes[$product['id']] = [];
                    $productRes[$product['id']][$product['discount']] = [
                        'count' => $product['count'],
                        'price' => $product['price'],
                        'title' => $product['title'],
                        'total' => 0
                    ];
                }
            }
            foreach ($productRes as $i => $prs) {
                foreach ($prs as $k => $pr) {
                    $thTotal = $pr['price'] * $pr['count'];
                    $summSk = $k * $thTotal / 100;
                    $thTotal = $thTotal - $summSk;
                    $total += $thTotal;
                    $productRes[$i][$k]['total'] = $thTotal;
                }
            }
            $arExcel = [];
            foreach ($productRes as $productRe) {
                foreach ($productRe as $k => $item) {
                    $arExcel[] = [
                        $item['title'],
                        $item['count'],
                        $item['price'] . ' грн',
                        $k . ' %',
                        $item['total'] . ' грн',
                    ];
                }
            }
            Excel::create(trans('сonsumableinvoice.titleOne') . ' №' . $id, function ($excel) use ($arExcel) {
                $excel->sheet('Лист 1', function ($sheet) use ($arExcel) {
                    $sheet->fromArray($arExcel)->row(1, array(
                        trans('act.name'),
                        trans('сonsumableinvoice.count'),
                        trans('сonsumableinvoice.price'),
                        trans('сonsumableinvoice.skidka'),
                        trans('сonsumableinvoice.allprice'),
                    ));
                });
            })->download('xls');
        }
    }



}
