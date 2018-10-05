<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     * @return mixed
     */
    protected $except_urls = [
        'api_v2/*'
      
    ];
    public function handle($request, Closure $next, $guard = null)
    {   
        if ($request->is('api_v2/*')){
            return $next($request);
        }
        if (Auth::guard($guard)->guest()) {
            if ($request->is('api/*') || $request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                if ($request->is('exhibitors/*')){
                    //return redirect()->guest('exhibitors/login');
                    return redirect('/exhibitors/login');
                }else{
                    return redirect()->guest('login');
                }
                
            }
        }
        return $next($request);
    }
}