<?php

namespace App\Http\Controllers;

use App\Stock;
use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends Controller
{
    protected $pageCount = 20;

    public function index(Request $request)
    {
        if ($request->has('q')) {
            $users = User::where('name', 'like', '%' . $request->q . '%')
                ->orWhere('phone', 'like', '%' . $request->q . '%')->paginate($this->pageCount);
        } else {
            $users = User::orderBy('created_at', 'desc')
                ->paginate($this->pageCount);
        }
        return view('users.index', [
            'users' => $users,
            'urlFilter' => '/users'
        ]);
    }

    // создание пользователя
    public function store(Request $request)
    {
        $phone = $request->phone;
        $user = new User;
        $user->name = $request->name;
        $user->phone = $phone;
        $user->status = $request->status;
        $user->save();
        $user->roles()->attach($request->role_id);
        return back()->with('status', true);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', [
            'user' => $user,
        ]);
    }

    // обновление
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('users/' . $id)->withInput()->withErrors($validator);
        } else {
            $user = User::find($id);
            $user->name = $request->name;
            $user->phone = $request->phone;;
            $user->status = $request->status;
            $user->roles()->sync($request->role_id);
            $user->save();
            return back()->with('status', true);
        }
    }

    public function destroy($id)
    {
        $stock = User::find($id);
        $stock->delete();
        return redirect('/user')->with('status', 'Delete!');
    }
}
