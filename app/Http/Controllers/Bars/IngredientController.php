<?php

namespace App\Http\Controllers\Bars;

use App\Bars\Ingredient;
use App\Services\ActService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\Kofeinyiapparatcount;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pageCount = 20;

    public function index(Request $request)
    {
        $ingredients = Ingredient::orderBy('title', 'ASC')
            ->paginate($this->pageCount);

        if ($request->ajax()) {
            return response()->json(['ingredients' => $ingredients]);
        }

        $page = 1;
        if ($request->has('page')) {
            $page = $request->page;
        }
        $user = Auth::user();
        $ReadCount = false;
        if ($user->hasRole('admin')) {
            $ReadCount = true;
        }

        return view('bars/ingredient_index', [
            'page' => $page,
            'ingredients' => $ingredients,
            'ReadCount' => $ReadCount,
//            'kofeinyiapparatcount' => $kofeinyiapparatcount,
        ]);
    }

    // поиск
    public function search($q)
    {
        $ingredients = Ingredient::where('title', 'LIKE', "%$q%")
            ->get();
        return response()->json(['ingredients' => $ingredients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ingr = new Ingredient;
        $ingr->title = $request->title;
        $ingr->count = $request->count;
        $ingr->save();
        return response()->json(['success' => true]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ing = Ingredient::findOrFail($id);
        $ing->title = $request->data['title'];
        $ing->count = $request->data['count'];
        $ing->save();

        ActService::UpdateProductCount();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::findOrFail($id);
        $ingredient->delete();
        ActService::UpdateProductCount();
        return response()->json(['success' => true]);
    }
}
