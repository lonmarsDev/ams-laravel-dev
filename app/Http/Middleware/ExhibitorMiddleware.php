<?php

namespace app\Http\Middleware;

use App\Models\Organiser;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
class ExhibitorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    
  


    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }
    public function handle($request, Closure $next)
    {
        /*
         * If there are no organisers then redirect the user to create one
         * else - if there's only one organiser bring the user straight there.
         */
        
        /*if (!$request->is('exhibitors/*')){
           return response('Unauthorized.', 401);
        }*/

        if ($this->auth->check()) {
            //var_dump(Auth::user()->role);die();
            if(Auth::user()->role==1){
                $response = $next($request);
                return $response;
            }else{
                return response('Unauthorized.', 401);
            }
        }else{
            return new RedirectResponse( route( 'Exhibitorlogin' ) ); 
        }

        
      
        $response = $next($request);

        return $response;
    }
}
