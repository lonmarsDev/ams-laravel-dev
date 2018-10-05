<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     *
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {   
        
        if ($this->auth->check()) {
                        
            if(Auth::user()->role==1){

                if (!$request->is('exhibitors/*')) {
                    return response('Unauthorized.', 401);
                }else{
                    return new RedirectResponse( route( 'TheExhibitorDashboard' ) );
                }
            }else{
                return new RedirectResponse( route( 'showSelectOrganiser' ) );    
            }
            
            
        }

        return $next($request);
    }

}