<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;



class CustomerController extends Controller
{
    protected $pageCount = 20;

    public function index(Request $request)
    {
        $customers = Customer::orderBy('created_at', 'desc')
            ->paginate($this->pageCount);
        if ($request->ajax()) {
            return response()->json(['customers' => $customers]);
        }
        $page = 1;
        if ($request->has('page')) {
            $page = $request->page;
        }
        return view(
            'customers.index',
            [
                'customers' => $customers,
                'page' => $page
            ]);
    }

    // показ пользователя
    public function show($id)
    {
        $customer = Customer::where('id', $id)->firstOrFail();
        $ordersum = Order::where('customer_id', $id)->sum('amount');
        return view(
            'customers.show',
            [
                'customer' => $customer,
                'ordersum' => number_format($ordersum, 2, ',', ' ')
            ]);
    }

    // поиск
    public function search($q)
    {
        $customers = Customer::where('name', 'LIKE', "%$q%")
            ->orWhere('surname', 'LIKE', "%$q%")
            ->orWhere('email', 'LIKE', "%$q%")
            ->orWhere('phone', 'LIKE', "%$q%")
            ->get();
        return response()->json(['customers' => $customers]);
    }

    // получить по ид для редактирования
    public function axiosReadCustomer($id)
    {
        $user=Auth::user();
        $role='manager';
        if($user->hasRole('admin')){
            $role='admin';
        }
        $customer = Customer::where('id', $id)->firstOrFail();
        return response()->json(['customer' => $customer,'role'=>$role]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (isset($request->id)) {
            $clients = Customer::where('id', $request->id)->firstOrFail();
        } else {
            $clients = '';
        }

        return view('admin.customers.create', compact('clients'));
    }


    public function store(Request $request)
    {

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->birthday = $request->birthday;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->description = $request->description;
        $customer->skidka = $request->skidka;
        $customer->skidka_bar = $request->skidka_bar;
        $customer->save();

        return response()->json(['success' => true]);
    }


    public function edit(Request $request)
    {

        if (isset($request->id)) {
            $clients = Customer::where('id', $request->id)->firstOrFail();
        } else {
            $clients = '';
        }
        return view('admin.customers.edit', compact('clients'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Customer $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $customer = Customer::find($request->id);
        $customer->name = $request->name;
        $customer->surname = $request->surname;
        $customer->birthday = $request->birthday;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->skidka = $request->skidka;
        $customer->skidka_bar = $request->skidka_bar;
        $customer->description = $request->description;
        $customer->save();
        return response()->json(['success' => true]);

    }


    public function destroy($id)
    {

        $customer = Customer::find($id);
        $customer->delete();
        return response()->json(['success' => true]);

    }

}
