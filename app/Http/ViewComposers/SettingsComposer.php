<?php

namespace App\Http\ViewComposers;

use App\Customer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SettingsComposer
{
    public function compose(View $view)
    {
          $SidebarToggle=session('SidebarToggle',false);
          $view->with('SidebarToggle',$SidebarToggle);
    }
}
