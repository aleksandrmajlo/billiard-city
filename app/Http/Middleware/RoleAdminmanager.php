<?php

namespace App\Http\Middleware;

use Closure;

class RoleAdminmanager
{
    public function handle($request, Closure $next)
    {
        if ($request->user()->hasRole('admin')||$request->user()->hasRole('manager')) {
            return $next($request);
        } else {
            return redirect('/no-access');
        }
    }
}