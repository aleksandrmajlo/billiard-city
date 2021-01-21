<?php

namespace App\Providers;


use App\Observers\StockObserver;
use App\Stock;

use Illuminate\Support\Facades\App;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use http\Env\Response;
use Illuminate\Http\Request;
use App\Http\ViewComposers\SettingsComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {

        View::composer(['layouts.app'], SettingsComposer::class);
        Stock::observe(StockObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
