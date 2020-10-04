<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Sms;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }
    //проверка кода
    public function checkCodeLogin(Request $request){
        if($request->has('code')&&$request->has('id_sms')&&$request->phone){

            // получить исключенные логины ***********************************
            $loginSelected=config('auth.loginSelected');
            if(isset($loginSelected[$request->phone])){
                if($loginSelected[$request->phone]==$request->code){
                    $user = User::where('phone', $request->phone)->select('id')->first();
                    if($user){
                        Auth::loginUsingId($user->id,true);
                        return response()->json([
                            'suc' => true,
                            'url'=>env('LOGIN_URL')
                        ]);
                    }else{
                        return response()->json([ 'err' => true], 404);
                    }
                }else{
                    return response()->json([ 'suc' => false]);
                }
            }
            // получить исключенные логины end **********************************
            $checkCode = Sms::where('id_sms', '=', $request->id_sms)->select('code')->orderBy('id', 'desc')->first();
            if ($checkCode->code == $request->code) {
                $user = User::where('phone', $request->phone)->select('id')->first();
                if($user){
                    Auth::loginUsingId($user->id,true);
                    return response()->json([ 'suc' => true]);
                }
                else{
                    return response()->json([ 'err' => true], 404);
                }
            }
            else{
                return response()->json([ 'suc' => false]);
            }
        }else{
            return response()->json([ 'err' => true], 404);
        }
    }
    // установка языка на странице входа
    public function setLng(Request $request){
        session(['lng' => $request->lng]);
        return Redirect::back();
    }

    // выход с логина
    public function logout(Request $request)
    {
        $this->guard()->logout();
        $request->session()->invalidate();
        return redirect(env('LOGOUT_URL'));
    }

}