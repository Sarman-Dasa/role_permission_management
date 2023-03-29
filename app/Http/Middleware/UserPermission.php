<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\equalTo;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {   
        $userRole = Auth::user()->roles->pluck('name')->toArray();
        $user = 
        dd($userRole);
        //dd($userRole[0]);

        if($userRole[0] == 'Hr')
        {
            return $next($request);
        }
       return error("Access denied!!!",[],'unauthenticated');
    }
}
