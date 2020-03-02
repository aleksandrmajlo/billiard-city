<?php

namespace App\Http\Controllers;

use App\Table;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tables = Table::orderBy('posiiton', 'desc')
            ->paginate(50);

        return view('table', compact('tables'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.table.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $table = new Table;
        $table->title = $request->title;
        $table->save();
        return redirect('/table')->with('status', 'Додано!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function show(Table $table)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $table = Table::find($request->id);
        $table->min_min = $request->min_max;
        $table->max_min = $request->max_min;
        $table->max_min_night = $request->max_min_night;
        $table->socket = $request->socket;
        $table->port = $request->port;
        $table->number = $request->number;

        if($request->image){
            $file = $request->file('image');
            $table->image=self::upload($file);
        }


        $table->save();
        return redirect('/table')->with('status', 'Оновлено');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Table $table)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Table  $table
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $table = Table::find($id);
        $table->delete();
        return redirect('/table')->with('status', 'Delete!');
    }



    public static function upload($file){
        $destinationPath = 'uploads';
        $fileName = "product".time().'.'.$file->getClientOriginalExtension();
        $file->move($destinationPath,$fileName);
        $fileBase='/uploads/'.$fileName;
        return $fileBase;
    }
    
}
