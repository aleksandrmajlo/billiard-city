<?php

namespace App\Http\Controllers;

use App\Stock;
use App\User;
use Illuminate\Http\Request;
use DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user', compact('users'));
    }


    public function create(Request $request)
    {
        if(isset($request->id)) {
            $users = User::where('id', $request->id)->firstOrFail();
        } else {
            $users  = '';
        }

        return view('admin.user.edit', compact('users'));
    }

    public function edit(Request $request)
    {
        if(isset($path)) {
            $path = $request->avatar->store('public/avatars');
        }


        $user = User::find($request->id);
        $user->name = $request->name;
        $user->email = $request->email;
        if($request->password) {
            $user->password = bcrypt($request->password);
        }


        if(isset($path)) {
            $user->avatar = $path;
        }
        $user->language = $request->language;
        $user->save();

        if($request->roles != '-') {
            $users = DB::update('update users_roles set role_id = '. $request->roles .' where user_id = ?', [$request->id]);
        }

        return redirect('/user')->with('status', 'Update!');
    }

    public function destroy($id)
    {
        $stock = User::find($id);
        $stock->delete();
        return redirect('/user')->with('status', 'Delete!');
    }
}
