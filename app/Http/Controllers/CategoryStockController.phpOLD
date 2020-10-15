<?php

namespace App\Http\Controllers;

use App\CategoryStock;
use Illuminate\Http\Request;

class CategoryStockController extends Controller
{
    public function index() {
        $categoryStocks = CategoryStock::all();
        return view('categorystock', compact('categoryStocks'));
    }

    public function edit(Request $request)
    {
        $categoryStock = CategoryStock::find($request->id);
        $categoryStock->title = $request->title;
        $categoryStock->color = $request->color;
        if($request->image){
            $file = $request->file('image');
            $categoryStock->image=self::upload($file);
        }
        $categoryStock->save();
        return redirect('/category')->with('status', 'Отредактированно');
    }

    public function store(Request $request) {
        $categoryStock = new CategoryStock();
        $categoryStock->title = $request->title;
        $categoryStock->color = $request->color;
        if($request->image){
            $file = $request->file('image');
            $categoryStock->image=self::upload($file);
        }
        $categoryStock->save();
        return redirect('/category')->with('status', 'Добавленно');
    }

    public function destroy($id)
    {
        $stock = CategoryStock::find($id);
        $stock->delete();
        return redirect('/category')->with('status', 'Delete!');
    }

    public static function upload($file){
        $destinationPath = 'uploads';
        $fileName = "cat".time().'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$fileName);
        $fileBase='/uploads/'.$fileName;
        return $fileBase;
    }
}
