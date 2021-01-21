<?php

namespace App\Http\Controllers\Docs;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Acts\Writeof;
use App\Stock;
use App\Bars\Ingredient;
use Illuminate\Support\Facades\Auth;
use Excel;

class WriteofController extends Controller
{
    protected $pageCount = 20;

    public function index(Request $request)
    {
        session()->forget('ActSortOrder');
        session()->forget('ActSortOrderType');

        if ($request->has('start') || $request->has('end') || $request->has('user_id')) {

            $purchaseinvoices = new Writeof();
            $purchaseinvoices = Writeof::orderBy('created_at', 'desc');

            if ($request->has('start') && !empty($request->start)) {
                $purchaseinvoices = $purchaseinvoices->where('created_at', '>=', $request->start . ' 00:00:00');
            }
            if ($request->has('end') && !empty($request->end)) {
                $purchaseinvoices = $purchaseinvoices->where('created_at', '<', $request->end . ' 23:59:59');
            }
            if ($request->has('user_id') && !empty($request->user_id)) {
                $purchaseinvoices = $purchaseinvoices->where('user_id', '=', $request->user_id);
            }
            $purchaseinvoices = $purchaseinvoices->orderBy('created_at', 'desc')->paginate($this->pageCount);

        } else {
            $purchaseinvoices = Writeof::orderBy('created_at', 'desc')
                ->paginate($this->pageCount);
        }
        $roleIds = [3, 2];
        $users = \App\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->get();

        return view('doc.writeofs', [
            'purchaseinvoices' => $purchaseinvoices->appends($request->except('page')),
            'users' => $users,
            'this_url' => '/doc/writeof'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('doc.writeofCreate', [

        ]);
    }


    public function store(Request $request)
    {

        $user = Auth::user();
        $writeof = new Writeof;
        $writeof->cause = "";
        $writeof->user_id = $user->id;
        $writeof->save();

        if ($request->has('stocks')) {
            foreach ($request->stocks as $k => $stock) {

                $text = $request->stock_causeTexts[$k];
                if (empty($text)) {
                    $text = $request->stock_causes[$k];
                }
                $writeof->stocks()->attach($stock, [
                        'count' => $request->count_stocks[$k],
                        'cause' => $text
                    ]
                );
            }
        }

        if ($request->has('ings')) {
            foreach ($request->ings as $k => $ing) {
                $text = $request->ing_causeTexts[$k];
                if (empty($text)) {
                    $text = $request->ing_causes[$k];
                }
                $writeof->ingredients()->attach($ing, [
                        'count' => $request->count_ings[$k],
                        'cause' => $text
                    ]
                );
            }
        }
        // обновляем продукты и ингадиенты
        \App\Services\PurchaseinvoiceService::WriteofUpdateStockIngr($writeof->id);
        return redirect('/doc/writeof')->with('success', true);

    }


    public function show($id, Request $request)
    {
        $purchaseinvoice = Writeof::findOrFail($id);

        // тип отображение start **************************************
        $ActSortOrder = session('ActSortOrder', 'cat');
        $ActSortOrderType = session('ActSortOrderType', 'asc');
        // тип отображение end **************************************

        $products = collect();
        $stocks = $purchaseinvoice->stocks;
        $ingredients = $purchaseinvoice->ingredients;
        foreach ($stocks as $stock) {
            $products->push([
                'title' => $stock->title,
                'cat' => $stock->categorySee->title,
                'count' => $stock->pivot->count,
                'type' => 1,
                'cause' => $stock->pivot->cause
            ]);
        }

        if ($ActSortOrder == "cat") {
            if ($ActSortOrderType == "desc") {
                $products = $products->sortByDesc('cat');
            } else {
                $products = $products->sortBy('cat');
            }
        }
        foreach ($ingredients as $stock) {
            $products->push([
                'title' => $stock->title,
                'cat' => null,
                'count' => $stock->pivot->count,
                'type' => 2,
                'cause' => $stock->pivot->cause
            ]);
        }

        if ($ActSortOrder == "title") {
            if ($ActSortOrderType == "desc") {
                $products = $products->sortByDesc('title');
            } else {
                $products = $products->sortBy('title');
            }
        }

        if ($ActSortOrder == "type") {
            if ($ActSortOrderType == "desc") {
                $products = $products->sortByDesc('type');
            } else {
                $products = $products->sortBy('type');
            }
        }

        if ($request->has('title')) {
            $search = $request->title;
            $products = $products->filter(function ($item) use ($search) {
                return stripos($item['title'], $search) !== false;
            });
        }

        $products->values()->all();

        return view('doc.writeof', [
            'purchaseinvoice' => $purchaseinvoice,
            'products' => $products,
            'id' => $id,
            'cause' => true,
            'this_url' => '/doc/writeof/' . $id,
            'ActSortOrder' => $ActSortOrder,
            'ActSortOrderType' => $ActSortOrderType
        ]);
    }


    public function edit($id)
    {

    }


    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // получить все продукты
    public function getProducts(Request $request)
    {
        // параметр или разрешить списание
        if ($request->has('resolve')) {
            $stocks = Stock::has('ingredients', '<', 1)->orderBy('created_at', 'desc')->get();
        } else {
            $stocks = Stock::where('resolve', 1)->has('ingredients', '<', 1)->orderBy('created_at', 'desc')->get();
        }
        $ingredients = Ingredient::orderBy('created_at', 'desc')->get();
        $causes = config('causes');
        return response()->json([
            'success' => true,
            'stocks' => $stocks,
            'ings' => $ingredients,
            'causes' => $causes
        ], 200);
    }
    public function export(Request $request)
    {

        $id = $request->id;
        $purchaseinvoice = Writeof::findOrFail($id);
        $results = [];
        $product = trans('act.product');
        foreach ($purchaseinvoice->stocks as $stock) {
            $results[] = [
                $stock->title,
                $stock->categorySee->title,
                $product,
                $stock->pivot->count,
                $stock->pivot->cause,
            ];

        }
        $ing = trans('act.ingredient');
        foreach ($purchaseinvoice->ingredients as $ingredient) {
            $results[] = [
                $ingredient->title,
                "",
                $ing,
                $ingredient->pivot->count,
                $ingredient->pivot->cause,

            ];
        }

        Excel::create(trans('writeof.title') . ' №' . $id, function ($excel) use ($results) {
            $excel->sheet('Лист 1', function ($sheet) use ($results) {
                $sheet->fromArray($results)->row(1, array(
                    trans('act.name'),
                    trans('act.cat'),
                    trans('act.type'),
                    trans('writeof.sklad'),
                    'Причина'
                ));
            });
        })->download('xls');
    }

    /*
    //установить порядок сортировки
    public function setCategoryDocSortOrder(Request $request){
        session(['ActSortOrder' => $request->sort]);
        session(['ActSortOrderType' => $request->type]);
        return back();
    }
    */

}
