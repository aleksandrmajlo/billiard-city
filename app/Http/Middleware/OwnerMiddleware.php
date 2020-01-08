<?php

namespace App\Http\Middleware;

use Closure;

class OwnerMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        $role = explode("|", $role);

            if(!$request->user()->hasRole($role[0])) {
                return redirect('/no-access');
            }

        return $next($request);
    }
}