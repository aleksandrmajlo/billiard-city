<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 13.11.2019
 * Time: 15:53
 */

namespace App\Http\Controllers\Docs;

use App\Acts\Act;
use App\CategoryStock;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;

class ActController extends Controller
{
    protected $pageCount = 20;

    public function index(Request $request)
    {

        if ($request->has('start') || $request->has('end') || $request->has('user_id')) {
            $acts = new Act();
            $acts = Act::orderBy('created_at', 'desc');
            if ($request->has('start') && !empty($request->start)) {
                $acts = $acts->where('created_at', '>=', $request->start . ' 00:00:00');
            }
            if ($request->has('end') && !empty($request->end)) {
                $acts = $acts->where('created_at', '<', $request->end . ' 23:59:59');
            }
            if ($request->has('user_id') && !empty($request->user_id)) {
                $acts = $acts->where('user_id', '=', $request->user_id);
            }
            $acts = $acts->orderBy('created_at', 'desc')->paginate($this->pageCount);

        } else {
            $acts = Act::orderBy('created_at', 'desc')
                ->paginate($this->pageCount);
        }
        $roleIds = [3];
        $users = \App\User::whereHas('roles', function ($q) use ($roleIds) {
            $q->whereIn('id', $roleIds);
        })->get();
        $filter_acts = Act::orderBy('created_at', 'desc')->get();

        return view('doc/acts', [
            'filter_acts' => $filter_acts,
            'acts' => $acts->appends($request->except('page')),
            'users' => $users,
            'this_url' => '/doc/act'
        ]);
    }

    // показать акт
    public function show($id, Request $request)
    {
        $act = Act::findOrFail($id);
        $showApparat = true;
        if ($request->has('title')) {
            $showApparat = false;
        }
        if ($request->has('type')) {
            $showApparat = false;
        }
        if ($request->has('cat') && $request->cat == '20') {
            $showApparat = true;
        } elseif ($request->has('cat') && $request->cat !== '20') {
            $showApparat = false;
        }


        // сортировка старт **************************************
        // desc - стрелка вверх по алфавиту вниз  а..я
        // asc - стрелка dybp по алфавиту я а
        $categoryDocSortOrder = session('categoryDocSortOrder', 'desc');
        if($categoryDocSortOrder=="desc"){
            $cats = CategoryStock::orderBy('title','desc')->get();
        }else{
            $cats = CategoryStock::orderBy('title','asc')->get();
        }
        $stocks = $act->stocks->toArray();;
        $stocks_res = [];
        $cat_title = [];
        foreach ($cats as $cat){
            $stocks_res[$cat->title]=[];
        }
        foreach ($stocks as $item) {
            $title = "";
            if (isset($cat_title[$item['categorystock_id']])) {
                $title = $cat_title[$item['categorystock_id']];
            } else {
                $cat = CategoryStock::select('title', 'id')->find($item['categorystock_id']);
                $cat_title[$cat->id] = $cat->title;
                $title = $cat->title;
            }
            $stocks_res[$title][]=[
                'title' => $item['title'],
                'category_id' => $item['categorystock_id'],
                'count' => $item['pivot']['count'],
                'category_title' => $title,
            ];
        }
        // сортировка end **************************************

        // тип отображение start **************************************
        $typeDocSortOrder=session('typeDocSortOrder', 'stocks');
        // тип отображение end **************************************

        return view('doc/act', [
            'act' => $act,
            'stocks' => $stocks_res,
            'cats' => $cats,
            'id' => $id,
            'showApparat' => $showApparat,
            'urlFilter' => '/doc/act/' . $id,
            'categoryDocSortOrder' => $categoryDocSortOrder,
            'typeDocSortOrder' => $typeDocSortOrder,
        ]);
    }

