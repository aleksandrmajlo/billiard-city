<?php

namespace App\Http\Controllers\Docs;

use App\Acts\Purchaseinvoice;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Stock;
use App\Bars\Ingredient;
use Illuminate\Support\Facades\Auth;
use Excel;

class PurchaseinvoiceController extends Controller
{

    protected $pageCount=20;
    public function index(Request $request)
    {
        if ($request->has('start') || $request->has('end') || $request->has('user_id')) {
            $purchaseinvoices = new Purchaseinvoice();
            $purchaseinvoices = Purchaseinvoice::orderBy('created_at', 'desc');

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
            $purchaseinvoices = Purchaseinvoice::orderBy('created_at', 'desc')
                ->paginate($this->pageCount);
        }
        $roleIds = [3,2];
        $users = \App\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->get();
        return view('doc.purchaseinvoices', [
            'purchaseinvoices' => $purchaseinvoices->appends($request->except('page')),
            'users' => $users,
            'this_url' => '/doc/purchaseinvoice'
        ]);
    }
    public function show($id){
        $purchaseinvoice=Purchaseinvoice::findOrFail($id);
        return view('doc.purchaseinvoice',[
            'purchaseinvoice'=>$purchaseinvoice,
            'id'=>$id,
            'this_url' => '/doc/purchaseinvoice/'.$id
        ]);
    }

    public function create()
    {
        $stocks = Stock::has('ingredients', '<', 1)->get();
        $ingredients = Ingredient::all()->pluck('title', 'id');
        $kofeinyi_apparat_category_id=config('category.kofeinyi_apparat_category_id');
        return view('doc.purchaseinvoiceCreate', [
            'stocks' => $stocks,
            'ingredients' => $ingredients,
            'kofeinyi_apparat_category_id'=>$kofeinyi_apparat_category_id
        ]);
    }

    public function store(Request $request)
    {

        $user = Auth::user();
        $purchaseinvoice = new Purchaseinvoice;
        $purchaseinvoice->user_id=$user->id;
        $purchaseinvoice->save();
        if($request->has('id_ingredients')){
            foreach ($request->id_ingredients as $k=>$id_ingredient){
                $purchaseinvoice->ingredients()->attach($id_ingredient,[
                    'count'=>$request->count_ingredients[$k]]
                );
            }
        }
        if($request->has('id_stocks')){
            foreach ($request->id_stocks as $k=>$id_stock){
                $purchaseinvoice->stocks()->attach($id_stock,[
                    'count'=>$request->count_stocks[$k]
                ]);
            }
        }
        // обновляем продукты и ингадиенты
        \App\Services\PurchaseinvoiceService::UpdateStockIngr($purchaseinvoice->id);
        return redirect('doc/purchaseinvoice')->with('success', true);

    }


    public function export(Request $request){
        $id = $request->id;
        $purchaseinvoice=Purchaseinvoice::findOrFail($id);
        $results = [];
        $product = trans('act.product');
        foreach ($purchaseinvoice->stocks as $stock) {
            $results[] = [
                $stock->title,
                $stock->categorySee->title,
                $product,
                $stock->pivot->count
            ];

        }
        $ing=trans('act.ingredient');
        foreach($purchaseinvoice->ingredients as $ingredient){
            $results[] = [
                $ingredient->title,
                "",
                $ing,
                $ingredient ->pivot->count
            ];
        }


        Excel::create(trans('purchaseinvoice.title').' №' . $id, function ($excel) use ($results) {
            $excel->sheet('Лист 1', function ($sheet) use ($results) {
                $sheet->fromArray($results)->row(1, array(
                    trans('act.name'),
                    trans('act.cat'),
                    trans('act.type'),
                    trans('purchaseinvoice.sklad'),
                ));
            });
        }) ->download('xls');
    }
}
