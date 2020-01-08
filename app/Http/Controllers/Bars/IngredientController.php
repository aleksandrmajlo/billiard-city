<?php

namespace App\Http\Controllers\Bars;

use App\Bars\Ingredient;
use App\Services\ActService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ingredients = Ingredient::orderBy('title', 'ASC')
            ->get();
        $user=Auth::user();
        $ReadCount=false;
        if($user->hasRole('admin')){
            $ReadCount=true;
        }
        return view('bars/ingredients', [
            'ingredients' => $ingredients,
            'ReadCount'   =>$ReadCount
        ]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $ingr = new Ingredient;
        $ingr->title = $request->title;
        $ingr->count = $request->count;
        $ingr->save();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ing=Ingredient::find($id);
        $ing->count=$request->count;
        $ing->save();

        ActService::UpdateProductCount();
        return response()->json([
            'success' => true,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $ingredient = Ingredient::find($id);
        $ingredient->delete();
        return redirect()->back()->with('status', 'Delete!');
    }
}
