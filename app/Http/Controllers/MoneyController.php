<?php

namespace App\Http\Controllers;

use App\Money;
use Illuminate\Http\Request;

class MoneyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $moneys = Money::orderBy('created_at', 'desc')
            ->paginate(50);
        return view('money', compact('moneys'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function show(Money $money)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        $money = new Money;
        $money->admin = - $request->admin;
        $money->admin_type = $request->type;
        $money->save();
        return redirect('/')->with('status', '+');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Money $money)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Money  $money
     * @return \Illuminate\Http\Response
     */
    public function destroy(Money $money)
    {
        //
    }
}
