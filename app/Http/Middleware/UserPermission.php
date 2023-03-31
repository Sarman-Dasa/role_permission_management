<?php

namespace App\Http\Middleware;

use App\Models\Permission;
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
    public function handle(Request $request, Closure $next,$model,$access)
    {
    
    /*
        dd([$model, $access]);
        $userRole = Auth::user()->roles->pluck('name');
        $permissions = auth()->user()->roles()->with('permissions.modules')->get(); //->pluck('permissions');
        $permissions = auth()->user()->roles()->with('permissions.modules')->get()->pluck('permissions')->toArray();

        dd($permissions);
        if (count($permissions[0])) {
            return $next($request);
        }

        dd($user);
        if ($userRole[0] == "Super admin") {
            return $next($request);
        }
    */
        $user = auth()->user();
        if($user->hasAccess($model,$access))
        {
            return $next($request);
        }
       return error("Access denied!!!",[],'unauthenticated');
    }
}
