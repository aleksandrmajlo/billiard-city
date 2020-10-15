<?php

namespace App\Http\Controllers\Bars;

use App\Http\Controllers\Controller;
use App\Bars\Ingredient;
use App\CategoryStock;
use App\Customer;
use App\Services\ActService;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    protected $pageCount = 20;

    public function index(Request $request)
    {
        $categoryStocks = CategoryStock::all();
        $products = Stock::orderBy('title', 'ASC')
            ->paginate($this->pageCount);
        if ($request->all()) {
            $products = new Stock();
            if (isset($request->searchtitle)) {
                $products = $products->where('title', 'like', '%' . $request->searchtitle . '%');
            }
            if (isset($request->type) && $request->type > 0) {
                $products = $products->where('categorystock_id', $request->type);
            }
            $products = $products->orderBy('title', 'ASC')->paginate($this->pageCount);
        }
        $kofeinyi_apparat_category_id = config('category.kofeinyi_apparat_category_id');
        $this_url = '/bars/stocks';
        return view('bars/stocks_index', compact(
            'products',
            'categoryStocks',
            'kofeinyi_apparat_category_id',
            'this_url'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $user = Auth::user();
        $ReadCount = false;
        if ($user->hasRole('admin')) {
            $ReadCount = true;
        }
        $getpage = '';
        $cat = '';
        $stock = '';
        $categoryStocks = CategoryStock::all();
        $ingredients = Ingredient::all();

        return view('bars.stock_create',
            compact(
                'ReadCount',
                'stock',
                'categoryStocks',
                'ingredients'
            )
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $stock = new Stock;
        $stock->title = $request->title;
        $stock->categorystock_id = $request->categoryStock;
        $stock->price = $request->price;
        $stock->count = $request->count;
        $stock->unlimited = $request->unlimited;
        $stock->published = $request->published;
        $stock->resolve = $request->resolve;

        if ($request->image) {
            $file = $request->file('image');
            $stock->image = self::upload($file);
        }

        $stock->save();
        if ($request->ingredients && !empty($request->ingredients)) {
            $ingredient_ar = [];
            foreach ($request->ingredients as $k => $ingredient) {
                $ingredient_ar[$ingredient] = ['count' => (float)$request->counts[$k]];
            }
            $stock->ingredients()->sync($ingredient_ar);
            ActService::UpdateProductCount();
        }
        return redirect('/bars/stocks')->with('ProductAdd', 'Додано!');
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Stock $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Stock $stock
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $stock = Stock::findOrfail($id);
        $user = Auth::user();
        $ReadCount = false;
        if ($user->hasRole('admin')) {
            $ReadCount = true;
        }
        $categoryStocks = CategoryStock::all();
        $ingredients = Ingredient::all();
        return view('bars.stock_edit',
            compact(
                'ReadCount',
                'stock',
                'categoryStocks',
                'ingredients'
            )
        );


    }


    public function update(Request $request, $id)
    {
        $stock = Stock::findOrfail($id);
        $stock->title = $request->title;
        $stock->categorystock_id = $request->categoryStock;
        $stock->price = $request->price;
        $stock->count = $request->count;
        $stock->published = $request->published;
        $stock->unlimited = $request->unlimited;
        $stock->resolve = $request->resolve;
        if ($request->image) {
            $file = $request->file('image');
            $stock->image = self::upload($file);
        }
        $stock->save();
        if ($request->ingredients && !empty($request->ingredients)) {
            $ingredient_ar = [];
            foreach ($request->ingredients as $k => $ingredient) {
                $ingredient_ar[$ingredient] = ['count' => (float)$request->counts[$k]];
            }
            $stock->ingredients()->sync($ingredient_ar);
        } else {
            $stock->ingredients()->sync([]);
        }
        ActService::UpdateProductCount();

        return redirect()->back()->with('ProductUpdate', 'Update!');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Stock $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock::findOrfail($id);
        $stock->delete();
        return redirect('/bars/stocks')->with('delete', 'Delete!');
    }


    public static function upload($file)
    {
        $destinationPath = 'uploads';
        $fileName = "product" . time() . '.' . $file->getClientOriginalExtension();
        $file->move($destinationPath, $fileName);
        $fileBase = '/uploads/' . $fileName;
        return $fileBase;
    }

}
