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
        //dd([$model,$access]);
        //$userRole = Auth::user()->roles->pluck('name')->toArray();
        //$permission = auth()->user()->roles()->with('permissions.modules')->get()->pluck('permissions')->flatten();
        $permissions = auth()->user()->roles()->with('permissions.modules')->get();//->pluck('permissions');

        // foreach($permissions as $permission)
        // {
        //     dd($permission);
        // }
        //dd($permission->modules());
         // if(count($permission[0]))
        // {
        //     return $next($request);
        // }
        
        $user = auth()->user();
        if($user->hasAccess($model,$access))
        {
            return $next($request);
        }
       return error("Access denied!!!",[],'unauthenticated');
    }
}
