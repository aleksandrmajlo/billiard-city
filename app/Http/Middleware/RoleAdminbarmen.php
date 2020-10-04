<?php


namespace App\Http\Middleware;
use Closure;

class RoleAdminbarmen
{
    public function handle($request, Closure $next)
    {
        if ($request->user()->hasRole('admin')||$request->user()->hasRole('barmen')) {
            return $next($request);
        } else {
            return redirect('/no-access');
        }
    }
}