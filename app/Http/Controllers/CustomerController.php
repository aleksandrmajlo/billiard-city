<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Customer::orderBy('created_at', 'desc')
            ->paginate(10);
        return view('customers', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(isset($request->id)) {
            $clients = Customer::where('id', $request->id)->firstOrFail();
        } else {
            $clients = '';
        }

        return view('admin.customers.create', compact('clients'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $customer = new Customer;
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->birthday = $request->birthday;
        $customer->email = $request->email;
        if(isset($request->phone)) {
            $customer->phone = $request->phone;
        }
        $customer->description = $request->description;

        if(isset($request->skidka)) {
            $customer->skidka = $request->skidka;
        }
        if(isset($request->skidka_bar)) {
            $customer->skidka_bar = $request->skidka_bar;
        }
        $customer->save();
        return redirect('/customers')->with('status', 'Додан!');
    }

    public function see($id)
    {
        $clients = Customer::where('id', $id)->firstOrFail();
        $orders = Order::where('customer_id', $id)->get();
        $ordersum = Order::where('customer_id', $id)->sum('amount');
        return view('customersee', compact('clients', 'orders', 'ordersum'));
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {

        if(isset($request->id)) {
            $clients = Customer::where('id', $request->id)->firstOrFail();
        } else {
            $clients = '';
        }

        return view('admin.customers.edit', compact('clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {

        $customer = Customer::find($request->id);
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->birthday = $request->birthday;
        $customer->email = $request->email;
        if(isset($request->phone)) {
            $customer->phone = $request->phone;
        }
        if(isset($request->skidka)) {
            $customer->skidka = $request->skidka;
        }
        if(isset($request->skidka_bar)) {
            $customer->skidka_bar = $request->skidka_bar;
        }


        $customer->description = $request->description;

        $customer->save();
        return redirect('/customers')->with('status', 'оновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $customer = Customer::find($id);
        $customer->delete();
        return redirect('/customers')->with('status', 'Видалено!');
    }
}
