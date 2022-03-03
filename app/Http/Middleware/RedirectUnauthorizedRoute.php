<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AssignSubmoduleRole;
use Illuminate\Support\Facades\Route;
use Session;

class RedirectUnauthorizedRoute
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard  
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        /*if((Route::currentRouteName() <> null) and (Auth::User()->user_type <> 1))
        {
            $nameRouteName = AssignSubmoduleRole::where('assign_submodule_role.roleID', Auth::User()->user_type)
                ->where('submodule.submodule_url', Route::currentRouteName())
                ->join('submodule', 'submodule.submoduleID', '=', 'assign_submodule_role.submoduleID')
                ->value('submodule_url');

            if($nameRouteName <> Route::currentRouteName())
            {
                return redirect()->route('home')->with('info', 'You are not authorized to visit the page !!!');
            }
        }*/
        //
        return $next($request);
    }
}
