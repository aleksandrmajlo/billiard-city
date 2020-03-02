<?php

namespace App\Http\Controllers;

use App\Bars\Ingredient;
use App\CategoryStock;
use App\Customer;
use App\Services\Kofeinyiapparatcount;
use App\Stock;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categoryStocks = CategoryStock::all();

        $products = Stock::orderBy('title', 'ASC')
            ->paginate(10);
        if($request->all()) {
            $products = new Stock();
            if(isset($request->searchtitle)) {
                $products = $products->where('title', 'like',  '%' . $request->searchtitle . '%');
            }
            if(isset($request->type) && $request->type > 0) {
                 $products = $products->where('categorystock_id', $request->type);
            }
            $products = $products->orderBy('title', 'ASC')->paginate(10);
        }
        $kofeinyi_apparat_category_id=config('category.kofeinyi_apparat_category_id');

        return view('stock', compact(
            'products',
            'categoryStocks',
            'kofeinyi_apparat_category_id'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

        $user=Auth::user();
        $ReadCount=false;
        if($user->hasRole('admin')){
            $ReadCount=true;
        }
        $getpage = '';
        $cat = '';
        if(isset($_GET['page'])) {
            $getpage = $_GET['page'];
        }

        if(isset($_GET['cat'])) {
            $cat = $_GET['cat'];
        }
        $arr_ingredients=[];
        if(isset($request->id)) {
            $stock = Stock::where('id', $request->id)->firstOrFail();
            $arr_ingredients=$stock->ingredients->pluck('id')->toArray();
            $categoryStocks = CategoryStock::all();
        } else {
            $stock = '';

        }
        $categoryStocks = CategoryStock::all();
        $ingredients=Ingredient::all();

        return view('admin.stock.create',
            compact(
                'ReadCount',
                'stock',
                'categoryStocks',
                'getpage',
                'cat','ingredients','arr_ingredients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $stock = new Stock;
        $stock->title = $request->title;
        $stock->categorystock_id  = $request->categoryStock;
        $stock->price = $request->price;
        $stock->count = $request->count;
        $stock->unlimited = $request->unlimited;
        $stock->published = $request->published;
        $stock->resolve = $request->resolve;

        if($request->image){
            $file = $request->file('image');
            $stock->image=self::upload($file);
        }
        
        $stock->save();
        if($request->ingredients&&!empty($request->ingredients)){
            $ingredient_ar=[];
            foreach ($request->ingredients as $k=>$ingredient){
                $ingredient_ar[$ingredient]=['count'=>(float)$request->counts[$k]];
            }
            $stock->ingredients()->sync($ingredient_ar);
        }
        return redirect('/stock')->with('status', 'Додано!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function show(Stock $stock)
    {


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $stock = Stock::find($request->id);
        $stock->title = $request->title;
        $stock->categorystock_id  = $request->categoryStock;
        $stock->price = $request->price;
        $stock->count = $request->count;
        $stock->published = $request->published;
        $stock->unlimited = $request->unlimited;
        $stock->resolve = $request->resolve;

        if($request->image){
            $file = $request->file('image');
            $stock->image=self::upload($file);
        }
        $stock->save();
        if($request->ingredients&&!empty($request->ingredients)){
            $ingredient_ar=[];
            foreach ($request->ingredients as $k=>$ingredient){
                $ingredient_ar[$ingredient]=['count'=>(float)$request->counts[$k]];
            }
            $stock->ingredients()->sync($ingredient_ar);
        }else{
            $stock->ingredients()->sync([]);
        }
        if(isset($request->cat) && isset($request->page)) {
            return redirect("/stock?type=$request->cat&page=$request->page")->with('status', 'Update!');
        } else {
            return redirect("/stock?type=$request->cat")->with('status', 'Update!');
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Stock $stock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Stock  $stock
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $stock = Stock::find($id);
        $stock->delete();
        return redirect('/stock')->with('status', 'Delete!');
    }


    public static function upload($file){
        $destinationPath = 'uploads';
        $fileName = "product".time().'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$fileName);
        $fileBase='/uploads/'.$fileName;
        return $fileBase;
    }

}
