<?php

namespace App\Http\Controllers\Bars;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\CategoryStock;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $pageCount = 20;
    public function index(Request $request)
    {

        $categories = CategoryStock::orderBy('title', 'ASC')
            ->paginate($this->pageCount);
        if ($request->ajax()) {
            return response()->json(['categories' => $categories]);
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

        return view('bars/categories_index', [
            'page' => $page,
            'categories' => $categories,
            'ReadCount' => $ReadCount,
        ]);
    }


    // поиск
    public function search($q)
    {
        $categories = CategoryStock::where('title', 'LIKE', "%$q%")
            ->get();
        return response()->json(['categories' => $categories]);
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
        $categoryStock = new CategoryStock();
        $categoryStock->title = $request->title;
        $categoryStock->color = $request->color;
        $categoryStock->image = $request->image;
        $categoryStock->save();
        return response()->json(['success' => true]);
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
        $categoryStock = CategoryStock::findOrFail($id);
        $categoryStock->title = $request->data['title'];
        $categoryStock->color = $request->data['color'];
        $categoryStock->image = $request->data['image'];
        $categoryStock->save();
        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = CategoryStock::findOrFail($id);
        $category->delete();
        return response()->json(['success' => true]);
    }
}