    // сравнение актов
    public function compare(Request $request)
    {
        $acts = Act::orderBy('created_at', 'desc')->get();
        $comp_results = [
            'ingredients' => [],
            'stocks' => [],
            'kofeinyi_apparat' => [],
        ];
        $act1 = false;
        $act2 = false;
        if ($request->has('act1') && $request->has('act2')) {

            $act1 = Act::find($request->act1);
            $act2 = Act::find($request->act2);
            if (count($act1->ingredients) > 0) {
                foreach ($act1->ingredients as $k => $ingredient) {
                    $count2 = 0;
                    if (isset($act2->ingredients[$k])) {
                        $count2 = $act2->ingredients[$k]->pivot->count;
                    }
                    $comp_results['ingredients'][$ingredient->id] = [
                        'title' => $ingredient->title,
                        'count' => [
                            $ingredient->pivot->count,
                            $count2
                        ]
                    ];
                }
            }
            if (count($act1->stocks) > 0) {
                foreach ($act1->stocks as $k => $stock) {
                    $count2 = 0;
                    if (isset($act2->stocks[$k])) {
                        $count2 = $act2->stocks[$k]->pivot->count;
                    }
                    $comp_results['stocks'][$stock->id] = [
                        'title' => $stock->title,
                        'cat' => $stock->categorySee->title,
                        'cat_id' => $stock->categorySee->id,
                        'count' => [
                            $stock->pivot->count,
                            $count2
                        ]
                    ];
                }
            }
            $kofeinyiapparat1 = 0;
            $kofeinyiapparat2 = 0;
            if ($act1->kofeinyiapparat) {
                $kofeinyiapparat1 = $act1->kofeinyiapparat->count;
            }

            if ($act2->kofeinyiapparat) {
                $kofeinyiapparat2 = $act2->kofeinyiapparat->count;
            }

            $comp_results['kofeinyi_apparat'] = [
                $kofeinyiapparat1,
                $kofeinyiapparat2
            ];
        }
        $cats = CategoryStock::all();

        $showApparat = true;
        if ($request->has('title')) {
            $showApparat = false;
        }
        if ($request->has('type')) {
            $showApparat = false;
        }
        if ($request->has('cat') && $request->cat == '20') {
            $showApparat = true;
        } elseif ($request->has('cat') && $request->cat !== '20') {
            $showApparat = false;
        }
        // тип отображение start **************************************
        $typeDocSortOrder=session('typeDocSortOrder', 'stocks');
        // тип отображение end **************************************
        return view('doc.compare', [
            'filter_acts' => $acts,
            'cats' => $cats,
            'comp_results' => $comp_results,
            'act1' => $act1,
            'act2' => $act2,
            'showApparat' => $showApparat,
            'urlFilter' => '/doc/compare?act1=' . $request->act1 . '&act2=' . $request->act2,
            'typeDocSortOrder' => $typeDocSortOrder,
        ]);
    }

    // экспорт
    public function export(Request $request)
    {

        $id = $request->id;
        $act = Act::findOrFail($id);
        $results = [];
        $product = trans('act.product');
        foreach ($act->stocks as $stock) {
            $results[] = [
                $stock->title,
                $stock->categorySee->title,
                $product,
                $stock->pivot->count
            ];

        }
        $ing = trans('act.ingredient');
        foreach ($act->ingredients as $ingredient) {
            $results[] = [
                $ingredient->title,
                "",
                $ing,
                $ingredient->pivot->count
            ];
        }

        /*
        $results[]=[
            trans('act.kofeinyi_apparat'),
            '',
            '',
            $act->kofeinyiapparat->count
        ];
        */

        Excel::create('Акт №' . $id, function ($excel) use ($results) {
            $excel->sheet('Лист 1', function ($sheet) use ($results) {
                $sheet->fromArray($results)->row(1, array(
                    trans('act.name'),
                    trans('act.cat'),
                    trans('act.type'),
                    trans('act.sklad'),
                ));
            });
        })->download('xls');
    }

    public function compareexport(Request $request)
    {

        if ($request->has('act1') && $request->has('act2')) {
            $comp_results = [
            ];

            $act1 = Act::find($request->act1);
            $act2 = Act::find($request->act2);


            $product = trans('act.product');
            if (count($act1->stocks) > 0) {
                foreach ($act1->stocks as $k => $stock) {
                    $count2 = 0;
                    if (isset($act2->stocks[$k])) {
                        $count2 = $act2->stocks[$k]->pivot->count;
                    }
                    $comp_results[] = [
                        'title' => $stock->title,
                        'cat' => $stock->categorySee->title,
                        $product,
                        $stock->pivot->count,
                        $count2,
                    ];
                }
            }

            $ing = trans('act.ingredient');
            if (count($act1->ingredients) > 0) {
                foreach ($act1->ingredients as $k => $ingredient) {
                    $count2 = 0;
                    if (isset($act2->ingredients[$k])) {
                        $count2 = $act2->ingredients[$k]->pivot->count;
                    }
                    $comp_results[] = [
                        'title' => $ingredient->title,
                        "",
                        $ing,
                        $ingredient->pivot->count,
                        $count2
                    ];
                }
            }

            /*
            $kofeinyi_apparat1 = 0;
            if ($act1->kofeinyiapparat) {
                $kofeinyi_apparat1 = $act1->kofeinyiapparat->count;
            }

            $kofeinyi_apparat2 = 0;
            if ($act2->kofeinyiapparat) {
                $kofeinyi_apparat2 = $act2->kofeinyiapparat->count;
            }
            $comp_results[] = [trans('act.kofeinyi_apparat'), "",  "",$kofeinyi_apparat1, $kofeinyi_apparat2];
            */

            Excel::create('Акт №' . $act1->id . ' №' . $act2->id, function ($excel) use ($comp_results, $act1, $act2) {
                $excel->sheet('Лист 1', function ($sheet) use ($comp_results, $act1, $act2) {
                    $sheet->fromArray($comp_results)->row(1, array(
                        trans('act.name'),
                        trans('act.cat'),
                        trans('act.type'),
                        trans('act.sklad') . " №" . $act1->change->id,
                        trans('act.sklad_smena') . " №" . $act2->change->id,
                    ));
                });
            })->download('xls');


        }
    }

    //установить порядок сортировки категорий
    public function setCategoryDocSortOrder(Request $request){
        session(['categoryDocSortOrder' => $request->sort]);
        return back();
    }
    // установить тип отображения
    public function setTypeDocSortOrder(Request $request){
        session(['typeDocSortOrder' => $request->type]);
        return back();
   }

}