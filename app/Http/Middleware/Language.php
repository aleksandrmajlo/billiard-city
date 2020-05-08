<?php


namespace App\Http\Middleware;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Session;


class Language
{

    public function handle($request, Closure $next)
    {
        if (session('lng') ) {
            App::setLocale(session('lng'));
        } else {
            App::setLocale(Config::get('app.locale'));
            $request->session()->put('lng', Config::get('app.locale'));
        }
        return $next($request);
    }
}