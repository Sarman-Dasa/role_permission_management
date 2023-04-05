<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$model,$access)
    {

        $user = auth()->user();
        if($user->hasAccess($model,$access))
        {
            return $next($request);
        }
       return error("Access denied!!!",[],'unauthenticated');
    }
}
